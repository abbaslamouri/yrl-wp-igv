<?php

/**
* Interactive WP Google Visualization
*
* @package Interactive WP Google Visualization
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace YRL_WP_IGV\Includes;

// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');

// Declare main class if it does not exist
if (!class_exists('IWPGVDashboard')) { 
	class IWPGVDashboard {
		public $name = IWPGV_NAME; // name of the plugin
		public $title = IWPGV_TITLE; // title of the plugin
		public $path = IWPGV_PATH; // Path to the plugin directory
		public $url = IWPGV_URL; // URL to the plugin directory
		public $base = IWPGV_BASE; // represents plugin-dir/plugin-file
		public $version = "1.0.0"; // plugin version
		public $prefix = IWPGV_PREFIX; // prefix (iwpgv)
		public $plugin = IWPGV_PLUGIN; // plugin (iwpgv)
		protected $allowed_file_extensions = ['xlsx', 'xls', 'csv', 'xlsm']; // Allowed file types suported by this plugin
		public $max_file_size = 3 * 1024 * 1024; // Define the maximum file size to be uploaded (3 MB)
		public $chart_types = array("LineChart" => "Line", "bar" => "Bar", "column" => "Column", "PieChart" => "Pie"); // Possible cahrt types
		public $allowed_data_types = array("string", "number", "boolean", "datetime", "timeofday"); // Possible cahrt types
		public $file_upload_nonce = "file_upload_nonce";
		public $file_select_nonce = "file_select_nonce";
		public $get_chart_nonce = "get_chart_nonce";
		public $chart_list_nonce = "chart_list_nonce";
		public $chart_save_nonce = "chart_save_nonce";
		public $chart_update_nonce = "chart_update_nonce";

		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {
			
			// // Declare and set global variables
			// global $wpdb;
			// 
			//var_dump(class_exists("IWPGV\\Includes\\IWPGVDatabase"));die;
			//


			
			
			// // Declare database table
			// $db->db_table = "{$wpdb->prefix}{$this->prefix}_charts";
			
			// Save plugin options and do other activation tasks
			register_activation_hook($this->base, array($this, 'activate'));
			register_deactivation_hook($this->base, array($this, 'deactivate'));

			// Register styles and scripts
			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

			// Add ajax file upload capability. The hook tells javascript which method to process. The hook is either embeded in the form or passed to javascript using wp_localise_script
			add_action( "wp_ajax_{$this->prefix}_file_upload_hook", array($this, 'file_upload_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_file_upload_hook", array($this, 'file_upload_callback' )); // need this to serve non logged in users

			// Add ajax file delete capability. The hook tells javascript which method to process. The hook is either embeded in the form or passed to javascript using wp_localise_script
			add_action( "wp_ajax_{$this->prefix}_file_delete_hook", array($this, 'file_delete_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_file_delete_hook", array($this, 'file_delete_callback' )); // need this to serve non logged in users

				// Add ajax chart file selection capability
			add_action( "wp_ajax_{$this->prefix}_file_select_hook", array($this, 'file_select_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_file_select_hook", array($this, 'file_select_callback' )); // need this to serve non logged in users

			// Add ajax chart list capability
			add_action( "wp_ajax_{$this->prefix}_get_chart_hook", array($this, 'get_chart_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_get_chart_hook", array($this, 'get_chart_callback' )); // need this to serve non logged in users
 
 
			// Add ajax chart list capability
			add_action( "wp_ajax_{$this->prefix}_chart_list_hook", array($this, 'chart_list_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_chart_list_hook", array($this, 'chart_list_callback' )); // need this to serve non logged in users

			// Add ajax chart update capability
			add_action( "wp_ajax_{$this->prefix}_chart_save_hook", array($this, 'chart_save_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_chart_save_hook", array($this, 'chart_save_callback' )); // need this to serve non logged in users

			// Add ajax chart update capability
			add_action( "wp_ajax_{$this->prefix}_chart_update_hook", array($this, 'chart_update_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_chart_update_hook", array($this, 'chart_update_callback' )); // need this to serve non logged in users

		
			//Register admin menus
			add_action('admin_menu', array($this, 'admin_menu_register'));
			
			// Add plugin settings link
			add_filter("plugin_action_links_" . $this->base, array($this, 'add_plugin_action_links'));
			
			// Display admin ntices
			add_action('admin_notices', array($this, 'add_admin_notices'));
			
			// Filter admin bar menu
			add_action('wp_before_admin_bar_render', array($this, 'my_admin_bar'));
			
			// Filters the “Thank you” text displayed in the Wordpress admin dashboard footer text
			add_filter('admin_footer_text', array($this, 'admin_footer_text'));
			
			// Intialize other initialization tidbits
			add_action('plugins_loaded', array($this, 'plugins_loaded'));
			
			// Set initialization tidbits
			add_action('admin_init', array($this, 'admin_init'));
			
			// List table reorder ajax handler
			add_action("wp_ajax_" . $this->prefix . "_list_reorder", array($this, 'list_reorder'));

			// Hide admin bar
			add_filter('show_admin_bar', '__return_true');

				// Redirects all wp-login.php requests to login page when login fails.  "wp_login_failed" hook fires if both username and password have a value but the credentilas fail
			add_action( 'wp_login_failed', function () {
				wp_redirect( home_url("login").'?login=failed' );
				exit;
			});

		} // END public function __construct()




		/**
		 * Plugin options
		 * @return Array Plugin options
		 */
		public function plugin_options() {
			
			return array('version' => $this->version);

		} // END  public function plugin_options() {




		/**
		* File upload ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function file_upload_callback() {

			// Ajax comes with two arrays $_POST and $_FILES
			//echo json_encode($_FILES);wp_die();
			// echo json_encode($_POST);wp_die();
			
			// Initialize wp errors
			$errors = new \WP_Error;
			$success = new \WP_Error;
			
			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;

			// Bail if request is not POST, not an ajax request or does not come the file upload form
			if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_file_upload_hook") 
				$errors->add("invalid_file_upload_ajax_request", __("This is not a valid ajax file upload request", $this->plugin));
			
			// verify file upload nonce
			if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->file_upload_nonce}"  )) 
				$errors->add("invalid_{$this->file_upload_nonce}", __("An invalid nonce was submited", $this->plugin));

			// Check if no files were selected
			if (empty($_FILES)) 
				$errors->add("file_missing", __("File missing, please select a file", $this->plugin));

			// Continue if no errors
			if (empty($errors->errors)) {

				// Extract file from $_FILES
				$file = $file_instance->extract_file($_FILES["files"]);

				//Check if there is a file and is an array
				if ($file && is_array($file)) {

					// Validate file (validate_file() returns either true or WP/Error)
					$results = $file_instance->validate_file($file);

					// If validate_file() returns WP/Error
					if (! is_wp_error($results)) {

						// Check if wp_handle_upload function exists
						if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

						// Attemtto upload file
						$movefile = wp_handle_upload( $file, array('test_form' => false));
						
						// If file upload successful
						if ( $movefile || ! isset( $movefile['error'] ) ) {

							// Fetch spreadsheet data to check if data types for each sheet are valid
							$spreadsheet = $this->fetch_spreadsheet_data(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));


							// Loop through all the sheets of each spreadsheet to check if colum data type is valid
							foreach ($spreadsheet["sheet-data"] as $key => $sheet) {

								
								// loop through the sheet data types
								foreach($sheet["data-types"] as $data_type_key => $value ) {

									if (isset ($value) && ! in_array($value, $this->allowed_data_types)) {
										$invalid_file_type = true;
										$sheet_name = $sheet["sheet-name"];
										$sheet_column = $data_type_key;
										break 2;
									}
								}
							}


							// If all data types are valid
							if (! isset($invalid_file_type)) {

								// Attempt to insert record into the database
								$file_id = $file_instance->db_insert($file);

								// If database insertion successful
								if ( $file_id) {

									// Fetch file selection foem and sheet selection form
									//$file_select_form = $this->compose_file_select_option($file_id);

									// Fetch file selection foem and sheet selection form
									$sheet_select_options = $this->compose_sheet_select_options($spreadsheet, $file_id);

									// Assemble success message
									$message = "{$file["name"]} is valid.\n";
									$message .= ($file["name"] != sanitize_file_name($file["name"]))? "This file was converted to ".sanitize_file_name($file["name"]) . " and was " : "This file was ";
									$message .= "uploaded successfully.  File ID ({$file_id}) inserted into the database table succesfully.\n";
									$message .= "File ID ({$file_id}) inserted into the database table succesfully.";
									$success->add("upload_database_insertion_successful", __($message, $this->plugin));

								// Database insertion failed	
								} else {
									$error = "Database insertion failed.  Could not insert {$file['name']} into the database";
									$errors->add("database_record_insertion_error", __($error, $this->plugin));
									wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
								}
							
							// If data type is invalid
							} else {

								$error = "File: ". sanitize_file_name($file["name"]) . " Sheet {$sheet_name} column {$sheet_column} is not a valid data type.  This file was not uploaded.";
								$errors->add("invalid_data_type", __($error, $this->plugin));
							}
								
						// File upload failed
						} else {
							$errors->add("file_upload_failed", __($movefile['error'], $this->plugin));
						}
					
					// If file does not validate
					} else {
						$errors = $results;
					}
				// If for some reason there is no file or the file is not an array
				} else {
					$error = " extract_file() mothod failed to return a file or an array";
					$errors->add("extract_file_failed", __($error, $this->plugin));
				}
						
			}

			// Compose message
			$message = $this->compose_message($errors, $success);

			// Compose response
			$response = array(
				"file-name" => $file["name"],
				"file-id" => $file_id,
				"spreadsheet" => $spreadsheet,
				"sheet-select-options" => $sheet_select_options,
				"file-select-option" => "<option value='{$file_id}' selected >{$file["name"]}</option>",
				"message" => $message,
				"post" =>$_POST
			);

			// return ajax data
			echo json_encode($response);
			wp_die();

		}  // END public function contact_form_process() {





		/**
		* File upload ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function file_select_callback() {

			// Ajax comes with two arrays $_POST and $_FILES
			//echo json_encode($_POST);wp_die();

			$errors = new \WP_Error;
			$success = new \WP_Error;

			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;

			// Bail if request is not POST, not an ajax request or does not come the file upload form
			if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_file_select_hook") 
				$errors->add("invalid_file_select_ajax_request", __("This is not a valid ajax file select request", $this->plugin));
			
				// verify file upload nonce
			if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->file_select_nonce}"  )) 
				$errors->add("invalid_{$this->file_select_nonce}", __("An invalid nonce was submited", $this->plugin));

			// Retrieve file
			$file = $db->find_by_id($_POST["file-id"]);
  			
			// If file exists
  			if ($file) {

				// Fetch spreadsheet data 
				$spreadsheet = $this->fetch_spreadsheet_data(wp_upload_dir()['path']."/".sanitize_file_name($file->name));

				// Fetch file selection foem and sheet selection form
				$sheet_select_options = $this->compose_sheet_select_options($spreadsheet, $file->id);

  			} else {
				
				$errors->add("file_not_Found", __("No file with ID = {$_POST["file-id"]} was found", $this->plugin));
			}
		
			// Compose message
			$message = $this->compose_message($errors, $success);

		// Compose response
			$response = array(
				"file-name" => $file->name,
				"file-id" => $file->id,
				"spreadsheet" => $spreadsheet,
				"sheet-select-options" => $sheet_select_options,
				//"file-select-form" => $file_select_form,
				"message" => $message,
				"post" =>$_POST
			);

			// return ajax data
			echo json_encode($response);
			wp_die();

		}  // END public function contact_form_process() {





		/**
		 * Composes file select form
		 * @return Array         Array contains a file selection from (String) and a sheet selection form (String)
		 */
		public function compose_file_select_form ($file_id)  {

			// // Instantiate the database class
			// if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;
				
			// // Retreive all files and compose file selection form
			// $files = $db->find_all($db->db_table);
			// if ($files) {
			// 	//$file_select_form = "<form id='file-selection-form'>";
			// 	$file_select_form = "<select id='select-file' name='".$this->prefix."_dashboard[select-file]'>";
			// 	$file_select_form .= "<option value='' ". selected( $file_id, "", false)."  >Select a File</option>";
			// 	foreach ($files as $file) {
			// 		$file_select_form .= "<option value='{$file->id}' ". selected( $file_id, $file->id, false)." >{$file->name}</option>";
			// 	}
			// 	$file_select_form .= "</select>";
			// 	//$file_select_form .= "</form>";				
			// }

			// return $file_select_form;

		}






		/**
		 * Composes sheet select forms to be displayed on the file select form
		 * @param  array $sheeets Array of sheets from a given spreadsheet file
		 * @return Array         Array contains a file selection from (String) and a sheet selection form (String)
		 */
		public function compose_sheet_select_options ($spreadsheet, $file_id)  {

			//echo json_encode($spreadsheet);wp_die();

			// Compose sheet selection form
			//$sheet_select_form = "<form id ='sheet-selection-form'>";
			//$sheet_select_form .= "<div class ='sheet'>";
			//$sheet_select_form .= "<select id='sheet-select' name='".$this->prefix."_dashboard[sheet-select]' >";
			//$sheet_select_form .= "<option value=''>Select a Sheet</option>";
			foreach ($spreadsheet["sheet-names"] as $sheet_key => $sheet_name) {
				$sheet_select_form .= "<option value='".(intval($sheet_key))."'>{$sheet_name}</option>";
			}
			//$sheet_select_form .= "</select>";
			//$sheet_select_form .= "</div>";
			//$sheet_select_form .= "</form>";

			return $sheet_select_form;

		}





		/**
		 * Composes error and success messsages
		 * @param  WP/Error $errors  wp error object
		 * @param  WP/Error $success wp error object (success)
		 * @return string          error and success message string
		 */
		public function compose_message ($errors = null, $success = null)  {

				// Prepare error output
			if ($errors && !empty($errors->errors)) {
				$message = "<div class='notice notice-error is-dismissible'>";
				foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $output) {
					$message .= "<p>{$output}</p>";
				}
				$message .= "</div>";
			}
			
			// Prepare success output
			if ($success && !empty($success->errors)) {
				$message .= "<div class='notice notice-success is-dismissible'>";
				foreach (array_combine($success->get_error_codes(), $success->get_error_messages()) as $code => $output) {
					$message .= "<p>{$output}</p>";
				}
				$message .= "</div>";
	
			}

			return $message;

		} // END function compose_message ($errors, $success)  {







		/**
		* File upload ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function chart_save_callback() {

			// Ajax comes with two arrays $_POST and $_FILES
			// echo json_encode($_POST);wp_die();

			$errors = new \WP_Error;
			$success = new \WP_Error;


			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;

			// Bail if request is not POST, not an ajax request or does not come the file upload form
			if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_save_chart_hook") 
				$errors->add("invalid_save_chart_ajax_request", __("This is not a valid ajax get chart request", $this->plugin));
			
				// verify file upload nonce
			if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->save_chart_nonce}"  )) 
				$errors->add("invalid_{$this->get_chart_nonce}", __("An invalid nonce was submited", $this->plugin));

			// verify if a file was selected (file id submitted)
			if (! $_POST["file-select"] ) {
				$errors->add("file_id_missing", __("No file selected.  Please slect a file", $this->plugin));
			} else {

				// verify if a sheet was selected (sheet id submitted)
				if ( $_POST["sheet-select"] == "") {
					$errors->add("sheet_id_missing", __("No sheet selected.  Please slect a sheet for this file", $this->plugin));
				} else {

					// Retrieve shart options
					$charts = get_option("{$this->prefix}_dashboard");

					// If no charts, initialize array
					if ( empty($charts) ) $charts = [];
			
					$chart_id =  "{$_POST["file-select"]}-{$_POST["sheet-select"]}";

					$output = [];

					foreach($_POST as $key => $value ) {
						//if (isset($_POST[$field["id"]])) {
							$output[$key] = $value;
						//}
					}

					$charts[$chart_id] = $output;

					// If the option update fails
					if (update_option("{$this->prefix}_dashboard", $charts)) {

						$errors->add("option_update_failed", __("Nt able to update option {{$this->prefix}_dashboard", $this->plugin));
					
					} else {

						// Assemble success message
						$success->add("option_update_successful", __("option update successful", $this->plugin));
					}

				}
			}
		
			// Compose message
			$message = $this->compose_message($errors, $success);

			// Compose response
			$response = array(
				"file-name" => $file->name,
				"file-id" => $file->id,
				"spreadsheet" => $spreadsheet,
				//"sheet-select-form" => $sheet_select_form,
				//"file-select-form" => $file_select_form,
				"message" => $message,
				"post" =>$_POST
			);

			// return ajax data
			echo json_encode($response);
			wp_die();
			

		}  // END public function contact_form_process() {








		/**
		* File upload ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function get_chart_callback() {

			// Ajax comes with two arrays $_POST and $_FILES
			// echo json_encode($_POST);wp_die();

			$errors = new \WP_Error;
			$success = new \WP_Error;


			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;

			// Bail if request is not POST, not an ajax request or does not come the file upload form
			if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_get_chart_hook") 
				$errors->add("invalid_get_chart_ajax_request", __("This is not a valid ajax get chart request", $this->plugin));
			
				// verify file upload nonce
			if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->get_chart_nonce}"  )) 
				$errors->add("invalid_{$this->get_chart_nonce}", __("An invalid nonce was submited", $this->plugin));

			// verify if a file was selected (file id submitted)
			if (! ($_POST["file-id"])) {
				$errors->add("file_id_missing", __("No file selected.  Please slect a file", $this->plugin));
			} else {

				// Retrieve file
				$file = $db->find_by_id($_POST["file-id"]);

				// If file exists
  				if ($file) {

					// Fetch spreadsheet data 
					$spreadsheet = $this->fetch_spreadsheet_data(wp_upload_dir()['path']."/".sanitize_file_name($file->name));

					// Fetch file selection foem and sheet selection form
					// $sheet_select_form = $this->compose_sheet_select_form($spreadsheet, $file->id);

  				} else {
				
					$errors->add("file_not_Found", __("No file with ID = {$_POST["file-id"]} was found", $this->plugin));
				}
			}
		
			// Compose message
			$message = $this->compose_message($errors, $success);

			// Compose response
			$response = array(
				"file-name" => $file->name,
				"file-id" => $file->id,
				"spreadsheet" => $spreadsheet,
				//"sheet-select-form" => $sheet_select_form,
				//"file-select-form" => $file_select_form,
				"message" => $message,
				"post" =>$_POST
			);

			// return ajax data
			echo json_encode($response);
			wp_die();
			

		}  // END public function contact_form_process() {

























		/**
		* Chart view ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function chart_list_callback() {


			// Ajax comes with two arrays $_POST and $_FILES
			// echo json_encode($_POST);wp_die();

			$errors = new \WP_Error;
			$success = new \WP_Error;

			// Instantiate the file class to validate and upload file
			//if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;

			// Bail if request is not POST, not an ajax request or does not come the file upload form
			if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_chart_list_hook") 
				$errors->add("invalid_chart_list_ajax_request", __("This is not a valid ajax chart list request", $this->plugin));
			
				// verify file upload nonce
			if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->chart_list_nonce}"  )) 
				$errors->add("invalid_{$this->chart_list_nonce}", __("An invalid nonce was submited", $this->plugin));
			
			// Retreive all Charts 
			$charts = get_option("{$this->prefix}_dashboard");

			// check if any chart data exists
			if (! $charts) {
				$errors->add("no_charts_found", __("No charts found", $this->plugin));
			} else {

				$spreadsheets = [];
				$chart_params = [];
				$response = [];


				// check if an array if returned
				if (! is_array($charts)) {
					$errors->add("invalid_data_returned", __("get_option returned invalid data, an array was expected but got ". gettype($charts) . ".", $this->plugin));
				} else {

					// Loop through all the data
					foreach($charts as $chart_key => $chart) {

						// Does data contain a file id
						if(! isset($chart["file-select"]) ||  $chart["file-select"] == "") {
							$errors->add("no_file_id_found", __("No file-id found in the data returned by get_option({$this->prefix}_dashboar", $this->plugin));
						} else {

							// Does data contain a sheet id
							if(! isset($chart["sheet-select"]) ||  $chart["sheet-select"] == "") {
								$errors->add("no_sheet_id_found", __("No sheet-id found in the data returned by get_option({$this->prefix}_dashboar", $this->plugin));
							} else {

								// Retreive file
								$file = $db->find_by_id($chart["file-select"]);


								// check if a file is found
								if (! $file) {
									$errors->add("no_file_found", __("id ({$chart["file-select"]}) submitted but no file found in the database", $this->plugin));
								} else {

									// Fetch spreadsheet data 
									//$spreasheet =  $this->fetch_spreadsheet_data(wp_upload_dir()['path']."/".sanitize_file_name($file->name))
									$response[$chart_key]["spreadsheet"] = $this->fetch_spreadsheet_data(wp_upload_dir()['path']."/".sanitize_file_name($file->name))["sheet-data"][$chart["sheet-select"]]["formatted"];
									$response[$chart_key]["chart-params"] = $chart;

								}
							}
						}
					}
				}

								
				// Compose message
				$message = $this->compose_message($errors, $success);

			// Compose response
				$response = array(
					//"file-name" => $file->name,
					//"file-id" => $file->id,
					"data" => $response, 
					//"sheet-select-options" => $sheet_select_options,
					//"file-select-form" => $file_select_form,
					"message" => $message,
					"post" =>$_POST
				);

				// return ajax data
				echo json_encode($response);
				wp_die();


				// Loop through all charts
			}
			

		}  // END public function contact_form_process() {








		/**
		* Chart view ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function chart_update_callback() 
		{

			$errors = new \WP_Error;
			$success = new \WP_Error;
			//echo json_encode (wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
			//wp_die();
			// Ajax comes with two arrays $_POST and $_FILES
			//echo json_encode($_FILES);wp_die();
			//echo json_encode($_POST);wp_die();
			// Start buffer
			//ob_start();
			//
			//// Initialaze response data array
			$ajax_data = [];
			
			// verify file upload nonce
			if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__chart_update_nonce" )) 
				$errors->add("invalid_nonce", __("An invalid nonce was submited", $this->plugin));

			// Bail if errors
			if (!empty($errors->errors)) {
				$response = "<div class='notice notice-error is-dismissible'>";
				 foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $output) {
					$response .= "<p>{$output}</p>";
				}
				$response .= "</div>";
				echo json_encode(["wp-message" => $response]);
				wp_die();
  			}

  			array_shift($ajax_data);


			// Initialize default spreadsheet data (this is the default sheet to plot when a new graph is added)
			//$default_data =[];

			// Fetch sheets data for this spreadsheet
			$data = $this->fetch_spreadsheet_data("{$this->path}assets/img/test-spreadsheet.xlsx");

			// Prepareadata for output (data are identified by spreadsheet number and sheet number in the series)
			$ajax_data["file-name"] = "{$this->path}assets/img/test-spreadsheet.xlsx";
			$ajax_data["sheets"] = $data["formatted"];
			$ajax_data["action"] = $_POST["action"];

			// Add add the formated test data to the ajax response array
			//$ajax_data = ["data" => $ajax_data, "action" => $_POST["action"] ];
					

			// Output data
			echo json_encode($ajax_data);
			wp_die();
			

		}  // END public function contact_form_process() {









		/**
		* formats spreadsheet
		* @author  Abbas Lamouri
		* @param   string $file_name (spreadsheet file name)
		* @return  array $data (both raw and formatted Spreadsheet cols and rows data)
		* @version  0.1
		*/
		public function fetch_sheet_data($file_path, $sheet_index =  false)
		{
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

			// Create a new Reader of the type that has been identified
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

			// Advise the Reader that we only want to load cell data (no fprmating)
			//$reader->setReadDataOnly(true);

			// Load $input_file_path to a Spreadsheet Object
			$spreadsheet = $reader->load($file_path);

			// Identify all sheets by name in the spreasheet
			//$sheet = $spreadsheet->getSheetNames(sheet_name);


			// Loop through all sheets
			//foreach ($sheets as $sheet_key => $sheet_name) {
				
				// Convert data in each spreadsheet to array
				$data = $spreadsheet->getSheet($sheet_index)->toArray(
					NULL,        // Value that should be returned for empty cells
					TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
					TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
					TRUE         // Should the array be indexed by cell row and cell column
				);


				// retreive labels (first row)
				$labels = array_shift($data);

				// Retreive data type (second row
				$data_types = array_shift($data);

				$new_labels = [];
				$new_data_types = [];
				foreach ($labels as $key => $label) {
					if (isset($label) && $label && isset($data_types[$key]) && $data_types[$key]) {
						$new_labels[$key] = $label;
						$new_data_types[$key] = $data_types[$key];
					}
				}

				// Prepare the remaining data for Google visualization
				$cols = [];
				foreach($new_labels as $key => $label) {
					$cols[] = ['id' => $key, 'label' => $label, 'pattern' => '', 'type' => $data_types[$key]];
				}

				// Set Google visualization data rows
				$cell_data = [];
				foreach($data as $key => $rows) {
					$cells = [];
					foreach ($rows as $cell => $value) {
						$cells[] = ["v" => floatval($value), "f" => null];
					}
					$cell_data[] = ["c" =>$cells];
				}

				$formatted = array( "cols" =>$cols, "rows" => $cell_data);

				// // Prepareadata for output (data are identified by spreadsheet number and sheet number in the series)
				// //$raw_data["sheet-name"] = $sheet_name;
				// $raw_data["data-types"] = $new_data_types;
				// $raw_data["labels"] = $new_labels;
				// $raw_data["data"] = $data;

				// // Prepareadata for output (data are identified by spreadsheet number and sheet number in the series)
				// //$formatted_data["sheet-name"] = $sheet_name;
				// $formatted_data["data-types"] = $new_data_types;
				// $formatted_data["labels"] = $new_labels;
				// $formatted_data["data"] = array( "cols" =>$cols, "rows" => $cell_data);
				
			

			// Return both raw and formatted datat (formatted data for google visualization)
			return $formatted;


		} // END public function fetch_spreadsheet_data($file_path)










		/**
		* formats spreadsheet
		* @author  Abbas Lamouri
		* @param   string $file_path (spreadsheet file name)
		* @return  array $data (both raw and formatted Spreadsheet cols and rows data)
		* @version  0.1
		*/
		public function fetch_spreadsheet_data($file_path)
		{
			// Initialize array that will contain all the sheets from each spreadsheet
			//$formatted_data = [];
				
			// Identify input file name
			//$input_file_path = wp_upload_dir()['path']."/".$file->file_path;
			

			$sheet_data = [];
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

			// Create a new Reader of the type that has been identified
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

			// Advise the Reader that we only want to load cell data (no fprmating)
			//$reader->setReadDataOnly(true);

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


				// retrei labels (first row)
				$labels = array_shift($data);

				// Retreive data type (second row
				$data_types = array_shift($data);

				// Weed out columns with null label and data type values
				$new_labels = [];
				$new_data_types = [];
				foreach ($labels as $key => $label) {
					if (isset($label) && $label && isset($data_types[$key]) && $data_types[$key]) {
						$new_labels[$key] = $label;
						$new_data_types[$key] = $data_types[$key];
					}
				}

				// Prepare the remaining data for Google visualization
				$cols = [];
				foreach($new_labels as $key => $label) {
					$cols[] = ['id' => $key, 'label' => $label, 'pattern' => '', 'type' => $data_types[$key]];
				}

				// Set Google visualization data rows
				$cell_data = [];
				foreach($data as $key => $rows) {
					$cells = [];
					foreach ($rows as $cell => $value) {
						$cells[] = ["v" => floatval($value), "f" => null];
					}
					$cell_data[] = ["c" =>$cells];
				}

				$formatted = array( "cols" =>$cols, "rows" => $cell_data);

				// Prepareadata for output (data are identified by spreadsheet number and sheet number in the series)
				//$formatted_data[$sheet_key]["sheet-name"] = $sheet_name;
				//$formatted_data[$sheet_key]["data-types"] = $new_data_types;
				//$formatted_data[$sheet_key]["labels"] = $new_labels;
				$sheet_data[$sheet_key]["formatted"] = $formatted;
				$sheet_data[$sheet_key]["data-types"]  = $data_types;
				$sheet_data[$sheet_key]["sheet-name"]  = $sheet_name;

			}

			// Instantiate the file class to validate and upload files
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			$output["file-path"] = $file_path;
			$output["file-name"] = $file_instance->file_parts($file_path)["basename"];
			$output["sheet-names"] = $sheet_names;
			$output["sheet-data"] = $sheet_data;
			

			// Return both raw and formatted datat (formatted data for google visualization)
			return $output;


		} // END public function fetch_spreadsheet_data($file_path)











		
		/*
		* Register admin menus, submenus, sections and fields
		*/
		public function admin_menu_register()
		{
			
			// If no admin or empty admin_menusreturn
			if (!is_admin() || !current_user_can('manage_options')) {
				return;
			}

			// Add menu page
			$this->page_hook = add_menu_page(
				__($this->name, $this->plugin), // page Title displayed in browser bar
				__($this->name, $this->plugin), // menu title, which is displayed in the admin menus
				'administrator', // The capability required for this menu to be displayed to the user.
				$this->prefix, // menu id
				function () {}, //array($this, $menu['callback']), // Callback function used to render the settings
				'dashicons-editor-customchar', // icon url
				'5' // menu position
			);
		
			// Add submenu pages
			$this->dashboard_hook = add_submenu_page(
				$this->prefix, // Parent id
				__($this->name, $this->plugin), // page title, which is displayed in the browser title bar
				__("Dashboard", $this->plugin), // menu title, which is displayed in the browser title bar
				'administrator', // The capability required for this page to be displayed to the user.
				$this->prefix, // submenu id
				array($this, 'dashboard_submenu_page_render') // callback function to render the page
			);
			
			$this->support_hook = add_submenu_page(
				$this->prefix, // Parent id
				__($this->name, $this->plugin), // page title, which is displayed in the browser title bar
				__("Support", $this->plugin), // menu title, which is displayed in the browser title bar
			'administrator', // The capability required for this page to be displayed to the user.
			"{$this->prefix}_support", // submenu id
			array($this, 'support_submenu_page_render')// callback function to render the page
			);

			// Create an option for the submenu
			if (false == get_option("{$this->prefix}_dashboard"))  
			add_option("{$this->prefix}_dashboard");
		
			// Register submenu settings
			register_setting( 
				"{$this->prefix}_dashboard",
				"{$this->prefix}_dashboard",
				array($this, 'sanitize')
			);
		
			// Adds my_help_tab when my_admin_page loads
			add_action("load-{$this->page_hook}", array($this, 'add_screen_options'));
			
			// Add meta box footer script
			add_action("admin_footer-{$this->page_hook}", array($this, 'meta_box_footer_scripts'));


		} // END  public function admin_menu_register () {





