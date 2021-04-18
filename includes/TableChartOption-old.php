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
if (!class_exists('TableChartOption')) {

	class TableChartOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($options) {

			$this->options = 	$options;

    } // END __construct





  public function options() {

			return [
        'enable' => false,
        "alternatingRowStyle" => true,
        "width" => 100,
        "height" => 100,
        "allowHtml" => false,
        "showRowNumber" => false,
        "firstRowNumber" => 1,
        "frozenColumns" => 1,
        "page" => 'enable',
        'pageSize' => 5,
        "sort" => 'enable',
        "sortAscending" => false,
        "sortColumn" => -1,
        "startPage" => 0,
        'scrollLeftStartPosition' => 0,
        'pagingButtons' => 'auto',
        "cssClassNames" => [
          "headerRow" => null,
          "tableRow" => null,
          "oddTableRow" => null,
          "selectedTableRow" => null,
          "hoverTableRow" => null,
          "headerCell" => null,
          "tableCell" => null,
          "rowNumberCell" => null,
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
        "tableChart" => [
          "id" => "iwpgv__tableChart",
          'cssClasses' => ['tableChartOption', 'chart'],
          "title" => __("Table Chart", $this->plugin),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"sections" => [
            'general' => [
							"id" => "tableChart",
							"title" => __("", $this->plugin),
							"fields" => [
								// [
								// 	[
								// 		"id" => "tableChartOptions[enable]",
								// 		"title" => __("Enable Table Chart", $this->plugin),
                //     "type" => "checkbox",
                //     "value" =>  $this->options()['enable'],
								// 		"hint" => ""
								// 	],
								// ],
								[
									[
										"id" => "tableChartOptions[width]",  
										"title" => __("Chart Width", $this->plugin),
										"suffix" => "%",
										"type" => "number",
										"min" => 0,
										"max" => 100,
                    "step" => 1,
                    "value" => $this->options()['width'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[height]", 
										"title" => __("Chart Height", $this->plugin),
										"suffix" => "%",
										"type" => "number",
										"min" => 0,
										"max" => 100,
                    "step" => 1,
                    "value" => $this->options()['height'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "tableChartOptions[alternatingRowStyle]",
										"title" => __("Alternating Row Styles", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['alternatingRowStyle'] ? true : false,
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "tableChartOptions[allowHtml",
										"title" => __("Allow HTML", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['allowHtml'] ? true : false,
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "tableChartOptions[showRowNumber]",
										"title" => __("Show Row Number", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['showRowNumber'] ? true : false,
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "tableChartOptions[firstRowNumber]",  
										"title" => __("First Row Number", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 2000,
                    "step" => 1,
                    "value" => $this->options()['firstRowNumber'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[frozenColumns]", 
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
										"id" => "tableChartOptions[pageSize]", 
										"title" => __("Page Size", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 100,
                    "step" => 1,
                    "value" =>$this->options()['pageSize'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[page]",
										"title" => __("Page", $this->plugin),
										"type" => "select",
										"options" => [
											"enable" => "Enable",
											"disable" => "Disable",
                    ],
                    "value" =>$this->options()['page'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "tableChartOptions[startPage]", 
										"title" => __("Start Page", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10000,
                    "step" => 1,
                    "value" => $this->options()['startPage'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[sort]",
										"title" => __("Sort", $this->plugin),
										"type" => "select",
										"options" => [
											"enable" => "Enable",
											"disable" => "Disable",
                    ],
                    "value" => $this->options()['sort'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "tableChartOptions[sortColumn]", 
										"title" => __("Sort Column", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10000,
                    "step" => 1,
                    "value" => $this->options()['sortColumn'],
										"hint" => ""
                  ],
										[
										"id" => "tableChartOptions[sortAscending]",
										"title" => __("Sort Ascending", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['sortAscending'] ? true : false,
										"hint" => ""
                  ]
								],
								[
									[
										"id" => "tableChartOptions[scrollLeftStartPosition]", 
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
										"id" => "tableChartOptions[cssClassNames][headerRow]", 
										"title" => __("Header Row", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['headerRow'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[cssClassNames][tableRow]",
										"title" => __("Table Row", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['tableRow'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "tableChartOptions[cssClassNames][oddTableRow]", 
										"title" => __("Odd Table Row", $this->plugin), 
                    "type" => "text",
                    "value" =>  $this->options()['cssClassNames']['oddTableRow'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[cssClassNames][selectedTableRow]",
										"title" => __("Selected Table Row", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['selectedTableRow'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "tableChartOptions[cssClassNames][hoverTableRow]", 
										"title" => __("Hover Table Row", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['hoverTableRow'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[cssClassNames][headerCell]",
										"title" => __("Header Cell", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['headerCell'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "tableChartOptions[cssClassNames][tableCell]", 
										"title" => __("Table Cell", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['tableCell'],
										"hint" => ""
                  ],
									[
										"id" => "tableChartOptions[cssClassNames][rowNumberCell]",
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