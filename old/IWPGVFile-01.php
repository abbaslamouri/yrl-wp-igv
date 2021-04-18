<?php

/**
* Interactive WP Google Visualization
*
* @package Interactive WP Google Visualization
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace IWPGV\Includes;

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// Create a class for the plugin if it does not exist.  the class object is instantiated in the main plugin file (axl-plugin.php)
if (!class_exists('IWPGVFile')) {	
	class IWPGVFile extends IWPGVDashboard { 

		// Allowed file types suported by this plugin
    	protected $file_types = ["xlsx", "Xlsx", "xls", "Xls", "csv", "Csv", "xlsm", "Xlsm"];

    	protected $max_files = 10000;
    	
    	//Define the maximum file size to be uploaded (from the form)
		public $max_file_size = 3*1024*1024;	// 3 MB
		
		//Allowed design substrates 
		//public $allowed_substrates = ["Ge", "ZnS", "ZnSe", "Si", "IG5", "IG6", "AMTIR5"];

		//Allowed design types
		//public $allowed_designs = ["AR/AR", "DLC/AR", "AR", "DLC", "WITNESS"];
		
		// Allowed materials
		//public $allowed_materials = ["Ge", "ZnS", "ZnSe", "AL2O3", "YF3", "Y2O3", "DLC"];

		public function __construct() {
			
			// Declare and set global variables
			global $wpdb;

		
			// Define the maximum file size to be uploaded (from the server)
			// $this->server_max_file_size = ini_get('upload_max_filesize');

			// // Define the maximum number of files that can be uploaded (from the server)
			// $this->max_file_upload = ini_get('max_file_uploads');
			
			// // Define the maximum post size to be uploaded (from the server)
			// $this->post_max_size = ini_get('post_max_size');
	
			// Define the plugin upload directory path with trailing slashes (examle:  /home/yrlusc5/public_html/test/uploads/)
			$this->upload_dir = wp_upload_dir()['path']."/";
			
			// Declare database table
			//$this->db_table = "{$wpdb->prefix}files";
		
			// Declare upload directory
			$this->file_path = $this->upload_dir;
			
			
		}	// END function __construct()



		// public function chart_options(){

		// 		// Instantiate the file class to validate and upload file
		// 	if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVOptions;

		// 		// Retreive settings options
		// 		$chart_options = $options->chart_options();

		// 		// Retreive files settings options
		// 		$files = (isset($chart_options["files"]))? $chart_options["files"] : array();

		// }



		public function extract_file($input = null) {


			if (!$input) return false;

		// get file components
			$file["name"] = array_shift($input["name"]);
			$file["size"] = array_shift($input["size"]);
			$file["tmp_name"] = array_shift($input["tmp_name"]);
			$file["type"] = array_shift($input["type"]);
			$file["error"] = array_shift($input["error"]);

			return $file;

		}



		/******************* Validate, Sanitize files data ************
		*/	
		public function validate_file($file) {

			// Initialize wp errors
			$errors = new \WP_Error;

			// Check if file exceeds the maximum file size in the File class
			if ($this->max_file_size < $file['size']) { 
				$error = $file["name"]." size ({$file["size"]}) exceeds allowed mximum file size of ".$this->convert_from_bytes($this->max_file_size).". This file was not uploaded.";
				return $error;
				//$errors->add("custom_max_file_size_sxceeded_{$key}", __($error, $this->plugin));
			}

			// Check file extension is allowed.  Alllowed extensions are defined at the begening of the File class
			if (! $this->file_extension($file["name"])){ 
				$error = "{$file["name"]} file type is not allowed. Allowed file extensions are  <strong>".implode(", ", $this->file_types)."</strong>.  This file was not uploaded.";
				return $error;
				// $errors->add("invalid_file_type", __($error, $this->plugin));
			}

			// Check if file errorf
			if ( $file['error'] !== 0) { 
				return $this->file_uplod_error_messages($file["error"]);
				//$errors->add($error, __($error, $this->plugin));
			}

			// Check if files exceed server MAX_UPLOAD_FILE_SIZE 
			if ($this->convert_to_bytes(ini_get('upload_max_filesize')) < $file['size']) {
				$error = $file["name"]." size ({$file["size"]}) exceeds server MAX_UPLOAD_FILE_SIZE of ".ini_get('upload_max_filesize')." This file was not uploaded.";
				return $error;
				// $errors->add("upload_max_filesize_exceeded", __($error, $this->plugin));
			}
			
			// Check if the file is already in the upload directory
			if (file_exists (wp_upload_dir()['path']."/".sanitize_file_name($file["name"]))) { 
				$error = ($file["name"] != sanitize_file_name($file["name"]))? "<strong>{$file["name"]}</strong> was converted to <strong>".sanitize_file_name($file["name"])."</strong>.  Converted file " : "<strong>{$file["name"]}</strong>";
				$error .= " exists in the upload folder";
				return $error;
				// $errors->add("file_exists", __($error, $this->plugin));
			}

			//if (empty($errors->errors)) return true;

			return true;

		}	// END public function validate_sanitize_files()





		/******************* Validate, Sanitize post data ************
		*/	
		public function validate_sanitize_post() {
			
			global $session;

			// Retreive design info and wp sanitize
			$this->substrate = sanitize_text_field($_POST['substrate']);
			$this->design_type = sanitize_text_field($_POST['design-type']);
			$this->design_min = floatval($_POST['design-min']);
			$this->design_max = floatval($_POST['design-max']);
			$this->design_center = floatval($_POST['design-center']);
			$this->design_note = sanitize_textarea_field($_POST['design-note']);
			$this->created_date = preg_replace("([^0-9/])", "", $_POST['created_date']);
			$this->updated_date = preg_replace("([^0-9/])", "", $_POST['updated_date']);

			if (!$this->substrate) {
				$session->errors[] = "Please select a substrate";
			}

			if (!$this->design_type) {
				$session->errors[] = "Please select a design";
				//return false;
			}

			if ($this->design_min ) {
				if ($this->design_min < .200 || $this->design_min > 20) {
					$session->errors[] = "Design Min must be between .2 and 20";
					//return false;
				}
			} else {
				$session->errors[] = "Design Min is required";
			}
			
			if ($this->design_max ) {
				if ($this->design_max < .200 || $this->design_max > 20) {
					$session->errors[] = "Design Max must be between .2 and 20";
					//return false;
				}
			}else {
				$session->errors[] = "Design Max is required";
			}

			if ($this->design_center ) {
				if ($this->design_center < .200 || $this->design_center > 20) {
					$session->errors[] = "Design center must be between .2 and 20";
					//return false;
				}
			}else {
				$session->errors[] = "Design Center is required";
			}
			
			if ($this->design_max && $this->design_min ) {
				if ($this->design_max < $this->design_min ) {
					$session->errors[] = "Design Max must be greater than Design Min";
					//return false;
				}
			}

			if ($this->design_center < $this->design_min || $this->design_center > $this->design_max ) {
					$session->errors[] = "Design Center must be greater than Design Min and less than Design Max";
					//return false;
				}

			if (!$session->errors) {
				return true;
			}
			
			return false;
			
		}	// END public function validate_sanitize_post()



		
		
		
		/**Upload files and saves the File class records in the database
		*
		*/	
		public function save() {
			
			global $session;

				// Validate, Sanitise and rename file
				//if(!$this->validate_sanitize_rename_file()) {
					//return false;
				//}
				
				// Upload file
				if (!$this->move_uploaded()) {
					$session->errors[]  = "$this->file_name could not be uploaded.";
					return false;
				}
				
				// Insert record into database
				if(!$this->db_insert()) {
					return false;
				} else {
					return $this->id;	
				}
				
		}	// END protected function save()




		/** Update file
		*/
		public function update() {
			
			global $session;

			//Insert into database
			$data = array (
				"substrate"						=> $this->substrate,
				"design_type"				=> $this->design_type,
				"design_min"					=> $this->design_min,
				"design_max"					=> $this->design_max,
				"design_center"		=> $this->design_center,
				"design_note"				=> $this->design_note,
				"created_date"			=> $this->created_date,
				"updated_date"			=> $this->updated_date
			);
			//update record into the database
			if (!Database::update($this->db_table, $data, ["id" => $this->id]) ) {
				$session->errors[] = "Database update failed.  Could not update $file->file_name database record";
				return false;
			} else {
				$session->messages[] = "Database record (ID : $this->id) updated succesfully.";
				return true;
			}	// END if (!($file->id = $database->insert_data))
			
			
		}	// END protected function db_insert()

		

		
		// /******************************** Moves upload file to upload directory ***************************
		// */	
		// protected function move_uploaded(){

		// 	global $session;
			
		// 	if (!is_dir($this->upload_dir) || !is_writable($this->upload_dir)) {
		// 		$session->errors[] = "Database insertion failed.  ".$this->upload_dir." must be a valid, writable folder.";
		// 		return false;
		// 	} else {
		// 		if (!move_uploaded_file($this->file_tmp, $this->upload_dir.$this->file_name)) {					
		// 			$session->errors[] = 
		// 			"Database insertion failed.  Could not upload $this->file_name. 
		// 			Uploaad Error Code ( " . $this->file_error . ")."  ;
		// 			return false;
		// 		} else {
		// 			$session->messages[] = "$this->file_name uploaded to $this->file_path successfully";
		// 			return true;
		// 		}		
		// 	}
		// }	// END protected function move_uploaded()
		
		
		
	
		
		/**************************************insert record into database***********************************
		*/
		protected function db_insert($file) {

			// Instantiate the database class
    		if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;
			
			// Insert record in databse
			$data = array(
				"name" => sanitize_file_name($file["name"]), 
				"type" => sanitize_text_field($file['type']), 
				"size" =>  intval($file['size']), 
				"file_path" =>  wp_upload_dir()['path']."/".sanitize_file_name($file["name"]),
				//"chart_type" =>  sanitize_text_field($_POST['chart-type']),
				//"chart_notes" =>  sanitize_textarea_field($_POST['chart-notes']),
				"created_date"	=>  preg_replace("([^0-9/])", "", $_POST['created-date']),
				"updated_date"	=>  preg_replace("([^0-9/])", "", $_POST['updated-date'])
			);

			//Insert record into the database
			return $db->create($data);
			
				
		}	// END protected function db_insert()



		/********************** Checks file extensions against a black list declared at the beginijg of this file **********
		*/	
		public function file_extension($file_path = null){

			if (!$file_path) return false;
			
			$file_parts = pathinfo($file_path);
			$file_extension = isset($file_parts['extension']) ? $file_parts['extension'] : '';
			if (in_array($file_extension, $this->file_types)) {
				return $file_extension;
			} else {
				return false;
			}
			
		} // END protected function check_filename_extension()



