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
		
	
		
    public $plugin_options = [        // This plugin options
			"version" => "1.0.0"
		];
		
		protected $file_types = [        	// Possible file types
			"xlsx", "Xlsx", "xls", "Xls", "csv", "Csv", "xlsm", "Xlsm"
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
			register_activation_hook($this->base, [$this, 'activate'] );
			register_deactivation_hook($this->base, [$this, 'deactivate'] );


			// Add admin Submenus, section and fields
      add_action('admin_menu', [$this, 'admin_menu_register'] );
		
			// Register styles and scripts
			add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
      add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

      // Add ajax chart file selection capability
			add_action( "wp_ajax_{$this->prefix}_file_select_action", [$this, 'file_select' ] );
			add_action( "wp_ajax_nopriv_{$this->prefix}_file_select_action", [$this, 'file_select'] );
      
      // Add ajax chart sheet selection capability
			add_action( "wp_ajax_{$this->prefix}_save_chart_action", [$this, 'save_chart' ] );
      add_action( "wp_ajax_nopriv_{$this->prefix}_save_chart_action", [$this, 'save_chart'] );

			// Add ajax chart delete
			add_action( "wp_ajax_{$this->prefix}_delete_chart_action", [$this, 'delete_chart'] );
			add_action( "wp_ajax_nopriv_{$this->prefix}_delete_chart_action", [$this, 'delete_chart'] );

			// add admin init action
			add_action('admin_init', [$this, 'admin_init'] );


			// // Filters the “Thank you” text displayed in the Wordpress admin dashboard footer text
			add_filter( 'admin_footer_text', [$this, "admin_footer_text"] );
			
			//  // Filter admin bar menu
			add_action( 'wp_before_admin_bar_render', [$this, 'admin_bar'] );

      // // An action function used to include the reCAPTCHA JavaScript fil at the end of the page.
			// add_action('wp_print_footer_scripts', [$this, 'add_captcha_js_to_footer'));

			// // Hide admin bar for non admins
			add_filter('show_admin_bar', function() {
        return (!current_user_can('manage_options')) ? false : true;
      });

			add_shortcode( "yrl_wp_igv__plotlyChart", [$this, 'render_shortcode'] );
      

    } // END __construct




    public function default_attributes() {
      return ['id' => null];
    }


    public function default_chart () {
      return [
        "fileUpload" => [], 
        "layout" => [
          "paper_bgcolor" => "#ffffff",
          "plot_bgcolor" => "#ffffff",
          "xaxis" => [
            "visible" => true,
            "autorange" => true,
            "title" => [
              "text" => 'Wavelength ( &#181;m )'
            ],
            "rangeslider" => [
              "visible" => true,
            ],
          ],
          "xaxis2" => [
            "visible" => true,
            "autorange" => true,
            "overlaying" => "x",
            "side" => "top",
						"rangeslider" => [
              "visible" => true,
            ],
          ],
          "yaxis" => [
            "visible" => true,
            "autorange" => true,
          ],
          "yaxis2" => [
            "visible" => true,
            "autorange" => true,
            "overlaying" => "y",
            "side" => "right",
          ]
        ],
        "config" => [
          "responsive" => true,
					"displayModeBar" => false,
					"displaylogo" => false
        ],
        "traces" => [],
        "minMaxAvgTable" => [
          "visible" => true,
          "type" => "table",
          "rounding" => null,
          "header" => [],
          "cells" => [],
          "layout" => [
            "height" => "200",
            "margin" => [
              "l" =>  20,
              "r" =>  20 ,
              "t" => 20 ,
              "b" =>  20 ,
               "pad" => 0 ,
              "autoexpand" => true,
            ],
          ],
          "config" => [
            "responsive" => true,
            "displayModeBar" => false,
            "displaylogo" => false
          ]
        ]
      ];
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


			try {

				// Bail if there is no id
        if ( !  $atts["id"] ) {
          throw new \Exception(  __(wp_kses_post( "A chart ID is required", $this->plugin ) ) );
        }

				// Get charts
				$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

				// Bail if there is no id
        if ( empty( $charts)) {
          throw new \Exception(  __(wp_kses_post( "We cannot find any charts", $this->plugin ) ) );
        }

				// Get chart from GET chart ID
				$chart = isset($charts[$atts['id']])? $charts[$atts['id']] : [];

				if ( empty( $chart ) ) {
					throw new \Exception(  __(wp_kses_post( "We cannot find a chart with this ID {$atts['id']}", $this->plugin ) ) );
				}

				// Fetch spreadsheet
				$spreadsheet = $this->fetch_spreadsheet( $chart['fileUpload']['fileId'] );

				// Bail if error ( fetch_spreadsheet( ) return an spreadsheet (array) or WP error)
			 	if (  is_wp_error( $spreadsheet ) ) {
          $message = array_combine($spreadsheet->get_error_codes(), $spreadsheet->get_error_messages())["error"];
          throw new \Exception(  __(wp_kses_post( $message, $this->plugin ) ) );
        } 

				
				$payload = [
					"chart"  => $chart,
					"spreadsheet"  => $spreadsheet,
					"prefix" => $this->prefix
				];

				// Prepare error output
				$response = ["status"	=> "success", "message" => "", "payload" => $payload];


			} catch (\Exception $e) {
  
				// Prepare error output
        $payload =[];
				$response = ["status"	=> "error", "message" => $e->getMessage(), "payload" => $payload];

			}
        
      wp_localize_script( "{$this->plugin}-public", "{$this->prefix}__plotlyChart", $response);

      // Return login template
			return $this->get_template_html("shortcode", $payload);			
			

		} // END render_shortcode
    

  


	
		/**
		 * Called when the plugin is activated using register_activation_hook
		 *
		 * @return void
		 */
		public function activate() {

			// Store and/or update plugin options
			if (false === get_option("{$this->plugin}-plugin-options")) {
				add_option("{$this->plugin}-plugin-options", $this->plugin_options);
			} elseif (get_option("{$this->plugin}-plugin-options") != $this->plugin_options) {
				update_option("{$this->plugin}-plugin-options", $this->plugin_options);
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
		 * Register and Enque admin stylesheet and scripts.  Hooks into 
		 *
		 * @return void
		 */
		public function enqueue_scripts()	{

			// wp_enqueue_media();

			// wp_register_script("{$this->plugin}-media-uploader", "{$this->url}assets/src/media-uploader.js", ["jquery" ], false, true);
			// wp_enqueue_script("{$this->plugin}-media-uploader");

			

      //Register and Enque/Load Google Visualization API
			// wp_register_script('google-visualization-api','https://www.gstatic.com/charts/loader.js');
			// wp_enqueue_script( 'google-visualization-api' );

			// Add the color picker css file       
			// wp_enqueue_style( 'wp-color-picker' ); 

			// Enqueue Stylesheet
			wp_register_style("{$this->plugin}-public", $this->url . "assets/bundle/public.css", null, false, 'all');
			wp_enqueue_style("{$this->plugin}-public");

			// Register and Enqueue file upload Javascript and use wp_localize_script to pass data to the javascript handler
			wp_register_script("{$this->plugin}-public", "{$this->url}assets/bundle/public.js", null, false, true);
			wp_enqueue_script("{$this->plugin}-public");
      

		} // END enqueue_scripts

	
		/**
		 * Register and Enque admin stylesheet and scripts.  Hooks into 
		 *
		 * @return void
		 */
		public function admin_enqueue_scripts()	{

			wp_enqueue_media();

			// Enqueue Stylesheet
			wp_register_style($this->plugin, $this->url . "assets/bundle/admin.css", [], false, 'all');
			wp_enqueue_style($this->plugin);


			// Register and Enqueue file upload Javascript and use wp_localize_script to pass data to the javascript handler
			wp_register_script("{$this->plugin}-admin", "{$this->url}assets/bundle/admin.js", [], false, true);
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
          "file_select_action"   => "{$this->prefix}_file_select_action",
					"file_select_nonce"  => wp_create_nonce("{$this->prefix}__file_select_nonce" ),
          "save_chart_action"   => "{$this->prefix}_save_chart_action",
          "save_chart_nonce"  => wp_create_nonce("{$this->prefix}__save_chart_nonce" ),
					"delete_chart_action"   => "{$this->prefix}_delete_chart_action",
          "delete_chart_nonce"  => wp_create_nonce("{$this->prefix}__delete_chart_nonce" ),
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
		public function fetch_spreadsheet( $file_id ) {

      // Check if a file Id is submitted
			if ( ! $file_id ) {
				return new \WP_Error ( 'error', __( wp_kses_post ( "A file ID is required." ), $this->plugin));
			}

      // Get file path from file Id
      $file_path = get_attached_file( $file_id );

      // Check if a file with the supplie Id exsts
			if ( ! $file_path ) {
				return new \WP_Error ( 'error', __( wp_kses_post ( "We cannot find a file with this ID <strong>{$file_id}</strong>." ), $this->plugin));
			}

			// Initialize spreadsheet
      $spreadsheet = [];

			// Check if the file is already in the upload directory
			if ( ! file_exists ($file_path)) {
				return new \WP_Error ( 'error', __( wp_kses_post ( "File <.>$file_path</.> does not exist." ), $this->plugin));
			}

      // Check file type
      if ( ! in_array(wp_check_filetype(wp_basename($file_path))["ext"], $this->file_types )) {
        throw new \Exception(  __(wp_kses_post("Invalid file type, <strong>".wp_basename($file_path)."</strong> is not a valid file type.  Only excel and csv spreadsheets are allowed"), $this->plugin));
      }
			
			// Identify input file type
			$file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);

			// Create a new Reader of the type that has been identified
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

			// Advise the Reader that we only want to load cell data (no fprmating)
			$reader->setReadDataOnly(true);

			// Load $input_file_path to a input_file Object
			$input_file = $reader->load($file_path);

			// Identify all sheets by name in the spreasheet
			$sheet_names = $input_file->getSheetNames();


			// Loop through all sheets
			foreach ($sheet_names as $sheet_key => $sheet_name) {
				
				// Convert data in each input_file to array
				$raw_data = $input_file->getSheetByName($sheet_name)->toArray(
					"",        // Value that should be returned for empty cells
					TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
					TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
					TRUE         // Should the array be indexed by cell row and cell column
				);
				// wp_send_json($raw_data);

				// Retreive labels
				$labels = !empty($raw_data) ? array_values( array_shift($raw_data) ) : [];

				// Filter out empty cells at the end of each column
				$filtered_data = [];
				foreach ($raw_data as $row_key => $row_values) {
					// wp_send_json($row_values["A"]);
					if ("" !== $row_values["A"]){
						$counter = 0;
						foreach ($row_values as $cell_key => $cell_value) {
							if ("" === $cell_value) {
								$counter++;
							}
						}

						if ($counter !== count($row_values)) {
							$filtered_data[] = array_values($row_values);
						}
					}
				}
				// wp_send_json($filtered_data);

				// Transpose data for plotly plot
				$transposed_data = array_map(null, ...$filtered_data);

				// Validate data
				// Check if the file is already in the upload directory
				if ( empty($labels) || empty($filtered_data)) {
					$message = "File <strong>{$file_path}</strong> contains invalid data (possible missing labels or empty columns).";
					$errors->add ( 'error', __( wp_kses_post ( $message ), $this->plugin));
					return $errors;
				}
		
				$spreadsheet[$sheet_key]["data"] = $transposed_data;
				$spreadsheet[$sheet_key]["sheetName"]  = $sheet_name;
				$spreadsheet[$sheet_key]["labels"] = $labels;	
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
			* Displays chart library or new chart menu or edited chart menu.
			*/

		public function chart_library() {

			// Fetch all chartss
			$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

			if ( ! isset($_GET['action']) ) { // If action is not set, display all charts

        // Initialize payload
        $payload = [];

        // Set template
        $template = "chart-library";

        try {

          // Assemble payload
          foreach ($charts as $chart_id => $chart) {
    
            // Set payload by chart Id
            $payload[$chart_id] = $chart;

            // Fetch spreadsheet
            $spreadsheet = ! is_wp_error( $this->fetch_spreadsheet( $chart['fileUpload']['fileId'] ) ) ? $this->fetch_spreadsheet( $chart['fileUpload']['fileId'] ) : null;

            // Bail if error ( fetch_spreadsheet( ) return an spreadsheet (array) or WP error)
            // if (  ! is_wp_error( $spreadsheet ) ) {
              // Fetch sheet
            $payload[$chart_id]['sheet'] = $spreadsheet[$chart['fileUpload']['sheetId']];
              // $message = array_combine($spreadsheet->get_error_codes(), $spreadsheet->get_error_messages())["error"];
              // throw new \Exception(  __(wp_kses_post( $message, $this->plugin ) ) );
            // } else {
              // $payload[$chart_id]['sheet'] = null;
            // }

            

          }

					$payload = array_reverse($payload);


          // Set payload and response
          $response = [ 'status' => "success", 'message' => null, 'action'	=> 'listCharts', 'charts' => $payload ];

        } catch (\Exception $e) {
  
          // Prepare error output
          $response = ["status"	 => "error", "message" => $e->getMessage()];

        }
       
			} else { // If action is set 

        // Set template
        $chart = [];
				$spreadsheet = null;

        try {

						$template = "edit-chart";
						$action = "editChart";

						$chart_id = ( isset( $_GET['chartId'] ) ) ? $_GET['chartId'] : null;

						if ( $chart_id  ) {
							if ( isset($charts[$chart_id] ) ) {

								$chart = $charts[$chart_id];

								// Fetch spreadsheet
								$spreadsheet = ! is_wp_error( $this->fetch_spreadsheet( $chart['fileUpload']['fileId'] ) ) ?  $this->fetch_spreadsheet($chart['fileUpload']['fileId']) : null;
							
							} else {
								throw new \Exception(  __(wp_kses_post("We cannot find a chart with ID = {$chart_id}"), $this->plugin ) );
							}

						} else {
              
              $chart = $this->default_chart();

            }



          // Set response
          $response = [ 'status' => "success", 'action' => $action, "chart" => $chart, 'spreadsheet' => $spreadsheet ];

        } catch (\Exception $e) {
  
          // Prepare error output
          $response = ["status"	 => "error", "message" => $e->getMessage()];

        }

        // Set payload
        $payload = ["chart" => $chart];

				
			}

      // Display Template
      echo $this->get_template_html($template, $payload);

      // Send js parameters
      wp_localize_script( 
        "{$this->plugin}-admin",
        "{$this->prefix}_charts", 
        $response
      );
				 
	  }











    /**
     * Ajax file select handler
     *
     * @return void
     */
		public function file_select() {

			// wp_send_json($_POST);

			try{

				// Validate request
				if ( ! isset($_POST["action"]) || $_POST["action"] !== "{$this->prefix}_file_select_action" || !wp_verify_nonce($_POST["nonce"], "{$this->prefix}__file_select_nonce")) {
					throw new \Exception(  __(wp_kses_post("Invalid request"), $this->plugin));
				}	

				// Fetch spreadsheet
				$spreadsheet = $this->fetch_spreadsheet(  $_POST["fileId"]  );

        // Bail if error ( fetch_spreadsheet( ) return an spreadsheet (array) or WP error)
        if (  is_wp_error( $spreadsheet ) ) {
          $message = array_combine($spreadsheet->get_error_codes(), $spreadsheet->get_error_messages())["error"];
          throw new \Exception(  __(wp_kses_post( $message, $this->plugin ) ) );
        } 

				// Compose response
				$response = array(
					"status" => "success",
					"spreadsheet" => $spreadsheet,
          "message" => null,
        );
			
			} catch (\Exception $e) {

				$response = [
					"status"  => "error",
          "message" => $e->getMessage(),
				];
				
			}

			// return ajax data
			wp_send_json($response);

		}  // END file_select() {





    
		/**
		 * Save Chart
		 *
		 * @return void
		 */
		public function save_chart() {
      
      // wp_send_json($_POST);

			try {
				
				if ( ! isset($_POST["action"]) || $_POST["action"] !== "{$this->prefix}_save_chart_action" || !wp_verify_nonce($_POST["nonce"], "{$this->prefix}__save_chart_nonce")) {
					throw new \Exception(  __(wp_kses_post("Invalid request"), $this->plugin));
        }
        
        // verify if a file, a sheet and a chart type were selected
				if ( ! isset( $_POST["{$this->prefix}__fileUpload"]["fileId"] ) || ! isset( $_POST["{$this->prefix}__fileUpload"]["fileName"] ) || ! isset( $_POST["{$this->prefix}__fileUpload"]["sheetId"] ) || ! isset ( $_POST["{$this->prefix}__fileUpload"]["chartType"] ) ) { 
          throw new \Exception (__("Please selecte a file, a sheet and a chart type.", $this->plugin));
				}
				
				// Verify if chart options are set
				if ( ! isset($_POST["{$this->prefix}__traces"]) && ! empty( $_POST["{$this->prefix}__traces"] ) ) { 
          throw new \Exception (__("Chart Traces missing.", $this->plugin));
				}

				// Verify if the axes are set
				// if ( $_POST["{$this->prefix}__fileUpload"]["chartType"] !== 'PieChart' && (! isset($_POST["{$this->prefix}__horAxisOptions"]) ||! isset ($_POST["{$this->prefix}__leftAxisOptions"]))) { 
        //   throw new \Exception (__("Axis options missing.", $this->plugin));
				// }
       	// wp_send_json($_POST);

				// Fetch all charts
				$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

			

        // There is a chart Id (edit)
        if (isset($_POST["{$this->prefix}__fileUpload"]["chartId"]) && $_POST["{$this->prefix}__fileUpload"]["chartId"]) {
					$chart_id = $_POST["{$this->prefix}__fileUpload"]["chartId"];
					      //  wp_send_json($_POST);

				// New chart
        } else {
					$last_chart = end( $charts );
					$chart_id = (  ! empty($charts) && isset( $last_chart ) ) ? $last_chart["fileUpload"]["chartId"] + 1 : 16327;
				}

				// Assemble chart
				$charts[$chart_id]["fileUpload"] = (  isset( $_POST["{$this->prefix}__fileUpload"] ) ) ?  $_POST["{$this->prefix}__fileUpload"] : [];
				$charts[$chart_id]["fileUpload"]["chartId"] = $chart_id;
				$charts[$chart_id]["layout"] = ( isset( $_POST["{$this->prefix}__layout"] ) ) ? $_POST["{$this->prefix}__layout"] : [];
				$charts[$chart_id]["traces"] = ( isset( $_POST["{$this->prefix}__traces"] ) ) ?  $_POST["{$this->prefix}__traces"] : [];
				$charts[$chart_id]["config"] = ( isset( $_POST["{$this->prefix}__config"] ) ) ?  $_POST["{$this->prefix}__config"] : [];
				// $charts[$chart_id]["tableChart"] = ( isset( $_POST["{$this->prefix}__tableChart"] ) ) ? $_POST["{$this->prefix}__tableChart"] : [];
				$charts[$chart_id]["minMaxAvgTableChart"] = ( isset( $_POST["{$this->prefix}__minMaxAvgTableChart"] ) ) ? $_POST["{$this->prefix}__minMaxAvgTableChart"] : [];


				// Convert xaxis array type fields to array
				if ( ! $charts[$chart_id]["layout"]["xaxis"]["range"] ) {
					$charts[$chart_id]["layout"]["xaxis"]["range"] = [];
				} else {
					$xaxis_range_arr = [];
					foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis"]["range"] ) as $value) {
						array_push($xaxis_range_arr, floatval($value));
					}
					$charts[$chart_id]["layout"]["xaxis"]["range"] = $xaxis_range_arr;
				}



				// if ( ! $charts[$chart_id]["layout"]["xaxis"]["tickvals"] ) {
				// 	$charts[$chart_id]["layout"]["xaxis"]["tickvals"] = [];
				// } else {
				// 	$xaxis_tickvals_arr = [];
				// 	foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis"]["tickvals"] ) as $value) {
				// 		array_push($xaxis_tickvals_arr, floatval($value));
				// 	}
				// 	$charts[$chart_id]["layout"]["xaxis"]["tickvals"] = $xaxis_tickvals_arr;
				// }

				// if ( ! $charts[$chart_id]["layout"]["xaxis"]["ticktext"] ) {
				// 	$charts[$chart_id]["layout"]["xaxis"]["ticktext"] = [];
				// } else {
				// 	$xaxis_ticktext_arr = [];
				// 	foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis"]["ticktext"] ) as $value) {
				// 		array_push($xaxis_ticktext_arr, floatval($value));
				// 	}
				// 	$charts[$chart_id]["layout"]["xaxis"]["ticktext"] = $xaxis_ticktext_arr;
				// }


        // Convert xaxis2 array type fields to array
        if ( ! $charts[$chart_id]["layout"]["xaxis2"]["range"] ) {
          $charts[$chart_id]["layout"]["xaxis2"]["range"] = [];
        } else {
          $xaxis2_range_arr = [];
          foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis2"]["range"] ) as $value) {
            array_push($xaxis2_range_arr, floatval($value));
          }
          $charts[$chart_id]["layout"]["xaxis2"]["range"] = $xaxis2_range_arr;
        }

        // if ( ! $charts[$chart_id]["layout"]["xaxis2"]["tickvals"] ) {
        //   $charts[$chart_id]["layout"]["xaxis2"]["tickvals"] = [];
        // } else {
        //   $xaxis2_tickvals_arr = [];
        //   foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis2"]["tickvals"] ) as $value) {
        //     array_push($xaxis2_tickvals_arr, floatval($value));
        //   }
        //   $charts[$chart_id]["layout"]["xaxis2"]["tickvals"] = $xaxis2_tickvals_arr;
        // }

        // if ( ! $charts[$chart_id]["layout"]["xaxis2"]["ticktext"] ) {
        //   $charts[$chart_id]["layout"]["xaxis2"]["ticktext"] = [];
        // } else {
        //   $xaxis2_ticktext_arr = [];
        //   foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis2"]["ticktext"] ) as $value) {
        //     array_push($xaxis2_ticktext_arr, floatval($value));
        //   }
        //   $charts[$chart_id]["layout"]["xaxis2"]["ticktext"] = $xaxis2_ticktext_arr;
        // }
	



        // add undefined checkbox values
        $charts[$chart_id]["layout"]["xaxis"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["xaxis"]["visible"] ) ?  true : false;
        $charts[$chart_id]["layout"]["xaxis"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["xaxis"]["autorange"] ) ?  true : false;
        $charts[$chart_id]["layout"]["xaxis"]["rangeslider"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["xaxis"]["rangeslider"]["visible"] ) ?  true : false;
        $charts[$chart_id]["layout"]["xaxis2"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["xaxis2"]["visible"] ) ?  true : false;
        $charts[$chart_id]["layout"]["xaxis2"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["xaxis2"]["autorange"] ) ?  true : false;
        $charts[$chart_id]["layout"]["xaxis2"]["rangeslider"][visible] = isset( $_POST["{$this->prefix}__layout"]["xaxis2"]["rangeslider"]["visible"] ) ?  true : false;
        $charts[$chart_id]["layout"]["yaxis"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["yaxis"]["visible"] ) ?  true : false;
        $charts[$chart_id]["layout"]["yaxis"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["yaxis"]["autorange"] ) ?  true : false;
        $charts[$chart_id]["layout"]["yaxis2"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["yaxis2"]["visible"] ) ?  true : false;
        $charts[$chart_id]["layout"]["yaxis2"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["yaxis2"]["autorange"] ) ?  true : false;
        $charts[$chart_id]["config"]["displayModeBar"] = isset( $_POST["{$this->prefix}__config"]["displayModeBar"] ) ?  true : false;
        $charts[$chart_id]["config"]["displaylogo"] = isset( $_POST["{$this->prefix}__config"]["displaylogo"] ) ?  true : false;

				// $layout = $_POST["{$this->prefix}__layout"];
				// $charts[$chart_id]["layout"]["config"]["responsive"] = ( isset( $layout["config"]["responsive"] ) ) ?  $layout["config"]["responsive"] : false;
        // $charts[$chart_id]["layout"]["config"]["displayModeBar"] = ( isset( $layout["config"]["displayModeBar"] ) ) ?  $layout["config"]["displayModeBar"] : false;
        // $charts[$chart_id]["layout"]["config"]["displaylogo"] = ( isset( $layout["config"]["displaylogo"] ) ) ?  $layout["config"]["displaylogo"] : false;
        // $charts[$chart_id]["layout"]["showlegend"] = ( isset( $layout["showlegend"] ) ) ?  $layout["showlegend"] : false;
        // $charts[$chart_id]["layout"]["showMinMaxAvgTable"] = ( isset( $layout["showMinMaxAvgTable"] ) ) ?  $layout["showMinMaxAvgTable"] : false;
        // $charts[$chart_id]["layout"]["showMinMaxAvgTable"] = ( isset( $layout["showMinMaxAvgTable"] ) ) ?  $layout["showMinMaxAvgTable"] : false;
        // $charts[$chart_id]["layout"]["autosize"] = ( isset( $layout["autosize"] ) ) ?  $layout["autosize"] : false;
        // $charts[$chart_id]["layout"]["hovermode"] = ( isset( $layout["hovermode"] ) ) ?  $layout["hovermode"] : false;
        // $charts[$chart_id]["layout"]["legend"]["itemclick"] = ( isset( $layout["legend"]["itemclick"] ) ) ?  $layout["legend"]["itemclick"] : false;
        // $charts[$chart_id]["layout"]["legend"]["itemdoubleclick"] = ( isset( $layout["legend"]["itemdoubleclick"] ) ) ?  $layout["legend"]["itemdoubleclick"] : false;
        // $charts[$chart_id]["layout"]["xaxis"]["automargin"] = ( isset( $layout["xaxis"]["automargin"] ) ) ?  $layout["xaxis"]["automargin"] : false;
        // $charts[$chart_id]["layout"]["xaxis"]["rangeslider"]["visible"] = ( isset( $layout["xaxis"]["rangeslider"]["visible"] ) ) ?  $layout["xaxis"]["rangeslider"]["visible"] : false;
        // $charts[$chart_id]["layout"]["yaxis"]["fixedrange"] = ( isset( $layout["yaxis"]["fixedrange"] ) ) ?  $layout["yaxis"]["fixedrange"] : false;
        // $charts[$chart_id]["layout"]["margin"]["autoexpand"] = ( isset( $layout["margin"]["autoexpand"] ) ) ?  $layout["margin"]["autoexpand"] : false;
        
        // $charts[$chart_id]["layout"]["showlegend"] = ( isset( $layout["showlegend"] ) ) ?  $layout["showlegend"] : false;
        
        // $charts[$chart_id]["layout"]["xaxis"]["rangeslider"]["visible"] = ( isset( $layout["xaxis"]["rangeslider"]["visible"] ) ) ?  $layout["xaxis"]["rangeslider"]["visible"] : false;	

					// $charts[$chart_id]["minMaxAvgTableChart"]["options"]["layout"]["autosize"] = ( isset( $_POST["{$this->prefix}__minMaxAvgTableChart"]["layout"]["autosize"] ) ) ? true : false;
       
	
        if (! update_option("{$this->prefix}_charts", $charts)) {
					// throw new \Exception ( __("Option <strong>{$this->prefix}_charts update failed", $this->plugin));
          $status = "unchanged";
				} else {
          $status = "success";
        }

				// Compose response
				$response = array(
					"status" => $status,
					"message" => "Chart saved successfully",
					"chartId" => $chart_id
				);


			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

				$response = [
					"status"  => "error",
          "chartId" => $chart_id,
					"message" => $message
				];

			}

			// return ajax data
			wp_send_json($response);
			

		}  // END public function contact_form_process() {





		
    
		/**
		 * Save Chart
		 *
		 * @return void
		 */
		public function delete_chart() {

        // wp_send_json($_POST);

			try {
				
				if ( ! isset($_POST["action"]) || $_POST["action"] !== "{$this->prefix}_delete_chart_action" || !wp_verify_nonce($_POST["nonce"], "{$this->prefix}__delete_chart_nonce")) {
					throw new \Exception(  __(wp_kses_post("Invalid request"), $this->plugin));
        }
				
				// Verify if chart options are set
				if (  ! isset( $_POST["chartId"] ) )  throw new \Exception (__("Chart ID missing.", $this->plugin));

				// Fetch all charts
				$charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];
				unset($charts[$_POST["chartId"]]);
	
        if (! update_option("{$this->prefix}_charts", $charts)) {
					throw new \Exception ( __("Option <strong>{$this->prefix}_charts update failed", $this->plugin));
				}

				// Compose response
				$response = array(
					"status" => "success",
					"message" => "Chart deleted successfully",
					"chartId" => $_POST["chartId"],
					"charts" => $charts
				);


			} catch (\Exception $e) {

				// Prepare error output
				$message = "<div class='notice notice-error is-dismissible'><p>{$e->getMessage()}</p></div>";

				$response = [
					"status"  => "error",
					"message" => $message
				];

			}

			// return ajax data
			wp_send_json($response);
			

		}  // END public function delete_chart() 






		
		/**
		 * Register admin menus, submenus, sections and fields
		 *
		 * @return void
		 */
		public function admin_menu_register() {

			// Bail if register user does not have adeqaute permission
			if (!is_admin() || !current_user_can('manage_options') ) return;

			// If no admin menus
			if (empty($this->admin_menus())) return;
			
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


			// If no admin submenu pages
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

		}



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
          'sectionTitle' => __('Chart Library', $this->plugin),
          'registerSetting' => false,
					'callback' => function () { echo "Charts";},
        ),

        "{$this->prefix}_settings" => array(
					'title' => __('', $this->plugin),
          'sectionTitle' => __('Chart Settings', $this->plugin),
          'registerSetting' => true,
					'callback' => function () {echo "<h2>Chart Settings</h2>"; },
				),
        
        "{$this->prefix}_files" => array(
					'title' => __('', $this->plugin),
          'sectionTitle' => __('Files', $this->plugin),
          'registerSetting' => false,
					// 'submenu' => "{$this->prefix}_charts",
					'callback' => function () {  echo "Files";},
				),

				"{$this->prefix}_other" => array(
					'title' => __('', $this->plugin),
          'sectionTitle' => __('Other', $this->plugin),
          'registerSetting' => false,
					'callback' => function () { echo "Other";},
				),
				

			);

		} // END admin_tabs



		
		




    

	} // END Dashboard

}