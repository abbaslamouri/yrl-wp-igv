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
if (!class_exists('AxisOption')) {

	class AxisOption Extends Dashboard {

	
		/**
		*Magic constructor.  Gets called when class is instantiated
		*/
		public function __construct( $options = [], $axis_params = [], $major_axis_type = null , $labels = []) {

      $this->options = $options;
      $this->axis_params = $axis_params;
      $this->major_axis_type = $major_axis_type;
      
      $this->axis_params_direction = $this->axis_params['id'] === 'horAxisOption' ? ["1" => "Left To Right", "-1" => "Right To Left"] : ["1" => "Bottom to Top", "-1" => "Top to Bottom"];

			$this->title = ( $this->axis_params['id'] === 'horAxisOption' && isset(array_values($labels)[0] ) ) ? array_values($labels)[0] : null;
			
    } // END __construct


		
  

    public function options() {

      

      // if (! empty( $this->options ) ) {
      //   wp_send_json($this->options);
      //   return $this->options;
      // }


			return [

        "title" => isset( $this->options['title'] ) ? $this->options['title'] : $this->title ,
        "baseline" => isset( $this->options['baseline'] )? $this->options['baseline'] :  null,
        "baselineColor" => isset( $this->options['baselineColor'] )? $this->options['baselineColor'] : 'cyan',
        'textPosition' => isset( $this->options['textPosition'] )? $this->options['textPosition'] : 'out',
        "ticks" => isset( $this->options['ticks'] )? $this->options['ticks'] : null,
        "direction" => isset( $this->options['direction'] )? $this->options['direction'] : '1',
        "format" => isset( $this->options['format'] )? $this->options['format'] : 'short',
        "minValue" => isset( $this->options['minValue'] )? $this->options['minValue'] : null,
        "maxValue" => isset( $this->options['maxValue'] )? $this->options['maxValue'] : null,
        "viewWindowMode" => isset( $this->options['viewWindowMode'] )? $this->options['viewWindowMode'] : 'maximized' ,
        "showTextEvery" => isset( $this->options['showTextEvery'] )? $this->options['showTextEvery'] : 1,
        "slantedText" => isset( $this->options['slantedText'] )? true : false,
        "slantedTextAngle" => isset( $this->options['slantedTextAngle'] )? $this->options['slantedTextAngle'] : 45,
        "allowContainerBoundaryTextCufoff" => isset( $this->options['allowContainerBoundaryTextCufoff'] )? $this->options['allowContainerBoundaryTextCufoff'] : false,
        "maxAlternation" => isset( $this->options['maxAlternation'] )? $this->options['maxAlternation'] : 2,
        "maxTextLines" => isset( $this->options['maxTextLines'] )? $this->options['maxTextLines'] : 2,
        "minTextSpacing" => isset( $this->options['minTextSpacing'] )? $this->options['minTextSpacing'] : 2,
        "viewWindow" => [
          "min" => isset( $this->options['viewWindow']['min'] )? $this->options['viewWindow']['min'] : null,
          "max" => isset( $this->options['viewWindow']['max'] )? $this->options['viewWindow']['max'] : null,
      ],

        "titleTextStyle" => [
          "color" => isset( $this->options['titleTextStyle']['color'] )? $this->options['titleTextStyle']['color'] : 'black',
          "fontName" => isset( $this->options['titleTextStyle']['fontName'] )? $this->options['titleTextStyle']['fontName'] : 'tahoma',
          "fontSize" => isset( $this->options['titleTextStyle']['fontSize'] )? $this->options['titleTextStyle']['fontSize'] : 20,
          "bold" => isset( $this->options['titleTextStyle']['bold'] )? true : false,
          "italic" => isset( $this->options['titleTextStyle']['italic'] )? true : false,
      ],
        "textStyle" => [
          "color" => isset( $this->options['textStyle']['color'] )? $this->options['textStyle']['color'] : 'teal',
          "fontName" => isset( $this->options['textStyle']['fontName'] )? $this->options['textStyle']['fontName'] : 'tahoma',
          "fontSize" => isset( $this->options['textStyle']['fontSize'] )? $this->options['textStyle']['fontSize'] : 12,
          "bold" => isset( $this->options['textStyle']['bold'] )? true : false,
          "italic" => isset( $this->options['textStyle']['italic'] )? true : false,
      ],
        "gridlines" => [
          "color" => isset( $this->options['gridlines']['color'] )? $this->options['gridlines']['color'] : 'green',
          "count" => isset( $this->options['gridlines']['count'] )? $this->options['gridlines']['count'] : -1,
      ],
        "minorGridlines" => [
          "color" => isset( $this->options['minorGridlines']['color'] )? $this->options['minorGridlines']['color'] : 'blue',
          "count" => isset( $this->options['minorGridlines']['count'] )? $this->options['minorGridlines']['count'] : 0,
      ],
        "scaleType" => isset( $this->options['scaleType'] )? $this->options['scaleType'] : null,

      ];
    }


   




		/**
		 * Admin fields
		 *
		 * @return void
		 */
		public function fields(){

			return [
				$this->axis_params['panelId'] => [
          "id" => $this->axis_params['panelId'],
          'cssClasses' => [$this->axis_params['id'], 'chart'],
					"title" => __(	$this->axis_params['title'], $this->plugin),
					"sections" => [
						"general" => [
							"id" => "general",
							"title" => "General Settings",
							"fields" => [
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[viewWindowMode]", 
										"title" => __("View Window Mode", $this->plugin),
										"type" => "select", 
										"options" => [
											"pretty" => "Pretty",
											"maximized" => "Maximized",
                    ],
                    "value" => $this->options()['viewWindowMode'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false,
                    'hint' => <<<ENDHTML
Specifies how to scale the horizontal axis to render the values within the chart area. The following string values are supported:

'pretty' - Scale the horizontal values so that the maximum and minimum data values are rendered a bit inside the left and right of the chart area. The viewWindow is expanded to the nearest major gridline for numbers, or the nearest minor gridline for dates and times.
'maximized' - Scale the horizontal values so that the maximum and minimum data values touch the left and right of the chart area. This will cause haxis.viewWindow.min and haxis.viewWindow.max to be ignored.
'explicit' - A deprecated option for specifying the left and right scale values of the chart area. (Deprecated because it's redundant with haxis.viewWindow.min and haxis.viewWindow.max.) Data values outside these values will be cropped. You must specify an hAxis.viewWindow object describing the maximum and minimum values to show.
This option is only supported for a continuous axis.
ENDHTML
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[viewWindow][min]", 
										"title" => __("View Window Min.", $this->plugin),
                    "type" => "number",
                    // "min" => 1,
										// "max" => 1000,
										// "step" => 1,
                    "value" => $this->options()['viewWindow']['min'],
                    'hint' => <<<ENDHTML
For a continuous axis:

The maximum horizontal data value to render.

For a discrete axis:

The zero-based row index where the cropping window ends. Data points at this index and higher will be cropped out. In conjunction with vAxis.viewWindowMode.min, it defines a half-opened range [min, max) that denotes the element indices to display. In other words, every index such that min <= index < max will be displayed.

Ignored when hAxis.viewWindowMode is 'pretty' or 'maximized.
ENDHTML

									],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[viewWindow][max]", 
										"title" => __("View Window Max.", $this->plugin),
                    "type" => "number", 
                    // "min" => 1,
										// "max" => 1000,
										// "step" => 1,
                    "value" => $this->options()['viewWindow']['max'],
                    'hint' => <<<ENDHTML
For a continuous axis:

The maximum horizontal data value to render.

For a discrete axis:

The zero-based row index where the cropping window ends. Data points at this index and higher will be cropped out. In conjunction with vAxis.viewWindowMode.min, it defines a half-opened range [min, max) that denotes the element indices to display. In other words, every index such that min <= index < max will be displayed.

Ignored when hAxis.viewWindowMode is 'pretty' or 'maximized.
ENDHTML
                  ]
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[minValue]", 
										"title" => __("Minimum Value", $this->plugin),
                    "type" => "number",
                    "min" => 1,
										"max" => 1000,
										"step" => 1,
                    "value" => $this->options()['minValue'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false,
                    'hint' => <<<ENDHTML
Moves the min value of the horizontal axis to the specified value; this will be leftward in most charts. Ignored if this is set to a value greater than the minimum x-value of the data. hAxis.viewWindow.min overrides this property.

This option is only supported for a continuous axis.
ENDHTML
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[maxValue]", 
										"title" => __("Maximum Value", $this->plugin),
                    "type" => "number",
                    "min" => 1,
										"max" => 1000,
										"step" => 1,
                    "value" => $this->options()['maxValue'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false,
                    'hint' => <<<ENDHTML
Moves the max value of the horizontal axis to the specified value; this will be rightward in most charts. Ignored if this is set to a value smaller than the maximum x-value of the data. hAxis.viewWindow.max overrides this property.

This option is only supported for a continuous axis.
ENDHTML
                  ],
                ],		
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[direction]", 
										"title" => __("Direction", $this->plugin),
										"type" => "select", 
										"options" => $this->axis_params_direction,
                    "value" => $this->options()['direction']
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[format]", 
										"title" => __("Format", $this->plugin),
										"type" => "select", 
										"options" => [
											"" => "Auto",
											"decimal" => "thousands",
											"scientific" => "scientific",
											"currency" => "currency",
											"percent" => "percentages",
											"short" => "abbreviated",
											"long" => "words",
                    ],
                    "value" =>  $this->options()['format'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false,
                    'hint' => <<<ENDHTML
A format string for numeric or date axis labels.

For number axis labels, this is a subset of the decimal formatting ICU pattern set . For instance, {format:'#,###%'} will display values "1,000%", "750%", and "50%" for values 10, 7.5, and 0.5. You can also supply any of the following:

{format: 'none'}: displays numbers with no formatting (e.g., 8000000)
{format: 'decimal'}: displays numbers with thousands separators (e.g., 8,000,000)
{format: 'scientific'}: displays numbers in scientific notation (e.g., 8e6)
{format: 'currency'}: displays numbers in the local currency (e.g., $8,000,000.00)
{format: 'percent'}: displays numbers as percentages (e.g., 800,000,000%)
{format: 'short'}: displays abbreviated numbers (e.g., 8M)
{format: 'long'}: displays numbers as full words (e.g., 8 million)
For date axis labels, this is a subset of the date formatting ICU pattern set . For instance, {format:'MMM d, y'} will display the value "Jul 1, 2011" for the date of July first in 2011.

The actual formatting applied to the label is derived from the locale the API has been loaded with. For more details, see loading charts with a specific locale .

In computing tick values and gridlines, several alternative combinations of all the relevant gridline options will be considered and alternatives will be rejected if the formatted tick labels would be duplicated or overlap. So you can specify format:"#" if you want to only show integer tick values, but be aware that if no alternative satisfies this condition, no gridlines or ticks will be shown.

This option is only supported for a continuous axis.
ENDHTML

                  ],
                ],
								
								[
									// [
									// 	"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[logScale]", 
									// 	"title" => __("Log Scale", $this->plugin),
									// 	"submenuPage" => "{$this->prefix}_dashboard", 
									// 	"type" => "checkbox", 
									// 	"hint" => "hAxis property that makes the horizontal axis a logarithmic scale (requires all values to be positive). Set to true for yes. This option is only supported for a continuous axis."
									// ),
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[scaleType]", 
										"title" => __("Scale Type", $this->plugin),
										"type" => "select", 
										"options" => [
											"null" => "No Log Scaling",
											"log" => "Log Scaling",
											"mirrorLog" => "Mirror Log"
                    ],
                    "value" => $this->options()['scaleType'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false,
                    "hint" => <<<ENDHTML
hAxis property that makes the horizontal axis a logarithmic scale. Can be one of the following:

null - No logarithmic scaling is performed.
'log' - Logarithmic scaling. Negative and zero values are not plotted. This option is the same as setting hAxis: { logscale: true }.
'mirrorLog' - Logarithmic scaling in which negative and zero values are plotted. The plotted value of a negative number is the negative of the log of the absolute value. Values close to 0 are plotted on a linear scale.
This option is only supported for a continuous axis.
ENDHTML
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[showTextEvery]", 
										"title" => __("Show Text Every", $this->plugin),
                    "type" => "number", 
                    "value" =>$this->options()['showTextEvery'],
										"min" => 1,
										"max" => 1000,
                    "step" => 1,
                    'hint' => <<<ENDHTML
How many horizontal axis labels to show, where 1 means show every label, 2 means show every other label, and so on. Default is to try to show as many labels as possible without overlapping.
ENDHTML
									],
								]
							]
            ],

						"title" => [
							"id" => "title",
							"title" => "Title",
							"fields" => [
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[title]", 
										"title" => __("Title", $this->plugin),
                    "type" => "text",
                    "value" => $this->options()['title'],
                    'hint' => <<<ENDHTML
hAxis property that specifies the title of the horizontal axis.
ENDHTML

                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[titleTextStyle][color]", 
										"cssClasses" => ["color-picker"],
										"title" => __("Title Color", $this->plugin),
                    "type" => "color-picker",
                    "value" => $this->options()['titleTextStyle']['color'],
                    'hint' => <<<ENDHTML
The color can be any HTML color string, for example: 'red' or '#00cc00'. Also see fontName and fontSize.
ENDHTML
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[titleTextStyle][fontName]", 
										"title" => __("Font Name", $this->plugin),
										"type" => "select", 
										"options" => $this->font_names,
                    "value" =>  $this->options()['titleTextStyle']['fontName'],
                    'hint' => <<<ENDHTML

ENDHTML
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[titleTextStyle][fontSize]", 
										"title" => __("Font Size", $this->plugin),
                    "type" => "number",
                    "min" => 1,
										"max" => 1000,
										"step" => 1, 
                    "value" => $this->options()['titleTextStyle']['fontSize'],
                    'hint' => <<<ENDHTML

ENDHTML
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[titleTextStyle][bold]", 
										"title" => __("Title Bold", $this->plugin),
										"type" => "checkbox", 
                    "value" =>  $this->options()['titleTextStyle']['bold'] ? true : false,
                    'hint' => <<<ENDHTML

ENDHTML
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[titleTextStyle][italic]", 
										"title" => __("Title Italic", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['titleTextStyle']['italic'] ? true : false,
                    'hint' => <<<ENDHTML

ENDHTML
									],
								],

							]
						],

						"labels" => [
							"id" => "labels",
							"title" => "Labels",
							"fields" => [
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[textStyle][fontName]", 
										"title" => __("Text Font", $this->plugin),
										"type" => "select", 
										"options" =>  $this->font_names,
                    "value" =>  $this->options()['textStyle']['fontName']
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[textStyle][fontSize]", 
										"title" => __("Text Size", $this->plugin),
                    "type" => "number", 
                    "min" => 1,
										"max" => 1000,
										"step" => 1,
                    "value" => $this->options()['textStyle']['fontSize']
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[textStyle][color]", 
										"cssClasses" => ["color-picker"],
										"title" => __("Text Color", $this->plugin),
                    "type" => "color-picker", 
                    "value" => $this->options()['textStyle']['color']
                  ],
                ],							
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[textStyle][bold]", 
										"title" => __("Text Bold", $this->plugin),
                    "type" => "checkbox", 
                    'value' => $this->options()['titleTextStyle']['bold'] ? true : false,
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[textStyle][italic]", 
										"title" => __("Text Italic", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['textStyle']['bold'] ? true : false, 
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[textPosition]", 
										"title" => __("Text Position", $this->plugin),
										"type" => "select", 
										"options" => [
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
                    ],
                    "value" => $this->options()['textPosition']
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[slantedText]", 
										"title" => __("Slanted Text", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['slantedText'] ? true : false,
                    'hint' => 
<<<ENDHTML
If true, draw the horizontal axis text at an angle, to help fit more text along the axis; if false, draw horizontal axis text upright. Default behavior is to slant text if it cannot all fit when drawn upright. Notice that this option is available only when the hAxis.textPosition is set to 'out' (which is the default). The default is false for dates and times.
ENDHTML
                  ],
									[
                    "id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[slantedTextAngle]", 
                    'dependentField' =>  "{$this->axis_params['id']}s{$this->axis_params['axis']}[slantedText]",
										"title" => __("Slanted Text Angle", $this->plugin),
										"type" => "number", 
										"min" => -90,
										"max" => 90,
                    "step" => 1,
                    "value" => $this->options()['slantedTextAngle'],
                    'hint' =>
<<<ENDHTML
The angle of the horizontal axis text, if it's drawn slanted. Ignored if hAxis.slantedText is false, or is in auto mode, and the chart decided to draw the text horizontally. If the angle is positive, the rotation is counter-clockwise, and if negative, it is clockwise.
ENDHTML
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[allowContainerBoundaryTextCufoff]", 
										"title" => __("Allow Container Text Cutoff", $this->plugin),
                    "type" => "checkbox",
                    "value" => $this->options()['allowContainerBoundaryTextCufoff'] ? true : false, 
                    "hint" => <<<ENDHTML
If false, will hide outermost labels rather than allow them to be cropped by the chart container. If true, will allow label cropping.

This option is only supported for a discrete axis.
ENDHTML
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[maxAlternation]", 
										"title" => __("Max Alternation", $this->plugin),
										"type" => "number", 
										"min" => 1,
										"max" => 10,
                    "step" => 1,
                    "value" => $this->options()['maxAlternation'],
                    "hint" => <<<ENDHTML
Maximum number of levels of horizontal axis text. If axis text labels become too crowded, the server might shift neighboring labels up or down in order to fit labels closer together. This value specifies the most number of levels to use; the server can use fewer levels, if labels can fit without overlapping. For dates and times, the default is 1.
ENDHTML
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[maxTextLines]", 
										"title" => __("Max Text Lines", $this->plugin),
										"type" => "number", 
										"min" => 1,
										"max" => 10,
                    "step" => 1,
                    "value" => $this->options()['maxTextLines'],
										"dataType" => "discrete",
                    "hint" => <<<ENDHTML
Maximum number of lines allowed for the text labels. Labels can span multiple lines if they are too long, and the number of lines is, by default, limited by the height of the available space.
ENDHTML
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[minTextSpacing]", 
										"title" => __("Min Text Spacing", $this->plugin),
										"type" => "number", 
										"min" => 1,
										"max" => 1000,
                    "step" => 10,
                    "value" =>  $this->options()['minTextSpacing'],
                    "hint" => <<<ENDHTML
Minimum horizontal spacing, in pixels, allowed between two adjacent text labels. If the labels are spaced too densely, or they are too long, the spacing can drop below this threshold, and in this case one of the label-unclutter measures will be applied (e.g, truncating the labels or dropping some of them).
ENDHTML
                  ],
                ],
              ]
              ],

						"gridlines" => [
							"id" => "gridlines",
							"title" => "Gridlines",
							"dataType" => "continuous",
							"fields" => [
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[baseline]", 
										"title" => __("Axis Baseline", $this->plugin),
                    "type" => "number",
                    "min" => 1,
										"max" => 1000,
										"step" => 1,
                    "value" => $this->options()['baseline'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false
                  ],
									[
                    "id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[ticks]",
                    'cssClasses' => ['array-field'],
										"title" => __("Ticks", $this->plugin),
                    "type" => "text",
                    "value" => is_array($this->options()['ticks']) ? implode(',', $this->options()['ticks']) : $this->options()['ticks'],
                    "hint" => "Enter comma separated values for each tick.  Leave blank for automatically generated ticks. Examples: 5,10,15,20",
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[baselineColor]", 
										"cssClasses" => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? ['text'] : ["color-picker"],
										"title" => __("Axis Baseline Color", $this->plugin),
                    "type" => "color-picker", 
                    "value" => $this->options()['baselineColor'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false
                  ],
                ],	
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[gridlines][count]", 
										"title" => __("Gridlines Count", $this->plugin),
										"type" => "number", 
										"min" => -1,
										"max" => 1000,
                    "step" =>1,
                    "value" =>$this->options()['gridlines']['count'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false,
                    'hint' => <<<ENDHTML
The approximate number of horizontal gridlines inside the chart area. If you specify a positive number for gridlines.count, it will be used to compute the minSpacing between gridlines. You can specify a value of 1 to only draw one gridline, or 0 to draw no gridlines. Specify -1, which is the default, to automatically compute the number of gridlines based on other options.'
ENDHTML
                  ],
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[minorGridlines][count]", 
										"title" => __("Minor Gridlines Count", $this->plugin),
										"type" => "number", 
										"default" => null,
										"min" => 0,
										"max" => 1,
                    "step" =>0,
                    "value" => $this->options()['minorGridlines']['count'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false,
                    'hint' => <<<ENDHTML
The minorGridlines.count option is mostly deprecated, except for disabling minor gridlines by setting the count to 0. The number of minor gridlines now depends entirely on the interval between major gridlines (see hAxis.gridlines.interval) and the minimum required space (see hAxis.minorGridlines.minSpacing).
ENDHTML
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[gridlines][color]", 
										"cssClasses" => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? ['text'] : ["color-picker"],
										"title" => __("Gridlines Color", $this->plugin),
                    "type" => "color-picker",
                    "value" => $this->options()['gridlines']['color'],
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false
                  ],
                ],
								[
									[
										"id" => "{$this->axis_params['id']}s{$this->axis_params['axis']}[minorGridlines][color]", 
										"cssClasses" => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? ['text'] : ["color-picker"],
										"title" => __("Minor Gridlines Color", $this->plugin),
                    "type" => "color-picker",
                    "value" =>  $this->options()['minorGridlines']['color'] ,
                    'disabled' => ( $this->major_axis_type === 'discrete' && $this->axis_params['id'] === 'horAxisOption' ) ? true : false
									],
								],
							]
						],

					]
				],
       
      
      ];

		} // END chart_fields
		




	} // END Dashboard

}