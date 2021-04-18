<?php

/**
* Interactive WP Google Visualization
*
* @package Interactive WP Google Visualization
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace Includes;

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// Create a class for the plugin if it does not exist.  the class object is instantiated in the main plugin file (axl-plugin.php)
if (!class_exists('File')) {	
	class File extends Dashboard { 

		// Allowed file types suported by this plugin
    protected $file_types = ["xlsx", "Xlsx", "xls", "Xls", "csv", "Csv", "xlsm", "Xlsm"];

    protected $max_files = 10000;
    	
    //Define the maximum file size to be uploaded (from the form)
		public $max_file_size = 3*1024*1024;	// 3 MB

		public function __construct() {
			
			// Declare upload directory
			$this->upload_path = wp_upload_dir()['path']."/";
			
			
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

			// Check if the file is already in the upload directory
			if ( ! file_exists ($file_path)) {
				return "File <strong>{$file_path}</strong> does not exist";
			}
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

				// Check file extension is allowed.  Alllowed extensions are defined at the begening of the File class
			if ( ! in_array($file_type, $this->file_types)) {
				return $this->file_extension()." file type is not allowed. Allowed file extensions are  ".implode(", ", $this->file_types).".  This file was not uploaded.";
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
        if (isset($roles) && !empty($roles)) {
          $sheet_data[$sheet_key]["roles"]  = $roles;
        }
				$sheet_data[$sheet_key]["data"] = $new_data;
				
			}

			return $sheet_data;

		} // END public function fetch_spreadsheet($file_path)







		// public function chart_options(){

		// 		// Instantiate the file class to validate and upload file
		// 	if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVOptions;

		// 		// Retreive settings options
		// 		$chart_options = $options->chart_options();

		// 		// Retreive files settings options
		// 		$files = (isset($chart_options["files"]))? $chart_options["files"] : array();

		// }



		/******************* Validate, Sanitize files data ************
		*/	
		public function validate_file($file) {

		
			
		

		

			

		
			
			

			return true;

		}	// END public function validate_sanitize_files()



		/********************** Checks file extensions against a list declared at the beginijg of this file **********
		*/	
		public function file_extension($file_name = null){

			if (!$file_name) return false;
			
			$file_parts = $this->file_parts($file_name);
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

