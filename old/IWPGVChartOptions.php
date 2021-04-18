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
if (!class_exists('IWPGVChartOptions')) { 
	class IWPGVChartOptions extends IWPGVDashboard  {

		// Declare field types
		public $field_types = array("text", "array", "select", "hidden", "file", "number", "checkbox", "color-picker");	// 3 MB
	



		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {



		
			
			

		} // END public function __construct()



		// public function chart_options(){

		// 	return (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

		// }






		function render_input_field ($field) {

			if (! isset($field["id"]) || ! isset($field["fieldType"]) || ! in_array($field["fieldType"], $this->field_types)) {
				$errors->add("file_id_or_file_type_mising", __("File id and file type are required for {$field["id"]}.", $this->plugin));

				return $errors;

			}

			// Strat buffer
			ob_start();

			$wrapper_class = "fieldWrapper ";
			//$wrapper_class .= (isset($field["wrapperClass"]))? $field["wrapperClass"] : "";
			$wrapper_class .= ( isset($field["cssClass"]) && false !== strpos($field["cssClass"], "color-picker") )? "wpColorPicker" : "";

			?>

			
			<div class =  "<?php echo $wrapper_class; ?>">

		      <h2>
		      	<?php echo (isset($field['fieldTitle']))? $field['fieldTitle'] : null; ?>
		      	<?php if (isset($field["fieldHint"])): ?>
		      	<span class='hint'>?</span>
		   		<?php endif; ?>	
		      </h2>
				

		      <?php switch ($field["fieldType"]) {
		      	
		      	case "select":
		      		?>
		      		<select 
					    	<?php echo (isset($field['cssClass']))? "class='{$field['cssClass']}'" : ""; ?> 
					    	<?php echo (isset($field["id"]))? "id='{$field["id"]}'": ""; ?>
					      <?php echo (isset($field["id"]))? "name='{$field["id"]}'": ""; ?>
					      >
					      <?php if (isset($field["nullOption"])): ?>
					      <option value="" <?php selected( $field['value'], "", true); ?> >
					      	<?php echo (isset($field['nullOption']))? $field['nullOption'] : "Select Option"; ?>
					      </option>
					   <?php endif; ?>
					     	<?php foreach ($field['fieldOptions'] as $field_option_key => $field_option)  {?>
					      	<option value="<?php echo $field_option_key; ?>" <?php selected( $field['value'], $field_option_key, true); ?> >
					      		<?php echo $field_option ?>
					      	</option>
					     	<?php } ?>
					   </select>
		      		<?php
		      		break;

		      		case "checkbox":
		      			?>
							<input
								<?php echo (isset($field['cssClass'])) ? "class='{$field['cssClass']}'" : ""; ?>
								<?php echo "id='{$field["id"]}'"; ?>
						  		<?php echo "name='{$field["id"]}'"; ?>
						  		type="checkbox"
						  		value="1"
						  		<?php checked(1, $field['value'], true);?>
						  		<?php echo (isset($field['disabled']) && $field['disabled']) ? "disabled" : ""; ?> 
						   />
							<?php
		      			break;

		      	
		      		default:
		      			?>
							<input 
								<?php echo (isset($field['cssClass']))? "class='{$field['cssClass']}'" : ""; ?> 
					    		<?php echo "id='{$field["id"]}'"; ?>
					      	<?php echo "name='{$field["id"]}'"; ?>
								<?php echo "type= '{$field['fieldType']}'"; ?> 
								<?php echo (isset($field['fieldSize']))? "size='{$field['fieldSize']}'" : ""; ?> 
								<?php echo (isset($field['placeholder']))? "placeholder='{$field['placeholder']}'" : ""; ?> 
								<?php echo (isset($field['pattern']))? "pattern='{$field['pattern']}'" : ""; ?>
								<?php echo (isset($field['fieldMin']))? "min = '{$field["fieldMin"]}'" : ""; ?> 
								<?php echo (isset($field['fieldMax']))? "max = '{$field["fieldMax"]}'" : ""; ?>
								<?php echo (isset($field['fieldStep']))? "step = '{$field["fieldStep"]}'" : ""; ?>
								<?php echo (isset($field['value']))? "value='{$field['value']}'" : "value = '{$field["default"]}'" ; ?> 
							/>
		      			<?php
		      			break;
		     	} ?>

		   	<?php if (isset($field['cssClass']) && strpos($field['cssClass'], "hasFieldSuffix") !== false): ?>
		   		<span class = "fieldSuffix"><?php echo $field["fieldSuffix"]; ?></span>
		   	<?php endif; ?>

		   	<?php if (isset($field['fieldHint'])): ?>
		   		<span class = "fieldHint"><?php echo $field["fieldHint"] ?></span>
		   	<?php endif; ?>

			</div>

			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;

		}







		public function chart_options($fields) {


			$chart_options = array(

				"theme" => "material",

				"width" => $fields["width"],
				"height" => $fields["height"],
				"lineWidth" => $fields["lineWidth"],
				"lineDashStyle" => $fields["lineDashStyle"],
				"fontName" => $fields["fontName"],
				"fontSize" => $fields["fontSize"],
				"pointShape" => $fields["pointShape"],
				"pointSize" => $fields["pointSize"],
				"pointsVisible" => $fields["pointsVisible"],
				"interpolateNulls" => $fields["interpolateNulls"],
				"orientation" => $fields["orientation"],
				"trendlines" => array(),
				"reverseCategories" => $fields["reverseCategories"],
				"selectionMode" => $fields["selectionMode"],
				"aggregationTarget" => $fields["aggregationTarget"],
				// "curveType" => $fields["curveType"],
				"annotations" => array(
					"boxStyle" => array(
						"stroke" => $fields["annotations.boxStyle.stroke"],
						"strokeWidth" => $fields["annotations.boxStyle.strokeWidth"],
						"rx" => $fields["annotations.boxStyle.rx"],
						"ry" => $fields["annotations.boxStyle.ry"],
						"gradient" => array(
							"color1" => $fields["annotations.boxStyle.gradient.color1"],
							"color2" => $fields["annotations.boxStyle.gradient.color2"],
							"x1" => $fields["annotations.boxStyle.gradient.x1"],
							"y1" => $fields["annotations.boxStyle.gradient.y1"],
							"x2" => $fields["annotations.boxStyle.gradient.x2"],
							"y2" => $fields["annotations.boxStyle.gradient.y2"],
							"useObjectBoundingBoxUnits" => $fields["annotations.boxStyle.gradient.useObjectBoundingBoxUnits"]
						)
					)
				),

				"animation" => array(
					"startup" => $fields["animation.startup"],
					"duration" => $fields["animation.duration"],
					"easing" => $fields["animation.easing"],
				),

				"crosshair" => array(
					"color" => $fields["crosshair.color"],
					"trigger" => $fields["crosshair.trigger"],
					"opacity" => $fields["crosshair.opacity"],
				),


				"backgroundColor" => array(
					"fill" => $fields["backgroundColor.fill"],
					"stroke" => $fields["backgroundColor.stroke"],
					"strokeWidth"	=> $fields["backgroundColor.strokeWidth"],
				),

				"title" => $fields["title"],
				"titleTextStyle" => array(
					"color" => $fields["titleTextStyle.color"],
					"fontName" => $fields["titleTextStyle.fontName"],
					"fontSize" => $fields["titleTextStyle.fontSize"],
					"bold" => $fields["titleTextStyle.bold"],
					"italic" => $fields["titleTextStyle.italic"],
				),

				"chartArea" => array(
					"width" => $fields["chartArea.width"],
					"height" => $fields["chartArea.height"],
					"top" => $fields["chartArea.top"],
					"left" => $fields["chartArea.left"],
					"backgroundColor" => array(
						"fill" => $fields["chartArea.backgroundColor.fill"],
						"stroke" => $fields["chartArea.backgroundColor.stroke"],
						"strokeWidth"	=> $fields["chartArea.backgroundColor.strokeWidth"],
					),
					
				),

				"legend" => array(
					"alignment"=> $fields["legend.alignment"],
					"position"=> $fields["legend.position"],
					"maxLines"=> $fields["legend.maxLines"],
					"textStyle" => array(
						"color" => $fields["legend.textStyle.color"],
						"fontName" => $fields["legend.textStyle.fontName"],
						"fontSize" => $fields["legend.textStyle.fontSize"],
						"bold" => $fields["legend.textStyle.bold"],
						"italic" => $fields["legend.textStyle.italic"],
					),
				),

				"tooltip" => array(
					"text"=> $fields["tooltip.text"],
					"showColorCode"=> $fields["tooltip.showColorCode"],
					"trigger" => $fields["tooltip.trigger"],
					"textStyle" => array(
						"color" => $fields["tooltip.textStyle.color"],
						"fontName" => $fields["tooltip.textStyle.fontName"],
						"fontSize" => $fields["tooltip.textStyle.fontSize"],
						"bold" => $fields["tooltip.textStyle.bold"],
						"italic" => $fields["tooltip.textStyle.italic"],
					),
				),
				"titlePosition" => $fields["titlePosition"],
			
				"hAxis" => array(
					"title" => $fields["hAxis.title"],
					"baseline"=> $fields["hAxis.baseline"],
					"baselineColor"=> $fields["hAxis.baselineColor"],
					"ticks" => $fields["hAxis.ticks"],
					"direction" => $fields["hAxis.direction"],
					"format" => $fields["hAxis.format"],
					"minValue" => $fields["hAxis.minValue"],
					"maxValue" => $fields["hAxis.maxValue"],
					"viewWindowMode" => $fields["hAxis.viewWindowMode"],
					"showTextEvery" => $fields["hAxis.showTextEvery"],
					"slantedText" => $fields["hAxis.slantedText"],
					"slantedTextAngle" => $fields["hAxis.slantedTextAngle"],
					"allowContainerBoundaryTextCufoff" => $fields["hAxis.allowContainerBoundaryTextCufoff"],
					"maxAlternation" => $fields["hAxis.maxAlternation"],
					"maxTextLines" => $fields["hAxis.maxTextLines"],
					"minTextSpacing" => $fields["hAxis.minTextSpacing"],
					"viewWindow" => array(
						"min" => $fields["hAxis.viewWindow.min"],
						"max" => $fields["hAxis.viewWindow.max"],
					),

					"titleTextStyle" => array(
						"color" => $fields["hAxis.titleTextStyle.color"],
						"fontName" => $fields["hAxis.titleTextStyle.fontName"],
						"fontSize" => $fields["hAxis.titleTextStyle.fontSize"],
						"bold" => $fields["hAxis.titleTextStyle.bold"],
						"italic" => $fields["hAxis.titleTextStyle.italic"],
					),
					"textStyle" => array(
						"color" => $fields["hAxis.textStyle.color"],
						"fontName" => $fields["hAxis.textStyle.fontName"],
						"fontSize" => $fields["hAxis.textStyle.fontSize"],
						"bold" => $fields["hAxis.textStyle.bold"],
						"italic" => $fields["hAxis.textStyle.italic"],
					),
					"gridlines" => array(
						"color" => $fields["hAxis.gridlines.color"],
						"count" => $fields["hAxis.gridlines.count"],
					),
					"minorGridlines" => array(
						"color" => $fields["hAxis.minorGridlines.color"],
						"count" => $fields["hAxis.minorGridlines.count"],
					),
					// "logScale" => $fields["hAxis.logScale"],
					"scaleType" => $fields["hAxis.scaleType"],
				),


				"vAxes" => array(
					"0" => array(
						"title" => $fields["vAxes.0.title"],
						// "titlePosition" => $fields["vAxes.0.titlePosition"],
						"baseline"=> $fields["vAxes.0.baseline"],
						"baselineColor"=> $fields["vAxes.0.baselineColor"],
						"ticks" => $fields["vAxes.0.ticks"],
						"direction" => $fields["vAxes.0.direction"],
						"format" => $fields["vAxes.0.format"],
						"minValue" => $fields["vAxes.0.minValue"],
						"maxValue" => $fields["vAxes.0.maxValue"],
						"viewWindowMode" => $fields["vAxes.0.viewWindowMode"],
						"viewWindow" => array(
							"min" => $fields["vAxes.0.viewWindow.min"],
							"max" => $fields["vAxes.0.viewWindow.max"],
						),

						"titleTextStyle" => array(
							"color" => $fields["vAxes.0.titleTextStyle.color"],
							"fontName" => $fields["vAxes.0.titleTextStyle.fontName"],
							"fontSize" => $fields["vAxes.0.titleTextStyle.fontSize"],
							"bold" => $fields["vAxes.0.titleTextStyle.bold"],
							"italic" => $fields["vAxes.0.titleTextStyle.italic"],
						),
						"textStyle" => array(
							"color" => $fields["vAxes.0.textStyle.color"],
							"fontName" => $fields["vAxes.0.textStyle.fontName"],
							"fontSize" => $fields["vAxes.0.textStyle.fontSize"],
							"bold" => $fields["vAxes.0.textStyle.bold"],
							"italic" => $fields["vAxes.0.textStyle.italic"],
						),
						"gridlines" => array(
							"color" => $fields["vAxes.0.gridlines.color"],
							"count" => $fields["vAxes.0.gridlines.count"],
						),
						"minorGridlines" => array(
							"color" => $fields["vAxes.0.minorGridlines.color"],
							"count" => $fields["vAxes.0.minorGridlines.count"],
						),
						// "logScale" => $fields["vAxes.0.logScale"],
						"scaleType" => $fields["vAxes.0.scaleType"],
					),
					"1" => array(
						"title" => $fields["vAxes.1.title"],
						// "titlePosition" => $fields["vAxes.1.titlePosition"],
						"baseline"=> $fields["vAxes.1.baseline"],
						"baselineColor"=> $fields["vAxes.1.baselineColor"],
						"ticks" => $fields["vAxes.1.ticks"],
						"direction" => $fields["vAxes.1.direction"],
						"format" => $fields["vAxes.1.format"],
						"minValue" => $fields["vAxes.1.minValue"],
						"maxValue" => $fields["vAxes.1.maxValue"],
						"viewWindowMode" => $fields["vAxes.1.viewWindowMode"],
						"viewWindow" => array(
							"min" => $fields["vAxes.1.viewWindow.min"],
							"max" => $fields["vAxes.1.viewWindow.max"],
						),

						"titleTextStyle" => array(
							"color" => $fields["vAxes.1.titleTextStyle.color"],
							"fontName" => $fields["vAxes.1.titleTextStyle.fontName"],
							"fontSize" => $fields["vAxes.1.titleTextStyle.fontSize"],
							"bold" => $fields["vAxes.1.titleTextStyle.bold"],
							"italic" => $fields["vAxes.1.titleTextStyle.italic"],
						),
						"textStyle" => array(
							"color" => $fields["vAxes.1.textStyle.color"],
							"fontName" => $fields["vAxes.1.textStyle.fontName"],
							"fontSize" => $fields["vAxes.1.textStyle.fontSize"],
							"bold" => $fields["vAxes.1.textStyle.bold"],
							"italic" => $fields["vAxes.1.textStyle.italic"],
						),
						"gridlines" => array(
							"color" => $fields["vAxes.1.gridlines.color"],
							"count" => $fields["vAxes.1.gridlines.count"],
						),
						"minorGridlines" => array(
							"color" => $fields["vAxes.1.minorGridlines.color"],
							"count" => $fields["vAxes.1.minorGridlines.count"],
						),
						//"logScale" => $fields["vAxes.1.logScale"],
						"scaleType" => $fields["vAxes.1.scaleType"],
					),
				),

				"is3D" => $fields["is3D"],
				"pieHole" => $fields["pieHole"],
				"pieSliceBorderColor" => $fields["pieSliceBorderColor"],
				"pieSliceText" => $fields["pieSliceText"],
				"pieStartAngle" => $fields["pieStartAngle"],
				"sliceVisibilityThreshold" => $fields["sliceVisibilityThreshold"],
				"pieResidueSliceLabel" => $fields["pieResidueSliceLabel"],
				"pieResidueSliceColor" => $fields["pieResidueSliceColor"],
				"pieSliceTextStyle" => array(
					"color" => $fields["pieSliceTextStyle.color"],
					"fontName" => $fields["pieSliceTextStyle.fontName"],
					"fontSize" => $fields["pieSliceTextStyle.fontSize"],
					"bold" => $fields["pieSliceTextStyle.bold"],
					"italic" => $fields["pieSliceTextStyle.italic"],
				)
			);

			// return options
			return $chart_options;
		
		}





		public function num_range_options($fields) {

			$num_range_options = array(

				"filterColumnIndex" => $fields["filterColumnIndex"],
				"minValue" => $fields["minValue"],
				"maxValue" => $fields["maxValue"],
				"ui" => array(
					"orientation" =>  $fields["ui.orientation"],
					"label" =>  $fields["ui.label"],
					"labelSeparator" =>  $fields["ui.labelSeparator"],
					"labelStacking" =>  $fields["ui.labelStacking"],
					"cssClass" =>  $fields["ui.cssClass"],
					"step" => $fields["ui.step"],
					"showRangeValues" =>  $fields["ui.showRangeValues"],
				)
			);

			// return options
			return $num_range_options;
		}





		public function chart_range_filter_options($fields) {

			$chart_range_options = array(

				"filterColumnIndex" => $fields["filterColumnIndex"],
				 "ui" => array(
				 	"chartType" =>  $fields["ui.chartType"],
				 	"chartOptions" => array(
				 		"width" =>  $fields["ui.chartOptions.width"],
				 		"height" =>  $fields["ui.chartOptions.height"],
				 		"lineWidth" => $fields["ui.chartOptions.lineWidth"],
				 		"backgroundColor" => array(
							"fill" => $fields["ui.chartOptions.backgroundColor.fill"],
							"stroke" => $fields["ui.chartOptions.backgroundColor.stroke"],
							"strokeWidth"	=> $fields["ui.chartOptions.backgroundColor.strokeWidth"],
						),
				 		"chartArea" => array(
				 			"width" => $fields["ui.chartOptions.chartArea.width"],
							"height" => $fields["ui.chartOptions.chartArea.height"],
							"top" => $fields["ui.chartOptions.chartArea.top"],
							"left" => $fields["ui.chartOptions.chartArea.left"],
							"backgroundColor" => array(
								"fill" => $fields["ui.chartOptions.chartArea.backgroundColor.fill"],
								"stroke" => $fields["ui.chartOptions.chartArea.backgroundColor.stroke"],
								"strokeWidth"	=> $fields["ui.chartOptions.chartArea.backgroundColor.strokeWidth"],
							),
						 ),
				 	),
				// 	"caption" =>  $fields["ui.caption"],
				// 	"label" =>  $fields["ui.label"],
				// 	"selectedValuesLayout" =>  $fields["ui.selectedValuesLayout"],
				// 	"labelSeparator" =>  $fields["ui.labelSeparator"],
				// 	"labelStacking" =>  $fields["ui.labelStacking"],
				// 	"cssClass" =>  $fields["ui.cssClass"],
				 )
			);

			// return options
			return $chart_range_options;
		}





		

		public function category_filter_options($fields) {

			$category_filter_options = array(

				"filterColumnIndex" => $fields["filterColumnIndex"],
				"ui" => array(
					"caption" =>  $fields["ui.caption"],
					"label" =>  $fields["ui.label"],
					"selectedValuesLayout" =>  $fields["ui.selectedValuesLayout"],
					"labelSeparator" =>  $fields["ui.labelSeparator"],
					"labelStacking" =>  $fields["ui.labelStacking"],
					"cssClass" =>  $fields["ui.cssClass"],
				)
			);

			// return options
			return $category_filter_options;
		}





		public function string_filter_options($fields) {

			$string_filter_options = array(

				"filterColumnIndex" => $fields["filterColumnIndex"],
				"ui" => array(
					"caption" =>  $fields["ui.caption"],
					"label" =>  $fields["ui.label"],
					"selectedValuesLayout" =>  $fields["ui.selectedValuesLayout"],
					"labelSeparator" =>  $fields["ui.labelSeparator"],
					"labelStacking" =>  $fields["ui.labelStacking"],
					"cssClass" =>  $fields["ui.cssClass"],
				)
			);

			// return options
			return $string_filter_options;
		}







		
		public function table_chart_options($fields) {

			$table_chart_options = array(

				"alternatingRowStyle" => $fields["alternatingRowStyle"],
				"width" => $fields["width"],
				"height" => $fields["height"],
				"allowHtml" => $fields["allowHtml"],
				"showRowNumber" => $fields["showRowNumber"],
				"firstRowNumber" => $fields["firstRowNumber"],
				"frozenColumns" => $fields["frozenColumns"],
				"page" => $fields["page"],
				"sort" => $fields["sort"],
				"sortAscending" => $fields["sortAscending"],
				"sortColumn" => $fields["sortColumn"],
				"startPage" => $fields["startPage"],
				"cssClassNames" => array(
					"headerRow " => $fields["cssClassNames.headerRow"],
					"tableRow " => $fields["cssClassNames.tableRow"],
					"oddTableRow " => $fields["cssClassNames.oddTableRow"],
					"selectedTableRow " => $fields["cssClassNames.selectedTableRow"],
					"hoverTableRow" => $fields["cssClassNames.hoverTableRow"],
					"headerCell" => $fields["cssClassNames.headerCell"],
					"tableCell " => $fields["cssClassNames.tableCell"],
					"rowNumberCell " => $fields["cssClassNames.rowNumberCell"],
				)
			);

			// return options
			return $table_chart_options;
		}







		public function min_max_avg_table_options($fields) {

			$min_max_avg_table_options = array(

				"alternatingRowStyle" => $fields["alternatingRowStyle"],
				"width" => $fields["width"],
				"height" => $fields["height"],
				"allowHtml" => $fields["allowHtml"],
				"showRowNumber" => $fields["showRowNumber"],
				"firstRowNumber" => $fields["firstRowNumber"],
				"frozenColumns" => $fields["frozenColumns"],
				"page" => $fields["page"],
				"sort" => $fields["sort"],
				"sortAscending" => $fields["sortAscending"],
				"sortColumn" => $fields["sortColumn"],
				"startPage" => $fields["startPage"],
				"cssClassNames" => array(
					"headerRow " => $fields["cssClassNames.headerRow"],
					"tableRow " => $fields["cssClassNames.tableRow"],
					"oddTableRow " => $fields["cssClassNames.oddTableRow"],
					"selectedTableRow " => $fields["cssClassNames.selectedTableRow"],
					"hoverTableRow" => $fields["cssClassNames.hoverTableRow"],
					"headerCell" => $fields["cssClassNames.headerCell"],
					"tableCell " => $fields["cssClassNames.tableCell"],
					"rowNumberCell " => $fields["cssClassNames.rowNumberCell"],
				)


			);

			// return options
			return $min_max_avg_table_options;
		}







	 

	 	public function legend_position($chart_type) {

		 	switch ($chart_type) {
		 		case "LineChart":
		 			return array(
						"right" => "Right",
						"left" => "Left",
						"top" => "Above",
						"bottom" => "Below",
						"in" => "Inside",
						"none" => "Omit",	
					);
		 			break;

		 		case "PieChart":
		 			return array(
		 				"right" => "Right",
						"left" => "Left",
						"top" => "Above",
						"bottom" => "Below",
						"labeled" => "Connected",
						"none" => "Omit Legend",	
					);
					break;
		 		
		 		default:
		 			return array();
		 			break;
		}


	}

	public function series ($index) {

		 return array(
			"id" => "series",
			"title" => "Series",
			"chartTypes" => array("LineChart"), //array("LineChart", "ScatterChart", "BarChart", "ColumnChart"),
			"subpanels" => array(
				"id" => "series-0",
				"title" => "Series 0",
				"fields" =>array(
					array(
						array(
							"id" => "chart.series.{$index}.curveType",  // id attribute if the field
							"fieldTitle" => __("Curve Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								"none" => "Linear",
								"function" => "Function",
							),
							//"nullOption" => "Curve Type",
							"default" => "function",
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.targetAxisIndex",  // id attribute if the field
							"fieldTitle" => __("Target Axis Index", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								0 => "Default Axis",
								1 => "Opposite Axis",
							),
							//"nullOption" => "Target Axis Index",
							//"chartTypes" => array(),
							"default" => 0,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.color", 
							"cssClass" => "chartOption color-picker",
							"fieldTitle" => __("Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => null,
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.visibleInLegend",  
							"fieldTitle" => __("Visible in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => true,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.labelInLegend",  
							"fieldTitle" => __("Label in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text", 
							"default" => null,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.lineWidth",  // id attribute if the field
							"fieldTitle" => __("Line Width", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => null,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.lineDashStyle",  // id attribute if the field
							"fieldTitle" => __("Line Dash Style", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
							"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => null,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.pointsVisible",  
							"fieldTitle" => __("Points Visible", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => null,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.pointShape.type",  // id attribute if the field
							"fieldTitle" => __("Point Shape Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								"circle" => "Circle",
								"triangle" => "Triangle",
								"square" => "Square",
								"diamond" => "Diamond",
								"star" => "Star"
							),
							//"nullOption" => "Point Shape",
							// "chartTypes" => array(),
							"default" => "circle",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.pointShape.sides",  // id attribute if the field
							"fieldTitle" => __("Point Shape Sides", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 5,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.pointShape.dent",  // id attribute if the field
							"fieldTitle" => __("Point Shape Dent", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"fieldMin" => 0,
							"fieldMax" => 1,
							"fieldStep" => .01,
							"default" => .5,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.pointSize",  // id attribute if the field
							"fieldTitle" => __("Point Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 10,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.pointShape.rotation",  // id attribute if the field
							"fieldTitle" => __("Pt. Shape Rotation", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"fieldMin" => -179,
							"fieldMax" => 179,
							"fieldStep" => 10,
							"default" => 0,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.annotations.textStyle.fontName",  // id attribute if the field
							"fieldTitle" => __("Font Text Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								"arial" => "Arial",
								"courier" => "Courier",
								"verdana" => "Verdana",
								"georgia" => " Georgia",
								"tahoma" => "Tahoma"
							),
							"nullOption" => "Select Font",
							"default" => "verdana",
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.annotations.textStyle.fontSize",  // id attribute if the field
							"fieldTitle" => __("Annotation Text Style Font Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 30,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.annotations.textStyle.color", 
							"cssClass" => "chartOption color-picker",
							"fieldTitle" => __("Annotations Text Style Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "teal",
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.annotations.textStyle.bold",  // id attribute if the field
							"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
	
							"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => true,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.series.{$index}.annotations.textStyle.italic",  // id attribute if the field
							"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
	
							"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => true,
							"cssClass" => "chartOption"
						),
					
					),
					array(
						array(
							"id" => "chart.series.{$index}.annotations.textStyle.auraColor", 
							"cssClass" => "chartOption color-picker",
							"fieldTitle" => __("Annotations Aura Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "teal",
						),
					),
					array(
						array(
							"id" => "chart.series.{$index}.annotations.textStyle.opacity",  // id attribute if the field
							"fieldTitle" => __("Annotation Text Style Font Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
								"fieldMin" => 0,
								"fieldMax" => 1,
								"fieldStep" => .1,
							"default" => .5,
							"cssClass" => "chartOption"
						),
					),
				)
			)
			
		);


	}



	public function trendlines ($index) {

		 return array(
			"id" => "trendlines",
			"title" => "Trendlines",
			"chartTypes" => array("LineChart"), //, "ScatterChart", "BarChart", "ColumnChart"),
			"dataType" => "continuous",
			"subpanels" => array(
				"id" => "trendlines-0",
				"title" => "Trendlines 0",
				"fields" =>array(
					array(
						array(
							"id" => "chart.trendlines.{$index}.enable",  
							"fieldTitle" => __("Enable Trendline", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.type",  // id attribute if the field
							"fieldTitle" => __("Type", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								"linear" => "Linear",
								"polynomial" => "Polynomial",
								"exponential" => "Exponential",
							),
							//"nullOption" => "Trendline Type",
							"default" => "linear",
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.trendlines.{$index}.degree",  // id attribute if the field
							"fieldTitle" => __("Degree", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 3,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.color", 
							"cssClass" => "chartOption color-picker",
							"fieldTitle" => __("Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "red",
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.visibleInLegend",  
							"fieldTitle" => __("Visible in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.trendlines.{$index}.labelInLegend",  
							"fieldTitle" => __("Label in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text", 
							"default" => "",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.lineWidth",  // id attribute if the field
							"fieldTitle" => __("Line Width", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 3,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.trendlines.{$index}.opacity",  // id attribute if the field
							"fieldTitle" => __("Opacity", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"fieldMin" => 0,
							"fieldMax" => 1,
							"fieldStep" => 0.1,
							"default" => .5,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.pointsVisible",  
							"fieldTitle" => __("Points Visible", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.trendlines.{$index}.pointShape.type",  // id attribute if the field
							"fieldTitle" => __("Point Shape Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								"circle" => "Circle",
								"triangle" => "Triangle",
								"square" => "Square",
								"diamond" => "Diamond",
								"star" => "Star"
							),
							"nullOption" => "Point Shape",
							// "chartTypes" => array(),
							"default" => "",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.pointShape.sides",  // id attribute if the field
							"fieldTitle" => __("Point Shape Sides", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 5,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.trendlines.{$index}.pointShape.dent",  // id attribute if the field
							"fieldTitle" => __("Point Shape Dent", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"fieldMin" => 0,
							"fieldMax" => 1,
							"fieldStep" => .01,
							"default" => .5,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.pointSize",  // id attribute if the field
							"fieldTitle" => __("Point Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 10,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "chart.trendlines.{$index}.pointShape.rotation",  // id attribute if the field
							"fieldTitle" => __("Pt. Shape Rotation", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"fieldMin" => -179,
							"fieldMax" => 179,
							"fieldStep" => 10,
							"default" => 0,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "chart.trendlines.{$index}.showR2",  
							"fieldTitle" => __("Show R2", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
					),

				)
			)
			
		);


	}







		/*
		* Admin Fields
		*/
		public function admin_field_panels($chart_type) {

			// Intialize file selection options array
			$file_select_options = [];

			//var_dump($this->files());die;

			// Loop through files to compse all the file select options
			if ($this->settings()["files"]) {
				foreach($this->settings()["files"] as $file_id => $file) {
					$file_select_options[$file_id] = $file["fileName"]; 
				}
			}

			return array(

				"fileSettings" => array(
					"id" => "fileSettings",
					"title" => "Upload/Select File",
					//"chartTypes" => array("LineChart"),
					"intro" => "Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday).",
					"subpanels" => array(
						"fileStuff" => array(
							"id" => "fileStuff",
							"title" => __("Upload Stuff", $this->plugin),
							"fields" => array(
								array(
									array(
										"id" => "chart.chartId",  // id attribute if the field
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "hidden", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
									),
								),
								array(
									array(
										//"slug" => "file_upload", // field id
										"id" => "chart.fileUpload",  // id attribute if the field
										"fieldTitle" => __("Upload File", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										// "id" => "chart.fileSettings",
										"fieldType" => "file", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
									),
								),
								array(
									array(//"slug" => 'file_select', // field id
										"id" => "chart.fileId",  // id attribute if the field
										"fieldTitle" => __("Select File", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
		
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => $file_select_options,
										"nullOption" => "Select File",
										"wrapperClass" => "hideOnLoad",
										//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
										//"value" => (isset($settings) && isset($settings["fileId"])) ? $settings["fileId"] : "",
										"default" => null,
									),
								),
								array(
									array(
										//"slug" => "sheetId", // field id
										"id" => "chart.sheetId",  // id attribute if the field
										"fieldTitle" => __("Sheet", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
		
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(),
										"nullOption" => "Select Sheet",
										"wrapperClass" => "hideOnLoad",
										//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
										//"value" => (isset($settings) && isset($settings["sheetId"])) ? $settings["sheetId"] : "",
										"default" => null,
									),
								),
								array(
									array(	//"slug" => "chart_type_select", // field id
										"id" => "chart.chartType",  // id attribute if the field
										"fieldTitle" => __("Chart Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => $this->chart_types,
										"nullOption" => "Select Chart Type",
										"wrapperClass" => "hideOnLoad",
										"default" => null,
									),
								),
							)
						)
					)
				),

				"chartSettings" => array(
					"id" => "chartSettings",
					"title" => "Chart Settings",
					// "chartTypes" => array("LineChart"),
					"subpanels" => array(
						"generalSettings" => array(
							"id" => "generalSettings",
							"title" => "General Settings",
							"fields" => array(
								array(
									array(
										"id" => "chart.width",  
										"fieldTitle" => __("Chart Width", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 200,
										"fieldMax" => 2000,
										"fieldStep" => 100,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.height", 
										"fieldTitle" => __("Chart Height", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 400,
										"fieldMin" => 200,
										"fieldMax" => 2000,
										"fieldStep" => 100,
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										"id" => "chart.backgroundColor.stroke",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Background Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "red",
									),
								),
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "chart.fontName",  // id attribute if the field
										"fieldTitle" => __("Font Name", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Select Font",
										"default" => "verdana",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.fontSize",  // id attribute if the field
										"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 8,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.backgroundColor.strokeWidth",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Bg. Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "6",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.orientation",  // id attribute if the field
										"fieldTitle" => __("Chart Orientation", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										),
										//"nullOption" => "Chart Orientation",
										"default" => "horizontal",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.backgroundColor.fill",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", 
										"default" => "#CCCCCC",
									),
								),
								array(
									array(
										"id" => "chart.lineWidth",  // id attribute if the field
										"fieldTitle" => __("Line Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 1,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.lineDashStyle",  // id attribute if the field
										"fieldTitle" => __("Line Dash Style", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.interpolateNulls",  // id attribute if the field
										"fieldTitle" => __("Interpolate", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.reverseCategories",  // id attribute if the field
										"fieldTitle" => __("Reverse Categories", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "chartOption"
									),
								),
								
								array(
									array(
										"id" => "chart.pointsVisible",  // id attribute if the field
										"fieldTitle" => __("Points Visible", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "chartOption"
									),
									// array(
									// 	"id" => "chart.labelInLegend",  // id attribute if the field
									// 	"fieldTitle" => __("Label In Legend", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									// 	"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									// 	"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									// 	"default" => false,
									// 	"cssClass" => "chartOption"
									// ),
								),
								array(
									array(
										"id" => "chart.pointShape",  // id attribute if the field
										"fieldTitle" => __("Ponit Shape", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"circle" => "Circle",
											"triangle" => "Triangle",
											"square" => "Square",
											"diamond" => "Diamond",
											"star" => "Star"
										),
										"nullOption" => "Point Shape",
										// "chartTypes" => array(),
										"default" => "circle",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.pointSize",  // id attribute if the field
										"fieldTitle" => __("Point Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 1,
										"cssClass" => "chartOption"
									),
								),
							)
						),

						"chartTitle" => array(
							"id" => "chartTitle",
							"title" => "Chart Title",
							"fields" => array(
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "chart.title",  // id attribute if the field
										"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
		
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "HELLO",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.titlePosition",  // id attribute if the field
										"fieldTitle" => __("Title Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										//"nullOption" => "Title Position",
										// "chartTypes" => array("Line"),
										"default" => "out",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.titleTextStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "yellow",
										//"cssClass" => "chartOption"
									),
								),

								array(
									array(
										"id" => "chart.titleTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "arial",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "chartOption"
									),
								)
							)
						),
						
						"chartArea" => array(
							"id" => "chartArea",
							"title" => "Chart Area",
							"fields" => array(
								array(
									array(
										"id" => "chart.chartArea.width",  // id attribute if the field
										"fieldTitle" => __("Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 50,
										"fieldMax" => 2000,
										"fieldStep" => 10,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.chartArea.height",  // id attribute if the field
										"fieldTitle" => __("Height", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 50,
										"fieldMax" => 2000,
										"fieldStep" => 10,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.chartArea.top",  // id attribute if the field
										"fieldTitle" => __("Top Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 20,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%"									),
									array(
										"id" => "chart.chartArea.left",  // id attribute if the field
										"fieldTitle" => __("Left Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 10,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%"
									),
								),
								array(
									array(
										"id" => "chart.chartArea.backgroundColor.fill",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "yellow",
									),
								),
								array(
									array(
										"id" => "chart.chartArea.backgroundColor.stroke",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "blue",
									),
								),
								array(
									array(
										"id" => "chart.chartArea.backgroundColor.strokeWidth",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 2,
										"cssClass" => "chartOption"
									)
								),
							)
						),

						"legend" => array(
							"id" => "legend",
							"title" => "Legend",
							"fields" => array(
								array(
									array(
										"id" => "chart.legend.alignment",  // id attribute if the field
										"fieldTitle" => __("Alignment", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"start" => "Start",
											"center" => "Center",
											"end"  => "End"
										),
										//"nullOption" => "Select Alignment",
										"default" => "center",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.legend.position",  // id attribute if the field
										"fieldTitle" => __("Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => $this->legend_position($chart_type),
										"nullOption" => "Chart Position",
										"default" => "right",
										"cssClass" => "chartOption",
										"fieldHint" => "Position of the legend. Can be one of the following:<br>
										<ul>
											<li>bottom' - Below the chart.
											<li>'left' - To the left of the chart, provided the left axis has no series associated with it. So if you want the legend on the left, use the option targetAxisIndex: 1</li>
											<li>'in' - Inside the chart, by the top left corner</li>
											<li>'none' - No legend is displayed</li>
											<li>'right' - To the right of the chart. Incompatible with the vAxes option</li>
											<li>'top' - Above the chart</li>
										</ul>"
									),
								),
								array(
									array(
										"id" => "chart.legend.maxLines",  // id attribute if the field
										"cssClass" => "maxLines",
										"fieldTitle" => __("Maximum Number of Lines", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args) 
										"default" => 3,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.legend.textStyle.color",  // id attribute if the field
										"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"cssClass" => "chartOption color-picker",
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "green",
										//"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.legend.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Font Name", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "georgia",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.legend.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 15,
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										"id" => "chart.legend.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.legend.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									)
								)
							)
						),

						"annotations" => array(
							"id" => "annotations",
							"title" => "Annotations",
							"fields" => array(
								array(
									array(
										"id" => "chart.annotations.boxStyle.strokeWidth",  // id attribute if the field
										"fieldTitle" => __("Box Stroke Width", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 2,
										"cssClass" => "chartOption",
										"fieldHint" => "Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday).Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday)."
									),
									array(
										"id" => "chart.annotations.boxStyle.gradient.useObjectBoundingBoxUnits",
										"fieldTitle" => __("Object Bounding Unit", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",				
										"fieldType" => "checkbox", 
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.annotations.boxStyle.stroke", 
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Stroke Color", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text",
										"default" => "grey",
									),
								),
								array(
									array(
										"id" => "chart.annotations.boxStyle.rx",  // id attribute if the field
										"fieldTitle" => __("Box X-radius", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 2,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.annotations.boxStyle.ry",  // id attribute if the field
										"fieldTitle" => __("Box Y-radius", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 2,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.annotations.boxStyle.gradient.color1", 
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Annotations Gradient 1", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text",
										"default" => "red",
									),
								),
								array(
									array(
										"id" => "chart.annotations.boxStyle.gradient.color2", 
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Annotations Gradient 2", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text",
										"default" => "green",
									),
								),
								array(
									array(
										"id" => "chart.annotations.boxStyle.gradient.x1",  
										"fieldTitle" => __("X Start", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"fieldMin" => 0,
										"fieldMax" => 100,
										"default" => 0,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%"
									),
									array(
										"id" => "chart.annotations.boxStyle.gradient.y1",  
										"fieldTitle" => __("Y Start", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number", 
										"default" => 0,
										"fieldMin" => 0,
										"fieldMax" => 100,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%",
										//"dataType" => "continuous"
									),
								),
								array(
									array(
										"id" => "chart.annotations.boxStyle.gradient.x2",  
										"fieldTitle" => __("X End", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number", 
										"default" => 100,
										"fieldMin" => 0,
										"fieldMax" => 100,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%",
										// "chartTypes" => array("PieChart"),
									),
									array(
										"id" => "chart.annotations.boxStyle.gradient.y2",  
										"fieldTitle" => __("Y End", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number", 
										"default" => 100,
										"fieldMin" => 0,
										"fieldMax" => 100,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%",
										"fieldHint" => "Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday).Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday).",
										"dataType" => "discrete"
									),
								),
							)
						),
						"animation" => array(
							"id" => "animation",
							"title" => "Animation",
							// "chartTypes" => array("PieChart"),
							"fields" => array(
								array(
									array(
										"id" => "chart.animation.startup",  // id attribute if the field
										"fieldTitle" => __("Startup", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.animation.duration",  // id attribute if the field
										"fieldTitle" => __("Duration", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 500,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "chart.animation.easing",  // id attribute if the field
										"fieldTitle" => __("Easing", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"linear" => "Linear",
											"in" => "In",
											"out" => "Out",
											"inAndOut" => "In and Out",
										),
										//"nullOption" => "Easing",
										"default" => "inAndOut",
										"cssClass" => "chartOption"
									),
								)
							)
						),
						"crosshair" => array(
							"id" => "crosshair",
							"title" => "Crosshair",
							// "chartTypes" => array("PieChart"),
							"fields" => array(
								array(
									array(
										"id" => "chart.crosshair.opacity",  
										"fieldTitle" => __("Opacity", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 1,
										"fieldMin" => 0,
										"fieldMax" => 1,
										"fieldStep" => .1,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.crosshair.trigger", 
										"fieldTitle" => __("Trigger", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "select", 
										"fieldOptions" => array(
											"focus" => "Focus",
											"selection" => "Selection",
											"both" => "Both",
										),
										//"nullOption" => "Easing",
										"default" => "focus",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.crosshair.color", 
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Color", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text",
										"default" => "magenta",
									),
								),
							)
						),	
					)
				),

				"horizontalAxis" => array(
					"id" => "horizontalAxis",
					"title" => "Horizontal Axis",
					"chartTypes" => array("LineChart"),
					"subpanels" => array(
						"general" => array(
							"id" => "general",
							"title" => "General Settings",
							// "chartTypes" => array("LineChart"),
							"fields" => array(
								array(
									array(
										"id" => "chart.hAxis.viewWindowMode",  // id attribute if the field
										"fieldTitle" => __("View Window Mode", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"pretty" => "Pretty",
											"maximized" => "Maximized",
										),
										//"nullOption" => "Select Font",
										"default" => "maximized",
										"cssClass" => "chartOption",
										//"dataType" => "continuous"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.viewWindow.min",  // id attribute if the field
										"fieldTitle" => __("View Window Min.", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.hAxis.viewWindow.max",  // id attribute if the field
										"fieldTitle" => __("View Window Max.", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										"id" => "chart.hAxis.minValue",  // id attribute if the field
										"fieldTitle" => __("Minimum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption",
										"dataType" => "continuous"

									),
									array(
										"id" => "chart.hAxis.maxValue",  // id attribute if the field
										"fieldTitle" => __("Maximum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption",
										"dataType" => "continuous"
									),
								),		
								array(
									array(
										"id" => "chart.hAxis.direction",  // id attribute if the field
										"fieldTitle" => __("Direction", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"1" => "Left to Right",
											"-1" => "Right to Left",
										),
										"nullOption" => "Axis Direction",
										// "chartTypes" => array("Line"),
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.hAxis.format",  // id attribute if the field
										"fieldTitle" => __("Format", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "Auto",
											"decimal" => "thousands",
											"scientific" => "scientific",
											"currency" => "currency",
											"percent" => "percentages",
											"short" => "abbreviated",
											"long" => "words",
										),
										"nullOption" => "Format",
										// "chartTypes" => array("Line"),
										"default" => "",
										"cssClass" => "chartOption",
										"dataType" => "continuous"
									),
								),
								
								array(
									// array(
									// 	"id" => "chart.hAxis.logScale",  // id attribute if the field
									// 	"fieldTitle" => __("Log Scale", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									// 	"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									// 	"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									// 	"default" => false,
									// 	"cssClass" => "chartOption",
									// 	"dataType" => "continuous",
									// 	"fieldHint" => "hAxis property that makes the horizontal axis a logarithmic scale (requires all values to be positive). Set to true for yes. This option is only supported for a continuous axis."
									// ),
									array(
										"id" => "chart.hAxis.scaleType",  // id attribute if the field
										"fieldTitle" => __("Scale Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"null" => "No Log Scaling",
											"log" => "Log Scaling",
											"mirrorLog" => "Mirror Log"
										),
										//"nullOption" => "Scale Type",
										"default" => "null",
										"cssClass" => "chartOption",
										"dataType" => "continuous",
										"fieldHint" => "hAxis property that makes the horizontal axis a logarithmic scale. Can be one of the following:
										<ul>
										<li> - null: No logarithmic scaling is performed</li>
										<li> - log: Logarithmic scaling. Negative and zero values are not plotted. This option is the same as setting hAxis: { logscale: true }</li>
										<li> - mirrorLog: Logarithmic scaling in which negative and zero values are plotted. The plotted value of a negative number is the negative of the log of the absolute value. Values close to 0 are plotted on a linear scale.</li>
										</ul>
										This option is only supported for a continuous axis."
									),
								),
								array(
									array(
										"id" => "chart.hAxis.showTextEvery",  // id attribute if the field
										"fieldTitle" => __("Show Text Every", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => 1,
										"fieldMax" => 1000,
										"fieldStep" => 1,
										// "chartTypes" => array("Line"),
										"default" => 1,
										"cssClass" => "chartOption",
										"dataType" => "discrete"
									),
								)
							)
						),

						"title" => array(
							"id" => "title",
							"title" => "Title",
							"fields" => array(
								array(
									array(
										"id" => "chart.hAxis.title",  // id attribute if the field
										"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("Line"),
										"default" => "World",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.titleTextStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Title Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "yellow",
										//"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.titleTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Title Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.hAxis.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Title Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 20,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Title Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.hAxis.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Title Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								),

							)
						),

						"label" => array(
							"id" => "label",
							"title" => "Label",
							"fields" => array(
								array(
									array(
										"id" => "chart.hAxis.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Text Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.hAxis.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Text Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.textStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Text Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "teal",
										//"cssClass" => "chartOption"
									),
								),							
								array(
									array(
										"id" => "chart.hAxis.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Text Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.hAxis.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Text Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.textPosition",  // id attribute if the field
										"fieldTitle" => __("Text Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										//"nullOption" => "Position",
										// "chartTypes" => array("Line"),
										"default" => "out",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.slantedText",  // id attribute if the field
										"fieldTitle" => __("Slanted Text", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption",
										"dataType" => "discrete"
									),
									array(
										"id" => "chart.hAxis.slantedTextAngle",  // id attribute if the field
										"fieldTitle" => __("Slanted Text Angle", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => -179,
										"fieldMax" => 179,
										"fieldStep" => 1,
										// "chartTypes" => array(
										// 	"Line"
										// ),
										"default" => 45,
										"cssClass" => "chartOption",
										"dataType" => "discrete"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.allowContainerBoundaryTextCufoff",  // id attribute if the field
										"fieldTitle" => __("Allow Container Text Cutoff", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption",
										"dataType" => "discrete",
										"fieldHint" => "If false, will hide outermost labels rather than allow them to be cropped by the chart container. If true, will allow label cropping"
									),
									array(
										"id" => "chart.hAxis.maxAlternation",  // id attribute if the field
										"fieldTitle" => __("Max Alternation", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => 1,
										"fieldMax" => 10,
										"fieldStep" => 1,
										// "chartTypes" => array(
										// 	"Line"
										// ),
										"default" => 2,
										"cssClass" => "chartOption",
										"dataType" => "discrete",
										"fieldHint" => "Maximum number of levels of horizontal axis text. If axis text labels become too crowded, the server might shift neighboring labels up or down in order to fit labels closer together. This value specifies the most number of levels to use; the server can use fewer levels, if labels can fit without overlapping.

											This option is only supported for a discrete axis."
									),
								),
								array(
									array(
										"id" => "chart.hAxis.maxTextLines",  // id attribute if the field
										"fieldTitle" => __("Max Text Lines", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => 1,
										"fieldMax" => 10,
										"fieldStep" => 1,
										// "chartTypes" => array("Line"),
										"default" => 2,
										"cssClass" => "chartOption",
										"dataType" => "discrete",
										"fieldHint" => "Maximum number of lines allowed for the text labels. Labels can span multiple lines if they are too long, and the number of lines is, by default, limited by the height of the available space.

											This option is only supported for a discrete axis."
									),
									array(
										"id" => "chart.hAxis.minTextSpacing",  // id attribute if the field
										"fieldTitle" => __("Min Text Spacing", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => 1,
										"fieldMax" => 10,
										"fieldStep" => 1,
										// "chartTypes" => array("LineChart"),
										"default" => 2,
										"cssClass" => "chartOption",
										"dataType" => "discrete",
										"fieldHint" => "Minimum horizontal spacing, in pixels, allowed between two adjacent text labels. If the labels are spaced too densely, or they are too long, the spacing can drop below this threshold, and in this case one of the label-unclutter measures will be applied (e.g, truncating the lables or dropping some of them).

											This option is only supported for a discrete axis."
									),
								),

							)
						),

						"gridlines" => array(
							"id" => "gridlines",
							"title" => "Gridlines",
							"dataType" => "continuous",
							"fields" => array(
								array(
									array(
										"id" => "chart.hAxis.baseline",  // id attribute if the field
										"fieldTitle" => __("Axis Baseline", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption",
										"dataType" => "continuous"
									),
									array(
										"id" => "chart.hAxis.ticks",  // id attribute if the field
										"fieldTitle" => __("Ticks", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "array", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null, //array(1000,2000),
										"cssClass" => "chartOption",
										"dataType" => "continuous",
										"fieldHint" => "Enter comma separated values for each tick.  Leave blank for automatically generated ticks. Examples: 5,10,15,20"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.baselineColor",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Axis Baseline Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "cyan",
										"dataType" => "continuous"
									),
								),	
								array(
									array(
										"id" => "chart.hAxis.gridlines.count",  // id attribute if the field
										"fieldTitle" => __("Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										// "fieldMin" => 2,
										// "fieldMax" => 10,
										// "fieldStep" =>1,
										"cssClass" => "chartOption",
										"dataType" => "continuous",
										"fieldHint" => "The number of horizontal gridlines inside the chart area. Minimum value is 2. Specify -1 to automatically compute the number of gridlines."
									),
									array(
										"id" => "chart.hAxis.minorGridlines.count",  // id attribute if the field
										"fieldTitle" => __("Minor Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										// "fieldMin" => 0,
										// "fieldMax" => 10,
										// "fieldStep" =>1,
										"cssClass" => "chartOption",
										"dataType" => "continuous",
										"fieldHint" => "The number of horizontal minor gridlines between two regular gridlines."
									),
								),
								array(
									array(
										"id" => "chart.hAxis.gridlines.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Gridlines Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "black",
										"dataType" => "continuous"
									),
								),
								array(
									array(
										"id" => "chart.hAxis.minorGridlines.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Minor Gridlines Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "teal",
										"dataType" => "continuous"
									),
								),
							)
						),

					)
				),

				"leftVerticalAxis" => array(
					"id" => "leftVerticalAxis",
					"title" => "Left Vertical Axis",
					"chartTypes" => array("LineChart"),
					"subpanels" => array(
						"general" => array(
							"id" => "general",
							"title" => "General Settings",
							// "chartTypes" => array("LineChart", "BarChart", "ColumnChart", "ScatterChart"),
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.0.viewWindowMode",  // id attribute if the field
										"fieldTitle" => __("View Window Mode", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"pretty" => "Pretty",
											"maximized" => "Maximized",
											"explicit" => "Explicit",
										),
										"nullOption" => "Select Font",
										"default" => "pretty",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.viewWindow.min",  // id attribute if the field
										"fieldTitle" => __("View Window Min", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => 0,
										//"fieldMax" => 100,
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.viewWindow.max",  // id attribute if the field
										"fieldTitle" => __("View Window Max", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										"id" => "chart.vAxes.0.minValue",  // id attribute if the field
										"fieldTitle" => __("Minimum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.maxValue",  // id attribute if the field
										"fieldTitle" => __("Maximum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.direction",  // id attribute if the field
										"fieldTitle" => __("Direction", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"1" => "Bottom to Top",
											"-1" => "Top to Bottom",
										),
										"nullOption" => "Axis Direction",
										// "chartTypes" => array("Line" ),
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.format",  // id attribute if the field
										"fieldTitle" => __("Format", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "Auto",
											"decimal" => "thousands",
											"scientific" => "scientific",
											"currency" => "currency",
											"percent" => "percentages",
											"short" => "abbreviated",
											"long" => "words",
										),
										"nullOption" => "Format",
										// "chartTypes" => array("Line" ),
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									// array(
									// 	"id" => "chart.vAxes.0.logScale",  // id attribute if the field
									// 	"fieldTitle" => __("Log Scale", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									// 	"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									// 	"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									// 	"default" => false,
									// 	"cssClass" => "chartOption"
									// ),
									array(
										"id" => "chart.vAxes.0.scaleType",  // id attribute if the field
										"fieldTitle" => __("Scale Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "No Log Scaling",
											"log" => "Log Scaling",
											"mirrorLog" => "Negative/Zero Values Plotted",
										),
										"nullOption" => "Scale Type",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
							)
						),

						"title" => array(
							"id" => "title",
							"title" => "Title",
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.0.title",  // id attribute if the field
										"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "World",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.titleTextStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Title Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "yellow",
										//"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.titleTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Title Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Title Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 20,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Title Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Title Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								
							)
						),

						"label" => array(
							"id" => "label",
							"title" => "Label",
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.0.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Text Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Text Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.textStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Text Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "teal",
										//"cssClass" => "chartOption"
									),
								),					
								array(
									array(
										"id" => "chart.vAxes.0.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Text Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Text Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.textPosition",  // id attribute if the field
										"fieldTitle" => __("Text Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Position",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								// array(
								// 	array(
								// 		"id" => "chart.vAxes.0.titlePosition",  // id attribute if the field
								// 		"fieldTitle" => __("Title Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
								// 		"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
								// 		"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
								// 		"fieldOptions" => array(
								// 			"in" => "Inside the Chart",
								// 			"out" => "Outside the Chart",
								// 			"none" => "Omit title"
								// 		),
								// 		"nullOption" => "Position",
								// 		// "chartTypes" => array("Line"),
								// 		"default" => "",
								// 		"cssClass" => "chartOption"
								// 	),
								// ),
							)
						),

						"gridlines" => array(
							"id" => "gridlines",
							"title" => "Gridlines",
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.0.baseline",  // id attribute if the field
										"fieldTitle" => __("AxisBaseline", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.0.ticks",  // id attribute if the field
										"fieldTitle" => __("Ticks", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "array", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption",
										"fieldHint" => "Enter comma separated values for each tick.  Leave blank for automatically generated ticks. Examples: 5,10,15,20"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.baselineColor",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Axis Baseline Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "black",
										//"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.gridlines.count",  // id attribute if the field
										"fieldTitle" => __("Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										// "fieldMin" => -1,
										// "fieldMax" => 10,
										// "fieldStep" =>1,
										"cssClass" => "chartOption",
										"fieldHint" => "The number of horizontal gridlines inside the chart area. Minimum value is 2. Specify -1 to automatically compute the number of gridlines."
									),
									array(
										"id" => "chart.vAxes.0.minorGridlines.count",  // id attribute if the field
										"fieldTitle" => __("Minor Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										// "fieldMin" => 0,
										// "fieldMax" => 10,
										// "fieldStep" =>1,
										"cssClass" => "chartOption",
										"dataType" => "continuous",
										"fieldHint" => "The number of horizontal minor gridlines between two regular gridlines."
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.gridlines.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Gridlines Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "cyan",
									),
								),
								array(
									array(
										"id" => "chart.vAxes.0.minorGridlines.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Minor Gridlines Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "green",
									),
								),
							)
						),
					)
				),
				
				"rightVerticalAxis" => array(
					"id" => "rightVerticalAxis",
					"title" => "Right Vertical Axis",
					"chartTypes" => array("LineChart"),
					"subpanels" => array(
						"general" => array(
							"id" => "general",
							"title" => "General Settings",
							// "chartTypes" => array("LineChart", "BarChart", "ColumnChart", "ScatterChart"),
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.1.viewWindowMode",  // id attribute if the field
										"fieldTitle" => __("View Window Mode", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"pretty" => "Pretty",
											"maximized" => "Maximized",
											"explicit" => "Explicit",
										),
										"nullOption" => "Select Font",
										"default" => "pretty",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.viewWindow.min",  // id attribute if the field
										"fieldTitle" => __("View Window Min", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => 0,
										//"fieldMax" => 100,
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.viewWindow.max",  // id attribute if the field
										"fieldTitle" => __("View Window Max", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										"id" => "chart.vAxes.1.minValue",  // id attribute if the field
										"fieldTitle" => __("Minimum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.maxValue",  // id attribute if the field
										"fieldTitle" => __("Maximum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.direction",  // id attribute if the field
										"fieldTitle" => __("Direction", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"1" => "Bottom to Top",
											"-1" => "Top to Bottom",
										),
										"nullOption" => "Axis Direction",
										// "chartTypes" => array("Line" ),
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.format",  // id attribute if the field
										"fieldTitle" => __("Format", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "Auto",
											"decimal" => "thousands",
											"scientific" => "scientific",
											"currency" => "currency",
											"percent" => "percentages",
											"short" => "abbreviated",
											"long" => "words",
										),
										"nullOption" => "Format",
										// "chartTypes" => array("Line" ),
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									// array(
									// 	"id" => "chart.vAxes.1.logScale",  // id attribute if the field
									// 	"fieldTitle" => __("Log Scale", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									// 	"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									// 	"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									// 	"default" => false,
									// 	"cssClass" => "chartOption"
									// ),
									array(
										"id" => "chart.vAxes.1.scaleType",  // id attribute if the field
										"fieldTitle" => __("Scale Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "No Log Scaling",
											"log" => "Log Scaling",
											"mirrorLog" => "Negative/Zero Values Plotted",
										),
										"nullOption" => "Scale Type",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
							)
						),

						"title" => array(
							"id" => "title",
							"title" => "Title",
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.1.title",  // id attribute if the field
										"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "World",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.titleTextStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Title Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "yellow",
										//"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.titleTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Title Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Title Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 40,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Title Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Title Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								
							)
						),

						"label" => array(
							"id" => "label",
							"title" => "Label",
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.1.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Text Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Font Name",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Text Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.textStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Text Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "teal",
										//"cssClass" => "chartOption"
									),
								),					
								array(
									array(
										"id" => "chart.vAxes.1.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Text Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Text Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.textPosition",  // id attribute if the field
										"fieldTitle" => __("Text Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Position",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								// array(
								// 	array(
								// 		"id" => "chart.vAxes.1.titlePosition",  // id attribute if the field
								// 		"fieldTitle" => __("Title Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
								// 		"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
								// 		"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
								// 		"fieldOptions" => array(
								// 			"in" => "Inside the Chart",
								// 			"out" => "Outside the Chart",
								// 			"none" => "Omit title"
								// 		),
								// 		"nullOption" => "Position",
								// 		// "chartTypes" => array("Line"),
								// 		"default" => "",
								// 		"cssClass" => "chartOption"
								// 	),
								// ),
							)
						),

						"gridlines" => array(
							"id" => "gridlines",
							"title" => "Gridlines",
							"fields" => array(
								array(
									array(
										"id" => "chart.vAxes.1.baseline",  // id attribute if the field
										"fieldTitle" => __("AxisBaseline", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.vAxes.1.ticks",  // id attribute if the field
										"fieldTitle" => __("Ticks", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "array", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption",
										"fieldHint" => "Enter comma separated values for each tick.  Leave blank for automatically generated ticks. Examples: 5,10,15,20"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.baselineColor",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Axis Baseline Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "black",
										//"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.gridlines.count",  // id attribute if the field
										"fieldTitle" => __("Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										// "fieldMin" => 2,
										// "fieldMax" => 10,
										// "fieldStep" =>1,
										"cssClass" => "chartOption",
										"fieldHint" => "The number of horizontal gridlines inside the chart area. Minimum value is 2. Specify -1 to automatically compute the number of gridlines."
									),
									array(
										"id" => "chart.vAxes.1.minorGridlines.count",  // id attribute if the field
										"fieldTitle" => __("Minor Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										// "fieldMin" => 0,
										// "fieldMax" => 10,
										// "fieldStep" =>1,
										"cssClass" => "chartOption",
										"dataType" => "continuous",
										"fieldHint" => "The number of horizontal minor gridlines between two regular gridlines."
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.gridlines.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Gridlines Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "cyan",
									),
								),
								array(
									array(
										"id" => "chart.vAxes.1.minorGridlines.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Minor Gridlines Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "green",
									),
								),
							)
						),
					)
				),

				

				"tooltips" => array(
					"id" => "tooltips",
					"title" => "Tooltips",
					// "chartTypes" => array("LineChart"),
					"subpanels" => array(
						array(
							"id" => "tooltips",
							"title" => "Tooltips",
							"fields" => array(
								array(
									array(
										"id" => "chart.selectionMode",  // id attribute if the field
										"fieldTitle" => __("Selection Mode", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"single" => "Single",
											"multiple" => "Multiple",
										),
										//"nullOption" => "Selection Mode",
										// "chartTypes" => array(),
										"default" => "single",
										"cssClass" => "chartOption",
										"fieldHint" => "When selectionMode is 'multiple', users may select multiple data points."
									),
									array(
										"id" => "chart.aggregationTarget",  // id attribute if the field
										"fieldTitle" => __("Aggregation Target", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"category" => "Category",
											"series" => "Series",
											"auto" => "Auto",
											"none" => "None",
										),
										//"nullOption" => "Aggregation Target",
										// "chartTypes" => array(),
										"default" => "none",
										"cssClass" => "chartOption",
										"fieldHint" => "How multiple data selections are rolled up into tooltips:<br>
											<ul>
											<li>- <strong>category</strong>: Group selected data by x-value</li>
											<li>- series: Group selected data by series</li>
											<li>- auto: Group selected data by x-value if all selections have the same x-value, and by series otherwise</li>
											<li>- none: Show only one tooltip per selection</li>
											</ul>"
									),
								),
								array(
									array(
										"id" => "chart.tooltip.trigger",  // id attribute if the field
										"fieldTitle" => __("Trigger", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"focus" => "Hover",
											"selection" => "Selection",
											"none" => "None",
										),
										"nullOption" => "Select Font",
										"default" => "focus",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.tooltip.text",  // id attribute if the field
										"fieldTitle" => __("Text", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"both" => "Value and Percentage",
											"value" => "Value",
											"percentage" => "Percentage",
										),
										"nullOption" => "Select Font",
										"default" => "both",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.tooltip.textStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Font", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Select Font",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									
									array(
										"id" => "chart.tooltip.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "chart.tooltip.textStyle.color",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "teal",
									),
								),
								array(
									array(
										"id" => "chart.tooltip.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "chart.tooltip.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								
									array(
										"id" => "chart.tooltip.showColorCode",  // id attribute if the field
										"fieldTitle" => __("Show Color Code", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "chartOption"
									),
								)
							)
						),
					)
				),	

				"pieChartSettings" => array(
					"id" => "pieChartSettings",
					"title" => "Pie Chart Settings",
					"chartTypes" => array("PieChart"),
					"subpanels" => array(
						array(
							"id" => "allsettings",
							"title" => "All Settings",
							"fields" => array(									
								array(
									array(
										"id" => "chart.is3D",  // id attribute if the field
										"fieldTitle" => __("3D Pie Chart", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => false
									),
									array(
										"id" => "chart.pieHole",  // id attribute if the field
										"fieldTitle" => __("Pie Hole", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => 0,
										"fieldMax" => 1,
										"fieldStep" => 0.1,
										// "chartTypes" => array("PieChart"),
										"default" => 0.5,
										"disableIf" => array("is3D" => true)
									),
								),
								array(
									array(
										"id" => "chart.sliceVisibilityThreshold",  // id attribute if the field
										"fieldTitle" => __("slice Visibility Threshold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										// "fieldMin" => 0,
										// "fieldMax" => 1,
										// "fieldStep" => 0,
										"default" => 0.1,	
									),
									array(
										"id" => "chart.pieSliceBorderColor",  // id attribute if the field
										"cssClass" => "chartOption color-picker",
										"fieldTitle" => __("Pie Slice Border Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "magenta",
									),
								),
								array(
									array(
										"id" => "chart.pieStartAngle",  // id attribute if the field
										"fieldTitle" => __("Pie Slice Angle", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										// "fieldMin" => 0,
										// "fieldMax" => 360,
										// "fieldStep" => 10,
										"default" => 0,
										//"disableIf" => array("is3D" => true)
									),
									array(
										"id" => "chart.pieResidueSliceLabel",  // id attribute if the field
										"fieldTitle" => __("Pie Residual Slice Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "ZZZ"
									),
								),
								array(
									array(
										"id" => "chart.pieResidueSliceColor",  // id attribute if the field
										"fieldTitle" => __("Pie Residual Slice ColorColor", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"cssClass" => "chartOption color-picker",
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "yellow"
									),
									array(
										"id" => "chart.pieSliceText",  // id attribute if the field
										"fieldTitle" => __("Pie Slice Text", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"label" => "The tooltip will be displayed when the user hovers over the element",
											"none" => " No text is displayed",
											"value" => "The quantitative value of the slice",
											"percentage" => "The percentage of the slice size out of the total",
										),
										"nullOption" => "Select Pie Slice Text",
										// "chartTypes" => array("PieChart"),
										"default" => "label"
									),
								),
								array(
									array(
										"id" => "chart.pieSliceTextStyle.color",  // id attribute if the field
										"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"cssClass" => "chartOption color-picker",
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "white"
									),
									array(
										"id" => "chart.pieSliceTextStyle.fontName",  // id attribute if the field
										"fieldTitle" => __("Font Name", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"arial" => "Arial",
											"courier" => "Courier",
											"verdana" => "Verdana",
											"georgia" => " Georgia",
											"tahoma" => "Tahoma"
										),
										"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "georgia"
									),
								),
								array(
									array(
										"id" => "chart.pieSliceTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => 10
									),
									array(
										"id" => "chart.pieSliceTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => false
									),
									array(
										"id" => "chart.pieSliceTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => true
									)
								),
							)
						)
					)
				),
				"numRangeFilter" => array(
					"id" => "numRangeFilter",
					"title" => "Number Range Filter",
					// "chartTypes" => array("PieChart"),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"subpanels" => array(
						array(
							"id" => "numRangeFilter",
							"title" => __("Number Range Filter", $this->plugin),
							"fields" => array(
								array(
									array(
										"id" => "numRangeFilter.enable",  // id attribute if the field
										"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => false,
										"cssClass" => "numRangeOption"
									),
								),
								array(
									array(
										"id" => "numRangeFilter.filterColumnIndex",  // id attribute if the field
										"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(),
										"nullOption" => "Select Filter Column",
										// "chartTypes" => array("PieChart"),
										"default" => null,
										"cssClass" => "numRangeOption"
									),
								),
								array(
									array(
										"id" => "numRangeFilter.minValue",  // id attribute if the field
										"fieldTitle" => __("Min Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => null,
										"cssClass" => "numRangeOption"
									),
									array(
										"id" => "numRangeFilter.maxValue",  // id attribute if the field
										"fieldTitle" => __("Max Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => null,
										"cssClass" => "numRangeOption"
									),

								),
								array(
									array(
										"id" => "numRangeFilter.ui.label",  // id attribute if the field
										"fieldTitle" => __("Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "",
										"cssClass" => "numRangeOption"
									),
								),
								array(
									array(
										"id" => "numRangeFilter.ui.orientation",  // id attribute if the field
										"fieldTitle" => __("Orientation", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "horizontal",
										"cssClass" => "numRangeOption"
									),
								),
								array(
									array(
										"id" => "numRangeFilter.ui.labelSeparator",  // id attribute if the field
										"fieldTitle" => __("Label Seperator", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "",
										"cssClass" => "numRangeOption"
									),
									array(
										"id" => "numRangeFilter.ui.labelStacking",  // id attribute if the field
										"fieldTitle" => __("Label Stacking", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "horizontal",
										"cssClass" => "numRangeOption"
									),
								),
								array(
									array(
										"id" => "numRangeFilter.ui.showRangeValues",  // id attribute if the field
										"fieldTitle" => __("Show Range Values", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => true,
										"cssClass" => "numRangeOption"
									),
									array(
										"id" => "numRangeFilter.ui.step",  // id attribute if the field
										"fieldTitle" => __("Step", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"fieldMin" => 0.1,
										"fieldMax" => 1000000,
										"fieldStep" => 0.1,
										"default" => 1,
										"cssClass" => "numRangeOption"
									),
								),
								array(
									array(
										"id" => "numRangeFilter.ui.cssClass",  // id attribute if the field
										"fieldTitle" => __("CSS Class", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "",
										"cssClass" => "numRangeOption"
									),
								),
							)
						),
					)
				),

				"chartRangeFilter" => array(
					"id" => "chartRangeFilter",
					"title" => "Chart Range Filter",
					// "chartTypes" => array("PieChart"),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"subpanels" => array(
						array(
							"id" => "chartRangeFilter",
							"title" => __("Chart Range Filter", $this->plugin),
							"fields" => array(
								array(
									array(
										"id" => "chartRangeFilter.enable",  // id attribute if the field
										"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => false,
										"cssClass" => "chartRangeOption"
									),
								),
								array(
									array(
										"id" => "chartRangeFilter.filterColumnIndex",  // id attribute if the field
										"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(),
										"nullOption" => "Select Filter Column",
										// "chartTypes" => array("PieChart"),
										"default" => null,
										"cssClass" => "chartRangeOption"
									),
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartType",  // id attribute if the field
										"fieldTitle" => __("Chart Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"LineChart" => "Line Chart",
											"ScatterChart" => "Scatter Chart",
											"AreaChart" => "Area Chart",
											"ComboChart" => "Combo Chart",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => null,
										"cssClass" => "chartRangeOption"
									),
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.width",  
										"fieldTitle" => __("Range Chart Width", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 200,
										"fieldMax" => 2000,
										"fieldStep" => 10,
										"cssClass" => "chartRangeOption"
									),
									array(
										"id" => "chartRangeFilter.ui.chartOptions.height", 
										"fieldTitle" => __("Range Chart Height", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 50,
										"fieldMin" => 50,
										"fieldMax" => 800,
										"fieldStep" => 10,
										"cssClass" => "chartRangeOption"
									)
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.lineWidth",  // id attribute if the field
										"fieldTitle" => __("Line Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 1,
										"cssClass" => "chartRangeOption"
									),
									
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.chartArea.top",  // id attribute if the field
										"fieldTitle" => __("Top Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 0,
										"cssClass" => "chartRangeOption hasFieldSuffix",
										"fieldSuffix" => "%"									),
									array(
										"id" => "chartRangeFilter.ui.chartOptions.chartArea.left",  // id attribute if the field
										"fieldTitle" => __("Left Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 5,
										"cssClass" => "chartRangeOption hasFieldSuffix",
										"fieldSuffix" => "%"
									),
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.backgroundColor.fill",  // id attribute if the field
										"cssClass" => "chartRangeOption color-picker",
										"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", 
										"default" => "#CCCCCC",
									),
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.backgroundColor.stroke",  // id attribute if the field
										"cssClass" => "chartRangeOption color-picker",
										"fieldTitle" => __("Background Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "red",
									),
								),
								
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.backgroundColor.strokeWidth",  // id attribute if the field
										"fieldTitle" => __("Bg. Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "6",
										"cssClass" => "chartRangeOption"
									),
									array(
										"id" => "chartRangeFilter.ui.chartOptions.chartArea.backgroundColor.strokeWidth",  // id attribute if the field
										"fieldTitle" => __("Chart Area Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 2,
										"cssClass" => "chartRangeOption"
									)
								),
								

								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.chartArea.width",  // id attribute if the field
										"fieldTitle" => __("Chart Area Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 50,
										"fieldMax" => 2000,
										"fieldStep" => 10,
										"cssClass" => "chartRangeOption"
									),
									array(
										"id" => "chartRangeFilter.ui.chartOptions.chartArea.height",  // id attribute if the field
										"fieldTitle" => __("chart Area Height", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 50,
										"fieldMax" => 2000,
										"fieldStep" => 10,
										"cssClass" => "chartRangeOption"
									),
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.chartArea.backgroundColor.fill",  // id attribute if the field
										"cssClass" => "chartRangeOption color-picker",
										"fieldTitle" => __("Chart Area Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "yellow",
									),
								),
								array(
									array(
										"id" => "chartRangeFilter.ui.chartOptions.chartArea.backgroundColor.stroke",  // id attribute if the field
										"cssClass" => "chartRangeOption color-picker",
										"fieldTitle" => __("Chart Area Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "blue",
									),
								),
								
								
							)
						),
					)
				),
				"categoryFilter" => array(
					"id" => "categoryFilter",
					"title" => "Category Filter",
					// "chartTypes" => array("PieChart"),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"subpanels" => array(
						array(
							"id" => "categoryFilter",
							"title" => __("Category Filter", $this->plugin),
							"fields" => array(
								array(
									array(
										"id" => "categoryFilter.enable",  // id attribute if the field
										"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => false,
										"cssClass" => "categoryFilterOption"
									),
								),
								array(
									array(
										"id" => "categoryFilter.filterColumnIndex",  // id attribute if the field
										"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(),
										"nullOption" => "Select Filter Column",
										// "chartTypes" => array("PieChart"),
										"default" => null,
										"cssClass" => "categoryFilterOption"
									),
								),
								array(
									array(
										"id" => "categoryFilter.ui.caption",  // id attribute if the field
										"fieldTitle" => __("Caption", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "",
										"cssClass" => "categoryFilterOption"
									),
									array(
										"id" => "categoryFilter.ui.label",  // id attribute if the field
										"fieldTitle" => __("Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "",
										"cssClass" => "categoryFilterOption"
									),
								),
								array(
									array(
										"id" => "categoryFilter.ui.labelSeparator",  // id attribute if the field
										"fieldTitle" => __("Label Seperator", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "",
										"cssClass" => "categoryFilterOption"
									),
									array(
										"id" => "categoryFilter.ui.labelStacking",  // id attribute if the field
										"fieldTitle" => __("Label Stacking", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "horizontal",
										"cssClass" => "categoryFilterOption"
									),
								),
								array(
									array(
										"id" => "categoryFilter.ui.selectedValuesLayout",  // id attribute if the field
										"fieldTitle" => __("Selected Values Layout", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"aside" => "Aside",
											"below" => "Below",
											"belowWrapping" => "Below Wrapping",
											"belowStacked" => " Below Stacked",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "aside",
										"cssClass" => "categoryFilterOption"
									),
								),
								array(
									array(
										"id" => "categoryFilter.ui.cssClass",  // id attribute if the field
										"fieldTitle" => __("CSS Class", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "",
										"cssClass" => "categoryFilterOption"
									),
								),
							)
						),
					)
				),
				"stringFilter" => array(
					"id" => "stringFilter",
					"title" => "String Filter",
					// "chartTypes" => array("PieChart"),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"subpanels" => array(
						array(
							"id" => "stringFilter",
							"title" => __("String Filter", $this->plugin),
							"fields" => array(
								array(
									array(
										"id" => "stringFilter.enable",  // id attribute if the field
										"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => false,
										"cssClass" => "stringFilterOption"
									),
								),
								array(
									array(
										"id" => "stringFilter.filterColumnIndex",  // id attribute if the field
										"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(),
										"nullOption" => "Select Filter Column",
										// "chartTypes" => array("PieChart"),
										"default" => null,
										"cssClass" => "stringFilterOption"
									),
								),
								array(
									array(
										"id" => "stringFilter.ui.caption",  // id attribute if the field
										"fieldTitle" => __("Caption", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "",
										"cssClass" => "stringFilterOption"
									),
									array(
										"id" => "stringFilter.ui.label",  // id attribute if the field
										"fieldTitle" => __("Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										// "chartTypes" => array("PieChart"),
										"default" => "",
										"cssClass" => "stringFilterOption"
									),
								),
								array(
									array(
										"id" => "stringFilter.ui.labelSeparator",  // id attribute if the field
										"fieldTitle" => __("Label Seperator", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "",
										"cssClass" => "stringFilterOption"
									),
									array(
										"id" => "stringFilter.ui.labelStacking",  // id attribute if the field
										"fieldTitle" => __("Label Stacking", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "horizontal",
										"cssClass" => "stringFilterOption"
									),
								),
								array(
									array(
										"id" => "stringFilter.ui.selectedValuesLayout",  // id attribute if the field
										"fieldTitle" => __("Selected Values Layout", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"aside" => "Aside",
											"below" => "Below",
											"belowWrapping" => "Below Wrapping",
											"belowStacked" => " Below Stacked",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "aside",
										"cssClass" => "stringFilterOption"
									),
								),
								array(
									array(
										"id" => "stringFilter.ui.cssClass",  // id attribute if the field
										"fieldTitle" => __("CSS Class", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => "",
										"cssClass" => "categoryFilterOption"
									),
								),
							)
						),		
					)
				),
				
				"tableChart" => array(
					"id" => "tableChart",
					"title" => "Table Chart",
					// "chartTypes" => array("PieChart"),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"subpanels" => array(
						array(
							"id" => "tableChart",
							"title" => __("Table Chart", $this->plugin),
							"fields" => array(
								array(
									array(
										"id" => "tableChart.enable",  // id attribute if the field
										"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => false,
										"cssClass" => "tableChartOption"
									),
								),
								array(
									array(
										"id" => "tableChart.width",  
										"fieldTitle" => __("Chart Width", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 200,
										"fieldMax" => 2000,
										"fieldStep" => 100,
										"cssClass" => "tableChartOption"
									),
									array(
										"id" => "tableChart.height", 
										"fieldTitle" => __("Chart Height", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 200,
										"fieldMax" => 2000,
										"fieldStep" => 100,
										"cssClass" => "tableChartOption"
									)
								),
								array(
									array(
										"id" => "tableChart.alternatingRowStyle",  // id attribute if the field
										"fieldTitle" => __("Alternating Row Styles", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "tableChartOption"
									)
								),
								array(
									array(
										"id" => "tableChart.allowHtml",  // id attribute if the field
										"fieldTitle" => __("Allow HTML", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "tableChartOption"
									)
								),
								array(
									array(
										"id" => "tableChart.showRowNumber",  // id attribute if the field
										"fieldTitle" => __("Show Row Number", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "tableChartOption"
									)
								),
								array(
									array(
										"id" => "tableChart.firstRowNumber",  
										"fieldTitle" => __("First Row Number", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 1,
										"fieldMin" => 1,
										"fieldMax" => 2000,
										"fieldStep" => 1,
										"cssClass" => "tableChartOption"
									),
									array(
										"id" => "tableChart.frozenColumns", 
										"fieldTitle" => __("Frozen Columns", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 1,
										"fieldMin" => 1,
										"fieldMax" => 10,
										"fieldStep" => 1,
										"cssClass" => "tableChartOption"
									)
								),
								array(
									array(
										"id" => "tableChart.pageSize", 
										"fieldTitle" => __("Page Size", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 1,
										"fieldMax" => 100,
										"fieldStep" => 1,
										"cssClass" => "tableChartOption"
									),
									array(
										"id" => "tableChart.page",  // id attribute if the field
										"fieldTitle" => __("Page", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"enable" => "Enable",
											"disable" => "Disable",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "enable",
										"cssClass" => "tableChartOption"
									),
								),
								array(
									array(
										"id" => "tableChart.startPage", 
										"fieldTitle" => __("Start Page", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 0,
										// "fieldMin" => 1,
										// "fieldMax" => 10000,
										// "fieldStep" => 1,
										"cssClass" => "tableChartOption"
									),
									array(
										"id" => "tableChart.sort",  // id attribute if the field
										"fieldTitle" => __("Sort", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"enable" => "Enable",
											"disable" => "Disable",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "enable",
										"cssClass" => "tableChartOption"
									),
								),
								array(
									array(
										"id" => "tableChart.sortColumn", 
										"fieldTitle" => __("Sort Column", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => -1,
										// "fieldMin" => 1,
										// "fieldMax" => 10000,
										// "fieldStep" => 1,
										"cssClass" => "tableChartOption"
									),
										array(
										"id" => "tableChart.sortAscending",  // id attribute if the field
										"fieldTitle" => __("Sort Ascending", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "tableChartOption"
									)
								),
								array(
									array(
										"id" => "tableChart.cssClassNames.headerRow", 
										"fieldTitle" => __("Header Row", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "minmaxavg-header-row",
										"cssClass" => "tableChartOption"
									),
									array(
										"id" => "tableChart.cssClassNames.tableRow",
										"fieldTitle" => __("Table Row", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "tableChartOption"
									),
								),
								array(
									array(
										"id" => "tableChart.cssClassNames.oddTableRow", 
										"fieldTitle" => __("Odd Table Row", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "tableChartOption"
									),
									array(
										"id" => "tableChart.cssClassNames.selectedTableRow",
										"fieldTitle" => __("Selected Table Row", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "tableChartOption"
									),
								),
								array(
									array(
										"id" => "tableChart.cssClassNames.hoverTableRow", 
										"fieldTitle" => __("Hover Table Row", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "tableChartOptin"
									),
									array(
										"id" => "tableChart.cssClassNames.headerCell",
										"fieldTitle" => __("Header Cell", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "tableChartOption"
									),
								),
								array(
									array(
										"id" => "tableChart.cssClassNames.tableCell", 
										"fieldTitle" => __("Table Cell", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "tableChartOptin"
									),
									array(
										"id" => "tableChart.cssClassNames.rowNumberCell",
										"fieldTitle" => __("Row Number Cell", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "tableChartOption"
									),
								),
							)
						),
					)
				),
				
				"minMaxAvgTable" => array(
					"id" => "minMaxAvgTable",
					"title" => "Min Max Average Table",
					// "chartTypes" => array("PieChart"),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"subpanels" => array(
						array(
							"id" => "minMaxAvgTable",
							"title" => __("Table Chart", $this->plugin),
							"fields" => array(
								array(
									array(
										"id" => "minMaxAvgTable.enable",  // id attribute if the field
										"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
										"default" => false,
										"cssClass" => "minMaxAvgTableOption"
									),
								),
								array(
									array(
										"id" => "minMaxAvgTable.width",  
										"fieldTitle" => __("Chart Width", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 200,
										"fieldMax" => 2000,
										"fieldStep" => 100,
										"cssClass" => "minMaxAvgTableOption"
									),
									array(
										"id" => "minMaxAvgTable.height", 
										"fieldTitle" => __("Chart Height", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 200,
										"fieldMax" => 2000,
										"fieldStep" => 100,
										"cssClass" => "minMaxAvgTableOption"
									)
								),
								array(
									array(
										"id" => "minMaxAvgTable.alternatingRowStyle",  // id attribute if the field
										"fieldTitle" => __("Alternating Row Styles", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "minMaxAvgTableOption"
									)
								),
								array(
									array(
										"id" => "minMaxAvgTable.allowHtml",  // id attribute if the field
										"fieldTitle" => __("Allow HTML", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => true,
										"cssClass" => "minMaxAvgTableOption"
									)
								),
								array(
									array(
										"id" => "minMaxAvgTable.showRowNumber",  // id attribute if the field
										"fieldTitle" => __("Show Row Number", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "minMaxAvgTableOption"
									)
								),
								array(
									array(
										"id" => "minMaxAvgTable.firstRowNumber",  
										"fieldTitle" => __("First Row Number", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 1,
										"fieldMin" => 1,
										"fieldMax" => 2000,
										"fieldStep" => 1,
										"cssClass" => "minMaxAvgTableOption"
									),
									array(
										"id" => "minMaxAvgTable.frozenColumns", 
										"fieldTitle" => __("Frozen Columns", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 1,
										"fieldMin" => 1,
										"fieldMax" => 10,
										"fieldStep" => 1,
										"cssClass" => "minMaxAvgTableOption"
									)
								),
								array(
									array(
										"id" => "minMaxAvgTable.pageSize", 
										"fieldTitle" => __("Page Size", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"fieldMin" => 1,
										"fieldMax" => 100,
										"fieldStep" => 1,
										"cssClass" => "minMaxAvgTableOption"
									),
									array(
										"id" => "minMaxAvgTable.page",  // id attribute if the field
										"fieldTitle" => __("Page", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"enable" => "Enable",
											"disable" => "Disable",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "enable",
										"cssClass" => "minMaxAvgTableOption"
									),
								),
								array(
									array(
										"id" => "minMaxAvgTable.startPage", 
										"fieldTitle" => __("Start Page", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 0,
										// "fieldMin" => 1,
										// "fieldMax" => 10000,
										// "fieldStep" => 1,
										"cssClass" => "minMaxAvgTableOption"
									),
									array(
										"id" => "minMaxAvgTable.sort",  // id attribute if the field
										"fieldTitle" => __("Sort", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"enable" => "Enable",
											"disable" => "Disable",
										),
										//"nullOption" => "Select Position",
										// "chartTypes" => array("PieChart"),
										"default" => "enable",
										"cssClass" => "minMaxAvgTableOption"
									),
								),
								array(
									array(
										"id" => "minMaxAvgTable.sortColumn", 
										"fieldTitle" => __("Sort Column", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => -1,
										// "fieldMin" => 1,
										// "fieldMax" => 10000,
										// "fieldStep" => 1,
										"cssClass" => "minMaxAvgTableOption"
									),
										array(
										"id" => "minMaxAvgTable.sortAscending",  // id attribute if the field
										"fieldTitle" => __("Sort Ascending", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "minMaxAvgTableOption"
									)
								),
								array(
									array(
										"id" => "minMaxAvgTable.cssClassNames.headerRow", 
										"fieldTitle" => __("Header Row", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "minmaxavg-header-row",
										"cssClass" => "minMaxAvgTableOption"
									),
									array(
										"id" => "minMaxAvgTable.cssClassNames.tableRow",
										"fieldTitle" => __("Table Row", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "minMaxAvgTableOption"
									),
								),
								array(
									array(
										"id" => "minMaxAvgTable.cssClassNames.oddTableRow", 
										"fieldTitle" => __("Odd Table Row", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "minMaxAvgTableOption"
									),
									array(
										"id" => "minMaxAvgTable.cssClassNames.selectedTableRow",
										"fieldTitle" => __("Selected Table Row", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "minMaxAvgTableOption"
									),
								),
								array(
									array(
										"id" => "minMaxAvgTable.cssClassNames.hoverTableRow", 
										"fieldTitle" => __("Hover Table Row", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "minMaxAvgTableOptin"
									),
									array(
										"id" => "minMaxAvgTable.cssClassNames.headerCell",
										"fieldTitle" => __("Header Cell", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "minMaxAvgTableOption"
									),
								),
								array(
									array(
										"id" => "minMaxAvgTable.cssClassNames.tableCell", 
										"fieldTitle" => __("Table Cell", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "minMaxAvgTableOptin"
									),
									array(
										"id" => "minMaxAvgTable.cssClassNames.rowNumberCell",
										"fieldTitle" => __("Row Number Cell", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard",
										"fieldType" => "text",
										"default" => "",
										"cssClass" => "minMaxAvgTableOption"
									),
								),
							)
						),
					)
				),


				"advanced" => array(
					"id" => "advanced",
					"title" => "Advanced",
					// "chartTypes" => array("PieChart"),
					"intro" => "You must selecte at least one filter to display and use the table chart and the the Min, Max, Average tables.",
					"subpanels" => array(
						// array(
						// 	"id" => "numRangeFilter",
						// 	"title" => __("Number Range Filter", $this->plugin),
						// 	"fields" => array(
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.enable",  // id attribute if the field
						// 				"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => false,
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.filterColumnIndex",  // id attribute if the field
						// 				"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(),
						// 				"nullOption" => "Select Filter Column",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.minValue",  // id attribute if the field
						// 				"fieldTitle" => __("Min Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 			array(
						// 				"id" => "numRangeFilter.maxValue",  // id attribute if the field
						// 				"fieldTitle" => __("Max Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "numRangeOption"
						// 			),

						// 		),
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.ui.label",  // id attribute if the field
						// 				"fieldTitle" => __("Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "",
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.ui.orientation",  // id attribute if the field
						// 				"fieldTitle" => __("Orientation", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"horizontal" => "Horizontal",
						// 					"vertical" => "Vertical",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "horizontal",
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.ui.labelSeparator",  // id attribute if the field
						// 				"fieldTitle" => __("Label Seperator", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 			array(
						// 				"id" => "numRangeFilter.ui.labelStacking",  // id attribute if the field
						// 				"fieldTitle" => __("Label Stacking", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"horizontal" => "Horizontal",
						// 					"vertical" => "Vertical",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "horizontal",
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.ui.showRangeValues",  // id attribute if the field
						// 				"fieldTitle" => __("Show Range Values", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => true,
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 			array(
						// 				"id" => "numRangeFilter.ui.step",  // id attribute if the field
						// 				"fieldTitle" => __("Step", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"fieldMin" => 0.1,
						// 				"fieldMax" => 1000000,
						// 				"fieldStep" => 0.1,
						// 				"default" => 1,
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "numRangeFilter.ui.cssClass",  // id attribute if the field
						// 				"fieldTitle" => __("CSS Class", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "numRangeOption"
						// 			),
						// 		),
						// 	)
						// ),
						// array(
						// 	"id" => "chartRangeFilter",
						// 	"title" => __("Chart Range Filter", $this->plugin),
						// 	"fields" => array(
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.enable",  // id attribute if the field
						// 				"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => false,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.filterColumnIndex",  // id attribute if the field
						// 				"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(),
						// 				"nullOption" => "Select Filter Column",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartType",  // id attribute if the field
						// 				"fieldTitle" => __("Chart Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"LineChart" => "Line Chart",
						// 					"ScatterChart" => "Scatter Chart",
						// 					"AreaChart" => "Area Chart",
						// 					"ComboChart" => "Combo Chart",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.width",  
						// 				"fieldTitle" => __("Range Chart Width", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => null,
						// 				"fieldMin" => 200,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 10,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.height", 
						// 				"fieldTitle" => __("Range Chart Height", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => null,
						// 				"fieldMin" => 50,
						// 				"fieldMax" => 800,
						// 				"fieldStep" => 10,
						// 				"cssClass" => "chartRangeOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.backgroundColor.fill",  // id attribute if the field
						// 				"cssClass" => "chartRangeOption color-picker",
						// 				"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", 
						// 				"default" => "#CCCCCC",
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.backgroundColor.stroke",  // id attribute if the field
						// 				"cssClass" => "chartRangeOption color-picker",
						// 				"fieldTitle" => __("Background Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => "red",
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.backgroundColor.strokeWidth",  // id attribute if the field
						// 				"fieldTitle" => __("Bg. Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => "6",
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.chartArea.width",  // id attribute if the field
						// 				"fieldTitle" => __("Chart Area Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => null,
						// 				"fieldMin" => 50,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 10,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.chartArea.height",  // id attribute if the field
						// 				"fieldTitle" => __("chart Area Height", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => null,
						// 				"fieldMin" => 50,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 10,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),

						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.chartArea.top",  // id attribute if the field
						// 				"fieldTitle" => __("Top Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 0,
						// 				"cssClass" => "chartRangeOption hasFieldSuffix",
						// 				"fieldSuffix" => "%"									),
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.chartArea.left",  // id attribute if the field
						// 				"fieldTitle" => __("Left Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 5,
						// 				"cssClass" => "chartRangeOption hasFieldSuffix",
						// 				"fieldSuffix" => "%"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.chartArea.backgroundColor.fill",  // id attribute if the field
						// 				"cssClass" => "chartRangeOption color-picker",
						// 				"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
						// 				"default" => "yellow",
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.chartArea.backgroundColor.stroke",  // id attribute if the field
						// 				"cssClass" => "chartRangeOption color-picker",
						// 				"fieldTitle" => __("Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => "blue",
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.chartOptions.chartArea.backgroundColor.strokeWidth",  // id attribute if the field
						// 				"fieldTitle" => __("Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 2,
						// 				"cssClass" => "chartRangeOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.minValue",  // id attribute if the field
						// 				"fieldTitle" => __("Min Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 			array(
						// 				"id" => "chartRangeFilter.maxValue",  // id attribute if the field
						// 				"fieldTitle" => __("Max Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "chartRangeOption"
						// 			),

						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.label",  // id attribute if the field
						// 				"fieldTitle" => __("Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "",
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.orientation",  // id attribute if the field
						// 				"fieldTitle" => __("Orientation", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"horizontal" => "Horizontal",
						// 					"vertical" => "Vertical",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "horizontal",
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.labelSeparator",  // id attribute if the field
						// 				"fieldTitle" => __("Label Seperator", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 			array(
						// 				"id" => "chartRangeFilter.ui.labelStacking",  // id attribute if the field
						// 				"fieldTitle" => __("Label Stacking", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"horizontal" => "Horizontal",
						// 					"vertical" => "Vertical",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "horizontal",
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.showRangeValues",  // id attribute if the field
						// 				"fieldTitle" => __("Show Range Values", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => true,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 			array(
						// 				"id" => "chartRangeFilter.ui.step",  // id attribute if the field
						// 				"fieldTitle" => __("Step", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"fieldMin" => 0.1,
						// 				"fieldMax" => 1000000,
						// 				"fieldStep" => 0.1,
						// 				"default" => 1,
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "chartRangeFilter.ui.cssClass",  // id attribute if the field
						// 				"fieldTitle" => __("CSS Class", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "chartRangeOption"
						// 			),
						// 		),
						// 	)
						// ),
						// array(
						// 	"id" => "categoryFilter",
						// 	"title" => __("Category Filter", $this->plugin),
						// 	"fields" => array(
						// 		array(
						// 			array(
						// 				"id" => "categoryFilter.enable",  // id attribute if the field
						// 				"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => false,
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "categoryFilter.filterColumnIndex",  // id attribute if the field
						// 				"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(),
						// 				"nullOption" => "Select Filter Column",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "categoryFilter.ui.caption",  // id attribute if the field
						// 				"fieldTitle" => __("Caption", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "",
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 			array(
						// 				"id" => "categoryFilter.ui.label",  // id attribute if the field
						// 				"fieldTitle" => __("Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "",
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "categoryFilter.ui.labelSeparator",  // id attribute if the field
						// 				"fieldTitle" => __("Label Seperator", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 			array(
						// 				"id" => "categoryFilter.ui.labelStacking",  // id attribute if the field
						// 				"fieldTitle" => __("Label Stacking", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"horizontal" => "Horizontal",
						// 					"vertical" => "Vertical",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "horizontal",
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "categoryFilter.ui.selectedValuesLayout",  // id attribute if the field
						// 				"fieldTitle" => __("Selected Values Layout", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"aside" => "Aside",
						// 					"below" => "Below",
						// 					"belowWrapping" => "Below Wrapping",
						// 					"belowStacked" => " Below Stacked",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "aside",
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "categoryFilter.ui.cssClass",  // id attribute if the field
						// 				"fieldTitle" => __("CSS Class", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 		),
						// 	)
						// ),
						// array(
						// 	"id" => "stringFilter",
						// 	"title" => __("String Filter", $this->plugin),
						// 	"fields" => array(
						// 		array(
						// 			array(
						// 				"id" => "stringFilter.enable",  // id attribute if the field
						// 				"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => false,
						// 				"cssClass" => "stringFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "stringFilter.filterColumnIndex",  // id attribute if the field
						// 				"fieldTitle" => __("Filter Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(),
						// 				"nullOption" => "Select Filter Column",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => null,
						// 				"cssClass" => "stringFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "stringFilter.ui.caption",  // id attribute if the field
						// 				"fieldTitle" => __("Caption", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "",
						// 				"cssClass" => "stringFilterOption"
						// 			),
						// 			array(
						// 				"id" => "stringFilter.ui.label",  // id attribute if the field
						// 				"fieldTitle" => __("Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "",
						// 				"cssClass" => "stringFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "stringFilter.ui.labelSeparator",  // id attribute if the field
						// 				"fieldTitle" => __("Label Seperator", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "stringFilterOption"
						// 			),
						// 			array(
						// 				"id" => "stringFilter.ui.labelStacking",  // id attribute if the field
						// 				"fieldTitle" => __("Label Stacking", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"horizontal" => "Horizontal",
						// 					"vertical" => "Vertical",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "horizontal",
						// 				"cssClass" => "stringFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "stringFilter.ui.selectedValuesLayout",  // id attribute if the field
						// 				"fieldTitle" => __("Selected Values Layout", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"aside" => "Aside",
						// 					"below" => "Below",
						// 					"belowWrapping" => "Below Wrapping",
						// 					"belowStacked" => " Below Stacked",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "aside",
						// 				"cssClass" => "stringFilterOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "stringFilter.ui.cssClass",  // id attribute if the field
						// 				"fieldTitle" => __("CSS Class", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => "",
						// 				"cssClass" => "categoryFilterOption"
						// 			),
						// 		),
						// 	)
						// ),
						// array(
						// 	"id" => "tableChart",
						// 	"title" => __("Table Chart", $this->plugin),
						// 	"fields" => array(
						// 		array(
						// 			array(
						// 				"id" => "tableChart.enable",  // id attribute if the field
						// 				"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => false,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.width",  
						// 				"fieldTitle" => __("Chart Width", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 800,
						// 				"fieldMin" => 200,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 100,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 			array(
						// 				"id" => "tableChart.height", 
						// 				"fieldTitle" => __("Chart Height", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 800,
						// 				"fieldMin" => 200,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 100,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.alternatingRowStyle",  // id attribute if the field
						// 				"fieldTitle" => __("Alternating Row Styles", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => true,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 	)
						// ),
						// array(
						// 	"id" => "minMaxAvgTable",
						// 	"title" => __("Min Max Table", $this->plugin),
						// 	"fields" => array(
						// 		array(
						// 			array(
						// 				"id" => "tableChart.enable",  // id attribute if the field
						// 				"fieldTitle" => __("Enable", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args
						// 				"default" => false,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.width",  
						// 				"fieldTitle" => __("Chart Width", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => null,
						// 				"fieldMin" => 200,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 100,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 			array(
						// 				"id" => "tableChart.height", 
						// 				"fieldTitle" => __("Chart Height", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => null,
						// 				"fieldMin" => 200,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 100,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.alternatingRowStyle",  // id attribute if the field
						// 				"fieldTitle" => __("Alternating Row Styles", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => false,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.allowHtml",  // id attribute if the field
						// 				"fieldTitle" => __("Allow HTML", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => false,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.showRowNumber",  // id attribute if the field
						// 				"fieldTitle" => __("Show Row Number", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => false,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.firstRowNumber",  
						// 				"fieldTitle" => __("First Row Number", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 1,
						// 				"fieldMin" => 1,
						// 				"fieldMax" => 2000,
						// 				"fieldStep" => 1,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 			array(
						// 				"id" => "tableChart.frozenColumns", 
						// 				"fieldTitle" => __("Frozen Columns", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 1,
						// 				"fieldMin" => 1,
						// 				"fieldMax" => 10,
						// 				"fieldStep" => 1,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.pageSize", 
						// 				"fieldTitle" => __("Page Size", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => null,
						// 				"fieldMin" => 1,
						// 				"fieldMax" => 100,
						// 				"fieldStep" => 1,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 			array(
						// 				"id" => "tableChart.page",  // id attribute if the field
						// 				"fieldTitle" => __("Page", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"enable" => "Enable",
						// 					"disable" => "Disable",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "enable",
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.startPage", 
						// 				"fieldTitle" => __("Start Page", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => 0,
						// 				"fieldMin" => 1,
						// 				"fieldMax" => 10000,
						// 				"fieldStep" => 1,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 			array(
						// 				"id" => "tableChart.sort",  // id attribute if the field
						// 				"fieldTitle" => __("Sort", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"fieldOptions" => array(
						// 					"enable" => "Enable",
						// 					"disable" => "Disable",
						// 				),
						// 				//"nullOption" => "Select Position",
						// 				// "chartTypes" => array("PieChart"),
						// 				"default" => "enable",
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 		),
						// 		array(
						// 			array(
						// 				"id" => "tableChart.sortColumn", 
						// 				"fieldTitle" => __("Sort Column", $this->plugin), 
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => -1,
						// 				"fieldMin" => 1,
						// 				"fieldMax" => 10000,
						// 				"fieldStep" => 1,
						// 				"cssClass" => "tableChartOption"
						// 			),
						// 				array(
						// 				"id" => "tableChart.sortAscending",  // id attribute if the field
						// 				"fieldTitle" => __("Sort Ascending", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						// 				"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						// 				"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						// 				"default" => false,
						// 				"cssClass" => "tableChartOption"
						// 			)
						// 		),
						// 	)
						// )
					)
				),
	 		);

		} // END  private  function register_fields (){




	} // END class Dashboard {


} // END if (!class_exists(Dashboard))