/********************** Checks file extensions against a black list declared at the beginijg of this file **********
		*/	
		public function file_parts($file_path = null){

			if (!$file_path) return false;
			
			return pathinfo($file_path);
			// $file_extension = isset($file_parts['extension']) ? $file_parts['extension'] : '';
			// if (in_array($file_extension, $this->file_types)) {
			// 	return $file_extension;
			// } else {
			// 	return false;
			// }
			
		} // END protected function check_filename_extension()



		/*******************************Converts file size to KB or MB ***********************************
		*/
		public function convert_to_bytes($bytes){
      $bytes = trim($bytes);
			$last = strtolower($bytes[strlen($bytes)-1]);
			if (in_array($last, array('g', 'm', 'k'))){
				switch ($last) {
					case 'g':
						$bytes = intval($bytes) * 1024;
					case 'm':
            $bytes = intval($bytes) * 1024;
					case 'k':
            $bytes = intval($bytes) * 1024;
				}
			}
			return $bytes;


		}  // public static function convert_to_bytes($val){
			
			
			
		/********************************Converts file size from bytes to KB or MB *****************************
		*/	
		public static function convert_from_bytes($bytes){
			if ($bytes < 1024) {
				return $bytes . ' bytes';	
			}
			$bytes /= 1024;
			if ($bytes > 1024) {
				return number_format($bytes/1024, 0) . ' MB';
			} else {
				return number_format($bytes, 0) . ' KB';
			}
		} // END public static function convert_from_bytes($bytes)




		function file_uplod_error_messages ($code) {
			
			$file_upload_errors = array(
	    		0 => 'There is no error, the file uploaded with success',
	   		1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
	    		2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
	    		3 => 'The uploaded file was only partially uploaded',
	    		4 => 'No files to uploaded.  Please select files',
	    		6 => 'Missing a temporary folder',
	    		7 => 'Failed to write file to disk.',
	    		8 => 'A PHP extension stopped the file upload.',
			);

			if ($code < 0 || $code > 8) {
				return "No idea what went wrong";
			}

			return $file_upload_errors[$code];
		}
		
		
			
	}	// END class File {	
		
		
}	// END if (!class_exists('File'))

