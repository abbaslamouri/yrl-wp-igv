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
if (!class_exists('SeriesOption')) {

	class SeriesOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($options = [], $i = 0, $labels = []) {

      $this->options = $options;
      $this->labels = $labels;
      $this->index = $i;

			
    } // END __construct



    

    public function options () {

      return [
        // 'enable' => isset( $this->options['enable'] ) ? true : false,
        'curveType' => isset( $this->options['curveType'] ) ? $this->options['curveType'] : 'linear',
        'targetAxisIndex' => isset( $this->options['targetAxisIndex'] ) ? $this->options['targetAxisIndex'] : 0,
        'color' => $this->index < 12 ? $this->colors[$this->index] : null, //isset( $this->options['color'] ) ? $this->options['color'] : $this->colors[array_rand($this->colors)],//"#".substr(md5(rand()), 0, 6),
        'visibleInLegend' => isset( $this->options['visibleInLegend'] ) ? true : true,
        'labelInLegend' => isset( $this->options['labelInLegend'] ) ? $this->options['labelInLegend'] : null,
        'lineWidth' => isset( $this->options['lineWidth'] ) ? $this->options['lineWidth'] : 2,
        'lineDashStyle' => isset( $this->options['lineDashStyle'] ) ? $this->options['lineDashStyle'] : null,
        'pointsVisible' => isset( $this->options['pointsVisible'] ) ? true : false,
        'pointShape' => [
          'type'=> $this->index < 12 ?  $this->point_shape_types[$this->index] : null, //isset( $this->options['pointShape']['type'] ) ? $this->options['pointShape']['type'] : $this->point_shape_types[array_rand($this->point_shape_types)],
          'sides' => isset( $this->options['pointShape']['sides'] ) ? $this->options['pointShape']['sides'] : 5,
          'dent' => isset( $this->options['pointShape']['dent'] ) ? $this->options['pointShape']['dent'] : .5,
          'rotation'=> isset( $this->options['pointShape']['rotation'] ) ? $this->options['pointShape']['rotation'] : 0
        ],
        'pointSize' => isset( $this->options['pointSize'] ) ? $this->options['pointSize'] : 10,
        'annotations' => [
          'textStyle' =>[
            'fontName' => isset( $this->options['annotations']['annotations']['fontName'] ) ? $this->options['annotations']['annotations']['fontName'] : 'verdana',
            'fontSize' => isset( $this->options['annotations']['annotations']['fontSize'] ) ? $this->options['annotations']['annotations']['fontSize'] : 10,
            'color' => isset( $this->options['annotations']['annotations']['color'] ) ? $this->options['annotations']['annotations']['color'] : 'teal',
            'bold' => isset( $this->options['annotations']['annotations']['bold'] ) ? true : false,
            'italic' => isset( $this->options['annotations']['annotations']['italic'] ) ? true : false,
            'auraColor' => isset( $this->options['annotations']['annotations']['auraColor'] ) ? $this->options['annotations']['annotations']['auraColor'] : 'red',
            'opacity'=> isset( $this->options['annotations']['annotations']['opacity'] ) ? $this->options['annotations']['annotations']['opacity'] : .5
          ]
        ]
      ];
    }





    public function seed () {
     
      return [
      //  "id" => "iwpgv__seriesPanel",
      //  'cssClass' => 'chartOption',
      //  "title" => __("Series", $this->plugin),
      //  "sections" => [
      //  "series-{$this->index}" => [
          // "id" => "series-{$this->index}",
          // "title" => __("", $this->plugin),
          // "fields" =>
          // [
            [
              [
                "id" => "seriesOptions[series][{$this->index}][curveType]", 
                "title" => __("Curve Type", $this->plugin),  	
                "type" => "select", 
                "options" => [
                  "none" => "Linear",
                  "function" => "Function",
                ],
                'value' =>  $this->options()['curveType'],
                ],
              [
                "id" => "seriesOptions[series][{$this->index}][targetAxisIndex]", 
                "title" => __("Target Axis Index", $this->plugin),  
                "type" => "select", 
                "options" => [
                  0 => "Default Axis",
                  1 => "Opposite Axis",
                ],
                'value' => $this->options()['targetAxisIndex'],
                ],
              ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][color]", 
                "cssClasses" =>["color-picker"],
                "title" => __("Color", $this->plugin),  
                "type" => "color-picker",
                'value' =>  isset($this->colors[$index]) ?  $this->colors[$index] : $this->options()['color']
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][visibleInLegend]",  
                "title" => __("Visible in Legend", $this->plugin),  
                "type" => "checkbox",
                'value' => $this->options()['visibleInLegend'] ? true : false
              ],
              [
                "id" => "seriesOptions[series][{$this->index}][labelInLegend]",  
                "title" => __("Label in Legend", $this->plugin),  
                "type" => "text",
                'value' => array_values($this->labels)[$index]
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][lineWidth]", 
                "title" => __("Line Width", $this->plugin), 
                "type" => "number",
                "min" => 1,
                "max" => 100,
                "step" => 1,
                'value' => $this->options()['lineWidth'],
              ],
              [
                "id" => "seriesOptions[series][{$this->index}][lineDashStyle]", 
                'cssClasses' => ['array-field'],
                "title" => __("Line Dash Style", $this->plugin),  
                "type" => "text",
                'value' =>$this->options()['lineDashStyle'],
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][pointsVisible]",  
                "title" => __("Points Visible", $this->plugin),  
                "type" => "checkbox", 
                'value' => $this->options()['pointsVisible'] ? true : false,
              ],
              [
                "id" => "seriesOptions[series][{$this->index}][pointShape][type]", 
                "title" => __("Point Shape", $this->plugin),  
                "type" => "select",
                'dependentField' => "seriesOptions[series][{$this->index}][pointsVisible]", 
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
                "id" => "seriesOptions[series][{$this->index}][pointShape][sides]", 
                "title" => __("Point Shape Sides", $this->plugin), 
                "type" => "number",
                'dependentField' => "seriesOptions[series][{$this->index}][pointsVisible]",
                "min" => 1,
                "max" => 100,
                "step" => 1,
                'value' => $this->options()['pointShape']['sides'],
              ],
              [
                "id" => "seriesOptions[series][{$this->index}][pointShape][dent]", 
                "title" => __("Point Shape Dent", $this->plugin), 
                "type" => "number",
                'dependentField' => "seriesOptions[series][{$this->index}][pointsVisible]",
                "min" => 0,
                "max" => 1,
                "step" => .01,
                'value' => $this->options()['pointShape']['dent'],
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][pointSize]", 
                "title" => __("Point Size", $this->plugin), 
                "type" => "number",
                 'dependentField' => "seriesOptions[series][{$this->index}][pointsVisible]",
                "min" => 1,
                "max" => 100,
                "step" => 1,
                'value' => $this->options()['pointSize'],
              ],
              [
                "id" => "seriesOptions[series][{$this->index}][pointShape][rotation]", 
                "title" => __("Pt. Shape Rotation", $this->plugin), 
                "type" => "number",
                'dependentField' => "seriesOptions[series][{$this->index}][pointsVisible]",
                "min" => -179,
                "max" => 179,
                "step" => 10,
                'value' => $this->options()['pointShape']['rotation'],
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][annotations][textStyle][fontName]", 
                "title" => __("Font Text Font", $this->plugin),  	
                "type" => "select", 
                "options" =>  $this->font_names,
                "nullOption" => "Select Font",
                'value' => $this->options()['annotations']['textStyle']['fontName'],
              ],
              [
                "id" => "seriesOptions[series][{$this->index}][annotations][textStyle][fontSize]", 
                "title" => __("Annotation Font Size", $this->plugin), 
                "type" => "number",
                "min" => 1,
                "max" => 100,
                "step" => 1,
                'value' =>$this->options()['annotations']['textStyle']['fontSize'],
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][annotations][textStyle][color]", 
                "cssClasses" =>["color-picker"],
                "type" => "color-picker", 
                "title" => __("Annotations Text Style Color", $this->plugin), 
                'value' => $this->options()['annotations']['textStyle']['color'], 
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][annotations][textStyle][bold]", 
                "title" => __("Bold", $this->plugin),  
                "type" => "checkbox",
                'value' => $this->options()['annotations']['textStyle']['bold'] ? true : false, 
              ],
              [
                "id" => "seriesOptions[series][{$this->index}][annotations][textStyle][italic]", 
                "title" => __("Italic", $this->plugin),  
                "type" => "checkbox", 
                'value' => $this->options()['annotations']['textStyle']['italic'] ? true : false,
              ],
            ],
            [
              [
                "id" => "seriesOptions[series][{$this->index}][annotations][textStyle][auraColor]", 
                "cssClasses" =>["color-picker"],
                "title" => __("Annotations Aura Color", $this->plugin),
                "type" => "color-picker", 
                'value' => $this->options()['annotations']['textStyle']['auraColor'], 
              ],
            ],
            [
              [

                "id" => "seriesOptions[series][{$this->index}][annotations][textStyle][opacity]", 
                "title" => __("Annotation Text Style Font Size", $this->plugin), 
                "type" => "number",
                "min" => 0,
                "max" => 1,
                "step" => .1,
                'value' => $this->options()['annotations']['textStyle']['opacity'],
              ],
            ],
          // ]
        //    ]
        //  ]
       
      ];
 
 
    }

    



	} // END Dashboard

}