// function add_meta_boxes() {
// 	add_meta_box(
// 		"{$this->plugin}-some-metaboxes",
// 		" JUNK",
// 		function ($object, $params)  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
// 		{
// 			echo $this->add_new_chart_metabox_render();
			
// 		},
// 		$this->dashboard_hook,
// 		'normal',
// 		'default',
// 		array()
// 	);

// }





		/**
		 * Renders dashboard submenu page content 
		 * @return null
		 */
		public function dashboard_submenu_page_render() {

			//var_dump(get_option("{$this->prefix}_dashboard"));die;

			//var_dump($_GET["action"] == "add-new-chart");

				// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;
				
			// Retreive all spreadsheet files from the database
			$files = $db->find_all();

			// This code is executed if a new chart request is initiated
			if (isset($_GET["action"]) && $_GET["action"] == "add-new-chart") {
			
				// Add new chart meta box				
				add_meta_box(
					"{$this->plugin}-add-new-chart-metabox",
					" Add New Chart",
					function ($object, $params) { // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
						echo $this->add_new_chart_metabox_render();
					},
					$this->dashboard_hook,
					'normal',
					'default',
					array()
				);

				// Add admin fields metabox
				add_meta_box(
					"{$this->prefix}-admin-fields",
					"Admin Fields",
					function ($object, $params) { // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
					
						//$field = $params['args']['field'];
						echo $this->admin_fields_metaboxes_render();
					
					},
					$this->dashboard_hook,
					'side',
					'default',
					array()
				);
				




				// // Add file select/file upload metabox
				// add_meta_box(
				// 	"{$this->plugin}-file-select-upload-metabox",
				// 	"Select File",
				// 	function ($object, $params)  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
				// 	{
				// 		$files = $params['args']['files'];
				// 		echo $this->file_select_upload_metabox_render($files);
				// 	},
				// 	$this->dashboard_hook,
				// 	'normal',
				// 	'default',
				// 	array("files" => $files)
				// );

				// // Add chart type selection div
				// add_meta_box(
				// 	"{$this->plugin}-chart-type-select-metabox",
				// 	"Select Chart Type",
				// 	function ($object, $params)  { // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
						
				// 		//echo $this->chart_type_select_metabox();

				// 	},
				// 	$this->dashboard_hook,
				// 	'side',
				// 	'default',
				// 	array()
				// );

				echo $this->get_template_html("new-chart");


			} else {



			$charts = get_option("{$this->prefix}_dashboard");




				if (! $charts) {  // Display a message in a meta box if the charts database does not contain any records

					add_meta_box(
						"{$this->prefix}_dashboard_no_chart",  // meta box ID
						'No Chart', // meta box title
						function ($object, $params)  {	echo "No charts found"; },// object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
						$this->dashboard_hook,  // meta box screen
						'normal',  // meta box context (normal, side or advanced)
						'default',  // The priority within the context where the boxes should show ('high', 'low')
						array()  // Data that should be set as the $args property of the box array (second parameter passed to your callback).
					);

				} else {  // If the files database table contains records

					var_dump($charts);

						// Loop through all records
					foreach ($charts as $chart_key => $chart) {
						
						// Add a meta box for each sheet
						add_meta_box(
							"{$this->plugin}-dashboard-chart-{$chart_key}",
							"File Name: {$chart["file-name"]},   Sheet Name: {$chart["sheet-name"]}",
							function ($object, $params) {  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
								$chart_key = $params["args"]["chart_key"];
								$chart = $params["args"]["chart"];
								?>
								<form id ="file-delete-form">
									<input type ="hidden" id = "file-id" value = "<?php echo $chart_key ?>" >
									<?php submit_button("Delete File", 'secondary', "file-delete-submit", false);?>
								</form>
								<?php

								echo $this->chart_render($params); 
							},
							$this->dashboard_hook,
							'normal',
							'default',
							array('chart_key' => $chart_key, "chart" => $chart)
						);				
								
					}	

				}





				if (! $files) {  // Display a message in a meta box if the charts database does not contain any records

					add_meta_box(
						"{$this->prefix}_dashboard_no_chart",  // meta box ID
						'No Chart', // meta box title
						function ($object, $params)  {	echo "No charts found"; },// object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
						$this->dashboard_hook,  // meta box screen
						'normal',  // meta box context (normal, side or advanced)
						'default',  // The priority within the context where the boxes should show ('high', 'low')
						array()  // Data that should be set as the $args property of the box array (second parameter passed to your callback).
					);

				} else {  // If the files database table contains records

						// Loop through all records
					foreach ($files as $file) {
						
						// Add a meta box for each sheet
						add_meta_box(
							"{$this->plugin}-dashboard-chart-{$file->id}",
							"File Name: {$file->name}",
							function ($object, $params) {  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
								$file_id = $params["args"]["file_id"]
								?>
								<form id ="file-delete-form">
									<input type ="hidden" id = "file-id" value = "<?php echo $file_id ?>" >
									<?php submit_button("Delete File", 'secondary', "file-delete-submit", false);?>
								</form>
								<?php

								//echo $this->sheet_selection_render($params); 
							},
							$this->dashboard_hook,
							'normal',
							'default',
							array('file_id' => $file->id)
						);				
								
					}	

				}

									echo $this->get_template_html("dashboard");

			}


		} // END  public function dashboard_submenu_page_render()




		
		/**
		 * Renders new chart meta box
		 */
		function add_new_chart_metabox_render() {

			?>

				<!-- Display reset settings button -->
					<div class="refresh">
						<?php submit_button('Refresh', 'primary', 'refresh', false, array());?>
					</div>


			<!-- div that will hold the dashboard -->
			<div id = "<?php echo "dashboard-new-div"; ?>"></div>

			<!-- div that will hold the filter -->
			<div id = "<?php echo "filter-new-div"; ?>"></div>

			<!-- div that will hold the chart -->
			<div id = "<?php echo "chart-new-div"; ?>"></div>
			<?php	

		} // END function add_new_chart_metabox_render()





		/**
		 * Renders file select/upload meta box
		 */
		function file_select_upload_metabox_render($files) {

			?>
				<!-- <table>
					<tr>
						<th> -->
							<!-- File selection div -->
							<div id ="file-selection-div">
								<?php //if (! empty($files)) { ?>
								<!-- <form id ="file-selection-form"> -->
									<select id="select-file" name="<?php echo "{$this->prefix}_dashboard[select-file]"; ?>" >
										<option value="" >Select a File</option>
										<?php foreach ($files as $file) { ?>
											<option value="<?php echo $file->id; ?>" ><?php echo $file->name ?></option>
										<?php //} ?>
									</select>
								<!-- </form> -->
								<?php } ?>
							</div>
					<!-- 	</th>
						<th> -->
							<!-- File upload div		 -->
							<div id ="file-upload-div">
							<!-- <form id ="file-upload-form" enctype="multipart/form-data" action="" method="post" > -->
								<input type='hidden' id = 'created-date' name='created-date' value='<?php echo strftime("%Y-%m-%d %H:%M:%S",time()); ?>'>
								<input type='hidden' id = 'updated-date' name='updated-date' value='<?php echo strftime("%Y-%m-%d %H:%M:%S",time()); ?>'>
								<?php wp_nonce_field("{$this->prefix}_{$this->file_upload_nonce}", $this->file_upload_nonce); ?>
								<input type='file' class = "clickable file-upload btn btn-success" name='file-upload' id='file-upload'>  
								<div><?php submit_button("Upload File", 'primary', "file-upload-submit", false);?></div>
							<!-- </form> -->
							</div>
						<!-- </th>
						<th> -->
							<!-- Sheet selection div -->
							<div id ="sheet-selection-div"><p> AND select a sheet</p><span></span></div>
						</th>
						<th>			
							<!-- Chart type div -->
							<div id ="chart-type-selection-div">
							<?php if (! empty($this->chart_types)) { ?>
								<!-- <form id ="chart-type-selection-form"> -->
								<select id="select-chart-type" name="<?php echo "{$this->prefix}_dashboard[select-chart-type]"; ?>">
									<option value="" >Select Chart Type</option>
									<?php foreach ($this->chart_types as $key => $name) { ?>
										<option value="<?php echo $key; ?>" ><?php echo $name ?></option>
									<?php } ?>
								</select>
									<!-- </form> -->
							<?php } ?>
							</div>
				<!-- 		</th>
					</tr>
				</table> -->

			<?php

		} // END function file_select_upload_metabox_render()



	



		/**
		 * Renders admin fields meta boxes
		 */
		function admin_fields_metaboxes_render() {

			?><div><?php

				foreach($this->admin_sections() as $section) {
					?>
					<button class ="accordion"><?php echo $section["title"] ?></button>
					<div class = "panel">
						<?php

						// Add meta box for each admin field
						foreach ($this->admin_fields() as $field) {
							foreach ($this->possible_field_options() as $option_key => $option_value) {
								if (!isset($field[$option_key]))  $field[$option_key] = $option_value;
							}

							if($field["section"] == $section["slug"]) {

								if (file_exists($this->path . "templates/" . $field['field_type'] . ".php")) {?>
									<?php require ($this->path . 'templates/' . $field['field_type'] . ".php");?>
								<?php } else {?>
									<?php _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['field_type'] . ".php does not exist</div>", $this->plugin);
								}

								if ($field["slug"] == "file_upload") submit_button("Upload File", 'primary', "file-upload-submit", false); 
							}
						}
						?>	
					</div>

				<?php } ?>

			</div><?php
	

		} // END function admin_fields_metaboxes_render()





		/**
		*
		* Renders sheet selection form
		*
		* @author  Abbas Lamouri
		* @param    $params: meta box parameters
		* @return
		* @version  0.1
		*
		*/

		public function chart_render($params)
		{

			// Strat buffer
			ob_start();
			//$file_id = $params['args']['chart_id'];
			$chart_key = $params['args']['chart_key'];
			$chart = $params['args']['chart'];

			?>

			<div id ="dashboard-chart-list">
				<div id = "<?php echo "dashboard-{$chart_key}-div"; ?>" ></div>
				<div id = "<?php echo "filter-{$chart_key}-div"; ?>" ></div>
				<div id = "<?php echo "chart-list-{$chart_key}-div"; ?>" ></div>
			</div>
			<?php

			$html = ob_get_contents();
			ob_end_clean();
			return $html;

		}








		/**
		*
		* Renders sheet selection form
		*
		* @author  Abbas Lamouri
		* @param    $params: meta box parameters
		* @return
		* @version  0.1
		*
		*/

		public function sheet_selection_render($params)
		{

			// Strat buffer
			ob_start();
			//$file_id = $params['args']['chart_id'];
			$sheets = $params['args']['sheets'];
			$selected_sheet = $params['args']['selected_sheet'];

			


			if (count($sheets)> 1 ) {
				?>
				<form id ="sheet-selection">
				<input type = "hidden" id ="chart-id" name = "chart-id" value = "">
					<!-- Sheet type select field -->
					<div class = "sheet">
					<h2>Selected Sheet: <?php echo $selected_sheet;?></h2>
						<select id="sheet" name="sheet">
							<option value="" <?php selected( $selected_sheet, "", true); ?> >Select a Sheet</option>
							 <!-- Loop throught all the sheet names -->
							<?php foreach ($sheets as $sheet_key => $sheet) { ?>
								<option value="<?php echo $sheet_key; ?>" <?php selected( $selected_sheet, $sheet["sheet-name"], true); ?> ><?php echo $sheet["sheet-name"] ?></option>
							<?php } ?>
						</select>
					</div>
				</form>
				<?php
			}

			?>
			<div id = "<?php echo "dashboard-{$file_id}-div"; ?>"></div>
			<div id = "<?php echo "filter-{$file_id}-div"; ?>"></div>
			<div id = "<?php echo "chart-list-{$file_id}-div"; ?>"></div>
			<?php

			$html = ob_get_contents();
			ob_end_clean();
			return $html;

		}









	
		/**
		*
		* Adds plugin setting link
		*
		* @author  Abbas Lamouri
		* @param   array $links,  links array
		* @return  arra $links,  links array
		* @version  0.1
		*
		*/
		public function add_plugin_action_links($links)
		{
			
			$settings_link = "<a href='admin.php?page={$this->prefix}'>Settings</a>";
			array_push($links, $settings_link);
			return $links;
		
		} // END private function add_plugin_action_links ( $links ) {





		/**
		*
		* Adds a help tab to the upper right corner of the plugin page.
		*
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*
		*/
		public function add_screen_options() {
		
			// Bail if it is not my plugin page
			$screen = get_current_screen();
			if ($screen->id != $this->page_hook) return;
			

			 /* Trigger the add_meta_boxes hooks to allow meta boxes to be added */
		    do_action('add_meta_boxes_',$this->page_hook, null);
		    do_action('add_meta_boxes',$this->page_hook , null);

		   // Add help tab
			$screen->add_help_tab(array(
				'id' => "{$this->plugin}_help_tab",
				'title' => "Help Tab",
				'content' => "<p>Descriptive content for my plugin {$this->title} that will show in My Help Tab-body goes here.)</p>",
			 ));

		 
		    /* Enqueue WordPress' script for handling the meta boxes */
		    //wp_enqueue_script('postbox');
		 
		    /* Add screen option: user can choose between 1 or 2 columns (default 2) */
		    add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );


		 } // END public function add_screen_options()



		/**
		*
		* Footer Script Needed for Meta Box: Meta Box Toggle, Spinner for Saving Option, Reset Settings Confirmation
		*
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*
		*/
		public function meta_box_footer_scripts()
		{

			?>
			<script type="text/javascript">
				//<![CDATA[
				jQuery(document).ready(function($) {
						// toggle
						$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
						postboxes.add_postbox_toggles('<?php echo $this->page_hook; ?>');
					});
				//]]>
			</script>
			<?php

		}  // END public function footer_scripts()






		/**
		*
		* Renders support submenu page content
		*
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*
		*/

		public function support_submenu_page_render()
		{

			// Declare support submenu page Tabs
			// Help
			$this->support_tabs = array(
				array(
					"id" => "{$this->prefix}_support_help",
					"title" => "Support",
					"meta_boxes" => array(
						array(
							"id" => "{$this->prefix}_support_help_1",
							"title" => __("Welcome to Visualizer!", $this->plugin),
							"content" => <<< HTML
Visualizer lets you easily create and customize responsive tables and charts so you can share your data effectively to your users.

With this version, you can already:
<ol>

<li>Create an unlimited number of tables and charts</li>
<li>Manually edit the data used by any graphs and tables</li>
<li>Import data from a URL or file</li>
</ul> 
Fully customize the design and behavior of your tables and charts
We have many more features and charts, and offer email & chat support if you purchase our Pro Version.

Ready to begin? Let's create a chart!
HTML
						),

						array(
							"id" => "{$this->prefix}_support_help_2",
							"title" => __("Documentation!", $this->plugin),
							"content" => <<< HTML
To get started with Visualizer, we recommend you first bookmark our main documentation page here.

Notably, you could take a look at this first introductory tutorial: How to create my first chart.

If you prefer learning through video, this could prove useful. It is a little dated however.
HTML
						),
						array(
							"id" => "{$this->prefix}_support_help_3",
							"title" => __("Need help?", $this->plugin),
							"content" => <<< HTML
Our support channel for users of the free version can be found here.
HTML
						),

					)
				),

				// More Features
				array(
					"id" => "{$this->prefix}_support_more_features",
					"title" => "More Features",
					"meta_boxes" => array(
						array(
							"id" => "{$this->prefix}_support_more_features_1",
							"title" => __("More charts!", $this->plugin),
							"content" => <<< HTML
Gain access to 6 more charts right away, and more in the future. So far these include the gauge, candlestick, timeline, combo, polar area and radar charts.

Of course, these are fully customizable!
HTML
						),
						array(
							"id" => "{$this->prefix}_support_more_features_2",
							"title" => __("Excel-like data editor", $this->plugin),
							"content" => <<< HTML
Use our excel-like data editor to configure your charts, unlimited value, and you can paste your data directly from excel!
HTML
						),
						array(
							"id" => "{$this->prefix}_support_more_features_3",
							"title" => __("Premium support", $this->plugin),
							"content" => <<< HTML
Get timely help from our trained representatives, they will answer all your questions, and even help you setup your instance.

With our Agency plan, you'll even get to chat with them in real time and get immediate answers (within regular business hours).
HTML
						),
						array(
							"id" => "{$this->prefix}_support_more_features_4",
							"title" => __("Import any data from your database!", $this->plugin),
							"content" => <<< HTML
Do you want to create a chart based on custom queries? Or display data about WordPress statistics? Do you want to import data periodically (every day, every hour, etc.) ?

With Pro you can do all of these, and much more. Visit our site to know more.
HTML
						),

					)
				),

				// Help Us Improve
				array(
					"id" => "{$this->prefix}_support_help_us_improve",
					"title" => "Help Us Improve",
					"meta_boxes" => array(
						array(
							"id" => "{$this->prefix}_support_help_us_improve_1",
							"title" => __("Answer a few questions for us to help us improve the product", $this->plugin),
							"content" => <<< HTML
We're always looking for suggestions to further improve Visualizer. If you'd like to help us, please fill out this survey.

If your feedback is especially helpful and we choose to do an interview with you to discuss your suggestions, you will even gain a yearly membership for free for your trouble.
HTML
						),

					)
				)


			);

			// Retreive active tab
			$this->support_active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : $this->support_tabs[0]['id'];

			
			// // Register support submenu page help content
			// add_meta_box(
			// 	"{$this->prefix}_support_help_us_improve",  // meta box ID
			// 	'Help', // meta box title
			// 	function ($object, $params)  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
			// 		{
			// 			echo "Help Content";
			// 		},
			// 	$this->support_hook,  // meta box screen
			// 	'advanced',  // meta box context (normal, side or advanced)
			// 	'default',  // The priority within the context where the boxes should show ('high', 'low')
			// 	array()  // Data that should be set as the $args property of the box array (second parameter passed to your callback).
			// );

			// // Register support submenu page more features content
			// add_meta_box(
			// 	"{$this->prefix}_more_features",  // meta box ID
			// 	'More Features', // meta box title
			// 	function ($object, $params)  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
			// 		{
			// 			echo "More FeaturesContent";
			// 		},
			// 	$this->support_hook,  // meta box screen
			// 	'normal',  // meta box context (normal, side or advanced)
			// 	'default',  // The priority within the context where the boxes should show ('high', 'low')
			// 	array()  // Data that should be set as the $args property of the box array (second parameter passed to your callback).
			// );

			// // Register support submenu page help us improve content
			// add_meta_box(
			// 	"{$this->prefix}_help us improve",  // meta box ID
			// 	'Help Us Improve', // meta box title
			// 	function ($object, $params)  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
			// 		{
			// 			echo "Help Us Improve Content";
			// 		},
			// 	$this->support_hook,  // meta box screen
			// 	'normal',  // meta box context (normal, side or advanced)
			// 	'default',  // The priority within the context where the boxes should show ('high', 'low')
			// 	array()  // Data that should be set as the $args property of the box array (second parameter passed to your callback).
			// );

			echo $this->get_template_html("support");


			}  // END public function support_submenu_page_render()
				 



					/**
					* Renders tab meta box
					* @return string $htmkl     html text
					*/
					public function render_tab_meta_box($args)
					{
						//echo $this->active_tab;die;
					foreach ($this->admin_fields() as $field_id => $field) {
					}
						// retreive record by active tab
					$records = $this->tab_settings($this->active_tab);
						//var_dump($_POST);die;
					if ("axl_wp_ultimate_cpt_add_cpt" == $this->active_tab) {
					 $this->button_label = (isset($_POST["{$this->plugin}-edit-record"])) ? 'Update Post Type' : "Add Post Type";
				 } elseif ("axl_wp_ultimate_woo_tidbits_single_product_tabs" == $this->active_tab) {
					 $this->button_label = (isset($_POST["{$this->plugin}-edit-record"])) ? 'Update Product Tab' : "Add Product Tab";
				 } else {
					 $this->button_label = "Save Changes";
				 }
						// If record is being edited and is multidimentional array, retreive record by recird ID
				 if (isset($_POST["{$this->plugin}-edit-record"]) && count($records, COUNT_RECURSIVE) !== count($records)) {
					 $record = (isset($_POST['record_id']) && isset($records) && $records[$_POST['record_id']]) ? $records[$_POST['record_id']] : array();
				 } else {
					 $record = $records;
				 }
				 var_dump($records);
				 echo "ppppp";
						// If no admin fields
				 if (empty($this->admin_fields())) {
					 return;
				 }
		// Localization.
						// wp_localize_script(
						//     "{$this->plugin}-admin-js", //handle for the script
						//     $this->prefix . "_admin_object", //  The name of the variable which will contain the data (used in the ajax url)
						//     array( // Data to be passed
						//         'ajax_url' => admin_url('admin-ajax.php'),
						//         'ajax_action' => $this->prefix . "_list_reorder",
						//         'ajax_tab' => $this->prefix . "_dashboard" . "_fields",
						//     )
						// );
				 ob_start();
				 ?>
				 <?php //if ( empty($this->tab_settings($this->active_tab))) :?>
				 <!-- There are no checkout fields to display -->
				 <?php //else : ?>
				 <div id="settings-options">
				 <div id="accordion">
				 <?php
				 foreach ($this->tab_settings($this->active_tab) as $record_id => $record) {
					?>
					<div class="group">
					<h3><?php echo $record_id ?></h3>
					<div>
					<table class="wp-list-table widefat fixed striped">
					<col width="30%">
					<col widht="70%">
					<thead>
					<tr>
					<th>Option</th>
					<th>Value</th>
					</tr>
					</thead>
					<tbody>
					<?php
													 //foreach ($record as $field_id => $field_value) {
					foreach ($this->admin_fields() as $field_id => $field) {
															//if ($field['tab'] != $this->active_tab || $field_id != $id ||$field['section'] != $args['section_id']) continue;
															// Populate all possible fields
						foreach ($this->possible_field_options() as $option_key => $option_value) {
						if (!isset($field[$option_key])) {
						 $field[$option_key] = $option_value;
					 }
				 }
															//$field['value'] = $field_value;
				 ?> <tr><?php
				 if (file_exists($this->path . "templates/" . $field['field_type'] . ".php")) {
					?>
					<td>
					<h2><?php echo $field['title'] ?></h2>
					</td>
					<td><?php require $this->path . 'templates/' . $field['field_type'] . ".php";?></td>
					<?php
				} else {
					?>
					<td>
					<?php _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['field_type'] . ".php does not exist</div>", $this->plugin);?>
					</td>
					<?Php
				}
				?>
				</tr><?php
			}
													 //}
			?>
			</tbody>
			</table>
			</div>
			</div>
			<?php
		}
		?>
		</div>
		</div>
		<?php //endif; ?>
		<table class="wp-list-table widefat fixed striped">
		<col width="20%">
		<col widht="80%">
		<thead>
		<tr>
		<th>Option</th>
		<th>Value</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($this->admin_fields() as $field_id => $field) {
		 if ($field['tab'] != $this->active_tab || $field['section'] != $args['section_id']) {
			continue;
		}
									 // Populate all possible fields
		foreach ($this->possible_field_options() as $option_key => $option_value) {
			if (!isset($field[$option_key])) {
			$field[$option_key] = $option_value;
		}
	}
									 // Set field value
	$field['value'] = (isset($record) && isset($record[$field_id])) ? $record[$field_id] : $this->defaults($this->active_tab)[$field_id];
									 // show template if it exists
	if (file_exists($this->path . "templates/" . $field['field_type'] . ".php")) {
		?>
		<tr id="<?php echo $field_id ?>">
		<td>
		<h2><?php echo $field['title'] ?></h2>
		</td>
		<td><?php require $this->path . 'templates/' . $field['field_type'] . ".php";?></td>
		</tr>
		<?php
	} else {
		?>
		<tr>
		<td>
		<?php _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['field_type'] . ".php does not exist</div>", $this->plugin);?>
		</td>
		</tr>
		<?Php
	}
}
?>
</tbody>
</table>
<?php submit_button($this->button_label, 'primary', 'submit', true);?><br style="clear:both;">
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
return $html;
}
				/**
				* Renders templates
				* @param string $template The name of the template to render (without .php)
				* @param array  $attributes    The PHP variables for the template
				* @return string               The contents of the template.
				*/
				public function get_template_html($template, $atts = array())
				{
				ob_start();
				do_action($this->prefix . '_before_' . $template);
				require $this->path . "templates/" . $template . ".php";
				do_action($this->prefix . '_after_' . $template);
				$html = ob_get_contents();
				ob_end_clean();
				return $html;
				} // END public function get_template_html($template, $atts = array() ) {








				/*
				* Perform additional validation on input fields before ther are stored in the DB
				*/
				public function sanitize($input) {
					var_dump($_POST);die;
					// Intialize error
				$errors = new \WP_Error;

				if (empty(get_option ("{$this->prefix}_dashboard"))) {
					$charts = [];
				} else {

					$charts = get_option ("{$this->prefix}_dashboard");
				}
				
				//var_dump($charts);die;

				$chart_id =  "{$input["file-select"]}-{$input["sheet-select"]}";

				//echo $chart_id;

				$charts[$chart_id] = $input;

				//var_dump($charts);die;

				return $charts;
					// get option page
				
				if (!isset($_POST['option_page'])) {
				 return;
			 }
					//Deelete option if post reset is set
			 if (isset($_POST['reset_settings']) && isset($_POST['option_page'])) {
				 delete_option($_POST['option_page']);
						 //var_dump($this);die;
				 return;
			 }
					// Retreive add_feild settings options
			 $output = (isset($_POST['option_page']) && get_option($_POST['option_page'])) ? get_option($_POST['option_page']) : array();
					// Set record ID based on the settings page (this is required for multi dimentional arrays like cpt)
			 if ("{$this->prefix}_woo_tidbits_single_product_tabs" == $_POST['option_page']) {
				 $record_id = 'tab_id';
			 } elseif ("{$this->prefix}_woo_tidbits_order_statuses" == $_POST['option_page']) {
				 $record_id = 'status_id';
			 } elseif ("{$this->prefix}_woo_tidbits_checkout_fields" == $_POST['option_page']) {
				 $record_id = 'checkout_field_id';
			 } elseif ("{$this->prefix}_cpt_add_cpt" == $_POST['option_page']) {
				 $record_id = 'cpt_slug';
			 } elseif ("{$this->prefix}_contact_form_fields" == $_POST['option_page']) {
				 $record_id = 'field_id';
			 }
					// Deelete setting if post delete is set
			 if (isset($_POST["{$this->plugin}-delete-record"])) {
				 unset($output[$_POST['record_id']]);
				 return $output;
			 }
					// This portion of the code is run if amult dimentional array  (like cpt) is being  saved/edited)
					// if (count($output, COUNT_RECURSIVE) !== count($output)) {
			 if (isset($record_id) && in_array($record_id, array_keys($input))) {
						 //var_dump($input);die;
						 // Add required fields error
				 foreach ($this->admin_fields() as $key => $field) {
					if (in_array($key, array_keys($input))) {
					if (isset($field['required']) && $field['required'] && !$input[$key]) {
					 $errors->add("{$key}_empty", __("{$field['title']} is required", $this->plugin));
				 }
			 }
		 }
						 // Check if an existing multi array record is being addaed
		 if ((!isset($_POST["{$this->plugin}-update-record"]) || !$_POST["{$this->plugin}-update-record"]) && isset($input[$record_id]) && in_array($input[$record_id], array_keys($output))) {
			$errors->add("{$input[$record_id]}_exists", __("A record with this ID = {$input[$record_id]} exists.  Please edit this tab to modify it", $this->plugin));
		}
	}
	if (!empty($errors->errors)) {
	 foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
		add_settings_error($this->prefix . "_dashboard", $code, $message);
	}
} else {
						 // Save settings depending on whether it is an array or array or arrays as in the case of add_field tab
						 //if (in_array($record_id, array_keys($input))) {
 if (isset($record_id) && in_array($record_id, array_keys($input))) {
	$output[$input[$record_id]] = $input;
} else {
	$output = $input;
}
}
					//var_dump($output);die;
