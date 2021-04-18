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
if (!class_exists('ChartLayout')) {

	class ChartLayout Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($layout = [], $chart_type = null) {

      $this->layout = $layout;
      $this->chart_type = $chart_type;
      // $this->settings = $settings;
      
    } // END __construct


		
  

    public function layout() {


			return [ 

				"paper_bgcolor" => isset( $this->layout['paper_bgcolor'] ) ? $this->layout['paper_bgcolor']: "#CCC",
				"plot_bgcolor" => isset( $this->layout['plot_bgcolor'] ) ? $this->layout['plot_bgcolor'] : "#FFF",
				"height" => isset( $this->layout['height'] ) ? $this->layout['height'] : 400,
				"autosize" => isset( $this->layout['autosize'] ) ? $this->layout['autosize'] : true,
				"title" => [
					"text" => isset( $this->layout['title']['text'] ) ? $this->layout['title']['text'] : 'Hello World',
					"x"    => isset( $this->layout['title']['x'] ) ? $this->layout['title']['x'] : 0.05,
					"y"    => isset( $this->layout['title']['y'] ) ? $this->layout['title']['y'] : 0.9,
					"font" => [
						"family" => isset( $this->layout['title']['font']['family'] ) ? $this->layout['title']['font']['family'] : 'arial',
						"size" => isset( $this->layout['title']['font']['size'] ) ? $this->layout['title']['font']['size'] : 40,
						"color" => isset( $this->layout['title']['font']['color'] ) ? $this->layout['title']['font']['color'] : "teal",
					]
				],
				"xaxis" => [
					"automargin" => isset( $this->layout['xaxis']['automargin'] ) ? $this->layout['xaxis']['automargin'] : true,
					"rangeslider" => [
						"visible" => isset( $this->layout['xaxis']['rangeslider']['visible'] ) ? $this->layout['xaxis']['rangeslider']['visible'] : false,
						"bgcolor" => isset( $this->layout['xaxis']['rangeslider']['bgcolor'] ) ? $this->layout['xaxis']['rangeslider']['bgcolor'] : "teal",
						"thickness" => isset( $this->layout['xaxis']['rangeslider']['thickness'] ) ? $this->layout['xaxis']['rangeslider']['thickness'] : 0.2,
					]
				],
				"yaxis" => [
					"fixedrange" => isset( $this->layout['yaxis']['fixedrange'] ) ? $this->layout['yaxis']['fixedrange'] : true,
				],



        // "width" => isset( $this->layout['width'] ) ? $this->layout['width'] : 100,
        // "widthSuffix" => isset( $this->layout['widthSuffix'] ) ? $this->layout['widthSuffix'] : 'percent',
			
				
				// "connectgaps" => isset( $this->layout['connectgaps'] ) ? $this->layout['connectgaps']: false,
			
				// "reverseCategories" =>  isset( $this->layout['reverseCategories']) ? true : false,
        // "heightSuffix" => isset( $this->layout['heightSuffix'] ) ? $this->layout['heightSuffix'] : 'pixels',
        // 'enableSeries' => isset( $this->layout['enableSeries'] ) ? true : false,
        // 'enableTrendlines' => isset( $this->layout['enableTrendlines'] ) ? true : false,
        // "orientation" => isset( $this->layout['orientation'] ) ? $this->layout['orientation'] : 'horizontal',
        // "lineWidth" => isset( $this->layout['lineWidth'] ) ? $this->layout['lineWidth'] : 1,
        // "lineDashStyle" => isset( $this->layout['lineDashStyle'] ) ? $this->layout['lineDashStyle'] : [4,7],
        // "interpolateNulls" =>  isset( $this->layout['interpolateNulls'] ) ? true : false,
        // "reverseCategories" =>  isset( $this->layout['reverseCategories']) ? true : false,
        // "pointsVisible" =>  isset( $this->layout['pointsVisible'] ) ? true : false,
        // "pointShape" => isset( $this->layout['pointShape'] ) ? $this->layout['pointShape'] : null,
        // "pointSize" => isset( $this->layout['pointSize'] ) ? $this->layout['pointSize'] : 10,
        // 'aggregationTarget' => isset( $this->layout['aggregationTarget'] ) ? $this->layout['aggregationTarget'] : null,
        // 'selectionMode' => isset( $this->layout['selectionMode'] ) ? $this->layout['selectionMode'] : 'single',
        // "titlePosition" => isset( $this->layout['titlePosition'] ) ? $this->layout['titlePosition'] : 'out',
        // "title" => isset( $this->layout['title'] ) ? $this->layout['title'] : 'Chart Title',

        // "backgroundColor" => [
        //   "fill" => isset( $this->layout['backgroundColor']['fill'] ) ? $this->layout['backgroundColor']['fill'] : "#CCC",
        //   "stroke" => isset( $this->layout['backgroundColor']['stroke'] ) ? $this->layout['backgroundColor']['stroke'] : 'green',
        //   "strokeWidth"	=> isset( $this->layout['backgroundColor']['strokeWidth'] ) ? $this->layout['backgroundColor']['strokeWidth'] : 6,
        // ],
       
        // "titleTextStyle" => [
        //   "color" => isset( $this->layout['titleTextStyle']['color'] ) ? $this->layout['titleTextStyle']['color'] : 'teal',
        //   "fontName" => isset( $this->layout['titleTextStyle']['fontName'] ) ? $this->layout['titleTextStyle']['fontName'] : 'arial',
        //   "fontSize" => isset( $this->layout['titleTextStyle']['fontSize'] ) ? $this->layout['titleTextStyle']['fontSize'] : 16,
        //   "bold" => isset( $this->layout['titleTextStyle']['bold'] ) ? true : false,
        //   "italic" => isset( $this->layout['titleTextStyle']['italic'] ) ? true : false,
        // ],

        // "chartArea" => [
        //   "width" => isset( $this->layout['chartArea']['width'] ) ? $this->layout['chartArea']['width'] : 65,
        //   "height" => isset( $this->layout['chartArea']['height'] ) ? $this->layout['chartArea']['height'] : 65,
        //   "top" => isset( $this->layout['chartArea']['top'] ) ? $this->layout['chartArea']['top'] : 80,
        //   "left" => isset( $this->layout['chartArea']['left'] ) ? $this->layout['chartArea']['left'] : 105,
        //   "backgroundColor" => [
        //     "fill" => isset( $this->layout['chartArea']['backgroundColor']['fill'] ) ? $this->layout['chartArea']['backgroundColor']['fill'] : '#ddd',
        //     "stroke" => isset( $this->layout['chartArea']['backgroundColor']['stroke'] ) ? $this->layout['chartArea']['backgroundColor']['stroke'] : 'blue',
        //     "strokeWidth"	=> isset( $this->layout['chartArea']['backgroundColor']['strokeWidth'] ) ? $this->layout['chartArea']['backgroundColor']['strokeWidth'] : 2,
        //   ],
          
        // ],

        // "legend" => [
        //   "alignment"=> isset( $this->layout['legend']['alignment'] ) ? $this->layout['legend']['alignment'] : 'center',
        //   "position"=> isset( $this->layout['legend']['position'] ) ? $this->layout['legend']['position'] : 'rigth',
        //   "maxLines"=> isset( $this->layout['legend']['maxLines'] ) ? $this->layout['legend']['maxLines'] : 10,
        //   "textStyle" => [
        //     "color" => isset( $this->layout['legend']['textStyle']['color'] ) ? $this->layout['legend']['textStyle']['color'] : 'green',
        //     "fontName" => isset( $this->layout['legend']['textStyle']['fontName'] ) ? $this->layout['legend']['textStyle']['fontName'] : 'arial',
        //     "fontSize" => isset( $this->layout['legend']['textStyle']['fontSize'] ) ? $this->layout['legend']['textStyle']['fontSize'] : 14,
        //     "bold" => isset( $this->layout['legend']['textStyle']['bold'] ) ? true : false,
        //     "italic" => isset( $this->layout['legend']['textStyle']['italic'] ) ? true: false,
        //   ],
				// ],
				
				// "fontName" => isset( $this->layout['fontName'] ) ? $this->layout['fontName'] : 'verdana',
        // "fontSize" => isset( $this->layout['fontSize'] ) ? $this->layout['fontSize'] : 16,
        // "annotations" => [
        //   "boxStyle" => [
        //     "strokeWidth" => isset( $this->layout['annotations']['boxStyle']['strokeWidth'] ) ? $this->layout['annotations']['boxStyle']['strokeWidth'] : 2,
        //     "stroke" => isset( $this->layout['annotations']['boxStyle']['stroke'] ) ? $this->layout['annotations']['boxStyle']['stroke'] : '#CCC',
        //     "rx" => isset( $this->layout['annotations']['boxStyle']['rx'] ) ? $this->layout['annotations']['boxStyle']['rx'] : 2,
        //     "ry" => isset( $this->layout['annotations']['boxStyle']['ry'] ) ? $this->layout['annotations']['boxStyle']['ry'] : 2,
        //     "gradient" => [
        //       "useObjectBoundingBoxUnits" => isset( $this->layout['annotations']['boxStyle']['gradient']['useObjectBoundingBoxUnits'] ) ? true : false,
        //       "color1" => isset( $this->layout['annotations']['boxStyle']['gradient']['color1'] ) ? $this->layout['annotations']['boxStyle']['gradient']['color1'] : 'red',
        //       "color2" => isset( $this->layout['annotations']['boxStyle']['gradient']['color2'] ) ? $this->layout['annotations']['boxStyle']['gradient']['color2'] : 'green',
        //       "x1" => isset( $this->layout['annotations']['boxStyle']['gradient']['x1'] ) ? $this->layout['annotations']['boxStyle']['gradient']['x1'] : 0,
        //       "y1" => isset( $this->layout['annotations']['boxStyle']['gradient']['y1'] ) ? $this->layout['annotations']['boxStyle']['gradient']['y1'] : 0,
        //       "x2" => isset( $this->layout['annotations']['boxStyle']['gradient']['x2'] ) ? $this->layout['annotations']['boxStyle']['gradient']['x2'] : 100,
        //       "y2" => isset( $this->layout['annotations']['boxStyle']['gradient']['y2'] ) ? $this->layout['annotations']['boxStyle']['gradient']['y2'] : 100, 
        //     ]
				// 	],
				// 	'textStyle'=> [
				// 		'fontName'=> isset( $this->layout['annotations']['textStyle']['fontName'] ) ? $this->layout['annotations']['textStyle']['fontName'] : 'Times-Roman',
				// 		'fontSize'=> isset( $this->layout['annotations']['textStyle']['fontSize'] ) ? $this->layout['annotations']['textStyle']['fontSize'] : 18,
				// 		'bold'=> isset( $this->layout['annotations']['textStyle']['bold'] ) ? $this->layout['annotations']['textStyle']['bold'] : true,
				// 		'italic'=> isset( $this->layout['annotations']['textStyle']['italic'] ) ? $this->layout['annotations']['textStyle']['italic'] : true,
				// 		'color'=> isset( $this->layout['annotations']['textStyle']['color'] ) ? $this->layout['annotations']['textStyle']['color'] : '#871b47',
				// 		'auraColor'=> isset( $this->layout['annotations']['textStyle']['auraColor'] ) ? $this->layout['annotations']['textStyle']['auraColor'] : '#d799ae',
				// 		'opacity'=> isset( $this->layout['annotations']['textStyle']['opacity'] ) ? $this->layout['annotations']['textStyle']['opacity'] : 0.8
				// 	]		
        // ],

        // "tooltip" => [
				// 	"text"=> isset( $this->layout['tooltip']['text'] ) ? $this->layout['tooltip']['text'] : 'both',
				// 	"showColorCode"=> isset( $this->layout['tooltip']['showColorCode'] ) ? true : false,
        //   "trigger" => isset( $this->layout['tooltip']['trigger'] ) ? $this->layout['tooltip']['trigger'] : 'focus',
        //   "ignoreBounds" => isset( $this->layout['tooltip']['ignoreBounds'] ) ? $this->layout['tooltip']['ignoreBounds'] : 'focus',
				// 	"textStyle" => [
				// 		"color" => isset( $this->layout['tooltip']['textStyle']['color'] ) ? $this->layout['tooltip']['textStyle']['color'] : 'magenta',
				// 		"fontName" => isset( $this->layout['tooltip']['textStyle']['fontName'] ) ? $this->layout['tooltip']['textStyle']['fontName'] : 'tahoma',
				// 		"fontSize" => isset( $this->layout['tooltip']['textStyle']['fontSize'] ) ? $this->layout['tooltip']['textStyle']['fontSize'] : 12,
				// 		"bold" => isset( $this->layout['tooltip']['textStyle']['bold'] ) ? true : false,
				// 		"italic" => isset( $this->layout['tooltip']['textStyle']['italic'] ) ? true : false,
        //   ],
        // ],

        // "animation" => [
				// 	"startup" => isset( $this->layout['animation']['startup'] ) ? true : false,
				// 	"duration" => isset( $this->layout['animation']['duration'] ) ? $this->layout['animation']['duration'] : 500,
				// 	"easing" => isset( $this->layout['animation']['easing'] ) ? $this->layout['animation']['easing'] : 'inAndOut',
        // ],

				// "crosshair" => [
				// 	"color" =>isset( $this->layout['crosshair']['color'] ) ? $this->layout['crosshair']['color'] : 'magenta',
				// 	"trigger" => isset( $this->layout['crosshair']['trigger'] ) ? $this->layout['crosshair']['trigger'] : 'both',
				// 	"opacity" => isset( $this->layout['crosshair']['opacity'] ) ? $this->layout['crosshair']['opacity'] : 1,
        // ],

      ];
    }


   




		/**
		 * Admin fields
		 *
		 * @return void
		 */
		public function fields(){

			return [

        "chartLayout" => [
					"id" => "yrl_wp_igv__chartLayoutPanel",
					'cssClasses' => ['chartOption', 'chart'],
					"title" => __("Chart Layout", $this->plugin),
					"sections" => [
						"general" => [
							"id" => "general",
							"title" => __("General Layout", $this->plugin),
							"fields" => [
								[
									[
										"id" => "chartLayout[width]", 
										"title" => __("Chart Width", $this->plugin), 
										'suffix' => '%',
										// 'suffixId' => 'chartOptions[widthSuffix]',
										// 'suffixVal' => $this->layout()['widthSuffix'],
										
										"type" => "number",
										// "min" => 200,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->layout()['width'],
										'hint' => 'Enter a number representing the cahrt width in percent'
									],
									[
                    "id" => "chartLayout[height]",
										'suffix' => 'px',
										// 'suffixId' => "chartLayout[heightSuffix]",
										// 'suffixVal' => $this->layout()['heightSuffix'],
										"title" => __("Chart Height", $this->plugin), 
										"type" => "number",
										// "min" => 200,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->layout()['height'],
										'hint' => 'Enter a number representing the cahrt width in pixels'
									],
								],
								[
									[
										"id" => "chartLayout[backgroundColor][stroke]",
										"cssClasses" => ["color-picker"],
										"title" => __("Background Stroke Color", $this->plugin),
										"type" => "color-picker",
										"value" => $this->layout()['backgroundColor']['stroke'],
									],
								],
								[
									[
										"id" => "chartLayout[backgroundColor][strokeWidth]",
										"title" => __("Bg. Stroke Width", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->layout()['backgroundColor']['strokeWidth'],
									],
								],
								[
									[
										"id" => "chartLayout[orientation]",
										"title" => __("Chart Orientation", $this->plugin),	
										"type" => "select",
										"options" => [
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										],
										// "nullOption" => "Chart Orientation",
										"value" => $this->layout()['orientation'],
									],
								],
								[
									[
										"id" => "chartLayout[backgroundColor][fill]",
										"title" => __("Background Color", $this->plugin),
										"type" => "color-picker", 
										"cssClasses" => ["color-picker"],
										"value" => $this->layout()['backgroundColor']['fill'],
									],
								],
								[
									[
										"id" => "chartLayout[lineWidth]",
										"title" => __("Line Width", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->layout()['lineWidth'],
									],
									[
										"id" => "chartLayout[lineDashStyle]",
										'cssClasses' => ['array-field'],
										"title" => __("Line Dash Style", $this->plugin),
										"type" => "text",
										"value" => is_array($this->layout()['lineDashStyle']) ? implode(",", $this->layout()['lineDashStyle']) : $this->layout()['lineDashStyle'] ,
									]
								],
                [
									[
										"id" => "chartLayout[connectgaps]",
										"title" => __("Interpolate", $this->plugin),
										"type" => "checkbox",
										"value" => $this->layout()['connectgaps'],
									],
									[
										"id" => "chartLayout[reverseCategories]",
										"title" => __("Reverse Cat", $this->plugin),
										"type" => "checkbox",
										"value" => $this->layout()['reverseCategories'],
									],
									[
                    "id" => "chartLayout[pointsVisible]",
                    'cssClasses' => ['hasDependents'],
                    "title" => __("Points Visible", $this->plugin),
                    'dependents' => ['chartOptions[pointShape]', 'chartOptions[pointSize]'],
                    "type" => "checkbox",
										"value" => $this->layout()['pointsVisible'],
								  ],
								],
								[
									[
										"id" => "chartLayout[pointShape]",
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
										"value" => $this->layout()['pointShape'],
									],
									[
										"id" => "chartLayout[pointSize]",
										"title" => __("Point Size", $this->plugin),
										// 'dependentField' => 'chartOptions[pointsVisible]',
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->layout()['pointSize'],
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
										"id" => "chartLayout[title][text]",
										"title" => __("Title", $this->plugin), 
										"type" => "text",
										"value" =>$this->layout()['title']['text'],
									],
								],
								[
									[
										"id" => "chartLayout[titlePosition]",
										"title" => __("Title Position", $this->plugin), 
										"type" => "select",
										"options" => [
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										],
										"value" =>$this->layout()['titlePosition'],
									],
								],
								[
									[
										"id" => "chartLayout[title][font][color]",
										"cssClasses" => ["color-picker"],
										"title" => __("Color", $this->plugin), 
										"type" => "color-picker",
										"value" =>  $this->layout()['title']['font']['color'],
	
									],
								],

								[
									[
										"id" => "chartLayout[titleTextStyle][fontName]",
										"title" => __("Font", $this->plugin), 
										"type" => "select",
										"options" =>  $this->font_names,
										"nullOption" => "Font Name",
										"value" => $this->layout()['titleTextStyle']['fontName'],
									],
									[
										"id" => "chartLayout[titleTextStyle][fontSize]",
										"title" => __("Size", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" =>$this->layout()['titleTextStyle']['fontSize'],
									],
								],
								[
									[
										"id" => "chartLayout[titleTextStyle][bold]",
										"title" => __("Bold", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->layout()['titleTextStyle']['bold'] ? true : false,
									],
									[
										"id" => "chartLayout[titleTextStyle][italic]",
										"title" => __("Italic", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->layout()['titleTextStyle']['italic'] ? true : false,
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
										"id" => "chartLayout[chartArea][width]", 
										"title" => __("Width", $this->plugin), 
										"type" => "number",
										// "min" => 50,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->layout()['chartArea']['width'],
										'hint' => 'Enter a number representing the chart width in percent (%)'
									],
									[
										"id" => "chartLayout[chartArea][height]",  
										"title" => __("Height", $this->plugin), 
										"type" => "number", 
										// "min" => 50,
										// "max" => 2000,
										// "step" => 10,
										"value" => $this->layout()['chartArea']['height'],
										'hint' => 'Enter a number representing the chart height in percent (%)'
									],
								],
								[
									[
										"id" => "chartLayout[chartArea][top]",
										"title" => __("Top Position", $this->plugin), 
										"type" => "number",
										"min" => 0,
										"max" => 1000,
										"step" => 5,
										"value" => $this->layout()['chartArea']['top'],
									],
									[
										"id" => "chartLayout[chartArea][left]", 
										"title" => __("Left Position", $this->plugin), 
										"type" => "number",
										"min" => 0,
										"max" => 1000,
										"step" => 5,
                    "value" =>  $this->layout()['chartArea']['left'],
                    
									],
									],
								[
									[
										"id" => "chartLayout[chartArea][backgroundColor][fill]",
										"title" => __("Background Color", $this->plugin), 
										"cssClasses" => ["color-picker"],
										"type" => "color-picker",
										"value" => $this->layout()['chartArea']['backgroundColor']['fill'], 
									],
								],
								[
									[
										"id" => "chartLayout[chartArea][backgroundColor][stroke]",
										"title" => __("Stroke Color", $this->plugin),  
										"cssClasses" => ["color-picker"],
										"type" => "color-picker",
										"value" => $this->layout()['chartArea']['backgroundColor']['stroke'], 
									],
								],
								[
									[
										"id" => "chartLayout[chartArea][backgroundColor][strokeWidth]", 
										"title" => __("Stroke Width", $this->plugin),  
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->layout()['chartArea']['backgroundColor']['strokeWidth'], 
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
										"id" => "chartLayout[legend][alignment]", 
										"title" => __("Alignment", $this->plugin), 
										"type" => "select", 
										"options" => [
											"start" => "Start",
											"center" => "Center",
											"end"  => "End"
										],
										"value" => $this->layout()['legend']['alignment'], 
									],
									[
										"id" => "chartLayout[legend][position]",
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
                    "value" => $this->layout()['legend']['position'],
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
										"id" => "chartLayout[legend][maxLines]",
										"title" => __("Maximum Number of Lines", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" =>$this->layout()['legend']['maxLines'],  
									],
								],
								[
									[
										"id" => "chartLayout[legend][textStyle][color]", 
										"title" => __("Color", $this->plugin), 
										"cssClasses" => ["color-picker"],
										"type" => "color-picker",
										"value" => $this->layout()['legend']['textStyle']['color'],
									],
								],
								[
									[
										"id" => "chartLayout[legend][textStyle][fontName]",
										"title" => __("Font Name", $this->plugin), 
										"type" => "select", 
										"options" =>  $this->font_names,
										"nullOption" => "Font Name",
										"value" => $this->layout()['legend']['textStyle']['fontName'],
								],
									[
										"id" => "chartLayout[legend][textStyle][fontSize]",
										"title" => __("Font Size", $this->plugin), 
                    "type" => "number",
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
										"value" => $this->layout()['legend']['textStyle']['fontSize'],
									]
								],
								[
									[
										"id" => "chartLayout[legend][textStyle][bold]",
										"title" => __("Bold", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->layout()['legend']['textStyle']['bold'] ? true : false, 
									],
									[
										"id" => "chartLayout[legend][textStyle][italic]",  
										"title" => __("Italic", $this->plugin), 
										"type" => "checkbox",
										"value" => $this->layout()['legend']['textStyle']['italic'] ? true : null,  
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
                    "id" => "chartLayout[annotations][textStyle][fontName]",
                    "title" => __("Font Name", $this->plugin),
                    "type" => "select",
                    "options" =>  $this->font_names,
                    "value" => $this->layout()['annotations']['textStyle']['fontName'],
                  ],
                  [
                    "id" => "chartLayout[annotations][textStyle][fontSize]",
                    "title" => __("Font Size", $this->plugin),
                    "type" => "number",
                    "value" => $this->layout()['annotations']['textStyle']['fontSize'],
                  ],
                ],
                [
                  [
                    "id" => "chartLayout[annotations][textStyle][color]",
                    "cssClasses" => ["color-picker"],
                    "title" => __("Color", $this->plugin),
                    "type" => "color-picker",
                    "value" => $this->layout()['annotations']['textStyle']['color'],
                  ],
								],
								[
                  [
										"id" => "chartLayout[annotations][textStyle][auraColor]",
										"cssClasses" => ["color-picker"],
                    "title" => __("Aura Color", $this->plugin),
                    "type" => "color-picker",
                    "value" => $this->layout()['annotations']['textStyle']['auraColor'],
                  ],
                  [
										"id" => "chartLayout[annotations][textStyle][opacity]",
                    "title" => __("Opacity", $this->plugin),
                    "type" => "text",
                    "value" => $this->layout()['annotations']['textStyle']['opacity'] ,
									],
								],
                [
                  [
                    "id" => "chartLayout[annotations][textStyle][bold]",
                    "title" => __("Bold", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->layout()['annotations']['textStyle']['bold'],
                  ],
                  [
                    "id" => "chartLayout[annotations][textStyle][italic]",
                    "title" => __("Italic", $this->plugin),
                    "type" => "checkbox",
                    'value' => $this->layout()['annotations']['textStyle']['italic'] ? true : false,
                    "hint" => "How multiple data selections are rolled up into annotationss:<br>
                      <ul>
                      <li>- <strong>category</strong>: Group selected data by x-value</li>
                      <li>- <strong>series</strong>: Group selected data by series</li>
                      <li>- <strong>auto</strong>: Group selected data by x-value if all selections have the same x-value, and by series otherwise</li>
                      <li>- none: Show only one annotations per selection</li>
                      </ul>"
                  ],
                ],
								[
									[
                    "id" => "chartLayout[annotations][boxStyle][strokeWidth]",
										"title" => __("Box Style Stroke Width", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 100,
                    "step" => 1,
										"value" =>$this->layout()['annotations']['boxStyle']['strokeWidth'],
										"hint" => ""
                  ],
									[
                    "id" => "chartLayout[annotations][boxStyle][gradient][useObjectBoundingBoxUnits]",
										"title" => __("Box Style Object Bounding Unit", $this->plugin),
										"type" => "checkbox", 
										"value" => $this->layout()['annotations']['boxStyle']['gradient']['useObjectBoundingBoxUnits'] ? true : false
									]
                ],
                [
                  [
                    "id" => "chartLayout[annotations][boxStyle][stroke]", 
                    "cssClasses" => ["color-picker"],
                    "title" => __("Box Style Stroke Color", $this->plugin), 
                    "type" => "color-picker",
                    "value" =>  $this->layout()['annotations']['boxStyle']['stroke'],
                  ]
                ],
								[
									[
										"id" => "chartLayout[annotations][boxStyle][rx]",
										"title" => __("Box style X-radius", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 1000,
                    "step" => 5,
										"value" => $this->layout()['annotations']['boxStyle']['rx'],
                  ],
									[
										"id" => "chartLayout[annotations][boxStyle][ry]",
										"title" => __("Box Style Y-radius", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 1000,
                    "step" => 5,
										"value" => $this->layout()['annotations']['boxStyle']['ry'],
									]
                ],
                [
                  [
                    "id" => "chartLayout[annotations][boxStyle][gradient][color1]", 
                    "cssClasses" => ["color-picker"],
                    "title" => __("Box Style Gradient Color 1", $this->plugin), 
                    "type" => "color-picker",
                    "value" => $this->layout()['annotations']['boxStyle']['gradient']['color1'],
                  ],
                ],
                [
                  [
                    "id" => "chartLayout[annotations][boxStyle][gradient][color2]",
                    "cssClasses" => ["color-picker"],
                    "title" => __("Box Style Gradient Color 2", $this->plugin), 
                    "type" => "color-picker",
                    "value" => $this->layout()['annotations']['boxStyle']['gradient']['color2'],
                  ],
                ],
								[
									[
										"id" => "chartLayout[annotations][boxStyle][gradient][x1]",  
										"title" => __("Box Style Gradient X Start", $this->plugin), 
                    "type" => "number",
										"suffix" => "%",
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" => $this->layout()['annotations']['boxStyle']['gradient']['x1'],
                    'hint' => 'pppppppppp'
                  ],
									[
										"id" => "chartLayout[annotations][boxStyle][gradient][y1]",  
										"title" => __("Box Style Gradient Y Start", $this->plugin), 
                    "type" => "number",
										"suffix" => "%",
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" =>  $this->layout()['annotations']['boxStyle']['gradient']['y1'],
                    'hint' => 'pppppppppp'
									]
                ],
								[
									[
										"id" => "chartLayout[annotations][boxStyle][gradient][x2]",  
										"title" => __("Box Style Gradient X End", $this->plugin),
                    "type" => "number",
										"suffix" => "%",
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" => $this->layout()['annotations']['boxStyle']['gradient']['x2'],
                    'hint' => 'pppppppppp'
                  ],
									[
										"id" => "chartLayout[annotations][boxStyle][gradient][y2]",  
										"title" => __("Box Style Gradient Y End", $this->plugin), 
                    "type" => "number",
										"suffix" => "%", 
										"min" => 0,
                    "max" => 100,
                    "step" => 5,
                    "value" => $this->layout()['annotations']['boxStyle']['gradient']['y2'],
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
										"id" => "chartLayout[animation][startup]",
										"title" => __("Startup", $this->plugin),
                    "type" => "checkbox", 
                    "value" => $this->layout()['animation']['startup'],
                    'hint' => 'pppppppppp'
                  ],
									[
										"id" => "chartLayout[animation][duration]",
										"title" => __("Duration", $this->plugin),
                    "type" => "number",
                    "min" => 0,
                    "max" => 10000,
                    "step" => 100,
                    "value" => $this->layout()['animation']['duration'], 
                  ],
                ],
								[
									[//"slug" => "chart_title", // field id
										"id" => "chartLayout[animation][easing]",
										"title" => __("Easing", $this->plugin),
										"type" => "select", 
										"options" => [
											"linear" => "Linear",
											"in" => "In",
											"out" => "Out",
											"inAndOut" => "In and Out",
                    ],
                    "value" => $this->layout()['animation']['easing'], 
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
										"id" => "chartLayout[crosshair][opacity]",  
										"title" => __("Opacity", $this->plugin), 
										"type" => "number",
										"min" => 0,
										"max" => 1,
                    "step" => .1,
                    "value" => $this->layout()['crosshair']['opacity'], 
                  ],
									[
										"id" => "chartLayout[crosshair][trigger]", 
										"title" => __("Trigger", $this->plugin), 
										"type" => "select", 
										"options" => [
											"focus" => "Focus",
											"selection" => "Selection",
											"both" => "Both",
                    ],
                    "value" => $this->layout()['crosshair']['trigger'], 
                  ],
                ],
								[
									[
										"id" => "chartLayout[crosshair][color]", 
										"title" => __("Color", $this->plugin), 
                    "type" => "color-picker",
                    "cssClasses" => ["color-picker"],
                    "value" => $this->layout()['crosshair']['color'], 

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
                    "id" => "chartLayout[selectionMode]",
                    "title" => __("Selection Mode", $this->plugin),
                    "type" => "select",
                    "options" => [
                      "single" => "Single",
                      "multiple" => "Multiple",
                    ],
                    "value" => $this->layout()['selectionMode'],
                    "hint" => "When selectionMode is 'multiple', users may select multiple data points."
                  ],
                  [
                    "id" => "chartLayout[tooltip][trigger]",
                    "title" => __("Annotation Trigger", $this->plugin),
                    "type" => "select",
										"options" => [
											"focus" => "Focus",
											"selection" => "Selection",
											"both" => "Both",
                    ],
                    "value" => $this->layout()['tooltip']['trigger'],
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
                  // [
                  //   "id" => "chartLayout[tooltip][trigger]",
                  //   "title" => __("Trigger", $this->plugin),
                  //   "type" => "select",
                  //   "options" => [
                  //     "focus" => "Hover",
                  //     "selection" => "Selection",
                  //     "none" => "None",
                  //   ],
                  //   "value" => $this->layout()['tooltip']['trigger'],
                  // ],
                  [
                    "id" => "chartLayout[tooltip][text]",
                    "title" => __("Text", $this->plugin),
                    "type" => "select",
                    'disabled' => $this->chart_type !=='PieChart'? true : false,
                    "options" => [
                      "both" => "Value and Percentage",
                      "value" => "Value",
                      "percentage" => "Percentage",
                    ],
                    "value" => $this->layout()['tooltip']['text'],
                  ],
                ],
                [
                  [
                    "id" => "chartLayout[tooltip][textStyle][fontName]",
                    "title" => __("Font", $this->plugin),
                    "type" => "select",
                    "options" =>  $this->font_names,
                    "value" => $this->layout()['tooltip']['textStyle']['fontName'],
                  ],
                  
                  [
                    "id" => "chartLayout[tooltip][textStyle][fontSize]",
                    "title" => __("Size", $this->plugin),
                    "type" => "number",
                    "value" => $this->layout()['tooltip']['textStyle']['fontSize'],
                  ],
                ],
                [
                  [
                    "id" => "chartLayout[tooltip][textStyle][color]",
                    "cssClasses" => ["color-picker"],
                    "title" => __("Color", $this->plugin),
                    "type" => "color-picker",
                    "value" => $this->layout()['tooltip']['textStyle']['color'],
                  ],
                ],
                [
                  [
                    "id" => "chartLayout[tooltip][textStyle][bold]",
                    "title" => __("Bold", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->layout()['tooltip']['textStyle']['bold'] ? true : false,
                  ],
                  [
                    "id" => "chartLayout[tooltip][textStyle][italic]",
                    "title" => __("Italic", $this->plugin),
                    "type" => "checkbox",
                    'value' => $this->layout()['tooltip']['textStyle']['italic'] ? true : false,
                    "hint" => "How multiple data selections are rolled up into tooltips:<br>
                      <ul>
                      <li>- <strong>category</strong>: Group selected data by x-value</li>
                      <li>- <strong>series</strong>: Group selected data by series</li>
                      <li>- <strong>auto</strong>: Group selected data by x-value if all selections have the same x-value, and by series otherwise</li>
                      <li>- none: Show only one tooltip per selection</li>
                      </ul>"
                  ],
                  [
                    "id" => "chartLayout[tooltip][showColorCode]",
                    "title" => __("Show Color Code", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->layout()['tooltip']['showColorCode'] ? true : false,
                  ],
                ]
              ]
            ]	
          ]
        ],

      ];

		} // END chart_fields
		




	} // END Dashboard

}