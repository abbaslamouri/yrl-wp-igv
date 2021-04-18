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
if (!class_exists('NumRangeOption')) {

	class NumRangeOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct(	$options, $filter_columns ) {

      $this->options = 	$options;
			$this->filter_columns = 	$filter_columns;
			
			$column_indeces = [];
      foreach ($this->filter_columns as $key => $value) {
				$this->column_indeces[$value['colIndex']] = $value['colLabel'];
			}

      // wp_send_json(	$filter_columns );
			
    } // END __construct



    public function options() {

			return [
        // 'enable' => isset( $this->options['enable'] ) ? true : false,
        'filterColumnIndex' => array_keys($this->column_indeces)[0],
        'minValue' => null,
        'maxValue' =>  null,
        'ui' => [
          'label' => isset( $this->options['ui']['label'] ) ? $this->options['ui']['label'] : 'Number Range Slider',
          'orientation' => isset( $this->options['ui']['orientation'] ) ? $this->options['ui']['orientation'] : 'horizontal',
          'labelSeparator' => isset( $this->options['ui']['labelSeparator'] ) ? $this->options['ui']['labelSeparator'] : '========',
          'labelStacking' => isset( $this->options['ui']['labelStacking'] ) ? $this->options['ui']['labelStacking'] : 'vertical',
          'showRangeValues' => isset( $this->options['ui']['showRangeValues'] ) ? $this->options['ui']['showRangeValues'] : true,
          'format' => [
            'fractionDigits' => isset( $this->options['format']['fractionDigits'] ) ? $this->options['format']['fractionDigits'] : 2
          ],
          'step' => isset( $this->options['step'] ) ? $this->options['step'] : 1,
          //  'ticks'=> 100,
          'cssClass' => isset( $this->options['cssClass'] ) ? $this->options['cssClass'] : "{$this->prefix}-num-range-filter"
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
        "numRangeFilter" => [
          "id" => "{$this->prefix}__numRangeFilter",
          'cssClasses' => ['numRangeOption', 'chart'],
          "title" => __("Number Range Filter", $this->plugin),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"sections" => [
            "general" => [
							"id" => "general",
							"title" => __("", $this->plugin),
							"fields" => [
								// [
								// 	[
                //     "id" => "numRangeOptions[enable]",
                //     'cssClasses' => 'hasDependents',
                //     'dependents' => [
                //       'numRangeOptions[filterColumnIndex]',
                //       'numRangeOptions[minValue]',
                //       'numRangeOptions[maxValue]',
                //       'numRangeOptions[ui][label]'],
								// 		"title" => __("Enable Number Range Slider", $this->plugin), 
                //     "type" => "checkbox", 
                //     "value" => $this->options()['enable'],
								// 		"hint" => ""
								// 	],
								// ],
								[
									[
										"id" => "numRangeOptions[filterColumnIndex]",
										"title" => __("Filter Column", $this->plugin), 
										"type" => "select",
										"options" => $this->column_indeces,
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" => $this->options()['filterColumnIndex'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "numRangeOptions[minValue]",
										"title" => __("Min Value", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
										"max" => 1000000,
										"step" => 1,
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" => $this->options()['minValue'],
										"hint" => ""
									],
									[
										"id" => "numRangeOptions[maxValue]",
										"title" => __("Max Value", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
										"max" => 20,
										"step" => 1,
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" => $this->options()['maxValue'],
										"hint" => ""
									],

								],
								[
									[
										"id" => "numRangeOptions[ui][label]",
										"title" => __("Label", $this->plugin), 
										"type" => "text",
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" =>$this->options()['ui']['label'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "numRangeOptions[ui][orientation]",
										"title" => __("Orientation", $this->plugin), 
										"type" => "select",
										"options" => [
											"horizontal" => "Horizontal",
                      "vertical" => "Vertical",
										],
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" => $this->options()['ui']['orientation'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "numRangeOptions[ui][labelSeparator]",
										"title" => __("Label Seperator", $this->plugin), 
										"type" => "text",
										// 'dependentField' => 'numRangeOptions[enable]', 
                    "value" => $this->options()['ui']['labelSeparator'],
										"hint" => ""
									],
									[
										"id" => "numRangeOptions[ui][labelStacking]",
										"title" => __("Label Stacking", $this->plugin), 
										"type" => "select",
										"options" => [
											"horizontal" => "Horizontal",
                      "vertical" => "Vertical",
										],
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" =>$this->options()['ui']['labelStacking'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "numRangeOptions[ui][showRangeValues]",
										"title" => __("Show Range Values", $this->plugin), 
										"type" => "checkbox",
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" => $this->options()['ui']['showRangeValues'] ? true : false,
										"hint" => ""
									],
									[
										"id" => "numRangeOptions[ui][step]",
										"title" => __("Step", $this->plugin), 
										"type" => "number",
										"min" => 0.01,
										"max" => 1000000,
										"step" => 0.01,
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" => $this->options()['ui']['step'],
										"hint" => ""
									],
								],
								[
									[
										"id" => "numRangeOptions[ui][cssClass]",
										"title" => __("CSS Class", $this->plugin), 
										"type" => "text", 
										// 'dependentField' => 'numRangeOptions[enable]',
                    "value" => $this->options()['ui']['cssClass'],
										"hint" => ""
									]
								]
							]
						]
					]
        ],
      ];

		} // END num_range_fields



	} // END Dashboard

}