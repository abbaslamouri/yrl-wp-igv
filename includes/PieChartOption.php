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
if (!class_exists('PieChartOption')) {

	class PieChartOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($options = []) {

      $this->options = $options;
      // $this->chart_type = $chart_type;

      // $this->axis = $axis;
      
      // $this->axis_direction = $axis['id'] === '[hAxis]' ? ["1" => "Left To Right", "-1" => "Right To Left"] : ["1" => "Bottom to Top", "-1" => "Top to Bottom"];
    } // END __construct


		
  

    public function options() {

      // if (! empty( $this->options ) ) {
      //   return $this->options;
      // }

			return [

        "is3D" => isset($this->options['is3D']) ? true: false,
				"pieHole" => isset($this->options['pieHole']) ? $this->options['pieHole'] : null,
				"pieSliceBorderColor" => isset($this->options['pieSliceBorderColor']) ? $this->options['pieSliceBorderColor'] : 'magenta',
				"pieSliceText" => isset($this->options['pieSliceText']) ? $this->options['pieSliceText'] : 'label',
				"pieStartAngle" => isset($this->options['pieStartAngle']) ? $this->options['pieStartAngle'] : 0,
				"sliceVisibilityThreshold" => isset($this->options['sliceVisibilityThreshold']) ? $this->options['sliceVisibilityThreshold'] : 0.1,
				"pieResidueSliceLabel" => isset($this->options['pieResidueSliceLabel']) ? $this->options['pieResidueSliceLabel'] : 'ZZZZ',
				"pieResidueSliceColor" => isset($this->options['pieResidueSliceColor']) ? $this->options['pieResidueSliceColor'] : 'orange',
				"pieSliceTextStyle" => [
					"color" => isset($this->options['pieSliceTextStyle']['color']) ? $this->options['pieSliceTextStyle']['color'] : 'cyan',
					"fontName" => isset($this->options['pieSliceTextStyle']['fontName']) ? $this->options['pieSliceTextStyle']['fontName'] : 'Times-Roman',
					"fontSize" => isset($this->options['pieSliceTextStyle']['fontSize']) ? $this->options['pieSliceTextStyle']['fontSize'] : 12,
					"bold" => isset($this->options['pieSliceTextStyle']['bold']) ? true : true,
					"italic" => isset($this->options['pieSliceTextStyle']['italic']) ? true : false,
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
				'pieChartSettings' => [
          "id" => 'pieChartSettings',
          'cssClasses' => ['pieChartOption', 'chart'],
					"title" => __('Pie Chart Settings', $this->plugin),
					"sections" => [
						"general" => [
							"id" => "general",
              "title" => __('', $this->plugin),
              "fields" => [									
								[
									[
										"id" => "pieChartOptions[is3D]",
										"title" => __("3D Pie Chart", $this->plugin),
                    "type" => "checkbox",
                    "value" =>  $this->options()['is3D']? true : false,
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
									[
										"id" => "pieChartOptions[pieHole]",
										"title" => __("Pie Hole", $this->plugin),
										"type" => "number",
										"min" => 0,
										"max" => 1,
                    "step" => 0.1,
                    "value" =>  $this->options()['pieHole'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
                ],
								[
									[
										"id" => "pieChartOptions[sliceVisibilityThreshold]",
										"title" => __("slice Visibility Threshold", $this->plugin),
										"type" => "number",
										"min" => 0,
										"max" => 1,
                    "step" => 0,
                    "value" =>  $this->options()['sliceVisibilityThreshold'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
									[
										"id" => "pieChartOptions[pieSliceBorderColor]",
                    "cssClasses" => ["color-picker"],
                    "title" => __("Pie Slice Border Color", $this->plugin),
										"type" => "color-picker",
                    "value" =>  $this->options()['pieSliceBorderColor'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
                ],
                [
									[
										"id" => "pieChartOptions[pieResidueSliceLabel]",
										"title" => __("Pie Residual Slice Label", $this->plugin),
                    "type" => "text",
                    "value" =>  $this->options()['pieResidueSliceLabel'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
                ],
								[
									[
										"id" => "pieChartOptions[pieResidueSliceColor]",
										"title" => __("Pie Residual Slice Color", $this->plugin),
                    "cssClasses" => ["color-picker"],
                    "type" => "color-picker",
                    "value" =>  $this->options()['pieResidueSliceColor'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
									[
										"id" => "pieChartOptions[pieSliceText]",
										"title" => __("Pie Slice Text", $this->plugin),
										"type" => "select",
										"options" => [
											"label" => "The tooltip will be displayed when the user hovers over the element",
											"none" => " No text is displayed",
											"value" => "The quantitative value of the slice",
											"percentage" => "The percentage of the slice size out of the total",
                    ],
                    "value" =>  $this->options()['pieSliceText'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
                ],
								[
									[
                    "id" => "pieChartOptions[pieSliceTextStyle][color]",
                    "cssClasses" => ["color-picker"],
										"title" => __("Color", $this->plugin),
                    "type" => "color-picker",
                    "value" =>  $this->options()['pieSliceTextStyle']['color'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
									[
										"id" => "pieChartOptions[pieSliceTextStyle][fontName]",
										"title" => __("Font Name", $this->plugin),
										"type" => "select",
										"options" =>  $this->font_names,
                    "value" =>  $this->options()['pieSliceTextStyle']['fontName'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
                ],
								[
									[
										"id" => "pieChartOptions[pieSliceTextStyle][fontSize]",
										"title" => __("Font Size", $this->plugin),
                    "type" => "number",
                    "min" => 1,
										"max" => 100,
                    "step" => 1,
                    "value" =>  $this->options()['pieSliceTextStyle']['fontSize'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
									[
										"id" => "pieChartOptions[pieSliceTextStyle][bold]",
										"title" => __("Bold", $this->plugin),
                    "type" => "checkbox",
                    "value" =>  $this->options()['pieSliceTextStyle']['bold'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ],
									[
										"id" => "pieChartOptions[pieSliceTextStyle][italic]",
										"title" => __("Italic", $this->plugin),
                    "type" => "checkbox",
                    "value" =>  $this->options()['pieSliceTextStyle']['italic'],
                    // 'disabled' => $this->chart_type !== 'PieChart' ? true: false
                  ]
                ],
              ]
            ]
          ]
        ]
      ];

		} // END chart_fields
		




	} // END Dashboard

}