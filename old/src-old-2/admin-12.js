jQuery(document).ready(function($) {
	
	"use strict";

	var dataSheet;

	var chartType;

	var divId;

	// Google visulaization dashboard
	var dashboard;

	//  Google chart data
	 var data;

	 // Min, Max and Average dataTable
	 var minMaxAvgData;

	//  Google visulaization chart
	var iwpgvChart;

	// Google dashboard table chart
	var iwpgvTable;

	// Google visulaization number range slider control
	var numRangeFilter;

	// Google visulaization number range slider control
	var chartRangeFilter;

	// Google visulaization control (category filter)
	var categoryFilter;

	// Google visulaization control (category filter)
	var stringFilter;

	// Min Max and Average table
	var iwpgvminMaxAvgTable;

	// Google chart Options
	var chartOptions;

	// Google table chart options
	var tableChartOptions;

	// Google chart control (number range options)
	var numRangeFilterOptions;

	// Google chart control (number range options)
	var chartRangeFilterOptions;

	// Google chart control (category filter options)
	var categoryFilterOptions;

		// Google chart control (category filter options)
	var stringFilterOptions;

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

		dataSheet = response.sheet;

		chartType = response.chartType;

		divId = response.divId;

		// Set chart options
		chartOptions = response.chartOptions;

		// Set table chart options
		tableChartOptions = response.tableChartOptions;

		// Set number range filter options
		chartRangeFilterOptions = response.chartRangeFilterOptions;

		// Set number range filter options
		numRangeFilterOptions = response.numRangeFilterOptions;

		// Set category filter options
		categoryFilterOptions = response.categoryFilterOptions;

		// Set category filter options
		stringFilterOptions = response.stringFilterOptions;

		// Set Min, Max and Average table options
		minMaxAvgTableOptions = response.minMaxAvgTableOptions;


		switch (response.get.action) {
				
			//New chart
			case "addNewChart":
				
				if (! response.message || (response.message && ! response.message.includes("notice-error"))) {

					//Render chart
					renderChart();

					// Set color pickers
					setColorPickers();	

				} else {

					// Display error message
					$("#iwpgv-dashboard #adminMessages").html(response.message) ;

				}

				break;

			// An existing chart is being edited
			case "editChart":				

	
				break;

			default:
		
		}

		// Hide file select field
		$("#iwpgv-dashboard select[id='chart.fileId']").parent(".fieldWrapper").slideUp();

		// hide sheet select feed
		$("#iwpgv-dashboard select[id='chart.sheetId']").parent(".fieldWrapper").slideUp();

		// Hide chart type select field
		$("#iwpgv-dashboard select[id='chart.chartType']").parent(".fieldWrapper").slideUp();


		// Add class active to file settings button and show adjacent panel 
		$("#iwpgv-dashboard button.accordionButton.fileSettings").addClass("active").slideDown().next(".panel#fileSettings").slideDown();

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='numRangeFilter.filterColumnIndex']").append(response.numRangeFilterColumnOptions);

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='categoryFilter.filterColumnIndex']").append(response.categoryFilterColumnOptions);

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='stringFilter.filterColumnIndex']").append(response.stringFilterColumnOptions);

			// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='chartRangeFilter.filterColumnIndex']").append(response.chartRangeFilterColumnOptions);


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

		// Get all admin fields inputs
		var allInputs = $("#iwpgv-dashboard #iwpgv-admin-fields select, #iwpgv-dashboard #iwpgv-admin-fields input");

		// Loop through all inputs
		$.each(allInputs, function(i,input) {

			// Intialize input id
			var inputId = input.id;

			// Set field class
			//var inputClass = $(input).attr("class");

			// Initialize input value
			var inputValue = input.value;
			
			// Initialize series id parts
			//var parts = inputId.split(".");

			// Fetch filter option id
			var filterOptionId = fetchInputIdFromParts ( input )

			// Chart options input/select fields begaviour
			if (!! inputId && inputId.includes("chart")) {

				// Get sheet select field
				var sheetId = $("#iwpgv-dashboard #iwpgv-admin-fields select[id='chart.sheetId']");
				
				// Get vhart type select field
				var chartType = $("#iwpgv-dashboard #iwpgv-admin-fields select[id='chart.chartType']"); 

				// Toggle file select field If there are files in the options database table
				if (inputId == "chart.fileId") {
					if ($(input).find("option").length > 1) {

						// Show file select field
						$(input).parent(".fieldWrapper").slideDown().change(function() {

							if ($(this).val()) {
								// Show sheet select field
								$(sheetId).parent(".fieldWrapper").slideDown();
								
							} else  {

								// Hide sheet select field
								$(sheetId).parent(".fieldWrapper").slideUp();

								// Hide chart type select field
								$(chartType).parent(".fieldWrapper").slideUp();

							}
						});


					} else {
						
						// Hide file select field
						$(input).parent(".fieldWrapper").slideUp();

						// Hide sheet select field
						$(sheetId).parent(".fieldWrapper").slideUp();

						// Hide chart type select field
						$(chartType).parent(".fieldWrapper").slideUp();
					}
				}

				// Toggle chart type select field based on sheet select field
				if (inputId == "chart.sheetId") {

					$(input).change(function() {

						if ($(this).val()) {
							// Show chart type select field
							$(chartType).parent(".fieldWrapper").slideDown();
							
						} else  {

							// Hide chart type select field
							$(chartType).parent(".fieldWrapper").slideUp();

						}
					});

				}

				// Toggle aggregationTarget based on selectionMode, 
				if (inputId == "chart.selectionMode" || inputId == "chart.tooltip.trigger") {

					var selectionMode = $("#iwpgv-dashboard select[id='chart.selectionMode']");
					var tooltipTrigger = $("#iwpgv-dashboard select[id='chart.tooltip.trigger']");
					
					inputLoadChangeEventHandler( $("#iwpgv-dashboard select[id='chart.aggregationTarget']"), selectionMode.val() == "multiple" && tooltipTrigger.val() == "selection" );
					$(input).change(function() {
						inputLoadChangeEventHandler( $("#iwpgv-dashboard select[id='chart.aggregationTarget']"), selectionMode.val() == "multiple" && tooltipTrigger.val() == "selection" );
						
						// Set chart option and redraw
						setChartOption(filterOptionId, $(this).val());
					});

				} else

				var pointOptions = [];

				// Toggle series.i.pointShape.type, pointShape.sides, pointShape.dent, pointSize, pointShape.rotation, based on series.i.pointsVisible, 
				if (inputId.includes("series") && inputId.includes("pointsVisible")) {

					console.log(inputId);

					var seriesPointIdParts = inputId.split(".")
						var seriesPointOptionId = seriesPointIdParts[0] + "." + seriesPointIdParts[1] + "." + seriesPointIdParts[2];
						//console.log(seriesPointOptionId);

					var pointOptions = [".pointSize", ".pointShape.sides", ".pointShape.dent", ".pointShape.rotation"];
					$.each(pointOptions, function (i, option) {

						//console.log("#iwpgv-dashboard select[id='" + seriesPointOptionId + ".pointShape.type']");

						inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='" + seriesPointOptionId + option + "']"), $(input).prop("checked") );
					});
					inputLoadChangeEventHandler( $("#iwpgv-dashboard select[id='" + seriesPointOptionId + ".pointShape.type']"), $(input).prop("checked") );
					
					$(input).change(function() {
						$.each(pointOptions, function (i, option) {

							//console.log("#iwpgv-dashboard input[id='" + seriesPointOptionId + option + "']");
							inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='" + seriesPointOptionId + option + "']"), $(this).prop("checked") );
						});
						console.log("#iwpgv-dashboard select[id='" + seriesPointOptionId + ".pointShape.type']");
						inputLoadChangeEventHandler( $("#iwpgv-dashboard select[id='" + seriesPointOptionId + ".pointShape.type']"), $(this).prop("checked") );
						
						// Set chart option and redraw
						//setChartOption( filterOptionId, $(this).prop("checked") );
					});

				} else 

		

				// } else 
			
				// Toggle series.i.labelInLegend based on series.i.visibleInLegend, 
				// if (inputId.includes("visibleInLegend")) {
				// 	var labelInLegend = $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + ".labelInLegend']");

				// 	console.log(labelInLegend);
				// 	inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + ".labelInLegend']"));
				// 	$(input).change(function() {
				// 		inputLoadChangeEventHandler($(this).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + ".labelInLegend']"));
				// 		setChartOption(inputId, $(this).prop("checked"));
				// 	});
				// } else

			

				// Toggle series.i.pointShape.type, pointShape.sides, pointShape.dent, pointSize, pointShape.rotation, based on series.i.pointsVisible, 
				// if (inputId.includes("pointsVisible")) {
				// 	var pointOptions = [".pointSize", ".pointShape.sides", ".pointShape.dent", ".pointShape.rotation"];
				// 	$.each(pointOptions, function (i, option) {
				// 		inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + option + "']"));
				// 	});
				// 	inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard select[id='"+ parts[0] + "." + parts[1] + ".pointShape.type']"));
					
				// 	$(input).change(function() {
				// 		$.each(pointOptions, function (i, option) {
				// 			inputLoadChangeEventHandler($(this).prop("checked"), $("#iwpgv-dashboard input[id='"+ parts[0] + "." + parts[1] + option + "']"));
				// 		});
				// 		inputLoadChangeEventHandler($(this).prop("checked"), $("#iwpgv-dashboard select[id='"+ parts[0] + "." + parts[1] + ".pointShape.type']"));
				// 		setChartOption(inputId,  $(this).prop("checked"));
				// 	});

				// } else 

					var labelOptions = [];

				// Toggle hAxis.slantedTextAngle, hAxis.allowContainerBoundaryTextCufoff, hAxis.maxAlternation, hAxis.maxTextLines, hAxis.minTextSpacing if hAxis.slantedText is checked
				if (inputId == "hAxis.slantedText") {
					labelOptions = [".slantedTextAngle", ".allowContainerBoundaryTextCufoff", ".maxAlternation", ".maxTextLines", ".minTextSpacing"];
					$.each(labelOptions, function (i, option) {
						inputLoadChangeEventHandler($(input).prop("checked"), $("#iwpgv-dashboard input[id='hAxis" + option + "']"));
					});
					$(input).change(function() {
						$.each(labelOptions, function (i, option) {
							inputLoadChangeEventHandler($(this).prop("checked"), $("#iwpgv-dashboard input[id='hAxis" + option + "']"));
						});
						setChartOption(inputId, $(this).prop("checked"));
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

				if (!! $(input).attr("class") && $(input).attr("class").includes("hasFieldSuffix")) {
					var suffix = $(input).siblings(".fieldSuffix").html();
					//setChartOption(inputId, inputValue + suffix);
					$(input).change(function() {
						setChartOption(inputId, $(this).val() + suffix);
				  	});

				} else 

				if ( inputId.includes("ticks") || inputId.includes("lineDashStyle")) {
					$(input).change(function() {
		 				var ticks = ($(this).val())? $(this).val().split(",") : "auto" ;
		 				//console.log(ticks.length);
		 				setChartOption(inputId, ticks);
		 			});

				} else 

				if ( inputId.includes("trendlines") ) {

					if (inputId.includes("enable") ) {
						$(input).change(function() {
							if ( $(this).prop("checked")) {
								chartOptions.trendlines[parts[1]] = response.trendlinesChartOptions[parts[1]];
							} else {

								var newTrendlines = [];
								$.each(chartOptions.trendlines, function(i, element){
									if ( i != parts[1]) newTrendlines[i] = element;
								});

								chartOptions.trendlines = newTrendlines;
							}

							// Redraw chart
							iwpgvChart.draw();

			 				// console.log(chartOptions.trendlines);	
			 			});

					// } else if (inputId.includes("type")) {

					// 	if ($(input).val() != "polynomial") {
					// 		$("#iwpgv-dashboard input[id ='" + parts[0] + "." + parts[1] + ".degree']").attr("disabled", true);
					// 	} else {
					// 		$("#iwpgv-dashboard input[id ='" + parts[0] + "." + parts[1] + ".degree']").attr("disabled", false);
					// 	}

					// 	$(input).change(function() {

					// 		if ($(this).val() != "polynomial") {
					// 			$("#iwpgv-dashboard input[id ='" + parts[0] + "." + parts[1] + ".degree']").attr("disabled", true);
					// 		} else {
					// 			$("#iwpgv-dashboard input[id ='" + parts[0] + "." + parts[1] + ".degree']").attr("disabled", false);
					// 		}
				 	

					// 		setChartOption(inputId, $(this).val());

					//   	});


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
							

				} else {

					$(input).change(function() {
						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			} else {
			 				inputValue = ($(this).val())? $(this).val() : null;
			 			}

						setChartOption(inputId, inputValue);

				  	});

			  	}

			// if input has class numRangeOption 
			} else if (!! inputId && inputId.includes("numRangeFilter")) {

				// Set number range input id (number range options start with numRangeFilter which is eliminated as part[0])
				//**********var numRangeOptionId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];

				// Get all number range filter panel inputs except the enable field and filter column
		 		var nrfInputs = $("#iwpgv-dashboard .panel#numRangeFilter input, #iwpgv-dashboard .panel#numRangeFilter select").not("#iwpgv-dashboard input[id='numRangeFilter.enable'], #iwpgv-dashboard select[id='numRangeFilter.filterColumnIndex']");

		 		// get number range filter filter column
		 		var nrfColumnIndex = $("#iwpgv-dashboard select[id='numRangeFilter.filterColumnIndex']");

				// Number range filter enable checkbox behaviour
				if ( inputId == "numRangeFilter.enable") {

					// Enable filtercolumn index select field on load if checked
	 				if ($(input).prop("checked")){
	 					nrfColumnIndex.attr("disabled", false);

	 				// Disable filtercolumn index select field on load if not checked
	 				} else {
	 					nrfColumnIndex.val(null).attr("disabled", true);
	 				}

	 				// Number range filter enable checkbox behaviour on change 
					$(input).change(function() {

						// remove error message if any
						$("#iwpgv-dashboard #adminMessages").html("");

			 			// If enable checkbox is checked
			 			if ($(this).prop("checked")){

		 					// Enable filtercolumn label select field
		 					nrfColumnIndex.attr("disabled", false);

		 					// Execute number range select handler
		 					rangeFilterColumnSelectHandler (nrfColumnIndex, nrfInputs, numRangeFilterOptions);
							
						// If enable checkbox is not checked
		 				} else {

		 					// Enable filtercolumn label select field
		 					nrfColumnIndex.attr("disabled", true);

							// Remove number range slider
							$("#iwpgv-dashboard #num-range-filter-" + divId + "-div").html("");

							// Hide number range inputs
							$("#iwpgv-dashboard #numRangeFilterInputs").slideUp();

		 				}

		 				//Render chart
						renderChart();


		 			});

				// Number range Filter column index input behaviour
				} else if ( inputId == "numRangeFilter.filterColumnIndex") {

					// Execute number range filter load handler
					rangeFilterColumnLoadHandler(input, nrfInputs)

					// Number range filter column select field behaviour on change 
					$(input).change(function() {

		 				// Execute number range select handler
						rangeFilterColumnSelectHandler ($(this), nrfInputs, numRangeFilterOptions);

						//Render chart
						renderChart();

	 				});

				// behavior for all other number range filter inputs
				}  else {

					$(input).change(function() {

						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			
			 			// Other inputs behaviour
			 			} else {
			 				inputValue = $(this).val();
			 			}

						// Set option and redraw number range slider
						numRangeFilter.setOption(numRangeOptionId, inputValue);
						numRangeFilter.draw();

				  	});

				}

			// if input has class chartRangeFilter 
			} else if (!! inputId && inputId.includes("chartRange")) {

				// // Set chart range input id (chanrt range options start with chartRangeFilter wich is eliminate as part[0])
				// var filterOptionId;
				
				// switch (parts.length) {

				// 	case 4:
				// 		filterOptionId = parts[1] + "." + parts[2] + "." + parts[3];
				// 		break;

				// 	case 3:
				// 		filterOptionId = parts[1] + "." + parts[2];
				// 		break;

				// 	default:
				// 		filterOptionId = parts[1]
				// 		break;

				// }
				

				// Get all chart range filter panel inputs except the enable field and filter column
		 		var crfInputs = $("#iwpgv-dashboard .panel#chartRangeFilter input, #iwpgv-dashboard .panel#chartRangeFilter select").not("#iwpgv-dashboard input[id='chartRangeFilter.enable'], #iwpgv-dashboard select[id='chartRangeFilter.filterColumnIndex']");

		 		// get chart range filter  column
				var crfColumnIndex = $("#iwpgv-dashboard select[id='chartRangeFilter.filterColumnIndex']");

				// Chart range filter enable checkbox behaviour
				if ( inputId == "chartRangeFilter.enable") {
					
					// Enable filtercolumn index select field on load if checked
	 				if ($(input).prop("checked")){
	 					crfColumnIndex.attr("disabled", false);

	 				// Disable filtercolumn index select field on load if not checked
	 				} else {
	 					crfColumnIndex.val(null).attr("disabled", true);
	 				}
					
					// Chart range filter enable checkbox behaviour on change 
					$(input).change(function() {

						$("#iwpgv-dashboard #adminMessages").html("");

			 			if ($(this).prop("checked")){

			 				// Enable filtercolumn label select field
		 					crfColumnIndex.attr("disabled", false);

		 					// Execute number range select handler
		 					rangeFilterColumnSelectHandler (crfColumnIndex, crfInputs, chartRangeFilterOptions);

		 				} else {

		 					// Enable filtercolumn label select field
		 					crfColumnIndex.attr("disabled", true);

							// Remove chart range slider
							$("#iwpgv-dashboard #chart-range-filter-" + divId + "-div").html("");

		 				}
						
						//Render chart
						renderChart();

		 			});

				// Chart range Filter column index input behaviour
				} else if ( inputId == "chartRangeFilter.filterColumnIndex") {

					// Execute number range filter load handler
					rangeFilterColumnLoadHandler(input, crfInputs)

					// Number range filter column select field behaviour on change 
					$(input).change(function() {

		 				// Execute number range select handler
						rangeFilterColumnSelectHandler ($(this), crfInputs, chartRangeFilterOptions);

						//Render chart
						renderChart();

	 				});

				// behavior for all other inputs
				}  else {

					$(input).change(function() {

						// console.log(filterOptionId);
						// console.log(inputValue);

						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			
			 			// Other inputs behaviour
			 			} else {
			 				inputValue = $(this).val();
			 			}

						// Set option and redraw number range slider
						chartRangeFilter.setOption(filterOptionId, inputValue);
						chartRangeFilter.draw();

				  	});

				}

			// if input has class categotyFilter 
			} else if (!! inputId && inputId.includes("categoryFilter")) {

				// Set input id (number range options start with categoryFilter wich is eliminate as part[0])
				// ***********var categoryFilterOptionId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];

				// Get all number range filter panel inputs 
		 		var cfInputs = $("#iwpgv-dashboard .panel#categoryFilter input, #iwpgv-dashboard .panel#categoryFilter select").not("#iwpgv-dashboard input[id='categoryFilter.enable'], #iwpgv-dashboard select[id='categoryFilter.filterColumnIndex']");

		 		var cfColumnIndex = $("#iwpgv-dashboard select[id='categoryFilter.filterColumnIndex']");


				// Number range filter behaviour
				if ( inputId == "categoryFilter.enable") {
					
	 				if ($(input).prop("checked")){

	 					// Enable filtercolumn label select field
	 					cfColumnIndex.attr("disabled", false);

	 				} else {

	 					// Enable filtercolumn label select field
	 					cfColumnIndex.val(null).attr("disabled", true);
	 				}

 					if (cfColumnIndex.val()) {
	 				 	// Unhide all number range filter option inputs
						$.each (cfInputs, function (i, cfInput) {
							$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
						});

					} else {

						// Unhide all number range filter option inputs
						$.each (cfInputs, function (i, cfInput) {
							$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
						});
					}

		
					$(input).change(function() {

						$("#iwpgv-dashboard #adminMessages").html("");

			 			if ($(this).prop("checked")){

		 					// Enable filtercolumn label select field
		 					cfColumnIndex.attr("disabled", false);

		 				} else {

		 					// Enable filtercolumn label select field
		 					cfColumnIndex.val(null).attr("disabled", true);

		 					// Remove number range slider
							$("#iwpgv-dashboard #category-filter-" + response.divId + "-div").html("");
		 				}

	 					if (cfColumnIndex.val()) {
		 				 	// Unhide all number range filter option inputs
							$.each (cfInputs, function (i, cfInput) {
								$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
							});

						} else {

							// Unhide all number range filter option inputs
							$.each (cfInputs, function (i, cfInput) {
								$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
							});
						}

		 			});

				} else

		 		
				// Filter column label input behaviour
				if ( inputId == "categoryFilter.filterColumnIndex") {

	 				// if a filter column is selected
					if ($(input).val()) {

						// Unhide all number range filter option inputs
						$.each (cfInputs, function (i, cfInput) {
							$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
						});

						$("#iwpgv-dashboard #categoryFilterInputs").slideDown();

					 
					// If a filter column is not selected
					} else {

						// Hide all number range filter inputs except enable checkbox and filter column itself
						$.each (cfInputs, function (i, cfInput) {
							// if (cfInput.id != "categoryFilter.enable"  && cfInput.id != "categoryFilter.filterColumnIndex")
							$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideUp();
						});

						$("#iwpgv-dashboard #categoryFilterInputs").slideUp();

					}



					$(input).change(function() {

						// if a filter column is selected
						if ($(this).val()) {

							// Unhide all number range filter option inputs
							$.each (cfInputs, function (i, cfInput) {
								$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
							});

							$("#iwpgv-dashboard #categoryFilterInputs").slideDown();

						 
						// If a filter column is not selected
						} else {

							// Hide all number range filter inputs except enable checkbox and filter column itself
							$.each (cfInputs, function (i, cfInput) {
								// if (cfInput.id != "categoryFilter.enable"  && cfInput.id != "categoryFilter.filterColumnIndex")
								$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideUp();
							});

							$("#iwpgv-dashboard #categoryFilterInputs").slideUp();

						}

					
						// Set filter column label (default = "")
						categoryFilterOptions.filterColumnIndex = ($(this).val())? $(this).val() : null;

						if (categoryFilterOptions.filterColumnIndex) {

							var tableChartEnable = $("#iwpgv-dashboard input[id='tableChart.enable']");

							if ( tableChartEnable.prop("checked") ) {

								//Render chart
								renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, categoryFilterOptions, stringFilterOptions, chartRangeFilterOptions, tableChartOptions, minMaxAvgTableOptions);

							
							} else {
							
								//Render chart
								renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, categoryFilterOptions, stringFilterOptions, chartRangeFilterOptions, null, minMaxAvgTableOptions);
							}

						} else {

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

						setCategoryFilterOption(categoryFilterOptionId, inputValue);

				  	});

				}
			// if input has class categotyFilter 
			} else if (!! inputId && inputId.includes("stringFilter")) {

				// Set input id (number range options start with stringFilter wich is eliminate as part[0])
				// ******var stringFilterOptionId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];

				// Get all number range filter panel inputs 
		 		var sfInputs = $("#iwpgv-dashboard .panel#stringFilter input, #iwpgv-dashboard .panel#stringFilter select").not("#iwpgv-dashboard input[id='stringFilter.enable'], #iwpgv-dashboard select[id='stringFilter.filterColumnIndex']");

		 		var sfColumnIndex = $("#iwpgv-dashboard select[id='stringFilter.filterColumnIndex']");


				// Number range filter behaviour
				if ( inputId == "stringFilter.enable") {
					
	 				if ($(input).prop("checked")){

	 					// Enable filtercolumn label select field
	 					sfColumnIndex.attr("disabled", false);

	 				} else {

	 					// Enable filtercolumn label select field
	 					sfColumnIndex.val(null).attr("disabled", true);
	 				}

 					if (sfColumnIndex.val()) {
	 				 	// Unhide all number range filter option inputs
						$.each (sfInputs, function (i, cfInput) {
							$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
						});

					} else {

						// Unhide all number range filter option inputs
						$.each (sfInputs, function (i, cfInput) {
							$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
						});
					}

		
					$(input).change(function() {

						$("#iwpgv-dashboard #adminMessages").html("");

			 			if ($(this).prop("checked")){

		 					// Enable filtercolumn label select field
		 					sfColumnIndex.attr("disabled", false);

		 				} else {

		 					// Enable filtercolumn label select field
		 					sfColumnIndex.val(null).attr("disabled", true);

		 					// Remove number range slider
							$("#iwpgv-dashboard #string-filter-" + response.divId + "-div").html("");
		 				}

	 					if (sfColumnIndex.val()) {
		 				 	// Unhide all number range filter option inputs
							$.each (sfInputs, function (i, cfInput) {
								$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
							});

						} else {

							// Unhide all number range filter option inputs
							$.each (sfInputs, function (i, cfInput) {
								$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
							});
						}

		 			});

				} else

		 		
				// Filter column label input behaviour
				if ( inputId == "stringFilter.filterColumnIndex") {

	 				// if a filter column is selected
					if ($(input).val()) {

						// Unhide all number range filter option inputs
						$.each (sfInputs, function (i, cfInput) {
							$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
						});

						$("#iwpgv-dashboard #stringFilterInputs").slideDown();

					 
					// If a filter column is not selected
					} else {

						// Hide all number range filter inputs except enable checkbox and filter column itself
						$.each (sfInputs, function (i, cfInput) {
							// if (cfInput.id != "stringFilter.enable"  && cfInput.id != "stringFilter.filterColumnIndex")
							$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideUp();
						});

						$("#iwpgv-dashboard #stringFilterInputs").slideUp();

					}



					$(input).change(function() {

						// if a filter column is selected
						if ($(this).val()) {

							// Unhide all number range filter option inputs
							$.each (sfInputs, function (i, cfInput) {
								$(cfInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
							});

							$("#iwpgv-dashboard #stringFilterInputs").slideDown();

						 
						// If a filter column is not selected
						} else {

							// Hide all number range filter inputs except enable checkbox and filter column itself
							$.each (sfInputs, function (i, cfInput) {
								// if (cfInput.id != "stringFilter.enable"  && cfInput.id != "stringFilter.filterColumnIndex")
								$(cfInput).attr("disabled", true); //parent(".fieldWrapper").slideUp();
							});

							$("#iwpgv-dashboard #stringFilterInputs").slideUp();

						}

					
						// Set filter column label (default = "")
						stringFilterOptions.filterColumnIndex = ($(this).val())? $(this).val() : null;

						if (stringFilterOptions.filterColumnIndex) {

							var tableChartEnable = $("#iwpgv-dashboard input[id='tableChart.enable']");

							if ( tableChartEnable.prop("checked") ) {

								//Render chart
							renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, categoryFilterOptions, stringFilterOptions, chartRangeFilterOptions, tableChartOptions, minMaxAvgTableOptions);

							
							} else {
							
								//Render chart
								renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, categoryFilterOptions, stringFilterOptions, chartRangeFilterOptions, null, minMaxAvgTableOptions);
							}

						} else {

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

						setstringFilterOption(stringFilterOptionId, inputValue);

				  	});

				}

			

			//if input has class tableChartOptions 
			} else if (!! inputId && inputId.includes("tableChart")) {

				// Set input id (table chart options start with tableChart wich is eliminate as part[0])
				// *******var tableChartOptionId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];

				// Get all number range filter panel inputs 
		 		var tableChartInputs = $("#iwpgv-dashboard .panel#tableChart input, #iwpgv-dashboard .panel#tableChart select").not("#iwpgv-dashboard input[id='tableChart.enable']");

		 				// Number range filter behaviour
				if ( inputId == "tableChart.enable") {

					
	 				if ($(input).prop("checked")){

	 				 	// Unhide all number range filter option inputs
						$.each (tableChartInputs, function (i, tableChartinput) {
							$(tableChartinput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
						});

					} else {

						// Unhide all number range filter option inputs
						$.each (tableChartInputs, function (i, tableChartinput) {
							$(tableChartinput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
						});
					}

		
					$(input).change(function() {


						$("#iwpgv-dashboard #adminMessages").html("");

			 			if ($(this).prop("checked")){

		 				 	// Unhide all number range filter option inputs
							$.each (tableChartInputs, function (i, tableChartinput) {
								$(tableChartinput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
							});

						} else {

							// Unhide all number range filter option inputs
						$.each (tableChartInputs, function (i, tableChartinput) {
							$(tableChartinput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
						});
						}


						if (numRangeFilterOptions.filterColumnIndex ||  categoryFilterOptions.filterColumnIndex ) {

							//var tableChartEnable = $("#iwpgv-dashboard input[id='tableChart.enable']");

							if ( $(this).prop("checked")) {

								//Render chart
								renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, categoryFilterOptions, stringFilterOptions, chartRangeFilterOptions, tableChartOptions, minMaxAvgTableOptions);
							

							} else {

								// Remove number range slider
								$("#iwpgv-dashboard #table-chart-" + response.divId + "-div").html("");
							}
							
						}




		 			});
					
				// behavior for all other inputs
				}  else {

					$(input).change(function() {
						//console.log(tableChartOptionId);

						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			} else {
			 				inputValue = $(this).val();
			 			}

						setChartOption(tableChartOptionId, inputValue);

				  	});

				}

				//if input has class tableChartOptions 
			} else if (!! inputId && inputId.includes("minMaxAvg")) {

				// Set input id (number range options start with numRangeFilter wich is eliminate as part[0])
				// *******var minMaxAvgOptionId = (!! parts[2])? parts[1] + "." + parts[2] : parts[1];

				// Get all number range filter panel inputs 
		 		var minMaxAvgTableInputs = $("#iwpgv-dashboard .panel#minMaxAvgTable input, #iwpgv-dashboard .panel#minMaxAvgTable select").not("#iwpgv-dashboard input[id='minMaxAvgTable.enable']");

		 				// Number range filter behaviour
				if ( inputId == "minMaxAvgTable.enable") {

					
	 				if ($(input).prop("checked")){

	 				 	// Unhide all number range filter option inputs
						$.each (minMaxAvgTableInputs, function (i, minMaxAvgTabletinput) {
							$(minMaxAvgTabletinput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
						});

					} else {

						// Unhide all number range filter option inputs
						$.each (minMaxAvgTableInputs, function (i, minMaxAvgTabletinput) {
							$(minMaxAvgTabletinput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
						});
					}

		
					$(input).change(function() {


						$("#iwpgv-dashboard #adminMessages").html("");

			 			if ($(this).prop("checked")){

		 				 	// Unhide all number range filter option inputs
							$.each (minMaxAvgTableInputs, function (i, minMaxAvgTabletinput) {
								$(minMaxAvgTabletinput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
							});

						} else {

							// Unhide all number range filter option inputs
							$.each (minMaxAvgTableInputs, function (i, minMaxAvgTabletinput) {
								$(minMaxAvgTabletinput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
							});
						}

						if ($(this).prop("checked")){

							//Render chart
							renderChart(response.sheet, response.divId, response.chartType, chartOptions, numRangeFilterOptions, categoryFilterOptions, stringFilterOptions, chartRangeFilterOptions, tableChartOptions, minMaxAvgTableOptions);
							
						} else {

								// Remove number range slider
								$("#iwpgv-dashboard #min-max-avg-" + response.divId + "-div").html("");
							
						}




		 			});
					
				// behavior for all other inputs
				}  else {

					$(input).change(function() {
						//console.log(tableChartOptionId);

						// checkbox behviour
			  			if ($(input).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			} else {
			 				inputValue = $(this).val();
			 			}

						setMinMaxAvgtableOption(minMaxAvgOptionId, inputValue);

				  	});

				}

			}

		});

		
	
	}


	function inputLoadChangeEventHandler(chartOptionInput, chartOptionCondition) {
		if (chartOptionCondition ){
			$(chartOptionInput).attr("disabled", false); 
		} else {
			$(chartOptionInput).attr("disabled", true); 
		}
	}

	



	function setChartOption(chartOptionId, chartOptionValue) {
		console.log("Setting chart option");
		console.log(chartOptionId);
		console.log(chartOptionValue);
		
		// Set chart option and redraw
		iwpgvChart.setOption(chartOptionId, chartOptionValue);
		iwpgvChart.draw();
	}





	// function setNumRangeFilterOption(inputId, inputValue) {
	// 	// console.log("range slider redwraw");
	// 	// console.log(inputId);
	// 	// console.log(inputValue);
		
	// 	// Set option
	// 	if ( numRangeFilter) {
	// 		numRangeFilter.setOption(inputId, inputValue);
		
	// 		// Redraw chart
	// 		numRangeFilter.draw();
	// 	}
	// }




	function setCategoryFilterOption(inputId, inputValue) {
		// console.log("range slider redwraw");
		// console.log(inputId);
		// console.log(inputValue);
		
		// Set option
		if ( categoryFilter) {
			categoryFilter.setOption(inputId, inputValue);
		
			// Redraw chart
			categoryFilter.draw();
		}
	}


	function setStringFilterOption(inputId, inputValue) {
		// console.log("range slider redwraw");
		// console.log(inputId);
		// console.log(inputValue);
		
		// Set option
		if ( stringFilter) {
			stringFilter.setOption(inputId, inputValue);
		
			// Redraw chart
			stringFilter.draw();
		}
	}





	function settableChartOption(inputId, inputValue) {
		// console.log("table Chart option");
		// console.log(inputId);
		// console.log(inputValue);
		
		// Set option
		if ( iwpgvTable) {
			iwpgvTable.setOption(inputId, inputValue);
		
			// Redraw chart
			iwpgvTable.draw();
		}
	}


	function setMinMaxAvgtableOption(inputId, inputValue) {
		// console.log("table Chart option");
		// console.log(inputId);
		// console.log(inputValue);
		
		// Set option
		if ( iwpgvTable) {
			iwpgvminMaxAvgTable.setOption(inputId, inputValue);
		
			// Redraw chart
			iwpgvminMaxAvgTable.draw();
		}
	}








	function renderChart() {
		
		// console.log(numRangeFilterOptions);
		
		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawVisualization);
		
		function drawVisualization() {

			// Get datatable
			data = getDatatable(dataSheet);

			//Create dashboard if number range filter is enabled and a filter column index is selected 
			var numRangeFilterEnable = $("#iwpgv-dashboard input[id='numRangeFilter.enable']");

			var chartRangeFilterEnable = $("#iwpgv-dashboard input[id='chartRangeFilter.enable']");

			var categoryFilterEnable = $("#iwpgv-dashboard input[id='categoryFilter.enable']");

			var stringFilterEnable = $("#iwpgv-dashboard input[id='stringFilter.enable']");

			if ( (numRangeFilterEnable.prop("checked") && numRangeFilterOptions.filterColumnIndex ) || (categoryFilterEnable.prop("checked") && categoryFilterOptions.filterColumnIndex) || (chartRangeFilterEnable.prop("checked") && chartRangeFilterOptions.filterColumnIndex) || (stringFilterEnable.prop("checked") && null !== stringFilterOptions.filterColumnIndex)) {

				// console.log("HGHGHHG____++++++");

				// Instantiate a dashboard object.
				dashboard = new google.visualization.Dashboard(document.getElementById('dashboard-'+divId+'-div'));

					//Create a table chart if table chart is enabled
				if ($("#iwpgv-dashboard input[id='numRangeFilter.enable']").prop("checked") && null !== numRangeFilterOptions.filterColumnIndex) {

					// Create a range slider, passing some options
					numRangeFilter = new google.visualization.ControlWrapper({
						controlType: 'NumberRangeFilter',
						containerId: 'num-range-filter-'+divId+'-div',
						options: numRangeFilterOptions
					});

					// Add dashboard ready handler
					google.visualization.events.addListener(numRangeFilter, 'statechange', numRangeFilterStateChangeHandler );

				}

				//Create a table chart if table chart is enabled
				if ($("#iwpgv-dashboard input[id='chartRangeFilter.enable']").prop("checked") && chartRangeFilterOptions.filterColumnIndex)  {

					// Create a range slider, passing some options
					chartRangeFilter = new google.visualization.ControlWrapper({
						controlType: 'ChartRangeFilter',
						containerId: 'chart-range-filter-'+divId+'-div',
						options: chartRangeFilterOptions
					});

					// Add dashboard ready handler
					google.visualization.events.addListener(chartRangeFilter, 'statechange', chartRangeFilterStateChangeHandler );


				}

				// Add dashboard ready handler
				//google.visualization.events.addListener(numRangeFilter, 'ready', numRangeFilterReadyHandler );

				
				//Create a table chart if table chart is enabled
				if ($("#iwpgv-dashboard input[id='categoryFilter.enable']").prop("checked") && categoryFilterOptions.filterColumnIndex)  {

					// Create a range slider, passing some options
					categoryFilter = new google.visualization.ControlWrapper({
						controlType: 'CategoryFilter',
						containerId: 'category-filter-'+divId+'-div',
						options: categoryFilterOptions
					});

				}


				//Create a table chart if table chart is enabled
				if ($("#iwpgv-dashboard input[id='stringFilter.enable']").prop("checked") && null !== stringFilterOptions.filterColumnIndex)  {

					// console.log("string Filter");

					// Create a range slider, passing some options
					stringFilter = new google.visualization.ControlWrapper({
						controlType: 'StringFilter',
						containerId: 'string-filter-'+divId+'-div',
						options: stringFilterOptions
					});

				}


				// Create chart
				iwpgvChart = new google.visualization.ChartWrapper({
					chartType: chartType,
					containerId: 'chart-'+divId+'-div',
					options: chartOptions
				
				});

				
				//Create a table chart if table chart is enabled
				if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
					iwpgvTable = new google.visualization.ChartWrapper({
						chartType: 'Table',
						containerId: 'table-chart-'+divId+'-div',
						options: {}
					});
				} else {
					// Remove table chart 
					$("#iwpgv-dashboard #table-chart-" + divId + "-div").html("");
				}



				if ($("#iwpgv-dashboard input[id='numRangeFilter.enable']").prop("checked") && numRangeFilterOptions.filterColumnIndex) {

					// Establish dependencies between chart and number range filter
					dashboard.bind(numRangeFilter, iwpgvChart); 

					//Create a table chart if table chart is enabled
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						// Establish dependencies between chart and number range filter
						dashboard.bind(numRangeFilter, iwpgvTable); 
					}

				}

				if ($("#iwpgv-dashboard input[id='chartRangeFilter.enable']").prop("checked") && chartRangeFilterOptions.filterColumnIndex) {

					// Establish dependencies between chart and number range filter
					dashboard.bind(chartRangeFilter, iwpgvChart); 

					//Create a table chart if table chart is enabled
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						// Establish dependencies between chart and number range filter
						dashboard.bind(chartRangeFilter, iwpgvTable); 
					}

				}


				if ($("#iwpgv-dashboard input[id='categoryFilter.enable']").prop("checked") && categoryFilterOptions.filterColumnIndex)  {

					// Establish dependencies between chart and number range filter
					dashboard.bind(categoryFilter, iwpgvChart); 

						//Create a table chart if table chart is enabled
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						// Establish dependencies between chart and number range filter
						dashboard.bind(categoryFilter, iwpgvTable); 
					}

						// Establish dependencies between chart and number range filter
					//dashboard.bind(categoryFilter, iwpgvTable); 

				}

				if ($("#iwpgv-dashboard input[id='stringFilter.enable']").prop("checked") && stringFilterOptions.filterColumnIndex)  {

					// Establish dependencies between chart and number range filter
					dashboard.bind(stringFilter, iwpgvChart); 

						//Create a table chart if table chart is enabled
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						// Establish dependencies between chart and number range filter
						dashboard.bind(stringFilter, iwpgvTable); 
					}

						// Establish dependencies between chart and number range filter
					//dashboard.bind(stringFilter, iwpgvTable); 

				}

				// console.log("HGHGHHG____++++++");

				// Draw the dashboard.
				dashboard.draw(data);

				// Add one time event listener
				google.visualization.events.addOneTimeListener(dashboard, 'ready', oneTimeDashboardReadyHandler );

				// Add dashboard ready handler
				google.visualization.events.addListener(dashboard, 'ready', dashboardReadyHandler );

				// Add dashboard error handler
				google.visualization.events.addListener(dashboard, 'error', dashboardErrorHandler );


				// Draw Min, Max, Avergae table if it is enabled
				if ($("#iwpgv-dashboard input[id='minMaxAvgTable.enable']").prop("checked")) {

					minMaxAvgData = new google.visualization.DataTable();

					// Declare columns
					minMaxAvgData.addColumn('string', 'Label');
					minMaxAvgData.addColumn('number', 'Min');
					minMaxAvgData.addColumn('number', 'Average');
					minMaxAvgData.addColumn('number', 'Max');

					minMaxAvgData.addRows(getMinMaxAvgTableRows(data, []));

					iwpgvminMaxAvgTable = new google.visualization.ChartWrapper({
						dataTable: minMaxAvgData,
						chartType: 'Table',
						containerId: 'min-max-avg-'+divId+'-div',
						options: minMaxAvgTableOptions
					});

					iwpgvminMaxAvgTable.draw();

					// Add chart error handler
					//google.visualization.events.addListener(iwpgvminMaxAvgTable, 'ready', iwpgvminMaxAvgTableReadyHandler );

				} else {

					// Remove min, max, avg table
					$("#iwpgv-dashboard #min-max-avg-" + divId + "-div").html("");
				}

			
			// If number range filter is not enabled or a filter column index is not selected 
			}  else {

				// Create chart, passing data
				iwpgvChart = new google.visualization.ChartWrapper({
					dataTable: data,
					chartType: chartType,
					containerId: 'chart-'+divId+'-div',
					options: chartOptions
				
				});

				// Draw chart
				iwpgvChart.draw();

			}

			// Add one time event listener
			google.visualization.events.addOneTimeListener(iwpgvChart, 'ready', oneTimeChartReadyHandler );

			// Add chart error handler
			google.visualization.events.addListener(iwpgvChart, 'error', chartErrorHandler );

		}

	} // END function renderChart(data, divId





	function getMinMaxAvg(data, filteredRows) {

		// Intialize min, max, avg object
		var minMaxAvg = {};

		// Initialize columns index (used to track columns) 
		var colIndex = 0;

		// Initialize the number of non null columns (empty columns are not counted in the computation of avergaes)
		var nonNullRowCount = [];

		var colSum = [];

		// Loop through all columns	
		do {

			// Consider only columns with data type number
			if (data.getColumnRole(colIndex) == "data") { // && data.getColumnType(colIndex) == "number") {

				// Initialize the minmaxavg object for each column
				minMaxAvg[colIndex] = {};

				// Add column label
				minMaxAvg[colIndex].label = data.getColumnLabel(colIndex);

				// Add coumn Min
				minMaxAvg[colIndex].min = data.getColumnRange(colIndex).min;

				// Initialize rows index (used to track rows) 
				var rowIndex = 0;

				// Initialize the sum of all rows for each column
				colSum[colIndex] = 0;

				// Initialize the non null count for each column
				nonNullRowCount[colIndex] = 0;

				// Loop through all rows
				do {

					// Add cell values to the sum and update the non null row count if the cell is not empty
					if (data.getValue(rowIndex, colIndex) && filteredRows.includes(rowIndex)) {
						colSum[colIndex] += data.getValue(rowIndex, colIndex);
						nonNullRowCount[colIndex]++;
					}						

					rowIndex++;

				} while (rowIndex < data.getNumberOfRows());


				// Compute the average for each column
				minMaxAvg[colIndex].avg = colSum[colIndex]/nonNullRowCount[colIndex];

				// Add column average
				minMaxAvg[colIndex].max = data.getColumnRange(colIndex).max;


			}


			colIndex++;


		} while (colIndex < data.getNumberOfColumns());

		// return aray(min, max, average)
		return minMaxAvg;

	}





	function getMinMaxAvgTableRows (data, filteredRows) {
		// Initialize tadatable rows
		var rows = [];
		
		// Loop through all the Min, Max, Avg data to populate the minMaxAvg datatable rows 
		$.each(getMinMaxAvg(data, filteredRows), function(rowIndex, row) {

			// Initialize a new row
			var newRow = [];
			
			// Loop through the columns to populate the rows
			$.each(row, function (colIndex, cell) {
				newRow.push(cell);
			});
			rows.push(newRow);

		});

		// Return all rows
		return rows;
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




	function oneTimeDashboardReadyHandler() {

			if ( numRangeFilter) {

				var lowValue = numRangeFilter.getState().lowValue.toPrecision(4);
				var highValue = numRangeFilter.getState().highValue.toPrecision(4);

				// Update Number range slider low and high option values
				$("#iwpgv-dashboard input[id='numRangeFilter.minValue']").val(lowValue);
				$("#iwpgv-dashboard input[id='numRangeFilter.maxValue']").val(highValue);

				// Set the Number range filter inputs and bind change events
				$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMin").val(lowValue).change(function(){
					numRangeFilter.setState({"lowValue": $(this).val(), "highValue": highValue});
					numRangeFilter.draw();

					// Set the Number range filter inputs
					if ( chartRangeFilter) {
						$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeStart").val(lowValue);
						$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeEnd").val(highValue);

						// Set number range filter values and redraw
						chartRangeFilter.setState({"range": {"start": lowValue, "end": highValue}});
						chartRangeFilter.draw();
					}

				});

				// Set the Number range filter inputs and bind change events
				$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMax").val(highValue).change(function(){
					numRangeFilter.setState({"highValue": $(this).val(), "lowValue": lowValue});
					numRangeFilter.draw();

					// Set the Number range filter inputs
					if ( chartRangeFilter) {
						$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeStart").val(lowValue);
						$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeEnd").val(highValue);

						// Set number range filter values and redraw
						chartRangeFilter.setState({"range": {"start": lowValue, "end": highValue}});
						chartRangeFilter.draw();
					}
				});

			}

			if ( chartRangeFilter) {

				var lowValue = chartRangeFilter.getState().range.start.toPrecision(4);
				var highValue = chartRangeFilter.getState().range.end.toPrecision(4);

				// Set the Number range filter inputs and bind change events
				$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeStart").val(lowValue).change(function(){
					chartRangeFilter.setState( {"range": {"start":  $(this).val(), "end": highValue }});
					chartRangeFilter.draw();

					// Set the Number range filter inputs
					if ( numRangeFilter) {
						$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMin").val(lowValue);
						$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMax").val(highValue);
					}

					// Set number range filter values and redraw
					numRangeFilter.setState({"lowValue": lowValue, "highValue": highValue});
					numRangeFilter.draw();

				});

				// Set the Number range filter inputs and bind change events
				$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeEnd").val(highValue).change(function(){
					chartRangeFilter.setState( {"range": {"start": lowValue, "end":  $(this).val() }});
					chartRangeFilter.draw();

					// Set the Number range filter inputs
					if ( numRangeFilter) {
						$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMin").val(lowValue);
						$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMax").val(highValue);
					}

					// Set number range filter values and redraw
					numRangeFilter.setState({"lowValue": lowValue, "highValue": highValue});
					numRangeFilter.draw();

				});

				
			}

	}







	function dashboardReadyHandler() {

		if (! numRangeFilter) return;

		// Get Number Range Filter high and low values; and filter nthe data accordingly
		var filteredRows = data.getFilteredRows([{
			column: parseInt(numRangeFilterOptions.filterColumnIndex),
			minValue: numRangeFilter.getState().lowValue,
			maxValue: numRangeFilter.getState().highValue
		}]);


		// Update the Min, Max, Average datatable (if it has been created) and redraw its table
		if (!! minMaxAvgData) {

			// Remove all rows
			minMaxAvgData.removeRows(0, minMaxAvgData.getNumberOfRows());

			// Add new rows based on the new min, max and avg data
			minMaxAvgData.addRows(getMinMaxAvgTableRows (data, filteredRows));

			// redrwa the table
			iwpgvminMaxAvgTable.draw();

		}
		

	}







	function dashboardErrorHandler(error) {

		//console.log(error);

		// Display error message
		$("#iwpgv-dashboard #gvErrorMessages").append("<div class='notice notice-error is-dismissible'><p>" + error.message + "</p></div>");
		console.log(error);

		// Remove google default error
		google.visualization.errors.removeError(error.id);

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
		$("#iwpgv-dashboard #gvErrorMessages").append("<div class='notice notice-error is-dismissible'><p>" + error.message + "</p></div>");
		console.log(error);

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}



	function numRangeFilterStateChangeHandler() {

		var lowValue = numRangeFilter.getState().lowValue.toPrecision(4);
		var highValue = numRangeFilter.getState().highValue.toPrecision(4);

		// Set the Number range filter inputs
		$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMin").val(lowValue);
		$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMax").val(highValue);

		if (chartRangeFilter) {

			// Set the Number range filter inputs
			$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeStart").val(lowValue);
			$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeEnd").val(highValue);
			
			// Set number range filter values and redraw
			chartRangeFilter.setState({"range": {"start": lowValue, "end": highValue}});
			chartRangeFilter.draw();
		}

		


		
		
		// Update Number range slider low value
		//$("#iwpgv-dashboard input[id='numRangeFilter.minValue']").val(numRangeFilter.getState().lowValue);
		
		// Update Number range slider high value
		//$("#iwpgv-dashboard input[id='numRangeFilter.maxValue']").val(numRangeFilter.getState().highValue);

	}




	function chartRangeFilterStateChangeHandler() {

		var lowValue = chartRangeFilter.getState().range.start.toPrecision(4);
		var highValue = chartRangeFilter.getState().range.end.toPrecision(4);

		// Set the chart range filter inputs
		$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeStart").val(lowValue);
		$("#iwpgv-dashboard #chartRangeFilterInputs input#inputRangeEnd").val(highValue);

		if (numRangeFilter) {

			// Set the Number range filter inputs
			$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMin").val(lowValue);
			$("#iwpgv-dashboard #numRangeFilterInputs input#inputRangeMax").val(highValue);
			
			// Set number range filter values and redraw
			numRangeFilter.setState({"lowValue": lowValue, "highValue": highValue});
			numRangeFilter.draw();
		}

	}





	function rangeFilterColumnSelectHandler (input, filterInputs, filterOptions){

		// If filter column selected has a value
		if ($(input).val()) {

			 // Enable  all number range filter option inputs
			$.each (filterInputs, function (i, filterInput) {
				$(filterInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
			});

			// Set filter column label (default = null)
			filterOptions.filterColumnIndex = $(input).val();

			// Show number range inputs
			if ($(input).attr("id") == "numRangeFilter.filterColumnIndex")
				$("#iwpgv-dashboard #numRangeFilterInputs").slideDown();

			// Show chart range inputs
			if ($(input).attr("id") == "chartRangeFilter.filterColumnIndex")
				$("#iwpgv-dashboard #chartRangeFilterInputs").slideDown();

		// If enable checkbox is not checked
		} else {
			
			// Disable  all number range filter option inputs
			$.each (filterInputs, function (i, filterInput) {
				$(filterInput).attr("disabled", true); //parent(".fieldWrapper").slideDown();
			});

			// Set filter column label (default = null)
			filterOptions.filterColumnIndex = null;

			// Remove range filter
			switch($(input).attr("id")) {

				case "numRangeFilter.filterColumnIndex":
					$("#iwpgv-dashboard #num-range-filter-" + divId + "-div").html("");
					break;

					case "chartRangeFilter.filterColumnIndex":
						$("#iwpgv-dashboard #chart-range-filter-" + divId + "-div").html("");
						break;

			}

			// Hide number range inputs
			if ($(input).attr("id") == "numRangeFilter.filterColumnIndex")
				$("#iwpgv-dashboard #numRangeFilterInputs").slideUp();

			// Hide chart range inputs
			if ($(input).attr("id") == "chartRangeFilter.filterColumnIndex")
				$("#iwpgv-dashboard #chartRangeFilterInputs").slideUp();

		}
	}




	function rangeFilterColumnLoadHandler(input, filterInputs) {

		// console.log("Check");
		// console.log(input);

		// if a filter column is selected
		if ($(input).val()) {

			// Unhide all number range filter option inputs
			$.each (filterInputs, function (i, filterInput) {
				$(filterInput).attr("disabled", false); //parent(".fieldWrapper").slideDown();
			});

			// Show number range inputs
			if ($(input).attr("id") == "numRangeFilter.filterColumnIndex")
				$("#iwpgv-dashboard #numRangeFilterInputs").slideDown();
		 
		// If a filter column is not selected
		} else {

			// Hide all number range filter inputs except enable checkbox and filter column itself
			$.each (filterInputs, function (i, filterInput) {
				$(filterInput).attr("disabled", true); //parent(".fieldWrapper").slideUp();
			});

			// Hide number range inputs
			if ($(input).attr("id") == "numRangeFilter.filterColumnIndex")
			$("#iwpgv-dashboard #numRangeFilterInputs").slideUp();

		}
	}

	function fetchInputIdFromParts ( input ) {

		if ( ! $(input).attr("id") ) return;

		if (! $(input).attr("id").includes(".") ) return $(input).attr("id");

		// Initialize series id parts
		var parts = $(input).attr("id").split(".");
		//console.log(parts);

		//if (!! $(input).attr("class") && ($(input).attr("class").includes("numRangeOption") || $(input).attr("class").includes("chartRangeOption") || $(input).attr("class").includes("categoryFilterOption") || $(input).attr("class").includes("stringFilterOption") )) {
		
			// Set chart range input id (chanrt range options start with chartRangeFilter wich is eliminate as part[0])
			var filterOptionId;
			
			switch (parts.length) {

				case 6:
					filterOptionId = parts[1] + "." + parts[2] + "." + parts[3] + "." + parts[4] + "." + parts[5];
					break;

				case 5:
					filterOptionId = parts[1] + "." + parts[2] + "." + parts[3] + "." + parts[4];
					break;

				case 4:
					filterOptionId = parts[1] + "." + parts[2] + "." + parts[3];
					break;

				case 3:
					filterOptionId = parts[1] + "." + parts[2];
					break;

				case 2:
					filterOptionId = parts[1];
					break;

				default:
					"This shouldn't have happened";
					break;
			}

			return filterOptionId;

		//}

		//return $(input).attr("id");
	}









	function setColorPickers () {
	
	 	// Setup WP Core color picker
		$("#iwpgv-dashboard input.color-picker").each(function(){
			$(this).wpColorPicker({

				change: function(event, ui){

					var filterOptionId = fetchInputIdFromParts ( $(this));

					// console.log(filterOptionId);

					// Set appropriate chart/control/table option and redraw 
					if ($(this).attr("id").includes("chartRangeFilter")) {
			 			chartRangeFilter.setOption(filterOptionId, ui.color.toString() );
			  			chartRangeFilter.draw();
					} else {
						// set chart option	and redraw
			 			iwpgvChart.setOption(filterOptionId, ui.color.toString() );
			  			iwpgvChart.draw();
			 		}
			  		
			  		
				},

				clear: function() {


					var field = $(this).parent().find(".wp-color-picker");

					var filterOptionId = fetchInputIdFromParts ( field);

					// Set appropriate chart/control/table option and redraw 
					if (field.attr("id").includes("chartRangeFilter")) {
			 			chartRangeFilter.setOption(filterOptionId, "transparent" );
			  			chartRangeFilter.draw();
					} else {
						// set chart option	and redraw
			 			iwpgvChart.setOption(filterOptionId, "transparent" );
			  			iwpgvChart.draw();
			 		}

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

			if ($(this).hasClass("active")) {
				$(this).removeClass("active");
			} else {
				$(this).addClass("active");
			}

			// Hide the active button sibling paels
			 $(this).siblings(".panel").slideUp();

			 // Show clicked button next panel
			 if ( $(this).hasClass("active") ) {
			 	$(this).next(".panel").slideDown();
			 } else {
			 	$(this).next(".panel").slideUp();
			 }
		});

			return this;
	 };
 
}( jQuery ));


