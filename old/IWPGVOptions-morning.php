<?php

/**
* Interactive WP Google Visualization
*
* @package Interactive WP Google Visualization
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace IWPGV\Includes;

// Prohibit direct script loading.
defined("ABSPATH") || die("No direct script access allowed!");

// Declare main class if it does not exist
if (!class_exists('IWPGVOptions')) { 
	class IWPGVOptions extends IWPGVDashboard  {

		// Declare field types
		public $field_types = array("text", "select", "hidden", "file", "number", "checkbox", "color-picker");	// 3 MB
	



		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {

		
			
			

		} // END public function __construct()



		// public function chart_options(){

		// 	return (!empty(get_option("{$this->prefix}_dashboard")))? get_option("{$this->prefix}_dashboard") : array();

		// }






		function render_input_field ($field) {

				// Initialize wp errors
			$errors = new \WP_Error;

			if (! isset($field["id"]) || ! isset($field["fieldType"]) || ! in_array($field["fieldType"], $this->field_types)) {
				$errors->add("file_id_or_file_type_mising", __("File id and file type are required for {$field["id"]}.", $this->plugin));

				return $errors;

			}

			// Strat buffer
			ob_start();

			$wrapper_class = "fieldWrapper ";
			//$wrapper_class .= ($field["fieldType"] == "color-picker")? "wpColorPicker " : "";
			$wrapper_class .= (isset($field["cssClass"]))? ($field["cssClass"] == "color-picker")? "wpColorPicker" : $field["cssClass"] : "";

			?>

			
			<div class =  "<?php echo $wrapper_class; ?>">

		      <h2><?php echo (isset($field['fieldTitle']))? $field['fieldTitle'] : null; ?></h2>

		      <?php switch ($field["fieldType"]) {
		      	
		      	case "select":
		      		?>
		      		<select 
					    	<?php echo (isset($field['cssClass']))? "class='{$field['cssClass']}'" : ""; ?> 
					    	<?php echo (isset($field["id"]))? "id='{$field["id"]}'": ""; ?>
					      <?php echo (isset($field["id"]))? "name='{$field["id"]}'": ""; ?>
					      >
					      <option value="" <?php selected( $field['value'], "", true); ?> >
					      	<?php echo (isset($field['nullOption']))? $field['nullOption'] : "Select Option"; ?>
					      </option>
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
		      			$value = (isset($field['value']))? implode(".", $field["value"]) : implode(".", $field["default"]);
		      			?>
							<input
								<?php echo (isset($field['cssClass']))? "class='{$field['cssClass']}'" : ""; ?> 
					    		<?php echo "id='{$field["id"]}'"; ?>
					      	<?php echo "name='{$field["id"]}'"; ?>
								<?php echo "type= '{$field['fieldType']}'"; ?> 
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
								<?php echo (isset($field['value']))? "value='{$field['value']}'" : "value = '{$field["default"]}'" ; ?> 
							/>
		      			<?php
		      			break;
		     	} ?>

		   	<span class = ""><?php echo (isset($field['description']))? $field["description"] : ""; ?></span>

			</div>

			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;

		}







		public function default_chart_options($fields, $chart_type) {


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
							"minValue" => $fields["vAxis.minValue"],
							"maxValue" => $fields["vAxis.maxValue"],
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


						"vAxis" => array(
							"title" => $fields["vAxis.title"],
							"titlePosition" => $fields["vAxis.titlePosition"],
							"baseline"=> $fields["vAxis.baseline"],
							"baselineColor"=> $fields["vAxis.baselineColor"],
							"ticks" => "auto",
							"direction" => $fields["vAxis.direction"],
							"format" => $fields["vAxis.format"],
							"minValue" => $fields["vAxis.minValue"],
							"maxValue" => $fields["vAxis.maxValue"],
							"viewWindowMode" => $fields["vAxis.viewWindowMode"],
							"viewWindow" => array(
								"min" => $fields["vAxis.viewWindow.min"],
								"max" => $fields["vAxis.viewWindow.max"],
							),

							"titleTextStyle" => array(
								"color" => $fields["vAxis.titleTextStyle.color"],
								"fontName" => $fields["vAxis.titleTextStyle.fontName"],
								"fontSize" => $fields["vAxis.titleTextStyle.fontSize"],
								"bold" => $fields["vAxis.titleTextStyle.bold"],
								"italic" => $fields["vAxis.titleTextStyle.italic"],
							),
							"textStyle" => array(
								"color" => $fields["vAxis.textStyle.color"],
								"fontName" => $fields["vAxis.textStyle.fontName"],
								"fontSize" => $fields["vAxis.textStyle.fontSize"],
								"bold" => $fields["vAxis.textStyle.bold"],
								"italic" => $fields["vAxis.textStyle.italic"],
							),
							"gridlines" => array(
								"color" => $fields["vAxis.gridlines.color"],
								"count" => $fields["vAxis.gridlines.count"],
							),
							"minorGridlines" => array(
								"color" => $fields["vAxis.minorGridlines.color"],
								"count" => $fields["vAxis.minorGridlines.count"],
							),
							"logScale" => $fields["vAxis.logScale"],
							"scaleType" => $fields["vAxis.scaleType"],
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



	public function trendlines ($index) {

		 return array(
			"id" => "trendlines",
			"title" => "Trendlines",
			"chartTypes" => array("LineChart", "ScatterChart", "BarChart", "ColumnChart"),
			"subpanels" => array(
				"id" => "trendlines-0",
				"title" => "Trendlines 00",
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
						"id" => "trendlines.{$index}.color", 
						"cssClass" => "color-picker",
						"fieldTitle" => __("Color", $this->plugin), 
						"submenuPage" => "{$this->prefix}_dashboard", 
						"fieldType" => "text",
						"default" => "#1e73be",
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
					)

				)
			)
			
		);


	}





	public function series ($index) {

		 return array(
			"id" => "series",
			"title" => "Series",
			"chartTypes" => array(), //array("LineChart", "ScatterChart", "BarChart", "ColumnChart"),
			"subpanels" => array(
				"id" => $index,
				"title" => "Series-{$index}",
				"fields" =>array(
					"color" => array(
						"id" => "color", 
						"cssClass" => "color-picker",
						"fieldTitle" => __("Color", $this->plugin), 
						"submenuPage" => "{$this->prefix}_dashboard", 
						"fieldType" => "text",
						"default" => "#1e73be",
					),
					"curveType" => array(
						"id" => "curveType",  // id attribute if the field
						"fieldTitle" => __("Curve Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
						"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						"fieldOptions" => array(
							"none" => "Linear",
							"function" => "Function",
						),
						"nullOption" => "Curve Type",
						"default" => "function",
						"cssClass" => "chartOption"
					),
					"labelInLegend" => array(
						"id" => "labelInLegend",  
						"fieldTitle" => __("Label in Legend", $this->plugin), 
						"submenuPage" => "{$this->prefix}_dashboard", 
						"fieldType" => "text", 
						"default" => "",
						"cssClass" => "chartOption"
					),
					"lineDashStyle" => array(
						//"slug" => chart_title, // field id
						"id" => "lineDashStyle",  // id attribute if the field
						"fieldTitle" => __("Line Dash Style", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
		
						"fieldType" => "array", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
						//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
						"default" => array(4,4),
						"cssClass" => "chartOption"
					),
					"lineWidth" => array(
						"id" => "lineWidth",  // id attribute if the field
						"fieldTitle" => __("Line Width", $this->plugin),
						"submenuPage" => "{$this->prefix}_dashboard", 
						"fieldType" => "number",
						"default" => 3,
						"cssClass" => "chartOption"
					),
					"pointShape" => array(
						"type" => array(
							"id" => "type",  // id attribute if the field
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
						"sides" => array(
							"id" => "sides",  // id attribute if the field
							"fieldTitle" => __("Point Shape Sides", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"default" => 5,
							"cssClass" => "chartOption"
						),
						"dent" => array(
							"id" => "dent",  // id attribute if the field
							"fieldTitle" => __("Point Shape Dent", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"fieldMin" => 0,
							"fieldMax" => 1,
							"fieldStep" => .1,
							"default" => .5,
							"cssClass" => "chartOption"
						),
						"rotation" => array(
							"id" => "rotation",  // id attribute if the field
							"fieldTitle" => __("Point Shape Rotation", $this->plugin),
							"submenuPage" => "{$this->prefix}_dashboard", 
							"fieldType" => "number",
							"fieldMin" => -179,
							"fieldMax" => 179,
							"fieldStep" => 10,
							"default" => 0,
							"cssClass" => "chartOption"
						)
					),
					"pointSize" => array(
						"id" => "pointSize",  // id attribute if the field
						"fieldTitle" => __("Point Size", $this->plugin),
						"submenuPage" => "{$this->prefix}_dashboard", 
						"fieldType" => "number",
						"default" => 10,
						"cssClass" => "chartOption"
					),
					"pointsVisible" => array(
						"id" => "pointsVisible",  
						"fieldTitle" => __("Points Visible", $this->plugin), 
						"submenuPage" => "{$this->prefix}_dashboard", 
						"fieldType" => "checkbox", 
						"default" => true,
						"cssClass" => "chartOption"
					),
					"targetAxisIndex" => array(
						"id" => "targetAxisIndex",  // id attribute if the field
						"fieldTitle" => __("Target Axis Index", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
						"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
						"fieldOptions" => array(
							0 => "Default Axis",
							1 => "Opposite Axis",
						),
						"nullOption" => "Target Axis Index",
						"chartTypes" => array(),
						"default" => 0,
						"cssClass" => "chartOption"
					),
					"visibleInLegend" => array(
						"id" => "visibleInLegend",  
						"fieldTitle" => __("Visible in Legend", $this->plugin), 
						"submenuPage" => "{$this->prefix}_dashboard", 
						"fieldType" => "checkbox", 
						"default" => false,
						"cssClass" => "chartOption"
					),

				)
			)
			
		);


	}








		/*
		* Admin Fields
		*/
		public function option_fields($chart_type = null) {
		
			// Fetch dashboard settings 
			//$settings = get_option("{$this->prefix}_dashboard");

			// extract files
			//$this->files = ($settings && isset($settings["files"]))? $settings["files"] : array();

			// Intialize file selection options array
			$file_select_options = [];

			// Loop through files to compse all the file select options
			if ($this->files) {
				foreach($this->files as $file_id => $file) {
					$file_select_options[$file_id] = $file["fileName"]; 
				}
			}


			return array(

				"fileSettings" => array(
					"id" => "fileSettings",
					"title" => "Upload/Select File",
					"subpanels" => array(
						"fileStuff" => array(
							"id" => "fileStuff",
							"title" => __("Upload Stuff", $this->plugin),
							"fields" => array(
								array(
									"id" => "chartId",  // id attribute if the field
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									"fieldType" => "hidden", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"default" => null,
								),
								array(
									//"slug" => "file_upload", // field id
									"id" => "fileUpload",  // id attribute if the field
									"fieldTitle" => __("Upload File", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									// "id" => "fileSettings",
									"fieldType" => "file", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"default" => null,
								),
								array(//"slug" => 'file_select', // field id
									"id" => "fileSelect",  // id attribute if the field
									"fieldTitle" => __("Select File", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => $file_select_options,
									"nullOption" => "Select File",
									"cssClass" => "hideOnLoad",
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["fileId"])) ? $settings["fileId"] : "",
									"default" => null,
								),
								array(
									//"slug" => "sheetId", // field id
									"id" => "sheet",  // id attribute if the field
									"fieldTitle" => __("Sheet", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(),
									"nullOption" => "Select Sheet",
									"cssClass" => "hideOnLoad",
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["sheetId"])) ? $settings["sheetId"] : "",
									"default" => null,
								),
								array(	//"slug" => "chart_type_select", // field id
									"id" => "chartType",  // id attribute if the field
									"fieldTitle" => __("Chart Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => $this->chart_types,
									"nullOption" => "Select Chart Type",
									"cssClass" => "hideOnLoad",
									//"description" => "Enable Custom Post Types", // Custom field description(supplied in the $args)
									//"value" => (isset($settings) && isset($settings["chart_type_select"])) ? $settings["chart_type_select"] : "",
									"default" => null,
								),
							)
						)
					)
				),

				"chartSettings" => array(
					"id" => "chartSettings",
					"title" => "Chart Settings",
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
									array(
										//"slug" => chart_title, // field id
										"id" => "interpolateNulls",  // id attribute if the field
										"fieldTitle" => __("Interpolate", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
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
								array(
									"id" => "backgroundColor.fill",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Background Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									"fieldType" => "text", 
									"default" => "#CCCCCC",
								),
								array(
									"id" => "backgroundColor.stroke",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Background Stroke Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"default" => "red",
								),
								array(
									"id" => "backgroundColor.strokeWidth",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Background Stroke Width", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"default" => "6",
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
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => true,
									"cssClass" => "chartOption"
								),
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
									"default" => "multiple",
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
								array(//"slug" => "chart_title", // field id
									"id" => "curveType",  // id attribute if the field
									"fieldTitle" => __("Curve Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"none" => "Linear",
										"function" => "Function",
									),
									"nullOption" => "Curve Type",
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "none",
									"cssClass" => "chartOption"
								),
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
							)
						),
						"animation" => array(
							"id" => "animation",
							"title" => "Animation",
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
								// array(
								// 	//"slug" => chart_title, // field id
								// 	"id" => "subtitle",  // id attribute if the field
								// 	"fieldTitle" => __("Subtitle", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
								// 	"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
								// 	"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
								// 	"chartTypes" => array(
								// 		"Line"
								// 	),
								// 	//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
								// 	//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
								// 	"default" => "World",
								// 	"cssClass" => "chartOption"
								// ),
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
							)
						)
					)
				),

				"horizontalAxis" => array(
					"id" => "horizontalAxis",
					"title" => "Horizontal Axis",
					"subpanels" => array(
						"general" => array(
							"id" => "general",
							"title" => "General Settings",
							"chartTypes" => array("LineChart"),
							"fields" => array(
								array(
									"id" => "hAxis.viewWindowMode",  // id attribute if the field
									"fieldTitle" => __("View Window Mode", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"pretty" => "Pretty",
										"maximized" => "Maximized",
										"explicit" => "Explicit",
									),
									"nullOption" => "Select Font",
									"default" => "verdana",
									"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.viewWindow.min",  // id attribute if the field
										"fieldTitle" => __("View Window Min", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.viewWindow.max",  // id attribute if the field
										"fieldTitle" => __("View Window Max", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => null,
										"cssClass" => "chartOption"
									)
								),
								
								
							
							
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.direction",  // id attribute if the field
										"fieldTitle" => __("Direction", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"1" => "Left to Right",
											"-1" => "Right to Left",
										),
										"nullOption" => "Axis Direction",
										"chartTypes" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "hAxis.format",  // id attribute if the field
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
										"chartTypes" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.logScale",  // id attribute if the field
										"fieldTitle" => __("Log Scale", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "hAxis.scaleType",  // id attribute if the field
										"fieldTitle" => __("Scale Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "Normal",
											true => "Log Scale",
										),
										"nullOption" => "Scale Type",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "hAxis.minValue",  // id attribute if the field
										"fieldTitle" => __("Minimum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "hAxis.maxValue",  // id attribute if the field
										"fieldTitle" => __("Maximum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),

								)
							)
						),

						"title" => array(
							"id" => "title",
							"title" => "Title",
							"fields" => array(

								array(
									"id" => "hAxis.title",  // id attribute if the field
									"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"Line"
									),
									"default" => "World",
									"cssClass" => "chartOption"
								),
								
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.titleTextStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Title Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Title Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 40,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Title Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Title Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),

								array(
									//"slug" => chart_title, // field id
									"id" => "hAxis.showTextEvery",  // id attribute if the field
									"fieldTitle" => __("Show Text Every", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldMin" => 0,
									"fieldMax" => 1000,
									"fieldStep" => 1,
									"chartTypes" => array(
										"Line"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 1,
									"cssClass" => "chartOption"
								),

							)
						),

						"label" => array(
							"id" => "label",
							"title" => "Label",
							"fields" => array(

									array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Text Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
									array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.textStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Text Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "teal",
									//"cssClass" => "chartOption"
								),							
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Text Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Text Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),

								array(
										//"slug" => chart_title, // field id
									"id" => "hAxis.textPosition",  // id attribute if the field
									"fieldTitle" => __("Text Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"in" => "Inside the Chart",
										"out" => "Outside the Chart",
										"none" => "Omit title"
									),
									"nullOption" => "Position",
									"chartTypes" => array(
										"Line"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "",
									"cssClass" => "chartOption"
								),


								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.slantedText",  // id attribute if the field
										"fieldTitle" => __("Slanted Text", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.slantedTextAngle",  // id attribute if the field
										"fieldTitle" => __("Slanted Text Angle", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
							
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldMin" => -179,
										"fieldMax" => 179,
										"fieldStep" => 1,
										"chartTypes" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => 45,
										"cssClass" => "chartOption"
									),
								),

							)
						),

						"gridlines" => array(
							"id" => "gridlines",
							"title" => "Gridlines",
							"fields" => array(


								array(
									//"slug" => chart_title, // field id
									"id" => "hAxis.baseline",  // id attribute if the field
									"fieldTitle" => __("Axis Baseline", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => null,
									"cssClass" => "chartOption"
								),
								
							
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.baselineColor",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Axis Baseline Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								
									
								array(
									//"slug" => chart_title, // field id
									"id" => "hAxis.ticks",  // id attribute if the field
									"fieldTitle" => __("Ticks", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "auto",
									"cssClass" => "chartOption"
								),

								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.gridlines.count",  // id attribute if the field
										"fieldTitle" => __("Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => -1,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "hAxis.minorGridlines.count",  // id attribute if the field
										"fieldTitle" => __("Minor Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => -1,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.gridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "hAxis.minorGridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Minor Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),


							)
						),

						

					)
				),

				"verticalAxis" => array(
					"id" => "verticalAxis",
					"title" => "Vertical Axis",
					"subpanels" => array(
						"general" => array(
							"id" => "general",
							"title" => "General Settings",
							"chartTypes" => array("LineChart"),
							"fields" => array(
								array(
									"id" => "vAxis.viewWindowMode",  // id attribute if the field
									"fieldTitle" => __("View Window Mode", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"pretty" => "Pretty",
										"maximized" => "Maximized",
										"explicit" => "Explicit",
									),
									"nullOption" => "Select Font",
									"default" => "verdana",
									"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.viewWindow.min",  // id attribute if the field
										"fieldTitle" => __("View Window Min", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.viewWindow.max",  // id attribute if the field
										"fieldTitle" => __("View Window Max", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => null,
										"cssClass" => "chartOption"
									)
								),
								
								array(
								
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.baseline",  // id attribute if the field
										"fieldTitle" => __("AxisBaseline", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => null,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.baselineColor",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Axis Baseline Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								
									
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.ticks",  // id attribute if the field
										"fieldTitle" => __("Ticks", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "auto",
										"cssClass" => "chartOption"
									),
								
							
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.direction",  // id attribute if the field
										"fieldTitle" => __("Direction", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"1" => "Bottom to Top",
											"-1" => "Top to Bottom",
										),
										"nullOption" => "Axis Direction",
										"chartTypes" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "vAxis.format",  // id attribute if the field
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
										"chartTypes" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.logScale",  // id attribute if the field
										"fieldTitle" => __("Log Scale", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => false,
										"cssClass" => "chartOption"
									),
									array(//"slug" => "chart_title", // field id
										"id" => "vAxis.scaleType",  // id attribute if the field
										"fieldTitle" => __("Scale Type", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"" => "No Log Scaling",
											"log" => "Log Scaling",
											"mirrorLog" => "Negative/Zero Values Plotted",
										),
										"nullOption" => "Scale Type",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										"id" => "vAxis.minValue",  // id attribute if the field
										"fieldTitle" => __("Minimum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "vAxis.maxValue",  // id attribute if the field
										"fieldTitle" => __("Maximum Value", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => null,
										"cssClass" => "chartOption"
									),

								)
							)
						),

						"title" => array(
							"id" => "title",
							"title" => "Title",
							"fields" => array(

								array(
									//"slug" => chart_title, // field id
									"id" => "vAxis.title",  // id attribute if the field
									"fieldTitle" => __("Title", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			 
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"Line"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "World",
									"cssClass" => "chartOption"
								),
								
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.titleTextStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Title Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "yellow",
									//"cssClass" => "chartOption"
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Title Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 40,
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Title Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titleTextStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Title Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.titlePosition",  // id attribute if the field
										"fieldTitle" => __("Title Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"in" => "Inside the Chart",
											"out" => "Outside the Chart",
											"none" => "Omit title"
										),
										"nullOption" => "Position",
										"chartTypes" => array(
											"Line"
										),
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "",
										"cssClass" => "chartOption"
									),

							)
						),

						"label" => array(
							"id" => "label",
							"title" => "Label",
							"fields" => array(

									array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Text Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 12,
										"cssClass" => "chartOption"
									),
								),
									array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.textStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Text Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && //isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "teal",
									//"cssClass" => "chartOption"
								),							
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.bold",  // id attribute if the field
										"fieldTitle" => __("Text Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Text Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "vAxis.textPosition",  // id attribute if the field
									"fieldTitle" => __("Text Position", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldOptions" => array(
										"in" => "Inside the Chart",
										"out" => "Outside the Chart",
										"none" => "Omit title"
									),
									"nullOption" => "Position",
									"chartTypes" => array(
										"Line"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "",
									"cssClass" => "chartOption"
								),

							)
						),

						"gridlines" => array(
							"id" => "gridlines",
							"title" => "Gridlines",
							"fields" => array(

								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "vAxis.gridlines.count",  // id attribute if the field
										"fieldTitle" => __("Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
						
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => -1,
										"cssClass" => "chartOption"
									),
									array(
										"id" => "vAxis.minorGridlines.count",  // id attribute if the field
										"fieldTitle" => __("Minor Gridlines Count", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => -1,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.gridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "vAxis.minorGridlines.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Minor Gridlines Color Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "red",
								),
								


							)
						),
			

					)
				),


				"toolTips" => array(
					"id" => "toolTips",
					"title" => "Tooltips",
					"subpanels" => array(
						array(
							"id" => "toolTips",
							"title" => "Tool Tips",
							"fields" => array(
								array(
									array(//"slug" => "chart_title", // field id
										"id" => "tooltip.text",  // id attribute if the field
										"fieldTitle" => __("Text", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs	
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"both" => "Value and Percentage",
											"value" => "Value",
											"percentage" => "Percentage",
										),
										"nullOption" => "Select Font",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "both",
										"cssClass" => "chartOption"
									),
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.trigger",  // id attribute if the field
										"fieldTitle" => __("Trigger", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "select", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"fieldOptions" => array(
											"focus" => "Hover",
											"none" => "None",
											"selection" => "Selection",
										),
										"nullOption" => "Select Font",
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "selection",
										"cssClass" => "chartOption"
									),
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.textStyle.fontName",  // id attribute if the field
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
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => "tahoma",
										"cssClass" => "chartOption"
									),
									
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.textStyle.fontSize",  // id attribute if the field
										"fieldTitle" => __("Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										"default" => 30,
										"cssClass" => "chartOption"
									),
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "tooltip.textStyle.color",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "teal",
								),
								array(
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.textStyle.bold",  // id attribute if the field
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
										"id" => "tooltip.textStyle.italic",  // id attribute if the field
										"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
										"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
				
										"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
										//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
										//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
										"default" => true,
										"cssClass" => "chartOption"
									),
								
									array(
										//"slug" => chart_title, // field id
										"id" => "tooltip.showColorCode",  // id attribute if the field
										"fieldTitle" => __("Show Color Code", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
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
									//"slug" => chart_title, // field id
									"id" => "is3D",  // id attribute if the field
									"fieldTitle" => __("3D Pie Chart", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => false
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieHole",  // id attribute if the field
									"fieldTitle" => __("Pie Hole", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"fieldMin" => 0,
									"fieldMax" => 1,
									"fieldStep" => 0.1,
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 0.5,
									"disableIf" => array("is3D" => true)
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "sliceVisibilityThreshold",  // id attribute if the field
									"fieldTitle" => __("slice Visibility Threshold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									"fieldMin" => 0,
									"fieldMax" => 1,
									"fieldStep" => 0.1,
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 0.1,	
								),
								array(
									//"slug" => chart_title_color, // field id
									"id" => "pieSliceBorderColor",  // id attribute if the field
									"cssClass" => "color-picker",
									"fieldTitle" => __("Pie Slice Border Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title_color])) ? $settings[chart_title_color] : "",
									"default" => "magenta",
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieStartAngle",  // id attribute if the field
									"fieldTitle" => __("Pie Slice Angle", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "number", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									"fieldMin" => 0,
									"fieldMax" => 360,
									"fieldStep" => 1,
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 0,
									//"disableIf" => array("is3D" => true)
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieResidueSliceLabel",  // id attribute if the field
									"fieldTitle" => __("Pie Residual Slice Label", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "ZZZ"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieResidueSliceColor",  // id attribute if the field
									"fieldTitle" => __("Pie Residual Slice ColorColor", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"cssClass" => "color-picker",
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "yellow"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceText",  // id attribute if the field
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
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "label"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.color",  // id attribute if the field
									"fieldTitle" => __("Color", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"cssClass" => "color-picker",
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
					
									"fieldType" => "color-picker", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "white"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.fontName",  // id attribute if the field
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
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => "georgia"
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.fontSize",  // id attribute if the field
									"fieldTitle" => __("Font Size", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "text", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => 10
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.bold",  // id attribute if the field
									"fieldTitle" => __("Bold", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => false
								),
								array(
									//"slug" => chart_title, // field id
									"id" => "pieSliceTextStyle.italic",  // id attribute if the field
									"fieldTitle" => __("Italic", $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
									"submenuPage" => "{$this->prefix}_dashboard", // Section to which this field belongs
			
									"fieldType" => "checkbox", // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
									"chartTypes" => array(
										"PieChart"
									),
									//description => Enable Custom Post Types, // Custom field description(supplied in the $args)
									//value => (isset($settings) && isset($settings[chart_title])) ? $settings[chart_title] : "",
									"default" => true
								)
							)
						)
					)
				),

				"advanced" => array(
					"id" => "advanced",
					"title" => "Advanced",
					"subpanels" => array(
						array(
							"id" => "slideControl",
							"title" => __("Slide Control", $this->plugin),
							"fields" => array(
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
							)
						)
					)
				),
	 		);

		} // END  private  function register_fields (){




	} // END class Dashboard {


} // END if (!class_exists(Dashboard))