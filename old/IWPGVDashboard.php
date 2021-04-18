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
		public $chart_types = array(
			"LineChart" => "Line Chart",
			"BarChart" => "Bar Chart",
			"ColumnChart" => "Column Chart",
			"PieChart" => "Pie Chart",
			"ScatterChart" => "Scatter Chart"
		); // Possible cahrt types
		public $data_types = array("string", "number", "boolean", "date", "datetime", "timeofday"); // Possible cahrt types
		public $role_types = array("domain", "data", "annotation", "annotationText", "interval", "certainty", "emphasis", "scope", "style", "tooltip" ); // Possible cahrt types
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
		public $default_file_name = "Ge 8-12.xlsx";
		public $default_chart_type = "LineChart";

		// public $chart_options;
		// public $files;
		// public $charts;



		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {
			
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


		public function settings() {
			$settings =  (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

			$charts = (isset($this->settings["charts"]))? $settings["charts"] : array();
		
			$files = (isset($settings["files"]))? $settings["files"] : array();

			return array("charts" => $charts, "files" => $files);
			
		}



	
		/**
		 * Ajax file upload handler
		 * @return array array of data
		 */
		public function file_upload_callback() {

			// Ajax comes with two arrays $_POST and $_FILES
			//echo json_encode($_FILES);wp_die();
			//echo json_encode($_POST);wp_die();

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
					throw new \Exception ( __("File Class method extract_file() failed to return a file array", $this->plugin));
				
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

				// Set default file name if no chart is being edited
				$file_path = wp_upload_dir()['path']."/".sanitize_file_name($file["name"]);

				// Fetch spreadsheet data to check if data types for each sheet are valid
				$spreadsheet = $file_instance->fetch_spreadsheet($file_path);

				// If all is good an array is returned by fetch spreadsheet
				if (! is_array($spreadsheet)) 
						throw new \Exception ( __($spreadsheet, $this->plugin));
				

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

					// loop through the sheet roles types if roles is set
					if (isset($sheet["roles"])) {

						foreach($sheet["roles"] as $col_key => $role ) {
							if (! isset($role) || ( isset($role) && ( $role === null || ! in_array($role, $this->role_types)))) {
								$invalid_role = true;
								$col_role = (isset($role))? $role : "Null";
								$sheet_name = $sheet["sheetName"];
								$sheet_column = $col_key;
								break 2;
							}
						}
					}

				}


				//If all data types are valid
				if (isset($invalid_data_type)) {
					$error = "File: <strong>". sanitize_file_name($file["name"]) . "</strong> Sheet <strong>{$sheet_name}</strong> column <strong>{$sheet_column}</strong> data type <strong>{$col_data_type}</strong> is not a valid data type.  Allowed data types are  <strong>".implode(", ", $this->data_types)."</strong>This file was not uploaded.";
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __($error, $this->plugin));
				}

				//If all data types are valid
				if (isset($invalid_role)) {
					$error = "File: <strong>". sanitize_file_name($file["name"]) . "</strong> Sheet <strong>{$sheet_name}</strong> column <strong>{$sheet_column}</strong> role <strong>{$col_role}</strong> is not a valid role.  Allowed roles are  <strong>".implode(", ", $this->role_types)."</strong>This file was not uploaded.";
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __($error, $this->plugin));
				}

				$files = $this->settings()["files"];

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

					// Make sure there are less than 10000 files in the option settings (I am allowing a maxcimum of 10000 files to be uploaded)
				if (count($files) > $file_instance->max_files)
					throw new \Exception ( __("Too many files.  You have exceeded the maximum  number of files you are allowed to upload ({$file_instance->max_files})", $this->plugin));


				if (! update_option("{$this->prefix}_dashboard", $settings)) {
					wp_delete_file(wp_upload_dir()['path']."/".sanitize_file_name($file["name"]));
					throw new \Exception ( __("Option <strong>{$this->prefix}_dashboard update failed", $this->plugin));
				}

				// compose file selection option
				$file_id_option = "<option value='{$file_id}' selected = 'selected' >{$file["name"]}</option>";

				// Compose sheet selection form
				$sheet_id_options = $this->compose_sheet_id_options($spreadsheet, $file_id);

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
				"sheetIdOptions" => (isset($sheet_id_options))? $sheet_id_options : null,
				"fileIdOption" => (isset($file_id_option))? $file_id_option : null,
				"message" => $message,
				"post" => array("action" => $_POST["action"]),
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
				$file = $this->settings()["files"][$_POST["fileId"]];

				// If file exists
	  			if ( !$file) 
					throw new \Exception ( __("No file with ID = {$_POST["fileId"]} was found", $this->plugin));

				// Set default file name if no chart is being edited
				$file_path = wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]);

				// Fetch spreadsheet data to check if data types for each sheet are valid
				$spreadsheet = $file_instance->fetch_spreadsheet($file_path);

				// If all is good an array is returned by fetch spreadsheet
				if (! is_array($spreadsheet)) 
						throw new \Exception ( __($spreadsheet, $this->plugin));


				// Fetch file selection foem and sheet selection form
				$sheet_id_options = $this->compose_sheet_id_options($spreadsheet, $_POST["fileId"]);

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
				"sheetIdOptions" => (isset($sheet_id_options))? $sheet_id_options : null,
				"message" => $message,
				"post" => array("action" => $_POST["action"]),
				"files" => $this->settings()["files"]
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

			// echo json_encode($_POST);wp_die();
			 
			 // Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVChartOptions;

			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			try {

				// verify if a file , sheet and chart type id's were selected
				if ( ! isset($_POST["fileId"]) ||  ! isset($_POST["sheetId"]) ||  ! isset($_POST["chartType"]) ) 
					throw new \Exception ( __("Please select a file, a sheet and chart type tp proceed", $this->plugin));

				$result = $this->validate_request("refresh_chart", $this->refresh_chart_nonce);

				if ($result !== true)
					throw new \Exception ( __($result, $this->plugin));

				// Retreive files settings options
				$file = $this->settings()["files"][$_POST["fileId"]];
				//echo json_encode($file);wp_die();

				// If there is no file
	  			if ( ! $file || ! is_array( $file )) 
	  				throw new \Exception (__("Either the file with ID = {$_POST["fileId"]} was not found or the file was found but it is invalid", $this->plugin));

	  			// Set file path
	  			$file_path = wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]);

				// Fetch spreadsheet (fetch_spreadsheet returns either a string if there is an error or an array on success)
				$spreadsheet = $file_instance->fetch_spreadsheet($file_path);
				//echo json_encode($spreadsheet);wp_die();
				
				// If all is good an array is returned by fetch spreadsheet
				if ( ! is_array($spreadsheet) ) 
					throw new \Exception ( __($spreadsheet, $this->plugin ) );
				
				// Fetch sheet
				$sheet = $spreadsheet[$_POST["sheetId"]];
				//echo json_encode($sheet);wp_die();

				// Fetch all admin field panels
				$admin_field_panels = $options->admin_field_panels($_POST["chartType"]);
				// echo json_encode($admin_field_panels);wp_die();

				// Add series to admin field panels (insert after chartSettings)
				$series = $this->fetch_series ($sheet, "series");
				$insertion_index = array_search("chartSettings", array_keys($admin_field_panels));
				$admin_field_panels = $this->insert_array($admin_field_panels, $series, "series", $insertion_index+1);
				// echo json_encode($admin_field_panels);wp_die();

				// Add trendlines to admin field panels
				$trendlines = $this->fetch_series ($sheet, "trendlines");
				$insertion_index = array_search("tooltips", array_keys($admin_field_panels));
				$admin_field_panels = $this->insert_array($admin_field_panels, $trendlines, "trendlines", $insertion_index+1);
				// echo json_encode($admin_field_panels);wp_die();
				
				// Fetch field defaults (return fields only.  This is used to set default chart options)
				$field_defaults = array();
				foreach( $this->fetch_panel_fields($admin_field_panels) as $field) {
					$field_defaults[$field["id"]] = $field["default"];
				}
				// echo json_encode($field_defaults);wp_die();

				// Set chart options filed default (remove prefox "chart" fron field id)
				$chart_options_field_defaults = array();
				foreach( $field_defaults as $field_default_id => $field_default) {
					if (false !==  strpos($field_default_id, "chart")) {
						$option_id = substr($field_default_id, strlen("chart."));
						$chart_options_field_defaults[$option_id] = $field_default;
					}
				}
				$chart_options = $options->chart_options( $chart_options_field_defaults); 
				// echo json_encode($chart_options);wp_die();
				
				// Fetch field defaults (return fields only.  This is used to set default chart options)
				$series_defaults = array();
				foreach( $this->fetch_panel_fields($series) as $field) {
					$series_defaults[$field["id"]] = $field["default"];
				}
				// echo json_encode($series_defaults);wp_die();
				
				// Set chart options series filed default (remove prefox "chart" fron field id)
				$series_options_field_defaults = array();
				foreach( $series_defaults as $field_default_id => $field_default) {
					if (false !==  strpos($field_default_id, "chart")) {
						$option_id = substr($field_default_id, strlen("chart."));
						$series_options_field_defaults[$option_id] = $field_default;
					}
				}
				$chart_options["series"] = $this->fetch_series_options($series_options_field_defaults); 
				// echo json_encode($series_defaults);wp_die();
				
					// Fetch field defaults (return fields only.  This is used to set default chart options)
				$trendlines_defaults = array();
				foreach( $this->fetch_panel_fields($trendlines) as $field) {
					$trendlines_defaults[$field["id"]] = $field["default"];
				}
				// echo "<pre>"; var_dump($trendlines_defaults);die;
				
				// Set chart options trendlines filed default (remove prefox "chart" fron field id)
				$trendlines_options_filed_defaults = array();
				foreach( $trendlines_defaults as $field_default_id => $field_default) {
					if (false !==  strpos($field_default_id, "chart")) {
						$option_id = substr($field_default_id, strlen("chart."));
						$trendlines_options_filed_defaults[$option_id] = $field_default;
					}
				}
				$trendlines_chart_options = $this->fetch_series_options($trendlines_options_filed_defaults); 
				// echo json_encode($series_defaults);wp_die();
				
				// Set table chart default options
				$table_chart_options_field_defaults = array();
				foreach( $field_defaults as $default_id => $default) {
					if (false !==  strpos($default_id, "tableChart")) {
						$option_id = substr($default_id, strlen("tableChart."));
						$table_chart_options_field_defaults[$option_id] = $default;
					}
				}
				$table_chart_options = $options->table_chart_options($table_chart_options_field_defaults);

				// Set number range slider default options
				$num_range_filter_options_field_defaults = array();
				foreach( $field_defaults as $default_id => $default) {
					if (false !==  strpos($default_id, "numRangeFilter")) {
						$option_id = substr($default_id, strlen("numRangeFilter."));
						$num_range_filter_options_field_defaults[$option_id] = $default;
					}
				}
				$num_range_filter_options = $options->num_range_options($num_range_filter_options_field_defaults);

				// Set category filter options
				$chart_range_filter_options_field_defaults = array();
				foreach( $field_defaults as $default_id => $default) {
					if (false !==  strpos($default_id, "chartRangeFilter")) {
						$option_id = substr($default_id, strlen("chartRangeFilter."));
						$chart_range_filter_options_field_defaults[$option_id] = $default;
					}
				}
				$chart_range_filter_options = $options->chart_range_filter_options($chart_range_filter_options_field_defaults);

				// Set category filter options
				$category_filter_options_field_defaults = array();
				foreach( $field_defaults as $default_id => $default) {
					if (false !==  strpos($default_id, "categoryFilter")) {
						$option_id = substr($default_id, strlen("categoryFilter."));
						$category_filter_options_field_defaults[$option_id] = $default;
					}
				}
				$category_filter_options = $options->category_filter_options($category_filter_options_field_defaults);

				// Set category filter options
				$string_filter_options_field_defaults = array();
				foreach( $field_defaults as $default_id => $default) {
					if (false !==  strpos($default_id, "stringFilter")) {
						$option_id = substr($default_id, strlen("stringFilter."));
						$string_filter_options_field_defaults[$option_id] = $default;
					}
				}
				$string_filter_options = $options->string_filter_options($string_filter_options_field_defaults);

				// Set number range slider default options
				$min_max_avg_table_options_field_defaults = array();
				foreach( $field_defaults as $default_id => $default) {
					if (false !==  strpos($default_id, "minMaxAvgTable")) {
						$option_id = substr($default_id, strlen("minMaxAvgTable."));
						$min_max_avg_table_options_field_defaults[$option_id] = $default;
					}
				}
				$min_max_avg_table_options = $options->min_max_avg_table_options($min_max_avg_table_options_field_defaults);

				// Get filter column select field options for all chart controls
				$columns_options = $this->compose_filter_columns_options ( $sheet );
				//var_dump($columns_options);die;
		
				// Retreive relevant panels (all panels, subpanels and fields with relevant chart type and data type)
				$relevant_field_panels = $this->fetch_relevant_field_panels ($admin_field_panels, $sheet);

				$panels = $this->render_admin_field_panels($relevant_field_panels);

				// $non_relevant_panels = array();
				// foreach ($admin_field_panels as $panel_id => $panel) {
					
				// 	if (! in_array($panel_id, array_keys($relevant_field_panels)))
				// 		$non_relevant_panels[$panel_id] = $panel;


				// }
				//echo json_encode($panels);wp_die();


				// Add success message
				$message = "<div class='notice notice-success is-dismissible'><p>Refresh successful</p></div>";

			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Compose response
			$response = array(

				"fileName" => $file["fileName"],
				"message" => $message,
				"divId" => "editChart",
				"relevantFieldPanels" => $panels,
				"sheet"			=> (isset($sheet))? $sheet : array(),
				"rangeFilterColumnOptions" => $columns_options["numberColumn"],
				"stringFilterColumnOptions" => $columns_options["stringColumn"],
				"chartOptions"	=> (isset($chart_options))? $chart_options : array(),
				"trendlinesChartOptions" => (isset($trendlines_chart_options))? $trendlines_chart_options : array(),
				"tableChartOptions" => (isset($table_chart_options))? $table_chart_options : array(),
				"numRangeFilterOptions" => (isset($num_range_filter_options))? $num_range_filter_options : array(),
				"categoryFilterOptions" => (isset($category_filter_options))? $category_filter_options : array(),
				"stringFilterOptions" => (isset($string_filter_options))? $string_filter_options : array(),
				"chartRangeFilterOptions" => (isset($chart_range_filter_options))? $chart_range_filter_options : array(),
				"minMaxAvgTableOptions" => (isset($min_max_avg_table_options))? $min_max_avg_table_options : array(),
				"post" => array(
					"fileId" => $_POST["fileId"],
					"action" => $_POST["action"],
					"chartType" => $_POST["chartType"],
					"sheetId" => $_POST["sheetId"]
				),

			);

			// return ajax data
			echo json_encode($response);
			wp_die();
			

		}  // END public function contact_form_process() {



		function fetch_series_options($id_value_pairs) {

			//echo "<pre>";var_dump($id_value_pairs);die;

			// compose series field options
			$series_options = array();
			foreach($id_value_pairs as $field_id => $field_value) {
				
				// Get filed id parts
				$parts = explode(".", $field_id);

				// extract option
				switch (count($parts)) {

					case 6:
						$series_options[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]] = $field_value;
						break;

					case 5:
						$series_options[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]] = $field_value;
						break;

					case 4:
						$series_options[$parts[0]][$parts[1]][$parts[2]][$parts[3]] = $field_value;
						break;

					case 3:
						$series_options[$parts[0]][$parts[1]][$parts[2]] = $field_value;
						break;

					case 2:
						$series_options[$parts[0]][$parts[1]] = $field_value;
						break;

					case 1:
						$series_options[$parts[0]] = $field_value;
						break;
					
					default:
						die("Something terrible happened");
						break;
				}
				
			}
			// $series_options;

			// echo "<pre>";var_dump(array_shift($series_options));die;


			return array_shift($series_options);
		}






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
				if (! $_POST["fileId"] )
					throw new \Exception ( __("No file selected.  Please slect a file", $this->plugin));

				// verify if a sheet was selected (sheet id submitted)
				if ( $_POST["sheetId"] == "") 
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
				"post" => array("action" => $_POST["action"]),
				//"redirect" => add_query_arg(array("page" => $this->plugin, "action" => "editChart", "chartId" => $chart_id), admin_url('admin.php'))
			);

			// return ajax data
			echo json_encode($response);
			wp_die();
			

		}  // END public function contact_form_process() {








		public function validate_request($action, $nonce) {

			// If request is not POST, not an ajax request or does not come from the file upload form
			if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST["action"]) || $_POST["action"] != "{$this->prefix}_{$action}_hook") 
				return __("This is not a valid {$action}_ajax request", $this->plugin);

			// verify file upload nonce
			if (! wp_verify_nonce( $_POST["nonce"], "{$this->prefix}__{$nonce}" )) 
				return __("An invalid {$action}_nonce was submited", $this->plugin);

			return true;

		}








		/**
		 * Composes sheet select forms to be displayed on the file select form
		 * @param  array $sheeets Array of sheets from a given spreadsheet file
		 * @return Array         Array contains a file selection from (String) and a sheet selection form (String)
		 */
		public function compose_sheet_id_options ($spreadsheet, $selected = null)  {

			//echo json_encode($spreadsheet);wp_die();

			// Compose sheet selection form
			//$sheet_select_form = "<form id ='sheet-selection-form'>";
			//$sheet_select_form .= "<div class ='sheet'>";
			//$sheet_select_form .= "<select id='sheet-select' name='".$this->prefix."_dashboard[sheet-select]' >";
			//$sheet_select_form .= "<option value=''>Select a Sheet</option>";
			$sheet_id_options = "";
			foreach ($spreadsheet as $sheet_key => $sheet) {
				$sheet_id_options .= "<option value='".(intval($sheet_key))."' ".selected(intval($sheet_key), $selected, false).">{$sheet["sheetName"]}</option>";
			}
			//$sheet_select_form .= "</select>";
			//$sheet_select_form .= "</div>";
			//$sheet_select_form .= "</form>";

			return $sheet_id_options;

		}





		/**
		 * Composes sheet select forms to be displayed on the file select form
		 * @param  array $sheeets Array of sheets from a given spreadsheet file
		 * @return Array         Array contains a file selection from (String) and a sheet selection form (String)
		 */
		public function compose_filter_columns_options ( $sheet, $selected = array() )  {

			$selected_number_column = (isset($selected["numberColumn"]))? $selected["numberColumn"] : null; 
			$selected_string_column = (isset($selected["stringColumn"]))? $selected["stringColumn"] : null;  


			// Initialize domains aray
			$number_columns = array();
			$i=0;

			foreach($sheet["dataTypes"] as $key => $value) {
				if ($value == "number" && isset($sheet["labels"][$key]) && $sheet["labels"][$key]) $number_columns[$i] = $sheet["labels"][$key];
				$i++;
			}

			$number_columns_options = "";
			foreach ($number_columns as $number_columns_key => $number_column) {
				$number_columns_options .= "<option value='{$number_columns_key}' ".selected(intval($number_columns_key), $selected_string_column, false).">{$number_column}</option>";
			}

			// Initialize domains aray
			$string_columns = array();
			$i=0;


			foreach($sheet["dataTypes"] as $key => $value) {
				if ($value == "string" && isset($sheet["labels"][$key]) && $sheet["labels"][$key]) $string_columns[$i] = $sheet["labels"][$key];
				$i++;
			}

		
			$string_columns_options = "";
			foreach ($string_columns as $string_columns_key => $string_column) {
				$string_columns_options .= "<option value='{$string_columns_key}' ".selected(intval($string_columns_key), $selected_number_column, false).">{$string_column}</option>";
			}

			return $output = array( "numberColumn" => $number_columns_options, "stringColumn" => $string_columns_options );


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
					$html .= "<p>{$message}</p>";
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
			

			// Instantiate the file class to validate and upload file
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

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
			$data = $file_instance->fetch_spreadsheet("{$this->path}assets/img/test-spreadsheet.xlsx");

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






		/**
		 * Renders dashboard submenu page content 
		 * @return null
		 */
		public function dashboard_submenu_page_render() {


			// Instantiate the database class
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVChartOptions;
			
			// This code is executed if a new chart request or edit request is initiated
			if (isset($_GET["action"])) {

				$divId = "editChart";

				// If action is add new chart
				if ($_GET["action"] == "addNewChart") {

					// Set default file name if no chart is being edited
					

					// Fetch all admin field panels
					$admin_field_panels = $options->admin_field_panels($this->default_chart_type);

						// Add series to admin field panels
					
					//$admin_field_panels["trendlines"] = $trendlines["trendlines"];
					
					// Fetch field defaults (return fields only.  This is used to set default chart options)
					$field_defaults = array();
					foreach( $this->fetch_panel_fields($admin_field_panels) as $field) {
						$field_defaults[$field["id"]] = $field["default"];
					}
					//echo "<pre>"; var_dump($field_defaults);die;

					// Set chart options filed default (remove prefox "chart" fron field id)
					$chart_options_field_defaults = array();
					foreach( $field_defaults as $field_default_id => $field_default) {
						if (false !==  strpos($field_default_id, "chart")) {
							$option_id = substr($field_default_id, strlen("chart."));
							$chart_options_field_defaults[$option_id] = $field_default;
						}
					}
					$chart_options = $options->chart_options( $chart_options_field_defaults); 
					//echo "<pre>"; var_dump($chart_options);die;
					
					// Fetch field defaults (return fields only.  This is used to set default chart options)
					
					//echo "<pre>"; var_dump($chart_options);die;
					
						// Fetch field defaults (return fields only.  This is used to set default chart options)
					
					//echo "<pre>"; var_dump($trendlines_chart_options);die;
					
					// Set table chart default options
					$table_chart_options_field_defaults = array();
					foreach( $field_defaults as $default_id => $default) {
						if (false !==  strpos($default_id, "tableChart")) {
							$option_id = substr($default_id, strlen("tableChart."));
							$table_chart_options_field_defaults[$option_id] = $default;
						}
					}
					$table_chart_options = $options->table_chart_options($table_chart_options_field_defaults);

					// Set number range slider default options
					$num_range_filter_options_field_defaults = array();
					foreach( $field_defaults as $default_id => $default) {
						if (false !==  strpos($default_id, "numRangeFilter")) {
							$option_id = substr($default_id, strlen("numRangeFilter."));
							$num_range_filter_options_field_defaults[$option_id] = $default;
						}
					}
					$num_range_filter_options = $options->num_range_options($num_range_filter_options_field_defaults);

					// Set category filter options
					$chart_range_filter_options_field_defaults = array();
					foreach( $field_defaults as $default_id => $default) {
						if (false !==  strpos($default_id, "chartRangeFilter")) {
							$option_id = substr($default_id, strlen("chartRangeFilter."));
							$chart_range_filter_options_field_defaults[$option_id] = $default;
						}
					}
					$chart_range_filter_options = $options->chart_range_filter_options($chart_range_filter_options_field_defaults);

					// Set category filter options
					$category_filter_options_field_defaults = array();
					foreach( $field_defaults as $default_id => $default) {
						if (false !==  strpos($default_id, "categoryFilter")) {
							$option_id = substr($default_id, strlen("categoryFilter."));
							$category_filter_options_field_defaults[$option_id] = $default;
						}
					}
					$category_filter_options = $options->category_filter_options($category_filter_options_field_defaults);

					// Set category filter options
					$string_filter_options_field_defaults = array();
					foreach( $field_defaults as $default_id => $default) {
						if (false !==  strpos($default_id, "stringFilter")) {
							$option_id = substr($default_id, strlen("stringFilter."));
							$string_filter_options_field_defaults[$option_id] = $default;
						}
					}
					$string_filter_options = $options->string_filter_options($string_filter_options_field_defaults);

					// Set number range slider default options
					$min_max_avg_table_options_field_defaults = array();
					foreach( $field_defaults as $default_id => $default) {
						if (false !==  strpos($default_id, "minMaxAvgTable")) {
							$option_id = substr($default_id, strlen("minMaxAvgTable."));
							$min_max_avg_table_options_field_defaults[$option_id] = $default;
						}
					}
					$min_max_avg_table_options = $options->min_max_avg_table_options($min_max_avg_table_options_field_defaults);

					// Compose doamin options
					//$selected_columns = array( "numberColumn" => 0, "stringColumn" => 1 );
					//$selected_columns =array();
					//$columns_options = $this->compose_filter_columns_options ( $sheet );
					//var_dump($columns_options);die;
			
					// Retreive all admin field panels
					//$relevant_field_panels = $this->fetch_relevant_field_panels ($admin_field_panels, $sheet);

					// Compose admin fields render
					$this->add_admin_fields_metabox($admin_field_panels);

					// Render chart metabox
					$this->add_chart_metabox (null, null, $divId, $_GET["action"] );
					
				

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
						$file = $files[$chart["fileId"]];

						// Fetch sheet data 
						$sheet = $this->fetch_sheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]), $chart["sheet"]);

						// If errors set errors
						if (is_wp_error($sheet)) 
							$this->errors->add("sheet_not_found",__($this->compose_error_message($sheet), $this->plugin));

						$file_name = $file["fileName"];

						// Fetch spreadsheet data 
						$spreadsheet = $file_instance->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file_name));

						// Fetch file selection foem and sheet selection form
						$sheet_id_options = $this->compose_sheet_id_options($spreadsheet, $chart["sheet"]);

					}
				}

				$message =(isset($errors))? "<div class='notice notice-error is-dismissible'><p>{$errors}</p></div>" : null;

				// Prepare output
				$response = array(
					"divId"		=> "editChart",
					//"sheet"			=> (isset($sheet))? $sheet : array(),
					//"fileName" 	=> $file_name,
					//"chartType"  => $this->default_chart_type,
					//"rangeFilterColumnOptions" => $columns_options["numberColumn"],
					//"stringFilterColumnOptions" => $columns_options["stringColumn"],
					"chartOptions"	=> (isset($chart_options))? $chart_options : array(),
					"trendlinesChartOptions" => (isset($trendlines_chart_options))? $trendlines_chart_options : array(),
					"tableChartOptions" => (isset($table_chart_options))? $table_chart_options : array(),
					"numRangeFilterOptions" => (isset($num_range_filter_options))? $num_range_filter_options : array(),
					"categoryFilterOptions" => (isset($category_filter_options))? $category_filter_options : array(),
					"stringFilterOptions" => (isset($string_filter_options))? $string_filter_options : array(),
					"chartRangeFilterOptions" => (isset($chart_range_filter_options))? $chart_range_filter_options : array(),
					"minMaxAvgTableOptions" => (isset($min_max_avg_table_options))? $min_max_avg_table_options : array(),
					"message" => $message,
					"get" =>$_GET,
					//"sheetIdOptions" => $sheet_id_options,
					//"templateId" => "{$this->prefix}-dashboard",
					//"extraPanels" => (isset($extra_panels))? $extra_panels : "",
					//"trendlines" => (isset($trendline_fields))? $trendline_fields : "",
					//"files" => $this->settings()["files"],
					//"series" => $series
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
				//echo $this->get_template_html("edit-chart");
				//
				$this->render_edit_chart_template();

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
						$file_id = (isset($chart["fileId"]))?  $chart["fileId"] :  null;

						// If there is no file
	  					if ( ! $file_id) 
	  					$this->errors->add("id_{$chart_id}_not_found", __("No file ID = {$chart["fileId"]} was found", $this->plugin));

	  					// get file
	  					$file = $files[$file_id];

	  					// check if a file is found
						if (! $file)
							$this->errors->add("file_{$chart_id}_not_found", __("ID = {$chart["fileId"]} submitted but no file with this ID was found in the database", $this->plugin));
						
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
						// $chart_options = $this->chart_options($options);

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
					

					// Compose response
					$response = array(
						"chartList" => $chart_list,
						"message" => $error,
						"get" =>$_GET,
						//"chartOptions"	=> $chart_options
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





		// public function fetch_gv_option_fields ($fields) {
			
		// 	$default_chart_option_fields = array();

		// 	foreach( $fields as $field_defaults_id => $field_default) {

		// 		// Get filed id parts
		// 		$parts = explode(".", $field_defaults_id);

		// 		// extract option
		// 		switch (count($parts)) {

		// 			case 7:
		// 				$default_chart_option_fields[$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]][$parts[6]] = $field_default;
		// 				break;

		// 			case 6:
		// 				$default_chart_option_fields[$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]] = $field_default;
		// 				break;

		// 			case 5:
		// 				$default_chart_option_fields[$parts[1]][$parts[2]][$parts[3]][$parts[4]] = $field_default;
		// 				break;

		// 			case 4:
		// 				$default_chart_option_fields[$parts[1]][$parts[2]][$parts[3]] = $field_default;
		// 				break;

		// 			case 3:
		// 				$default_chart_option_fields[$parts[1]][$parts[2]] = $field_default;
		// 				break;

		// 			case 2:
		// 				$default_chart_option_fields[$parts[1]] = $field_default;
		// 				break;
					
		// 			default:
		// 				die("Something terrible happened");
		// 				break;
		// 		}

		// 	}

		// 	return $default_chart_option_fields;

		// }





		public function insert_array($arr, $arr_to_insert, $array_key, $insertion_index) {

									//echo "<pre>"; var_dump($arr);die;
			
			// Get an array containg all elements between key and the end of the array
			$end = array_splice($arr, $insertion_index);
						
			// Insert array array at the end of the original array
			$arr[$array_key] = $arr_to_insert[$array_key];

			// Return merged array
			return array_merge($arr, $end);

		}




		public function fetch_relevant_field_panels ($admin_field_panels, $sheet){

				//echo "<pre>"; var_dump($admin_field_panels);die;


			// Initialize annotations array ( Informs whether annotations exists and for which series)
			$annotations = array();
			$i = -1;

			// Loop through sheet roles
			foreach($sheet["roles"] as $role_key => $role) {

				// continue if domain column
				if ($role === " domain") continue;
				
				// Intitialize series annotations if column ids data
				if ($role === "data") {
					$i++;
					$annotations[$i] = array();

				// Populate annotations array
				} else {
					if (isset($annotations[$i])) array_push($annotations[$i], $role);
				}
			}

			//echo "<pre>"; var_dump($annotations);die;
			
			// Initialize annotation flag (Will be set to true if any column in the sheet has annotations)
			$annotation_flag = false;

			// Loop through all series and eliminate annotations options for any series that does not have corresponding annotation columns
			foreach($admin_field_panels["series"]["subpanels"] as $subpanel_id => $subpanel) {
				foreach($subpanel["fields"] as $row_index => $row) {
					foreach ($row as $field_index => $field) {
						if (empty($annotations[$subpanel_id])) {
							if ( false !== strpos($field["id"], "annotations"))
								unset($admin_field_panels["series"]["subpanels"][$subpanel_id]["fields"][$row_index][$field_index]);
						} else {
							$annotation_flag = true;
						}

					}
				}
			}

			// Loop through all series and clean up empty rows
			foreach($admin_field_panels["series"]["subpanels"] as $subpanel_id => $subpanel) {
				foreach($subpanel["fields"] as $row_index => $row) {
					if (empty($row)) unset($admin_field_panels["series"]["subpanels"][$subpanel_id]["fields"][$row_index]);
				}
			}
			
			// Filter panels to eliminate non relevent chart type panels, subpanels and fields; and datatype fields
			foreach ($admin_field_panels as $panel_id => $panel) {

				// Unset panels with non relevant chart types
				if ( 
						( isset($panel["chartTypes"]) && ! in_array($this->default_chart_type, $panel["chartTypes"]) )
						||
						( isset($panel["dataType"]) &&  (	(array_values($sheet["dataTypes"])[0] == "string" && $panel["dataType"] != "discrete") || ( array_values($sheet["dataTypes"])[0] == "number" && $panel["dataType"] != "continuous") ) )
					) {
					unset($admin_field_panels[$panel_id]);
				
				} else {
					foreach($panel["subpanels"] as $subpanel_id => $subpanel) {

						// Unset subpanels with non relevant chart types and annnotationa
						if ( 
								(isset($subpanel["chartTypes"]) && ! in_array($this->default_chart_type, $subpanel["chartTypes"]))
								||
								(! $annotation_flag && false !== strpos($subpanel_id, "annotations"))
								||
								( isset($subpanel["dataType"]) &&  (	(array_values($sheet["dataTypes"])[0] == "string" && $subpanel["dataType"] != "discrete") || ( array_values($sheet["dataTypes"])[0] == "number" && $subpanel["dataType"] != "continuous") ) )
							) {
							unset($admin_field_panels[$panel_id]["subpanels"][$subpanel_id]);
						} else {
							foreach($subpanel["fields"] as $row_index => $row) {
								foreach ($row as $field_index => $field) {

									// Unset admin fields with non relevant chart types and data types (continuous or discrete)
									if ( 
										(isset($field["chartTypes"]) && ! in_array($this->default_chart_type, $field["chartTypes"]))
										||
										( isset($field["dataType"]) &&  (	(array_values($sheet["dataTypes"])[0] == "string" && $field["dataType"] != "discrete") || ( array_values($sheet["dataTypes"])[0] == "number" && $field["dataType"] != "continuous") ) )
							 		
							 		) 
										unset($admin_field_panels[$panel_id]["subpanels"][$subpanel_id]["fields"][$row_index][$field_index]);

								}
							}
						}
						
					}
				}
				
			}

			//echo "<pre>"; var_dump($admin_field_panels);die;

			return $admin_field_panels;
		}




		function render_edit_chart_template() {

			?>
			<div class="wrap" id="<?php echo "{$this->plugin}-dashboard"; ?>">
				<div class="icon32" id="<?php echo "{$this->plugin}-icon"; ?>"></div><br> <!-- Add the icon to the page -->
				<!-- <h2><?php //echo "{$this->title}-New Chart"; ?></h2> -->

				<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
				<?php settings_errors("{$this->prefix}_dashboard");?>

				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
								
						<!-- meta boxes container 1 -->
						<div id="postbox-container-1" class="postbox-container">

							<!-- Settings fields form -->
							<form id ="chartForm" method='post' >

								<?php settings_fields("{$this->prefix}_dashboard");?>

								<!-- Used to save closed meta boxes and their order */ -->
						   	<?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>
						   	<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>

								<!-- Display reset settings button -->
								<div class="reset-settings">
									<?php //submit_button('Reset Settings', 'delete', 'reset_settings', false, array());?>
								</div>
								
								<?php do_meta_boxes($this->dashboard_hook, 'side', null);?>
								<?php //submit_button("Save Chart", 'primary', 'saveChart');?>
								
							</form>

						</div><!-- #post-box-container-1 -->

							<!-- meta boxes container 2 -->
						<div id="postbox-container-2" class="postbox-container">

							<div id="chart-metabox">
								<?php do_meta_boxes($this->dashboard_hook, 'normal', null);?>
							</div>
						</div> <!-- #post-box-container-2 -->

					</div> <!-- #post-body -->
					<br class="clear">
				</div> <!-- #poststuff -->


			</div> <!-- .wrap -->
			<?php
		}





		public function fetch_series ($sheet, $panel){

			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVChartOptions;

			// Initialize series index
			$i = 0;
			$subpanels = array();

			//echo "<pre>"; var_dump($options->series($i)["chartTypes"]);die;

			// Loop through all the data types columns to create series for each column of the chart
			foreach($sheet["labels"] as $label_key => $label) {
				if (isset($sheet["roles"]) && $sheet["roles"][$label_key] != "data") continue;

				switch ($panel) {
					case 'series':
						$id = $options->series($i)["id"];
						$title = $options->series($i)["title"];
						$chart_types = (isset($options->series($i)["chartTypes"]))? $options->series($i)["chartTypes"] : null;
						$data_type = (isset($options->series($i)["dataType"]))? $options->series($i)["dataType"] : null;
						$elements = $options->series($i);
						break;

					case 'trendlines':
						$id = $options->trendlines($i)["id"];
						$title = $options->trendlines($i)["title"];
						$chart_types = (isset($options->trendlines($i)["chartTypes"]))? $options->trendlines($i)["chartTypes"] : null;
						$data_type = (isset($options->trendlines($i)["dataType"]))? $options->trendlines($i)["dataType"] : null;
						$elements = $options->trendlines($i);
						break;
					
					default:
						# code...
						break;
				}

				//Get trendline fields form the options
				$subpanels[$i]["id"] = $label_key;
				$subpanels[$i]["title"] = $label;
				$subpanels[$i]["fields"] = $elements["subpanels"]["fields"];
				
				$i++;
			}

			// Assemble trendlines array
			return array(
				$panel => array(
					"id" => $id,
					"title" => $title,
					"chartTypes" => $chart_types,
					"dataType" => $data_type,
					"subpanels" => $subpanels
				)
			);


		}






		public function fetch_panel_fields($panels) {
			
			$all_fields = array();

			//Loop through admmin fields to populate option fields
			foreach ($panels as  $panel_id => $panel) {
				foreach($panel["subpanels"] as $subpanel_id => $subpanel) {

					foreach($this->fetch_subpanel_fields($subpanel) as $field) {
						array_push($all_fields, $field );
					}
				}
				
			}

			return $all_fields;

		}







		public function fetch_subpanel_fields($subpanel) {

			$subpanel_fields = array();

			foreach($subpanel["fields"] as $row) {
				foreach($row as $field) {
					array_push($subpanel_fields, $field);
				}
			}

			return $subpanel_fields;

		}






		public function fetch_subpanels ($subpanels) {

			?>
			<!-- open subpanel accordio div -->
			<div class="subPanel accordion" >
			<?php
				// Loop through trendline subpanel fields
				foreach ($subpanels as $subpanel_id => $subpanel) {
				 	if (count($subpanels) != 1) :?>
						<button class ="accordionButton <?php echo $subpanel_id ?>" ><?php echo $subpanel["title"] ?></button>
						<div class = "panel" id="<?php echo $subpanel["id"] ?>">
					<?php  endif; ?>
						<!-- Show Trendlines <input type="checkbox" id ="<?php //echo "series-{$i}" ?>" class="showTrendlines"> -->
						<?php $this->render_fields($subpanel);	?>	
					<?php if (count($subpanels) != 1) :?>
						</div><!-- .panel -->
					<?php endif ?>
				<?php } ?>
			</div>
			<?php

		}



		public function add_chart_metabox ($file_name, $sheet, $divId, $action ) {
			// Add/Edit chart meta box				
			add_meta_box(
				"{$this->plugin}-edit-chart-metabox",
				(isset($sheet) && ! empty($sheet))? "{$file_name} - {$sheet["sheetName"]}" : "New Chart" ,
				array($this, "chart_metabox_render" ),
				// function ($object, $params) { // object is passed as first parameter from do_meta_boxes, $params[$args] is the last argument in add_meta_box
				// 	$divId = $params["args"]["divId"];
				// 	$action = $params["args"]["action"];

				// 	echo $this->chart_metabox_render($divId, $action);
				// },
				$this->dashboard_hook,
				'normal',
				'default',
				array("divId" => $divId, "action" => $action)
			);
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

		//public function chart_metabox_render($divId, $action= null) {
		public function chart_metabox_render($object, $params) {



			$divId = $params["args"]["divId"];
			
			?>
			<div id = "dashboard" >

				<!-- Ajax loading jiff -->
				<div id="adminAjaxLoading">
					<!-- <h2> Please wait while we process your request.  This may take a while</h2> -->
					<img src="<?php echo "{$this->url}assets/img/loading.gif" ?>" alt="">
				</div>
					
				<!-- Display admin messages -->
				<div id="<?php echo "adminMessages"; ?>"></div>
					
				<!-- Display google chart admin messages -->
				<div id="<?php echo "gvErrorMessages"; ?>"></div>


				<?php if ( $params["args"]["action"] == "addNewChart" ): ?>

					<div id = "empty-chart">
							Hello World
					</div>

				<?php endif; ?>
						

					<!-- Dashboard, Chart and Control divs -->
					<div id = "<?php echo "dashboard-{$divId}-div"; ?>" ></div>
					<div id="filters-min-max-avg">
						<div id="num-range-filter">
							<div id = "<?php echo "num-range-filter-{$divId}-div"; ?>" ></div>
							<div id = "nrfHighLowInputs">
								from:<input type="text" id="inputRangeMin" name = "inputRangeMin">
								To:<input type="text" id="inputRangeMax" name = "inputRangeMax">
							</div>
						</div>
						<div id = "<?php echo "category-filter-{$divId}-div"; ?>" ></div>
						<div id = "<?php echo "string-filter-{$divId}-div"; ?>" ></div>
						<div id = "<?php echo "min-max-avg-table-{$divId}-div"; ?>" ></div>
					</div>
					<div id = "<?php echo "chart-{$divId}-div"; ?>" ></div>
					<div id="chart-range-filter">
						<div id = "<?php echo "chart-range-filter-{$divId}-div"; ?>" ></div>
						<div id = "crfStartEndInputs">
							from:<input type="text" id="inputRangeStart" name = "inputRangeStart">
							To:<input type="text" id="inputRangeEnd" name = "inputRangeEnd">
						</div>
					</div>
					<div id = "<?php echo "table-chart-{$divId}-div"; ?>" ></div>
			</div>
			<?php

		}





		public function add_admin_fields_metabox($panels) {

			add_meta_box(
				"{$this->prefix}-admin-fields",
				"Admin Fields",
				array($this, "admin_fields_metaboxes_render" ),
				$this->dashboard_hook,
				'side',
				'default',
				array(
					//"chart" => (isset($chart))? $chart : array(),
					//"sheet" => (isset($sheet))? $sheet : array(),
					"panels" => $panels
				)
			);
		} 





		/**
		 * Renders admin fields meta boxes
		 */
		function admin_fields_metaboxes_render($object, $params) {

			//$chart = $params["args"]["chart"];
			//$sheet = $params["args"]["sheet"];
			$panels =$params["args"]["panels"];



			//$file_id_options = [];
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVChartOptions;

			?>
			<div id ="dashboard-admin-fields" class = "accordion">
				<?php $this->render_panels($panels); ?>
					<input class="button button-primary" id="saveChart" value = "Save Chart" />
			</div> <!-- accordion -->
			<?php
	

		} // END function admin_fields_metaboxes_render()



		public function render_admin_field_panels($panels){

			ob_start();
			$this->render_panels($panels);
			$html = ob_get_contents();
			ob_end_clean();
			return $html;

		}

		public function render_panels($panels) {

			//echo "<pre>"; var_dump($panels);die;

			?>
				<!-- Loop through admin sections -->
			<?php foreach($panels as $panel_id => $panel) { ?>
				<button  class ="accordionButton <?php echo $panel_id ?>"><?php echo $panel["title"] ?></button>
				<div class = "panel" id = "<?php echo $panel_id ?>" >
					<div class="panelIntro"><?php echo (isset($panel["intro"]))? $panel["intro"] : ""; ?></div>
					<?php $this->fetch_subpanels ($panel["subpanels"]); ?>
				</div><!-- .panel -->
			<?php }

		} 



		public function render_fields($subpanel) {

			foreach($subpanel["fields"] as $row) {
				?>
				<?php if  ( count($row) < 2) : ?>
					<div class="singleField">
				 <?php else : ?> 
					<div class="multipleFields">
				<?php endif; ?>
				<?php 
				foreach($row as $field) {
					$this->render_field_type_template($field);
				}
				?>
				</div>
				<?php
			}
		}







		public function render_field_type_template($field, $chart = array()) {

			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $options = new IWPGVChartOptions;

			$field["value"] = (isset($chart[$field["id"]]))? $chart[$field["id"]] : $field["default"];
			$input_field = $options->render_input_field($field);

			echo (! is_wp_error($input_field))? $input_field : $this->compose_error_message($input_field);

			// Add file upload button if input field type if file
			if ($field["id"] == "chart.fileUpload") submit_button("Upload File", 'primary', "fileUploadSubmit", false);

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
				wp_register_style("{$this->plugin}-admin-css", $this->url . "assets/admin.css", array("wp-color-picker"), false, "all");
				wp_enqueue_style("{$this->plugin}-admin-css");

				// Register and Enqueue file upload Javascript and use wp_localize_script to pass data to the javascript handler
					wp_register_script( "{$this->prefix}_handle", "{$this->url}assets/admin.js", array( "jquery", "wp-color-picker" ), false, "all" );
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
				if (! ($_POST["fileId"])) 
					throw new \Exception (__("No file selected.  Please slect a file", $this->plugin));

				// Retreive settings options
				$settings = (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

				// Retreive files settings options
				$file = (isset($settings["files"]))? $settings["files"][$_POST["fileId"]] : null;

				// If there is no file
	  			if ( ! $file) 
	  				throw new \Exception (__("No file with ID = {$_POST["fileId"]} was found", $this->plugin));

				// Fetch spreadsheet data 
				$sheet = $this->fetch_sheet(wp_upload_dir()['path']."/".sanitize_file_name($file["fileName"]), $_POST["sheetId"]);

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
				// $chart_options = $this->chart_options($options);
				
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
				"fileId" => (isset($_POST["fileId"]))? $_POST["fileId"] : null,
				"sheet" => $sheet,
				"rangeSliderColSelect" => $range_slider_options,
				//"chartOptions" => $chart_options,
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
			if ( class_exists("IWPGV\\Includes\\IWPGVFile")) $file_instance = new IWPGVFile;

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
									//$spreasheet =  $file_instance->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file->name))
									$response[$chart_id]["spreadsheet"] = $file_instance->fetch_spreadsheet(wp_upload_dir()['path']."/".sanitize_file_name($file->name))["sheet-data"][$chart["sheetId"]]["formatted"];
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
					//"sheet-select-options" => $sheet_id_options,
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