<?php

/**
* Interactive WP Google Visualization
*
* @package Interactive WP Google Visualization
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace YRL_WP_IGV\Includes;


// Prohibit direct script loading.
defined("ABSPATH") || die("No direct script access allowed!");

// Declare main class if it does not exist
if (!class_exists('TrendlinesOption')) { 
	class TrendlinesOption Extends Dashboard {


    	/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($options, $i, $labels) {

      $this->options = $options;
      $this->labels = $labels;
      $this->index = $i;
			
    } // END __construct
  


    public function options () {
    
      return [

        'type' =>  isset( $this->options['type'] ) ? $this->options['type'] : 'polynomial',
        'degree' =>  isset( $this->options['degree'] ) ? $this->options['degree'] : 3,
        'color' => $this->index < 12 ? $this->colors[$this->index] : null, //isset( $this->options['color'] ) ? $this->options['color'] : $this->colors[array_rand($this->colors)], //"#".substr(md5(rand()), 0, 6),
        'visibleInLegend' =>  isset( $this->options['visibleInLegend'] ) ? true : false,
        'labelInLegend' =>  isset( $this->options['labelInLegend'] ) ? $this->options['labelInLegend'] : null,
        'lineWidth' =>  isset( $this->options['lineWidth'] ) ? $this->options['lineWidth'] : 2,
        'opacity' =>  isset( $this->options['opacity'] ) ? $this->options['opacity'] : 1,
        'pointsVisible' =>  isset( $this->options['pointsVisible'] ) ? true : false,
        'pointShape' => [
          'type'=> $this->index < 12 ?  $this->point_shape_types[$this->index] : null, //isset( $this->options['pointShape']['type'] ) ? $this->options['pointShape']['type'] : $this->point_shape_types[array_rand($this->point_shape_types)],
          'sides' =>  isset( $this->options['pointShape']['sides'] ) ? $this->options['pointShape']['sides'] : 5,
          'dent' =>  isset( $this->options['pointShape']['dent'] ) ? $this->options['pointShape']['dent'] : 1,
          'rotation'=>  isset( $this->options['pointShape']['rotation'] ) ? $this->options['pointShape']['rotation'] : 0
        ],
        'pointSize' =>  isset( $this->options['pointSize'] ) ? $this->options['pointSize'] : 10,
        'showR2' =>  isset( $this->options['showR2'] ) ? true: false        
      ];

    }
    



    public function seed () {

      return [
        // "id" => "iwpgv__trendlinesPanel",
        // 'cssClass' => 'chartOption',
        // "title" => __("Trendlines", $this->plugin),
        // "sections" => [
        //   "trendlines-{$this->index}" => [
        //     "id" => "trendlines-{$this->index}",
        //     "title" => __("", $this->plugin),
        //     "fields" => [
              // [
              //   [
              //     "id" => "trendlinesOptions[trendlines][{$this->index}][enable]",  
              //     "title" => __("Enable Trendline", $this->plugin), 
              //     "type" => "checkbox",
              //     'value' => isset($this->options()['enable']) ? $this->options()['enable'], 
              //   ],
              // ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][type]", 
                  "title" => __("Type", $this->plugin),
                  "type" => "select", 
                  "options" => [
                    "linear" => "Linear",
                    "polynomial" => "Polynomial",
                    "exponential" => "Exponential",
                  ],
                  'value' => $this->options()['type'],
                ],
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][degree]", 
                  "title" => __("Degree", $this->plugin),
                  "type" => "number",
                  "min" => 1,
                  "max" => 10,
                  "step" => 1,
                  'value' =>  $this->options()['degree'],
                ],
              ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][color]", 
                  "title" => __("Color", $this->plugin), 
                  "type" => "color-picker",
                  "cssClasses" => ["color-picker"],
                  'value' => isset($this->colors[$index]) ? $this->colors[$index] : $this->options()['color'],
                ],
              ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][visibleInLegend]",  
                  "title" => __("Visible in Legend", $this->plugin), 
                  "type" => "checkbox",
                  'value' => $this->options()['visibleInLegend'] ? true : false, 
                ],
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][labelInLegend]",  
                  "title" => __("Label in Legend", $this->plugin), 
                  "type" => "text",
                  'value' => array_values($this->labels)[$index] 
                ],
              ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][lineWidth]", 
                  "title" => __("Line Width", $this->plugin),
                  "type" => "number",
                  "min" => 1,
                  "max" => 20,
                  "step" => 1,
                  'value' => $this->options()['lineWidth'],
                ],
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][opacity]", 
                  "title" => __("Opacity", $this->plugin),
                  "type" => "number",
                  "min" => 0,
                  "max" => 1,
                  "step" => 0.05,
                  'value' => $this->options()['opacity'],
                ],
              ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][pointsVisible]",  
                  "title" => __("Points Visible", $this->plugin), 
                  "type" => "checkbox",
                  'value' => $this->options()['pointsVisible'] ? true : false, 
                ],
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][pointShape][type]", 
                  "title" => __("Point Shape", $this->plugin), 
                  "type" => "select", 
                  'dependentField' => "trendlinesOptions[trendlines][{$this->index}][pointsVisible]",
                  "options" => [
                    "circle" => "Circle",
                    "triangle" => "Triangle",
                    "square" => "Square",
                    "diamond" => "Diamond",
                    "star" => "Star"
                  ],
                  'value' => isset($this->point_shape_types[$index]) ? $this->point_shape_types[$index] : $this->options()['pointShape']['type'],
                ],
              ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][pointShape][sides]", 
                  "title" => __("Sides", $this->plugin),
                  "type" => "number",
                  'dependentField' => "trendlinesOptions[trendlines][{$this->index}][pointsVisible]",
                  "min" => 0,
                  "max" => 10,
                  "step" => 1,
                  'value' => $this->options()['pointShape']['sides'],
                ],
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][pointShape][dent]", 
                  "title" => __("Point Dent", $this->plugin),
                  "type" => "number",
                  'dependentField' => "trendlinesOptions[trendlines][{$this->index}][pointsVisible]",
                  "min" => 0,
                  "max" => 1,
                  "step" => .01,
                  'value' => $this->options()['pointShape']['dent'],
                ],
              ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][pointSize]", 
                  "title" => __("Point Size", $this->plugin),
                  "type" => "number",
                  'dependentField' => "trendlinesOptions[trendlines][{$this->index}][pointsVisible]",
                  "min" => 1,
                  "max" => 100,
                  "step" => 1,
                  'value' => $this->options()['pointSize'],
                ],
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][pointShape][rotation]", 
                  "title" => __("Point Rotation", $this->plugin),
                  "type" => "number",
                  'dependentField' => "trendlinesOptions[trendlines][{$this->index}][pointsVisible]",
                  "min" => -179,
                  "max" => 179,
                  "step" => 10,
                  'value' => $this->options()['pointShape']['rotation'],
                ],
              ],
              [
                [
                  "id" => "trendlinesOptions[trendlines][{$this->index}][showR2]",  
                  "title" => __("Show R2", $this->plugin), 
                  "type" => "checkbox",
                  'value' => $this->options()['showR2'] ? true : false, 
                ],
              ]
            ];
      //     ]
      //   ]
      // ];
 
    }

	} // END Option

}