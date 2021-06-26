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
			"version" => "1.0.0",
			"rest_version" => "1"
		];

		public $rest_namespace; // Rest API namespace
		public $rest_base; // Rest API base name
		
		protected $file_types = [        	// Possible file types
			"xlsx", "Xlsx", "xls", "Xls", "csv", "Csv", "xlsm", "Xlsm"
		];

		// protected $font_family = [
		// 	// "" => "Select Font Family",
		// 	"arial" => "Arial",
		// 	"balto" => "Balto",
		// 	"Times New Roman" => "Times New Roman",
		// 	"Courier New" => "Courier New",
		// 	"Droid Sans" => "Droid Sans",
		// 	"Droid Serif" => "Droid Serif",
		// 	"Droid Sans Mono" => "Droid Sans Mono",
		// 	"Gravitas One" => "Gravitas One",
		// 	"Old Standard TT" => " Old Standard TT",
		// 	"Open Sans" => "Open Sans",
		// 	"overpass" => "Overpass",
		// 	"PT Sans Narrow" => "PT Sans Narrow",
		// 	"raleway" => "Raleway"
		// ];

		// protected $colors = [

		// 	"3366cc", "dc3912", "ff9900", "109618", "0099c6", "dd4477", "66aa00", "b82e2e", "316395", "#b71c1c", "#0d47a1", "#7B1FA2", "#e65100", "#581845", "#795548", "#0097A7", "#558B2F", "#1976D2", "#212121", "#00796B", "#455A64", "#263238", "#303F9F", "#33691E", "#EF6C00", "#FFA000", "#004d40"
			
		// ];


		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct() {

			$this->rest_namespace = "{$this->plugin}/v{$this->plugin_options['rest_version']}";
      $this->rest_base = 'charts';

			// Declare upload directory
			$this->upload_path = wp_upload_dir()['path']."/";

      // $this->charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

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
			// add_action( "wp_ajax_{$this->prefix}_save_chart_action", [$this, 'save_chart' ] );
      // add_action( "wp_ajax_nopriv_{$this->prefix}_save_chart_action", [$this, 'save_chart'] );

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


			// Rest API Settings
			add_action('rest_api_init', array($this, "register_rest_api_routes"));
      

    } // END __construct




    public function default_attributes() {
      return ['id' => null];
    }



    public function fetch_payload() {


      $charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];


      // Initialize payload
      $payload = [];

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

			}

      // return array_reverse( $payload );
      return $payload;

    }



		public function register_rest_api_routes() {

      // *************************** Dashboard
					register_rest_route( $this->rest_namespace, "/{$this->rest_base}/(?P<id>\d+)",
          array(
            'methods' => "GET",
            'callback' => [$this, "fetch_chart_spreadsheet"],
            'args' => array(),
            'permission_callback' => array($this, "select_file_permissions_check"),
          )
        );


					// *************************** Dashboard
					register_rest_route( $this->rest_namespace, "/{$this->rest_base}",
						array(
							'methods' => "POST",
							'callback' => [$this, "save_chart"],
							'args' => array(),
							'permission_callback' => array($this, "create_chart_permissions_check"),
						)
					);

		}



    public function fetch_chart_spreadsheet( $request ) {
      
			return $this->fetch_spreadsheet( $request->get_params()["id"] );

    }





    public function save_chart( $request ) {

      // return json_decode( $request->get_body(), true );

      // $charts = get_option( "{$this->prefix}_charts") ? get_option("{$this->prefix}_charts" ) : [];
      // $chart = json_decode( $request->get_body(), true );
      
      // if ( isset($chart["fileUpload"]["chartId"]) ) { // There is a chart Id (edit)
      //   $chart_id = $chart["fileUpload"]["chartId"];

      // } else { // New chart
      //   $last_chart = end( $charts );
      //   $chart_id = (  ! empty($charts) && isset( $last_chart ) ) ? $last_chart["fileUpload"]["chartId"] + 1 : 16327;
      // }


      // $chart["fileUpload"]["chartId"] = $chart_id;

      // array_push ($charts, $chart);

      return update_option( "{$this->prefix}_charts", json_decode( $request->get_body(), true ) );
      
			// return $chart_id;

    }




		public function select_file_permissions_check() {

			if ( ! current_user_can( 'manage_options' ) ) 
			return new \WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this content.', $this->plugin), array('status' => 401));
			
			return true;

		} // END 	public function permissions_check() {



		public function create_chart_permissions_check() {

			if ( ! current_user_can( 'manage_options' ) ) 
			return new \WP_Error('rest_forbidden', esc_html__('You do not have permissions to save charts.', $this->plugin), array('status' => 401));
			
			return true;

		} // END 	public function permissions_check() {



		
		// protected function chart_trace() {

			// return [

			// 	"type" => "scatter",
			// 	"visible" => true,
			// 	"showlegend" => true,
			// 	"mode" => "lines+markers+text",
			// 	"name" => null,
			// 	"x" => NULL,
			// 	"xaxis" => "x",
			// 	"y" => null,
			// 	"yaxis" => "y",
			// 	"connectgaps" => false,
			// 	"opacity" => 1,
			// 	"marker" => [
			// 		"symbol" => 0,
			// 		"size" => 6,
			// 		"opacity" =>1,
			// 		"color" => null,
			// 		"line" => [
			// 			"color" => "#444444",
			// 			"width" => 0
			// 		],
			// 		"gradient" => [
			// 			"type" => "none",
			// 			"color" => "#444444"
			// 		],
			// 		"masdisplayed" => 0
			// 	],
			// 	"line" => [
			// 		"dash" => "solid",
			// 		"shape" => "linear",
			// 		"width" => 2,
			// 		"color" => null,
			// 		"smoothing" => 1,
			// 		"simplify" => true
			// 	],
			// 	"text" => "Hello",
			// 	"textfont" => [
			// 		"family" => "Raleway",
			// 		"color" => null,
			// 		"size" => 12,
			// 	],
			// 	"textposition" => "top center",
			// 	"hovertext" => "",
			// 	"hoverinfo" => "all",
			// 	"hoverlabel" => [
			// 		"align" => "auto",
			// 		"namelength" => 15,
			// 		"font" => [
			// 			"family" => "Raleway",
			// 			"color" => "#FFFFFF",
			// 			"size" => 12,
			// 		],
			// 		"bgcolor" =>null,
			// 		"bordercolor"  => "#000000",
			// 	],
			// 	"error_y" => [
			// 		"visible" => false,
			// 		"type" => "percent",
			// 		"value" => 20,
			// 		"valueminus" => 10,
			// 		"array" => [],
			// 		"arrayminus" => [],
			// 		"color" =>null,
			// 		"symmetric" => false,
			// 		"thickness" => 2,
			// 		"width" => 4
			// 	]

			// ];

		// }





		// public function chart_axis($title_text, $side, $overlaying, $matches, $rangesliderVisible ) {
      
		// 	return [
		// 		"visible" => true,
		// 		"side" => $side,
		// 		"autorange" => true,
		// 		"overlaying" => $overlaying,
		// 		"matches" => $matches,
		// 		// "rangemode" => "normal",
		// 		"title" => ["text" => $title_text],
		// 		"rangeslider" => ["visible" => $rangesliderVisible],
		// 	];

		// }






		// public function min_max_avg_table_options() {

		// 	return [
		// 		"visible" => true,
		// 		"type" => "table",
		// 		"rounding" => null,
		// 		"header" => [],
		// 		"cells" => [],
		// 		"layout" => [
		// 			"height" => 150,
		// 			// "width" => 400,
		// 			"margin" => ["l" => 20, "r" => 20, "t" => 20, "b" => 20, "pad" => 0, "autoexpand" => true],
		// 		],
		// 		"config" => ["responsive" => true, "displayModeBar" => true, "displaylogo" => true]
		// 	];

		// }



    // public function trace_seed() {
      
    //   return [

    //     "visible"  => true,
    //     "mode" => "lines+markers",
    //     "text"  => "Hi there",
    //     "textposition"  => "bottom center",
    //     "hovertext"  => "",
    //     "hoverinfo"  => "all",
    //     "xaxis"  => "x",
    //     "yaxis"  => "y",
    //     "connectgaps"  => false,
    //     "textfont"  => ["family"  =>  "raleway", "size"  => 16, "color"  =>null],
    //     "marker"  => [
    //       "symbol"  => 1,
    //       "opacity" => 1,
    //       "size" => 5,
    //       "maxdisplayed"  => 10,
    //       "color"  => null,
    //       "line" => ["width" => 1, "color" => null],
    //       "gradient" => ["type" => "radial", "color" => null],  
    //     ],
    //     "line"  => [
    //       "shape" => "spline",
    //       "width" => 2,
    //       "color"  => null,
    //       "dash" => "solid",
    //       "smoothing" => 1,
    //       "simplify" => true,
    //     ],
    //     "error_y"  => [
    //       "visible" => false,
    //       "type" => "percent",
    //       "symmetric" => true,
    //       "value" => 20,
    //       "valueminus" => 20,
    //       "array" => [] ,
    //       "arrayminus" => [],
    //       "color" => null,
    //       "thickness" => 2,
    //       "width" => 4,
    //     ],
    //     "hoverlabel"  => [
    //       "bgcolor" => null,
    //       "bordercolor" => null,
    //       "font"  => [
    //         "family"  => "raleway",
    //         "size"  => 15,
    //         "color"  => null,
    //       ],
    //       "align" => null,
    //       "namelength" => -1,
    //     ]
    //   ];
    // }





    // public function default_chart () {

			
    //   return [
        
		// 		"fileUpload" => [], 
    //     "layout" => [
		// 			"width" => null,
		// 			"height" => 300,
		// 			"autosize" => true,
    //       "paper_bgcolor" => "#ffffff",
    //       "plot_bgcolor" => "#ffffff",
    //       "showlegend" => true,
    //       "legend" => [
    //         "valign" => "middle",
    //         "borderwidth" =>1,
    //         "orientation" => "v",
    //         "bgcolor" => "#ffffff",
    //         "bordercolor" => "#ffffff",
    //         "font" => ["family" => "raleway", "size" => 14, "color" => "#222222"],
    //         "itemsizing" => "trace",
    //         "itemwidth" => 30,
    //         "itemclick" => false,
    //         "itemdoubleclick" => "toggle",
    //         "x" => 1.02,
    //         "y"=> 1,
		// 				"title" =>[
		// 					"text" => "",
		// 					"side" => "top",
		// 					"font" => [
		// 						"family" => "balto",
		// 						"size" => 14,
		// 						"color" => "#666666"
		// 					]
    //         ],
    //       ],
    //       "hovermode" => "x",
    //       // "hoverlabel" => [
    //       //   "bgcolor" => "red"
    //       // ],
		// 			"title" => ["text" => "Ge AR/AR 8.0 - 12.0 &#181;m", "x" => 0.5, "y" => "auto", "font" => ["family" => "raleway", "size" => 14, "color" => "#666666"]],
    //       "xaxis" => $this->chart_axis(  "Wavelength ( &#181;m )" , "bottom", null, null, true),
    //       "xaxis2" => $this->chart_axis(  "Wavelength ( &#181;m )", "top", "x", "x", false ),
    //       "yaxis" => $this->chart_axis( "Transmittance ( % )", "left", null, null, false ),
		// 			"yaxis2" => $this->chart_axis( "Reflectance ( % )", "right", "y", "y",false ),
    //       "modebar" => ["bgcolor" => "#cccccc", "orientation" => "h", "color" => "#ffffff", "activecolor" => "#ffffff"],
		// 			"margin" => ["l" => 80, "r" => 80, "t" => 100, "b" => 80, "pad" => 0,"autoexpand" => true],
		// 			"annotations" =>[
    //         // [
    //         //   "visible" => true,
    //         //   "text" => 'Annotation A',
    //         //   "textangle" => 0,
    //         //   "font" => [
    //         //     "family" => "balto",
    //         //     "size" => 14,
    //         //     "color" => "#666666"
    //         //   ],
    //         //   "width" => null,
    //         //   "height" => null,
    //         //   "opacity" => 1,
    //         //   "align" => "center",
    //         //   "valign" => "middle",
    //         //   "bgcolor" => "#ffffff",
    //         //   "bordercolor" => "#000000",
    //         //   "borderpad" => 1,
    //         //   "borderwidth" => 1,
    //         //   "showarrow" => true,
    //         //   "arrowcolor" => "#000000",
    //         //   "arrowhead" => 1,
    //         //   "startarrowhead" => 1,
    //         //   "arrowside" => "end",
    //         //   "arrowsize" => 1,
    //         //   "startarrowsize" => 1,
    //         //   "arrowwidth" => null,
    //         //   "standoff" => 0,
    //         //   "startstandoff" => 0,
    //         //   "axref" => 'pixel',
    //         //   "ayref" => 'pixel',
    //         //   "ax" => -30,
    //         //   "ay" => -40,
    //         //   "xref" => 'x',
    //         //   "x" => 2150,
    //         //   "xshift" => 0,
    //         //   "yref" => 'y',
    //         //   "y" => 20,
    //         //   "yshift" => 0
    //         // ]
		// 			]
		// 		],
    //     "config" => ["responsive" => true, "displayModeBar" => false, "displaylogo" => true, "staticPlot" => false],
    //     "traces" => [],
    //     "minMaxAvgTable" => $this->min_max_avg_table_options()

    //   ];

    // }





		
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
          "wp_rest_nonce"  => wp_create_nonce("wp_rest" ),
          "wp_rest_url" => get_rest_url(null, "{$this->rest_namespace}/{$this->rest_base}"),
          "charts" => $this->fetch_payload()
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
				return new \WP_Error ( 'file_id_missing', __( wp_kses_post ( "A file ID is required." ), $this->plugin ), ["status" => 404] );
			}

      // Get file path from file Id
      $file_path = get_attached_file( $file_id );

      // Check if a file with the supplie Id exsts
			if ( ! $file_path ) {
				return new \WP_Error ( 'file_by_id_missing', __( wp_kses_post ( "We cannot find a file with this ID <strong>{$file_id}</strong>." ), $this->plugin ), ["status" => 404] );
			}

			// Initialize spreadsheet
      $spreadsheet = [];

			// Check if the file is already in the upload directory
			if ( ! file_exists ($file_path)) {
				return new \WP_Error ( 'file_missing', __( wp_kses_post ( "File <.>$file_path</.> does not exist." ), $this->plugin ), ["status" => 404] );
			}

      // Check file type
      if ( ! in_array(wp_check_filetype(wp_basename($file_path))["ext"], $this->file_types )) {
        return new \WP_Error  (  'invalid_file_type', __(wp_kses_post("Invalid file type, <strong>".wp_basename($file_path)."</strong> is not a valid file type.  Only excel and csv spreadsheets are allowed"), $this->plugin ), ["status" => 406] );
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

			var_dump($charts);

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
              
              $chart = null;

            }



          // Set response
          $response = [ 'status' => "success", "action" => $action, "chart" => $chart, "spreadsheet" => $spreadsheet ];
          // $response = [ 'status' => "success", 'action' => $action, "chart" => $chart, 'spreadsheet' => $spreadsheet, "fontFamily" => $this->font_family, "colors" => $this->colors ];

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




		public function chart_admin() {

		
			// $charts = get_option("{$this->prefix}_charts") ? get_option("{$this->prefix}_charts") : [];

			// // var_dump($charts);

			// $template = "chart-admin";

      // // Initialize payload
      // $payload = [];

			// // Assemble payload
			// foreach ($charts as $chart_id => $chart) {

			// 	// Set payload by chart Id
			// 	$payload[$chart_id] = $chart;

			// 	// Fetch spreadsheet
			// 	$spreadsheet = ! is_wp_error( $this->fetch_spreadsheet( $chart['fileUpload']['fileId'] ) ) ? $this->fetch_spreadsheet( $chart['fileUpload']['fileId'] ) : null;

			// 	// Bail if error ( fetch_spreadsheet( ) return an spreadsheet (array) or WP error)
			// 	// if (  ! is_wp_error( $spreadsheet ) ) {
			// 		// Fetch sheet
			// 	$payload[$chart_id]['sheet'] = $spreadsheet[$chart['fileUpload']['sheetId']];

			// }

			echo $this->get_template_html("chart-admin");


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
		public function save_chart_1() {
			echo ($_POST);
			die;
      
      // wp_send_json($_POST);

			try {
				
				if ( ! isset($_POST["action"]) || $_POST["action"] !== "{$this->prefix}_save_chart_action" || ! wp_verify_nonce($_POST["nonce"], "{$this->prefix}__save_chart_nonce")) {
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
				$charts[$chart_id]["minMaxAvgTable"] = ( isset( $_POST["{$this->prefix}__minMaxAvgTable"] ) ) ? $_POST["{$this->prefix}__minMaxAvgTable"] : [];

        $charts[$chart_id]["layout"]["hovermode"] = $charts[$chart_id]["layout"]["hovermode"] === "true"  ? true : ( $charts[$chart_id]["layout"]["hovermode"] === "false" ? false : $charts[$chart_id]["layout"]["hovermode"] );

				$charts[$chart_id]["layout"]["xaxis"]["autorange"] = $charts[$chart_id]["layout"]["xaxis"]["autorange"] === "true"  ? true : ( $charts[$chart_id]["layout"]["xaxis"]["autorange"] === "false" ? false : $charts[$chart_id]["layout"]["xaxis"]["autorange"] );

				$charts[$chart_id]["layout"]["xaxis2"]["autorange"] = $charts[$chart_id]["layout"]["xaxis2"]["autorange"] === "true" ? true : ( $charts[$chart_id]["layout"]["xaxis2"]["autorange"] === "false" ? false : $charts[$chart_id]["layout"]["xaxis2"]["autorange"] );

				$charts[$chart_id]["layout"]["yaxis"]["autorange"] = $charts[$chart_id]["layout"]["yaxis"]["autorange"] === "true" ? true : ( $charts[$chart_id]["layout"]["yaxis"]["autorange"] === "false" ? false : $charts[$chart_id]["layout"]["yaxis"]["autorange"] );

				$charts[$chart_id]["layout"]["yaxis2"]["autorange"] = $charts[$chart_id]["layout"]["yaxis2"]["autorange"] === "true"  ? true : ( $charts[$chart_id]["layout"]["yaxis2"]["autorange"] === "false" ? false : $charts[$chart_id]["layout"]["yaxis2"]["autorange"] );


				$charts[$chart_id]["config"]["responsive"] = isset($charts[$chart_id]["config"]["responsive"] ) ?  true : false;
        $charts[$chart_id]["config"]["staticPlot"] = isset($charts[$chart_id]["config"]["staticPlot"] ) ?  true : false;
				$charts[$chart_id]["config"]["displayModeBar"] = isset($charts[$chart_id]["config"]["displayModeBar"] ) ?  true : false;
        $charts[$chart_id]["config"]["displaylogo"] = isset($charts[$chart_id]["config"]["displaylogo"] ) ?  true : false;
        
				
				$charts[$chart_id]["layout"]["autoexpand"] = isset($charts[$chart_id]["layout"]["autoexpand"] ) ?  true : false;
				$charts[$chart_id]["layout"]["autosize"] = isset($charts[$chart_id]["layout"]["autosize"] ) ?  true : false;
       


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

				// Convert xaxis array type fields to array
				if ( ! $charts[$chart_id]["layout"]["xaxis"]["tickvals"] ) {
					$charts[$chart_id]["layout"]["xaxis"]["tickvals"] = [];
				} else {
					$xaxis_tickvals_arr = [];
					foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis"]["tickvals"] ) as $value) {
						array_push($xaxis_tickvals_arr, floatval($value));
					}
					$charts[$chart_id]["layout"]["xaxis"]["tickvals"] = $xaxis_tickvals_arr;
				}

				// Convert xaxis array type fields to array
				if ( ! $charts[$chart_id]["layout"]["xaxis"]["ticktext"] ) {
					$charts[$chart_id]["layout"]["xaxis"]["ticktext"] = [];
				} else {
					$xaxis_ticktext_arr = [];
					foreach (explode( ",", $charts[$chart_id]["layout"]["xaxis"]["ticktext"] ) as $value) {
						array_push($xaxis_ticktext_arr, $value);
					}
					$charts[$chart_id]["layout"]["xaxis"]["ticktext"] = $xaxis_ticktext_arr;
				}

				wp_send_json($charts[$chart_id]);




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
        // $charts[$chart_id]["layout"]["xaxis"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["xaxis"]["visible"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["xaxis"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["xaxis"]["autorange"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["xaxis"]["rangeslider"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["xaxis"]["rangeslider"]["visible"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["xaxis2"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["xaxis2"]["visible"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["xaxis2"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["xaxis2"]["autorange"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["xaxis2"]["rangeslider"][visible] = isset( $_POST["{$this->prefix}__layout"]["xaxis2"]["rangeslider"]["visible"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["yaxis"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["yaxis"]["visible"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["yaxis"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["yaxis"]["autorange"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["yaxis2"]["visible"] = isset( $_POST["{$this->prefix}__layout"]["yaxis2"]["visible"] ) ?  true : false;
        // $charts[$chart_id]["layout"]["yaxis2"]["autorange"] = isset( $_POST["{$this->prefix}__layout"]["yaxis2"]["autorange"] ) ?  true : false;
        // $charts[$chart_id]["config"]["displayModeBar"] = isset( $_POST["{$this->prefix}__config"]["displayModeBar"] ) ?  true : false;
        // $charts[$chart_id]["config"]["displaylogo"] = isset( $_POST["{$this->prefix}__config"]["displaylogo"] ) ?  true : false;
        // $charts[$chart_id]["minMaxAvgTable"]["visible"] = isset( $_POST["{$this->prefix}minMaxAvgTable"]["visible"] ) ?  true : false;



        $charts[$chart_id]["layout"]["title"]["y"] = "" === $_POST["{$this->prefix}__layout"]["title"]["y"] ?  "auto" : $_POST["{$this->prefix}__layout"]["title"]["y"];

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
					'callback' => array($this, 'chart_admin')
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