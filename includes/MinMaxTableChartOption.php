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
if (!class_exists('MinMaxTableChartOption')) {

	class MinMaxTableChartOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($options) {

			$this->options = 	$options;
			
    } // END __construct


		
    

    public function options() {

			return [
        // 'enable' => false,
        "alternatingRowStyle" => isset( $this->options['alternatingRowStyle'] ) ? true : true,
        "width" => isset( $this->options['width'] ) ? $this->options['width'] : '100%',
        "height" => isset( $this->options['height'] ) ? $this->options['height'] : '100%',
        "allowHtml" => isset( $this->options['allowHtml'] ) ? true : false,
        "showRowNumber" => isset( $this->options['showRowNumber'] ) ? true : false,
        "firstRowNumber" => isset( $this->options['firstRowNumber'] ) ? $this->options['firstRowNumber'] : 1,
        "frozenColumns" => isset( $this->options['frozenColumns'] ) ? $this->options['frozenColumns'] : 1,
        "page" => isset( $this->options['page'] ) ? $this->options['page'] : 'enable',
        'pageSize' => isset( $this->options['pageSize'] ) ? $this->options['pageSize'] : 10,
        "sort" => isset( $this->options['sort'] ) ? $this->options['sort'] : 'enable',
        "sortAscending" => isset( $this->options['sortAscending'] ) ? true : false,
        "sortColumn" => isset( $this->options['sortColumn'] ) ? $this->options['sortColumn'] : 0,
        "startPage" => isset( $this->options['startPage'] ) ? $this->options['startPage'] : 0,
        'scrollLeftStartPosition' => isset( $this->options['scrollLeftStartPosition'] ) ? $this->options['scrollLeftStartPosition'] : 0,
        'pagingButtons' => isset( $this->options['pagingButtons'] ) ? $this->options['pagingButtons'] : null,
        "cssClassNames" => [
          "headerRow" => isset( $this->options['cssClassNames']['headerRow'] ) ? $this->options['cssClassNames']['headerRow'] : '',
          "tableRow" => isset( $this->options['cssClassNames']['tableRow'] ) ? $this->options['cssClassNames']['tableRow'] : '',
          "oddTableRow" => isset( $this->options['cssClassNames']['oddTableRow'] ) ? $this->options['cssClassNames']['oddTableRow'] : '',
          "selectedTableRow" => isset( $this->options['cssClassNames']['selectedTableRow'] ) ? $this->options['cssClassNames']['selectedTableRow'] : '',
          "hoverTableRow" => isset( $this->options['cssClassNames']['hoverTableRow'] ) ? $this->options['cssClassNames']['hoverTableRow'] : '',
          "headerCell" => isset( $this->options['cssClassNames']['headerCell'] ) ? $this->options['cssClassNames']['headerCell'] : '',
          "tableCell" => isset( $this->options['cssClassNames']['tableCell'] ) ? $this->options['cssClassNames']['tableCell'] : '',
          "rowNumberCell" => isset( $this->options['cssClassNames']['rowNumberCell'] ) ? $this->options['cssClassNames']['rowNumberCell'] : '',
        ]          
      ];
		
    }





     	/**
		 * Admin fields
		 *
		 * @return void
		 */
		public function fields(){

      return [
        "minMaxTableChart" => [
          "id" => "iwpgv__minMaxTableChart",
          "cssClasses" => ['minMaxTableChartOption', 'chart'],
          "title" => __("Min Max Table", $this->plugin),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"sections" => [
            'general' => [
							"id" => "minMaxTableChart",
							"title" => __("", $this->plugin),
							"fields" => [
								// [
								// 	[
								// 		"id" => "minMaxTableChartOptions[enable]",
								// 		"title" => __("Enable Min/Max/Average Table", $this->plugin),
                //     "type" => "checkbox",
                //     "value" => $this->options()['enable'] ? true : false,
								// 		"hint" => ""
								// 	],
								// ],
								[
									[
										"id" => "minMaxTableChartOptions[width]",  
										"title" => __("Chart Width", $this->plugin),
										// "fieldSuffix" => "%",
										"type" => "text",
										// "min" => 200,
										// "max" => 2000,
                    // "step" => 100,
                    "value" =>  $this->options()['width'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[height]", 
										"title" => __("Chart Height", $this->plugin),
										// "fieldSuffix" => "%",
										"type" => "text",
										// "min" => 200,
										// "max" => 2000,
                    // "step" => 100,
                    "value" => $this->options()['height'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "minMaxTableChartOptions[alternatingRowStyle]",
										"title" => __("Alternating Row Styles", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['alternatingRowStyle'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "minMaxTableChartOptions[allowHtml",
										"title" => __("Allow HTML", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['allowHtml'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "minMaxTableChartOptions[showRowNumber]",
										"title" => __("Show Row Number", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['showRowNumber'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "minMaxTableChartOptions[firstRowNumber]",  
										"title" => __("First Row Number", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 2000,
                    "step" => 1,
                    "value" => $this->options()['firstRowNumber'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[frozenColumns]", 
										"title" => __("Frozen Columns", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10,
                    "step" => 1,
                    "value" => $this->options()['frozenColumns'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "minMaxTableChartOptions[pageSize]", 
										"title" => __("Page Size", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 100,
                    "step" => 1,
                    "value" => $this->options()['pageSize'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[page]",
										"title" => __("Page", $this->plugin),
										"type" => "select",
										"options" => [
											"enable" => "Enable",
											"disable" => "Disable",
                    ],
                    "value" => $this->options()['page'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "minMaxTableChartOptions[startPage]", 
										"title" => __("Start Page", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10000,
                    "step" => 1,
                    "value" => $this->options()['startPage'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[sort]",
										"title" => __("Sort", $this->plugin),
										"type" => "select",
										"options" => [
											"enable" => "Enable",
											"disable" => "Disable",
                    ],
                    "value" =>$this->options()['sort'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "minMaxTableChartOptions[sortColumn]", 
										"title" => __("Sort Column", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10000,
                    "step" => 1,
                    "value" => $this->options()['sortColumn'],
										"hint" => ""
                  ],
										[
										"id" => "minMaxTableChartOptions[sortAscending]",
										"title" => __("Sort Ascending", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['sortAscending'],
										"hint" => ""
                  ]
								],
								[
									[
										"id" => "minMaxTableChartOptions[scrollLeftStartPosition]", 
										"title" => __("Sort Column", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10000,
                    "step" => 1,
                    "value" =>  $this->options()['scrollLeftStartPosition'],
										"hint" => ""
                  ],
								],
								[
									[
										"id" => "minMaxTableChartOptions[cssClassNames][headerRow]", 
										"title" => __("Header Row", $this->plugin), 
                    "type" => "text",
                    "value" =>$this->options()['cssClassNames']['headerRow'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[cssClassNames][tableRow]",
										"title" => __("Table Row", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['tableRow'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "minMaxTableChartOptions[cssClassNames][oddTableRow]", 
										"title" => __("Odd Table Row", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['oddTableRow'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[cssClassNames][selectedTableRow]",
										"title" => __("Selected Table Row", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['selectedTableRow'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "minMaxTableChartOptions[cssClassNames][hoverTableRow]", 
										"title" => __("Hover Table Row", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['hoverTableRow'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[cssClassNames][headerCell]",
										"title" => __("Header Cell", $this->plugin),
                    "type" => "text",
                    "value" =>  $this->options()['cssClassNames']['headerCell'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "minMaxTableChartOptions[cssClassNames][tableCell]", 
										"title" => __("Table Cell", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['tableCell'],
										"hint" => ""
                  ],
									[
										"id" => "minMaxTableChartOptions[cssClassNames][rowNumberCell]",
										"title" => __("Row Number Cell", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['rowNumberCell'],
										"hint" => ""
									],
								],
              ]
            ],
          ]
        ],
      ];
		
		} // END admin_fields

    





	} // END Dashboard

}