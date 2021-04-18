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
			$wrapper_class .= (isset($field["wrapperClass"]))? $field["wrapperClass"] : "";
			$wrapper_class .= (isset($field["cssClass"]) && $field["cssClass"] == "color-picker")? "wpColorPicker" : "";

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
						  		<?php echo "name='{$field["id"]}'"; ?>]
						  		type="checkbox"
						  		value="1"
						  		<?php checked(1, $field['value'], true);?>
						  		<?php echo (isset($field['disabled']) && $field['disabled']) ? "disabled" : ""; ?> 
						   />
							<?php
		      			break;

		      		case "array":
		      			$value = (isset($field['value']))? implode(",", $field["value"]) : implode(",", $field["default"]);
		      			?>
							<input
								<?php echo (isset($field['cssClass']))? "class='{$field['cssClass']}'" : ""; ?> 
					    		<?php echo "id='{$field["id"]}'"; ?>
					      	<?php echo "name='{$field["id"]}'"; ?>
								type='text'
								<?php echo (isset($field['fieldSize']))? "size='{$field['fieldSize']}'" : ""; ?> 
								<?php echo (isset($field['placeholder']))? "placeholder='{$field['placeholder']}'" : ""; ?> 
								<?php echo (isset($field['pattern']))? "pattern='{$field['pattern']}'" : ""; ?> 
								value="<?php echo $value; ?>"   
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







		public function default_chart_options($fields, $chart_type) {

			echo "<pre>";var_dump($fields); die;


			$chart_options = array(

				"theme" => "material",

				"width" => $fields["width"],
				"height" => $fields["height"],
				"fontName" => $fields["fontName"],
				"fontSize" => $fields["fontSize"],
				"interpolateNulls" => $fields["interpolateNulls"],
				"orientation" => $fields["orientation"],
				"trendlines" => array(),
				"reverseCategories" => $fields["reverseCategories"],
				"selectionMode" => $fields["selectionMode"],
				"aggregationTarget" => $fields["aggregationTarget"],
				"curveType" => $fields["curveType"],
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
				
			);










 return array(
			"id" => "series",
			"title" => "Series",
			"chartTypes" => array("LineChart"), //array("LineChart", "ScatterChart", "BarChart", "ColumnChart"),
			"subpanels" => array(
				"id" => "series-0",
				"title" => "Series 0",
				"fields" =>array(
					array(
						array(//"slug" => "chart_title", // field id
							"id" => "series.{$index}.curveType",  // id attribute if the field
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
							"id" => "series.{$index}.targetAxisIndex",  // id attribute if the field
							"fieldTitle" => __("Target Axis Index", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								0 => "Default Axis",
								1 => "Opposite Axis",
							),
							//"nullOption" => "Target Axis Index",
							"chartTypes" => array(),
							"default" => 0,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.color", 
							"cssClass" => "color-picker",
							"fieldTitle" => __("Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => null,
						),
					),
					array(
						array(
							"id" => "series.{$index}.labelInLegend",  
							"fieldTitle" => __("Label in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text", 
							"default" => "",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.lineWidth",  // id attribute if the field
							"fieldTitle" => __("Line Width", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 3,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.lineDashStyle",  // id attribute if the field
							"fieldTitle" => __("Line Dash Style", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							"fieldType" => "array", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => array(4,4),
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.pointShape.type",  // id attribute if the field
							"fieldTitle" => __("Ponit Shape Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
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
							"chartTypes" => array(),
							"default" => "star",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.pointShape.sides",  // id attribute if the field
							"fieldTitle" => __("Point Shape Sides", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 5,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.pointShape.dent",  // id attribute if the field
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
							"id" => "series.{$index}.pointSize",  // id attribute if the field
							"fieldTitle" => __("Point Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 10,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.pointShape.rotation",  // id attribute if the field
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
							"id" => "series.{$index}.pointsVisible",  
							"fieldTitle" => __("Points Visible", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => true,
							"cssClass" => "chartOption"
						),
						
						array(
							"id" => "series.{$index}.visibleInLegend",  
							"fieldTitle" => __("Visible in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
					),
					
					array(
						array(//"slug" => "chart_title", // field id
							"id" => "series.{$index}.annotations.textStyle.fontName",  // id attribute if the field
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
							"id" => "series.{$index}.annotations.textStyle.fontSize",  // id attribute if the field
							"fieldTitle" => __("Annotation Text Style Font Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 30,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.color", 
							"cssClass" => "color-picker",
							"fieldTitle" => __("Annotations Text Style Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "teal",
						),
					),
					array(
						array(
							//"slug" => chart_title, // field id
							"id" => "series.{$index}.annotations.textStyle.bold",  // id attribute if the field
							"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
	
							"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => true,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.annotations.textStyle.italic",  // id attribute if the field
							"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
	
							"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => true,
							"cssClass" => "chartOption"
						),
					
					),
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.auraColor", 
							"cssClass" => "color-picker",
							"fieldTitle" => __("Annotations Aura Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "teal",
						),
					),
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.opacity",  // id attribute if the field
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










			


			switch ($this->default_chart_type) {

				case "LineChart":

					$other_options = array(
						"titlePosition" => $fields["titlePosition"],
					
						"hAxis" => array(
							"title" => $fields["hAxis.title"],
							"baseline"=> $fields["hAxis.baseline"],
							"baselineColor"=> $fields["hAxis.baselineColor"],
							"ticks" => "auto",
							"direction" => $fields["hAxis.direction"],
							"format" => $fields["hAxis.format"],
							"minValue" => $fields["hAxis.minValue"],
							"maxValue" => $fields["hAxis.maxValue"],
							"viewWindowMode" => $fields["hAxis.viewWindowMode"],
							"showTextEvery" => $fields["hAxis.showTextEvery"],
							"slantedText" => $fields["hAxis.slantedText"],
							"slantedTextAngle" => $fields["hAxis.slantedTextAngle"],
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
							"logScale" => $fields["hAxis.logScale"],
							"scaleType" => $fields["hAxis.scaleType"],
						),


						"vAxes" => array(
							"0" => array(
								"title" => $fields["vAxes.0.title"],
								"titlePosition" => $fields["vAxes.0.titlePosition"],
								"baseline"=> $fields["vAxes.0.baseline"],
								"baselineColor"=> $fields["vAxes.0.baselineColor"],
								"ticks" => "auto",
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
								"logScale" => $fields["vAxes.0.logScale"],
								"scaleType" => $fields["vAxes.0.scaleType"],
							),
							"1" => array(
								"title" => $fields["vAxes.1.title"],
								"titlePosition" => $fields["vAxes.1.titlePosition"],
								"baseline"=> $fields["vAxes.1.baseline"],
								"baselineColor"=> $fields["vAxes.1.baselineColor"],
								"ticks" => "auto",
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
								"logScale" => $fields["vAxes.1.logScale"],
								"scaleType" => $fields["vAxes.1.scaleType"],
							),
						),
					);
								
					break;
									
				case 'PieChart':
					$other_options = array(
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
					break;
				
				default:
					$other_options = array();
					break;
			}

			return array_merge($chart_options, $other_options);
			
		
		}



	 

	 	public function legend_position($chart_type) {

		 	switch ($chart_type) {
		 		case "LineChart":
		 			return array(
						"right" => "Right of the cahrt",
						"left" => "Left of the Chart",
						"top" => "Above the Chart",
						"bottom" => "Below The Chart",
						"in" => "Inside the Chart",
						"none" => "Omit Legend",	
					);
		 			break;

		 		case "PieChart":
		 			return array(
		 				"right" => "Right of the chart",
						"left" => "Left of the Chart",
						"top" => "Above the Chart",
						"bottom" => "Below The Chart",
						"none" => "Omit Legend",
						"labeled" => "Connected to Chart",
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
							"id" => "series.{$index}.curveType",  // id attribute if the field
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
							"id" => "series.{$index}.targetAxisIndex",  // id attribute if the field
							"fieldTitle" => __("Target Axis Index", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								0 => "Default Axis",
								1 => "Opposite Axis",
							),
							//"nullOption" => "Target Axis Index",
							"chartTypes" => array(),
							"default" => 0,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.color", 
							"cssClass" => "color-picker",
							"fieldTitle" => __("Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => null,
						),
					),
					array(
						array(
							"id" => "series.{$index}.labelInLegend",  
							"fieldTitle" => __("Label in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text", 
							"default" => "",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.lineWidth",  // id attribute if the field
							"fieldTitle" => __("Line Width", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 3,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.lineDashStyle",  // id attribute if the field
							"fieldTitle" => __("Line Dash Style", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
							"fieldType" => "array", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => array(4,4),
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.pointShape.type",  // id attribute if the field
							"fieldTitle" => __("Ponit Shape Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
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
							"chartTypes" => array(),
							"default" => "star",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.pointShape.sides",  // id attribute if the field
							"fieldTitle" => __("Point Shape Sides", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 5,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.pointShape.dent",  // id attribute if the field
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
							"id" => "series.{$index}.pointSize",  // id attribute if the field
							"fieldTitle" => __("Point Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 10,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.pointShape.rotation",  // id attribute if the field
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
							"id" => "series.{$index}.pointsVisible",  
							"fieldTitle" => __("Points Visible", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => true,
							"cssClass" => "chartOption"
						),
						
						array(
							"id" => "series.{$index}.visibleInLegend",  
							"fieldTitle" => __("Visible in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
					),
					
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.fontName",  // id attribute if the field
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
							"id" => "series.{$index}.annotations.textStyle.fontSize",  // id attribute if the field
							"fieldTitle" => __("Annotation Text Style Font Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 30,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.color", 
							"cssClass" => "color-picker",
							"fieldTitle" => __("Annotations Text Style Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "teal",
						),
					),
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.bold",  // id attribute if the field
							"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
	
							"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => true,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "series.{$index}.annotations.textStyle.italic",  // id attribute if the field
							"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
							"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
	
							"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"default" => true,
							"cssClass" => "chartOption"
						),
					
					),
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.auraColor", 
							"cssClass" => "color-picker",
							"fieldTitle" => __("Annotations Aura Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "teal",
						),
					),
					array(
						array(
							"id" => "series.{$index}.annotations.textStyle.opacity",  // id attribute if the field
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
			"chartTypes" => array("LineChart", "ScatterChart", "BarChart", "ColumnChart"),
			"subpanels" => array(
				"id" => "trendlines-0",
				"title" => "Trendlines 0",
				"fields" =>array(
					array(
						array(
							"id" => "trendlines.{$index}.type",  // id attribute if the field
							"fieldTitle" => __("Type", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
							"fieldOptions" => array(
								"linear" => "Linear",
								"polynomial" => "Polynomial",
								"exponential" => "Exponential",
							),
							"nullOption" => "Trendline Type",
							"default" => "linear",
							"cssClass" => "chartOption"
						),
						array(
							"id" => "trendlines.{$index}.degree",  // id attribute if the field
							"fieldTitle" => __("Degree", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 3,
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "trendlines.{$index}.color", 
							"cssClass" => "color-picker",
							"fieldTitle" => __("Color", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text",
							"default" => "#1e73be",
						),
					),
					array(
						array(
							"id" => "trendlines.{$index}.lineWidth",  // id attribute if the field
							"fieldTitle" => __("Line Width", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 3,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "trendlines.{$index}.opacity",  // id attribute if the field
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
							"id" => "trendlines.{$index}.pointSize",  // id attribute if the field
							"fieldTitle" => __("Point Size", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 3,
							"cssClass" => "chartOption"
						),
							array(
							"id" => "trendlines.{$index}.labelInLegend",  
							"fieldTitle" => __("Label in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "text", 
							"default" => "",
							"cssClass" => "chartOption"
						),
					),
					array(
						array(
							"id" => "trendlines.{$index}.pointsVisible",  
							"fieldTitle" => __("Points Visible", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "trendlines.{$index}.visibleInLegend",  
							"fieldTitle" => __("Visible in Legend", $this->plugin), 
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "checkbox", 
							"default" => false,
							"cssClass" => "chartOption"
						),
						array(
							"id" => "trendlines.{$index}.showR2",  
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
										"id" => "chartId",  // id attribute if the field
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "hidden", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
									),
								),
								array(
									array(
										//"slug" => "file_upload", // field id
										"id" => "fileUpload",  // id attribute if the field
										"fieldTitle" => __("Upload File", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										// "id" => "fileSettings",
										"fieldType" => "file", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
									),
								),
								array(
									array(//"slug" => 'file_select', // field id
										"id" => "fileId",  // id attribute if the field
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
										"id" => "sheetId",  // id attribute if the field
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
										"id" => "chartType",  // id attribute if the field
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
					"chartTypes" => array("LineChart"),
					"subpanels" => array(
						"generalSettings" => array(
							"id" => "generalSettings",
							"title" => "General Settings",
							"fields" => array(
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "width",  // id attribute if the field
										"fieldTitle" => __("Chart Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "800",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "height",  // id attribute if the field
										"fieldTitle" => __("Chart Height", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "800",
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										"id" => "backgroundColor.strokeWidth",  // id attribute if the field
										"cssClass" => "color-picker",
										"fieldTitle" => __("Bg. Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "6",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "backgroundColor.stroke",  // id attribute if the field
										"cssClass" => "color-picker",
										"fieldTitle" => __("Background Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => "red",
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "lineWidth",  // id attribute if the field
										"fieldTitle" => __("Line Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => 1,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "lineDashStyle",  // id attribute if the field
										"fieldTitle" => __("Line Dash Style", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "verdana",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "fontSize",  // id attribute if the field
										"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "8",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "curveType",  // id attribute if the field
										"fieldTitle" => __("Curve Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"none" => "Linear",
											"function" => "Function",
										),
										//"nullOption" => "Curve Type",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "function",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "orientation",  // id attribute if the field
										"fieldTitle" => __("Chart Orientation", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"horizontal" => "Horizontal",
											"vertical" => "Vertical",
										),
										"nullOption" => "Chart Orientation",
										"default" => "horizontal",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "backgroundColor.fill",  // id attribute if the field
										"cssClass" => "color-picker",
										"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "text", 
										"default" => "#CCCCCC",
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "interpolateNulls",  // id attribute if the field
										"fieldTitle" => __("Interpolate", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "reverseCategories",  // id attribute if the field
										"fieldTitle" => __("Reverse Categories", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"chartTypes" => array(
										
										),
										"default" => false,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "selectionMode",  // id attribute if the field
										"fieldTitle" => __("Selection Mode", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"single" => "Single",
											"multiple" => "Multiple",
										),
										"nullOption" => "Selection Mode",
										"chartTypes" => array(
										),
										"default" => "single",
										"cssClass" => "chartOption"
									),
									array(
										"id" => "aggregationTarget",  // id attribute if the field
										"fieldTitle" => __("Aggregation Target", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"category" => "Category",
											"series" => "Series",
											"auto" => "Auto",
											"none" => "None",
										),
										"nullOption" => "Aggregation Target",
										"chartTypes" => array(),
										"default" => "category",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "labelInLegend",  // id attribute if the field
										"fieldTitle" => __("Label In Legend", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "pointsVisible",  // id attribute if the field
										"fieldTitle" => __("Points Visible", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "pointShape",  // id attribute if the field
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
										"chartTypes" => array(),
										"default" => "circle",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "pointSize",  // id attribute if the field
										"fieldTitle" => __("Point Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => 1,
										"cssClass" => "chartOption"
									),
								),
							)
						),


						"annotations" => array(
							"id" => "annotations",
							"title" => "Annotations",
							"fields" => array(
								array(
									array(
										"id" => "annotations.boxStyle.strokeWidth",  // id attribute if the field
										"fieldTitle" => __("Box Stroke Width", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 2,
										"cssClass" => "chartOption",
										"fieldHint" => "Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday).Select and upload your data CSV file here. The first row of the CSV file should contain the column headings. The second one should contain series type (string, number, boolean, date, datetime, timeofday)."
									),
									array(
										"id" => "annotations.boxStyle.gradient.useObjectBoundingBoxUnits",
										"fieldTitle" => __("Object Bounding Unit", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard",				
										"fieldType" => "checkbox", 
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "annotations.boxStyle.stroke", 
										"cssClass" => "color-picker",
										"fieldTitle" => __("Stroke Color", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text",
										"default" => "grey",
									),
								),
								array(
									array(
										"id" => "annotations.boxStyle.rx",  // id attribute if the field
										"fieldTitle" => __("Box X-radius", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 2,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "annotations.boxStyle.ry",  // id attribute if the field
										"fieldTitle" => __("Box Y-radius", $this->plugin),
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number",
										"default" => 2,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "annotations.boxStyle.gradient.color1", 
										"cssClass" => "color-picker",
										"fieldTitle" => __("Annotations Gradient 1", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text",
										"default" => "red",
									),
								),
								array(
									array(
										"id" => "annotations.boxStyle.gradient.color2", 
										"cssClass" => "color-picker",
										"fieldTitle" => __("Annotations Gradient 2", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "text",
										"default" => "green",
									),
								),
								array(
									array(
										"id" => "annotations.boxStyle.gradient.x1",  
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
										"id" => "annotations.boxStyle.gradient.y1",  
										"fieldTitle" => __("Y Start", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number", 
										"default" => 0,
										"fieldMin" => 0,
										"fieldMax" => 100,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%",
										"dataType" => "continuous"
									),
								),
								array(
									array(
										"id" => "annotations.boxStyle.gradient.x2",  
										"fieldTitle" => __("X End", $this->plugin), 
										"submenuPage" => "{$this->prefix}_dashboard", 
										"fieldType" => "number", 
										"default" => 100,
										"fieldMin" => 0,
										"fieldMax" => 100,
										"cssClass" => "chartOption hasFieldSuffix",
										"fieldSuffix" => "%",
										"chartTypes" => array("PieChart"),
									),
									array(
										"id" => "annotations.boxStyle.gradient.y2",  
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
							"chartTypes" => array("PieChart"),
							"fields" => array(
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "animation.startup",  // id attribute if the field
										"fieldTitle" => __("Startup", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "animation.duration",  // id attribute if the field
										"fieldTitle" => __("Duration", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => 1000,
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "animation.easing",  // id attribute if the field
										"fieldTitle" => __("Easing", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"linear" => "Linear",
											"in" => "In",
											"out" => "Out",
											"inAndOut" => "In and Out",
										),
										"nullOption" => "Easing",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "linear",
										"cssClass" => "chartOption"
									),
								)
							)
						),
						
						
						"chartTitle" => array(
							"id" => "chartTitle",
							"title" => "Chart Title",
							"fields" => array(
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "title",  // id attribute if the field
										"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "HELLO",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "titlePosition",  // id attribute if the field
										"fieldTitle" => __("Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Title Position",
										"chartTypes" => array("Line"),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title_color, // field id
										"id" => "titleTextStyle.color",  // id attribute if the field
										"cssClass" => "color-picker",
										"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "yellow",
										//"cssClass" => "chartOption"
									),
								),

								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 40,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								)
							)
						),

						"legend" => array(
							"id" => "legend",
							"title" => "Chart Legend",
							"fields" => array(
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.alignment",  // id attribute if the field
										"fieldTitle" => __("Alignment", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"start" => "Start",
											"center" => "Center",
											"end"  => "End"
										),
										"nullOption" => "Select Alignment",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "center",
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "legend.position",  // id attribute if the field
										"fieldTitle" => __("Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => $this->legend_position($chart_type),
										"nullOption" => "Chart Position",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "right",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.maxLines",  // id attribute if the field
										"cssClass" => "maxLines",
										"fieldTitle" => __("Maximum Number of Lines", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args) 
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => 3,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.color",  // id attribute if the field
										"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"cssClass" => "color-picker",
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "green",
										//"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "georgia",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => 15,
										"cssClass" => "chartOption"
									)
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "legend.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									)
								)
							)
						),
			
						"chartArea" => array(
							"id" => "chartArea",
							"title" => "Chart Area",
							"fields" => array(
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.width",  // id attribute if the field
										"fieldTitle" => __("Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "400",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.height",  // id attribute if the field
										"fieldTitle" => __("Height", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "400",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.top",  // id attribute if the field
										"fieldTitle" => __("Top Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "20%",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "chartArea.left",  // id attribute if the field
										"fieldTitle" => __("Left Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "20%",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title_color, // field id
										"id" => "chartArea.backgroundColor.fill",  // id attribute if the field
										"cssClass" => "color-picker",
										"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "yellow",
									),
								),
								array(
									array(
										//"slug" => chart_title_color, // field id
										"id" => "chartArea.backgroundColor.stroke",  // id attribute if the field
										"cssClass" => "color-picker",
										"fieldTitle" => __("Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "blue",
									),
								),
								array(
									array(
										//"slug" => chart_title_color, // field id
										"id" => "chartArea.backgroundColor.strokeWidth",  // id attribute if the field
										"cssClass" => "color-picker",
										"fieldTitle" => __("Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
										"default" => "2",
										"cssClass" => "chartOption"
									)
								),
							)
						)
					)
				),

				"advanced" => array(
					"id" => "advanced",
					"title" => "Advanced",
					"chartTypes" => array("PieChart"),
					"subpanels" => array(
						array(
							"id" => "slideControl",
							"title" => __("Slide Control", $this->plugin),
							"fields" => array(
								array(
									array(	//"slug" => "chart_type_select", // field id
										"id" => "rangeSliderColSelect",  // id attribute if the field
										"fieldTitle" => __("Range Slider Column", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(),
										"nullOption" => "Select Column",
										//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
										//"value" => (isset($settings) && isset($settings["chart_type_select"])) ? $settings["chart_type_select"] : "",
										"default" => null,
									)
								),
							)
						)
					)
				),
	 		);

		} // END  private  function register_fields (){




	} // END class Dashboard {


} // END if (!class_exists(Dashboard))