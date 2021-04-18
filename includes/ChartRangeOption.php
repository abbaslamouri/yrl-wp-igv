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
if (!class_exists('ChartRangeOption')) {

	class ChartRangeOption Extends Dashboard {

	
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

			
    } // END __construct



    public function options() {

			return [

        "filterColumnIndex" => array_keys($this->column_indeces)[0],
        "ui" => [
          "chartType" =>  isset( $this->options['ui']['chartType'] ) ? $this->options['ui']['chartType'] : 'ComboChart',
          "minRangeSize" => isset( $this->options['ui']['minRangeSize'] ) ? $this->options['ui']['minRangeSize'] : null,
          "snapToData" => isset( $this->options['ui']['snapToData'] ) ? true: false,
          "chartOptions" => [
            "width" => isset( $this->options['ui']['chartOptions']['width'] ) ? $this->options['ui']['chartOptions']['width'] : null,
            "height" => isset( $this->options['ui']['chartOptions']['height'] ) ? $this->options['ui']['chartOptions']['height'] : null,
            'enableSeries' => isset( $this->options['ui']['chartOptions']['enableSeries'] ) ? true : false,
            'enableTrendlines' => isset( $this->options['ui']['chartOptions']['enableTrendlines'] ) ? true : false,
            "fontName" => isset( $this->options['ui']['chartOptions']['fontName'] ) ? $this->options['ui']['chartOptions']['fontName'] : 'verdana',
            "fontSize" => isset( $this->options['ui']['chartOptions']['fontSize'] ) ? $this->options['ui']['chartOptions']['fontSize'] : 16,
            "orientation" => isset( $this->options['ui']['chartOptions']['orientation'] ) ? $this->options['ui']['chartOptions']['orientation'] : 'horizontal',
            "lineWidth" => isset( $this->options['ui']['chartOptions']['lineWidth'] ) ? $this->options['ui']['chartOptions']['lineWidth'] : 1,
            "lineDashStyle" => isset( $this->options['ui']['chartOptions']['lineDashStyle'] ) ? $this->options['ui']['chartOptions']['lineDashStyle'] : null,
            "interpolateNulls" =>  isset( $this->options['ui']['chartOptions']['interpolateNulls'] ) ? true : false,
            "reverseCategories" =>  isset( $this->options['ui']['chartOptions']['reverseCategories']) ? true : false,
            "pointsVisible" =>  isset( $this->options['ui']['chartOptions']['pointsVisible'] ) ? true : false,
            "pointShape" => isset( $this->options['ui']['chartOptions']['pointShape'] ) ? $this->options['ui']['chartOptions']['pointShape'] : null,
            "pointSize" => isset( $this->options['ui']['chartOptions']['pointSize'] ) ? $this->options['ui']['chartOptions']['pointSize'] : 10,
            'aggregationTarget' => isset( $this->options['ui']['chartOptions']['aggregationTarget'] ) ? $this->options['ui']['chartOptions']['aggregationTarget'] : null,
            'selectionMode' => isset( $this->options['ui']['chartOptions']['selectionMode'] ) ? $this->options['ui']['chartOptions']['selectionMode'] : 'single',
            "titlePosition" => isset( $this->options['ui']['chartOptions']['titlePosition'] ) ? $this->options['ui']['chartOptions']['titlePosition'] : 'out',
            "title" => isset( $this->options['ui']['chartOptions']['title'] ) ? $this->options['ui']['chartOptions']['title'] : 'Chart Title',

            "backgroundColor" => [
              "fill" => isset( $this->options['ui']['chartOptions']['backgroundColor']['fill'] ) ? $this->options['ui']['chartOptions']['backgroundColor']['fill'] : "#CCC",
              "stroke" => isset( $this->options['ui']['chartOptions']['backgroundColor']['stroke'] ) ? $this->options['ui']['chartOptions']['backgroundColor']['stroke'] : 'green',
              "strokeWidth"	=> isset( $this->options['ui']['chartOptions']['backgroundColor']['strokeWidth'] ) ? $this->options['ui']['chartOptions']['backgroundColor']['strokeWidth'] : 6,
            ],
          
            "titleTextStyle" => [
              "color" => isset( $this->options['ui']['chartOptions']['titleTextStyle']['color'] ) ? $this->options['ui']['chartOptions']['titleTextStyle']['color'] : 'teal',
              "fontName" => isset( $this->options['ui']['chartOptions']['titleTextStyle']['fontName'] ) ? $this->options['ui']['chartOptions']['titleTextStyle']['fontName'] : 'arial',
              "fontSize" => isset( $this->options['ui']['chartOptions']['titleTextStyle']['fontSize'] ) ? $this->options['ui']['chartOptions']['titleTextStyle']['fontSize'] : 16,
              "bold" => isset( $this->options['ui']['chartOptions']['titleTextStyle']['bold'] ) ? true : false,
              "italic" => isset( $this->options['ui']['chartOptions']['titleTextStyle']['italic'] ) ? true : false,
            ],

            "chartArea" => [
              "width" => isset( $this->options['ui']['chartOptions']['chartArea']['width'] ) ? $this->options['ui']['chartOptions']['chartArea']['width'] : null,
              "height" => isset( $this->options['ui']['chartOptions']['chartArea']['height'] ) ? $this->options['ui']['chartOptions']['chartArea']['height'] :null,
              "top" => isset( $this->options['ui']['chartOptions']['chartArea']['top'] ) ? $this->options['ui']['chartOptions']['chartArea']['top'] : null,
              "left" => isset( $this->options['ui']['chartOptions']['chartArea']['left'] ) ? $this->options['ui']['chartOptions']['chartArea']['left'] : null,
              "backgroundColor" => [
                "fill" => isset( $this->options['ui']['chartOptions']['chartArea']['backgroundColor']['fill'] ) ? $this->options['ui']['chartOptions']['chartArea']['backgroundColor']['fill'] : '#ddd',
                "stroke" => isset( $this->options['ui']['chartOptions']['chartArea']['backgroundColor']['stroke'] ) ? $this->options['ui']['chartOptions']['chartArea']['backgroundColor']['stroke'] : 'blue',
                "strokeWidth"	=> isset( $this->options['ui']['chartOptions']['chartArea']['backgroundColor']['strokeWidth'] ) ? $this->options['ui']['chartOptions']['chartArea']['backgroundColor']['strokeWidth'] : 2,
              ],
              
            ],

            "legend" => [
              "alignment"=> isset( $this->options['ui']['chartOptions']['legend']['alignment'] ) ? $this->options['ui']['chartOptions']['legend']['alignment'] : 'right',
              "position"=> isset( $this->options['ui']['chartOptions']['legend']['position'] ) ? $this->options['ui']['chartOptions']['legend']['position'] : 'start',
              "maxLines"=> isset( $this->options['ui']['chartOptions']['legend']['maxLines'] ) ? $this->options['ui']['chartOptions']['legend']['maxLines'] : 10,
              "textStyle" => [
                "color" => isset( $this->options['ui']['chartOptions']['legend']['textStyle']['color'] ) ? $this->options['ui']['chartOptions']['legend']['textStyle']['color'] : 'green',
                "fontName" => isset( $this->options['ui']['chartOptions']['legend']['textStyle']['fontName'] ) ? $this->options['ui']['chartOptions']['legend']['textStyle']['fontName'] : 'arial',
                "fontSize" => isset( $this->options['ui']['chartOptions']['legend']['textStyle']['fontSize'] ) ? $this->options['ui']['chartOptions']['legend']['textStyle']['fontSize'] : 14,
                "bold" => isset( $this->options['ui']['chartOptions']['legend']['textStyle']['bold'] ) ? true : false,
                "italic" => isset( $this->options['ui']['chartOptions']['legend']['textStyle']['italic'] ) ? true: false,
              ],
            ],

            "annotations" => [
              "boxStyle" => [
                "strokeWidth" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['strokeWidth'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['strokeWidth'] : 2,
                "stroke" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['stroke'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['stroke'] : '#CCC',
                "rx" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['rx'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['rx'] : 2,
                "ry" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['ry'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['ry'] : 2,
                "gradient" => [
                  "useObjectBoundingBoxUnits" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['useObjectBoundingBoxUnits'] ) ? true : false,
                  "color1" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['color1'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['color1'] : 'red',
                  "color2" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['color2'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['color2'] : 'green',
                  "x1" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['x1'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['x1'] : 0,
                  "y1" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['y1'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['y1'] : 0,
                  "x2" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['x2'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['x2'] : 100,
                  "y2" => isset( $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['y2'] ) ? $this->options['ui']['chartOptions']['annotations']['boxStyle']['gradient']['y2'] : 100,
                  
                ]
              ]
            ],

            "tooltip" => [
              "text"=> isset( $this->options['ui']['chartOptions']['tooltip']['text'] ) ? $this->options['ui']['chartOptions']['tooltip']['text'] : 'both',
              "showColorCode"=> isset( $this->options['ui']['chartOptions']['tooltip']['showColorCode'] ) ? true : false,
              "trigger" => isset( $this->options['ui']['chartOptions']['tooltip']['trigger'] ) ? $this->options['ui']['chartOptions']['tooltip']['trigger'] : 'focus',
              "textStyle" => [
                "color" => isset( $this->options['ui']['chartOptions']['tooltip']['textStyle']['color'] ) ? $this->options['ui']['chartOptions']['tooltip']['textStyle']['color'] : 'magenta',
                "fontName" => isset( $this->options['ui']['chartOptions']['tooltip']['textStyle']['fontName'] ) ? $this->options['ui']['chartOptions']['tooltip']['textStyle']['fontName'] : 'tahoma',
                "fontSize" => isset( $this->options['ui']['chartOptions']['tooltip']['textStyle']['fontSize'] ) ? $this->options['ui']['chartOptions']['tooltip']['textStyle']['fontSize'] : 12,
                "bold" => isset( $this->options['ui']['chartOptions']['tooltip']['textStyle']['bold'] ) ? true : false,
                "italic" => isset( $this->options['ui']['chartOptions']['tooltip']['textStyle']['italic'] ) ? true : false,
              ],
            ],

            "animation" => [
              "startup" => isset( $this->options['ui']['chartOptions']['animation']['startup'] ) ? true : false,
              "duration" => isset( $this->options['ui']['chartOptions']['animation']['duration'] ) ? $this->options['ui']['chartOptions']['animation']['duration'] : 500,
              "easing" => isset( $this->options['ui']['chartOptions']['animation']['easing'] ) ? $this->options['ui']['chartOptions']['animation']['easing'] : 'inAndOut',
            ],

            "crosshair" => [
              "color" =>isset( $this->options['ui']['chartOptions']['crosshair']['color'] ) ? $this->options['ui']['chartOptions']['crosshair']['color'] : 'magenta',
              "trigger" => isset( $this->options['ui']['chartOptions']['crosshair']['trigger'] ) ? $this->options['ui']['chartOptions']['crosshair']['trigger'] : 'both',
              "opacity" => isset( $this->options['ui']['chartOptions']['crosshair']['opacity'] ) ? $this->options['ui']['chartOptions']['crosshair']['opacity'] : 1,
            ],

          ],
             
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
        "chartRangeFilter" => [
          "id" => "yrl_wp_igv__chartRangeFilter",
          'cssClasses' => ['chartRangeOption', 'chart'],
          "title" => __("Chart Range Filter", $this->plugin),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
          "sections" => [
						"general" => [
							"id" => "general",
							"title" => __("General Settings", $this->plugin),
							"fields" => [
                [
									[
										"id" => "chartRangeOptions[filterColumnIndex]",
										"title" => __("Filter Column", $this->plugin),	
										"type" => "select",
										"options" => $this->column_indeces,
										// "nullOption" => "Chart Orientation",
										"value" => $this->options()['filterColumnIndex'],
									],
                ],
                [
									[
										"id" => "chartRangeOptions[ui][chartType]",
										"title" => __("Chart Type", $this->plugin),	
                    "type" => "select",
                    'options' =>[
                      'AreaChart' => 'AreaChart',
                      'LineChart' => 'LineChart',
                      'ComboChart' => 'ComboChart',
                      'ScatterChart' => 'ScatterChart',
                    ],
										"value" => $this->options()['ui']['chartType'],
									],
                ],
                [
									[
										"id" => "chartRangeOptions[ui][minRangeSize]", 
										"title" => __("Min Range Size", $this->plugin), 
										'suffix' => '%',
										"type" => "number",
										// "min" => 200,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->options()['ui']['minRangeSize'],
										'hint' => ''
									],
									[
                    "id" => "chartRangeOptions[ui][snapToData]",
										'suffix' => 'px',
										"title" => __("Snap to Data", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->options()['ui']['snapToData'],
										'hint' => ''
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][width]", 
										"title" => __("Chart Width", $this->plugin), 
										'suffix' => '%',
										"type" => "number",
										// "min" => 200,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->options()['ui']['chartOptions']['width'],
										'hint' => 'Enter a number representing the cahrt width in percent'
									],
									[
                    "id" => "chartRangeOptions[ui][chartOptions][height]",
										'suffix' => 'px',
										"title" => __("Chart Height", $this->plugin), 
										"type" => "number",
										// "min" => 200,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->options()['ui']['chartOptions']['height'],
										'hint' => 'Enter a number representing the cahrt width in pixels'
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][backgroundColor][stroke]",
										"cssClasses" => ["color-picker"],
										"title" => __("Background Stroke Color", $this->plugin),
										"type" => "color-picker",
										"value" => $this->options()['ui']['chartOptions']['backgroundColor']['stroke'],
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][fontName]",
										"title" => __("Font Name", $this->plugin),	
										"type" => "select",
										"options" =>  $this->font_names,
										"nullOption" => "Select Font",
										"value" => $this->options()['ui']['chartOptions']['fontName'],
									]
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][fontSize]",
										"title" => __("Font Size", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 100,
                    "step" => 1,
										"value" =>$this->options()['fontSize'],
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][backgroundColor][strokeWidth]",
										"title" => __("Bg. Stroke Width", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->options()['ui']['chartOptions']['backgroundColor']['strokeWidth'],
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][orientation]",
										"title" => __("Chart Orientation", $this->plugin),	
										"type" => "select",
										"options" => [
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										],
										// "nullOption" => "Chart Orientation",
										"value" => $this->options()['ui']['chartOptions']['orientation'],
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][backgroundColor][fill]",
										"title" => __("Background Color", $this->plugin),
										"type" => "color-picker", 
										"cssClasses" => ["color-picker"],
										"value" => $this->options()['ui']['chartOptions']['backgroundColor']['fill'],
									],
                ],
                [
									[
										"id" => "chartRangeOptions[ui][chartOptions][interpolateNulls]",
										"title" => __("Interpolate", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['ui']['chartOptions']['interpolateNulls'],
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][reverseCategories]",
										"title" => __("Reverse Cat", $this->plugin),
										"type" => "checkbox",
										"value" => $this->options()['ui']['chartOptions']['reverseCategories'],
									],
									[
                    "id" => "chartRangeOptions[ui][chartOptions][pointsVisible]",
                    'cssClasses' => ['hasDependents'],
                    "title" => __("Points Visible", $this->plugin),
                    'dependents' => ['chartOptions[pointShape]', 'chartOptions[pointSize]'],
                    "type" => "checkbox",
										"value" => $this->options()['ui']['chartOptions']['pointsVisible'],
								  ],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][lineWidth]",
										"title" => __("Line Width", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->options()['ui']['chartOptions']['lineWidth'],
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][lineDashStyle]",
										'cssClasses' => ['array-field'],
										"title" => __("Line Dash Style", $this->plugin),
										"type" => "text",
										"value" => is_array($this->options()['lineDashStyle']) ? implode(",", $this->options()['ui']['chartOptions']['lineDashStyle']) : $this->options()['ui']['chartOptions']['lineDashStyle'] ,
									]
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][pointShape]",
										"title" => __("Ponit Shape", $this->plugin),
										"type" => "select",
										// 'dependentField' => 'chartOptions[pointsVisible]',
										"options" => [
											"circle" => "Circle",
											"triangle" => "Triangle",
											"square" => "Square",
											"diamond" => "Diamond",
											"star" => "Star"
										],
										// "nullOption" => "Point Shape",
										"value" => $this->options()['ui']['chartOptions']['pointShape'],
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][pointSize]",
										"title" => __("Point Size", $this->plugin),
										// 'dependentField' => 'chartOptions[pointsVisible]',
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->options()['ui']['chartOptions']['pointSize'],
									],
								]
              ]
						],
						
						"chartTitle" => [
							"id" => "chartTitle",
							"title" => "Chart Title",
							"fields" => [
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][title]",
										"title" => __("Title", $this->plugin), 
										"type" => "text",
										"value" =>$this->options()['title'],
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][titlePosition]",
										"title" => __("Title Position", $this->plugin), 
										"type" => "select",
										"options" => [
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										],
										"value" =>$this->options()['titlePosition'],
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][titleTextStyle][color]",
										"cssClasses" => ["color-picker"],
										"title" => __("Color", $this->plugin), 
										"type" => "color-picker",
										"value" =>  $this->options()['ui']['chartOptions']['titleTextStyle']['color'],
	
									],
								],

								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][titleTextStyle][fontName]",
										"title" => __("Font", $this->plugin), 
										"type" => "select",
										"options" =>  $this->font_names,
										"nullOption" => "Font Name",
										"value" => $this->options()['ui']['chartOptions']['titleTextStyle']['fontName'],
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][titleTextStyle][fontSize]",
										"title" => __("Size", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" =>$this->options()['titleTextStyle']['fontSize'],
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][titleTextStyle][bold]",
										"title" => __("Bold", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->options()['ui']['chartOptions']['titleTextStyle']['bold'] ? true : false,
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][titleTextStyle][italic]",
										"title" => __("Italic", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->options()['ui']['chartOptions']['titleTextStyle']['italic'] ? true : false,
									],
								]
							]
						],

						"chartArea" => [
							"id" => "chartArea",
							"title" => "Chart Area",
							"fields" => [
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][chartArea][width]", 
										"title" => __("Width", $this->plugin), 
										"type" => "number",
										// "min" => 50,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->options()['ui']['chartOptions']['chartArea']['width'],
										'hint' => 'Enter a number representing the chart width in percent (%)'
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][chartArea][height]",  
										"title" => __("Height", $this->plugin), 
										"type" => "number", 
										// "min" => 50,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->options()['ui']['chartOptions']['chartArea']['height'],
										'hint' => 'Enter a number representing the chart height in percent (%)'
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][chartArea][top]",
										"title" => __("Top Position", $this->plugin), 
										"type" => "number",
										"min" => 0,
										"max" => 1000,
										"step" => 5,
										"value" => $this->options()['ui']['chartOptions']['chartArea']['top'],
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][chartArea][left]", 
										"title" => __("Left Position", $this->plugin), 
										"type" => "number",
										"min" => 0,
										"max" => 1000,
										"step" => 5,
                    "value" =>  $this->options()['ui']['chartOptions']['chartArea']['left'],
                    
									],
									],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][chartArea][backgroundColor][fill]",
										"title" => __("Background Color", $this->plugin), 
										"cssClasses" => ["color-picker"],
										"type" => "color-picker",
										"value" => $this->options()['ui']['chartOptions']['chartArea']['backgroundColor']['fill'], 
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][chartArea][backgroundColor][stroke]",
										"title" => __("Stroke Color", $this->plugin),  
										"cssClasses" => ["color-picker"],
										"type" => "color-picker",
										"value" => $this->options()['ui']['chartOptions']['chartArea']['backgroundColor']['stroke'], 
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][chartArea][backgroundColor][strokeWidth]", 
										"title" => __("Stroke Width", $this->plugin),  
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->options()['ui']['chartOptions']['chartArea']['backgroundColor']['strokeWidth'], 
									]
								],
							]
						],

						"legend" => [
							"id" => "legend",
							"title" => "Legend",
							"fields" => [
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][alignment]", 
										"title" => __("Alignment", $this->plugin), 
										"type" => "select", 
										"options" => [
											"start" => "Start",
											"center" => "Center",
											"end"  => "End"
										],
										"value" => $this->options()['ui']['chartOptions']['legend']['alignment'], 
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][position]",
										"title" => __("Position", $this->plugin), 
										"type" => "select", 
										"options" => [
                      "right" => "Right",
                      "left" => "Left",
                      "top" => "Above",
                      "bottom" => "Below",
                      "in" => "Inside",
                      "labeled" => "Connected",
                      "none" => "Omit",	
                    ],
                    "value" => $this->options()['ui']['chartOptions']['legend']['position'],
                    "hint" => "Position of the legend. Can be one of the following:<br>
										<ul>
											<li>bottom' - Below the chart[
											<li>'left' - To the left of the chart, provided the left axis has no series associated with it. So if you want the legend on the left, use the option targetAxisIndex: 1</li>
											<li>'in' - Inside the chart, by the top left corner</li>
											<li>'none' - No legend is displayed</li>
											<li>'right' - To the right of the chart[ Incompatible with the vAxes option</li>
											<li>'top' - Above the chart</li>
										</ul>", 
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][maxLines]",
										"title" => __("Maximum Number of Lines", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" =>$this->options()['legend']['maxLines'],  
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][textStyle][color]", 
										"title" => __("Color", $this->plugin), 
										"cssClasses" => ["color-picker"],
										"type" => "color-picker",
										"value" => $this->options()['ui']['chartOptions']['legend']['textStyle']['color'],
									],
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][textStyle][fontName]",
										"title" => __("Font Name", $this->plugin), 
										"type" => "select", 
										"options" =>  $this->font_names,
										"nullOption" => "Font Name",
										"value" => $this->options()['ui']['chartOptions']['legend']['textStyle']['fontName'],
								],
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][textStyle][fontSize]",
										"title" => __("Font Size", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->options()['ui']['chartOptions']['legend']['textStyle']['fontSize'],
									]
								],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][textStyle][bold]",
										"title" => __("Bold", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->options()['ui']['chartOptions']['legend']['textStyle']['bold'] ? true : false, 
									],
									[
										"id" => "chartRangeOptions[ui][chartOptions][legend][textStyle][italic]",  
										"title" => __("Italic", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->options()['ui']['chartOptions']['legend']['textStyle']['italic'] ? true : null,  
									]
								]
							]
						],

            "annotations" => [
							"id" => "annotations",
							"title" => "Annotations",
							"fields" => [
								[
									[
                    "id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][strokeWidth]",
										"title" => __("Box Stroke Width", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 100,
                    "step" => 1,
										"value" =>$this->options()['annotations']['boxStyle']['strokeWidth'],
										"hint" => ""
                  ],
									[
                    "id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][gradient][useObjectBoundingBoxUnits]",
										"title" => __("Object Bounding Unit", $this->plugin),
										"type" => "checkbox", 
										"value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['gradient']['useObjectBoundingBoxUnits'] ? true : false
									]
                ],
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][stroke]", 
                    "cssClasses" => ["color-picker"],
                    "title" => __("Stroke Color", $this->plugin), 
                    "type" => "color-picker",
                    "value" =>  $this->options()['ui']['chartOptions']['annotations']['boxStyle']['stroke'],
                  ]
                ],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][rx]",
										"title" => __("Box X-radius", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 1000,
                    "step" => 5,
										"value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['rx'],
                  ],
									[
										"id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][ry]",
										"title" => __("Box Y-radius", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 1000,
                    "step" => 5,
										"value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['ry'],
									]
                ],
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][gradient][color1]", 
                    "cssClasses" => ["color-picker"],
                    "title" => __("Annotations Gradient 1", $this->plugin), 
                    "type" => "color-picker",
                    "value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['gradient']['color1'],
                  ],
                ],
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][gradient][color2]",
                    "cssClasses" => ["color-picker"],
                    "title" => __("Annotations Gradient 2", $this->plugin), 
                    "type" => "color-picker",
                    "value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['gradient']['color2'],
                  ],
                ],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][gradient][x1]",  
										"title" => __("Gradient X Start", $this->plugin), 
                    "type" => "number",
										"suffix" => "%",
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['gradient']['x1'],
                    'hint' => 'pppppppppp'
                  ],
									[
										"id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][gradient][y1]",  
										"title" => __("Gradient Y Start", $this->plugin), 
                    "type" => "number",
										"suffix" => "%",
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" =>  $this->options()['ui']['chartOptions']['annotations']['boxStyle']['gradient']['y1'],
                    'hint' => 'pppppppppp'
									]
                ],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][gradient][x2]",  
										"title" => __("Gradient X End", $this->plugin),
                    "type" => "number",
										"suffix" => "%",
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['gradient']['x2'],
                    'hint' => 'pppppppppp'
                  ],
									[
										"id" => "chartRangeOptions[ui][chartOptions][annotations][boxStyle][gradient][y2]",  
										"title" => __("Gradient Y End", $this->plugin), 
                    "type" => "number",
										"suffix" => "%", 
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" => $this->options()['ui']['chartOptions']['annotations']['boxStyle']['gradient']['y2'],
                    'hint' => 'pppppppppp'
									]
								]
							]	
            ],

            "animation" => [
							"id" => "animation",
							"title" => "Animation",
							"fields" => [
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][animation][startup]",
										"title" => __("Startup", $this->plugin),
                    "type" => "checkbox", 
                    "value" => $this->options()['ui']['chartOptions']['animation']['startup'],
                    'hint' => 'pppppppppp'
                  ],
									[
										"id" => "chartRangeOptions[ui][chartOptions][animation][duration]",
										"title" => __("Duration", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 10000,
                    "step" => 100,
                    "value" => $this->options()['ui']['chartOptions']['animation']['duration'], 
                  ],
                ],
								[
									[//"slug" => "chart_title", // field id
										"id" => "chartRangeOptions[ui][chartOptions][animation][easing]",
										"title" => __("Easing", $this->plugin),
										"type" => "select", 
										"options" => [
											"linear" => "Linear",
											"in" => "In",
											"out" => "Out",
											"inAndOut" => "In and Out",
                    ],
                    "value" => $this->options()['ui']['chartOptions']['animation']['easing'], 
									],
								]
							]
            ],
            
						"crosshair" => [
							"id" => "crosshair",
							"title" => "Crosshair",
							"fields" => [
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][crosshair][opacity]",  
										"title" => __("Opacity", $this->plugin), 
										"type" => "number",
										"min" => 0,
										"max" => 1,
                    "step" => .1,
                    "value" => $this->options()['ui']['chartOptions']['crosshair']['opacity'], 
                  ],
									[
										"id" => "chartRangeOptions[ui][chartOptions][crosshair][trigger]", 
										"title" => __("Trigger", $this->plugin), 
										"type" => "select", 
										"options" => [
											"focus" => "Focus",
											"selection" => "Selection",
											"both" => "Both",
                    ],
                    "value" => $this->options()['ui']['chartOptions']['crosshair']['trigger'], 
                  ],
                ],
								[
									[
										"id" => "chartRangeOptions[ui][chartOptions][crosshair][color]", 
										"title" => __("Color", $this->plugin), 
                    "type" => "color-picker",
                    "cssClasses" => ["color-picker"],
                    "value" => $this->options()['ui']['chartOptions']['crosshair']['color'], 

									],
								],
							]
            ],

            "tooltips" => [
              "id" => "tooltips",
              "title" => __('Tooltips', $this->plugin),
              "fields" => [
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][selectionMode]",
                    "title" => __("Selection Mode", $this->plugin),
                    "type" => "select",
                    "options" => [
                      "single" => "Single",
                      "multiple" => "Multiple",
                    ],
                    "value" => $this->options()['ui']['chartOptions']['selectionMode'],
                    "hint" => "When selectionMode is 'multiple', users may select multiple data points."
                  ],
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][aggregationTarget]",
                    "title" => __("Aggregation Target", $this->plugin),
                    "type" => "select",
                    "options" => [
                      "category" => "Category",
                      "series" => "Series",
                      "auto" => "Auto",
                      "none" => "None",
                    ],
                    "value" => $this->options()['ui']['chartOptions']['aggregationTarget'],
                    "hint" => <<<ENDHTML
