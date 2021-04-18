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
defined("ABSPATH") || die("No direct script access allowed!");

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
		public $chart_types = array("line" => "Line", "Bar" => "Bar", "column" => "Column", "PieChart" => "Pie"); // Possible cahrt types
		public $data_types = array("string", "number", "boolean", "date", "datetime", "timeofday"); // Possible cahrt types
		public $file_upload_nonce = "file_upload_nonce";
		public $file_select_nonce = "file_select_nonce";
		public $sheet_select_nonce = "sheet_select_nonce";
		public $refresh_chart_nonce = "refresh_chart_nonce";
		public $chart_list_nonce = "chart_list_nonce";
		public $chart_save_nonce = "chart_save_nonce";
		public $chart_update_nonce = "chart_update_nonce";
		public $min_file_id = 1235;
		public $file_id_increment = 3;
		public $min_chart_id = 12345;
		public $chart_id_increment = 3;
		public $template_id = IWPGV_PLUGIN."-dashboard";
		public $file_panel_id = "fileSettings";
		public $chart_panel_id = "chartSettings";
		public $default_file_name = "line-chart.xlsx";
		public $default_chart_type = "LineChart";



		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {
			
			// // Declare and set global variables
			// global $wpdb;
			// 
			//var_dump(class_exists("IWPGV\\Includes\\IWPGVDatabase"));die;
			//

			// Intialize wp errors
			$this->errors = new \WP_Error;
			$this->success = new \WP_Error;
			
			
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

					// Add ajax chart file selection capability
			add_action( "wp_ajax_{$this->prefix}_file_select_hook", array($this, 'file_select_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_file_select_hook", array($this, 'file_select_callback' )); // need this to serve non logged in users

					// Add ajax chart file selection capability
			add_action( "wp_ajax_{$this->prefix}_sheet_select_hook", array($this, 'sheet_select_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_sheet_select_hook", array($this, 'sheet_select_callback' )); // need this to serve non logged in users

			// Add ajax file delete capability. The hook tells javascript which method to process. The hook is either embeded in the form or passed to javascript using wp_localise_script
			add_action( "wp_ajax_{$this->prefix}_file_delete_hook", array($this, 'file_delete_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_file_delete_hook", array($this, 'file_delete_callback' )); // need this to serve non logged in users

		

			// Add ajax chart list capability
			add_action( "wp_ajax_{$this->prefix}_refresh_chart_hook", array($this, 'refresh_chart_callback' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_refresh_chart_hook", array($this, 'refresh_chart_callback' )); // need this to serve non logged in users
 
 
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
			
			return array(
				'version' => $this->version
			);

		} // END  public function plugin_options() {



	
		/**
		 * Ajax file upload handler
		 * @return array array of data
		 */
		public function file_upload_callback() {

			// Ajax comes with two arrays $_POST and $_FILES
			// echo json_encode($_FILES);wp_die();
			// echo json_encode($_POST);wp_die();
			
			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			try{

				// If request is not POST, not an ajax request or does not come the file upload form
				if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_file_upload_hook") 
				throw new \Exception (__("This is not a valid ajax file upload request", $this->plugin));

				// verify file upload nonce
				if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->file_upload_nonce}"  )) 
					throw new \Exception (__("An invalid nonce was submited", $this->plugin));

				// Check if no files were selected
				if (empty($_FILES)) 
					throw new \Exception ( __("File missing, please select a file", $this->plugin));

				// Extract file from $_FILES
				$file = $file_instance->extract_file($_FILES["files"]);

				//Check if there is a file and is an array
				if (! $file || ! is_array($file)) 
					throw new \Exception ( __("File Class method extract_file() failed to return a file or an array", $this->plugin));
				
				// If validate_file() returns WP/Error (validate_file() returns either true or WP/Error)
				if (is_wp_error($file_instance->validate_file($file))) 
					throw new \Exception ( __($this->compose_error_message($file_instance->validate_file($file)), $this->plugin));
				

				// Check if wp_handle_upload function exists
				if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

				// Attemtto upload file
				$movefile = wp_handle_upload( $file, array('test_form' => false));

				// If file upload fails
				if ( ! $movefile && isset( $movefile['error'] ) ) 
					throw new \Exception ( __($movefile['error'], $this->plugin));
					
				// Fetch spreadsheet data to check if data types for each sheet are valid
				$spreadsheet = $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));

				//wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
				//echo json_encode($spreadsheet);die;

				// Loop through all the sheets of each spreadsheet to check if colum data type is valid
				foreach ($spreadsheet as $key => $sheet) {
					// loop through the sheet data types
					foreach($sheet["dataTypes"] as $col_key => $data_type ) {
						if (! isset($data_type) || ( isset($data_type) && ( $data_type === null || ! in_array($data_type, $this->data_types)))) {
							$invalid_data_type = true;
							$col_data_type = (isset($data_type))? $data_type : "Null";
							$sheet_name = $sheet["sheetName"];
							$sheet_column = $col_key;
							break 2;
						}
					}
				}

				// If all data types are valid
				if (isset($invalid_data_type)) {
					$error = "File: <strong>". sanitize_file_name($file["name"]) . "</strong> Sheet <strong>{$sheet_name}</strong> column <strong>{$sheet_column}</strong> data type <strong>{$col_data_type}</strong> is not a valid data type.  Allowed file extensions are  <strong>".implode(", ", $this->data_types)."</strong>This file was not uploaded.";
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __($error, $this->plugin));

				}

				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

				// Retreive files
				$files = (isset($settings["files"]))? $settings["files"] : array();

				// If no files exists, initialise file id to start at $this->min_file_id (file id will be incremented by $this->file_id_increment for each new file)
				if (empty($files)) {
					$file_id = $this->min_file_id;
				
				// If there are file in the database, find max file id and increment by 1
				} else {
					$max_file_id = $this->min_file_id;
					foreach (array_keys($files) as $file_id) {
						if ($file_id > $max_file_id) $max_file_id = $file_id;
					}

					// Increment max file id by 1
					$file_id = $max_file_id + $this->file_id_increment;
				}

				// Prepare file for insertion into database table
				$file_parts = array(
					"fileId" => $file_id,
					"fileName" => $file["name"],
					"fileType" => $file["type"],
					"fileSize" => $file["size"],
				);

				// If for some reason the file with this file id exists (highly unlikely)
				if (isset($files[$file_id])) {
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __("This file {$file["name"]} (ID = {$file_id}) should not be in option <strong>{$this->prefix}_dashboard", $this->plugin));
				}
				
				// Add new file to the files array
				$files[$file_id] = $file_parts;
				
				// Update 
				$settings["files"] = $files;


				if (! update_option("{$this->prefix}_dashboard", $settings)) {
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __("Option <strong>{$this->prefix}_dashboard update failed", $this->plugin));
				}

				// compose file selection option
				$file_select_option = "<option value='{$file_id}' selected = 'selected' >{$file["name"]}</option>";

				// Compose sheet selection form
				$sheet_select_options = $this->compose_sheet_select_options($spreadsheet, $file_id);

				// Assemble success message
				$message = "<strong>{$file["name"]}</strong> is valid.\n";
				$message .= ($file["name"] != sanitize_file_name($file["name"]))? "This file was converted to <strong>".sanitize_file_name($file["name"]) . "</strong> and was " : "This file was ";
				$message .= "uploaded successfully and file ID ({$file_id}) inserted into the database table succesfully.\n";
				$message = "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";
			

			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";
			}

			// Compose response
			$response = array(
				"fileName" => (isset($file["name"]))? $file["name"] : "",
				"fileId" => (isset($file_id))? $file_id : null,
				//"spreadsheet" => (isset($spreadsheet))? $spreadsheet : array(),
				"sheetSelectOptions" => (isset($sheet_select_options))? $sheet_select_options : null,
				"fileSelectOption" => (isset($file_select_option))? $file_select_option : null,
				"message" => $message,
				"post" =>$_POST,
				"spreadsheet" => $spreadsheet
				//"files" => get_option("{$this->prefix}_dashboard")["files"]
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
			// echo json_encode($_POST);wp_die();

			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			try {

				// Bail if request is not POST, not an ajax request or does not come the file upload form
				if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_file_select_hook") 
					throw new \Exception ( __("This is not a valid ajax file select request", $this->plugin));
				
					// verify file upload nonce
				if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->file_select_nonce}"  )) 
					throw new \Exception ( __("An invalid nonce was submited", $this->plugin));
				
				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();


				// Retreive files settings options
				$file = (isset($settings["files"]))? $settings["files"][$_POST["fileSelect"]] : null;

				// If file exists
	  			if ( !$file) 
					throw new \Exception ( __("No file with ID = {$_POST["fileSelect"]} was found", $this->plugin));

				//echo json_encode($file);die;
				
				// Fetch spreadsheet data 
				$spreadsheet = $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]));

				// Fetch file selection foem and sheet selection form
				$sheet_select_options = $this->compose_sheet_select_options($spreadsheet, $_POST["fileSelect"]);

				// Add success message
				$message = __("File selection successful", $this->plugin);

				$message = "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";

				// //Loop through admmin fields to populate chart options
				// foreach ($this->admin_settings() as $setting_group) {
				// 	foreach($setting_group["subsections"] as $subsection) {
				// 		foreach($subsection["fields"] as $field) {
				// 			if  (array_keys($field)[0]) {
				// 				$unique_fields[$field["id"]] = (isset($field["uniqueTo"]))? $field["uniqueTo"] : null;
				// 			} else {
				// 				foreach($field as $sub_field) {
				// 					$unique_fields[$sub_field["id"]] = (isset($sub_field["uniqueTo"]))? $sub_field["uniqueTo"] : null;
				// 				}
				// 			}
				// 		}
				// 	}
				// }

	  		
			}  catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Compose response
			$response = array(
				"fileName" => (isset($file["fileName"]))? $file["fileName"] : "",
				"fileId" => (isset($file["fileId"]))? $file["fileId"] : null,
				//"spreadsheet" => (isset($spreadsheet))? $spreadsheet : array(),
				"sheetSelectOptions" => (isset($sheet_select_options))? $sheet_select_options : null,
				//"fileSelectOption" => (isset($file_select_option))? $file_select_option : null,
				"message" => $message,
				"post" =>$_POST,
				//"uniqueFields" => $unique_fields,
				//"files" => get_option("{$this->prefix}_dashboard")["files"]
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
		public function refresh_chart_callback() {

			 echo json_encode($_POST);wp_die();

			try {
				// Bail if request is not POST, not an ajax request or does not come the file upload form
				if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_refresh_chart_hook")
					throw new \Exception ( __("This is not a valid ajax get chart request", $this->plugin));
				
					// verify file upload nonce
				if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->refresh_chart_nonce}"  )) 
					throw new \Exception (__("An invalid nonce was submited", $this->plugin));

				// verify if a file was selected (file id submitted)
				if (! ($_POST["fileSelect"])) 
					throw new \Exception (__("No file selected.  Please slect a file", $this->plugin));

				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

				// Retreive files settings options
				$file = (isset($settings["files"]))? $settings["files"][$_POST["fileSelect"]] : null;

				// If there is no file
	  			if ( ! $file) 
	  				throw new \Exception (__("No file with ID = {$_POST["fileSelect"]} was found", $this->plugin));

				// Fetch spreadsheet data 
				$sheet = $this->fetch_sheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]), $_POST["sheet"]);

				//echo json_encode($sheet);die;

				// If errors set errors
				if (is_wp_error($sheet)) 
					throw new \Exception ( __($this->compose_error_message($sheet), $this->plugin));

				$unique_fields = array();

				
					//Loop through admmin fields to populate chart options
				foreach ($this->admin_settings() as $setting_group) {
					foreach($setting_group["subsections"] as $subsection) {
						foreach($subsection["fields"] as $field) {
							if  (array_keys($field)[0]) {
								if (isset($field["uniqueTo"])) $unique_fields[$field["id"]] =  $field["uniqueTo"];
							} else {
								foreach($field as $sub_field) {
									if (isset($sub_field["uniqueTo"])) $unique_fields[$sub_field["id"]] = $sub_field["uniqueTo"];
								}
							}
						}
					}
				}


					// switch ($this->default_chart_type) {
						// 	case 'Line':
						// 		$chart_options = $this->line_chart_default_options($field_values);
						// 		break;
									
						// 		case 'PieChart':
						// 		$chart_options = $this->pie_chart_default_options($field_values);
						// 		break;
							
						// 	default:
						// 		$chart_options = $this->default_chart_options($field_values);

						// 		break;
						// }
				//echo json_encode($unique_fields);die;

				// set sheet name
				//$sheet_name = $sheet["sheetName"];

				// Loop through all admin fields to set the chart options
				// $options = [];
				// foreach ($this->admin_fields() as $field) {
				// 	$options[$field["id"]]= (isset($_POST[$field["id"]]))? $_POST[$field["id"]]  : field["default"];
				// }
				// $default_chart_options = $this->default_chart_options($options);
				
				// Add success message
				//$message = "<div class='notice notice-success is-dismissible'><p>Refresh successful</p></div>";

			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Set google dashboard, chart, and filter div id
			//$chart_id = "editChart";
			//$chart_id = " New Chart";

			// Compose response
			$response = array(
				"fileName" => (isset($file["fileName"]))? $file["fileName"] : "",
				"fileId" => (isset($_POST["fileSelect"]))? $_POST["fileSelect"] : null,
				"sheet" => $sheet,
				//"chartOptions" => $default_chart_options,
				//"message" => $message,
				"divId" => "editChart",  //(isset($_POST["chartId"]) && $_POST["chartId"])? $_POST["chartId"] : "editChart",
				"uniqueFields" => $unique_fields,
				"post" =>$_POST,
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
		public function chart_save_callback() {

			echo json_encode($_POST);wp_die();
			
			try {
				// Bail if request is not POST, not an ajax request or does not come the file upload form
				if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_chart_save_hook") 
					throw new \Exception ( __("This is not a valid ajax chart save request", $this->plugin));
				
					// verify file upload nonce
				if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->chart_save_nonce}"  )) 
					throw new \Exception ( __("An invalid nonce was submited", $this->plugin));

				// verify if a file was selected (file select id submitted)
				if (! $_POST["fileSelect"] )
					throw new \Exception ( __("No file selected.  Please slect a file", $this->plugin));

				// verify if a sheet was selected (sheet id submitted)
				if ( $_POST["sheet"] == "") 
					throw new \Exception ( __("No sheet selected.  Please slect a sheet for this file", $this->plugin));

				// verify if a chart type was selected
				if ( $_POST["chartType"] == "") 
					throw new \Exception ( __("No chart type selecte.  Please slect a chart type.", $this->plugin));

				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

				// Retreive charts
				$charts = (isset($settings["charts"]))? $settings["charts"] : array();

				// // If no charts exists, initialise chart id to start at $this->min_file_id (chart id will be incremented by $this->file_id_increment for each new chart)
				if ( empty($charts)) {

					// start a new chart id minimum 
					$chart_id = $this->min_chart_id;

				// If chart being edited
				} elseif (isset($_POST{"chartId"}) &&  $_POST["chartId"]) {

					$chart_id = $_POST["chartId"];

				// If there are charts and no id submitted  (new chart)
				} else {

					$max_chart_id = $this->min_chart_id;
					foreach (array_keys($charts) as $chart_id) {
						if ($chart_id > $max_chart_id) $max_chart_id = $chart_id;
					}

					// Increment max file id by 1
					$chart_id = $max_chart_id + $this->chart_id_increment;
				}

				//echo json_encode($chart_id);die;
				

				// Prepare chart options
				$chart_options = array( );
				foreach ($this->admin_fields() as $option_group_id => $option_group) {

						foreach($option_group["options"] as $option => $field) {

							if ($field["id"] != "fileUpload") {

								$index = explode(".", $field["id"]);
								$index = implode("_", $index);

								if (isset($_POST[$index])) {
									$chart_options[$field["id"]] = $_POST[$index];
								}
							}
						}

				}

				//echo json_encode($chart_id);die;

				// Add chart Id to chart array
				$chart_options["chartId"] = $chart_id; 

				// If for some reason the chart with this id exists (highly unlikely)
				// if (isset($charts[$chart_id])) {
				// 	wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
				// 	throw new \Exception ( __("This chart (ID = {$chart_id}) should not be in option <strong>{$this->prefix}_dashboard", $this->plugin));
				// }
				
				// Add new chart to the charts array
				$charts[$chart_id] = $chart_options;
				
				// Update settings options
				$settings["charts"] = $charts;

				//echo json_encode($settings);die;


				if (! update_option("{$this->prefix}_dashboard", $settings)) {
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __("Option <strong>{$this->prefix}_dashboard update failed", $this->plugin));
				}

				// Add success message
				$message = "<div class='notice notice-success is-dismissible'><p>Chart (ID = {$chart_id} )saved successfully</p></div>";
				
			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Compose response
			$response = array(
				"chartId" => $chart_id,
				"message" => $message,
				"post" =>$_POST,
				//"redirect" => add_query_arg(array("page" => $this->plugin, "action" => "editChart", "chartId" => $chart_id), admin_url('admin.php'))
			);

			// return ajax data
			echo json_encode($response);
			wp_die();
			

		}  // END public function contact_form_process() {







		/**
		 * Composes file select form
		 * @return Array         Array contains a file selection from (String) and a sheet selection form (String)
		 */
		public function compose_file_select_options ($file_id)  {

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
		public function compose_sheet_select_options ($spreadsheet, $selected = null)  {

			//echo json_encode($spreadsheet);wp_die();

			// Compose sheet selection form
			//$sheet_select_form = "<form id ='sheet-selection-form'>";
			//$sheet_select_form .= "<div class ='sheet'>";
			//$sheet_select_form .= "<select id='sheet-select' name='".$this->prefix."_dashboard[sheet-select]' >";
			//$sheet_select_form .= "<option value=''>Select a Sheet</option>";
			$sheet_select_options = "";
			foreach ($spreadsheet as $sheet_key => $sheet) {
				$sheet_select_options .= "<option value='".(intval($sheet_key))."' ".selected(intval($sheet_key), $selected, false).">{$sheet["sheetName"]}</option>";
			}
			//$sheet_select_form .= "</select>";
			//$sheet_select_form .= "</div>";
			//$sheet_select_form .= "</form>";

			return $sheet_select_options;

		}






			/**
		 * Composes sheet select forms to be displayed on the file select form
		 * @param  array $sheeets Array of sheets from a given spreadsheet file
		 * @return Array         Array contains a file selection from (String) and a sheet selection form (String)
		 */
		public function range_slider_select_options ($sheet)  {

			$range_slider_options = "";
			foreach ($sheet["labels"] as $col_index => $label) {
				$range_slider_options .= "<option value='{$col_index}'>{$label}</option>";
			}
			//$sheet_select_form .= "</select>";
			//$sheet_select_form .= "</div>";
			//$sheet_select_form .= "</form>";

			return $range_slider_options;

		}


		






		/**
		 * Composes error and success messsages
		 * @param  WP/Error $errors  wp error object
		 * @param  WP/Error $success wp error object (success)
		 * @return string          error and success message string
		 */
		public function compose_error_message ($errors = null)  {

			// Prepare error output
			$message = "";
			if ($errors && !empty($errors->errors)) {
				//$message .= "<div class='notice notice-error is-dismissible'>";
				foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $output) {
					$message .= "<p>{$output}</p>";
				}
				//$message .= "</div>";
			}

			return $message;

		} // END function compose_message ($errors, $success)  {






		/**
		 * Composes error and success messsages
		 * @param  WP/Error $errors  wp error object
		 * @param  WP/Error $success wp error object (success)
		 * @return string          error and success message string
		 */
		public function compose_message ($errors = null, $success = null)  {

			//var_dump($errors);die;


			// Prepare error output
			$message = "";
			if ($errors && !empty($errors->errors)) {
				$message .= "<div class='notice notice-error is-dismissible'>";
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
				$this->errors->add("invalid_nonce", __("An invalid nonce was submited", $this->plugin));

			// Bail if errors
			if (!empty($this->errors->errors)) {
				$response = "<div class='notice notice-error is-dismissible'>";
				 foreach (array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages()) as $code => $output) {
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
			$data = $this->fetch_spreadsheet("{$this->path}assets/img/test-spreadsheet.xlsx");

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
		* @param   string $file_path (spreadsheet file name)
		* @return  array $data (both raw and formatted Spreadsheet cols and rows data)
		* @version  0.1
		*/
		public function fetch_spreadsheet($file_path)
		{
			// Initialize array that will contain all the sheets from each spreadsheet
			//$formatted_data = [];
				
			// Identify input file name
			//$input_file_path = wp_upload_dir()['path']."/".$file->file_path;
			

			$sheet_data = [];

			// Instantiate the File class
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			// Check if the file is already in the upload directory
			if ( ! file_exists ($file_path)) {
				$this->errors->add("file_does_not_exists", __("File <strong>{$file_path}</strong> does not exist", $this->plugin));
				return $errors;
			}
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

				// Check file extension is allowed.  Alllowed extensions are defined at the begening of the File class
			if ( ! in_array($file_type, $file_instance->file_types)) {
				$error = $file_instance->file_extension()." file type is not allowed. Allowed file extensions are  ".implode(", ", $file_instance->file_types).".  This file was not uploaded.";
				$this->errors->add("invalid_file_type", __($error, $this->plugin));
				return $errors;
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

				//echo json_encode($raw_data);die;

				//$data = $this->weed_out_null_columns ($raw_data);
				//$data = $raw_data;

				// Retreive labels
				$labels = array_shift($data);

				// Retreive raw_data type (second row
				$data_types = array_shift($data);

			


			

				// // Weed out columns with null label and data type values
				// $new_labels = [];
				// $new_data_types = [];
				// foreach ($labels as $key => $label) {
				// 	if (isset($label) && $label && isset($data_types[$key]) && $data_types[$key]) {
				// 		$new_labels[$key] = $label;
				// 		$new_data_types[$key] = $data_types[$key];
				// 	}
				// }

				// // Prepare the remaining data for Google visualization
				// $cols = [];
				// foreach($new_labels as $key => $label) {
				// 	$cols[] = ['id' => $key, 'label' => $label, 'pattern' => '', 'type' => $data_types[$key]];
				// }

				// // Set Google visualization data rows
				// $cell_data = [];
				// foreach($data as $key => $rows) {
				// 	$cells = [];
				// 	foreach ($rows as $cell => $value) {
				// 		$cells[] = ["v" => floatval($value), "f" => null];
				// 	}
				// 	$cell_data[] = ["c" =>$cells];
				// }

				// $formatted = array( "cols" =>$cols, "rows" => $cell_data);

				// Prepareadata for output (data are identified by spreadsheet number and sheet number in the series)
				//$formatted_data[$sheet_key]["sheet-name"] = $sheet_name;
				//$formatted_data[$sheet_key]["data-types"] = $new_data_types;
				$sheet_data[$sheet_key]["labels"] = $labels;
				$sheet_data[$sheet_key]["data"] = $data;
				$sheet_data[$sheet_key]["dataTypes"]  = $data_types;
				$sheet_data[$sheet_key]["sheetName"]  = $sheet_name;

			}

			// Instantiate the file class to validate and upload files
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			//$output["file-path"] = $file_path;
			//$output["file-name"] = $file_instance->file_parts($file_path)["basename"];
			
			

			// Return both raw and formatted datat (formatted data for google visualization)
			//return $sheet_data;
			//
			//// Return sheet name and formatted data (formatted data for google visualization)
			// return array(
			// 	"sheetName" => $sheet_name,
			// 	"data" => $data, //$formatted
			// 	"labels" => $labels,
			// 	"dataTypes" => $data_types
			// );

			return $sheet_data;


		} // END public function fetch_spreadsheet($file_path)






	
		/**
		 * Fetches sheet name and sgeet data from a spreadsheet
		 * @param  String  $file_path path to file being read
		 * @param  string $sheetId   id (index) of the sheet from the spreadhsett
		 * @return Array             sheet name and formatted sheet data
		 */
		public function fetch_sheet($file_path, $sheetId =  false) {

			// Initialize WP Error
			//$errors = new \WP_Error;
			//$success = new \WP_Error;

			//ile_path = "{$this->path}assets/charts.xlsx";

			// Instantiate the File class
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			// Check if the file is already in the upload directory
			if ( ! file_exists ($file_path)) {
				$this->errors->add("file_does_not_exists", __("File <strong>{$file_path}</strong> does not exist", $this->plugin));
				return $errors;
			}

			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

			// Check file extension is allowed.  Alllowed extensions are defined at the begening of the File class
			if ( ! in_array($file_type, $file_instance->file_types)) {
				$error = $file_instance->file_extension()." file type is not allowed. Allowed file extensions are  ".implode(", ", $file_instance->file_types).".  This file was not uploaded.";
				$this->errors->add("invalid_file_type", __($error, $this->plugin));
				return $errors;
			}

			// Create a new Reader of the type that has been identified
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

			// Advise the Reader that we only want to load cell data (no fprmating)
			$reader->setReadDataOnly(true);

			// Load $input_file_path to a Spreadsheet Object
			$spreadsheet = $reader->load($file_path);
			
			// Retreive sheet name
			$sheet_name = $spreadsheet->getSheetNames()[$sheetId];
		
			// Convert data in each spreadsheet to array
			$data = $spreadsheet->getSheet($sheetId)->toArray(
				NULL,        // Value that should be returned for empty cells
				TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
				TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
				TRUE         // Should the array be indexed by cell row and cell column
			);

			//echo json_encode($raw_data);die;


			
			//$data = $this->weed_out_null_columns($raw_data);
			//$data = $raw_data;

			//echo json_encode($raw_data);die;


			// Retreive labels
			$labels = array_shift($data);

			// Retreive raw_data type (second row
			$data_types = array_shift($data);

			// $new_labels = [];
			// $new_data_types = [];
			// foreach ($labels as $key => $label) {
			// 	if (isset($label) && $label && isset($data_types[$key]) && $data_types[$key]) {
			// 		$new_labels[$key] = $label;
			// 		$new_data_types[$key] = $data_types[$key];
			// 	}
			// }

			// // Prepare the remaining data for Google visualization
			// $cols = [];
			// foreach($new_labels as $key => $label) {
			// 	$cols[] = ['id' => $key, 'label' => $label, 'pattern' => '', 'type' => $data_types[$key]];
			// }

			// // Set Google visualization data rows
			// $cell_data = [];
			// foreach($data as $key => $rows) {
			// 	$cells = [];
			// 	foreach ($rows as $cell => $value) {
			// 		if ($value != null) {
			// 			$cells[] = ["v" => floatval($value), "f" => null];
			// 		} else {
			// 			continue 2;
			// 		}
			// 	}
			// 	$cell_data[] = ["c" =>$cells];
			// }

			// $formatted = array( "cols" =>$cols, "rows" => $cell_data);

			// Return sheet name and formatted data (formatted data for google visualization)
			return array(
				"sheetName" => $sheet_name,
				"data" => $data, //$formatted
				"labels" => $labels,
				"dataTypes" => $data_types
			);


		} // END public function fetch_spreadsheet($file_path)






		// public function weed_out_null_columns ($raw_data) {

		// 	// Weed out all columns that do not have null labels
		// 	// Initial an empty to hold all valid column indeces	

		// 	$valid_cols =array();

		// 	// Loop through the lables row and populate valid indices array
		// 	foreach ($raw_data[1] as $col_index => $label) {
		// 		if ($label !== null) array_push($valid_cols, $col_index);
		// 	}



		// 	// Intial new data array
		// 	$data = array();

		// 	// Loop through all the spreadsheet data to populate the new data array (without null co,imns)
		// 	foreach($raw_data as $row_key => $row) {
		// 		$new_row = array();
		// 		foreach($row as $col_index => $cell) {
		// 			if (in_array($col_index, $valid_cols)) array_push($new_row, $cell);
		// 		}
		// 		array_push($data, $new_row);
		// 	}


		// 	return $data;

		// }












		
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
		
			// Register dashboard submenu settings
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

			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

				// extract charts
				$charts = ($settings && isset($settings["charts"]))? $settings["charts"] : array();

				// extract files
				$files = ($settings && isset($settings["files"]))? $settings["files"] : array();

			
			// This code is executed if a new chart request or edit request is initiated
			if (isset($_GET["action"])) {

				$divId = "editChart";


				// Set default file name if no chart is being edited
				$file_name = "New Chart"; //$file_instance->file_parts("{$this->path}assets/{$this->default_file_name}")["basename"];
				//$file_name = $file_instance->file_parts("{$this->path}assets/{$this->default_file_name}")["basename"];
				$sheet =null;








				// Get chart id
				//$chart_id = (isset($_GET["chartId"]))? $_GET["chartId"] :  "editChart";

				// If action is add new chart
				if ($_GET["action"] == "addNewChart") {

					// Fetch first sheet(index = 0) of default spreadshheet
					$sheet = $this->fetch_sheet("{$this->path}assets/{$this->default_file_name}", 0);
					
					// If errors set errors
					if (is_wp_error($sheet)) {
						$this->errors = $sheet;
					// If no errors 
					} else {


						// Set default file name if no chart is being edited
						$file_name = $file_instance->file_parts("{$this->path}assets/{$this->default_file_name}")["basename"];


						//Loop through admmin fields to populate chart options with default field values
						$field_values = array();
						foreach ($this->admin_settings($this->default_chart_type, null) as $panel) {
							//echo"<pre>";var_dump($panel); echo "<br><br>";
							foreach($panel["subpanels"] as $subpanel) {
								//if (! isset($subpanel["fields"] ) || ! $subpanel["fields"] ) //echo "ppppp<br>"; var_dump($subsection); //continue;
								foreach($subpanel["fields"] as $field) {
									if  (array_keys($field)[0]) {
										$field_values[$field["id"]] = (isset($field["default"]))? $field["default"] : null;
									} else {
										foreach($field as $sub_field) {
											//var_dump($sub_field);
											$field_values[$sub_field["id"]] = (isset($sub_field["default"]))? $sub_field["default"] : null;
										}
									}
								}
							}

						}


						$chart_options = $this->default_chart_options($field_values, $this->default_chart_type);

						// echo "<pre>";
						// var_dump($chart_options);
						// die;


						// Initialize series index
						$i = 0;
						$subpanels = array();
						
						// Get all sheet labels except for the first column
						$series = $sheet["labels"];
						array_shift($series);

						// Loop through all the label columns to create series for each column of the chart
						foreach($series as $label_key => $label) {


							$trendlines = ($this->admin_settings($this->default_chart_type, $i)["trendlines"]);

							//Get the first array of the trendline
							//$selected_sub_section = $selected_section["subsections"];
							$subpanels[$i]["id"] = $label_key;
							$subpanels[$i]["title"] = $label;
							$subpanels[$i]["fields"] = array_shift($trendlines["subpanels"])["fields"];
							
							$i++;
						}


						$trendlines = array(
							"id" => "trendlines",
							"title" => "Trendlines",
							"subpanels" => $subpanels
						);

						//Loop through the trendlines field options populate the series options with default values
						//foreach ($trendlines as $panel) {
							//echo"<pre>";var_dump($panel); echo "<br><br>";
							//$field_values = array();
							foreach($trendlines["subpanels"] as $subpanel) {
								//if (! isset($subpanel["fields"] ) || ! $subpanel["fields"] ) //echo "ppppp<br>"; var_dump($subsection); //continue;
								foreach($subpanel["fields"] as $field) {
									if  (array_keys($field)[0]) {
										if (isset($field["default"])) $chart_options[$field["id"]] = $field["default"];
									} else {
										foreach($field as $sub_field) {
											if (isset($sub_field["default"])) $chart_options[$sub_field["id"]] = $sub_field["default"];
											//$field_values[$sub_field["id"]] = (isset($sub_field["default"]))? $sub_field["default"] : null;
										}
									}
								}
							}

						//}
						



						// echo "<pre>";
						// var_dump($trendlines);
						// die;

					

			

									
					
						
						//$output = array();
						ob_start();?>
	

						<!-- <button id='trendlines' class='accordionButton'>Trendlines</button>
						<div class='panel trendlines' > -->
							<div class='subPanel accordion' >
								<?php

								// Loop through admmin fields -->
								foreach ($trendlines["subpanels"] as $panel_key => $panel) {
									//if ($series_options_key == 0) continue;
								 	if (count($trendlines["subpanels"]) != 1) :?>
										<button class ="accordionButton" ><?php echo $panel["title"] ?></button>
											<div class = "panel" id="<?php echo $panel["id"] ?>">
									<?php  endif;

												foreach($panel["fields"] as $field) {
													if  ( array_keys($field)[0]) {
														$this->render_field_type_template($field);
													} else {
														?><div class="multipleFields"><?php
															foreach($field as $sub_field) {
																$this->render_field_type_template($sub_field);
															}
														?></div><?php
													}
												}

											if (count($trendlines["subpanels"]) != 1) :?>
											</div><!-- .panel -->
											<?php endif ?>
							
								<?php } ?>
							</div>
							<!-- </div> -->
						<?php

						$trendline_fields = ob_get_clean();
						ob_end_flush();

					}



				} elseif ($_GET["action"] == "editChart") {

					// return to main dashboard page if no settings or no charts are
					if (! $settings || ! $charts ) {
						wp_redirect( add_query_arg(array("page" => $this->plugin), admin_url('admin.php')) );
						exit;
					}

					// If there is no chart id
	  				if ( ! $_GET["chartId"]) 
	  					$this->errors->add("chart_id_not_found", __("No chart with ID = {$_GET["chartId"]} was found", $this->plugin));

	  				// Get chart
					$chart = (isset($charts))? $charts[$_GET["chartId"]] : array();

					// check if a file is found
					if ( empty($chart)) {
						$this->errors->add("chart_not_found", __("Chart with ID = ({$_GET["chartId"]}) was not found in the database", $this->plugin));
					} else {

						// get file
						$file = $files[$chart["fileSelect"]];

						// Fetch sheet data 
						$sheet = $this->fetch_sheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]), $chart["sheet"]);

						// If errors set errors
						if (is_wp_error($sheet)) 
							$this->errors->add("sheet_not_found",__($this->compose_error_message($sheet), $this->plugin));

						$file_name = $file["fileName"];

						// Fetch spreadsheet data 
						$spreadsheet = $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file_name));

						// Fetch file selection foem and sheet selection form
						$sheet_select_options = $this->compose_sheet_select_options($spreadsheet, $chart["sheet"]);

						
					}

				}	
			
				// Add/Edit chart meta box				
				add_meta_box(
					"{$this->plugin}-edit-chart-metabox",
					"{$file_name} - {$sheet["sheetName"]}",
					function ($object, $params) { // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
						$divId = $params["args"]["divId"];
						$action = $params["args"]["action"];

						echo $this->chart_metabox_render($divId, $action);
					},
					$this->dashboard_hook,
					'normal',
					'default',
					array("divId" => $divId, "action" => $_GET["action"])
				);

				// Add admin fields metabox
				add_meta_box(
					"{$this->prefix}-admin-fields",
					"Admin Fields",
					function ($object, $params) { // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
						
						$chart = $params["args"]["chart"];
						$sheet = $params["args"]["sheet"];
					
						echo $this->admin_fields_metaboxes_render($chart, $sheet);
					
					},
					$this->dashboard_hook,
					'side',
					'default',
					array(
						"chart" => (isset($chart))? $chart : array(),
						"sheet" => (isset($sheet))? $sheet : array()
					)
				);
				

				// Prepare output
				$response = array(
					"divId"		=> "editChart",
					"sheet"			=> $sheet,
					"fileName" 	=> $file_name,
					"chartType"  => (isset($chart) && isset($chart["chartType"]))? $chart["chartType"] : $this->default_chart_type,
					"chartOptions"	=> (isset($chart_options))? $chart_options : array(),
					"error" => $this->compose_message($this->errors, $this->success),
					"get" =>$_GET,
					"sheetSelectOptions" => (isset($sheet_select_options))? $sheet_select_options : null,
					"trendlines" => $trendline_fields
				);

				//send ajax data
				wp_localize_script( 
					"{$this->prefix}_handle",  //handle for the script
					"{$this->prefix}_chart_object",     //  The name of the variable which will contain the data (used in the ajax url)
					array(  // Data to be passed 
						"response"  => $response
					)
				);

				// Render page template
				echo $this->get_template_html("edit-chart");

			// If not new chart or edit chart request is submitted
			} else {

				
			

				// var_dump("<pre>");
				// var_dump($settings);

				// If no charts are found
				if (empty( $charts )) {  

					add_meta_box(
						"{$this->prefix}_dashboard_no_chart",  // meta box ID
						'No Chart', // meta box title
						function ($object, $params)  {	echo "No charts found"; },// object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
						$this->dashboard_hook,  // meta box screen
						'normal',  // meta box context (normal, side or advanced)
						'default',  // The priority within the context where the boxes should show ('high', 'low')
						array()  // Data that should be set as the $args property of the box array (second parameter passed to your callback).
					);

					//send ajax data
					wp_localize_script( 
						"{$this->prefix}_handle",  //handle for the script
						"{$this->prefix}_chart_object",     //  The name of the variable which will contain the data (used in the ajax url)
						array(  // Data to be passed 
							"response"  => null
						)
					);

				// If there are charts 
				} else {

					//var_dump($charts);
					
					// Initialise charts list data
					$chart_list = array();

					// Loop through all records
					foreach ($charts as $chart_id => $chart) {					

						// Retreive file for this chart
						$file_id = (isset($chart["fileSelect"]))?  $chart["fileSelect"] :  null;

						// If there is no file
	  					if ( ! $file_id) 
	  					$this->errors->add("id_{$chart_id}_not_found", __("No file ID = {$chart["fileSelect"]} was found", $this->plugin));

	  					// get file
	  					$file = $files[$file_id];

	  					// check if a file is found
						if (! $file)
							$this->errors->add("file_{$chart_id}_not_found", __("ID = {$chart["fileSelect"]} submitted but no file with this ID was found in the database", $this->plugin));
						
						// Fetch sheet data 
						$sheet = $this->fetch_sheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]), $chart["sheet"]);

						// If errors set errors
						if (is_wp_error($sheet)) 
							$this->errors->add("file_{$chart_id}_not_found",__($this->compose_error_message($sheet), $this->plugin));


						// Loop through all admin fields to set the chart options
						// $options = [];
						// foreach ($this->admin_fields() as $field) {
						// 	$options[$field["id"]]= (isset($chart[$field["id"]]))? $chart[$field["id"]]  : $field["default"];
						// }
						// $default_chart_options = $this->default_chart_options($options);

						// Prepare output
						$chart_list[$chart_id] = array(
							"chartId"		=> $chart_id,
							"sheet"			=> $sheet,
							"fileName" 	=> $file["fileName"],
							"chartType"  => $chart["chartType"],
							"chartOptions"			=> $chart,
							"error" => $this->compose_message($this->errors, $this->success)

						);
					
				
						// Add a meta box for each chart
						add_meta_box(
							"{$this->plugin}-dashboard-chart-{$chart_id}",
							"{$file["fileName"]} - {$sheet["sheetName"]}",
							function ($object, $params) {  // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
								$chart_id = $params["args"]["chartId"];
								//$chart = $params["args"]["chart"];
								?>
								<!-- <form id ="chart-delete-form" action = "">
									<input type ="hidden" id = "chartId" name="chartId" value = "<?php //echo $chart_id ?>" >
									<?php //submit_button("Delete Chart", 'secondary', "chart-delete-submit", false);?>
								</form> -->

								<a href ="<?php echo add_query_arg(array("page" => $this->plugin, "action" => "editChart", "chartId" => $chart_id), admin_url('admin.php')); ?>" class ="add-new-h2" id ="edithart">Edit Chart</a>

								<!-- <form id ="chart-edit-form" action = " " method="POST"> -->
									<!-- <input type ="hidden" id = "chartId" name="chartId" value = "<?php //echo $chart_id ?>" > -->
									<?php //submit_button("Edit Chart", 'secondary', "chart-edit-submit", false);?>
								<!-- </form> -->
								<?php

								echo $this->chart_metabox_render($chart_id); 
							},
							$this->dashboard_hook,
							'normal',
							'default',
							array("chartId" => $chart_id)
						);				
							
					}

					// Compose message
					//$message = $this->compose_message($this->errors, $this->success);

					// Compose response
					$response = array(
						"chartList" => $chart_list,
						"message" => "",
						"get" =>$_GET,
						//"chartOptions"	=> $default_chart_options
					);

					//send ajax data
					wp_localize_script( 
						"{$this->prefix}_handle",  //handle for the script
						"{$this->prefix}_chart_object",     //  The name of the variable which will contain the data (used in the ajax url)
						array(  // Data to be passed 
							"response"  => $response
						)
					);

				}

				// Render page template
				echo $this->get_template_html("dashboard");


			}


		} // END  public function dashboard_submenu_page_render()






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

		public function chart_metabox_render($divId, $action= null) {

			//var_dump($action);die;
			
			// Strat buffer
			ob_start();?>

			<div id = "dashboard" >

					<?php if ($action) submit_button("Refresh", 'primary', "refresh", false); ?>
					
					<div id = "<?php echo "dashboard-{$divId}-div"; ?>" ></div>
					<div id="filters-charts">
						<div id="filters">
							<div id = "<?php echo "filter-{$divId}-div"; ?>" ></div>
						</div>
						<div id = "<?php echo "chart-{$divId}-div"; ?>" ></div>
						<div id = "<?php echo "table-{$divId}-div"; ?>" ></div>
						<div id = "<?php echo "min-max-{$divId}-div"; ?>" ></div>
					</div>
				<?php //endif ?>
			</div>

			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;

		}




		public function fetch_trendline_fields ($index) {
			return array(
				// array(
				// 	array(
				// 		"id" => "trendlines.{$index}.type",  // id attribute if the field
				// 		"fieldTitle" => __("Type", $this->plugin),
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
				// 		"fieldOptions" => array(
				// 		"linear" => "Linear",
				// 		"polynomial" => "Polynomial",
				// 		"exponential" => "Exponential",
				// 	),
				// 	"nullOption" => "Trendline Type",
				// 	"default" => "",
				// 	"cssClass" => "chartOption"
				// 	),
				// 	array(
				// 		"id" => "trendlines.{$index}.degree",  // id attribute if the field
				// 		"fieldTitle" => __("Degree", $this->plugin),
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "number",
				// 		"default" => 3,
				// 		"cssClass" => "chartOption"
				// 	),
				// ),
				// array(
				// 	"id" => "trendlines.{$index}.color", 
				// 	"cssClass" => "color-picker",
				// 	"fieldTitle" => __("Color", $this->plugin), 
				// 	"submenuPage" => "{$this->prefix}_dashboard", 
				// 	"fieldType" => "color-picker",
				// 	"default" => "teal",
				// ),
				// array(
				// 	array(
				// 		"id" => "trendlines.{$index}.lineWidth",  // id attribute if the field
				// 		"fieldTitle" => __("Line Width", $this->plugin),
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "number",
				// 		"default" => 3,
				// 		"cssClass" => "chartOption"
				// 	),
				// 	array(
				// 		"id" => "trendlines.{$index}.opacity",  // id attribute if the field
				// 		"fieldTitle" => __("Opacity", $this->plugin),
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "number",
				// 		"fieldMin" => 0,
				// 		"fieldMax" => 1,
				// 		"fieldStep" => 0.1,
				// 		"default" => .5,
				// 		"cssClass" => "chartOption"
				// 	),
				// ),
				// array(
				// 	array(
				// 		"id" => "trendlines.{$index}.pointSize",  // id attribute if the field
				// 		"fieldTitle" => __("Point Size", $this->plugin),
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "number",
				// 		"default" => 3,
				// 		"cssClass" => "chartOption"
				// 	),
				// 		array(
				// 		"id" => "trendlines.{$index}.labelInLegend",  
				// 		"fieldTitle" => __("Label in Legend", $this->plugin), 
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "text", 
				// 		"default" => "",
				// 		"cssClass" => "chartOption"
				// 	),
				// ),
				// array(
				// 	array(
				// 		"id" => "trendlines.{$index}.pointsVisible",  
				// 		"fieldTitle" => __("Points Visible", $this->plugin), 
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "checkbox", 
				// 		"default" => true,
				// 		"cssClass" => "chartOption"
				// 	),
				// 	array(
				// 		"id" => "trendlines.{$index}.visibleInLegend",  
				// 		"fieldTitle" => __("Visible in Legend", $this->plugin), 
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "checkbox", 
				// 		"default" => true,
				// 		"cssClass" => "chartOption"
				// 	),
				// 	array(
				// 		"id" => "trendlines.{$index}.showR2",  
				// 		"fieldTitle" => __("Show R2", $this->plugin), 
				// 		"submenuPage" => "{$this->prefix}_dashboard", 
				// 		"fieldType" => "checkbox", 
				// 		"default" => false,
				// 		"cssClass" => "chartOption"
				// 	),
				// )
			);
		}





		/**
		 * Renders admin fields meta boxes
		 */
		function admin_fields_metaboxes_render($chart, $sheet) {

			//$file_select_options = [];

			?>
			<div id ="dashbboard-admin-fields" class = " accordion">
				<!-- Loop through admin sections -->
				<?php foreach($this->admin_settings($this->default_chart_type, null) as $setting) { ?>
				
					<button  class ="accordionButton"><?php echo $setting["title"] ?></button>
					<div class = "panel" id = "<?php echo $setting["id"] ?>" >
						<div class = "subPanel accordion" >
							<!-- Loop through admmin fields -->
							<?php foreach ($setting["subpanels"] as $subsection) {
							 	if (count($setting["subpanels"]) != 1) :?>
										
									<button class ="accordionButton" ><?php echo $subsection["title"] ?></button>
										<div class = "panel" id="<?php echo $subsection["id"] ?>">
								<?php  endif;

											foreach($subsection["fields"] as $field) {
												if  ( array_keys($field)[0]) {
													$this->render_field_type_template($field);
												} else {
													?><div class="multipleFields"><?php
														foreach($field as $sub_field) {
															$this->render_field_type_template($sub_field);
														}
													?></div><?php
												}
											}

										if (count($setting["subpanels"]) != 1) :?>
										</div><!-- .panel -->
										<?php endif ?>
						
							<?php } ?>
						</div> <!-- subpanel accordion -->
					</div><!-- .panel -->
					
				<?php } ?>
			</div> <!-- accordion -->
			<?php
	

		} // END function admin_fields_metaboxes_render()





		public function render_field_type_template($field) {

			$field["value"] = (isset($chart[$field["id"]]))? $chart[$field["id"]] : $field["default"];


			if (file_exists($this->path . "templates/" . $field['fieldType'] . ".php")) {?>
				<?php require ($this->path . 'templates/' . $field['fieldType'] . ".php");?>
			<?php } else {?>
				<?php _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['fieldType'] . ".php does not exist</div>", $this->plugin);
			}

				// Add file upload button if input field type if file
			if ($field["id"] == "fileUpload") submit_button("Upload File", 'primary', "fileUploadSubmit", false);

		}






		// public function flatten_array($arr) {

		// 	global $new_arr;//Initialize flat array
		// 	//$new_arr = array();
			
		// 	// loop recursively through array to retreive all arrays inside mai  array
		// 	foreach ($arr as $key => $value) {
				
		// 		// check if the first element is an array
		// 		if (is_array($value[array_keys($value)[0]])) {		
		// 			$this->flatten_array($value);
		// 		} else {
		// 			$new_arr[$key]= $value;
		// 		}
	

		// 	}
		
		// 	//var_dump($new_arr);
		// 	return $new_arr;
		// }








		
		/**
		 * Renders new chart meta box
		 */
		public function add_edit_chart_metabox_render($params) {

				//$file_id = $params['args']['chart_id'];
			$chart_id = $params['args']['chart_key'];
			$chart = $params['args']['chart'];

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
				<input type = "hidden" id ="chartId" name = "chartId" value = "">
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
			<div id="dashboard">
				<div id="">
			<div id = "<?php echo "dashboard-{$file_id}-div"; ?>"></div>
			<div id = "<?php echo "filter-{$file_id}-div"; ?>"></div>
			<div id = "<?php echo "chart-list-{$file_id}-div"; ?>"></div>
		</div>
		  </div>
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
				 if (file_exists($this->path . "templates/" . $field['fieldType'] . ".php")) {
					?>
					<td>
					<h2><?php echo $field['title'] ?></h2>
					</td>
					<td><?php require $this->path . 'templates/' . $field['fieldType'] . ".php";?></td>
					<?php
				} else {
					?>
					<td>
					<?php _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['fieldType'] . ".php does not exist</div>", $this->plugin);?>
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
	if (file_exists($this->path . "templates/" . $field['fieldType'] . ".php")) {
		?>
		<tr id="<?php echo $field_id ?>">
		<td>
		<h2><?php echo $field['title'] ?></h2>
		</td>
		<td><?php require $this->path . 'templates/' . $field['fieldType'] . ".php";?></td>
		</tr>
		<?php
	} else {
		?>
		<tr>
		<td>
		<?php _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['fieldType'] . ".php does not exist</div>", $this->plugin);?>
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
					$this->errors->add("{$class_name}_requires_woocommerce", __("{$class_name} requires <a href ='https://wordpress.org/plugins/woocommerce/'>woocommerce</a> to be installed and activated. PLease activate Woocommerce or disable this module.", $this->plugin));
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
				if (empty($this->errors->errors)) {
				 return;
			 }
			 ob_start();
			 foreach (array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages()) as $code => $message) {
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
				if ($field['fieldType'] == "text" || $field['fieldType'] == "email" || $field['fieldType'] == "password" || $field['fieldType'] == "hidden") {
					?><input type="<?php echo $field['fieldType'] ?>" id="<?php echo $field['field_id']; ?>"
					name="<?php echo $field['field_id'] ?>" <?php echo ($value) ? "value='" . $value . "'" : "" ?>>
					<?php echo $field['field_description'];
				} elseif ($field['fieldType'] == "textarea") {
					?><textarea name="<?php echo $field['field_id'] ?>" id="<?php echo $field['field_id'] ?>"
					rows=10><?php echo $value ?></textarea><?php
				} elseif ($field['fieldType'] == "checkbox") {
					?><input type="<?php echo $field['fieldType'] ?>" id="<?php echo $field['field_id'] ?>"
					name="<?php echo $field['field_id'] ?>" value="1" <?php checked(1, $value, true)?>>
					<?php echo $field['field_description'] ?><?php
				} elseif ($field['fieldType'] == "select") {
					?>
					<select id="<?php echo $field['field_id'] ?>" name="<?php echo $field['field_id'] ?>">
					<option value="0">Select Option</option>
					<?php
					$options = explode(", ", $field['fieldOptions']);
					foreach ($options as $key => $option) {
						?>
						<option value="<?php echo $option ?>" <?php selected($value, $option, true);?>><?php echo $option ?></option>
					<?php }?>
					</select>
					<?php
				} elseif ($field['fieldType'] == "radio") {
				 $options = explode(", ", $field['fieldOptions']);
				 foreach ($options as $key => $option) {
					?>
					<input type="<?php echo $field['fieldType'] ?>" id="<?php echo $field['field_id'] ?>"
					name="<?php echo $field['field_id'] ?>" value="<?php echo $option ?>" <?php checked($option, $value, true);?>>
					<?php echo $option;
				}
			} else {
			 _e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['fieldType'] . ".php does not exist</div>", $this->plugin);
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
				 'cssClass' => '',
				 'tab' => '',
				 'fieldType' => '',
				 'fieldSize' => '',
				 'fieldMin' => '',
				 'fieldMax' => '',
				 'required' => false,
				 'readonly' => false,
				 'unique' => false,
				 'disabled' => false,
				 'pattern' => '',
				 'placeholder' => '',
				 'modal' => false,
				 'description' => '',
				 'nullOption' => 'Select Option',
				 'default' => '',
				 'fieldOptions' => '',
				 'textareaCols' => '',
				 'textareaRows' => '',
				 'multiple' => false
			 );
				} // END  public function possible_field_options() {











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
					wp_register_script( "{$this->prefix}_handle", "{$this->url}assets/admin.js", array( "jquery" ), null, true );
					wp_enqueue_script( "{$this->prefix}_handle");
					wp_localize_script( 
						"{$this->prefix}_handle",  //handle for the script
						"{$this->prefix}_object",     //  The name of the variable which will contain the data (used in the ajax url)
						array(  // Data to be passed 
							"url"      => admin_url( "admin-ajax.php" ),
							"plugin"  => $this->plugin,
							"prefix"  => $this->prefix,
							"templateId" => "$this->template_id",
							"filePanelId"  => $this->file_panel_id,
							"chartPanelId"  => $this->chart_panel_id,
							"file_upload_action"   => "{$this->prefix}_file_upload_hook",
							"file_delete_action"   => "{$this->prefix}_file_delete_hook",
							"file_select_action"   => "{$this->prefix}_file_select_hook",
							"sheet_select_action"   => "{$this->prefix}_sheet_select_hook",
							"refresh_chart_action"   => "{$this->prefix}_refresh_chart_hook",
							"chart_list_action"   => "{$this->prefix}_chart_list_hook",
							"chart_save_action"   => "{$this->prefix}_chart_save_hook",
							"chart_update_action"   => "{$this->prefix}_chart_update_hook",
							"file_upload_nonce"  => wp_create_nonce("{$this->prefix}__{$this->file_upload_nonce}" ),
							"file_delete_nonce"  => wp_create_nonce("{$this->prefix}__file_delete_nonce" ),
							"file_select_nonce"  => wp_create_nonce("{$this->prefix}__{$this->file_select_nonce}" ),
							"sheet_select_nonce"  => wp_create_nonce("{$this->prefix}__{$this->sheet_select_nonce}" ),
							"refresh_chart_nonce"  => wp_create_nonce("{$this->prefix}__{$this->refresh_chart_nonce}" ),
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
					// 			'template_id' => "{$this->prefix}-dashboard",
					// 			"nonce"  => wp_create_nonce("{$this->prefix}__chart_list_nonce" ),
					// 		)
					// ); 
					

			}

		} // END public function enqueue_scripts()








		public function default_chart_options($fields, $chart_type) {


			$chart_options = array(

				"theme" => "material",

				"width" => $fields["width"],
				"height" => $fields["height"],
				"fontName" => $fields["fontName"],
				"fontSize" => $fields["fontSize"],
				"interpolateNulls" => $fields["interpolateNulls"],
				"orientation" => $fields["orientation"],
				"trendlines" => array(),


				"backgroundColor" => array(
					"fill" => $fields["backgroundColor.fill"],
					"stroke" => $fields["backgroundColor.stroke"],
					"strokeWidth"	=> $fields["backgroundColor.strokeWidth"],
				),

				"title" => $fields["title"],
				"titleTextStyle" => array(
					"color" => $fields["titleTextStyle.color"],
					"fontName" => $fields["titleTextStyle.fontName"],
					"fontSize" => $fields["titleTextStyle.fontSize"],
					"bold" => $fields["titleTextStyle.bold"],
					"italic" => $fields["titleTextStyle.italic"],
				),

				"chartArea" => array(
					"width" => $fields["chartArea.width"],
					"height" => $fields["chartArea.height"],
					"top" => $fields["chartArea.top"],
					"left" => $fields["chartArea.left"],
					"backgroundColor" => array(
						"fill" => $fields["chartArea.backgroundColor.fill"],
						"stroke" => $fields["chartArea.backgroundColor.stroke"],
						"strokeWidth"	=> $fields["chartArea.backgroundColor.strokeWidth"],
					),
					
				),

				"legend" => array(
					"alignment"=> $fields["legend.alignment"],
					"position"=> $fields["legend.position"],
					"maxLines"=> $fields["legend.maxLines"],
					"textStyle" => array(
						"color" => $fields["legend.textStyle.color"],
						"fontName" => $fields["legend.textStyle.fontName"],
						"fontSize" => $fields["legend.textStyle.fontSize"],
						"bold" => $fields["legend.textStyle.bold"],
						"italic" => $fields["legend.textStyle.italic"],
					),
				),

				"tooltip" => array(
					"text"=> $fields["tooltip.text"],
					"showColorCode"=> $fields["tooltip.showColorCode"],
					"trigger" => $fields["tooltip.trigger"],
					"textStyle" => array(
						"color" => $fields["tooltip.textStyle.color"],
						"fontName" => $fields["tooltip.textStyle.fontName"],
						"fontSize" => $fields["tooltip.textStyle.fontSize"],
						"bold" => $fields["tooltip.textStyle.bold"],
						"italic" => $fields["tooltip.textStyle.italic"],
					),
				),
				
			);


			switch ($this->default_chart_type) {

				case "LineChart":

					$other_options = array(
						"titlePosition" => $fields["titlePosition"],
					
						"hAxis" => array(
							"title" => $fields["hAxis.title"],
							"titlePosition" => $fields["hAxis.titlePosition"],
							"baseline"=> $fields["hAxis.baseline"],
							"baselineColor"=> $fields["hAxis.baselineColor"],
							"ticks" => "auto",
							"direction" => $fields["hAxis.direction"],
							"format" => $fields["hAxis.format"],
							"titleTextStyle" => array(
								"color" => $fields["hAxis.titleTextStyle.color"],
								"fontName" => $fields["hAxis.titleTextStyle.fontName"],
								"fontSize" => $fields["hAxis.titleTextStyle.fontSize"],
								"bold" => $fields["hAxis.titleTextStyle.bold"],
								"italic" => $fields["hAxis.titleTextStyle.italic"],
							),
							"textStyle" => array(
								"color" => $fields["hAxis.textStyle.color"],
								"fontName" => $fields["hAxis.textStyle.fontName"],
								"fontSize" => $fields["hAxis.textStyle.fontSize"],
								"bold" => $fields["hAxis.textStyle.bold"],
								"italic" => $fields["hAxis.textStyle.italic"],
							),
							"gridlines" => array(
								"color" => $fields["hAxis.gridlines.color"],
								"count" => $fields["hAxis.gridlines.count"],
							),
							"minorGridlines" => array(
								"color" => $fields["hAxis.minorGridlines.color"],
								"count" => $fields["hAxis.minorGridlines.count"],
							),
							"logScale" => $fields["hAxis.logScale"],
							"scaleType" => $fields["hAxis.scaleType"],
						),


						"vAxis" => array(
							"title" => $fields["vAxis.title"],
							"titlePosition" => $fields["vAxis.titlePosition"],
							"baseline"=> $fields["vAxis.baseline"],
							"baselineColor"=> $fields["vAxis.baselineColor"],
							"ticks" => "auto",
							"direction" => $fields["vAxis.direction"],
							"format" => $fields["vAxis.format"],
							"titleTextStyle" => array(
								"color" => $fields["vAxis.titleTextStyle.color"],
								"fontName" => $fields["vAxis.titleTextStyle.fontName"],
								"fontSize" => $fields["vAxis.titleTextStyle.fontSize"],
								"bold" => $fields["vAxis.titleTextStyle.bold"],
								"italic" => $fields["vAxis.titleTextStyle.italic"],
							),
							"textStyle" => array(
								"color" => $fields["vAxis.textStyle.color"],
								"fontName" => $fields["vAxis.textStyle.fontName"],
								"fontSize" => $fields["vAxis.textStyle.fontSize"],
								"bold" => $fields["vAxis.textStyle.bold"],
								"italic" => $fields["vAxis.textStyle.italic"],
							),
							"gridlines" => array(
								"color" => $fields["vAxis.gridlines.color"],
								"count" => $fields["vAxis.gridlines.count"],
							),
							"minorGridlines" => array(
								"color" => $fields["vAxis.minorGridlines.color"],
								"count" => $fields["vAxis.minorGridlines.count"],
							),
							"logScale" => $fields["vAxis.logScale"],
							"scaleType" => $fields["vAxis.scaleType"],
						),
					);
								
					break;
									
				case 'PieChart':
					$other_options = array(
						"is3D" => $fields["is3D"],
						"pieHole" => $fields["pieHole"],
						"pieSliceBorderColor" => $fields["pieSliceBorderColor"],
						"pieSliceText" => $fields["pieSliceText"],
						"pieStartAngle" => $fields["pieStartAngle"],
						"reverseCategories" => $fields["reverseCategories"],
						"sliceVisibilityThreshold" => $fields["sliceVisibilityThreshold"],
						"pieResidueSliceLabel" => $fields["pieResidueSliceLabel"],
						"pieResidueSliceColor" => $fields["pieResidueSliceColor"],
						"pieSliceTextStyle" => array(
							"color" => $fields["pieSliceTextStyle.color"],
							"fontName" => $fields["pieSliceTextStyle.fontName"],
							"fontSize" => $fields["pieSliceTextStyle.fontSize"],
							"bold" => $fields["pieSliceTextStyle.bold"],
							"italic" => $fields["pieSliceTextStyle.italic"],
						)
					);
					break;
				
				default:
					$other_options = array();
					break;
			}

			return array_merge($chart_options, $other_options);
			
		
		}






		// /**
	 //    * Admin sections 
	 //    */
	 //    public function  admin_sections (){

	 //      return array(

	 //      	// File settings section (accordion panel)  
	 //         array(
	 //         	"id" 			=> $this->file_panel_id,
	 //          	'title'  		=> __('File Settings', $this->plugin)
	 //        ), 
	         
	 //         // Chart settings section (accordion panel);
	 //         array(
	 //          	"id" 			=> $this->chart_panel_id,
	 //          	'title'  		=> __('Chat Settings', $this->plugin)
	 //        ), 
	 //      );

	 //    }
	 

	 public function legend_position($chart_type) {

	 	//var_dump($chart_type);


	 	switch ($chart_type) {
	 		case "LineChart":
	 			return array(
					"right" => "Right of the cahrt",
					"left" => "Left of the Chart",
					"top" => "Above the Chart",
					"bottom" => "Below The Chart",
					"in" => "Inside the Chart",
					"none" => "Omit Legend",	
				);
	 			break;

	 		case "PieChart":
	 			return array(
	 				"right" => "Right of the cahrt",
					"left" => "Left of the Chart",
					"top" => "Above the Chart",
					"bottom" => "Below The Chart",
					"none" => "Omit Legend",
					"labeled" => "Connected to Chart",
					"none" => "Omit Legend",	
				);
				break;
	 		
	 		default:
	 			return array();
	 			break;
	 	}



	 }








		/*
		* Admin Fields
		*/
		public function admin_settings($chart_type = null, $series_index = 0) {
		
			// Fetch dashboard settings 
			$settings = get_option("{$this->prefix}_dashboard");

			// extract files
			$files = ($settings && isset($settings["files"]))? $settings["files"] : array();

			// Intialize file selection options array
			$file_select_options = [];

			// Loop through files to compse all the file select options
			if ($files) {
				foreach($files as $file_id => $file) {
					$file_select_options[$file_id] = $file["fileName"]; 
				}
			}


			return array(

				"fileSettings" => array(
					"id" => "fileSettings",
					"title" => "Upload/Select File",
					"subpanels" => array(
						"fileStuff" => array(
							"id" => "fileStuff",
							"title" => __("Upload Stuff", $this->plugin),
							"fields" => array(
								array(
									//"slug" => "file_upload", // field id
									"id" => "chartId",  // id attribute if the field
									//"fieldTitle" => __("U", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									// "id" => "fileSettings",
									"fieldType" => "hidden", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//"cssClass" => "clickable file-upload btn btn-success",
									//"multiple" =>true,
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["file_upload"])) ? $settings["file_upload"] : "",
									//"default" => null,
									"default" => null,
								),
								array(
									//"slug" => "file_upload", // field id
									"id" => "fileUpload",  // id attribute if the field
									"fieldTitle" => __("Upload File", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									// "id" => "fileSettings",
									"fieldType" => "file", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//"cssClass" => "clickable file-upload btn btn-success",
									//"multiple" =>true,
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["file_upload"])) ? $settings["file_upload"] : "",
									//"default" => null,
									"default" => null,
								),
								array(//"slug" => 'file_select', // field id
									"id" => "fileSelect",  // id attribute if the field
									"fieldTitle" => __("Select File", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => $file_select_options,
									"nullOption" => "Select File",
									"cssClass" => "hideAtFirst",
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["fileId"])) ? $settings["fileId"] : "",
									"default" => null,
								),
								array(
									//"slug" => "sheetId", // field id
									"id" => "sheet",  // id attribute if the field
									"fieldTitle" => __("Sheet", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(),
									"nullOption" => "Select Sheet",
									"cssClass" => "hideAtFirst",
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["sheetId"])) ? $settings["sheetId"] : "",
									"default" => null,
								),
								array(	//"slug" => "chart_type_select", // field id
									"id" => "chartType",  // id attribute if the field
									"fieldTitle" => __("Chart Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => $this->chart_types,
									"nullOption" => "Select Chart Type",
									"cssClass" => "hideAtFirst",
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["chart_type_select"])) ? $settings["chart_type_select"] : "",
									"default" => null,
								),
							)
						)
					)
				),

				"chartSettings" => array(
					"id" => "chartSettings",
					"title" => "Chart Settings",
					"subpanels" => array(
						"main" => array(
							"id" => "main",
							"title" => "Main",
							"fields" => array(
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "width",  // id attribute if the field
										"fieldTitle" => __("Chart Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "800",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "height",  // id attribute if the field
										"fieldTitle" => __("Chart Height", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "800",
										"cssClass" => "chartOption"
									)
								),
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "fontName",  // id attribute if the field
										"fieldTitle" => __("Font Name", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Select Font",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "verdana",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "fontSize",  // id attribute if the field
										"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "8",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "interpolateNulls",  // id attribute if the field
										"fieldTitle" => __("Interpolate", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(//"slug" => "chart_title", // field id
									"id" => "orientation",  // id attribute if the field
									"fieldTitle" => __("Chart Orientation", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"horizontal" => "Horizontal",
										"vertical" => "Vertical",
									),
									"nullOption" => "Chart Orientation",
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "horizontal",
									"cssClass" => "chartOption"
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "backgroundColor.fill",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									// "fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "#CCCCCC",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "backgroundColor.stroke",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Background Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "backgroundColor.strokeWidth",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Background Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "6",
									"cssClass" => "chartOption"
								),
							)
						),
						 "hAxis" => array(
							"id" => "hAxis",
							"title" => "Horizontal Axis",
							"fields" => array(
								array(
									//"slug" => chart_title, // field id
									"id" => "hAxis.title",  // id attribute if the field
									"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"Line"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "World",
									"cssClass" => "chartOption"
								),
								
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.titleTextStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Title Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Title Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Title Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 40,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Title Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Title Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titlePosition",  // id attribute if the field
										"fieldTitle" => __("Title Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Position",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.baseline",  // id attribute if the field
										"fieldTitle" => __("AxisBaseline", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => null,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.baselineColor",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Axis Baseline Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textPosition",  // id attribute if the field
										"fieldTitle" => __("Text Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Position",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.ticks",  // id attribute if the field
										"fieldTitle" => __("Ticks", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "auto",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Text Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Text Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
									array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.textStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Text Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "teal",
									//"cssClass" => "chartOption"
								),							
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Text Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Text Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.direction",  // id attribute if the field
										"fieldTitle" => __("Direction", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"1" => "Left to Right",
											"-1" => "Right to Left",
										),
										"nullOption" => "Axis Direction",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "hAxis.format",  // id attribute if the field
										"fieldTitle" => __("Format", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "Auto",
											"decimal" => "thousands",
											"scientific" => "scientific",
											"currency" => "currency",
											"percent" => "percentages",
											"short" => "abbreviated",
											"long" => "words",
										),
										"nullOption" => "Format",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.gridlines.count",  // id attribute if the field
										"fieldTitle" => __("Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => -1,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.minorGridlines.count",  // id attribute if the field
										"fieldTitle" => __("Minor Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => -1,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.gridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.minorGridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Minor Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.logScale",  // id attribute if the field
										"fieldTitle" => __("Log Scale", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "hAxis.scaleType",  // id attribute if the field
										"fieldTitle" => __("Scale Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "Normal",
											true => "Log Scale",
										),
										"nullOption" => "Scale Type",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),

								)
							)
						),
						"vAxis" => array(
							"id" => "vAxis",
							"title" => "Vertical Axis",
							"fields" => array(
								array(
									//"slug" => chart_title, // field id
									"id" => "vAxis.title",  // id attribute if the field
									"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			 
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"Line"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "World",
									"cssClass" => "chartOption"
								),
								
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.titleTextStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Title Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Title Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Title Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 40,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Title Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Title Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titlePosition",  // id attribute if the field
										"fieldTitle" => __("Title Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Position",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.baseline",  // id attribute if the field
										"fieldTitle" => __("AxisBaseline", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => null,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.baselineColor",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Axis Baseline Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textPosition",  // id attribute if the field
										"fieldTitle" => __("Text Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Position",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.ticks",  // id attribute if the field
										"fieldTitle" => __("Ticks", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "auto",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Text Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Text Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
									array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.textStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Text Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "teal",
									//"cssClass" => "chartOption"
								),							
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Text Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Text Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.direction",  // id attribute if the field
										"fieldTitle" => __("Direction", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"1" => "Bottom to Top",
											"-1" => "Top to Bottom",
										),
										"nullOption" => "Axis Direction",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "vAxis.format",  // id attribute if the field
										"fieldTitle" => __("Format", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "Auto",
											"decimal" => "thousands",
											"scientific" => "scientific",
											"currency" => "currency",
											"percent" => "percentages",
											"short" => "abbreviated",
											"long" => "words",
										),
										"nullOption" => "Format",
										"uniqueTo" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.gridlines.count",  // id attribute if the field
										"fieldTitle" => __("Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => -1,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.minorGridlines.count",  // id attribute if the field
										"fieldTitle" => __("Minor Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => -1,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.gridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.minorGridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Minor Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.logScale",  // id attribute if the field
										"fieldTitle" => __("Log Scale", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "vAxis.scaleType",  // id attribute if the field
										"fieldTitle" => __("Scale Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "No Log Scaling",
											"log" => "Log Scaling",
											"mirrorLog" => "Negative/Zero Values Plotted",
										),
										"nullOption" => "Scale Type",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								)
							)
						),
			
						"chartTitle" => array(
							"id" => "chartTitle",
							"title" => "Chart Title",
							"fields" => array(
								array(//"slug" => "chart_title", // field id
									"id" => "title",  // id attribute if the field
									"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "HELLO",
									"cssClass" => "chartOption"
								),
								// array(
								// 	//"slug" => chart_title, // field id
								// 	"id" => "subtitle",  // id attribute if the field
								// 	"fieldTitle" => __("Subtitle", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
								// 	"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
								// 	"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
								// 	"uniqueTo" => array(
								// 		"Line"
								// 	),
								// 	//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
								// 	//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
								// 	"default" => "World",
								// 	"cssClass" => "chartOption"
								// ),
								array(
									//"slug" => chart_title, // field id
									"id" => "titlePosition",  // id attribute if the field
									"fieldTitle" => __("Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"in" => "Inside the Chart",
										"out" => "Outside the Chart",
										"none" => "Omit title"
									),
									"nullOption" => "Title Position",
									"uniqueTo" => array(
										"Line"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "",
									"cssClass" => "chartOption"
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "titleTextStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 40,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								
								)
							)
						),

						"legend" => array(
							"id" => "legend",
							"title" => "Chart Legend",
							"fields" => array(
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.alignment",  // id attribute if the field
										"fieldTitle" => __("Alignment", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"start" => "Start",
											"center" => "Center",
											"end"  => "End"
										),
										"nullOption" => "Select Alignment",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "center",
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "legend.position",  // id attribute if the field
										"fieldTitle" => __("Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => $this->legend_position($chart_type),
										"nullOption" => "Chart Position",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "right",
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "legend.maxLines",  // id attribute if the field
									"cssClass" => "maxLines",
									"fieldTitle" => __("Maximum Number of Lines", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args) 
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 3,
									"cssClass" => "chartOption"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "legend.textStyle.color",  // id attribute if the field
									"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"cssClass" => "color-picker",
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "green",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Font Name", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "georgia",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => 15,
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									)
								)
							
							)
						),
			
						"chartArea" => array(
							"id" => "chartArea",
							"title" => "Chart Area",
							"fields" => array(
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.width",  // id attribute if the field
										"fieldTitle" => __("Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "400",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.height",  // id attribute if the field
										"fieldTitle" => __("Height", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "400",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.top",  // id attribute if the field
										"fieldTitle" => __("Top Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "20%",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.left",  // id attribute if the field
										"fieldTitle" => __("Left Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "20%",
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "chartArea.backgroundColor.fill",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "chartArea.backgroundColor.stroke",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "blue",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "chartArea.backgroundColor.strokeWidth",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "2",
									"cssClass" => "chartOption"
								)
							)
						)
					)
				),
				"trendlines" => array(
					"id" => "trendlines",
					"title" => "Trendlines",
					"subpanels" => array(
						"series0" => array(
							"id" => "series0",
							"title" => "Series 0",
							"fields" =>array(
								array(
									array(
										"id" => "trendlines.{$series_index}.type",  // id attribute if the field
										"fieldTitle" => __("Type", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
										"linear" => "Linear",
										"polynomial" => "Polynomial",
										"exponential" => "Exponential",
									),
									"nullOption" => "Trendline Type",
									"default" => "",
									"cssClass" => "chartOption"
									),
									array(
										"id" => "trendlines.{$series_index}.degree",  // id attribute if the field
										"fieldTitle" => __("Degree", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 3,
										"cssClass" => "chartOption"
									),
								),
								array(
									"id" => "trendlines.{$series_index}.color", 
									"cssClass" => "color-picker",
									"fieldTitle" => __("Color", $this->plugin), 
									"submenuPage" => "{$this->prefix}_dashboard", 
									"fieldType" => "color-picker",
									"default" => "teal",
								),
								array(
									array(
										"id" => "trendlines.{$series_index}.lineWidth",  // id attribute if the field
										"fieldTitle" => __("Line Width", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 3,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "trendlines.{$series_index}.opacity",  // id attribute if the field
										"fieldTitle" => __("Opacity", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"fieldMin" => 0,
										"fieldMax" => 1,
										"fieldStep" => 0.1,
										"default" => .5,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "trendlines.{$series_index}.pointSize",  // id attribute if the field
										"fieldTitle" => __("Point Size", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 3,
										"cssClass" => "chartOption"
									),
										array(
										"id" => "trendlines.{$series_index}.labelInLegend",  
										"fieldTitle" => __("Label in Legend", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text", 
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "trendlines.{$series_index}.pointsVisible",  
										"fieldTitle" => __("Points Visible", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "checkbox", 
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "trendlines.{$series_index}.visibleInLegend",  
										"fieldTitle" => __("Visible in Legend", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "checkbox", 
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "trendlines.{$series_index}.showR2",  
										"fieldTitle" => __("Show R2", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "checkbox", 
										"default" => false,
										"cssClass" => "chartOption"
									),
								)

							)
						)
						
					)
				),

				"otherSettings" => array(
					"id" => "otherSettings",
					"title" => "Other Settings",
					"subpanels" => array(
						array(
							"id" => "toolTips",
							"title" => "Tool Tips",
							"fields" => array(
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "tooltip.text",  // id attribute if the field
										"fieldTitle" => __("Text", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"both" => "Value and Percentage",
											"value" => "Value",
											"percentage" => "Percentage",
										),
										"nullOption" => "Select Font",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "value",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.trigger",  // id attribute if the field
										"fieldTitle" => __("Trigger", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"focus" => "Hover",
											"none" => "None",
											"selection" => "Select",
										),
										"nullOption" => "Select Font",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "selection",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Select Font",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 30,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "tooltip.textStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "teal",
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.showColorCode",  // id attribute if the field
										"fieldTitle" => __("Show Color Code", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								)
							)
						),

						array(
							"id" => "other",
							"title" => "Other",
							"fields" => array(		
							
								
								array(//"slug" => "chart_title", // field id
									"id" => "colors",  // id attribute if the field
									"fieldTitle" => __("Chart Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
									"fieldType" => "radio", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
											"blue" => "Blue",
											"red" => "Red",
											"cyan" => "Cyan",
											"teal" => " Teal",
											"magenta" => "Magent"
									),
									"nullOption" => "Select Colors",
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => null
								),
							
							)
						)
					)
				),	

				"piechartSettings" => array(
					"id" => "piechartSettings",
					"title" => "Pie Chart Settings",
					"subpanels" => array(
						array(
							"id" => "allsettings",
							"title" => "All Settings",
							"fields" => array(									
								array(
									//"slug" => chart_title, // field id
									"id" => "is3D",  // id attribute if the field
									"fieldTitle" => __("3D Pie Chart", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => false
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieHole",  // id attribute if the field
									"fieldTitle" => __("Pie Hole", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldMin" => 0,
									"fieldMax" => 1,
									"fieldStep" => 0.1,
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 0.5,
									"disableIf" => array("is3D" => true)
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "sliceVisibilityThreshold",  // id attribute if the field
									"fieldTitle" => __("slice Visibility Threshold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									"fieldMin" => 0,
									"fieldMax" => 1,
									"fieldStep" => 0.1,
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 0.1,	
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "pieSliceBorderColor",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Pie Slice Border Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "magenta",
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieStartAngle",  // id attribute if the field
									"fieldTitle" => __("Pie Slice Angle", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									"fieldMin" => 0,
									"fieldMax" => 360,
									"fieldStep" => 1,
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 0,
									//"disableIf" => array("is3D" => true)
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "reverseCategories",  // id attribute if the field
									"fieldTitle" => __("Reverse Categories", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => false
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieResidueSliceLabel",  // id attribute if the field
									"fieldTitle" => __("Pie Residual Slice Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "ZZZ"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieResidueSliceColor",  // id attribute if the field
									"fieldTitle" => __("Pie Residual Slice ColorColor", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"cssClass" => "color-picker",
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "yellow"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceText",  // id attribute if the field
									"fieldTitle" => __("Pie Slice Text", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"label" => "The tooltip will be displayed when the user hovers over the element",
										"none" => " No text is displayed",
										"value" => "The quantitative value of the slice",
										"percentage" => "The percentage of the slice size out of the total",
									),
									"nullOption" => "Select Pie Slice Text",
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "label"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.color",  // id attribute if the field
									"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"cssClass" => "color-picker",
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "white"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.fontName",  // id attribute if the field
									"fieldTitle" => __("Font Name", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"arial" => "Arial",
										"courier" => "Courier",
										"verdana" => "Verdana",
										"georgia" => " Georgia",
										"tahoma" => "Tahoma"
									),
									"nullOption" => "Select Position",
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "georgia"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.fontSize",  // id attribute if the field
									"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 10
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.bold",  // id attribute if the field
									"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => false
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.italic",  // id attribute if the field
									"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"uniqueTo" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => true
								)
							)
						)
					)
				),

				"advanced" => array(
					"id" => "advanced",
					"title" => "Advanced",
					"subpanels" => array(
						array(
							"id" => "slideControl",
							"title" => __("Slide Control", $this->plugin),
							"fields" => array(
								array(	//"slug" => "chart_type_select", // field id
									"id" => "rangeSliderColSelect",  // id attribute if the field
									"fieldTitle" => __("Range Slider Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(),
									"nullOption" => "Select Column",
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["chart_type_select"])) ? $settings["chart_type_select"] : "",
									"default" => null,
								)
							)
						)
					)
				),
	 		);

		} // END  private  function register_fields (){





		/**
		* File upload ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function sheet_select_callback() {

		 // echo json_encode($_POST);wp_die();

			try {
				// Bail if request is not POST, not an ajax request or does not come the file upload form
				if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_sheet_select_hook")
					throw new \Exception ( __("This is not a valid ajax get chart request", $this->plugin));
				
					// verify file upload nonce
				if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->sheet_select_nonce}"  )) 
					throw new \Exception (__("An invalid nonce was submited", $this->plugin));

				// verify if a file was selected (file id submitted)
				if (! ($_POST["fileSelect"])) 
					throw new \Exception (__("No file selected.  Please slect a file", $this->plugin));

				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

				// Retreive files settings options
				$file = (isset($settings["files"]))? $settings["files"][$_POST["fileSelect"]] : null;

				// If there is no file
	  			if ( ! $file) 
	  				throw new \Exception (__("No file with ID = {$_POST["fileSelect"]} was found", $this->plugin));

				// Fetch spreadsheet data 
				$sheet = $this->fetch_sheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]), $_POST["sheet"]);

				//echo json_encode($sheet);die;

				// If errors set errors
				if (is_wp_error($sheet)) 
					throw new \Exception ( __($this->compose_error_message($sheet), $this->plugin));

					// Compose sheet selection form
				$range_slider_options = $this->range_slider_select_options($sheet);

				// set sheet name
				//$sheet_name = $sheet["sheetName"];

				// Loop through all admin fields to set the chart options
				// $options = [];
				// foreach ($this->admin_fields() as $field) {
				// 	$options[$field["id"]]= (isset($_POST[$field["id"]]))? $_POST[$field["id"]]  : field["default"];
				// }
				// $default_chart_options = $this->default_chart_options($options);
				
				// Add success message
				$message = "<div class='notice notice-success is-dismissible'><p>Sheet Select successful</p></div>";

			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Set google dashboard, chart, and filter div id
			$chart_id = "editChart";
			$chart_id = " New Chart";

			// Compose response
			$response = array(
				"fileName" => (isset($file["fileName"]))? $file["fileName"] : "",
				"fileId" => (isset($_POST["fileSelect"]))? $_POST["fileSelect"] : null,
				"sheet" => $sheet,
				"rangeSliderColSelect" => $range_slider_options,
				//"chartOptions" => $default_chart_options,
				"message" => $message,
				"chartId" => "editChart",
				"post" =>$_POST,
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
				$this->errors->add("invalid_chart_list_ajax_request", __("This is not a valid ajax chart list request", $this->plugin));
			
				// verify file upload nonce
			if (! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__{$this->chart_list_nonce}"  )) 
				$this->errors->add("invalid_{$this->chart_list_nonce}", __("An invalid nonce was submited", $this->plugin));
			
			// Retreive all Charts 
			$charts = get_option("{$this->prefix}_dashboard");

			// check if any chart data exists
			if (! $charts) {
				$this->errors->add("no_charts_found", __("No charts found", $this->plugin));
			} else {

				$spreadsheets = [];
				$chart_params = [];
				$response = [];


				// check if an array if returned
				if (! is_array($charts)) {
					$this->errors->add("invalid_data_returned", __("get_option returned invalid data, an array was expected but got ". gettype($charts) . ".", $this->plugin));
				} else {

					// Loop through all the data
					foreach($charts as $chart_id => $chart) {

						// Does data contain a file id
						if(! isset($chart["fileId"]) ||  $chart["fileId"] == "") {
							$this->errors->add("no_file_id_found", __("No file-id found in the data returned by get_option({$this->prefix}_dashboar", $this->plugin));
						} else {

							// Does data contain a sheet id
							if(! isset($chart["sheetId"]) ||  $chart["sheetId"] == "") {
								$this->errors->add("no_sheet_id_found", __("No sheet-id found in the data returned by get_option({$this->prefix}_dashboar", $this->plugin));
							} else {

								// Retreive file
								$file = $db->find_by_id($chart["fileId"]);


								// check if a file is found
								if (! $file) {
									$this->errors->add("no_file_found", __("id ({$chart["fileId"]}) submitted but no file found in the database", $this->plugin));
								} else {

									// Fetch spreadsheet data 
									//$spreasheet =  $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file->name))
									$response[$chart_id]["spreadsheet"] = $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file->name))["sheet-data"][$chart["sheetId"]]["formatted"];
									$response[$chart_id]["chart-params"] = $chart;

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

				$chart_id =  "{$input["fileId"]}-{$input["sheetId"]}";

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
					 $this->errors->add("{$key}_empty", __("{$field['title']} is required", $this->plugin));
				 }
			 }
		 }
						 // Check if an existing multi array record is being addaed
		 if ((!isset($_POST["{$this->plugin}-update-record"]) || !$_POST["{$this->plugin}-update-record"]) && isset($input[$record_id]) && in_array($input[$record_id], array_keys($output))) {
			$this->errors->add("{$input[$record_id]}_exists", __("A record with this ID = {$input[$record_id]} exists.  Please edit this tab to modify it", $this->plugin));
		}
	}
	if (!empty($this->errors->errors)) {
	 foreach (array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages()) as $code => $message) {
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










	} // END class Dashboard {


} // END if (!class_exists(Dashboard))