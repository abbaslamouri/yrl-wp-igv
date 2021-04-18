<?php

/**
* YRL WP Interactive Google Visualization
*
* @package YRL WP Interactive Google Visualization
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace YRL_WP_IGV\Includes;

// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');

// Declare main class if it does not exist
if (!class_exists('Dashboard')) {

	class Dashboard {

		public $name = YRL_WP_IGV_NAME; 				// title of the plugin
		public $path = YRL_WP_IGV_PATH; 				// Path to the plugin directory
		public $url = YRL_WP_IGV_URL; 					// URL to the plugin directory
		public $base = YRL_WP_IGV_BASE; 				// represents plugin-dir/plugin-file
		public $prefix = YRL_WP_IGV_PREFIX; 		// prefix (YRL_WP_IGV)
    public $plugin = YRL_WP_IGV_PLUGIN; 		// plugin (axl-wp-ugv)
		
		public $chart_types = [						// All chart types supported by this plugin
      "" => "Select Chart Type",
			"LineChart" => "Line Chart",
			"BarChart" => "Bar Chart",
			"ColumnChart" => "Column Chart",
			"PieChart" => "Pie Chart",
			"ScatterChart" => "Scatter Chart"
		];
		
    public $plugin_options = [        // This plugin options
			"version" => "1.0.0"
		];
		
		protected $file_types = [        	// Possible file types
			"xlsx", "Xlsx", "xls", "Xls", "csv", "Csv", "xlsm", "Xlsm"
		];
		public $data_types = array(				// Possible chart types
			"string", "number", "boolean", "date", "datetime", "timeofday"
		); 
		public $role_types = array(				// Possible role type
			"domain", "data", "annotation", "annotationText", "interval", "certainty", "emphasis", "scope", "style", "tooltip"
		);

    public $colors = [ 								// Possible chart colors
			'#1976D2', '#00796B', '#581845', '#455A64', '#263238', '#303F9F', '#33691E', '#7B1FA2', '#0097A7', '#EF6C00', '#795548', '#FFA000'
		];
      
    public $point_shape_types = [		// Possible chart point types
			'circle', 'square', 'triangle', 'diamond', 'star', 'polygon','circle', 'square', 'triangle', 'diamond', 'star', 'polygon'
		];

    public $font_names = [					// Chart possible fonts
      "arial" => "Arial",
      'Times-Roman' => "Times Roman",
      "courier" => "Courier",
      "verdana" => "Verdana",
      "georgia" => " Georgia",
      "tahoma" => "Tahoma"
		];
		
    protected $continuous_chart_types = [  // POssible continuous chart types
      'LineChart', 'ComboChart', 'ScatterChart', 'BarChart', 'ColumnChart'
		];
		


		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct() {

			// Declare upload directory
			$this->upload_path = wp_upload_dir()['path']."/";

			// Add plugin settings link
			add_filter("plugin_action_links_" . $this->base, function ($links) {
				$settings_link = "<a href='admin.php?page=" . "{$this->prefix}" . "'>" . __("Settings") . "</a>";
				array_push($links, $settings_link);
				return $links;
			});

			// Plugin activation and deactivation registration
			register_activation_hook($this->base, array($this, 'activate'));
			register_deactivation_hook($this->base, array($this, 'deactivate'));


			// Add admin Submenus, section and fields
      add_action('admin_menu', array($this, 'admin_menu_register'));

			// // Plugin options
      $this->settings = get_option("{$this->prefix}_settings") ? get_option("{$this->prefix}_settings") : [];
      $this->file_settings = isset($this->settings['files']) ? $this->settings['files'] : [];
			$this->chart_settings = isset($this->settings['chart']) ? $this->settings['chart'] : [];
			
			$this->settings =  (!empty(get_option("{$this->prefix}_settings")))? get_option("{$this->prefix}_settings") : array();

			$this->charts = (isset($this->settings["charts"]))? $this->settings["charts"] : array();
		
			$this->files = (isset($this->settings["files"]))? $this->settings["files"] : array();
		

			// Register styles and scripts
			add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
      add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
      
      
			// Add ajax file upload capability
			add_action( "wp_ajax_{$this->prefix}_file_upload_action", array($this, 'file_upload' ));
      add_action( "wp_ajax_nopriv_{$this->prefix}_file_upload_action", array($this, 'file_upload' ));

      // Add ajax chart file selection capability
			add_action( "wp_ajax_{$this->prefix}_file_select_action", array($this, 'file_select' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_file_select_action", array($this, 'file_select' ));

			// Add ajax chart sheet selection capability
			add_action( "wp_ajax_{$this->prefix}_sheet_chart_type_select_action", array($this, 'sheet_chart_type_select' ));
      add_action( "wp_ajax_nopriv_{$this->prefix}_sheet_chart_type_select_action", array($this, 'sheet_chart_type_select' ));
      
      // Add ajax chart sheet selection capability
			add_action( "wp_ajax_{$this->prefix}_save_chart_action", array($this, 'save_chart' ));
      add_action( "wp_ajax_nopriv_{$this->prefix}_save_chart_action", array($this, 'save_chart' ));
      
      // Add new file
			add_action( "wp_ajax_{$this->prefix}_add_new_file_action", array($this, 'add_new_file' ));
      add_action( "wp_ajax_nopriv_{$this->prefix}_add_new_file_action", array($this, 'add_new_file' ));
      
       // Delete file
			add_action( "wp_ajax_{$this->prefix}_delete_file_action", array($this, 'delete_file' ));
			add_action( "wp_ajax_nopriv_{$this->prefix}_delete_file_action", array($this, 'delete_file' ));

			// add admin init action
			add_action('admin_init', array($this, 'admin_init'));


			// // Filters the “Thank you” text displayed in the Wordpress admin dashboard footer text
			add_filter( 'admin_footer_text', array($this, "admin_footer_text"));
			
			//  // Filter admin bar menu
			add_action( 'wp_before_admin_bar_render', array($this, 'admin_bar'));

      // // An action function used to include the reCAPTCHA JavaScript fil at the end of the page.
			add_action('wp_print_footer_scripts', array($this, 'add_captcha_js_to_footer'));

			// // Hide admin bar for non admins
			add_filter('show_admin_bar', function() {
        return (!current_user_can('manage_options')) ? false : true;
      });    
      

    } // END __construct




    public function default_attributes() {
      return ['id' => null];
    }





		
		/**
		 * Shortcode render method
		 *
		 * @param array $atts
		 * @param string $page
		 * @return void
		 */
		public function render_shortcode($atts, $content, $tag) {
			

			$atts = shortcode_atts(
        $this->default_attributes(), // default array values
        $atts // array of values passed to the shortcode if any
      );

				// Get charts
				$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

				// Get chart from GET chart ID
				$saved_chart = isset($charts[$atts['id']])? $charts[$atts['id']] : [];

				if (empty($saved_chart)) {
					return;
				}
				
				//  echo "<pre>"; print_r($saved_chart); die;
        
				// Get spreadsheet
        $spreadsheet = $this->fetch_spreadsheet( $saved_chart['chartParams']['fileName'] );
        
        // Get selected sheet
				$sheet = $spreadsheet[$saved_chart['chartParams']['sheetId']];

				// Fetch plugin settings
				$settings =  (!empty(get_option("{$this->prefix}_settings")))? get_option("{$this->prefix}_settings") : array();

			
				$chart = $this->chart_options_admin_fields ($saved_chart, $sheet, $saved_chart['chartParams']['chartType'], $settings)[0];
				
				
				$chart['chartParams'] = $saved_chart['chartParams'];

        $chart['chartParams']["sheet"] 	= $sheet;
				
				$payload = 	array(
					"chart"  => $chart,
          // 'settings' => $settings
        );
        wp_localize_script( "{$this->plugin}-public", "{$this->prefix}_front_end_chart", $payload );

        // Return login template
			  return $this->get_template_html("shortcode", $atts);
				
				// Send chart options to js
			
			

		} // END render_shortcode
    

    public function field_attributes() {
      return [
				'id', 'title', 'cssClass', 'wrapperClass', 'tab', 'fieldType', 'fieldTitle',  'fieldSize', 'fieldMin', 'fieldMax', 'required', 'disabled', 'pattern', 'placeholder', 'option_none', 'value', 'field_options', 'textareaCols', 'textareaRows', 'hint', 'nullOption', 'dependentField', 'readOnly', 'fieldStep', 'dependents'
      ];
		}
	

		



	
		/**
		 * Called when the plugin is activated using register_activation_hook
		 *
		 * @return void
		 */
		public function activate() {

			// Store and/or update plugin options
			if (false === get_option("{$this->prefix}-plugin-options")) {
				add_option("{$this->prefix}-plugin-options", $this->plugin_options);
			} elseif (get_option("{$this->prefix}-plugin-options") != $this->plugin_options) {
				update_option("{$this->prefix}-plugin-options", $this->plugin_options);
			}

		} // END activate





		/**
		 * Called when the plugin is deactivated using register_deactivation_hook
		 *
		 * @return void
		 */
		public function deactivate() {

			//Flush rewrite rules on plugin activation. (woocoommerce endpoint permalink)
			flush_rewrite_rules();

		} // END deactivate




		/**
		 * Admin init tidbits.
		 *
		 * @return void
		 */
		public function admin_init() {

      //Limit access to the dashboard for everybody but administrators
      if (is_admin() && !current_user_can('manage_options') && !(defined('DOING_AJAX') && DOING_AJAX)) {
        wp_redirect(home_url());
        exit;
      }

      

		} // END init
    





		/**
		 * Renders page template
		 *
		 * @param string $template
		 * @param array $atts
		 * @return string page html
		 */
		public function get_template_html($template, $payload = array()) {

			ob_start();

			do_action("{$this->prefix}_before_{$template}");

			require "{$this->path}templates/{$template}.php";

			do_action("{$this->prefix}_after_{$template}");

			$html = ob_get_contents();
			ob_end_clean();

			return $html;

		} // END get_template_html





		/**
		 * Render input field based on type
		 *
		 * @param array $args
		 * @return void
		 */
		public function input_field_render($field) {

			// var_dump($field);die;


			 // Populate all field attributes
			 foreach ($this->field_attributes() as $attribute) {
				if (! isset($field[$attribute])) {
					$field[$attribute] = null;
				} 
			}

			// settings options
			$options = (get_option($field['section'])) ? get_option($field['section']) : array();

			// field value
			$field['value'] = (isset($options[$field['id']])) ? $this->sanitize_field_type($options[$field['id']], $field['fieldType']) : null;

			// show template if it exists
			if (file_exists($this->path . "templates/" . $field['fieldType'] . ".php")) {

				echo $this->get_template_html($field['fieldType'], $field);

			} else {

				_e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['fieldType'] . ".php does not exist</div>", $this->plugin);

			}

		} // END input_field_render





		/**
		 * Sanitizes input fields based on type
		 *
		 * @param string $value
		 * @param string $type
		 * @return void
		 */
		public function sanitize_field_type($value, $type) {

			if (strpos($type, 'text')) {
				return sanitize_text_field($value);
			} elseif (strpos($type, 'email')) { // check if text email
				return sanitize_text_email($value);
			} elseif (strpos($type, 'textarea')) { // check if text textarea
				return wp_kses(stripslashes_deep($value, wp_kses_allowed_html('post')));
			} else {
				return $value;
      }
      
		} // END sanitize_field_type





    	/**
		 * Register and Enque admin stylesheet and scripts.  Hooks into 
		 *
		 * @return void
		 */
		public function enqueue_scripts()	{

			// wp_enqueue_media();

			// wp_register_script("{$this->plugin}-media-uploader", "{$this->url}assets/src/media-uploader.js", ["jquery" ], false, true);
			// wp_enqueue_script("{$this->plugin}-media-uploader");

			

      //Register and Enque/Load Google Visualization API
			wp_register_script('google-visualization-api','https://www.gstatic.com/charts/loader.js');
			wp_enqueue_script( 'google-visualization-api' );

			// Add the color picker css file       
			// wp_enqueue_style( 'wp-color-picker' ); 

			// Enqueue Stylesheet
			wp_register_style("{$this->plugin}-public", $this->url . "assets/bundle/public.css", null, false, 'all');
			wp_enqueue_style("{$this->plugin}-public");

			// Register and Enqueue file upload Javascript and use wp_localize_script to pass data to the javascript handler
			wp_register_script("{$this->plugin}-public", "{$this->url}assets/bundle/public.js", null, false, true);
			wp_enqueue_script("{$this->plugin}-public");
			// wp_localize_script(
			// 	$this->plugin, //handle for the script
			// 	"{$this->prefix}_obj", //  The name of the variable which will contain the data (used in the ajax url)
			// 	array( // Data to be passed
			// 		"ajax_url" => admin_url("admin-ajax.php"),
			// 		"home_url" => home_url(),
			// 		"plugin" => $this->plugin,
      //     "prefix" => $this->prefix,
      //     'plugin_url' => add_query_arg(["page" => $this->plugin], admin_url('admin.php')),
			// 		// "file_upload_action"   => "{$this->prefix}_file_upload_action",
      //     // "file_upload_nonce"  => wp_create_nonce("{$this->prefix}__file_upload_nonce" ),
      //     "file_select_action"   => "{$this->prefix}_file_select_action",
			// 		"file_select_nonce"  => wp_create_nonce("{$this->prefix}__file_select_nonce" ),
			// 		"sheet_chart_type_select_action"   => "{$this->prefix}_sheet_chart_type_select_action",
      //     "sheet_chart_type_select_nonce"  => wp_create_nonce("{$this->prefix}__sheet_chart_type_select_nonce" ),
      //     "save_chart_action"   => "{$this->prefix}_save_chart_action",
      //     "save_chart_nonce"  => wp_create_nonce("{$this->prefix}__save_chart_nonce" ),
      //     "add_new_file_action"   => "{$this->prefix}_add_new_file_action",
      //     "add_new_file_nonce"  => wp_create_nonce("{$this->prefix}__add_new_file_nonce" ),
      //     "delete_file_action"   => "{$this->prefix}_delete_file_action",
			// 		"delete_file_nonce"  => wp_create_nonce("{$this->prefix}__delete_file_nonce" ),
			// 	)
      // );
      
      

		} // END enqueue_scripts

	
		/**
		 * Register and Enque admin stylesheet and scripts.  Hooks into 
		 *
		 * @return void
		 */
		public function admin_enqueue_scripts()	{

			wp_enqueue_media();

			// wp_register_script("{$this->plugin}-media-uploader", "{$this->url}assets/src/media-uploader.js", ["jquery" ], false, true);
			// wp_enqueue_script("{$this->plugin}-media-uploader");

			

      //Register and Enque/Load Google Visualization API
			wp_register_script('google-visualization-api','https://www.gstatic.com/charts/loader.js');
			wp_enqueue_script( 'google-visualization-api' );

			// Add the color picker css file       
			wp_enqueue_style( 'wp-color-picker' ); 

			// Enqueue Stylesheet
			wp_register_style($this->plugin, $this->url . "assets/bundle/admin.css", ["wp-color-picker"], false, 'all');
			wp_enqueue_style($this->plugin);


			// Register and Enqueue file upload Javascript and use wp_localize_script to pass data to the javascript handler
			wp_register_script("{$this->plugin}-admin", "{$this->url}assets/bundle/admin.js", ["jquery", "wp-color-picker" ], false, true);
			wp_enqueue_script("{$this->plugin}-admin");
			wp_localize_script(
				"{$this->plugin}-admin", //handle for the script
				"{$this->prefix}_obj", //  The name of the variable which will contain the data (used in the ajax url)
				array( // Data to be passed
					"ajax_url" => admin_url("admin-ajax.php"),
					"home_url" => home_url(),
					"plugin" => $this->plugin,
					"prefix" => $this->prefix,
					'url' => $this->url,
          'plugin_url' => add_query_arg(["page" => $this->plugin], admin_url('admin.php')),
					// "file_upload_action"   => "{$this->prefix}_file_upload_action",
          // "file_upload_nonce"  => wp_create_nonce("{$this->prefix}__file_upload_nonce" ),
          "file_select_action"   => "{$this->prefix}_file_select_action",
					"file_select_nonce"  => wp_create_nonce("{$this->prefix}__file_select_nonce" ),
					"sheet_chart_type_select_action"   => "{$this->prefix}_sheet_chart_type_select_action",
          "sheet_chart_type_select_nonce"  => wp_create_nonce("{$this->prefix}__sheet_chart_type_select_nonce" ),
          "save_chart_action"   => "{$this->prefix}_save_chart_action",
          "save_chart_nonce"  => wp_create_nonce("{$this->prefix}__save_chart_nonce" ),
          "add_new_file_action"   => "{$this->prefix}_add_new_file_action",
          "add_new_file_nonce"  => wp_create_nonce("{$this->prefix}__add_new_file_nonce" ),
          "delete_file_action"   => "{$this->prefix}_delete_file_action",
					"delete_file_nonce"  => wp_create_nonce("{$this->prefix}__delete_file_nonce" ),
				)
      );
      
      

		} // END enqueue_scripts






		

		/**
		* formats spreadsheet
		* @author  Abbas Lamouri
		* @param   string $this->upload_path.$filename (spreadsheet file name)
		* @return  array $data (both raw and formatted Spreadsheet cols and rows data)
		* @version  0.1
		*/
		public function fetch_spreadsheet($filename) {

			$errors = new \WP_Error();

			$spreadsheet = [];

			// Check if the file is already in the upload directory
			if ( ! file_exists ($this->upload_path.$filename)) {
				$message = "File <strong>$this->upload_path.$filename</strong> does not exist.";
				$errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
				return $errors;
			}
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($this->upload_path.$filename);

			// Create a new Reader of the type that has been identified
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

			// Advise the Reader that we only want to load cell data (no fprmating)
			$reader->setReadDataOnly(true);

			// Load $input_file_path to a input_file Object
			$input_file = $reader->load($this->upload_path.$filename);

			// Identify all sheets by name in the spreasheet
			$sheet_names = $input_file->getSheetNames();

			// Loop through all sheets
			foreach ($sheet_names as $sheet_key => $sheet_name) {
				
				// Convert data in each input_file to array
				$raw_data = $input_file->getSheetByName($sheet_name)->toArray(
					NULL,        // Value that should be returned for empty cells
					TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
					TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
					TRUE         // Should the array be indexed by cell row and cell column
				);
				
				// Eliminate all empty columns and rows
				// Columns are considred empty if a data type is not supplied
				// Rows are considered empty if all the columns in the row are null;
				$data = [];
				foreach ($raw_data as $row_index => $row) {
					$new_row = [];
					foreach ($row as $col_index => $cell ) {
						if (isset($raw_data[2][$col_index]) && $raw_data[2][$col_index])
							$new_row[$col_index]= $cell;
					
					}

					if (!empty($new_row)) {
						$data[$row_index] = $new_row;
					}
				}

				// Retreive labels
				$labels = !empty($data) ? array_shift($data) : [];

				// Retreive raw_data type (second row
				$data_types = !empty($data) ? array_shift($data) : [];

				// extract roles if first element of first row is domain
				$roles = 	array_values($data[0])[0] === "domain" ? array_shift($data) : [];

				// Validate datatypes
				foreach($data_types as $col_key => $col_data_type ) {
					if (! isset($col_data_type) || $col_data_type === null || ! in_array($col_data_type, $this->data_types)) {
						$message = "File: <strong>{$filename}</strong> Sheet <strong>{$sheet_name}</strong> column <strong>{$labels[$col_key]}</strong> data type <strong>{$col_data_type}</strong> is not a valid data type.  Allowed data types are  <strong>".implode(", ", $this->data_types)."</strong>This file was not uploaded.";
						$errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
						return $errors;
						break 2;
					}
				}

				// Validate roles if any
				foreach($roles as $col_key => $role ) {
					if (! isset($role) || $role === null || ! in_array($role, $this->role_types)) {
						$message = "File: <strong>". sanitize_file_name($filename) . "</strong> contains an invalid role ( Sheet: <strong>{$sheet_name}</strong>,  column: <strong>{$labels[$col_key]}</strong>,  role: <strong>{$role}</strong> ).  Allowed roles are  <strong>".implode(", ", $this->role_types)."</strong>.";
						$errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
						return $errors;
						break 2;
					}
				}

				// Validate data (check for data mismatch)
				$data_cols = [];
				$i=0;
				foreach($data as $row_key => $row) {
					$j=0;
					foreach($row as $col_key => $col)  {
						$data_cols[$col_key][$i] = array_values($row)[$j];
						$j++ ;
					}
					$i++;
				}

				$i=1;
				foreach($data_cols as $col_key => $col) {
					while ($i < count($col)) {
						if ((is_numeric($col[$i]) && ! is_numeric($col[$i-1])) || (is_numeric($col[$i-1]) && ! is_numeric($col[$i])) || (is_string($col[$i]) && ! is_string($col[$i-1])) || (is_string($col[$i-1]) && ! is_string($col[$i]))) {
							$row = !empty($roles) ? $i + 4 : $i + 3;
							$message = "File: <strong>". sanitize_file_name($filename) . "</strong> contains invalid data ( Sheet: <strong>{$sheet_name}</strong>,  column: <strong>{$labels[$col_key]}</strong>,  row: <strong>{$row}</strong> ). All entries in the same olumn must have the same datat type";
							$errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
							return $errors;
							break 2;
						}
						$i++;
					}
				}

				$spreadsheet[$sheet_key]["data"] = $data;
				$spreadsheet[$sheet_key]["sheetName"]  = $sheet_name;
				$spreadsheet[$sheet_key]["labels"] = $labels;
				$spreadsheet[$sheet_key]["dataTypes"]  = $data_types;
				if (isset($roles) && !empty($roles)) {
					$spreadsheet[$sheet_key]["roles"]  = $roles;
				}
				
			}

			return $spreadsheet;

		} // END fetch_spreadsheet






		/**
		 * Filter admin footer text
		 *
		 * @return "admin footer text"
		 */
		public function admin_footer_text() {

			return  sprintf( __( 'Let us know if you like this Plugin.   Want the real thing? <a href="%s" title="Click here to purchase a license!" target="_blank">Click here to purchase a license!</a>', $this->plugin ), 'http://abbaslamouri.com' );

		} // END admin_footer_text





		 /**
    * Add plugin links to admin bar
    *
    * @param array $wp_admin_bar:  admin bar menu
    *
    */
		function admin_bar() {

      global $wp_admin_bar;

      //Add a link called for the plugin
      $wp_admin_bar->add_menu( array(
        'id'    => 'yrl_wp_igv',
        'title' => 'Interactive Google Visualization',
        'href'  => add_query_arg( array('page' => 'yrl_wp_igv'), admin_url('admin.php')),
      ));

		} // END admin_bar
		




    /**
     * Ajax file select handler
     *
     * @return void
     */
		public function file_select() {

			// wp_send_json($_POST);

			try{

				// Validate request
				if ( ! isset($_POST["action"]) || $_POST["action"] !== "{$this->prefix}_file_select_action" || !wp_verify_nonce($_POST['nonce'], "{$this->prefix}__file_select_nonce")) {
					throw new \Exception(  __(wp_kses_post('Invalid request'), $this->plugin));
				}	

				// Get file name and extension
				$filename = wp_basename($_POST['yrl_wp_igv__chartParams']['fileUpload']);

				// Check file type
				if ( !in_array(wp_check_filetype( $filename )['ext'], $this->file_types )) {
					throw new \Exception(  __(wp_kses_post('Invalid file type.  Only excel spreadsheets are allowed'), $this->plugin));
				}
			
				// process spreadsheet
				$spreadsheet = $this->fetch_spreadsheet($filename);
				if ( is_wp_error($spreadsheet)) {
					$message = array_combine($spreadsheet->get_error_codes(), $spreadsheet->get_error_messages())['error'];
					throw new \Exception ( __($message, $this->plugin));
				}
        
        // Fetch file selection foem and sheet selection form
				// $sheet_id_options = $this->sheet_id_options($spreadsheet);

				// Compose response
				$response = array(
					"status" => 'success',
					// "sheetIdOptions" => $sheet_id_options,
					'spreadsheet' => $spreadsheet,
					"message" => null,
				);

				// return ajax data
				wp_send_json($response); wp_die();
			
			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

				$response = [
					"status" => 'error',
					'message' => $message
				];
					// return ajax data
				wp_send_json($response, 200); wp_die();
			}

		}  // END public function contact_form_process() {





			

			/**
		* File upload ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
    */
    
    /**
     * Sets all parameters required to draw chart
     *
     * @return void
     */
		public function sheet_chart_type_select() {

			//  wp_send_json($_POST);
	
			try {
				
				if ( ! isset($_POST["action"]) || $_POST["action"] !== "{$this->prefix}_sheet_chart_type_select_action" || !wp_verify_nonce($_POST['nonce'], "{$this->prefix}__sheet_chart_type_select_nonce")) {
					throw new \Exception(  __(wp_kses_post('Invalid request'), $this->plugin));
				}
 
				// verify if a file was selected (file id submitted)
				if (! isset($_POST['yrl_wp_igv__chartParams']['fileUpload']) || ! isset($_POST['yrl_wp_igv__chartParams']['sheetId']) || ! isset ($_POST['yrl_wp_igv__chartParams']['chartType'])) { 
					 throw new \Exception (__("Please selecte a file, a sheet and a chart type.", $this->plugin));
				}

        // Convert chart linedashstyle to array if not null
				if ( isset($_POST['yrl_wp_igv__chartOptions']['lineDashStyle']) && $_POST['yrl_wp_igv__chartOptions']['lineDashStyle'] ) {
					$line_dash_style = explode(',', $_POST['yrl_wp_igv__chartOptions']['lineDashStyle']);
					array_walk($line_dash_style, function(&$element){$element = intval($element);});
					$_POST['yrl_wp_igv__chartOptions']['lineDashStyle'] = $line_dash_style;
				} else {
					$_POST['yrl_wp_igv__chartOptions']['lineDashStyle'] = null;;
				}
				 
				// Convert chart x-axis ticks to array if not null
				if ( isset($_POST['yrl_wp_igv__horAxisOptions']['hAxis']['ticks']) && $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['ticks'] ) {
					$axis_ticks = explode(',', $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['ticks']);
					array_walk($axis_ticks, function(&$element){$element = intval($element);});
					// wp_send_json($axis_ticks);
					$_POST['yrl_wp_igv__horAxisOptions']['hAxis']['ticks'] = $axis_ticks;
				 } else {
					$_POST['yrl_wp_igv__horAxisOptions']['hAxis']['ticks'] = null;
				 }
         
         // // Convert chart left y-axis ticks to array if not null
				if ( isset($_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0]['ticks'] ) && $_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0]['ticks'] ) {
					$axis_ticks = explode(',', $_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0]['ticks']);
					array_walk($axis_ticks, function(&$element){$element = intval($element);});
					// wp_send_json($axis_ticks);
					$_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0]['ticks'] = $axis_ticks;
				} else {
					$_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0]['ticks'] = null;
				}
         
        // Convert  chart right y-axis ticks to array if not null
				if ( isset($_POST['yrl_wp_igv__righttAxisOptions']['vAxes'][1]['ticks'] ) && $_POST['yrl_wp_igv__righttAxisOptions']['vAxes'][1]['ticks'] ) {
					$axis_ticks = explode(',', $_POST['yrl_wp_igv__righttAxisOptions']['vAxes'][1]['ticks']);
					array_walk($axis_ticks, function(&$element){$element = intval($element);});
					// wp_send_json($axis_ticks);
					$_POST['yrl_wp_igv__righttAxisOptions']['vAxes'][1]['ticks'] = $axis_ticks;
        } else {
					$_POST['yrl_wp_igv__righttAxisOptions']['vAxes'][1]['ticks'] = null;
				}

				// Reset haxis baseline if not set
				if ( ! isset($_POST['yrl_wp_igv__horAxisOptions']['hAxis']['baseline'] ) || ! $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['baseline'] ) {
					$_POST['yrl_wp_igv__horAxisOptions']['hAxis']['baseline'] = null;
				}

				// Reset haxis min value if not set
				if ( ! isset($_POST['yrl_wp_igv__horAxisOptions']['hAxis']['minValue'] ) || ! $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['minValue'] ) {
					$_POST['yrl_wp_igv__horAxisOptions']['hAxis']['minValue'] = null;
				}

				// Reset haxis max value if not set
				if ( ! isset($_POST['yrl_wp_igv__horAxisOptions']['hAxis']['maxValue'] ) || ! $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['maxValue'] ) {
					$_POST['yrl_wp_igv__horAxisOptions']['hAxis']['maxValue'] = null;
				}

				// Reset haxis viewWindow min value if not set
				if ( ! isset($_POST['yrl_wp_igv__horAxisOptions']['hAxis']['viewWindow']['min'] ) || ! $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['viewWindow']['min'] ) {
					$_POST['yrl_wp_igv__horAxisOptions']['hAxis']['viewWindow']['min'] = null;
				}

				// Reset haxis viewWindow max value if not set
				if ( ! isset($_POST['yrl_wp_igv__horAxisOptions']['hAxis']['viewWindow']['max'] ) || ! $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['viewWindow']['max'] ) {
					$_POST['yrl_wp_igv__horAxisOptions']['hAxis']['viewWindow']['max'] = null;
				}

				// Reset right axis baseline if not set
				if ( ! isset($_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['baseline'] ) || ! $_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['baseline'] ) {
					$_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['baseline'] = null;
				}

				// Reset left axis min value if not set
				if ( ! isset($_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['minValue'] ) || ! $_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['minValue'] ) {
					$_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['minValue'] = null;
				}

				// Reset left axis max value if not set
				if ( ! isset($_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['maxValue'] ) || ! $_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['maxValue'] ) {
					$_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['maxValue'] = null;
				}

				// Reset left axis viewWindow min value if not set
				if ( ! isset($_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['viewWindow']['min'] ) || ! $_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['viewWindow']['min'] ) {
					$_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['viewWindow']['min'] = null;
				}

				// Reset left axis viewWindow max value if not set
				if ( ! isset($_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['viewWindow']['max'] ) || ! $_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['viewWindow']['max'] ) {
					$_POST['yrl_wp_igv__leftAxisOptions']['vAxes']['0']['viewWindow']['max'] = null;
				}

				// Reset right axis baseline if not set
				if ( ! isset($_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['baseline'] ) || ! $_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['baseline'] ) {
					$_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['baseline'] = null;
				}

				// Reset right axis min value if not set
				if ( ! isset($_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['minValue'] ) || ! $_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['minValue'] ) {
					$_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['minValue'] = null;
				}

				// Reset right axis max value if not set
				if ( ! isset($_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['maxValue'] ) || ! $_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['maxValue'] ) {
					$_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['maxValue'] = null;
				}

				// Reset right axis viewWindow min value if not set
				if ( ! isset($_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['viewWindow']['min'] ) || ! $_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['viewWindow']['min'] ) {
					$_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['viewWindow']['min'] = null;
				}

				// Reset right axis viewWindow max value if not set
				if ( ! isset($_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['viewWindow']['max'] ) || ! $_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['viewWindow']['max'] ) {
					$_POST['yrl_wp_igv__rightAxisOptions']['vAxes']['1']['viewWindow']['max'] = null;
				}



        	// process spreadsheet
				$spreadsheet = $this->fetch_spreadsheet($_POST["yrl_wp_igv__chartParams"]["fileUpload"]);

				if ( is_wp_error($spreadsheet)) {
					$message = array_combine($spreadsheet->get_error_codes(), $spreadsheet->get_error_messages())['error'];
					throw new \Exception ( __($message, $this->plugin));
        }

        // Get selected sheet
        $sheet = $spreadsheet[$_POST['yrl_wp_igv__chartParams']['sheetId']];

        // Fetch plugin settings
        // $settings =  (!empty(get_option("{$this->prefix}_settings")))? get_option("{$this->prefix}_settings") : array();

        // Gather all chart options post variables
        $options = [];
        $options['chartParams'] = isset($_POST['yrl_wp_igv__chartParams']) ? $_POST['yrl_wp_igv__chartParams'] : [];
        $options['chartOptions'] = isset($_POST['yrl_wp_igv__chartOptions']) ? $_POST['yrl_wp_igv__chartOptions'] : [];
        $options['pieChartOptions'] = isset($_POST['yrl_wp_igv__pieChartOptions']) ? $_POST['yrl_wp_igv__pieChartOptions'] : [];
        $options['horAxisOptions']['hAxis'] = isset($_POST['yrl_wp_igv__horAxisOptions']['hAxis']) ? $_POST['yrl_wp_igv__horAxisOptions']['hAxis'] : [];
        $options['leftAxisOptions']['vAxes'][0] = isset($_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0]) ? $_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0] : [];
        $options['rightAxisOptions']['vAxes'][1] = isset($_POST['yrl_wp_igv__rightAxisOptions']['vAxes'][1]) ? $_POST['yrl_wp_igv__rightAxisOptions']['vAxes'][1] : [];
        $options['seriesOptions']['series'] = isset($_POST['yrl_wp_igv__seriesOptions']['series']) ? $_POST['yrl_wp_igv__seriesOptions']['series'] : [];
        $options['trendlinesOptions']['trendlines'] = isset($_POST['yrl_wp_igv__trendlinesOptions']['trendlines']) ? $_POST['yrl_wp_igv__trendlinesOptions']['trendlines'] : [];
        $options['numRangeOptions'] = isset($_POST['yrl_wp_igv__numRangeOptions']) ? $_POST['yrl_wp_igv__numRangeOptions'] : [];
        $options['chartRangeOptions'] = isset($_POST['yrl_wp_igv__chartRangeOptions']) ? $_POST['yrl_wp_igv__numRangeOptions'] : [];
        $options['minMaxTableChartOptions'] = isset($_POST['yrl_wp_igv__minMaxTableChartOptions']) ? $_POST['yrl_wp_igv__minMaxTableChartOptions'] : [];
        $options['tableChartOptions'] = isset($_POST['yrl_wp_igv__tableChartOptions']) ? $_POST['yrl_wp_igv__tableChartOptions'] : [];
        
        // Retreive admin field  and chart settings
        $chart_options_admin_fields = $this->chart_options_admin_fields($options, $sheet);
        if (is_wp_error($chart_options_admin_fields)) {
          $message = array_combine($chart_options_admin_fields->get_error_codes(), $chart_options_admin_fields->get_error_messages())['error'];
					throw new \Exception ( __($message, $this->plugin));
        } else {
          $chart =  $chart_options_admin_fields['chart'];
          $panels = $chart_options_admin_fields['panels'];
        }

        // Get admin field panels
        // $panels = $this->render_panels($admin_fields);
        
        // Get sheet select options
        // $sheet_id_options = $this->sheet_id_options($spreadsheet, $_POST['yrl_wp_igv__sheetId'] );
				        
				// Add success message
				$message = "<div class='notice notice-success is-dismissible'><p>Sheet Select successful</p></div>";
 
				// Compose response
			 	$response = array(
					'status'	 	 => 'success',
          "message" 	 => null,
          'chart'      => $chart,
          'panels'     => $panels,
          // 'sheet'      => $sheet,
          'spreadsheet' => $spreadsheet,
          // 'settings' => $settings,
          'post' => $_POST,
				);
	
				// return ajax data
				wp_send_json($response); wp_die();
 
			} catch (\Exception $e) {
 
				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

				// Compose response
				$response = array(
					'status'	 => 'error',
					"message" => $message,
				);
	
				// return ajax data
				wp_send_json($response); wp_die();

			}
			 
		}  // END public function contact_form_process() {





    

		/**
		* File upload ajax handler
		* @author  Abbas Lamouri
		* @param    null
		* @return
		* @version  0.1
		*/
		public function save_chart() {

        // wp_send_json(($_POST));

			try {
				
				if ( ! isset($_POST["action"]) || $_POST["action"] !== "{$this->prefix}_save_chart_action" || !wp_verify_nonce($_POST['nonce'], "{$this->prefix}__save_chart_nonce")) {
					throw new \Exception(  __(wp_kses_post('Invalid request'), $this->plugin));
        }
        
        // verify if a file was selected (file id submitted)
				if (! isset($_POST["yrl_wp_igv__fileUpload"]) || ! isset($_POST['yrl_wp_igv__sheetId']) || ! isset ($_POST['yrl_wp_igv__chartType'])) { 
          throw new \Exception (__("Please selecte a file, a sheet and a chart type.", $this->plugin));
				}
				
				// Verify if chart options are set
				if (! isset($_POST["yrl_wp_igv__chartOptions"])) { 
          throw new \Exception (__("Chart options missing.", $this->plugin));
				}

				// Verify if chart options are set
				if ( isset($_POST["yrl_wp_igv__chartType"]) && ($_POST["yrl_wp_igv__chartType"]) !== 'PieChart' && (! isset($_POST['yrl_wp_igv__horAxisOptions']) ||! isset ($_POST['yrl_wp_igv__leftAxisOptions']))) { 
          throw new \Exception (__("Axis options missing.", $this->plugin));
				}

        // There is a chart Id (edit)
        if (isset($_POST['yrl_wp_igv__chartId']) && $_POST['yrl_wp_igv__chartId'] ) {
					$chart_id = $_POST['yrl_wp_igv__chartId'];
					// Add success message
					// $message = "<div class='notice notice-success is-dismissible'><p>Chart (ID = {$chart_id} ) updated successfully</p></div>";
        } else {

           // Pick a random 5 digit number  ($i id used to interupt the process in case the random number search exceeds 1000000)
					$i = 1;
					$chart_id = rand ( 10000 , 99999 );
					if( ! empty( $this->charts)) {
						while(in_array($chart_id, array_keys($this->charts)) && $i < 1000000) {
							$chart_id = rand ( 10000 , 99999 );
							$i++;
						}
					}

					if ($i >= 999999999) {
						throw new \Exception ( __("Unable to come up with a 5 digit chart id after 999999999 tries.", $this->plugin));
					}

				}
				
				$chart = [];
				$chart['chartParams']['chartId'] = $chart_id;
				$chart['chartParams']['sheetId'] = $_POST['yrl_wp_igv__sheetId'];
        $chart['chartParams']['chartType'] = $_POST['yrl_wp_igv__chartType'];
				$chart['chartParams']["fileName"] 	= $_POST["yrl_wp_igv__fileUpload"];
				
        $chart['chartOptions'] = $_POST['yrl_wp_igv__chartOptions'];
        if (isset($chart['chartOptions']['lineDashStyle']) && $chart['chartOptions']['lineDashStyle']) {
          $chart['chartOptions']['lineDashStyle'] = explode(',', $_POST['yrl_wp_igv__chartOptions']['lineDashStyle']);
        }

        if ( isset($_POST['yrl_wp_igv__horAxisOptions'] ) ) {
          $chart['horAxisOptions'] = $_POST['yrl_wp_igv__horAxisOptions'];
          if (isset($chart['horAxisOptions']['hAxis']['ticks']) && $chart['horAxisOptions']['hAxis']['ticks'] ) {
            $chart['horAxisOptions']['hAxis']['ticks'] = explode(',', $_POST['yrl_wp_igv__horAxisOptions']['hAxis']['ticks']);
          }
        } else {
          $chart['horAxisOptions']['hAxis'] = [];
        }
        
        if (isset($_POST['yrl_wp_igv__leftAxisOptions'])) {
          $chart['leftAxisOptions'] = $_POST['yrl_wp_igv__leftAxisOptions'];
          if (isset($chart['leftAxisOptions']['vAxes'][0]['ticks']) && $chart['leftAxisOptions']['vAxes'][0]['ticks'] ) {
            $chart['leftAxisOptions']['vAxes'][0]['ticks'] = explode(',', $_POST['yrl_wp_igv__leftAxisOptions']['vAxes'][0]['ticks']);
          }
        } else {
          $chart['leftAxisOptions']['vAxes'][0] = [];
        }

        if ( isset($_POST['yrl_wp_igv__rightAxisOptions']) ) {
          $chart['rightAxisOptions'] = $_POST['yrl_wp_igv__rightAxisOptions'];
          if (isset($chart['rightAxisOptions']['vAxes'][0]['ticks']) && $chart['rightAxisOptions']['vAxes'][0]['ticks'] ) {
            $chart['rightAxisOptions']['vAxes'][1]['ticks'] = explode(',', $_POST['yrl_wp_igv__rightAxisOptions']['vAxes'][1]['ticks']);
          }
        } else {
          $chart['rightAxisOptions']['vAxes'][1]  = [];
        }
        
				$chart['pieChartOptions']= isset($_POST['yrl_wp_igv__pieChartOptions']) ? $_POST['yrl_wp_igv__pieChartOptions'] : [];
				$chart['seriesOptions'] = isset($_POST['yrl_wp_igv__seriesOptions']) ? $_POST['yrl_wp_igv__seriesOptions'] : [];
				$chart['trendlinesOptions'] = isset($_POST['yrl_wp_igv__trendlinesOptions']) ? $_POST['yrl_wp_igv__trendlinesOptions'] : [];
				$chart['numRangeOptions'] = isset($_POST['yrl_wp_igv__numRangeOptions']) ? $_POST['yrl_wp_igv__numRangeOptions'] : [];
				$chart['minMaxTableChartOptions'] = isset($_POST['yrl_wp_igv__minMaxTableChartOptions']) ? $_POST['yrl_wp_igv__minMaxTableChartOptions'] : [];
				
				// Add new chart to the charts array
				$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];
				$charts[$chart_id] = $chart;
        if (! update_option("{$this->prefix}_charts", $charts)) {
					throw new \Exception ( __("Option <strong>{$this->prefix}_charts update failed", $this->plugin));
				}
				
				// Add success message
				$status = 'success';
				$message = "<div class='notice notice-success is-dismissible'><p>Chart (ID = {$chart_id} ) saved successfully</p></div>";

			} catch (\Exception $e) {

				// Prepare error output
				$status = 'error';
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

			}

			// Compose response
			$response = array(
				'status'	 => $status,
        "message" => $message,
        'post' => $_POST
			);

			// return ajax data
			wp_send_json($response); wp_die();
			

		}  // END public function contact_form_process() {






    public function chart_options_admin_fields ($options, $sheet) {
      $errors = new \WP_Error();

      // Initialize admin Fields
      $panels = [];

      // Initialize chart
      $chart = [];

      // Set chart major axis type 
      $major_axis_type = array_values($sheet['dataTypes'])[0] === 'string' ? 'discrete' : 'continuous';

      if ($major_axis_type === 'discrete' && in_array($options['chartParams']['chartType'], $this->continuous_chart_types)) {
        $message = "This type of chart requires numeric values in the first column (domain column)";
        $errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
        return $errors;
      }

      // Set chart options and field
      $instance = new ParamOption( $options['chartParams']  );
      $chart['chartParams'] = $instance->options();
      $panels = array_merge($panels, $instance->fields());

      // Set chart options and field
      $chart['chartOptions'] = [];
      $instance = new ChartOption( $options['chartOptions'], $options['chartParams']['chartType']  );
      $chart['chartOptions'] =  $instance->options();
      $panels = array_merge($panels, $instance->fields());

      // Set pie chart options and fields
      if ( $options['chartParams']['chartType'] === 'PieChart') {
        $instance = new PieChartOption($options['pieChartOptions'] );
        $chart['chartOptions'] = array_merge($chart['chartOptions'], $instance->options());
        $panels = array_merge($panels, $instance->fields());
      }

      // Set horizontal axis options and fields
      if( $options['chartParams']['chartType'] !== 'PieChart') {
      
         // Set horizontal axis
         $axis_params = ['id' => 'horAxisOption' , 'axis' =>'[hAxis]', 'panelId' =>'horizontalAxis', 'title' => 'Horzontal Axis'];
         $instance = new AxisOption($options['horAxisOptions']['hAxis'], $axis_params, $major_axis_type, $sheet['labels']);
         $chart['chartOptions']['hAxis'] = $instance->options();
         $panels = array_merge($panels, $instance->fields( ));

         // Set left vertical axis
         $axis_params = ['id' => 'leftAxisOption' , 'axis' =>'[vAxes][0]', 'panelId' =>'leftAxis', 'title' => 'Left Axis'];
         $instance = new AxisOption($options['leftAxisOptions']['vAxes'][0], $axis_params, $major_axis_type, $sheet['labels']);
         $chart['chartOptions']['vAxes'][0] = $instance->options();
         $panels = array_merge($panels, $instance->fields( ));

         // Set right vertical axis
        if ( isset($options['chartParams']['enableSeries'])  && $options['chartParams']['enableSeries'] ) {
          $axis_params = ['id' => 'rightAxisOption' , 'axis' =>'[vAxes][1]', 'panelId' =>'rightAxis', 'title' => 'Right Axis'];
          $instance = new AxisOption($options['rightAxisOptions']['vAxes'][1], $axis_params, $major_axis_type, $sheet['labels']);
          $chart['chartOptions']['vAxes'][1] = $instance->options();
          $panels = array_merge($panels, $instance->fields( ));
        }
      }

      // Set series
      if ( isset($options['chartParams']['enableSeries']) && $options['chartParams']['enableSeries']) {
        $chart['chartOptions']['series'] = $this->fetch_series_trendlines($options['seriesOptions']['series'], 'series',  $sheet)['options'];
        $panels['series'] = $this->fetch_series_trendlines($options['seriesOptions']['series'], 'series',  $sheet)['panel'];
      }
       
       // Set trendlines ( continuous axis only)
      if ( isset($options['chartParams']['enableTrendlines']) &&  $options['chartParams']['enableTrendlines'] && $major_axis_type === 'continuous') {
        $chart['chartOptions']['trendlines'] = $this->fetch_series_trendlines($options['trendlinesOptions']['trendlines'], 'trendlines', $sheet )['options'];
        $panels['trendlines'] = $this->fetch_series_trendlines($options['trendlinesOptions']['trendlines'], 'trendlines', $sheet )['panel'];
			}
			
      $filter_columns = $this->set_num_range_filter_columns($sheet);
       
      // Include number range slider if enabled
      if (isset($options['chartParams']['enableNumRangeSlider']) && $options['chartParams']['enableNumRangeSlider'] ) {
        $instance = new NumRangeOption( $options['numRangeOptions'], $filter_columns['num_range_filter_columns']  );
        $chart['numRangeOptions'] = $instance->options();
        $panels = array_merge($panels, $instance->fields());
			}

			 // Include number range slider if enabled
       if (isset($options['chartParams']['enableChartRangeSlider']) && $options['chartParams']['enableChartRangeSlider'] ){
        if ($filter_columns['role_filter_columns'] ) {
          $instance = new ChartRangeOption( $options['chartRangeOptions'], $filter_columns['role_filter_columns']  );
          $chart['chartRangeOptions'] = $instance->options();
          $panels = array_merge($panels, $instance->fields());
        } else {
          $message = "Chart Range slider is not possible with type of chart data.";
          $errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
          return $errors;
        }
			}
			
       // Include minmax table chart if enabled
      if (isset($options['chartParams']['enableMinMaxTableChart']) && $options['chartParams']['enableMinMaxTableChart'] ) {
				$title = "Min Max Table";
				$intro = "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.";
        $instance = new TableChartOption( $options['minMaxTableChartOptions'], "minMaxTableChartOptions", 'minMaxTableChart', 'minMaxTableChartOption', $title, $intro );
        $chart['minMaxTableChartOptions'] = $instance->options();
        $panels = array_merge($panels, $instance->fields());
			}
			
			 // Include table chart if enabled
			 if (isset($options['chartParams']['enableTableChart']) && $options['chartParams']['enableTableChart'] ) {
				$title = "Table Chart";
				$intro = "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.";
        $instance = new TableChartOption( $options['tableChartOptions'], "tableChartOptions", 'tableChart', 'tableChartOption', $title, $intro );
        $chart['tableChartOptions'] = $instance->options();
        $panels = array_merge($panels, $instance->fields());
      }

       return ['chart' => $chart, 'panels' => $panels];
    }






      


    public function set_num_range_filter_columns($sheet) {

      if (!$sheet) return;
      $num_range_filter_columns = [];
      $role_filter_columns =[];
      $i=0;
      $j=0;
      foreach (array_values($sheet['dataTypes']) as $value) {
        if(isset($sheet['roles'])) {
          
          // Set filter columns
          if ((array_values($sheet['roles'])[$i] === 'data' && $value === 'number') || ( array_values($sheet['roles'])[$i] === 'domain' &&  $value === 'number')) {
            $num_range_filter_columns[$j]['colIndex'] = $i;
            $num_range_filter_columns[$j]['colLabel'] = array_values($sheet['labels'])[$i];
            $j++;
          }

        } else if ($value === 'number'){

          // Set filter columns
          $num_range_filter_columns[$j]['colIndex'] = $i;
          $num_range_filter_columns[$j]['colLabel'] = array_values($sheet['labels'])[$i];
          $j++;
        }
          $i++;
      }
      $j=0;
      foreach (array_values($sheet['dataTypes']) as $value) {
        if(isset($sheet['roles'])) {
          
          // Set Role filter columns
          if (array_values($sheet['roles'])[$i] === 'domain' && $value === 'number' ) {
            $role_filter_columns[$j]['colIndex'] = $i;
            $role_filter_columns[$j]['colLabel'] = array_values($sheet['labels'])[$i];
            $j++;
          }

        } 
        $i++;
      }

      if (empty($role_filter_columns) && array_values($sheet['dataTypes'])[0] === 'number') {
        $role_filter_columns[0]['colIndex'] = 0;
        $role_filter_columns[0]['colLabel'] = array_values($sheet['labels'])[0];
      } else {
        $role_filter_columns = null;
      }

      return ['num_range_filter_columns' => $num_range_filter_columns, 'role_filter_columns' => $role_filter_columns];
    }






    
    public function numRangeFilterColumns ($sheet, $column=null) {

      if (empty($sheet)) return;

      // wp_send_json($sheet);

      
      $sheet_columns_options = "<option value=''>Select Column</option> ";
      foreach ($sheet['labels'] as $key => $value) {
        $selected = ( $column == $key ) ? 'selected' : '';
        $sheet_columns_options .= "<option value='{$key}' {$selected}>{$value}</option>";
      }

      return $sheet_columns_options;

    }






    public function sheet_id_options ($spreadsheet, $sheet_id = null) {

      if (empty($spreadsheet)) return;
      
      $sheet_id_options = "<option value=''>Select Sheet</option> ";
      if (count($spreadsheet) === 1) {
        $sheet_id_options .= "<option value='".array_keys($spreadsheet)[0]."' selected>{$spreadsheet[0]['sheetName']}</option>";
      } else {
        foreach ($spreadsheet as $key => $value) {
          $selected = ($sheet_id !== null && intval($key) == intval($sheet_id)) ? 'selected' : '';
          $sheet_id_options .= "<option value='{$key}' {$selected}>{$value['sheetName']}</option>";
        }
      }

      return $sheet_id_options;

    }







   public function render_panels($panels) {

    ob_start();

    foreach($panels as $panel) :
      ?>  
      <div class="accordion__toggle">
        <div class="accordion__heading-title"><?php echo $panel["title"]; ?></div>
        <svg class ="accordion__svg">
          <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-keyboard_arrow_right"; ?>"></use>
        </svg>
      </div>
      <div class = "panel accordion__content accordion hidden" id = "<?php echo $panel['id'] ?>" >
        <div class="panel-intro">
          <?php echo (isset($panel["intro"]))? $panel["intro"] : ""; ?>
				</div>
        <?php $this->render_subpanels($panel['sections'], $panel['cssClass']) ?>
      </div><!-- .panel -->

    <?php endforeach;

    $html = ob_get_contents();
    ob_end_clean();
    return $html;

   }




   public function render_subpanels($subpanels, $panel_css_class) {

    
    foreach ( $subpanels as $section) : 

      ?>
          
      <?php if ($section['title']) :?>
        <div class="subpanel-heading accordion__toggle">
          <div class="accordion__heading">
            <?php echo isset($section["title"]) ? $section["title"] : ';' ?>
          </div>
          <svg class ="accordion__svg">
            <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-keyboard_arrow_right"; ?>"></use>
          </svg>
        </div>
      <?php endif; ?>
      <div class = "subpanel accordion__content <?php echo $section['title'] ? 'hidden' : '' ; ?>" id = "<?php echo $section['id'] ?>" >

        <?php $this->render_fields($section['fields'], $panel_css_class) ?>

      </div><!-- .subpanel -->

    <?php endforeach;
   }





   public function render_fields($section_fields, $panel_css_class) {

    foreach ( $section_fields as $row) :


      if  ( count($row) == 3) : 
        ?>
        <div class="field-group triple-field">
        <?php elseif ( ( count($row) == 2 ) ) : ?> 
        <div class="field-group dual-field">
      <?php else : ?> 
        <div class="field-group single-field">
          <?php endif;
					foreach($row as $field) :

            // Populate all field attributes
            foreach ($this->field_attributes() as $attribute) {
              if (! isset($field[$attribute])) {
                $field[$attribute] = null;
              } 
						}
						
						// show template if it exists
						if (file_exists($this->path . "templates/" . $field['fieldType'] . ".php")) : ?>
							<div class="form-group <?php echo $field['wrapperClass']; ?>">
								<?php if ($field['fieldTitle']) : ?>
									<label class="form-group__label" for="yrl_wp_igv__<?php echo $field['id']; ?>"><?php _e($field['fieldTitle'], $this->plugin);  ?></label>
								<?php endif; ?>
								
								<div class="form-group__field">
									<?php
									$field['cssClass'] = "{$field['cssClass']} {$panel_css_class} yrl_wp_igv__charts ";
									echo $this->get_template_html($field['fieldType'], $field);
									if (isset($field['fieldSuffix']) ) : ?>
										<span class = 'form-group__suffix'><?php echo $field['fieldSuffix']; ?></span>
                  <?php endif;
                  ?>
								</div>
                <?php if( $field['hint'] ): ?>
                    <div class='form-group__hint-alert'>hint ?</div>
										<span class="form-group__hint hidden"><?php echo __($field['hint'], $this->plugin); ?></span>
									<?php endif;?>
							</div>
						<?php else :
							_e("<div class = 'admin-errors'> Template " . $this->path . "templates/" . $field['fieldType'] . ".php does not exist</div>", $this->plugin);
						endif;

					endforeach;
						?>
				</div>
        <?php
    	endforeach; 
  
	 }
	 





	 

		public function chart_library() {

				// Initialize chart
				$chart = [];
				$panels = [];

			// Check if action is set
			if ( isset($_GET['action']) && $_GET['action'] === 'edit-chart' ) {


				if (! isset ($_GET['chartId'])) {
					$instance = new ParamOption([]);
					// $chart['chartParams'] = $instance->options();
					$panels = $instance->fields( );
					$action = 'editChart';
					
					// render template
					echo $this->get_template_html("edit-chart");
				} else {

					// Get charts
					$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

					// Get chart from GET chart ID
					$saved_chart = isset($charts[$_GET['chartId']])? $charts[$_GET['chartId']] : [];

					if (empty($saved_chart)) {
						return;
					}
					
					// echo "<pre>"; print_r($saved_chart); die;
					
					// Get spreadsheet
					$spreadsheet = $this->fetch_spreadsheet( $saved_chart['chartParams']['fileName'] );
					
					// Get selected sheet
					$sheet = $spreadsheet[$saved_chart['chartParams']['sheetId']];

					// Fetch plugin settings
					$settings =  (!empty(get_option("{$this->prefix}_settings")))? get_option("{$this->prefix}_settings") : array();

				
					$chart = $this->chart_options_admin_fields ($saved_chart, $sheet, $saved_chart['chartParams']['chartType'], $settings)[0];
					$admin_fields = $this->chart_options_admin_fields ($saved_chart, $sheet, $saved_chart['chartParams']['chartType'], $settings)[1];
					
					$chart['chartParams'] = $saved_chart['chartParams'];

					$chart['chartParams']["sheet"] 	= $sheet;
				
					// Get sheet select filed options based on sheet ID
					$sheet_id_options = $this->sheet_id_options( $spreadsheet, [$saved_chart['chartParams']['sheetId']] );
					
					$payload = 	array(
						"chart"  => $chart,
						'sheetIdOptions' => isset($sheet_id_options) ? $sheet_id_options : [],
						'action' => $action,
						'settings' => $settings
					);

					// render panels
					echo $this->get_template_html("edit-chart", $admin_fields);

					// Send chart options to js
					wp_localize_script( "{$this->plugin}-admin", "{$this->prefix}_charts", $payload );

				}

				// Send chart options to js
				wp_localize_script( 
					"{$this->plugin}-admin",
					"{$this->prefix}_charts",
					[
						'action' => $action,
						// 'chart' => $chart,
						'panels' => $panels
					]
				);
				
			// If No $_GET['action']
			} else {
				// echo "<pre>";var_dump($this->charts);

				// if ( ! isset($_GET['tab']) || ( isset($_GET['tab']) && $_GET['tab'] ===  "{$this->prefix}_settings")) {

					// $charts  =  (!empty(get_option("{$this->prefix}_chart_options")))? get_option("{$this->prefix}_chart_options") : array();
					$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

					$payload = [];

					foreach ($charts as $chart_id => $chart) {

						$filename =  $chart['chartParams']['fileName'];
						$sheet_id =$chart['chartParams']['sheetId'];
						
						// process spreadsheet
						$spreadsheet = $this->fetch_spreadsheet($filename);

						// Get selected sheet
						$sheet = $spreadsheet[$sheet_id];
						$chart['chartParams']['sheet'] =$sheet;
				
						$payload[$chart_id] = $chart;

					}

					// $payload = $payload;
					$action = 'list-charts';

				// } 
				// elseif ( isset($_GET['tab']) && $_GET['tab'] ===  "{$this->prefix}_files") {

				// 	// Set payload & action
				// 	$payload = $this->files;
				// 	$action = 'list-files';

				// }

				// Display Template
				echo $this->get_template_html("admin", $payload);

				// Send chart options to js
				wp_localize_script( "{$this->plugin}-admin", "{$this->prefix}_charts",  ['action' => $action, 'payload' => $payload ]);

			}

			// // Send chart options to js
			// wp_localize_script( 
			//   $this->plugin,  
			//   "{$this->prefix}_charts",    
			//   array(
			//      "payload"  => $payload,
			//      'sheetIdOptions' => isset($sheet_id_options) ? $sheet_id_options : [],
			// 		 'action' => $action,
			// 		 'other'=> isset($other) ? $other : null
			//   )
			// );

	
				 
	}




	public function fetch_series_trendlines($options, $target, $sheet) {

    $errors = new \WP_Error();

		if (! $sheet || ! $target ) {
      $message = "A sheet and a target are required to compile series and trendlines.";
      $errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
      return $errors;
    }

		$id = $target === 'series' ?  'series' : 'trendlines';
    $title = $target === 'series' ?  'Series' : 'Trendlines';
    
	
		// Initialize series panels and options
		$panel = [
			"id" => "yrl_wp_igv__{$target}Panel",
			'cssClass' => $id === 'series' ? 'seriesOption' : 'trendlinesOption',
			"title" => __("{$title}", $this->plugin),
			'sections' => [
      ]
    ];
    

    $target_options = [];

		// Populate series panels and options
    $i=0;
    $j=0;
    $labels = [];
    $series_options = [];
		while ($i < count($sheet['labels'])) {
      if ( array_values($sheet['dataTypes'])[$i] === 'number' && ( ! isset($sheet['roles']) || (isset($sheet['roles']) && array_values($sheet['roles'])[$i] === 'data'))) {
        $labels[$j] = array_values($sheet['labels'])[$i];

        $instance = $target == 'series' ? new SeriesOption($options, $j, $labels) : new TrendlinesOption($options, $j, $labels);

        $series_options[$j] = $instance->options();
        $seed = $instance->seed();
        $panel['sections']["{$id}-{$j}"]['id'] = "{$id}-{$i}";
        $panel['sections']["{$id}-{$j}"]['title'] = count($sheet['labels']) > 1 ? __(array_values($sheet['labels'])[$i], $this->plugin) : "";
        $panel['sections']["{$id}-{$j}"]['fields'] = $seed;
        $j++;
      }
			$i++;
			
    };
    
		return ['panel' => $panel, 'options' => $series_options];

  }
  


		


		/**
		 * Calls Admin class to register admin menus, submenus, sections and fields
		 *
		 * @return void
		 */
		public function admin_menu_register() {

			// If no admin return
			if (!is_admin() || !current_user_can('manage_options') || empty($this->admin_menus())) return;
			
			//add menu page
			foreach ($this->admin_menus() as $menu) {
				add_menu_page(
					$menu['page_title'], // page Title displayed in browser bar
					$menu['menu_title'], // menu title, which is displayed under admin menus
					$menu['caps'], // The capability required for this menu to be displayed to the user.
					$menu['id'], // menu id
					$menu['callback'], //array($this, $menu['callback']), // Callback function used to render the settings
					$menu['dashicon'], // icon url
					$menu['position']// menu position
				);
			}

			// If no submenu pages
			if (empty($this->admin_submenus())) return;
			
			//add submenu pages
			foreach ($this->admin_submenus() as $submenu) {
				add_submenu_page(
					$submenu['parent_id'], // Parent id
					$submenu['page_title'], // page title, which is displayed in the browser title bar
					$submenu['menu_title'], // menu title, which is displayed in the browser title bar
					$submenu['caps'], // The capability required for this page to be displayed to the user.
					$submenu['id'], // submenu id
					$submenu['callback']//array($this, $menu['callback']), // Callback function used to render the settings
				);
			}

			// If no admin sections
      if (empty($this->admin_sections())) return;

			// Register sections, section settings snd section options
			foreach ($this->admin_sections() as $section_id => $section) {

				if ( $section['registerSetting'] ) {
                
          // Register section  settings
          register_setting(
            $section_id,
            $section_id,
            array($this, 'pre_save_sanitize')
          );

          // Add  a settings section
          add_settings_section(
            $section_id, //id
            $section['title'], // Title
            $section['callback'], // callback to render section
            $section_id // id of admin submenu to which this section belongs
          );
        }

			}

			// If no admin sections
			if (empty($this->plugin_settings())) return;
			
			foreach ($this->plugin_settings() as $field_id => $field) {

				add_settings_field(
					$field_id, // id-name to identify the field. Used in the 'id' attribute of tags.
					$field['fieldTitle'], // Formatted title of the field. Shown as the label for the field during output.
					[$this, 'input_field_render'], //$field['callback'],   // callback to render the field
					$field['section'], // The id-name of the menu page on which to show the input field
					$field['section'], // The id-name of the section in which to show the box.
					array_merge($field, []) // $args:  Extra arguments used when outputting the 
				);
			}
		}





		
		/**
		 * Process Settings API fields before being saved by WP
		 *
		 * @param array $inputs
		 * @return void
		 */
		public function pre_save_sanitize($input){

			return $input;

		} // END pre_save_sanitize





		/**
		 * Admin menus
		 *
		 * @return void
		 */
		public function admin_menus() {

			return array(

				array(
					'page_title' => __('Interactive Google Visualization', $this->plugin), // Text to be displayed in the browser window.
					'menu_title' => __('Interactive Google Visualization', $this->plugin), // Text to be displayed for the menu
					'caps' => 'administrator', // The capability required for this page to be displayed to the user.
					'id' => "{$this->prefix}", // Unique id for this menu
					'callback' => function () {}, // Callback to output the menu (Handled by the first submenu in this case
					'dashicon' => 'dashicons-editor-customchar', // icon url
					'position' => '2', // menu position
				),

			);

    } // END admin_menus
    





	
		/**
		 * Admin submenus
		 *
		 * @return void
		 */
		public function admin_submenus() {

			return array(
				array(
					'parent_id' => $this->prefix, 
					'page_title' => __('Interactive WP Google Visualization', $this->plugin), 
					'menu_title' => __('Chart Library', $this->plugin), 
					'caps' => 'administrator', 
					'id' => $this->prefix, 
					'callback' => array($this, 'chart_library')
				),
				
				array(
					'parent_id' => $this->prefix, 
					'page_title' => __('Settings', $this->plugin), 
					'menu_title' => __('Settings', $this->plugin), 
					'caps' => 'administrator', 
					'id' => "{$this->prefix}_settings", 
					'callback' => function () {
            echo $this->get_template_html("chart-settings");
          }, 
        ),
        
        array(
					'parent_id' => $this->prefix, 
					'page_title' => __('Support', $this->plugin), 
					'menu_title' => __('Support', $this->plugin), 
					'caps' => 'administrator', 
					'id' => "{$this->prefix}_support", 
					'callback' => function () {
            echo $this->get_template_html("support");
          }, 
				),
			);

		} // END admin_submenus




		/**
		 * Admin tabs (sections)
		 *
		 * @return void
		 */
		public function admin_sections() {

			return array(

				"{$this->prefix}_charts" => array(
					'title' => __('', $this->plugin),
          'tabTitle' => __('Chart Library', $this->plugin),
          'registerSetting' => false,
					'callback' => function () { },
        ),

        "{$this->prefix}_settings" => array(
					'title' => __('', $this->plugin),
          'tabTitle' => __('Chart Settings', $this->plugin),
          'registerSetting' => true,
					'callback' => function () {echo "<h2>Chart Settings</h2>"; },
				),
        
        "{$this->prefix}_files" => array(
					'title' => __('', $this->plugin),
          'tabTitle' => __('Files', $this->plugin),
          'registerSetting' => false,
					// 'submenu' => "{$this->prefix}_charts",
					'callback' => function () {},
				),

				"{$this->prefix}_other" => array(
					'title' => __('', $this->plugin),
          'tabTitle' => __('Other', $this->plugin),
          'registerSetting' => false,
					'callback' => function () {},
				),
				

			);

		} // END admin_tabs



		public function plugin_settings() {
			return [
        [
					"id" => "enableTableChart",
					"section" => 	"{$this->prefix}_settings",
					"fieldTitle" => __("Enable Table Chart", $this->plugin),
					"fieldType" => "settings-checkbox",
					"value" => false
        ],
        [
					"id" => "enableMinMaxTable",
					"section" => 	"{$this->prefix}_settings",
					"fieldTitle" => __("Min/Max/Average Table", $this->plugin),
					"fieldType" => "settings-checkbox",
					"value" => false
        ],
        [
					"id" => "includeMinMaxTableFirstCol",
					"section" => 	"{$this->prefix}_settings",
					"fieldTitle" => __("Include Min/Max/Ave Table First Column", $this->plugin),
					"fieldType" => "settings-checkbox",
					"value" => false
				],
				[
					"id" => "enableNumRangeSlider",
					"section" => 	"{$this->prefix}_settings",
					"fieldTitle" => __("Enable Number Range Slider", $this->plugin),
					"fieldType" => "settings-checkbox",
					"value" => false
        ], 
        [
					"id" => "enableSeries",
					"section" => 	"{$this->prefix}_settings",
					"fieldTitle" => __("Enable Series", $this->plugin),
					"fieldType" => "settings-checkbox",
					"value" => false
        ],  
        [
					"id" => "enableTrendlines",
					"section" => 	"{$this->prefix}_settings",
					"fieldTitle" => __("Enable Trendlines", $this->plugin),
					"fieldType" => "settings-checkbox",
					"value" => false
        ], 
        
			];
    }
    

	} // END Dashboard

}