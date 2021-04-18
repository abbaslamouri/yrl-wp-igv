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
if (!class_exists('ToolTipOption')) {

	class ToolTipOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct() {

      // $this->axis = $axis;
      
      // $this->axis_direction = $axis['id'] === '[hAxis]' ? ["1" => "Left To Right", "-1" => "Right To Left"] : ["1" => "Bottom to Top", "-1" => "Top to Bottom"];
    } // END __construct


		
  

    public function options() {

			return [

       
				"tooltip" => [
          'selectionMode' => 'single',
          'aggregationTarget' => 'auto',
					"text"=> 'both',
					"showColorCode"=> true,
					"trigger" => 'focus',
					"textStyle" => [
						"color" => 'magenta',
						"fontName" => 'tahoma',
						"fontSize" => 12,
						"bold" => true,
						"italic" => true,
          ],
        ],

      ];
    }


   




		/**
		 * Admin fields
		 *
		 * @return void
		 */
		public function fields(){

			return [
				'tooltips' => [
          "id" => 'tooltips',
          'cssClass' => 'chartOption',
					"title" => __('Tooltips', $this->plugin),
					"sections" => [
						"general" => [
							"id" => "general",
              "title" => __('', $this->plugin),
              "fields" => [
								[
									[
										"id" => "chartOptions[selectionMode]",
										"fieldTitle" => __("Selection Mode", $this->plugin),
										"fieldType" => "select",
										"fieldOptions" => [
											"single" => "Single",
											"multiple" => "Multiple",
                    ],
                    "value" => $this->options()['selectionMode'],
										"hint" => "When selectionMode is 'multiple', users may select multiple data points."
                  ],
									[
										"id" => "chartOptions[aggregationTarget]",
										"fieldTitle" => __("Aggregation Target", $this->plugin),
										"fieldType" => "select",
										"fieldOptions" => [
											"category" => "Category",
											"series" => "Series",
											"auto" => "Auto",
											"none" => "None",
                    ],
                    "value" => $this->options()['aggregationTarget'],
										"hint" => "How multiple data selections are rolled up into tooltips:<br>
											<ul>
											<li>- <strong>category</strong>: Group selected data by x-value</li>
											<li>- series: Group selected data by series</li>
											<li>- auto: Group selected data by x-value if all selections have the same x-value, and by series otherwise</li>
											<li>- none: Show only one tooltip per selection</li>
											</ul>"
                  ],
                ],
								[
									[
										"id" => "chartOptions[tooltip][trigger]",
										"fieldTitle" => __("Trigger", $this->plugin),
										"fieldType" => "select",
										"fieldOptions" => [
											"focus" => "Hover",
											"selection" => "Selection",
											"none" => "None",
                    ],
                    "value" => $this->options()['tooltip']['trigger'],
                  ],
									[
										"id" => "chartOptions[tooltip][text]",
										"fieldTitle" => __("Text", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",	
										"fieldType" => "select",
										"fieldOptions" => [
											"both" => "Value and Percentage",
											"value" => "Value",
											"percentage" => "Percentage",
                    ],
                    "value" => $this->options()['tooltip']['text'],
                  ],
                ],
								[
									[
										"id" => "chartOptions[tooltip][textStyle][fontName]",
										"fieldTitle" => __("Font", $this->plugin),
										"fieldType" => "select",
										"fieldOptions" =>  $this->font_names,
                    "value" => $this->options()['tooltip']['textStyle']['fontName'],
                  ],
									
									[
										"id" => "chartOptions[tooltip][textStyle][fontSize]",
										"fieldTitle" => __("Size", $this->plugin),
                    "fieldType" => "number",
                    "value" => $this->options()['tooltip']['textStyle']['fontSize'],
                  ],
                ],
								[
									[
										"id" => "chartOptions[tooltip][textStyle][color]",
										"cssClass" => "color-picker",
										"fieldTitle" => __("Color", $this->plugin),
                    "fieldType" => "color-picker",
                    "value" => $this->options()['tooltip']['textStyle']['color'],
                  ],
                ],
								[
									[
										"id" => "chartOptions[tooltip][textStyle][bold]",
										"fieldTitle" => __("Bold", $this->plugin),
                    "fieldType" => "checkbox",
                    "value" => $this->options()['tooltip']['textStyle']['bold'] ? true : false,
                  ],
									[
										"id" => "chartOptions[tooltip][textStyle][italic]",
										"fieldTitle" => __("Italic", $this->plugin),
                    "fieldType" => "checkbox",
                    'value' => $this->options()['tooltip']['textStyle']['italic'] ? true : false,
                  ],
									[
										"id" => "chartOptions[tooltip][showColorCode]",
										"fieldTitle" => __("Show Color Code", $this->plugin),
                    "fieldType" => "checkbox",
                    "value" => $this->options()['tooltip']['showColorCode'] ? true : false,
                  ],
                ]
              ]
            ]
            
          ]
        ]
      ];

		} // END chart_fields
		




	} // END Dashboard

}