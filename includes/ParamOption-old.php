<?php

/**
* AXL-WP-Member
*
* @package AXL-WP-Member
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace YRL_WP_IGV\Includes;

// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');

// Declare main class if it does not exist
if (!class_exists('ParamOption')) {

	class ParamOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($options = []) {

      $this->options = $options;
      
    } // END __construct



    public function options() {

			return [ 
        "fileUpload" => isset( $this->options['fileUpload'] ) ? $this->options['fileUpload'] : null,
        "sheetId" => isset( $this->options['sheetId'] ) ? $this->options['sheetId'] : null,
        "chartType" => isset( $this->options['chartType'] ) ? $this->options['chartType'] : null,
        "chartId" => isset( $this->options['chartId'] ) ? $this->options['chartId'] : null,
        "mediaUploadBtn" => 'Upload New File',
        'enableSeries' => isset( $this->options['enableSeries'] ) ? true : false,
        'enableTrendlines' => isset( $this->options['enableTrendlines'] ) ? true : false,
        'enableNumRangeSlider' => isset( $this->options['enableNumRangeSlider'] ) ? true : false,
        'enableChartRangeSlider' => isset( $this->options['enableChartRangeSlider'] ) ? true : false,
        'enableMinMaxTableChart' => isset( $this->options['enableMinMaxTableChart'] ) ? true : false,
        'enableTableChart' => isset( $this->options['enableTableChart'] ) ? true : false,
        "theme" => isset( $this->options['theme'] ) ? $this->options['theme'] : null
      ];
    }


   

		/**
		 * Admin fields
		 *
		 * @return void
		 */
		public function fields(){

			return [

        "chartParams" => [
					"id" => "{$this->prefix}__chartParamsPanel",
					'cssClasses' => ['chartParams', 'openOnLoad'],
					"title" => __("Chart Params", $this->plugin),
					'intro' => __('', $this->plugin),
					"sections" => [
						"general" => [
							'intro' => "",
							"id" => "{$this->prefix}__chartParamsSubpanel-general",
							"title" => __("", $this->plugin),
							"fields" => [
								[
                  [
                    "id" => "chartParams[mediaUploadBtn]",
                    'cssClasses' => ['button', 'button-secondary'], 
										"title" => __("Upload File", $this->plugin), 
										"type" => "button",
										"value" => $this->options()['mediaUploadBtn'],
										'hint' => 'Enter a number representing the cahrt width in percent'
									],
									[
                    "id" => "chartParams[fileUpload]",
										"title" => __("", $this->plugin), 
										"type" => "text",
										"value" => $this->options()['fileUpload'],
										'hint' => 'Enter a number representing the cahrt width in percent'
                  ],
								],
                [
                  [
										"id" => "chartParams[sheetId]",
										'cssClasses' => ['hidden'],
										"title" => __("Sheet", $this->plugin),	
										"type" => "select",
										"options" => [],
										"value" => $this->options()['sheetId'],
									],
                ],
                [
                  [
										"id" => "chartParams[chartType]",
										'cssClasses' => ['hidden'],
										"title" => __("Chart Type", $this->plugin),	
										"type" => "select",
										"options" => $this->chart_types,
										"value" => $this->options()['chartType'],
									],
								],
								[
									[
										"id" => "chartParams[chartId]",
										"title" => __("", $this->plugin),	
										"type" => "hidden",
										"value" => $this->options()['chartId'],
									],
								],
								[
									[
										"id" => "chartParams[enableSeries]",
										"title" => __("Enable Series", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['enableSeries'],
									],
									[
										"id" => "chartParams[enableTrendlines]",
										"title" => __("Enable Trendlines", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['enableTrendlines'],
									],
                ],
                [
									[
										"id" => "chartParams[enableNumRangeSlider]",
										"title" => __("Enable Num. Range Slider", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['enableNumRangeSlider'],
									],
									[
										"id" => "chartParams[enableChartRangeSlider]",
										"title" => __("Enable Chart Range Slider", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['enableChartRangeSlider'],
									],
								],
								[
									[
										"id" => "chartParams[enableMinMaxTableChart]",
										"title" => __("Enable Min/Max Table", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['enableMinMaxTableChart'],
									],
                  [
										"id" => "chartParams[enableTableChart]",
										"title" => __("Enable Table Chart", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['enableTableChart'],
                  ],
                ],
								[
									[
										"id" => "chartParams[theme]",
										"title" => __("Select Theme", $this->plugin),	
										"type" => "select",
										"options" => [
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										],
										"value" => $this->options()['theme'],
									],
                ],
              ]
						],

						// "advanced" => [
						// 	'intro' => "",
						// 	"id" => "{$this->prefix}__chartParamsSubpanel-advanced",
						// 	"title" => __("Advanced", $this->plugin),
						// 	"fields" => [
            //     [
						// 			[
						// 				"id" => "chartParams[enableSeries]",
						// 				"title" => __("Enable Series", $this->plugin),
						// 				"type" => "checkbox",
						// 				"value" => $this->options()['enableSeries'],
						// 			],
						// 			[
						// 				"id" => "chartParams[enableTrendlines]",
						// 				"title" => __("Enable Trendlines", $this->plugin),
						// 				"type" => "checkbox",
						// 				"value" => $this->options()['enableTrendlines'],
						// 			],
            //     ],
            //     [
						// 			[
						// 				"id" => "chartParams[enableNumRangeSlider]",
						// 				"title" => __("Enable Number Range Slider", $this->plugin),
						// 				"type" => "checkbox",
						// 				"value" => $this->options()['enableNumRangeSlider'],
						// 			],
						// 			[
						// 				"id" => "chartParams[enableChartRangeSlider]",
						// 				"title" => __("Enable Chart Range Slider", $this->plugin),
						// 				"type" => "checkbox",
						// 				"value" => $this->options()['enableChartRangeSlider'],
						// 			],
						// 		],
						// 		[
						// 			[
						// 				"id" => "chartParams[enableMinMaxTableChart]",
						// 				"title" => __("Enable Min/Max Table Chart", $this->plugin),
						// 				"type" => "checkbox",
						// 				"value" => $this->options()['enableMinMaxTableChart'],
						// 			],
						// 		],
						// 		[
            //       [
						// 				"id" => "chartParams[enableTableChart]",
						// 				"title" => __("Enable Table Chart", $this->plugin),
						// 				"type" => "checkbox",
						// 				"value" => $this->options()['enableTableChart'],
            //       ],
            //     ],
						// 		[
						// 			[
						// 				"id" => "chartParams[theme]",
						// 				"title" => __("Select Theme", $this->plugin),	
						// 				"type" => "select",
						// 				"options" => [
						// 					"horizontal" => "Horizontal",
						// 					"vertical" => "Vertical",
						// 				],
						// 				"value" => $this->options()['theme'],
						// 			],
            //     ],
            //   ]
            // ]	
          ]
        ],

      ];

		} // END chart_fields
		

	} // END Dashboard

}