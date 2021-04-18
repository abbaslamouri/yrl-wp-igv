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
if (!class_exists('TraceOption')) {

	class TraceOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct($options = [], $i = 0, $labels = [], $chart_type = null) {
      
      //  wp_send_json($this->colors[$i]);

      $this->options = $options;
      $this->labels = array_values($labels);
      $this->index = $i;
      $this->chart_type = $chart_type;

      switch ($chart_type) {
        case "LineChart":
        case "ScatterChart":
          // $this->type = "scatter";
          $this->mode = "lines+markers";
          break;
        default:
          // $this->type = null;
          $this->mode = null;   
      }

    } // END __construct



    

    public function options () {


      return [
        // 'enable' => isset( $this->options['enable'] ) ? true : false,
        'type' => isset( $this->options['type'] ) ? $this->options['type'] :  $this->chart_type ,
        'mode' => isset( $this->options['mode'] ) ? $this->options['mode'] : $this->mode,
        'name' => isset( $this->options['mode'] ) ?  $this->options['mode'] : $this->labels[ $this->index],
        'connectgaps' => isset( $this->options['connectgaps'] ) ?  $this->options['connectgaps'] : false,
        'line' => [
          'color' => isset( $this->options['line']['color'] ) ? $this->options['line']['color'] : $this->colors[$this->index],
          'width'=> isset( $this->options['line']['width'] ) ? $this->options['line']['width'] : 2
        ],
        'marker' => [
          'color' => isset( $this->options['marker']['color'] ) ? $this->options['marker']['color'] : $this->colors[$this->index],
          'size'=> isset( $this->options['marker']['size'] ) ? $this->options['marker']['size'] : 10
        ],
        // 'targetAxisIndex' => isset( $this->options['targetAxisIndex'] ) ? $this->options['targetAxisIndex'] : 0,
        // 'color' => $this->index < 12 ? $this->colors[$this->index] : null, //isset( $this->options['color'] ) ? $this->options['color'] : $this->colors[array_rand($this->colors)],//"#".substr(md5(rand()), 0, 6),
        // 'visibleInLegend' => isset( $this->options['visibleInLegend'] ) ? true : true,
        // 'labelInLegend' => isset( $this->options['labelInLegend'] ) ? $this->options['labelInLegend'] : null,
        // 'lineWidth' => isset( $this->options['lineWidth'] ) ? $this->options['lineWidth'] : 2,
        // 'lineDashStyle' => isset( $this->options['lineDashStyle'] ) ? $this->options['lineDashStyle'] : null,
        // 'pointsVisible' => isset( $this->options['pointsVisible'] ) ? true : false,
        // 'pointShape' => [
        //   'type'=> $this->index < 12 ?  $this->point_shape_types[$this->index] : null, //isset( $this->options['pointShape']['type'] ) ? $this->options['pointShape']['type'] : $this->point_shape_types[array_rand($this->point_shape_types)],
        //   'sides' => isset( $this->options['pointShape']['sides'] ) ? $this->options['pointShape']['sides'] : 5,
        //   'dent' => isset( $this->options['pointShape']['dent'] ) ? $this->options['pointShape']['dent'] : .5,
        //   'rotation'=> isset( $this->options['pointShape']['rotation'] ) ? $this->options['pointShape']['rotation'] : 0
        // ],
        // 'pointSize' => isset( $this->options['pointSize'] ) ? $this->options['pointSize'] : 10,
        // 'annotations' => [
        //   'textStyle' =>[
        //     'fontName' => isset( $this->options['annotations']['annotations']['fontName'] ) ? $this->options['annotations']['annotations']['fontName'] : 'verdana',
        //     'fontSize' => isset( $this->options['annotations']['annotations']['fontSize'] ) ? $this->options['annotations']['annotations']['fontSize'] : 10,
        //     'color' => isset( $this->options['annotations']['annotations']['color'] ) ? $this->options['annotations']['annotations']['color'] : 'teal',
        //     'bold' => isset( $this->options['annotations']['annotations']['bold'] ) ? true : false,
        //     'italic' => isset( $this->options['annotations']['annotations']['italic'] ) ? true : false,
        //     'auraColor' => isset( $this->options['annotations']['annotations']['auraColor'] ) ? $this->options['annotations']['annotations']['auraColor'] : 'red',
        //     'opacity'=> isset( $this->options['annotations']['annotations']['opacity'] ) ? $this->options['annotations']['annotations']['opacity'] : .5
        //   ]
        // ]
      ];
    }





    public function fields() {
     
      return [
      //  "id" => "iwpgv__seriesPanel",
      //  'cssClass' => 'chartOption',
      //  "title" => __("Series", $this->plugin),
      //  "sections" => [
      //  "series-{$this->index}" => [
          // "id" => "series-{$this->index}",
          // "title" => __("", $this->plugin),
          // "fields" => [
            [
              [
                "id" => "traceOptions[{$this->index}][name]",  
                "title" => __("Label in Legend", $this->plugin),  
                "type" => "text",
                'value' => $this->options()['name'],
                "hint" => "The trace name appear as the legend item and on hover."
              ],
              [
                "id" => "traceOptions[{$this->index}][mode]", 
                "title" => __("Mode", $this->plugin),  	
                "type" => "select", 
                "options" => [
                  "none" => "None",
                  "markers" => "Markers",
                  "lines" => "LInes",
                  "lines+markers" => "LInes & Markers"
                ],
                'value' =>  $this->options()['mode'],
                "hint" => "Determines the drawing mode for this scatter trace. If the provided `mode` includes 'text' then the `text` elements appear at the coordinates. Otherwise, the `text` elements appear on hover. If there are less than 20 points and the trace is not stacked then the default is 'lines+markers'. Otherwise, 'lines'."
              ],
                // [
                // "id" => "traceOptions[{$this->index}][namexxx]", 
                // "title" => __("Target Axis Index", $this->plugin),  
                // "type" => "select", 
                // "options" => [
                //   0 => "Default Axis",
                //   1 => "Opposite Axis",
                // ],
                // 'value' => $this->options()['namexxx'],
                // ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][color]", 
                "cssClasses" =>["color-picker"],
                "title" => __("Color", $this->plugin),  
                "type" => "color-picker",
                'value' =>  isset($this->colors[$index]) ?  $this->colors[$index] : $this->options()['color']
              ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][visibleInLegend]",  
                "title" => __("Visible in Legend", $this->plugin),  
                "type" => "checkbox",
                'value' => $this->options()['visibleInLegend'] ? true : false
              ],
              [
                "id" => "traceOptions[{$this->index}][labelInLegend]",  
                "title" => __("Label in Legend", $this->plugin),  
                "type" => "text",
                'value' => array_values($this->labels)[$index]
              ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][lineWidth]", 
                "title" => __("Line Width", $this->plugin), 
                "type" => "number",
                "min" => 1,
                "max" => 100,
                "step" => 1,
                'value' => $this->options()['lineWidth'],
              ],
              [
                "id" => "traceOptions[{$this->index}][lineDashStyle]", 
                'cssClasses' => ['array-field'],
                "title" => __("Line Dash Style", $this->plugin),  
                "type" => "text",
                'value' =>$this->options()['lineDashStyle'],
              ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][pointsVisible]",  
                "title" => __("Points Visible", $this->plugin),  
                "type" => "checkbox", 
                'value' => $this->options()['pointsVisible'] ? true : false,
              ],
              [
                "id" => "traceOptions[{$this->index}][pointShape][type]", 
                "title" => __("Point Shape", $this->plugin),  
                "type" => "select",
                'dependentField' => "traceOptions[{$this->index}][pointsVisible]", 
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
                "id" => "traceOptions[{$this->index}][pointShape][sides]", 
                "title" => __("Point Shape Sides", $this->plugin), 
                "type" => "number",
                'dependentField' => "traceOptions[{$this->index}][pointsVisible]",
                "min" => 1,
                "max" => 100,
                "step" => 1,
                'value' => $this->options()['pointShape']['sides'],
              ],
              [
                "id" => "traceOptions[{$this->index}][pointShape][dent]", 
                "title" => __("Point Shape Dent", $this->plugin), 
                "type" => "number",
                'dependentField' => "traceOptions[{$this->index}][pointsVisible]",
                "min" => 0,
                "max" => 1,
                "step" => .01,
                'value' => $this->options()['pointShape']['dent'],
              ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][pointSize]", 
                "title" => __("Point Size", $this->plugin), 
                "type" => "number",
                 'dependentField' => "traceOptions[{$this->index}][pointsVisible]",
                "min" => 1,
                "max" => 100,
                "step" => 1,
                'value' => $this->options()['pointSize'],
              ],
              [
                "id" => "traceOptions[{$this->index}][pointShape][rotation]", 
                "title" => __("Pt. Shape Rotation", $this->plugin), 
                "type" => "number",
                'dependentField' => "traceOptions[{$this->index}][pointsVisible]",
                "min" => -179,
                "max" => 179,
                "step" => 10,
                'value' => $this->options()['pointShape']['rotation'],
              ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][annotations][textStyle][fontName]", 
                "title" => __("Font Text Font", $this->plugin),  	
                "type" => "select", 
                "options" =>  $this->font_names,
                "nullOption" => "Select Font",
                'value' => $this->options()['annotations']['textStyle']['fontName'],
              ],
              [
                "id" => "traceOptions[{$this->index}][annotations][textStyle][fontSize]", 
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
                "id" => "traceOptions[{$this->index}][annotations][textStyle][color]", 
                "cssClasses" =>["color-picker"],
                "type" => "color-picker", 
                "title" => __("Annotations Text Style Color", $this->plugin), 
                'value' => $this->options()['annotations']['textStyle']['color'], 
              ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][annotations][textStyle][bold]", 
                "title" => __("Bold", $this->plugin),  
                "type" => "checkbox",
                'value' => $this->options()['annotations']['textStyle']['bold'] ? true : false, 
              ],
              [
                "id" => "traceOptions[{$this->index}][annotations][textStyle][italic]", 
                "title" => __("Italic", $this->plugin),  
                "type" => "checkbox", 
                'value' => $this->options()['annotations']['textStyle']['italic'] ? true : false,
              ],
            ],
            [
              [
                "id" => "traceOptions[{$this->index}][annotations][textStyle][auraColor]", 
                "cssClasses" =>["color-picker"],
                "title" => __("Annotations Aura Color", $this->plugin),
                "type" => "color-picker", 
                'value' => $this->options()['annotations']['textStyle']['auraColor'], 
              ],
            ],
            [
              [

                "id" => "traceOptions[{$this->index}][annotations][textStyle][opacity]", 
                "title" => __("Annotation Text Style Font Size", $this->plugin), 
                "type" => "number",
                "min" => 0,
                "max" => 1,
                "step" => .1,
                'value' => $this->options()['annotations']['textStyle']['opacity'],
              ],
            ],
          ];
        //    ]
        //  ]
       
      // ];
 
 
    }

    



	} // END Dashboard

}