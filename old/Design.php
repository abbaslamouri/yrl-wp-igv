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
if (!class_exists('File')) {
		
	class Design extends Dashboard{
        
		
		// // Allowed file types suported by this plugin
    // protected $allowed_file_extensions = ['xlsx', 'xls', 'csv', 'xlsm'];
    // 	// Define the maximum file size to be uploaded (from the form)
		// public $max_file_size = 3*1024*1024;	// 3 MB
		
		//Allowed design substrates 
		//public $allowed_substrates = ["Ge", "ZnS", "ZnSe", "Si", "IG5", "IG6", "AMTIR5"];

		//Allowed design types
		//public $allowed_designs = ["AR/AR", "DLC/AR", "AR", "DLC", "WITNESS"];
		
		// Allowed materials
		//public $allowed_materials = ["Ge", "ZnS", "ZnSe", "AL2O3", "YF3", "Y2O3", "DLC"];

		public function __construct() {
			
			// // Declare and set global variables
			// global $wpdb;

		
			// // Define the maximum file size to be uploaded (from the server)
			// $this->server_max_file_size = ini_get('upload_max_filesize');

			// // Define the maximum number of files that can be uploaded (from the server)
			// $this->max_file_upload = ini_get('max_file_uploads');
			
			// // Define the maximum post size to be uploaded (from the server)
			// $this->post_max_size = ini_get('post_max_size');
	
			// // Define the plugin upload directory path with trailing slashes (examle:  /home/yrlusc5/public_html/test/uploads/)
			// $this->upload_dir = wp_upload_dir()['path']."/";
			
			// // Declare database table
			// $this->db_table = "{$wpdb->prefix}designs";
		
			// // Declare upload directory
			// $this->file_path = $this->upload_dir;
			
			
		}	// END function __construct()
		
		
	




		/******************* Validate, Sanitize files data ************
		*/	
		public function validate_sanitize_files() {
			
			global $session;

			//Check if at least one file was selected (new records have $_POST['id'] = 0 )
			if (!$_FILES) {
				//if $_POST['id'] is 0 (new record) then a file is required.  Otherwise this is an edit and new file is not required
				if(intval($_POST['id']) == 0) {
					$session->errors[] = "Please select a file";
				}
			} else {

				//var_dump($_FILES['files']);die;

				// Select only the first file
				$this->file_name = wp_unique_filename($this->file_path, $_FILES['files']['name'][0]);
				$this->file_tmp = $_FILES['files']['tmp_name'][0];
				$this->file_error = $_FILES['files']['error'][0];
				$this->file_size = (intval($_FILES['files']['size'][0] )) ? $_FILES['files']['size'][0] : '';

				//Get server max file size, convert to bytes and compare to form max file size ( MAX_UPLOAD_FILE_SIZE )
				if (!is_numeric($this->max_file_size) || $this->max_file_size <= 0) {
					$session->errors[] = "Maximum upload file size is not a valid number. ". $this->max_file_size;
					//return false;
				} else {
					if ($this->max_file_size > self::convert_to_bytes($this->max_file_size)) {
						$session->errors[] = "Maximum size cannot exceed server limit for individual files: 
							" . self::convert_from_bytes($this->max_file_size);
						//return false;
					}
				}
				
				//Check that maximum post size is not exceeded ( post_max_size is usally 128 MB)
				if ($this->file_size  > File::convert_to_bytes($this->post_max_size)) {
					$session->errors[] = "Database insertion failed.  ". $this->file_size.": exceeds the total number of bytes you can post to the form" . self::convert_to_bytes($this->post_max_size);
						 //return false;	
				}
				
				// Make sure the file extension is allowed.  Alllowed extensions are defined at the begening of this file
				$this->file_type = $this->file_extension();
				if (!$this->file_type){
					$session->errors[] = $this->file_name.$this->file_type . "file type is not allowed. Allowed file extensions are  ".implode(", ", $this->allowed_file_extensions);
					//return false;
				}

			} // END if  (!$_FILES){

			if (!$session->errors) {
				return true;
			}
			
			return false;

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

		

		
		/******************************** Moves upload file to upload directory ***************************
		*/	
		protected function move_uploaded(){

			global $session;
			
			if (!is_dir($this->upload_dir) || !is_writable($this->upload_dir)) {
				$session->errors[] = "Database insertion failed.  ".$this->upload_dir." must be a valid, writable folder.";
				return false;
			} else {
				if (!move_uploaded_file($this->file_tmp, $this->upload_dir.$this->file_name)) {					
					$session->errors[] = 
					"Database insertion failed.  Could not upload $this->file_name. 
					Uploaad Error Code ( " . $this->file_error . ")."  ;
					return false;
				} else {
					$session->messages[] = "$this->file_name uploaded to $this->file_path successfully";
					return true;
				}		
			}
		}	// END protected function move_uploaded()
		
		
		
	
		
		/**************************************insert record into database***********************************
		*/
		protected function db_insert() {
			
			global $session;
				
			//Get Excel data and ready spreadsheet data for insertion into the database	
			$sheet = new Sheet;
			$excel_obj = Sheet::get_excel_object($this->file_path.$this->file_name);
			$sheet_names = $excel_obj->getSheetNames($this->file_path.$this->file_name);
			$this->sheet_count = count($sheet_names);
			
			//Insert into database
			$data = array(
				"file_name" 					=> $this->file_name, 
				"file_type" 					=> $this->file_type, 
				"file_size" 					=> $this->file_size, 
				"file_path" 					=> $this->file_path,
				"substrate"						=> $this->substrate,
				"design_type"				=> $this->design_type,
				"design_min"					=> $this->design_min,
				"design_max"					=> $this->design_max,
				"design_center"		=> $this->design_center,
				"design_note"				=> $this->design_note,
				"created_date"			=> $this->created_date,
				"updated_date"			=> $this->updated_date
			);
			
			//Insert record into the database
			if (!($this->id = Database::create($this->db_table, $data))) {
				$session->errors[] = "Database insertion failed.  Could not insert $file->file_name into the database";
				return false;
			} else {
				$session->messages[] = "$this->file_name (ID : $this->id) inserted into the databse table succesfully.";
				return $this->id;
			}	// END if (!($file->id = $database->insert_data))
			
			
		}	// END protected function db_insert()



		/********************** Checks file extensions against a black list declared at the beginijg of this file **********
		*/	
		protected function file_extension(){
			
			$file_parts = pathinfo($this->file_name);
			$file_extension = isset($file_parts['extension']) ? $file_parts['extension'] : '';
			if (in_array($file_extension, $this->allowed_file_extensions)) {
				return $file_extension;
			} else {
				return false;
			}
			
		} // END protected function check_filename_extension()



		/*******************************Converts file size to KB or MB ***********************************
		*/
		public static function convert_to_bytes($bytes){
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
		
		
			
	}	// END class File {	
		
		
}	// END if (!class_exists('File'))

