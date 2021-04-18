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
		public $chart_types = array("LineChart" => "Line Chart", "Bar" => "Bar", "column" => "Column", "PieChart" => "Pie"); // Possible cahrt types
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
			//$this->errors = new \WP_Error;
			//$this->success = new \WP_Error;


			// Retreive settings options
			$this->chart_options = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

			// Retreive files settings options
			$this->files = (isset($this->chart_options["files"]))? $this->chart_options["files"] : array();

			// Retreive charts setiings
			$this->charts = (isset($this->chart_options["charts"]))? $this->chart_options["charts"] : array();

			
			
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
			//  echo json_encode($_FILES));wp_die();
			//  echo json_encode($_POST);wp_die();

			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			try{

				$result = $this->validate_request("file_upload", $this->file_upload_nonce);

				if ($result !== true)
					throw new \Exception ( __($result, $this->plugin));

				// Check if no files were selected
				if ( empty($_FILES)) 
					throw new \Exception ( __("File missing, please select a file to upload", $this->plugin));

				// Extract file from $_FILES
				$file = $file_instance->extract_file($_FILES["files"]);

				//Check if there is a file and is an array
				if (! $file || ! is_array($file)) 
					throw new \Exception ( __("File Class method extract_file() failed to return a file or an array", $this->plugin));
				
				// If validate_file() returns WP/Error (validate_file() returns either true or WP/Error
				$result = $file_instance->validate_file($file);
				if ($result !== true) 
					throw new \Exception ( __($result, $this->plugin));				

				// Check if wp_handle_upload function exists
				if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

				// Attemtto upload file
				$movefile = wp_handle_upload( $file, array('test_form' => false));


				// If file upload fails
				if ( ! $movefile && isset( $movefile['error'] ) ) 
					throw new \Exception ( __($movefile['error'], $this->plugin));
					
				// Fetch spreadsheet data to check if data types for each sheet are valid
				$spreadsheet = $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));

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


				//If all data types are valid
				if (isset($invalid_data_type)) {
					$error = "File: <strong>". sanitize_file_name($file["name"]) . "</strong> Sheet <strong>{$sheet_name}</strong> column <strong>{$sheet_column}</strong> data type <strong>{$col_data_type}</strong> is not a valid data type.  Allowed file extensions are  <strong>".implode(", ", $this->data_types)."</strong>This file was not uploaded.";
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __($error, $this->plugin));
				}

				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

				// Retreive files
				$files = (isset($settings["files"]))? $settings["files"] : array();

				// Make sure there are less than 10000 files in the option settings (I am allowing a maxcimum of 10000 files to be uploaded)
				if (count($files) > $file_instance->max_files)
					throw new \Exception ( __("Too many files.  You have exceeded the maximum  number of files you are allowed to upload ({$file_instance->max_files})", $this->plugin));

				// Pick a random 5 digit number  ($i id used to interupt the process in case the random number search exceeds 1000000)
				$i = 1;
				$file_id = rand ( 10000 , 99999 );
				while(in_array($file_id, array_keys($files) && $i < 1000000)) {
					$file_id = rand ( 10000 , 99999 );
					$i++;
				}

				if ($i >= 1000000) 
					throw new \Exception ( __("TUnable to come up with a 5 digit file id after 1000000 tries.", $this->plugin));
				
				// Prepare file for insertion into database table
				$file_parts = array(
					"fileId" => $file_id,
					"fileName" => $file["name"],
					"fileType" => $file["type"],
					"fileSize" => $file["size"],
				);
				
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
				"sheetSelectOptions" => (isset($sheet_select_options))? $sheet_select_options : null,
				"fileSelectOption" => (isset($file_select_option))? $file_select_option : null,
				"message" => $message,
				"post" =>$_POST,
				"spreadsheet" => $spreadsheet,
				"files" => $files
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

			// echo json_encode($_POST);wp_die();

			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			try {


				$result = $this->validate_request("file_select", $this->file_select_nonce);

				if ($result !== true)
					throw new \Exception ( __($result, $this->plugin));

				// Retreive files settings options
				$file = $this->files[$_POST["fileSelect"]];

				// If file exists
	  			if ( !$file) 
					throw new \Exception ( __("No file with ID = {$_POST["fileSelect"]} was found", $this->plugin));
				
				// Fetch spreadsheet data 
				$spreadsheet = $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]));

				// Fetch file selection foem and sheet selection form
				$sheet_select_options = $this->compose_sheet_select_options($spreadsheet, $_POST["fileSelect"]);

				// Add success message
				$message = __("File selection successful", $this->plugin);
				$message = "<div class='notice notice-success is-dismissible'><p>{$message}</p></div>";
	  		
			}  catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Compose response
			$response = array(
				"fileName" => (isset($file["fileName"]))? $file["fileName"] : "",
				"fileId" => (isset($file["fileId"]))? $file["fileId"] : null,
				"sheetSelectOptions" => (isset($sheet_select_options))? $sheet_select_options : null,
				"message" => $message,
				"post" =>$_POST,
				"files" => $this->files
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

			 //echo json_encode($_POST);wp_die();
			 
			 // Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVOptions;

			try {

				$result = $this->validate_request("refresh_chart", $this->refresh_chart_nonce);

				if ($result !== true)
					throw new \Exception ( __($result, $this->plugin));

				// verify if a file was selected (file id submitted)
				if (! ($_POST["fileSelect"])) 
					throw new \Exception (__("No file selected.  Please slect a file", $this->plugin));

				// Retreive files settings options
				$file = $this->files[$_POST["fileSelect"]];

				// If there is no file
	  			if ( ! $file) 
	  				throw new \Exception (__("No file with ID = {$_POST["fileSelect"]} was found", $this->plugin));

				// Fetch spreadsheet data 
				$sheet = $this->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]), $_POST["sheet"]);

				//echo json_encode($sheet);die;

				// If errors set errors
				if (is_wp_error($sheet)) 
					throw new \Exception ( __($this->compose_error_message($sheet), $this->plugin));

				// Get uniwue options (panel buttons for fields taht are unique to certain chart types)
				$unique_panels = array();
				foreach($options->option_fields($_POST["chartType"], null) as $option_id => $option) {
					if (isset($option["chartTypes"])) $unique_panels[$option_id] =  $option["chartTypes"];
				}

				// Get uniwue subpanels (subpanel buttons for fields taht are unique to certain chart types)
				$unique_subpanels = array();
				foreach ($options->option_fields($_POST["chartType"], null) as  $option_id => $option) {
					foreach($option["subpanels"] as $subpanel_id => $subpanel) {
						if (isset($subpanel["chartTypes"])) $unique_subpanels[$subpanel_id] =  $subpanel["chartTypes"];
					}
				}

				// Set option fields values to defaults (this is used to set default chart options)
				$option_fields = array();
				foreach( $this->fetch_fields($_POST["chartType"]) as $field) {
					$option_fields[$field["id"]] = $field["default"];
				}

				// get unique fields (fields that are unique to certain chart types
				$unique_fields = array();
				foreach( $this->fetch_fields($_POST["chartType"]) as $field) {
					if (isset($field["chartTypes"])) $unique_fields[$field["id"]] =  $field["chartTypes"];
				}

			
				

				// Add success message
				$message = "<div class='notice notice-success is-dismissible'><p>Refresh successful</p></div>";

			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Compose response
			$response = array(
				"fileName" => (isset($file["fileName"]))? $file["fileName"] : "",
				"fileId" => (isset($_POST["fileSelect"]))? $_POST["fileSelect"] : null,
				"sheet" => $sheet,
				"chartOptions" => $options->default_chart_options($option_fields, $_POST["chartType"]),
				"message" => $message,
				"divId" => "editChart", 
				"uniquePanels" => $unique_panels,
				"uniqueSubpanels" => $unique_subpanels,
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








		public function validate_request($action, $nonce) {
			// If request is not POST, not an ajax request or does not come the file upload form
			if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_{$action}_hook") 
				return __("This is not a valid {$action}_ajax request", $this->plugin);

			// verify file upload nonce
			if (! wp_verify_nonce( $_POST["nonce"], "{$this->prefix}__{$nonce}" )) 
				return __("An invalid {$action}_nonce was submited", $this->plugin);

			return true;

		}










		public function fetch_fields($chart_type) {

			 // Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVOptions;
			
			$option_fields = array();

			//Loop through admmin fields to populate option fields
			foreach ($options->option_fields($chart_type, null) as  $option_id => $option) {
				foreach($option["subpanels"] as $subpanel_id => $subpanel) {
					foreach($subpanel["fields"] as $field) {
						if  (array_keys($field)[0]) {
							array_push($option_fields, $field);
						} else {
							foreach($field as $sub_field) {
							array_push($option_fields, $sub_field);								}
						}
					}
				}
			}

			return $option_fields;

		}








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
			$html = "";
			if ($errors && !empty($errors->errors)) {
				//$html .= "<div class='notice notice-error is-dismissible'>";
				foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
					$html .= "<p>{$output}</p>";
				}
				//$html .= "</div>";
			}

			return $html;

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
		public function fetch_spreadsheet($file_path,  $sheetId = null) {
		

			$sheet_data = [];

			// Instantiate the File class
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			// Check if the file is already in the upload directory
			if ( ! file_exists ($file_path)) {
				return "File <strong>{$file_path}</strong> does not exist";
			}
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

				// Check file extension is allowed.  Alllowed extensions are defined at the begening of the File class
			if ( ! in_array($file_type, $file_instance->file_types)) {
				$error = $file_instance->file_extension()." file type is not allowed. Allowed file extensions are  ".implode(", ", $file_instance->file_types).".  This file was not uploaded.";
				//$this->errors->add("invalid_file_type", __($error, $this->plugin));
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

				//echo json_encode($data);die;

				$new_data = array();

				foreach ($data as $row_index => $row) {
					$new_row = array();
					foreach ($row as $col_index => $cell ) {
						if (isset($data[1][$col_index]) && $data[1][$col_index] && isset($data[2][$col_index]) && $data[2][$col_index])
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

			
				$sheet_data[$sheet_key]["sheetName"]  = $sheet_name;
				$sheet_data[$sheet_key]["labels"] = $labels;
				$sheet_data[$sheet_key]["dataTypes"]  = $data_types;
				$sheet_data[$sheet_key]["data"] = $new_data;
				
				

			}


			if ($sheetId === null) return $sheet_data;

			return $sheet_data[$sheetId];


		} // END public function fetch_spreadsheet($file_path)









		
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

			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVOptions;
			
			// This code is executed if a new chart request or edit request is initiated
			if (isset($_GET["action"])) {

				$divId = "editChart";

				// If action is add new chart
				if ($_GET["action"] == "addNewChart") {

					// Set default file name if no chart is being edited
					$file_path = "{$this->path}assets/{$this->default_file_name}";
					$file = $file_instance->file_parts("{$this->path}assets/{$file_path}");
					$file["name"] = $file["basename"];

					//var_dump($file_path);die;

					// If validate_file() returns WP/Error (validate_file() returns either true or WP/Error
					$result = $file_instance->validate_file($file);
					if ($result !== true) {
						$errors =  __($result, $this->plugin);		
					} else {
					
						// Fetch first sheet(index = 0) of default spreadshheet (fetch_spreadsheet returns either a string if there is an error or an array on success)
						$sheet = $this->fetch_spreadsheet($file_path, 0);

						// If all is good an array is returned by fetch spreadsheet
						if (! is_array($sheet)) {
							$errors .= __($sheet, $this->plugin);
						} else {

							$file_name = $file["basename"];

							// Set option fields values to defaults (this is used to set default chart options)
							$option_fields = array();
							foreach( $this->fetch_fields($this->default_chart_type) as $field) {
								$option_fields[$field["id"]] = $field["default"];
							}

							// Set chart options
							$chart_options = $options->default_chart_options($option_fields, $this->default_chart_type);

							


							// Initialize series index
							$i = 0;
							$subpanels = array();
							
							// Get all sheet labels except for the first column
							$series = $sheet["labels"];
							array_shift($series);

							// Loop through all the label columns to create series for each column of the chart
							foreach($series as $label_key => $label) {

								$trendlines = ($options->option_fields($this->default_chart_type, $i)["trendlines"]);

								//Get trendline fields form the options
								$subpanels[$i]["id"] = $label_key;
								$subpanels[$i]["title"] = $label;
								$subpanels[$i]["fields"] = array_shift($trendlines["subpanels"])["fields"];
								
								$i++;
							}

							// Assemble trendlines array
							$trendlines = array(
								"id" => "trendlines",
								"title" => "Trendlines",
								"subpanels" => $subpanels
							);

							//Loop through the trendlines field options populate the series options with default values
							$trendline_parts = array();
							foreach($trendlines["subpanels"] as $subpanel) {
								foreach($subpanel["fields"] as $field) {
									if  (array_keys($field)[0]) {
										$parts = explode(".", $field["id"]);
										if (isset($field["default"])) $trendline_parts[$parts[1]][$parts[2]] = $field["default"];
									} else {
										foreach($field as $sub_field) {
											$parts = explode(".", $sub_field["id"]);
											if (isset($sub_field["default"])) $trendline_parts[$parts[1]][$parts[2]] = $sub_field["default"];
										}
									}
								}
							}

							$trendline_fields = $this->get_trendlines_subpanel ($trendlines);
						
						
						}
					}

				// If action is edit chart
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

				if (! isset($errors)) {
			
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
				} else {

					// Prepare error output
					$message = "<div class='notice notice-error is-dismissible'><p>{$errors}</p></div>";
				}
				//echo "<pre>"; var_dump($chart_options);die;

				// Prepare output
				$response = array(
					"divId"		=> "editChart",
					"sheet"			=> $sheet,
					"fileName" 	=> $file_name,
					"chartType"  => (isset($chart) && isset($chart["chartType"]))? $chart["chartType"] : $this->default_chart_type,
					"chartOptions"	=> (isset($chart_options))? $chart_options : array(),
					"message" => (isset($message))? $message : "",
					"get" =>$_GET,
					"sheetSelectOptions" => (isset($sheet_select_options))? $sheet_select_options : null,
					"trendlines" => (isset($trendline_fields))? $trendline_fields : "",
					"files" => $this->files,
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
						"message" => $error,
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






		public function get_trendlines_subpanel ($trendlines) {

			// start buffer;
			ob_start();?>

			<!-- open subpanel accordio div -->
			<div class="subPanel accordion" >
			<?php
				// Initialize series
				$i = 0;

				// Loop through trendline subpanel fields
				foreach ($trendlines["subpanels"] as $subpanel_id => $subpanel) {
				 	if (count($trendlines["subpanels"]) != 1) :?>
						<button class ="accordionButton <?php echo $subpanel_id ?>" ><?php echo $subpanel["title"] ?></button>
						<div class = "panel" id="<?php echo subpanel["id"] ?>">
					<?php  endif; ?>

						Show Trendlines <input type="checkbox" id ="<?php echo $i ?>" class="showTrendlines">

						<?php $this->render_field_panel($subpanel);	?>	

					<?php if (count($trendlines["subpanels"]) != 1) :?>
							
						</div><!-- .panel -->
					<?php endif ?>
					<?php $i++ ?>
			
				<?php } ?>
			</div>
			<?php

			$trendline_fields = ob_get_clean();
			ob_end_flush();

			return $trendline_fields;


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

		public function chart_metabox_render($divId, $action= null) {

			//var_dump($action);die;
			
			// Strat buffer
			ob_start();?>

			<div id = "dashboard" >

					<!-- Display admin messages -->
	<div class="<?php echo "adminMessages"; ?>"></div>

		<!-- Display admin messages -->
	<div class="<?php echo "gvErrorMessages"; ?>"></div>


					<?php //if ($action) submit_button("Refresh", 'primary', "refresh", false); ?>
					
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





		/**
		 * Renders admin fields meta boxes
		 */
		function admin_fields_metaboxes_render($chart, $sheet) {

			//$file_select_options = [];
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVOptions;

			?>
			<div id ="dashbboard-admin-fields" class = "accordion">
				<!-- Loop through admin sections -->
				<?php foreach($options->option_fields($this->default_chart_type, null) as $option_id => $option) { ?>
				
					<button  class ="accordionButton <?php echo $option_id ?>"><?php echo $option["title"] ?></button>
					<div class = "panel" id = "<?php echo $option_id ?>" >
						<div class = "subPanel accordion" >
							<!-- Loop through admmin fields -->
							<?php foreach ($option["subpanels"] as $subpanel_id => $subpanel) {
							 	if (count($option["subpanels"]) != 1) :?>	
									<button class ="accordionButton <?php echo $subpanel_id ?>" ><?php echo $subpanel["title"] ?></button>
									<div class = "panel" id="<?php echo $subpanel["id"] ?>">
								<?php  endif;

									$this->render_field_panel($subpanel);		

								if (count($option["subpanels"]) != 1) :?>
									</div><!-- .panel -->
								<?php endif ?>
						
							<?php } ?>
						</div> <!-- subpanel accordion -->
					</div><!-- .panel -->
					
				<?php } ?>
			</div> <!-- accordion -->
			<?php
	

		} // END function admin_fields_metaboxes_render()



		public function render_field_panel($subpanel) {

			foreach($subpanel["fields"] as $field) {
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
		}







		public function render_field_type_template($field, $chart = array()) {

			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVOptions;

			$field["value"] = (isset($chart[$field["id"]]))? $chart[$field["id"]] : $field["default"];

			$input_field = $options->render_input_field($field);

			echo (! is_wp_error($input_field))? $input_field : $this->compose_error_message($input_field);

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
						foreach ($this->possible_field_options() as $option_id => $option_value) {
						if (!isset($field[$option_id])) {
						 $field[$option_id] = $option_value;
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
		foreach ($this->possible_field_options() as $option_id => $option_value) {
			if (!isset($field[$option_id])) {
			$field[$option_id] = $option_value;
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

				//wp_enqueue_style( 'wp-color-picker');
				//wp_enqueue_script( 'wp-color-picker');
				
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
				wp_register_style("{$this->plugin}-admin-css", $this->url . "assets/admin.css", array("wp-color-picker"), null, 'screen');
				wp_enqueue_style("{$this->plugin}-admin-css");

				// Register and Enqueue file upload Javascript and use wp_localize_script to pass data to the javascript handler
					wp_register_script( "{$this->prefix}_handle", "{$this->url}assets/admin.js", array( "jquery", "wp-color-picker" ), false, true );
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