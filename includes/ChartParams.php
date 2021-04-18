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
if (!class_exists('ChartParams')) {

	class ChartParams Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($params = []) {

      $this->params = $params;
      
    } // END __construct



    public function params() {

			return [ 
        "fileUpload" => isset( $this->params['fileUpload'] ) ? $this->params['fileUpload'] : null,
        "sheetId" => isset( $this->params['sheetId'] ) ? $this->params['sheetId'] : null,
        "chartType" => isset( $this->params['chartType'] ) ? $this->params['chartType'] : null,
        "chartId" => isset( $this->params['chartId'] ) ? $this->params['chartId'] : null,
        "mediaUploadBtn" => 'Upload New File',
        // 'enableSeries' => isset( $this->params['enableSeries'] ) ? true : false,
        // 'enableTrendlines' => isset( $this->params['enableTrendlines'] ) ? true : false,
        'rangeSlider' => isset( $this->params['rangeSlider'] ) ? true : false,
        'enableChartRangeSlider' => isset( $this->params['enableChartRangeSlider'] ) ? true : false,
        'enableMinMaxTableChart' => isset( $this->params['enableMinMaxTableChart'] ) ? true : false,
        'enableTableChart' => isset( $this->params['enableTableChart'] ) ? true : false,
        "theme" => isset( $this->params['theme'] ) ? $this->params['theme'] : null
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
					'intro' => __("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.", $this->plugin),
					"sections" => [
						"general" => [
							'intro' => "jkljkljkljklkjlkjljkljkljkjkljklkjl",
							"id" => "{$this->prefix}__chartParamsSubpanel-general",
							"title" => __("", $this->plugin),
							"fields" => [
								[
                  [
                    "id" => "chartParams[mediaUploadBtn]",
                    'cssClasses' => ['button', 'button-secondary'], 
										"title" => __("Upload File", $this->plugin), 
										"type" => "button",
										"value" => $this->params()['mediaUploadBtn'],
										'hint' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
									],
									[
                    "id" => "chartParams[fileUpload]",
										"title" => __("", $this->plugin), 
										"type" => "text",
										"value" => $this->params()['fileUpload'],
										'hint' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
                  ],
								],
                [
                  [
										"id" => "chartParams[sheetId]",
										'cssClasses' => ['hidden'],
										"title" => __("Sheet", $this->plugin),	
										"type" => "select",
										"options" => [],
										"value" => $this->params()['sheetId'],
									],
                ],
                [
                  [
										"id" => "chartParams[chartType]",
										'cssClasses' => ['hidden'],
										"title" => __("Chart Type", $this->plugin),	
										"type" => "select",
										"options" => $this->chart_types,
										"value" => $this->params()['chartType'],
									],
								],
								[
									[
										"id" => "chartParams[chartId]",
										"title" => __("", $this->plugin),	
										"type" => "hidden",
										"value" => $this->params()['chartId'],
									],
								],
								// [
								// 	[
								// 		"id" => "chartParams[enableSeries]",
								// 		"title" => __("Enable Series", $this->plugin),
								// 		"type" => "checkbox",
								// 		"value" => $this->params()['enableSeries'],
								// 	],
								// 	[
								// 		"id" => "chartParams[enableTrendlines]",
								// 		"title" => __("Enable Trendlines", $this->plugin),
								// 		"type" => "checkbox",
								// 		"value" => $this->params()['enableTrendlines'],
								// 	],
                // ],
                [
									[
										"id" => "chartParams[enableChartRangeSlider]",
										"title" => __("Enable Range Slider", $this->plugin),
										"type" => "checkbox",
										"value" => $this->params()['enableChartRangeSlider'],
									],
									[
										"id" => "chartParams[enableMinMaxTableChart]",
										"title" => __("Enable Min/Max Table", $this->plugin),
										"type" => "checkbox",
										"value" => $this->params()['enableMinMaxTableChart'],
									],
                  [
										"id" => "chartParams[enableTableChart]",
										"title" => __("Enable Table Chart", $this->plugin),
										"type" => "checkbox",
										"value" => $this->params()['enableTableChart'],
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
										"value" => $this->params()['theme'],
									],
                ],
              ]
						],
          ]
        ],

      ];

		} // END chart_fields
		

	} // END Dashboard

}