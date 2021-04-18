jQuery(document).ready(function($) {
	
	"use strict";

	// Google visulaization dashboard
	var dashboard;

	// // Google chart data
	// var data;

	//  Google visulaization chart
	var iwpgvChart;

	// Google dashboard table chart
	var iwpgvTable

	// Google visulaization number range slider control
	var numRangeFilter;

	// Google chart Options
	var chartOptions

	// Google table chart options
	var tableChartOptions;

	// Google chart number range options
	var numRangeFilterOptions;

	// Min, Max and Average table options
	var minMaxAvgTableOptions;

	// server response
	var response;

	// user messages (error and success)
	var message;

	// Load the Visualization API and the corechart package.
	//google.charts.load('current', {'packages':['line', 'table', 'controls']});
	google.charts.load('current', {'packages':['corechart','controls']});

	//**************************** New, Edit or list graph render ***************************

	// Fetch wp_localize chart object data
	response = iwpgv_chart_object.response;
	
	if (response) {

		console.log(response);

		// Set chart options
		chartOptions = response.chartOptions;

		// Set table chart options
		tableChartOptions = response.tableChartOptions

		// Set number range filter options
		numRangeFilterOptions = response.numRangeFilterOptions;

		// Set Min, Max and Average table options
		minMaxAvgTableOptions = response.minMaxAvgTableOptions;


		// Display error message
		$("#iwpgv-dashboard #adminMessages").html(response.message) ;

		// If there are files in the database, show file selection field
		var fileId = $("#iwpgv-dashboard #fileId");
		(fileId.find("option").length > 1)? fileId.parent(".fieldWrapper").slideDown() : fileId.parent(".fieldWrapper").slideUp();

		// Hide sheet select if no file is selected
		(fileId.val())? $("#iwpgv-dashboard #sheetId").parent(".fieldWrapper").slideDown() : $("#iwpgv-dashboard #sheetId").parent(".fieldWrapper").slideUp();
		
		// Hide chart type select if no sheet is selected
		(fileId.val())? $("#iwpgv-dashboard #chartType").parent(".fieldWrapper").slideDown() : $("#iwpgv-dashboard #chartType").parent(".fieldWrapper").slideUp();

		// Add class active to file settings button and show adjacent panel 
		$("#iwpgv-dashboard button.accordionButton.fileSettings").addClass("active").slideDown().next(".panel#fileSettings").slideDown();

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='numRangeFilter.filterColumnLabel']").append(response.filterColumnOptions);

		// show number range filter enable checkbox
		//$("#iwpgv-dashboard input#numRangeFilterEnable").slideDown();


		// Set accordion
		$("#iwpgv-dashboard .accordion").iwpgvAccordion();

		// Show field hints on mouseover
	  	$("#iwpgv-dashboard .hint").mouseover(function(){
	  		$(this).parent().parent().children(".fieldHint").css("opacity", 1).slideDown();
	  	});

	   // Hide field hints on mouseover
	   $("#iwpgv-dashboard .hint").mouseout(function(){
			$(this).parent().parent().children(".fieldHint").css("opacity", 0).slideUp();
		});

		// Get all inputs
		var allInputs = $("#iwpgv-dashboard select, #iwpgv-dashboard input");

		// Loop through all input
		$.each(allInputs, function(i,input) {

			// Intialize input id
			var inputId = input.id;

			// Set field class
			var inputClass = $(input).attr("class");

			// Initialize input value
			var inputValue = input.value;
			
			// Initialize series id parts
			var parts = inputId.split(".");

			var labelOptions = [];

			// do if inputs have class chartOption
			if (!! inputClass && inputClass.includes("chartOption")) {
			
				// Toggle series.i.labelInLegend based on series.i.visibleInLegend, 
				if (inputId.includes("visibleInLegend")) {
					inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + ".labelInLegend']"));
					$(input).change(function() {
						inputLoadChangeEventHandler($(this).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + ".labelInLegend']"));
						setChartOption(inputId, $(this).prop("checked"));
					});
				} else

				// Toggle aggregationTarget based on selectionMode, 
				if (inputId == "selectionMode" || inputId == "tooltip.trigger") {
					var selectionMode = $("#iwpgv-dashboard select[id='selectionMode']");
					var tooltipTrigger = $("#iwpgv-dashboard select[id='tooltip.trigger']");
					inputLoadChangeEventHandler(selectionMode.val() == "multiple" && tooltipTrigger.val() == "selection", $("#iwpgv-dashboard select[id='aggregationTarget']"));
					$(input).change(function() {
						var selectionMode = $("#iwpgv-dashboard select[id='selectionMode']");
						var tooltipTrigger = $("#iwpgv-dashboard select[id='tooltip.trigger']");
						inputLoadChangeEventHandler(selectionMode.val() == "multiple" && tooltipTrigger.val() == "selection", $("#iwpgv-dashboard select[id='aggregationTarget']"));
						setChartOption(inputId, $(this).val());
					});
				} else

				// Toggle series.i.pointShape.type, pointShape.sides, pointShape.dent, pointSize, pointShape.rotation, based on series.i.pointsVisible, 
				if (inputId.includes("pointsVisible")) {
					var pointOptions = [".pointSize", ".pointShape.sides", ".pointShape.dent", ".pointShape.rotation"];
					$.each(pointOptions, function (i, option) {
						inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + option + "']"));
					});
					inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard select[id='"+ parts[0] + "." + parts[1] + ".pointShape.type']"));
					
					$(input).change(function() {
						$.each(pointOptions, function (i, option) {
							inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + option + "']"));
						});
						inputLoadChangeEventHandler($(this).prop("checked"), $("#iwpgv-dashboard select[id='"+ parts[0] + "." + parts[1] + ".pointShape.type']"));
						setChartOption(inputId,  $(this).prop("checked"));
					});

				} else 

				// Toggle hAxis.slantedTextAngle, hAxis.allowContainerBoundaryTextCufoff, hAxis.maxAlternation, hAxis.maxTextLines, hAxis.minTextSpacing if hAxis.slantedText is checked
				if (inputId == "hAxis.slantedText") {
					labelOptions = [".slantedTextAngle", ".allowContainerBoundaryTextCufoff", ".maxAlternation", ".maxTextLines", ".minTextSpacing"];
					$.each(labelOptions, function (i, option) {
						inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='hAxis" + option + "']"));
					});
					$(input).change(function() {
						$.each(labelOptions, function (i, option) {
							inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='hAxis" + option + "']"));
						});
						setChartOption(inputId, $(input).prop("checked"));
					});

				} else 
				// toggle hAxis.slantedText, hAxis.slantedTextAngle, hAxis.allowContainerBoundaryTextCufoff, hAxis.maxAlternation, hAxis.maxTextLines, hAxis.minTextSpacing based on hAxis.textPosition
				if (inputId == "hAxis.textPosition" ) {
					labelOptions = [".slantedText", ".slantedTextAngle", ".allowContainerBoundaryTextCufoff", ".maxAlternation", ".maxTextLines", ".minTextSpacing"];
					$.each(labelOptions, function (i, option) {
						inputLoadChangeEventHandler(inputValue == "out", $("#iwpgv-dashboard input[id='hAxis" + option + "']"));
					});
					$(input).change(function() {
						$.each(labelOptions, function (i, option) {
							inputLoadChangeEventHandler(inputValue == "out", $("#iwpgv-dashboard input[id='hAxis" + option + "']"));
						});
						setChartOption(inputId, $(this).val());
					});

				} else

				if (inputClass.includes("hasFieldSuffix")) {
					var suffix = $(input).siblings(".fieldSuffix").html();
					//setChartOption(inputId, inputValue + suffix);
					$(input).change(function() {
						setChartOption(inputId, $(this).val() + suffix);
				  	});

				} else 

				if ( inputId.includes("ticks") || inputId.includes("lineDashStyle")) {
					$(input).change(function() {
		 				var ticks = ($(this).val())? $(this).val().split(",") : "auto" ;
		 				console.log(ticks.length);
		 				setChartOption(inputId, ticks);
		 			});



				} else {

					$(input).change(function() {
						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			} else {
			 				inputValue = $(this).val();
			 			}

						setChartOption(inputId, inputValue);

				  	});

			  	}

			 // if input has class numRangeOption 
			} else if (!! inputClass && inputClass.includes("numRangeOption")) {

				console.log(inputId + "======" + inputValue);

				// Set input id (number range options start with numRangeFilter wich is eliminate as part[0])
				numRangeOptionId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];


				// Number range filter behaviour
				if ( inputId == "numRangeFilter.enable") {
					
					// Get all number range filter panel inputs 
		 			var numRangeFilterInputs = $("#iwpgv-dashboard .panel#numRangeFilter input, #iwpgv-dashboard .panel#numRangeFilter select").not("#iwpgv-dashboard input[id='numRangeFilter.enable']");

	 				if ($(input).prop("checked")){
	 				 	$("#iwpgv-dashboard select[id='numRangeFilter.filterColumnLabel']").parent(".fieldWrapper").slideDown();
	 				} else {
	 				 	$("#iwpgv-dashboard select[id='numRangeFilter.filterColumnLabel']").val(null).parent(".fieldWrapper").slideUp();
	 				}

				// Get number range column label input
				//var filterColumnLabel = $("#iwpgv-dashboard select[id='numRangeFilter.filterColumnLabel']");

				//$.each (numRangeFilterInputs, function (i, numRangeFilterinput) {
					//if (numRangeFilterinput.id != "numRangeFilter.enable" )
					//$(numRangeFilterinput).parent(".fieldWrapper").slideUp();
				//});
					$(document).on("change", $(input), function(event) {

						$("#iwpgv-dashboard #adminMessages").html("");

		 				if ($(input).prop("checked")){
		 				 	$("#iwpgv-dashboard select[id='numRangeFilter.filterColumnLabel']").parent(".fieldWrapper").slideDown();
		 				 // 	if (filterColumnLabel.val()) {
		 				 // 		$.each (numRangeFilterInputs, function (i, numRangeFilterinput) {
								// 	$(numRangeFilterinput).parent(".fieldWrapper").slideDown();
								// });
		 				 // 	} else {
		 				 // 		$.each (numRangeFilterInputs, function (i, numRangeFilterinput) {
								// 	if (numRangeFilterinput.id != "numRangeFilter.enable"  && numRangeFilterinput.id != "numRangeFilter.filterColumnLabel")
								// 		$(numRangeFilterinput).parent(".fieldWrapper").slideUp();
								// });
		 				 // 	}
		 				} else {
		 				 	$("#iwpgv-dashboard select[id='numRangeFilter.filterColumnLabel']").val(null).parent(".fieldWrapper").slideUp();
		 				//  	$.each (numRangeFilterInputs, function (i, numRangeFilterinput) {
							// 	if (numRangeFilterinput.id != "numRangeFilter.enable")
							// 		$(numRangeFilterinput).parent(".fieldWrapper").slideUp();
							// });

	 				 // 		//Render chart
							// renderChart(response.sheet, response.divId, response.chartType, chartOptions, null, null, null);

							// $("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked", false);

							// $("#iwpgv-dashboard #filter-" + response.divId + "-div").html("");
							// $("#iwpgv-dashboard #table-" + response.divId + "-div").html("");

		 				}

		 			});

				} else

		 		
				// Filter column label input behaviour
				if ( inputId == "filterColumnLabel") {

					$(document).on("change", $(input), function(event) {

					//$(input).change(function() {

						// if a filter column is selected
						if ($(input).val()) {

							// Unhide all number range filter option inputs
							$.each (numRangeFilterInputs, function (i, numRangeFilterinput) {
								$(numRangeFilterinput).parent(".fieldWrapper").slideDown();
							});

							// Set filter column label (default = "")
							numRangeFilterOptions.filterColumnLabel = $(this).val();

						 	//Render chart
							renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, null, null);

						// If a filter column is not selected
						} else {

							// Hide all number range filter inputs except enable checkbox and filter column itself
							$.each (numRangeFilterInputs, function (i, numRangeFilterinput) {
								if (numRangeFilterinput.id != "numRangeFilter.enable"  && numRangeFilterinput.id != "numRangeFilter.filterColumnLabel")
								$(numRangeFilterinput).parent(".fieldWrapper").slideUp();
							});

							//Render chart
							renderChart(response.sheet, response.divId, response.chartType, chartOptions, null, null, null );

							// Remove number range slider
							$("#iwpgv-dashboard #filter-" + response.divId + "-div").html("");

						}

	 				});

				// behavior for all other inputs
				}  else {

					$(input).change(function() {
						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			} else {
			 				inputValue = $(this).val();
			 			}

						setNumRangeSliderOption(inputId, inputValue);

				  	});

				}

			// if input has class tableChartOptions 
			} else if (!! inputClass && inputClass.includes("tableChartOptions")) {

				// Set input id (number range options start with numRangeFilter wich is eliminate as part[0])
				inputId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];

				// Get all number range filter panel inputs 
		 		var tableChartInputs = $("#iwpgv-dashboard .panel#tableChart input, #iwpgv-dashboard .panel#tableChart select");

				$.each (tableChartInputs, function (i, tableChartIinput) {
					if (tableChartIinput.id != "tableChart.enable" )
					$(tableChartIinput).parent(".fieldWrapper").slideUp();
				});

				// Number range filter behaviour
				if ( inputId == "enable") {
					
					$(document).on("change", $(input), function() {

						$("#iwpgv-dashboard #adminMessages").html("");

						if ( ! numRangeFilterOptions.filterColumnLabel) {

							$(input).prop("checked", false);
							// Display error message
							message = "<div class='notice notice-error is-dismissible'><p> Please enable Number Range Slider and Select A filter column first</p></div>";
							$("#iwpgv-dashboard #adminMessages").html(message);

						} else {
			 				if ($(input).prop("checked")){
		 				 		$.each (tableChartInputs, function (i, tableChartIinput) {
									$(tableChartIinput).parent(".fieldWrapper").slideDown();
								});

								//Render chart
								renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, tableChartOptions, null);
			 				 	
			 				} else {
			 				 	$.each (tableChartInputs, function (i, tableChartIinput) {
									if (tableChartIinput.id != "tableChart.enable")
										$(tableChartIinput).parent(".fieldWrapper").slideUp();
								});

		 				 		//Render chart
								renderChart(response.sheet, response.divId, response.chartType, chartOptions, null, null, null);

								$("#iwpgv-dashboard #table-" + response.divId + "-div").html("");

			 				}
			 			}

		 			});
				}



			// if input has class minMaxAvgOptionss 
			} else if (!! inputClass && inputClass.includes("minMaxAvgOptions")) {

				// Set input id (number range options start with numRangeFilter wich is eliminate as part[0])
				inputId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];

						// Get all number range filter panel inputs 
		 		var minMaxAvgTableInputs = $("#iwpgv-dashboard .panel#minMaxAvgTable input, #iwpgv-dashboard .panel#minMaxAvgTable select");

				// Get number range column label input
				//var filterColumnLabel = $("#iwpgv-dashboard select[id='minMaxAvgTable.filterColumnLabel']");

				$.each (minMaxAvgTableInputs, function (i, minMaxAvgTableinput) {
					if (minMaxAvgTableinput.id != "minMaxAvgTable.enable" )
					$(minMaxAvgTableinput).parent(".fieldWrapper").slideUp();
				});

				// Number range filter behaviour
				if ( inputId == "enable") {
					
					$(input).change(function() {
		 				if ($(this).prop("checked")){
		 				 	
	 				 		$.each (minMaxAvgTableInputs, function (i, minMaxAvgTableinput) {
								$(minMaxAvgTableinput).parent(".fieldWrapper").slideDown();
							});

							//Render chart
							renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, null, minMaxAvgTableOptions);
		 				 	
		 				} else {
		 				 	$.each (minMaxAvgTableInputs, function (i, minMaxAvgTableinput) {
								if (minMaxAvgTableinput.id != "minMaxAvgTable.enable")
									$(minMaxAvgTableinput).parent(".fieldWrapper").slideUp();
							});

	 				 		//Render chart
							renderChart(response.sheet, response.divId, response.chartType, chartOptionsnull, null, null, null);

							$("#iwpgv-dashboard #filter-" + response.divId + "-div").html("");

		 				}

		 			});


				// behavior for all other inputs
				}  else {

					$(input).change(function() {
						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			} else {
			 				inputValue = $(this).val();
			 			}

						setNumRangeSliderOption(inputId, inputValue);

				  	});

				}

			}

		});

		
		switch (response.get.action) {
				
			//New chart
			case "addNewChart":
				
				if (! response.message || (response.message && ! response.message.includes("notice-error"))) {

					//Render chart
					renderChart(response.sheet, response.divId, response.chartType, chartOptions, null, null, null);

					// Set color pickers
					setColorPickers();	

				}
					
				break;

			// An existing chart is being edited
			case "editChart":				

	
				break;

			default:

		
		}
	}


	function inputLoadChangeEventHandler(condition, input) {
		if (condition ){
			input.attr("disabled", false); 
		} else {
			input.attr("disabled", true); 
		}
	}

	



	function setChartOption(inputId, inputValue) {
		console.log("chart redwraw");
		console.log(inputId);
		console.log(inputValue);
		// Set option
		iwpgvChart.setOption(inputId, inputValue);
		
		// Redraw chart
		iwpgvChart.draw();
	}





	function setNumRangeSliderOption(inputId, inputValue) {
		console.log("range slider redwraw");
		console.log(inputId);
		console.log(inputValue);
		// Set option
		numRangeFilter.setOption(inputId, inputValue);
		
		// Redraw chart
		numRangeFilter.draw();
	}





	// *****************************************  When chart option input fields change   *****************************************

	//$("input, select").change(function() {
	$(document).on("change", "#iwpgv-dashboard inputxx, selectxx", function(event) {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

		// Set field value
		var fieldValue =  $(this).val();

		// Set field class
		var fieldClass = $(this).attr("class");

		// Set field id
		var fieldId = $(this).attr("id");

		var parts = fieldId.split(".");

		if (!! fieldClass && fieldClass.includes("chartOption")) {

			console.log($(this).attr("id"));
			console.log($(this).val());


		  	// checkbox behviour
		  	if ($(this).attr("type") == "checkbox") {
		 		iwpgvChart.setOption(fieldId, $(this).prop("checked"));
		 		
	 		// All other input types including, ticks, dashes and suffixes
	 		} else {

				if ( fieldId.includes("ticks") || fieldId.includes("lineDashStyle")) {
	 				var ticks = fieldValue.split(",");// Set option
				iwpgvChart.setOption(fieldId, fieldValue);
	 				iwpgvChart.setOption( fieldId, ticks);
	 			}

	 			if (fieldClass.includes("hasFieldSuffix")) {
			 		var suffix = $(this).siblings(".fieldSuffix").html();
					iwpgvChart.setOption(fieldId, fieldValue + suffix );
				}

				iwpgvChart.setOption(inputId, inputValue);
				
			}
		  	
		  	// Redraw chart
		  	iwpgvChart.draw();
		}

	});






	function renderChart(sheet, divId, chartType, chartOptions, numRangeFilterOptions, tableChartOptions, minMaxAvgTableOptions ) {

		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawVisualization);
		
		function drawVisualization() {

			// Get datatable
			var data = getDatatable(sheet);

			console.log(data);

				// If no filter column is defined
			if ( numRangeFilterOptions) {

				// Instantiate a dashboard object.
				dashboard = new google.visualization.Dashboard(document.getElementById('dashboard-'+divId+'-div'));

				// Create chart, passing some options
				iwpgvChart = new google.visualization.ChartWrapper({
					chartType: chartType,
					containerId: 'chart-'+divId+'-div',
					options: chartOptions
				
				});

				//Create a table chart, passing some options
				if (tableChartOptions) {
					iwpgvTable = new google.visualization.ChartWrapper({
						chartType: 'Table',
						containerId: 'table-'+divId+'-div',
						options: {}
					});
				}



				if (numRangeFilterOptions.filterColumnLabel) {
					console.log("control");

					// Create a range slider, passing some options
					numRangeFilter = new google.visualization.ControlWrapper({
						controlType: 'NumberRangeFilter',
						containerId: 'filter-'+divId+'-div',
						options: numRangeFilterOptions
					});

					// Establish dependencies, declaring what filter drives the chart, table
					dashboard.bind(numRangeFilter, iwpgvChart); //.bind(numRangeFilter, iwpgvTable);

					// Establish dependencies, declaring what filter drives the chart, table
					if (tableChartOptions) 
						dashboard.bind(numRangeFilter, iwpgvTable); //.bind(numRangeFilter, iwpgvTable);
				}
			


				// Draw the dashboard.
				dashboard.draw(data);

			

			// if filter column is set
			}  else {

					// Create chart, passing some options
				iwpgvChart = new google.visualization.ChartWrapper({
					dataTable: data,
					chartType: chartType,
					containerId: 'chart-'+divId+'-div',
					options: chartOptions
				
				});

			 	iwpgvChart.draw();

			}

			// Add one time event listener
			google.visualization.events.addOneTimeListener(iwpgvChart, 'ready', oneTimeChartReadyHandler );

			// Add chart error handler
			google.visualization.events.addListener(iwpgvChart, 'error', chartErrorHandler );

			//console.log(getMinMaxAvg(data));

		}

	} // END function renderChart(data, divId







	function getMinMaxAvg(data) {


		var minMaxAvg = {};

		var colIndex = 0;

		var nonNullRowCount = [];

		do {

			if (data.getColumnRole(colIndex) == "data" && data.getColumnType(colIndex) == "number") {

				minMaxAvg[colIndex] = {};

				minMaxAvg[colIndex].min = data.getColumnRange(colIndex).min;
				minMaxAvg[colIndex].max = data.getColumnRange(colIndex).max;

				var rowIndex = 0;

				var colSum = 0;

				nonNullRowCount[colIndex] = 0;

				do {

					if (data.getValue(rowIndex, colIndex)) {
						console.log(data.getValue(rowIndex, colIndex));
						colSum += data.getValue(rowIndex, colIndex);
						nonNullRowCount[colIndex]++;
					}						

					rowIndex++;

				} while (rowIndex < data.getNumberOfRows());


				minMaxAvg[colIndex].avg = colSum/nonNullRowCount[colIndex];

			}


			colIndex++;


		} while (colIndex < data.getNumberOfColumns());



		return minMaxAvg;

	}






	function getDatatable(sheet) {

		// Create empty datatable
		var data = new google.visualization.DataTable();
		
		// Loop through all the labels row to add datatable columns (eliminate all null values)
		$.each (sheet.labels, function (i, label) {
			data.addColumn({
				type: sheet.dataTypes[i], 
				label: label,
				role: sheet.roles[i]

				});
		});

		// initial datatbale rows array
		var arr = [];
		
		// Loop through all rows
		$.each (sheet.data, function (i, row) {
			
			// Initialize column array
			var element =[];

			// loop through all column element and add to the column array
			$.each (row, function (j, cell) {
					element.push(cell);

			});

			// Add the column array to the rows arrays if the rowe and comun length match (cells with null values are not added)
			arr.push(element);
		});

		// add all rows to datatable
		data.addRows(arr);

		// Return datatable
		return data;


	}






	function oneTimeChartReadyHandler () {
		
		// Get all inputs
		var allInputs = $("#iwpgv-dashboard input");
		
		// Loop through all inputs
		$.each(allInputs, function(i, input) {

			// add suffix to all inputs with class containing hasSuffix
			if (!! $(input).attr("class") && $(input).attr("class").includes("hasFieldSuffix")){
				var suffix = $(input).siblings(".fieldSuffix").html();
				iwpgvChart.setOption($(input).attr("id"), $(input).val() + suffix );
			}

		});

		iwpgvChart.draw();
	}





	function chartErrorHandler(error) {

		//console.log(error);

		// Display error message
		$("#" + templateId + " .gvErrorMessages").append(displayMessage(error.message) );
		console.log(displayMessage(error.message));

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}









	function setColorPickers () {
	
 	// Setup WP Core color picker
	$("#iwpgv-dashboard input.color-picker").each(function(){
		$(this).wpColorPicker({
			change: function(event, ui){

				// set chart option	
		 		iwpgvChart.setOption($(this).attr("id"), ui.color.toString() );
		  		
		  		// Redraw chart
		  		iwpgvChart.draw();
			},

			clear: function() {


				var field = $(this).parent().find(".wp-color-picker");

				// set chart option	
		 		iwpgvChart.setOption(field.attr("id"), "transparent" );
		  		
		  		// Redraw chart
		  		iwpgvChart.draw();
								//console.log(field.attr("id"));

			},
		});
 	});

}





});  // END jQuery(document).ready(function($) {




(function ( $ ) {

	 // Accordion
	 $.fn.iwpgvAccordion = function() {
				
		// Add event listner to the accordion div buttons
		this.on("click", "button", function(event) {

			event.stopPropagation(); // Stop stuff happening
			event.preventDefault(); // Totally stop stuff happening
			
			// Remove active class from all the clicked button siblings
			$(this).siblings("button").removeClass("active");

			($(this).hasClass("active"))? $(this).removeClass("active") : $(this).addClass("active");

			// Hide the active button sibling paels
			 $(this).siblings(".panel").slideUp();

			 // Show clicked button next panel
			 ($(this).hasClass("active"))? $(this).next(".panel").slideDown() : $(this).next(".panel").slideUp();
		});

			return this;
	 };
 
}( jQuery ));