<p>How multiple data selections are rolled up into tooltips</p>
<ul>
<li>- <strong>category</strong>: Group selected data by x-value</li>
<li>- <strong>series</strong>: Group selected data by series</li>
<li>- <strong>auto</strong>: Group selected data by x-value if all selections have the same x-value, and by series otherwise</li>
<li>- <strong>none</strong>: Show only one tooltip per selection</li>
</ul>
<p><strong>aggregationTarget</strong> will often be used in tandem with <strong>selectionMode</strong> and <strong>tooltip.trigger</strong></p>
ENDHTML
                  ],
                ],
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][trigger]",
                    "title" => __("Trigger", $this->plugin),
                    "type" => "select",
                    "options" => [
                      "focus" => "Hover",
                      "selection" => "Selection",
                      "none" => "None",
                    ],
                    "value" => $this->options()['ui']['chartOptions']['tooltip']['trigger'],
                  ],
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][text]",
                    "title" => __("Text", $this->plugin),
                    "submenuPage" => "{$this->prefix}_dashboard",	
                    "type" => "select",
                    "options" => [
                      "both" => "Value and Percentage",
                      "value" => "Value",
                      "percentage" => "Percentage",
                    ],
                    "value" => $this->options()['ui']['chartOptions']['tooltip']['text'],
                  ],
                ],
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][textStyle][fontName]",
                    "title" => __("Font", $this->plugin),
                    "type" => "select",
                    "options" =>  $this->font_names,
                    "value" => $this->options()['ui']['chartOptions']['tooltip']['textStyle']['fontName'],
                  ],
                  
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][textStyle][fontSize]",
                    "title" => __("Size", $this->plugin),
                    "type" => "number",
                    "value" => $this->options()['ui']['chartOptions']['tooltip']['textStyle']['fontSize'],
                  ],
                ],
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][textStyle][color]",
                    "cssClasses" => ["color-picker"],
                    "title" => __("Color", $this->plugin),
                    "type" => "color-picker",
                    "value" => $this->options()['ui']['chartOptions']['tooltip']['textStyle']['color'],
                  ],
                ],
                [
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][textStyle][bold]",
                    "title" => __("Bold", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['ui']['chartOptions']['tooltip']['textStyle']['bold'] ? true : false,
                  ],
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][textStyle][italic]",
                    "title" => __("Italic", $this->plugin),
                    "type" => "checkbox",
                    'value' => $this->options()['ui']['chartOptions']['tooltip']['textStyle']['italic'] ? true : false,
                    "hint" => "How multiple data selections are rolled up into tooltips:<br>
                      <ul>
                      <li>- <strong>category</strong>: Group selected data by x-value</li>
                      <li>- <strong>series</strong>: Group selected data by series</li>
                      <li>- <strong>auto</strong>: Group selected data by x-value if all selections have the same x-value, and by series otherwise</li>
                      <li>- none: Show only one tooltip per selection</li>
                      </ul>"
                  ],
                  [
                    "id" => "chartRangeOptions[ui][chartOptions][tooltip][showColorCode]",
                    "title" => __("Show Color Code", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['ui']['chartOptions']['tooltip']['showColorCode'] ? true : false,
                  ],
                ]
              ]
            ]	
          ]
        ],
      ];

		} // END num_range_fields



	} // END Dashboard

}