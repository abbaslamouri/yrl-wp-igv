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








		/**
		* formats spreadsheet
		* @author  Abbas Lamouri
		* @param   string $file_path (spreadsheet file name)
		* @return  array $data (both raw and formatted Spreadsheet cols and rows data)
		* @version  0.1
		*/
		public function fetch_spreadsheet($file_path) {
		

			$sheet_data = [];

			// Instantiate the File class
			//if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $this = new IWPGVFile;

			// Check if the file is already in the upload directory
			if ( ! file_exists ($file_path)) {
				return "File <strong>{$file_path}</strong> does not exist";
			}
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

				// Check file extension is allowed.  Alllowed extensions are defined at the begening of the File class
			if ( ! in_array($file_type, $this->file_types)) {
				$error = $this->file_extension()." file type is not allowed. Allowed file extensions are  ".implode(", ", $this->file_types).".  This file was not uploaded.";
				//$this->errors->add("invalid_file_type", __($error, $this->plugin));
				return $error;
			}

			// Create a new Reader of the type that has been identified
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

			// Advise the Reader that we only want to load cell data (no fprmating)
			$reader->setReadDataOnly(true);

			// Load $input_file_path to a Spreadsheet Object
			$spreadsheet = $reader->load($file_path);

			// Identify all sheets by name in the spreasheet
			$sheet_names = $spreadsheet->getSheetNames();

			// Loop through all sheets
			foreach ($sheet_names as $sheet_key => $sheet_name) {
				
				// Convert data in each spreadsheet to array
				$data = $spreadsheet->getSheetByName($sheet_name)->toArray(
					NULL,        // Value that should be returned for empty cells
					TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
					TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
					TRUE         // Should the array be indexed by cell row and cell column
				);


				//echo json_encode($data);die;

				// Eliminate all empty columns and rows
				// Columns are considred empty if a data type is not supplied
				// Rows are considered empty if all the columns in the row are null;
				$new_data = array();
				foreach ($data as $row_index => $row) {
					$new_row = array();
					foreach ($row as $col_index => $cell ) {
						if (isset($data[2][$col_index]) && $data[2][$col_index])
							$new_row[$col_index]= $cell;
					
					}

					if (!empty($new_row)) {
						$new_data[$row_index] = $new_row;
					}
				}

				// echo "<pre>";var_dump($new_data);die;


				// Retreive labels
				$labels = array_shift($new_data);

				// Retreive raw_data type (second row
				$data_types = array_shift($new_data);

				// extract roles if first element of first row is domain
				if ( array_values($new_data[0])[0] == "domain")
				$roles = array_shift($new_data);

				$sheet_data[$sheet_key]["sheetName"]  = $sheet_name;
				$sheet_data[$sheet_key]["labels"] = $labels;
				$sheet_data[$sheet_key]["dataTypes"]  = $data_types;
				$sheet_data[$sheet_key]["roles"]  = $roles;
				$sheet_data[$sheet_key]["data"] = $new_data;
				
				

			}


			//f (isset($sheetId))
				return $sheet_data;

			//return $sheet_data[$sheetId];


		} // END public function fetch_spreadsheet($file_path)







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

			// Check file name
			if ( ! isset($file["name"]) || ! $file["name"]) { 
				return $error = "Cannot accept a file without a file name.";
			}	// Check file extension is allowed.  Alllowed extensions are defined at the begening of the File class
			
			if ( isset($file["name"]) && ! $this->file_extension($file["name"])){ 
				$error = "{$file["name"]} file type is not allowed. Allowed file extensions are  <strong>".implode(", ", $this->file_types)."</strong>.";
				return $error;
				// $errors->add("invalid_file_type", __($error, $this->plugin));
			}

			// Check if file exceeds the maximum file size in the File class
			if ( isset($file["size"]) && $this->max_file_size < $file['size']) { 
				$error = $file["name"]." size ( " . $this->convert_from_bytes( $file["size"])." ) exceeds allowed mximum file size of ".$this->convert_from_bytes($this->max_file_size).".";
				return $error;
				//$errors->add("custom_max_file_size_sxceeded_{$key}", __($error, $this->plugin));
			}

			// Check if file errorf
			if ( isset($file["error"]) && $file['error'] !== 0) { 
				return $this->file_uplod_error_messages($file["error"]);
				//$errors->add($error, __($error, $this->plugin));
			}

			// Check if files exceed server MAX_UPLOAD_FILE_SIZE 
			if ( isset($file["size"]) && $this->convert_to_bytes(ini_get('upload_max_filesize')) < $file['size']) {
				$error = $file["name"]." size ({$file["size"]}) exceeds server MAX_UPLOAD_FILE_SIZE of ".ini_get('upload_max_filesize').".";
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

			return true;

		}	// END public function validate_sanitize_files()



		/********************** Checks file extensions against a list declared at the beginijg of this file **********
		*/	
		public function file_extension($file_path = null){

			if (!$file_path) return false;
			
			$file_parts = $this->file_parts($file_path);
			$file_extension = isset($file_parts['extension']) ? $file_parts['extension'] : '';
			if (in_array($file_extension, $this->file_types)) {
				return $file_extension;
			} else {
				return false;
			}
			
		} // END protected function check_filename_extension()



/********************** Checks file extensions against a list declared at the beginijg of this file **********
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