return $output;
				} // END static function sanitize( $input ) {













		



				/*
				* Adds admin notices
				*/
				public function add_admin_notices()
				{
					//Return if not WP Ultimate plugin page
				if (!strpos(get_current_screen()->id, $this->prefix . "_dashboard")) {
				 return;
			 }
			 extract($this->tab_settings("{$this->prefix}_dashboard"));
					// Initialize error/success objects
			 $errors = new \WP_Error;
					//Check if WooCommerce is active enable plugin functionality
			 if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
				 foreach ($this->tab_settings("{$this->prefix}_dashboard") as $class_name => $flag) {
					if (false !== strpos($class_name, "Woo") && isset($flag) && $flag) {
					$errors->add("{$class_name}_requires_woocommerce", __("{$class_name} requires <a href ='https://wordpress.org/plugins/woocommerce/'>woocommerce</a> to be installed and activated. PLease activate Woocommerce or disable this module.", $this->plugin));
				}
			}
		}
					// Display admin notice
		echo $this->display_admin_notices($errors);
				} // END public function display_admin_notices() {




				/**
				* Displays admin notices
				* @param  object $error WP Error
				* @return string $html  List of admin errors
				*/
				public function display_admin_notices($errors)
				{
					// Loop through all the errors/success
				if (empty($errors->errors)) {
				 return;
			 }
			 ob_start();
			 foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
				?><div class="notice notice-error is-dismissible">
				<p><?php echo "$message"; ?> </p>
				</div><?php
			}
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
				} // END public function display_admin_notices() {
				/**
				* Add plugin links to admin bar
				*/
				public function my_admin_bar()
				{
				global $wp_admin_bar;
					// extract(get_option("{$this->prefix}_dashboard"));
					//Add a link called for the plugin
				$wp_admin_bar->add_menu(array(
				 'id' => $this->plugin,
				 'title' => 'AXL WP Ultimate',
				 'href' => '#',
			 ));
					//THEN add  sub-links
				$wp_admin_bar->add_menu(array(
				 'id' => $this->prefix . "_dashboard",
				 'title' => 'Dashboard',
				 'href' => add_query_arg(array('page' => $this->prefix . "_dashboard"), admin_url('admin.php')),
				 'parent' => $this->plugin,
			 ));
					// foreach ($this->tab_settings("{$this->prefix}_dashboard") as $class_name => $flag) {
					//     if (isset($flag) && $flag && class_exists("Ultimate\\Includes\\{$class_name}")) {
					//         $class = 'Ultimate\\Includes\\' . $class_name;
					//         $instance = new $class;
					//         $wp_admin_bar->add_menu(array(
					//             'id' => $instance->class_module(),
					//             'title' => $instance->name,
					//             'href' => add_query_arg(array('page' => $instance->class_module()), admin_url('admin.php')),
					//             'parent' => $this->plugin,
					//         ));
					//     }
					// }
				} // END  function my_admin_bar() {
				/**
				* Displays admin footer text
				* @return string footer text
				*/
				public function admin_footer_text()
				{
					// Get current screen
				$screen = get_current_screen();
					// Return if not WP Ultimate plugin page
				if (!strpos($screen->id, $this->prefix)) {
				 return;
			 }
			 return sprintf(__('Let us know if you like this site.   Want the real thing? <a href="%s" title="Click here to purchase a license!" target="_blank">Click here to purchase a license!</a>', $this->plugin), 'http://abbaslamouri.com');
				} // end  public function admin_footer_text() {
				/**
				* Loads all enabled modules
				*/
				public function plugins_loaded()
				{
					//extract($this->tab_settings("{$this->prefix}_dashboard"));
					// instantiate all the class modules
					// foreach ($this->tab_settings("{$this->prefix}_dashboard") as $class_name => $flag) {
					//     if (isset($flag) && $flag && class_exists("Ultimate\\Includes\\{$class_name}")) {
					//         $class = 'Ultimate\\Includes\\' . $class_name;
					//         $instance = new $class;
					//     }
					// }
				} // END public function plugins_loaded () {
				/**
				* Initialization tidbits
				*/
				public function admin_init()
				{
					// start a session if none exists
				if (!session_id()) {
				 session_start();
			 }
				} // END  public function init (){
				/**
				* Uses Jquery UI to reorder lists using Ajax
				*/
				public function list_reorder()
				{
					//print_r($_POST);wp_die();
					// Fetch existing fields
				$old_fields = $this->tab_settings($_POST['tab']);
					//get new order
				$new_order = $_POST['rows'];
					// Intialize new fields
				$new_keys = array();
				$new_fields = array();
					//Retreive the keys, reorder them , then reorder the fields)
				foreach ($new_order as $key => $value) {
				 $new_keys[$key] = array_keys($old_fields)[$value - 1];
			 }
			 foreach ($new_keys as $key => $value) {
				 $new_fields[$value] = $old_fields[$value];
			 }
					// Update fields
			 update_option($_POST['tab'], $new_fields);
				} // END list_reorder() {
				/**
				* Renders front end form fields by type
				* @param  array $field input field
				* @param  string $value input value if any
				* @return strin        htnl input field
				*/
				public function render_field_type($field, $value)
				{
				ob_start();
				if ($field['field_type'] == "text" || $field['field_type'] == "email" || $field['field_type'] == "password" || $field['field_type'] == "hidden") {
					?><input type="<?php echo $field['field_type'] ?>" id="<?php echo $field['field_id']; ?>"
					name="<?php echo $field['field_id'] ?>" <?php echo ($value) ? "value='" . $value . "'" : "" ?>>
					<?php echo $field['field_description'];
				} elseif ($field['field_type'] == "textarea") {
					?><textarea name="<?php echo $field['field_id'] ?>" id="<?php echo $field['field_id'] ?>"
					rows=10><?php echo $value ?></textarea><?php
				} elseif ($field['field_type'] == "checkbox") {
					?><input type="<?php echo $field['field_type'] ?>" id="<?php echo $field['field_id'] ?>"
					name="<?php echo $field['field_id'] ?>" value="1" <?php checked(1, $value, true)?>>
					<?php echo $field['field_description'] ?><?php
				} elseif ($field['field_type'] == "select") {
					?>
					<select id="<?php echo $field['field_id'] ?>" name="<?php echo $field['field_id'] ?>">
					<option value="0">Select Option</option>
					<?php
					$options = explode(", ", $field['field_options']);
					foreach ($options as $key => $option) {
						?>
						<option value="<?php echo $option ?>" <?php selected($value, $option, true);?>><?php echo $option ?></option>
					<?php }?>
					</select>
					<?php
				} elseif ($field['field_type'] == "radio") {
				 $options = explode(", ", $field['field_options']);
				 foreach ($options as $key => $option) {
					?>
					<input type="<?php echo $field['field_type'] ?>" id="<?php echo $field['field_id'] ?>"
					name="<?php echo $field['field_id'] ?>" value="<?php echo $option ?>" <?php checked($option, $value, true);?>>
					<?php echo $option;
				}
			} else {
			 _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['field_type'] . ".php does not exist</div>", $this->plugin);
		 }
		 return ob_get_clean();
				} // END public function render_field_type ($field, $value) {
				/*
				* Checks if required pages are set
				*/
				public function fetch_page($tab, $page_slug)
				{
				$pages = $this->tab_settings($tab);
				if (isset($pages[$page_slug]) && $pages[$page_slug] && get_post($pages[$page_slug])) {
				 return get_post($pages[$page_slug])->post_name;
			 } else {
				 return "";
			 }
				} // END public static function  admin_menus (){
				/**
				* display_user_messages on the front end
				* @return string html text
				*/
				public function display_user_messages()
				{
					// Retreive user errors if any
				$errors = (isset($_SESSION[$this->plugin . '-errors'])) ? $_SESSION[$this->plugin . '-errors'] : array();
					// Retreive user errors sucess if any
				$success = (isset($_SESSION[$this->plugin . '-success'])) ? $_SESSION[$this->plugin . '-success'] : array();
				if (!empty($errors)) {
				 $messages = $errors;
				 $css_class = "user-errors";
				 unset($_SESSION[$this->plugin . '-errors']);
			 } elseif (!empty($success)) {
				 $messages = $success;
				 $css_class = "user-success";
				 unset($_SESSION[$this->plugin . '-success']);
			 } else {
				 $messages = array();
				 $css_class = "";
			 }
			 $html = "";
			 if (!empty($messages)) {
				 foreach ($messages as $code => $message) {
					$output = ($this->fetch_error_from_code($code) !== "unknown") ? $this->fetch_error_from_code($code) : $message;
					$html .= "<div class = '" . $css_class . "' >" . wpautop($output, true) . "</div>";
				}
			}
			return $html;
		}
				/**
				* Finds and returns a matching error message for the given error code.
				* @param  string $error_code error code
				* @return string            error message
				*/
				public function fetch_error_from_code($error_code)
				{
				extract($this->tab_settings($this->prefix . "_dashboard" . "_dialogs"));
				switch ($error_code) {
				 case 'incorrect_password':
				 $err = __("{$incorrect_password} <a href='%s'>Did you forget your password?</a>", $this->plugin);
				 return sprintf($err, wp_lostpassword_url());
				 case 'invalid_key':
				 return __('The password reset key has expired.  Please try again', $this->plugin);
				 default:
				 return __('unknown', $this->plugin);
				 break;
			 }
				} // END public function fetch_error_from_code( $error_code ) {
				/**
				* Registers post types
				* @param  string $slug Post type slug
				* @param  array $args arguments array
				*/
				public function register_post_types($slug, $args)
				{
				$result = register_post_type($slug, $args);
				if (is_wp_error($result)) {
				 add_action('admin_notices', function () {
					echo $this->display_admin_notices($result);
				});
			 }
		 }
				/**
				* Relaces brackets with options
				* @param aray $options bracketed options
				* @param string $text text to be modofied
				* @return string $text with bracketed items replaced by actual option values
				*/
				public function replace_bracketed_values($options, $text)
				{
				foreach ($options as $key => $value) {
				 $text = sanitize_file_name(lue, $text);
			 }
			 return $text;
				} // END public function replace_bracketed_values ($text) {
				/**
				* Adds errors/messages to the debug log
				* @param object/array/string $message
				*/
				public function log_message($message)
				{
				if (WP_DEBUG === true) {
				 if (is_array($message) || is_object($message)) {
					error_log(print_r($message, true));
				} else {
					error_log($message);
				}
			}
		}
				// /*
				//  * Admin menus
				//  */
				// public function admin_menus()
				// {
				//     return array(
				//         array(
				//             'page_title' => __($this->name, $this->plugin), // Text to be displayed in the browser window.
				//             'menu_title' => __($this->name, $this->plugin), // Text to be displayed for the menu
				//             'caps' => 'administrator', // The capability required for this page to be displayed to the user.
				//             'id' => $this->prefix . "_dashboard", // Unique id for this menu
				//             'callback' => function () {}, // Callback to output the menu (Handled by the first submenu in this case
				//             'dashicon' => 'dashicons-editor-customchar', // icon url
				//             'position' => '5', // menu position
				//         ),
				//     );
				// } // END public static function  admin_menus (){
				// /*
				//  * Admin submenus
				//  */
				// public function admin_submenus()
				// {
				//     return array(
				//         array(
				//             'parent_id' => "{$this->prefix}_dashboard", // The id name for the parent menu (or the file name of a standard WordPress admin page).
				//             'page_title' => __($this->name, $this->plugin), // Text to be displayed in the browser window.
				//             'menu_title' => __("Dashboard", $this->plugin), // Text to be displayed for the menu
				//             'caps' => 'administrator', // The capability required for this page to be displayed to the user.
				//             'id' => $this->prefix . "_dashboard", //Unique id for this menu
				//             'callback' => array($this, 'settings_page_render'),
				//         ),
				//         array(
				//             'parent_id' => "{$this->prefix}_dashboard", // The id name for the parent menu (or the file name of a standard WordPress admin page).
				//             'page_title' => __("Other", $this->plugin), // Text to be displayed in the browser window.
				//             'menu_title' => __("Other", $this->plugin), // Text to be displayed for the menu
				//             'caps' => 'administrator', // The capability required for this page to be displayed to the user.
				//             'id' => "{$this->prefix}_dashboard_Other", //Unique id for this menu
				//             'callback' => array($this, 'settings_page_render_other'),
				//         ),
				//     );
				// }
				/*
				* Admin sections
				*/
				public function support_page_tabs()
				{
				return array(
				 array(
					'id' => "{$this->prefix}_support_tab1",
								'title' => __('Tab1', $this->plugin), // Formatted title of the section. Shown as the heading for the section.
								'tab_title' => __('Tab1', $this->plugin), // Formatted title of the tab. Shown as the heading for the section.
							),
				 array(
					'id'  => "{$this->prefix}_support_tab2",
								'title' => __('Tab2', $this->plugin), // Formatted title of the section. Shown as the heading for the section.
								'tab_title' => __('Tab2', $this->plugin), // Formatted title of the tab. Shown as the heading for the section.
							),
				 array(
					'id' => "{$this->prefix}_support_tab3",
								'title' => __('Other', $this->plugin), // Formatted title of the section. Shown as the heading for the section.
								'tab_title' => __('Other', $this->plugin), // Formatted title of the tab. Shown as the heading for the section.
							)
			 );
			}



				/**
				* All possible field options
				* @return array of possible field options
				*/
				public function possible_field_options() {
				return array(
				 'id' => '',
				 'title' => '',
				 'css_class' => '',
				 'tab' => '',
				 'field_type' => '',
				 'field_size' => '',
				 'field_min' => '',
				 'field_max' => '',
				 'required' => false,
				 'readonly' => false,
				 'unique' => false,
				 'disabled' => false,
				 'pattern' => '',
				 'placeholder' => '',
				 'modal' => false,
				 'description' => '',
				 'option_none' => 'Select Option',
				 'default' => '',
				 'field_options' => '',
				 'textarea_cols' => '',
				 'textarea_rows' => '',
				 'multiple' => false
			 );
				} // END  public function possible_field_options() {





				/**
				* Default settings
				* @return array of default settings
				*/
				public function defaults($tab)
				{
				switch ($tab) {
				 case $this->prefix . "_dashboard" . '_settings':
				 return array(
					'CPT' => false,
					'WooMenuCart' => false,
					'Members' => false,
					'WooPDF' => false,
					'ContactForm' => false,
					'EmailSMTP' => false,
					'WooTidbits' => false,
				);
				 default:
				 $defaults = array();
				 break;
			 }
				} // END public function settings_defaults($tab) {








		/**
		* Called when the plugin is activated using register_activation_hook
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function activate() {

			// Declare global variable
			global $wpdb;
			
			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;

			// Store plugin options
			if( false === get_option($this->prefix)){ //Options does not exists (first activation of plugin) add option

				// Create plugin database table
				$db->create_db_table();
				
				// Reset auto increment if the table has been created
				if ( $db->table_exists()) {

					// Reset ID auto incerement
					if (true === $db->reset_auto_increment()) {

						// Add plugin options if the database creation is successful
						add_option( $this->prefix, $this->plugin_options());
					} else {
						wp_die(__($db->reset_auto_increment(), $this->plugin));
					}

				} else {
					wp_die(__("<h2> Database table does not exist, please contact your web master.  Sorry for the invonvenienc.</h2>", $this->plugin));
				}
	
			} elseif ( get_option($this->prefix) != $this->plugin_options()) { // Plugin version different then update
				
				// Update plugin database table
				$db->update_db_table();

				// Update options if the database table exists
				if ( $db->table_exists()) {

					// Add option if the database creation is successful
					update_option( $this->prefix, $this->plugin_options());

				} else {
					wp_die(__("<h2> Database table does not exist, please contact your web master.  Sorry for the invonvenienc.</h2>", $this->plugin));
				}

				
			}

		} // END public function activate() {




		/**
		*Called when the plugin is deactivated using register_deactivation_hook
		**/
		public function deactivate()
		{
		//Flush rewrite rules on plugin activation. (woocoommerce endpoint permalink)
			flush_rewrite_rules();
		} //END public function plugin_deactivate()



		/**
		* Register and Enque stylesheet and scripts.  Hooks into WP's wp_enqueue_scripts
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function enqueue_scripts()
		{
		// Enqueue Javascript
			//wp_register_script("{$this->plugin}-public-js", $this->url . "assets/js/public.js", array("jquery"), null, true);
		//wp_enqueue_script( "{$this->plugin}-public-js");
		// Enqueue Stylesheet
			//wp_register_style("{$this->plugin}-public-css", $this->url . "assets/css/public.css", array(), null, 'screen');
			//wp_enqueue_style("{$this->plugin}-public-css");
		} // END public function styles()



		/**
		 * Register and Enque admin stylesheet and scripts.  Hooks into WP's admin_enqueue_scripts
		 * @return  
		 */
		public function admin_enqueue_scripts() {
			
			// for Admin Dashboard Only // Embed the Script on our Plugin's Option Page Only
			if (is_admin() && isset($_GET['page']) && $_GET['page'] == $this->plugin ) {
      	
      		
      
				// Add the color picker css file
				//wp_enqueue_style('wp-color-picker');
				
				// Meta boxes javascript
				wp_enqueue_script('common');
				wp_enqueue_script('wp-lists');
				wp_enqueue_script('postbox');

				wp_enqueue_style( 'wp-color-picker');
				wp_enqueue_script( 'wp-color-picker');
				
				// Media uploader
				//wp_enqueue_media();
				
				//Enqueue Javascript
				//wp_register_script("{$this->plugin}-wp-media-uploader", $this->url . "assets/js/wp-media-uploader.js", array("jquery"), null, true);
				//wp_enqueue_script("{$this->plugin}-wp-media-uploader");
				
				// Register and Enqueue Javascript
				//wp_register_script("{$this->plugin}-admin-js", $this->url . "assets/admin-js.js", array("jquery"), null, true);
				//wp_enqueue_script("{$this->plugin}-admin-js");
				


				

				//Register and Enque/Load Google Visualization API
				wp_register_script('google-visualization-api','https://www.gstatic.com/charts/loader.js');
				wp_enqueue_script( 'google-visualization-api' );
				
				// Enqueue Stylesheet
				wp_register_style("{$this->plugin}-admin-css", $this->url . "assets/admin.css", array(), null, 'screen');
				wp_enqueue_style("{$this->plugin}-admin-css");

				// Register and Enqueue file upload Javascript and use wp_localize_script to pass data to the javascript handler
					wp_register_script( "{$this->prefix}_chart_handle", "{$this->url}assets/admin.js", array( "jquery" ), null, true );
					wp_enqueue_script( "{$this->prefix}_chart_handle");
					wp_localize_script( 
							"{$this->prefix}_chart_handle",  //handle for the script
							"{$this->prefix}_chart_object",     //  The name of the variable which will contain the data (used in the ajax url)
							array(  // Data to be passed 
								'url'      => admin_url( 'admin-ajax.php' ),
								'file_upload_action'   => "{$this->prefix}_file_upload_hook",
								'file_delete_action'   => "{$this->prefix}_file_delete_hook",
								'file_select_action'   => "{$this->prefix}_file_select_hook",
								'get_chart_action'   => "{$this->prefix}_get_chart_hook",
								'chart_list_action'   => "{$this->prefix}_chart_list_hook",
								'chart_save_action'   => "{$this->prefix}_chart_save_hook",
								'chart_update_action'   => "{$this->prefix}_chart_update_hook",
								'plugin'  => $this->plugin,
								'prefix'  => $this->prefix,
								'dashboard_template_id' => "{$this->prefix}-dashboard",
								"file_upload_nonce"  => wp_create_nonce("{$this->prefix}__{$this->file_upload_nonce}" ),
								"file_delete_nonce"  => wp_create_nonce("{$this->prefix}__file_delete_nonce" ),
								"file_select_nonce"  => wp_create_nonce("{$this->prefix}__{$this->file_select_nonce}" ),
								"get_chart_nonce"  => wp_create_nonce("{$this->prefix}__{$this->get_chart_nonce}" ),
								"chart_list_nonce"  => wp_create_nonce("{$this->prefix}__{$this->chart_list_nonce}" ),
								"chart_update_nonce"  => wp_create_nonce("{$this->prefix}__{$this->chart_update_nonce}" ),
								"chart_save_nonce"  => wp_create_nonce("{$this->prefix}__{$this->chart_save_nonce}" ),
							)
					);

					wp_enqueue_style("{$this->plugin}-jquery-ui-css", 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/cupertino/jquery-ui.css', false, "1.9.0", false);


					// Register and Enqueue chart view Javascript 
					// wp_register_script( "{$this->prefix}_chart_list_handle", "{$this->url}assets/chart-list.js", array( "jquery" ), null, true );
					// wp_enqueue_script( "{$this->prefix}_chart_list_handle");
					// wp_localize_script( 
					// 		"{$this->prefix}_chart_list_handle",  //handle for the script
					// 		"{$this->prefix}_chart_list_object",     //  The name of the variable which will contain the data (used in the ajax url)
					// 		array(  // Data to be passed 
					// 			'url'      => admin_url( 'admin-ajax.php' ),
					// 			'action'   => "{$this->prefix}_chart_list_hook",
					// 			'plugin'  => $this->plugin,
					// 			'prefix'  => $this->prefix,
					// 			'dashboard_template_id' => "{$this->prefix}-dashboard",
					// 			"nonce"  => wp_create_nonce("{$this->prefix}__chart_list_nonce" ),
					// 		)
					// ); 
					

			}

		} // END public function enqueue_scripts()






		 /**
    * Admin sections 
    */
    public function  admin_sections (){

      return array(  
         array(
         	"slug" 			=> "file_settings",
          	"id" 			=> "file-settings",
          	'title'  		=> __('File Settings', $this->plugin)
        ), 
          
         array(
          	"slug" 			=> "general_settings",
          	"id"				=> "general-settings",
          	'title'  		=> __('General Settings', $this->plugin)
        ), 
      );

    }








				/*
				* Admin Fields
				*/
				public function admin_fields() {

					// Instantiate the database class
					if ( class_exists("IWPGV\\Includes\\IWPGVDatabase")) $db = new IWPGVDatabase;

					// Retreive all spreadsheet files from the database
					$files = $db->find_all();
					if ($files) {
						$file_select_options = [];
						foreach($files as $file) {
							$file_select_options[$file->id] = $file->name; 
						}
					}

					$settings = get_option("{$this->prefix}_dashboard");
					//var_dump($settings);die;
					// $options = $this->tab_settings("{$this->prefix}_dashboard");
					// $woocommerce_inactive = !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
					// $notice = ($woocommerce_inactive) ? "<span class='notice notice-warning'>Please install and activate Woocommerce if you wish to use this module.  <a href ='https://wordpress.org/plugins/woocommerce/'>woocommerce</a></span>" : "";
					return array(
					
					// array(
					// 	'id' =>  'file_upload', // field id
					// 	'title' => __('File Upload', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
					// 	'submenu_page' => "{$this->prefix}_dashboard", // Section to which this field belongs
					// 	'section' => 'general',
					// 	'field_type' => 'file', // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
					// 	'description' => 'Enter file to upload', // Custom field description(supplied in the $args)
					// 	'value' => (isset($settings) && isset($settings['file_upload']) && $settings['file_upload']) ? $settings['file_upload'] : false,
					// ),
					// 
					
					

					array(
						"slug" => 'file_select', // field id
						"id" => "file-select",  // id attribute if the field
						'title' => __('Select File', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						'submenu_page' => "{$this->prefix}_dashboard", // Section to which this field belongs
						'section' => 'file_settings',
						'field_type' => 'select', // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						'field_options' => (isset($file_select_options))? $file_select_options : [],
						'option_none' => 'Select File',
						//'description' => 'Enable Custom Post Types', // Custom field description(supplied in the $args)
						'value' => (isset($settings) && isset($settings['file_select'])) ? $settings['file_select'] : "",
					),

					array(
						"slug" => 'file_upload', // field id
						"id" => "file-upload",  // id attribute if the field
						'title' => __('Upload File', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						'submenu_page' => "{$this->prefix}_dashboard", // Section to which this field belongs
						'section' => 'file_settings',
						'field_type' => 'file', // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						'css_class' => 'clickable file-upload btn btn-success',
						//'multiple' =>true,
						//'description' => 'Enable Custom Post Types', // Custom field description(supplied in the $args)
						'value' => (isset($settings) && isset($settings['file_upload'])) ? $settings['file_upload'] : "",
					),

					array(
						"slug" => 'sheet_select', // field id
						"id" => "sheet-select",  // id attribute if the field
						'title' => __('Select Sheet', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						'submenu_page' => "{$this->prefix}_dashboard", // Section to which this field belongs
						'section' => 'file_settings',
						'field_type' => 'select', // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						'field_options' => array(),
						'option_none' => 'Select Sheet',
						//'description' => 'Enable Custom Post Types', // Custom field description(supplied in the $args)
						'value' => (isset($settings) && isset($settings['sheet_select'])) ? $settings['sheet_select'] : "",
					),

					array(
						"slug" => 'chart_type_select', // field id
						"id" => "chart-type-select",  // id attribute if the field
						'title' => __('Select Chart Type', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						'submenu_page' => "{$this->prefix}_dashboard", // Section to which this field belongs
						'section' => 'file_settings',
						'field_type' => 'select', // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						'field_options' => $this->chart_types,
						'option_none' => 'Select Chart Type',
						//'description' => 'Enable Custom Post Types', // Custom field description(supplied in the $args)
						'value' => (isset($settings) && isset($settings['chart_type_select'])) ? $settings['chart_type_select'] : "",
					),

					array(
						"slug" => 'chart_title', // field id
						"id" => "chart-title",  // id attribute if the field
						'title' => __('Chart Title', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						'submenu_page' => "{$this->prefix}_dashboard", // Section to which this field belongs
						'section' => 'general_settings',
						'field_type' => 'text', // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						//'description' => 'Enable Custom Post Types', // Custom field description(supplied in the $args)
						'value' => (isset($settings) && isset($settings['chart_title'])) ? $settings['chart_title'] : "",
					),

					array(
						"slug" => 'chart_title_color', // field id
						"id" => "chart-title-color",  // id attribute if the field
						"css-class" => "color-picker",
						'title' => __('Chart Title Color', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						'submenu_page' => "{$this->prefix}_dashboard", // Section to which this field belongs
						'section' => 'general_settings',
						'field_type' => 'color-picker', // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						//'description' => 'Enable Custom Post Types', // Custom field description(supplied in the $args)
						'value' => (isset($settings) && isset($settings['chart_title_color'])) ? $settings['chart_title_color'] : "",
					),

			 );
				} // END  private  function register_fields (){






		/**
		* Class module name
		* @return string module name
		*/
		public function class_module()
		{
			return $this->prefix . "_dashboard";
		} // END public function class_module()



		/**
		* Settings API option
		* @return array settings_option
		*/
		public function tab_settings($tab)
		{
			if (!get_option($tab)) {
				return $this->defaults($tab);
			}
		// return options if at leat one option is not empty, otherwise return default
			foreach (get_option($tab) as $key => $val) {
				if (!empty($val)) {
					return get_option($tab);
				}
			}
			return $this->defaults($tab);
		} // END public function tab_settings($tab) {



	} // END class Dashboard {


} // END if (!class_exists('Dashboard'))