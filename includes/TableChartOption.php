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
		public function __construct($options, $control, $id, $css_class, $title, $intro) {

			$this->options = 	$options;
			$this->id = 	$id;
			$this->title = 	$title;
			$this->intro = 	$intro;
			$this->control = 	$control;
			$this->css_class = 	$css_class;
			
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
        "{$this->id}" => [
          "id" => "iwpgv__{$this->id}",
          "cssClasses" => [$this->css_class, 'chart'],
          "title" => __($this->title, $this->plugin),
					"intro" => $this->intro,
					"sections" => [
            'general' => [
							"id" => "general",
							"title" => __("", $this->plugin),
							"fields" => [
								// [
								// 	[
								// 		"id" => "{$this->control}[enable]",
								// 		"title" => __("Enable Min/Max/Average Table", $this->plugin),
                //     "type" => "checkbox",
                //     "value" => $this->options()['enable'] ? true : false,
								// 		"hint" => ""
								// 	],
								// ],
								[
									[
										"id" => "{$this->control}[width]",  
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
										"id" => "{$this->control}[height]", 
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
										"id" => "{$this->control}[alternatingRowStyle]",
										"title" => __("Alternating Row Styles", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['alternatingRowStyle'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "{$this->control}[allowHtml",
										"title" => __("Allow HTML", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['allowHtml'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "{$this->control}[showRowNumber]",
										"title" => __("Show Row Number", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['showRowNumber'],
										"hint" => ""
                  ]
                ],
								[
									[
										"id" => "{$this->control}[firstRowNumber]",  
										"title" => __("First Row Number", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 2000,
                    "step" => 1,
                    "value" => $this->options()['firstRowNumber'],
										"hint" => ""
                  ],
									[
										"id" => "{$this->control}[frozenColumns]", 
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
										"id" => "{$this->control}[pageSize]", 
										"title" => __("Page Size", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 100,
                    "step" => 1,
                    "value" => $this->options()['pageSize'],
										"hint" => ""
                  ],
									[
										"id" => "{$this->control}[page]",
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
										"id" => "{$this->control}[startPage]", 
										"title" => __("Start Page", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10000,
                    "step" => 1,
                    "value" => $this->options()['startPage'],
										"hint" => ""
                  ],
									[
										"id" => "{$this->control}[sort]",
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
										"id" => "{$this->control}[sortColumn]", 
										"title" => __("Sort Column", $this->plugin), 
										"type" => "number",
										"min" => 1,
										"max" => 10000,
                    "step" => 1,
                    "value" => $this->options()['sortColumn'],
										"hint" => ""
                  ],
										[
										"id" => "{$this->control}[sortAscending]",
										"title" => __("Sort Ascending", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['sortAscending'],
										"hint" => ""
                  ]
								],
								[
									[
										"id" => "{$this->control}[scrollLeftStartPosition]", 
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
										"id" => "{$this->control}[cssClassNames][headerRow]", 
										"title" => __("Header Row", $this->plugin), 
                    "type" => "text",
                    "value" =>$this->options()['cssClassNames']['headerRow'],
										"hint" => ""
                  ],
									[
										"id" => "{$this->control}[cssClassNames][tableRow]",
										"title" => __("Table Row", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['tableRow'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "{$this->control}[cssClassNames][oddTableRow]", 
										"title" => __("Odd Table Row", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['oddTableRow'],
										"hint" => ""
                  ],
									[
										"id" => "{$this->control}[cssClassNames][selectedTableRow]",
										"title" => __("Selected Table Row", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['selectedTableRow'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "{$this->control}[cssClassNames][hoverTableRow]", 
										"title" => __("Hover Table Row", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['hoverTableRow'],
										"hint" => ""
                  ],
									[
										"id" => "{$this->control}[cssClassNames][headerCell]",
										"title" => __("Header Cell", $this->plugin),
                    "type" => "text",
                    "value" =>  $this->options()['cssClassNames']['headerCell'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "{$this->control}[cssClassNames][tableCell]", 
										"title" => __("Table Cell", $this->plugin), 
                    "type" => "text",
                    "value" => $this->options()['cssClassNames']['tableCell'],
										"hint" => ""
                  ],
									[
										"id" => "{$this->control}[cssClassNames][rowNumberCell]",
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