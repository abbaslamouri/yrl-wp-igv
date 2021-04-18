jQuery(document).ready(function($) {
	
	"use strict";

	
	// admin field form
	var formData = new FormData();

	var dataSheet;

	var fileSelect;

	var sheetSelect;

	var chartTypeSelect;

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
	var minMaxAvgTable;

	// Google chart Options
	var chartOptions;

	// Trendlines chart Options
	var trendlinesChartOptions;

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

	// Filter options inputs
	var filterInputs;

	// Filter column index
	var filterColumnIndex;

	// Number and Chart Range filter inputs
	var rangeFilterInputs;

	// filter div (div where the filter is drawn)
	var filterDiv;

	// server response
	var response;

	// user messages (error and success)
	var message;

	// Load the Visualization API and the corechart package.
	//google.charts.load('current', {'packages':['line', 'table', 'controls']});
	google.charts.load('current', {'packages':['corechart','controls']});

	// file select field
	fileSelect = $("#iwpgv-dashboard select[id='chart.fileId']");

	// If there are files in the database, show file selection field
  if (fileSelect.find("option").length > 1) {
   	fileSelect.parent(".fieldWrapper").slideDown();
  }
	
	// Show file select field
	//fileSelect.parent(".fieldWrapper").slideUp()

	// Sheet select field
	sheetSelect = $("#iwpgv-dashboard select[id='chart.sheetId']")
	
	// Show sheet select field
	sheetSelect.parent(".fieldWrapper").slideUp()

	//  Chart Type select field
	chartTypeSelect = $("#iwpgv-dashboard select[id='chart.chartType']")
	
	// Show sheet select field
	chartTypeSelect.parent(".fieldWrapper").slideUp()


	//******************************************  Upload new file   *******************************
	
	// When the file upload form is submitted
	$("input[id='fileUploadSubmit").click(function(event) {

		console.log(iwpgv_object);

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

   	// Remove existing sheetId selction options.
   	 $.each($("#iwpgv-dashboard select[id='chart.sheetId']").children("option"), function(i, obj) {
    		if (obj.value !== "") {
    			obj.remove();
    		}
    	});
		
		// Append form submit button input field
		formData.append("fileUploadSubmit", $("#iwpgv-dashboard #fileUploadSubmit").val()); 

		// Append action hook to the form
		formData.append("action", iwpgv_object.file_upload_action);
		
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.file_upload_nonce);

		//Retreive files and append to form
		var formFiles =  $("#iwpgv-dashboard  input[id='chart.fileUpload']"); 

		$.each($(formFiles), function(i, obj) {
			$.each(obj.files,function(j,file){
				formData.append('files[' + j + ']', file);
			});
		});

		// upload files
		ajaxRequest(formData);

	});




	function fileUploadRender(response) {

		// Display Messages
		$("#iwpgv-dashboard #adminMessages").append(response.message) ;

		// show file, sheetId and chart type select if no error
		if (! response.message.includes("notice-error")) {
			console.log("success")

			$("#iwpgv-dashboard input[id='chart.fileUpload']").val("");

	   	$("#iwpgv-dashboard select[id='chart.fileId']").append(response.fileIdOption).val(response.fileId).parent(".fieldWrapper").slideDown();
			$("#iwpgv-dashboard select[id='chart.sheetId']").append(response.sheetIdOptions).parent(".fieldWrapper").slideDown();
			$("#iwpgv-dashboard select[id='chart.chartType']").val("").parent(".fieldWrapper").slideUp();
			
		}
	  
	} // END  function fileUpload() {





	// 	//****************************************** Select File    *******************************
	

	//if there are files in the options database table
	// if (fileSelect.find("option").length > 1) {

	// 	// Show file select field
	// 	fileSelect.parent(".fieldWrapper").slideDown();

	// 	// File Select change
	// 	fileSelect.change(function() {

	// 		// If a file is selected
	// 		if ($(this).val()) {

	// 	   	// Remove existing sheetId selction options.
	// 	   	 $.each($(sheetSelect).children("option"), function(i, obj) {
	// 	    		if (obj.value !== "") {
	// 	    			obj.remove();
	// 	    		}
	// 	    	});

	// 	   	// append file id to form
	// 	   	formData.append("fileId",  fileSelect.val() );

	// 			// Append action hook to the form
	// 			formData.append("action", iwpgv_object.file_select_action);
			
	// 			// Append nonce to the form
	// 			formData.append("nonce", iwpgv_object.file_select_nonce);

	// 			// upload files
	// 			ajaxRequest(formData);
			
	// 		} else  {

	// 			// Hide file select field
	// 			//fileSelect.parent(".fieldWrapper").slideUp();

	// 			// Display error merrase
	// 			message = " Please select a file";
	// 			$("#iwpgv-dashboard #adminMessages").append("<div class='notice notice-error is-dismissible'><p>" + message + "</p></div>");

	// 		}
	// 	});

	// }




	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function fileSelectRender(response) {

		// Display error message
		$("#iwpgv-dashboard #adminMessages").append(response.message) ;

		sheetSelect.append(response.sheetIdOptions).parent(".fieldWrapper").slideDown();
	  
	} // END  function fileIdRender() {

	
	// sheetSelect.change(function() {

	// 	// If a sheet is selected
	// 	if ($(this).val()) {
			
	// 		// Show sheet select field
	// 		chartTypeSelect.parent(".fieldWrapper").slideDown()
	// 		chartTypeSelect.change(function() {

	// 			// If a chart Type is selected
	// 			if ($(this).val()) {

	// 				// Fetch data

	// 			} else {

	// 				// Display error merrase
	// 				message = " Please select a chart type";
	// 				$("#iwpgv-dashboard #adminMessages").append("<div class='notice notice-error is-dismissible'><p>" + message + "</p></div>");
	// 			}

	// 		});

	// 	} else {

	// 		// Hide chart type select field
	// 		chartTypeSelect.parent(".fieldWrapper").slideUp();

	// 		// Display error merrase
	// 		message = " Please select a sheet";
	// 		$("#iwpgv-dashboard #adminMessages").append("<div class='notice notice-error is-dismissible'><p>" + message + "</p></div>");

	// 	}

	// });







	//****************************************** Select file   *******************************
	
	$( "#iwpgv-dashboard select[id='chart.fileId'], #iwpgv-dashboard select[id='chart.sheetId'], #iwpgv-dashboard select[id='chart.chartType']" ).change( function(event) {

		// Reset user-notice and Google visualization error message divs
		$("#iwpgv-dashboard #adminMessages").html( "" );
		$("#iwpgv-dashboard #gvErrorMessages").html( "" );

		// Bail if not file, sheetId or chart type select
		//if ($(this).attr("id") != "fileId" && $(this).attr("id") != "sheetId" && $(this).attr("id") != "chartType") return;
		
		// If a file was selected
		if ($(this).attr("id") == "chart.fileId") {

			// If no file selected 
		   if (! $(this).val()) {
		   	
		   	// Display error merrase
				message = " Please select a file";
				$("#iwpgv-dashboard #adminMessages").append("<div class='notice notice-error is-dismissible'><p>" + message + "</p></div>");

		   	// hise sheetId and chart type selection field
		   	$(sheetSelect).parent(".fieldWrapper").slideUp();
		   	$(chartTypeSelect).parent(".fieldWrapper").slideUp();
		   
		   // If a file is selected
		  	} else {

		   	// Remove existing sheetId selction options.
		   	 $.each($(sheetSelect).children("option"), function(i, obj) {
		    		if (obj.value !== "") {
		    			obj.remove();
		    		}
		    	});

		   	// append file id to form
		   	formData.append("fileId",  fileSelect.val() );

				// Append action hook to the form
				formData.append("action", iwpgv_object.file_select_action);
			
				// Append nonce to the form
				formData.append("nonce", iwpgv_object.file_select_nonce);

				// upload files
				ajaxRequest(formData);
			}
		
		} else {

			// Hide chart type select field until a file has been selected/uploaded   
	 		if ($(this).attr("id") == "chart.sheetId") {
	 			 if ($(this).val()) {

	 			 	// Show sheet select field
	 			 	$(chartTypeSelect).parent(".fieldWrapper").slideDown();

	 			 } else {

	 			 	// Show chart Type
	 			 	$(chartTypeSelect).parent(".fieldWrapper").slideUp();
	 			 }
	 		}

	   	refresh();
		
		}

	});


	




	function refresh(){

	   // Remove user-notice html text
		$("#iwpgv-dashboard #adminMessages").html( "" );

		// Check if a file id was submitted
	  if ( ! fileSelect.val() ) {
	  		message= "<div class='notice notice-error is-dismissible'>Please select a file</div>" ;
	  		$("#iwpgv-dashboard #adminMessages").html(message);
	  		return;
	  	}
	  
	  // Check if a sheetId id was submitted
	  if ( ! sheetSelect.val() ) {
			message= "<div class='notice notice-error is-dismissible'>Please select a sheet</div>" ;
	  		$("#iwpgv-dashboard #adminMessages").html(message);	  		return;
	  	}
	  
	  // Check if a chart type id was submitted
	  if ( ! chartTypeSelect.val()) {
			message= "<div class='notice notice-error is-dismissible'>Please select a chart type</div>" ;
	  		$("#iwpgv-dashboard #adminMessages").html(message);	  		return;
	  	}
	  

	  	//var chartForm = $("#" + templateId + " form#chartForm");
	  	
	  	var chartForm = $("#iwpgv-dashboard form#chartForm");
	  	console.log(chartForm)

	  	// Gather all chart inputs
	  	$.each(chartForm[0], function(i, obj) {
	  		formData.append(obj.id, obj.value);
	  	});

	   // Append action hook to the form
		formData.append("action", iwpgv_object.refresh_chart_action);
		
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.refresh_chart_nonce);

				console.log(iwpgv_object);



		// upload files
		ajaxRequest(formData);

	   
	  
	}








	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function refreshChart(response) {

		console.log("Refresh Chart");
		//console.log(response);

		



	} // END  function fileUpload() {





	





	function ajaxRequest(formData){

		 // Show animation
		$("#iwpgv-dashboard #adminAjaxLoading").toggle();

		// Remove user-notice html text
		$("#iwpgv-dashboard #adminMessages").html( "" );

		$.ajax ({
			type: "POST",
			url: iwpgv_object.url, 
			data: formData,
			contentType: false,
			processData: false,
			//dataType: "json",
			success: function(response, status, jqXHR) {
			  response = JSON.parse(response);
				console.log(response);

				 // If request action is file upload
				if (response.post.action === iwpgv_object.file_upload_action) fileUploadRender(response);

				// If request action is file select
				if (response.post.action === iwpgv_object.file_select_action) fileSelectRender(response);

				// If request action is file select
				//if (response.post.action === iwpgv_object.sheetId_select_action) sheetIdSelectRender(response);

				 // If request action is draw chart
				if (response.post.action === iwpgv_object.refresh_chart_action) refreshChart(response);

				// If request action is file select
				if (response.post.action === iwpgv_object.chart_save_action) chartSaveRender(response);

			},

			error: function (jqXHR, response, error) {
				if (jqXHR.readyState === 4 && response === "error"){
					var output = "<div class='notice notice-error is-dismissible'>Status: " + jqXHR.status + ",   Error: PHP " + error + "</div>" ;
					$("#iwpgv-dashboard #adminMessages").html(output);
				}

			},

			complete: function(response) {

           // hide  animation
				$("#iwpgv-dashboard #adminAjaxLoading").slideUp();

        }			
				 
		});  // END $.ajax ({

	 }  // END function ajaxRequest(){
















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

		// add % suffix to chartArea.top and chartArea.left
		chartOptions.chartArea.top = chartOptions.chartArea.top + "%";
		chartOptions.chartArea.left = chartOptions.chartArea.left + "%";

		// Set chart lineDashStyle (comma seperated values converted to array)
		chartOptions.lineDash = (chartOptions.lineDash)? chartOptions.lineDash.split(",") : null;

		// Set hAxis.ticks (comma seperated values converted to array)
		chartOptions.hAxis.ticks = (chartOptions.hAxis.ticks)? chartOptions.hAxis.ticks.split(",") : null;

			// Set vAxes.n.ticks (comma seperated values converted to array)
		$.each(chartOptions.vAxes, function(i, element) {
			element.ticks  = (element.ticks)? element.ticks.split(",") : null;
		});

		// Set series lineDashStyle (comma seperated values converted to array)
		$.each(chartOptions.series, function(i, element) {
			element.lineDashStyle  = (element.lineDashStyle)? element.lineDashStyle.split(",") : null;
		});


		// Set trendlines chart options
		trendlinesChartOptions = response.trendlinesChartOptions;

		// Set table chart options
		tableChartOptions = response.tableChartOptions;

		// Set number range filter options
		chartRangeFilterOptions = response.chartRangeFilterOptions;

		// add % suffix to chartRangeFilterOptions.ui.chartOptions.chartArea top and chartArea.left
		chartRangeFilterOptions.ui.chartOptions.chartArea.top = chartRangeFilterOptions.ui.chartOptions.chartArea.top + "%";
		chartRangeFilterOptions.ui.chartOptions.chartArea.left = chartRangeFilterOptions.ui.chartOptions.chartArea.left + "%";

		// Set number range filter options
		numRangeFilterOptions = response.numRangeFilterOptions;

		// Set category filter options
		categoryFilterOptions = response.categoryFilterOptions;

		// Set category filter options
		stringFilterOptions = response.stringFilterOptions;

		// Set Min, Max and Average table options
		minMaxAvgTableOptions = response.minMaxAvgTableOptions;

		var colorPickerOptions = {
	   	
	   	// you can declare a default color here,
	    	// or in the data-default-color attribute on the input
	    	defaultColor: false,
	    	
	    	// a callback to fire whenever the color changes to a valid color
	    	change: function(event, ui) { colorPickerChangeCallback( event, ui ) },
	    	
	    	// a callback to fire when the input is emptied or an invalid color
	    	clear: function( event ) { colorPickerClearCallback( event ) },
	    	
	    	// hide the color picker controls on load
	    	true: false,
	    	
	    	// show a group of common colors beneath the square
	    	// or, supply an array of colors to customize further
	    	palettes: true

		};


		switch (response.get.action) {
				
			//New chart
			case "addNewChart":
				
				if (! response.message || (response.message && ! response.message.includes("notice-error"))) {

					//Render chart
					renderChart();

					// Set color pickers
					//setColorPickers();	

				} else {

					// Display error message
					$("#iwpgv-dashboard #adminMessages").html(response.message);

				}

				break;

			// An existing chart is being edited
			case "editChart":				

	
				break;

			default:
		
		}

		// Hide file select field
		//$("#iwpgv-dashboard select[id='chart.fileId']").parent(".fieldWrapper").slideUp();

		// hide sheet select feed
		//$("#iwpgv-dashboard select[id='chart.sheetId']").parent(".fieldWrapper").slideUp();

		// Hide chart type select field
		//$("#iwpgv-dashboard select[id='chart.chartType']").parent(".fieldWrapper").slideUp();

		// Add class active to file settings button and show adjacent panel 
		$("#iwpgv-dashboard button.accordionButton.fileSettings").addClass("active").slideDown().next(".panel#fileSettings").slideDown();

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='numRangeFilter.filterColumnIndex']").append(response.rangeFilterColumnOptions);

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='chartRangeFilter.filterColumnIndex']").append(response.rangeFilterColumnOptions);

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='categoryFilter.filterColumnIndex']").append(response.stringFilterColumnOptions);

		// Append filter column options to number range filter column select
		$("#iwpgv-dashboard select[id='stringFilter.filterColumnIndex']").append(response.stringFilterColumnOptions);

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
			var inputClass = $(input).attr("class");

			// Initialize input value
			var inputValue = input.value;
			
			// Initialize series id parts
			//var parts = inputId.split(".");

			// Fetch filter option id
			//var optionId = optionFromFieldId ( input );
		
			
			// Set color pickers
			if (!! $( input ).attr( "class" ) && $( input ).attr( "class" ).includes( "color-picker" ) ) {
				$(input).wpColorPicker (colorPickerOptions);
			}

			// Chart options input/select fields begaviour
			if (!! inputClass && inputClass.includes("chartOption") ) {

				// Get sheet select field
				var sheetId = $("#iwpgv-dashboard #iwpgv-admin-fields select[id='chart.sheetId']");
				
				// Get vhart type select field
				var chartType = $("#iwpgv-dashboard #iwpgv-admin-fields select[id='chart.chartType']");

				// Split series/trendlines options id into parts
				var seriesPointIdParts = inputId.split(".");
						
				// Select the first 3 parts (chart.series.n)
				var seriesPointOptionId = seriesPointIdParts[0] + "." + seriesPointIdParts[1] + "." + seriesPointIdParts[2];
				
				// chart point options
				var dependentFields = [];

				// Horizontal axis label text options
				var hAxisLabelOptions = [];

				// Toggle aggregationTarget based on tooltip selectionMode and trigger 
				if (inputId == "chart.selectionMode" || inputId == "chart.tooltip.trigger") {

					// Tooltip SelectionMode fields 
					var selectionMode = $("#iwpgv-dashboard select[id='chart.selectionMode']");
					
					// Tooltip trigger field
					var tooltipTrigger = $("#iwpgv-dashboard select[id='chart.tooltip.trigger']");

					// Tooltip aggregationTarget field
					var aggregationTarget = $("#iwpgv-dashboard select[id='chart.aggregationTarget']");
					
					// Toggle aggregationTarget
					inputLoadChangeEventHandler( aggregationTarget, selectionMode.val() == "multiple" && tooltipTrigger.val() == "selection" );
					
					// selectionMode or trigger change
					$(input).change(function() {
						
						// Toggle aggregationTarget
						inputLoadChangeEventHandler( aggregationTarget, selectionMode.val() == "multiple" && tooltipTrigger.val() == "selection" );
						
						// Set chart option and redraw
						setChartOption( input, $(this).val(), iwpgvChart);
					});

				// Toggle series.i.pointShape.type, pointShape.sides, pointShape.dent, pointSize, pointShape.rotation, based on series.i.pointsVisible, 
				} else if ( ( inputId.includes("series") ||  inputId.includes("trendlines") ) && inputId.includes("pointsVisible")) {
		
					// Select all the fields thatr will enabled/disabled basec on the series pointsVisible checkbox
					dependentFields = [".pointShape.type", ".pointSize", ".pointShape.rotation"];
					
					// Loop through all point options
					$.each(dependentFields, function (i, option) {

						// Toggle dependent input fields
						inputLoadChangeEventHandler( $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']"), $(input).prop("checked") );
					});
					
					// pointsVisible input change
					$(input).change(function() {

						// Set the enable/disbale condition
						var DisableCondition = $(this).prop("checked");

						// Loop through all the points inputs
						$.each(dependentFields, function (i, option) {
							
							// Toggle dependent input fields
							inputLoadChangeEventHandler( $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']"), DisableCondition );
						});
						
						// Set chart option and redraw
						setChartOption( input, $(this).prop("checked"), iwpgvChart );
					});

				//Toggle series.i.pointShape.sides, pointShape.dent based on pointShape.type 
				} else if ( ( inputId.includes("series") ||  inputId.includes("trendlines") ) && inputId.includes("pointShape.type")) {
					//console.log(inputId);
					
					// PointsVisible must be checked to enable pointShape.sides and pointShape.dent
					var pointsVisible = $("#iwpgv-dashboard [id='" + seriesPointOptionId + ".pointsVisible']");
		
					// Select all the fields that will enabled/disabled based on the series pointShape.type select
					dependentFields = [".pointShape.sides", ".pointShape.dent"];
					
					// Loop through all point options
					$.each(dependentFields, function (i, option) {

						// Toggle dependent input fields
						inputLoadChangeEventHandler( $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']"), inputValue == "star" && pointsVisible.prop("checked") );
					});
					
					// pointsVisible input change
					$(input).change(function() {

						// Set the enable/disbale condition
						var DisableCondition = $(this).val();

						// Loop through all the points inputs
						$.each(dependentFields, function (i, option) {
							
							// Toggle dependent input fields
							inputLoadChangeEventHandler( $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']"), DisableCondition == "star"  && pointsVisible.prop("checked") );
						});
						
						// Set chart option and redraw
						setChartOption( input, $(this).val(), iwpgvChart );
					});

				// Toggle chart.trendlines.i.degree based on chart.trendlines.i.type
				} else if (inputId.includes("trendlines") && inputId.includes("type") && ! inputId.includes("pointShape")) {

					// enable/Disable chart.trendlines.i.degree
					inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id ='" + seriesPointOptionId + ".degree']"), inputValue == "polynomial" );

					// type input change
					$(input).change(function() {

						// enable/Disable chart.trendlines.i.degree
						inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id ='" + seriesPointOptionId + ".degree']"), $(this).val() == "polynomial" );
			 	
						// Update chart and redraw
						setChartOption( input, $(this).val(), iwpgvChart );

				  	});
				  	
				// If input is chart.trendline.i.enable
				} else if ( inputId.includes("trendlines") && inputId.includes( "enable" ) ) {

					// Select all the fields that will enabled/disabled based on the trendlines.i.enable
					dependentFields = [".type", ".color", ".visibleInLegend", ".lineWidth", ".opacity", ".pointsVisible"];

					// Loop through all the points inputs
					$.each(dependentFields, function (i, option) {

						var field = $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']");

						if ( !! field.attr("class") && field.attr("class").includes("color-picker")) {
							if ($(input).prop("checked")) {
								field.closest(".fieldWrapper").slideDown();
							} else {
								field.closest(".fieldWrapper").slideUp();
							}
						} else {
							// Toggle dependent input fields
							inputLoadChangeEventHandler( $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']"), $(input).prop("checked") );
						}
					});

					// Enbale checkbox change
					$(input).change(function() {

						// Set the enable/disbale condition
						var DisableCondition = $(this).prop("checked");

						// Loop through all the points inputs
						$.each(dependentFields, function (i, option) {

							var field = $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']");

							if (!! field.attr("class") && field.attr("class").includes("color-picker")) {
								if ($(input).prop("checked")) {
									field.closest(".fieldWrapper").slideDown();
								} else {
									field.closest(".fieldWrapper").slideUp();
								}
							} else {
								// Toggle dependent input fields
								inputLoadChangeEventHandler( $("#iwpgv-dashboard [id='" + seriesPointOptionId + option + "']"), DisableCondition );
							}
						});
						
						// if checkbox is checked 
						if ( $(this).prop("checked")) {

							// Set trendlines.i enabled
							trendlinesChartOptions[seriesPointIdParts[2]].enable = $(this).prop("checked");

							// Add trendline.i option to chart options
							chartOptions.trendlines[seriesPointIdParts[2]] = trendlinesChartOptions[seriesPointIdParts[2]];


						} else {

							// Loop through the chartOptions.trendlines and remove all unched trendlines
							var newTrendlines = [];
							$.each(chartOptions.trendlines, function(i, element){
								if ( i != seriesPointIdParts[2]) newTrendlines[i] = element;
							});
							chartOptions.trendlines = newTrendlines;
						}

						// Redraw chart
						iwpgvChart.draw();

		 			});

		 		// Toggle series.i.labelInLegend based on series.i.visibleInLegend, 
				} else if ( (inputId.includes("series") || inputId.includes("trendlines")) && inputId.includes("visibleInLegend")) {
					
					// enable/Disable labelInLegend
					inputLoadChangeEventHandler($("#iwpgv-dashboard [id='" + seriesPointOptionId + ".labelInLegend']"), $(input).prop("checked") );

					//if (inputId.includes("trendlines")) {
						// enable/Disable showR2
						inputLoadChangeEventHandler($("#iwpgv-dashboard [id='" + seriesPointOptionId + ".showR2']"), $(input).prop("checked") );
					//}
					
					// visibleInLegend input change
					$(input).change(function() {

						// Toggle labelInLegend
						inputLoadChangeEventHandler( $("#iwpgv-dashboard [id='" + seriesPointOptionId + ".labelInLegend']"), $(this).prop("checked") );

						//if (inputId.includes("trendlines")) {
							// enable/Disable showR2
							inputLoadChangeEventHandler($("#iwpgv-dashboard [id='" + seriesPointOptionId + ".showR2']"), $(this).prop("checked") );
						//}
						
						// Set chart option
						setChartOption( input, $(this).prop("checked"), iwpgvChart );

					});

				// Toggle hAxis.slantedTextAngle, hAxis.allowContainerBoundaryTextCufoff, hAxis.maxAlternation, hAxis.maxTextLines, hAxis.minTextSpacing if hAxis.slantedText is checked
				} else if (inputId == "chart.hAxis.slantedText") {

					
					// Set horizontal axis label options
					hAxisLabelOptions = [".slantedTextAngle", ".allowContainerBoundaryTextCufoff", ".maxAlternation", ".maxTextLines", ".minTextSpacing"];
					
					// loop through axis label options
					$.each(hAxisLabelOptions, function (i, option) {
						
						// Toggle axis label option
						inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), $(input).prop("checked") );
					});
					
					// hAxis slantedText input change
					$(input).change(function() {

						// Set the enable/disbale condition
						var DisableCondition = $(this).prop("checked");
						
						// Loop through options
						$.each(hAxisLabelOptions, function (i, option) {
							
							// Toggle axis label option
							inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), DisableCondition );
						});

						// Set chart option
						setChartOption( input, DisableCondition, iwpgvChart );

					});

				// toggle hAxis.slantedText, hAxis.slantedTextAngle, hAxis.allowContainerBoundaryTextCufoff, hAxis.maxAlternation, hAxis.maxTextLines, hAxis.minTextSpacing based on hAxis.textPosition
				} else if (inputId == "chart.hAxis.textPosition" ) {
					
					// Set horizontal axis label options
					hAxisLabelOptions = [".slantedTextAngle", ".allowContainerBoundaryTextCufoff", ".maxAlternation", ".maxTextLines", ".minTextSpacing"];

					// Get slantedText input
					var slantedText = $("#iwpgv-dashboard input[id='chart.hAxis.slantedText']");

					// If label text position is outside chart
					if (inputValue != "out") {

						// Toggle slantedText input disabled
						inputLoadChangeEventHandler( slantedText, false );

						// Loop through label options
						$.each(hAxisLabelOptions, function (i, option) {
							
							// Toggle axis label option disabled
							inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), false );
						});
						
					// If label text position is other than outside
					} else {

						// Toggle slantedText input Enabled
						inputLoadChangeEventHandler( slantedText, true );

						// If slantedText is checked
						if (slantedText.prop("checked")) {

							// Loop through label options
							$.each(hAxisLabelOptions, function (i, option) {
							
								// Toggle axis label option enabled
								inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), true );
							});

						// If slantedText is not checked
						} else {

							// Loop through label options
							$.each(hAxisLabelOptions, function (i, option) {
							
								// Toggle axis label option enabled
								inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), false );
							});

						}
						
					}
						
					// hAxis textPosition input change
					$(input).change(function() {

						// If label text position is outside chart
						if ($(this).val() != "out") {

							// Toggle slantedText input disabled
							inputLoadChangeEventHandler( slantedText, false );

							// Loop through label options
							$.each(hAxisLabelOptions, function (i, option) {
								
								// Toggle axis label option disabled
								inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), false );
							});
							
						// If label text position is other than outside
						} else {

							// Toggle slantedText input Enabled
							inputLoadChangeEventHandler( slantedText, true );

							// If slantedText is checked
							if (slantedText.prop("checked")) {

								// Loop through label options
								$.each(hAxisLabelOptions, function (i, option) {
								
									// Toggle axis label option enabled
									inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), true );
								});

							// If slantedText is not checked
							} else {

								// Loop through label options
								$.each(hAxisLabelOptions, function (i, option) {
								
									// Toggle axis label option enabled
									inputLoadChangeEventHandler( $("#iwpgv-dashboard input[id='chart.hAxis" + option + "']"), false );
								});

							}
							
						}

							// Set chart option
						setChartOption( input, $(this).val(), iwpgvChart );

					});

				// If input class includes hasSuffix
				} else if (!! $(input).attr("class") && $(input).attr("class").includes("hasFieldSuffix")) {
					
					// input change
					$(input).change(function() {

						// set chart option
						setChartOption( input, $(this).val() + $(input).siblings(".fieldSuffix").html(), iwpgvChart );

				  	});

				// If input id linedash
				} else if ( inputId.includes("ticks") || inputId.includes("lineDashStyle")) {

					// input change
					$(input).change(function() {

		 				// Set chart option
		 				setChartOption( input, ($(this).val())? $(this).val().split(",") : null, iwpgvChart );

		 			});


				// If input id linedash
				} else if ( inputId == "chart.pointShape")  {

					// input change
					$(input).change(function() {

						// Set series lineDashStyle (comma seperated values converted to array)
						$.each(chartOptions.series, function(i, element) {
							//element.pointShape  = {};
						});

		 				// Set chart option
		 				setChartOption( input, ($(this).val()), iwpgvChart );

		 			});
			

				} else {

					$(input).change(function() {
						
						// checkbox behviour
			  			if ($(this).attr("type") == "checkbox") {
			  				inputValue = $(this).prop("checked");
			 			
			  			// All other input
			 			} else {
			 				inputValue = ($(this).val())? $(this).val() : null;
			 			}

						setChartOption(input, inputValue, iwpgvChart);


				  	});

			  	}

			// if inputId has numRangeOption 
			} else if (!! inputClass && inputClass.includes("numRangeOption")) {


				// Get all number range filter panel inputs except the enable field and filter column
		 		var nrfInputs = $("#iwpgv-dashboard .panel#numRangeFilter input, #iwpgv-dashboard .panel#numRangeFilter select").not("#iwpgv-dashboard input[id='numRangeFilter.enable'], #iwpgv-dashboard select[id='numRangeFilter.filterColumnIndex']");

		 		// Set number range filter column
		 		var nrfColumnIndex = $("#iwpgv-dashboard select[id='numRangeFilter.filterColumnIndex']");

		 		// Set range filter high and low value inputs
				var nrfHighLowInputs = $("#iwpgv-dashboard #nrfHighLowInputs");

				// Number range filter div (div where the filter is drawn)
				var nrfDiv = $("#iwpgv-dashboard #num-range-filter-" + divId + "-div");

				// Call input load and select handler
				filterInputHandler ( input, "numRangeFilter", numRangeFilter, nrfInputs, nrfColumnIndex, numRangeFilterOptions, nrfDiv, nrfHighLowInputs );


			// if inputId has chartRangeOption 
			} else if (!! inputClass && inputClass.includes("chartRangeOption")) {

				// Get all chart range filter panel inputs except the enable field and filter column
		 		var crfInputs = $("#iwpgv-dashboard .panel#chartRangeFilter input, #iwpgv-dashboard .panel#chartRangeFilter select").not("#iwpgv-dashboard input[id='chartRangeFilter.enable'], #iwpgv-dashboard select[id='chartRangeFilter.filterColumnIndex']");

		 		// get chart range filter filter column
		 		var crfColumnIndex = $("#iwpgv-dashboard select[id='chartRangeFilter.filterColumnIndex']");

		 		// Set range filter inputs
				var crfHighLowInputs = $("#iwpgv-dashboard #crfStartEndInputs");

				// Chart range filter div (div where the filter is drawn)
				var crfDiv = $("#iwpgv-dashboard #num-range-filter-" + divId + "-div");

				// Call input load and select handler
				filterInputHandler ( input, "chartRangeFilter", chartRangeFilter, crfInputs, crfColumnIndex, chartRangeFilterOptions, crfDiv, crfHighLowInputs );

			// if input has class categotyFilter 
			} else if (!! inputClass && inputClass.includes("categoryFilterOption")) {

				// Get all chart range filter panel inputs except the enable field and filter column
		 		var cfInputs = $("#iwpgv-dashboard .panel#categoryFilter input, #iwpgv-dashboard .panel#categoryFilter select").not("#iwpgv-dashboard input[id='categoryFilter.enable'], #iwpgv-dashboard select[id='categoryFilter.filterColumnIndex']");

		 		// get chart range filter filter column
		 		var cfColumnIndex = $("#iwpgv-dashboard select[id='categoryFilter.filterColumnIndex']");

				// Chart range filter div (div where the filter is drawn)
				var cfDiv = $("#iwpgv-dashboard #category-filter-" + divId + "-div");

				// Call input load and select handler
				filterInputHandler ( input, "categoryFilter", categoryFilter, cfInputs, cfColumnIndex, categoryFilterOptions, cfDiv, null );


			// if input has class categotyFilter 
			} else if (!! inputClass && inputClass.includes("stringFilterOption")) {

				// Get all chart range filter panel inputs except the enable field and filter column
		 		var sfInputs = $("#iwpgv-dashboard .panel#stringFilter input, #iwpgv-dashboard .panel#stringFilter select").not("#iwpgv-dashboard input[id='stringFilter.enable'], #iwpgv-dashboard select[id='stringFilter.filterColumnIndex']");

		 		// get chart range filter filter column
		 		var sfColumnIndex = $("#iwpgv-dashboard select[id='stringFilter.filterColumnIndex']");

				// Chart range filter div (div where the filter is drawn)
				var sfDiv = $("#iwpgv-dashboard #string-filter-" + divId + "-div");

				// Call input load and select handler
				filterInputHandler ( input, "stringFilter", stringFilter, sfInputs, sfColumnIndex, stringFilterOptions, sfDiv, null );


			//if input has class tableChartOptions 
			} else if (!! inputClass && inputClass.includes("tableChart")) {

				// Get all number range filter panel inputs 
		 		var tableChartInputs = $("#iwpgv-dashboard .panel#tableChart input, #iwpgv-dashboard .panel#tableChart select").not("#iwpgv-dashboard input[id='tableChart.enable']");

		 		// table div (div where the table is drawn)
				var tableChartDiv =$("#iwpgv-dashboard #table-chart-" + divId + "-div");

		 		tableInputHandler ( input, "tableChart", iwpgvTable, tableChartInputs, tableChartDiv );

		 		

				//if input has class tableChartOptions 
			} else if (!! inputClass && inputClass.includes("minMaxAvgTableOption")) {

				// Get all number range filter panel inputs 
		 		var minMaxAvgTableInputs = $("#iwpgv-dashboard .panel#minMaxAvgTable input, #iwpgv-dashboard .panel#minMaxAvgTable select").not("#iwpgv-dashboard input[id='minMaxAvgTable.enable']");

		 		// table div (div where the table is drawn)
				var minMaxAvgtableDiv =$("#iwpgv-dashboard #min-max-avg-table-" + divId + "-div");

		 		tableInputHandler ( input, "minMaxAvgTable", minMaxAvgTable, minMaxAvgTableInputs, minMaxAvgtableDiv );

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

	



	function setChartOption(chartOptionInput, chartOptionValue, controlChartWrapper) {
		
		console.log( optionFromFieldId ( chartOptionInput ));
		console.log(chartOptionValue);
		
		// Set chart option and redraw
		controlChartWrapper.setOption( optionFromFieldId ( chartOptionInput ), chartOptionValue);
		controlChartWrapper.draw();

	}





	function renderChart() {
				
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

			if ( ( numRangeFilterEnable.prop("checked") && numRangeFilterOptions.filterColumnIndex ) || (categoryFilterEnable.prop("checked") && categoryFilterOptions.filterColumnIndex) || (chartRangeFilterEnable.prop("checked") && chartRangeFilterOptions.filterColumnIndex) || (stringFilterEnable.prop("checked") && stringFilterOptions.filterColumnIndex)) {

				// Instantiate a dashboard object.
				dashboard = new google.visualization.Dashboard(document.getElementById('dashboard-'+divId+'-div'));

				// Create chart
				iwpgvChart = new google.visualization.ChartWrapper({
					chartType: chartType,
					containerId: 'chart-'+divId+'-div',
					options: chartOptions
				
				});

				// Create a number range filter comtrol if enabled
				if (numRangeFilterEnable.prop("checked") && null !== numRangeFilterOptions.filterColumnIndex) {

					//console.log(numRangeFilterOptions);

					numRangeFilter = new google.visualization.ControlWrapper({
						controlType: 'NumberRangeFilter',
						containerId: 'num-range-filter-'+divId+'-div',
						options: numRangeFilterOptions
					});


					// Add number range state change listener
					google.visualization.events.addListener(numRangeFilter, 'statechange', numRangeFilterStateChangeHandler );

				}

				// Create a chart range filter if enabled
				if (chartRangeFilterEnable.prop("checked") && null !== chartRangeFilterOptions.filterColumnIndex)  {

					//console.log(chartRangeFilterOptions);

					chartRangeFilter = new google.visualization.ControlWrapper({
						controlType: 'ChartRangeFilter',
						containerId: 'chart-range-filter-'+divId+'-div',
						options: chartRangeFilterOptions
					});

					// Add chart range state change listener
					google.visualization.events.addListener(chartRangeFilter, 'statechange', chartRangeFilterStateChangeHandler );


				}
				

				// Create a category filter if enabled
				if (categoryFilterEnable.prop("checked") &&  null !== categoryFilterOptions.filterColumnIndex)  {

					categoryFilter = new google.visualization.ControlWrapper({
						controlType: 'CategoryFilter',
						containerId: 'category-filter-'+divId+'-div',
						options: categoryFilterOptions
					});

				}

				// Create a string filter if enabled
				if ( stringFilterEnable.prop("checked") && null !== stringFilterOptions.filterColumnIndex)  {

					stringFilter = new google.visualization.ControlWrapper({
						controlType: 'StringFilter',
						containerId: 'string-filter-'+divId+'-div',
						options: stringFilterOptions
					});

				}

				
				// Create a table chart if enabled
				if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
					//console.log("table chart");
					
					iwpgvTable = new google.visualization.ChartWrapper({
						chartType: 'Table',
						containerId: 'table-chart-'+divId+'-div',
						options: tableChartOptions
					});

				}

				// Draw Min, Max, Avergae table if  enabled
				if ($("#iwpgv-dashboard input[id='minMaxAvgTable.enable']").prop("checked")) {

					minMaxAvgData = new google.visualization.DataTable();

					// Declare columns
					minMaxAvgData.addColumn('string', 'Label');
					minMaxAvgData.addColumn('number', 'Min');
					minMaxAvgData.addColumn('number', 'Average');
					minMaxAvgData.addColumn('number', 'Max');

					minMaxAvgData.addRows(getMinMaxAvgTableRows(data, []));

					minMaxAvgTable = new google.visualization.ChartWrapper({
						dataTable: minMaxAvgData,
						chartType: 'Table',
						containerId: 'min-max-avg-table-'+divId+'-div',
						options: minMaxAvgTableOptions
					});

					minMaxAvgTable.draw();

				}

				if (numRangeFilterEnable.prop("checked") && null !== numRangeFilterOptions.filterColumnIndex) {

					// Establish dependencies between chart and number range filter
					dashboard.bind(numRangeFilter, iwpgvChart); 

					// Establish dependency between number range filter and table chart if any
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						dashboard.bind(numRangeFilter, iwpgvTable); 
					}

				}


				if (chartRangeFilterEnable.prop("checked") && null !== chartRangeFilterOptions.filterColumnIndex) {

					// Establish dependencies between chart and chart range filter
					dashboard.bind(chartRangeFilter, iwpgvChart); 

					// Establish dependency between chart range filter and table chart if any
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						// Establish dependencies between chart and number range filter
						dashboard.bind(chartRangeFilter, iwpgvTable); 
					}

				}


				if (categoryFilterEnable.prop("checked") && null !== categoryFilterOptions.filterColumnIndex) {

					// Establish dependencies between chart and chart range filter
					dashboard.bind(categoryFilter, iwpgvChart); 

					// Establish dependency between chart range filter and table chart if any
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						// Establish dependencies between chart and number range filter
						dashboard.bind(categoryFilter, iwpgvTable); 
					}

				}

				if (stringFilterEnable.prop("checked") && null !== stringFilterOptions.filterColumnIndex) {

					// Establish dependencies between chart and chart range filter
					dashboard.bind(stringFilter, iwpgvChart); 

					// Establish dependency between chart range filter and table chart if any
					if ($("#iwpgv-dashboard input[id='tableChart.enable']").prop("checked") ) {
						// Establish dependencies between chart and number range filter
						dashboard.bind(stringFilter, iwpgvTable); 
					}

				}


				// Draw the dashboard.
				dashboard.draw(data);

				// Add one time event listener
				google.visualization.events.addOneTimeListener(dashboard, 'ready', oneTimeDashboardReadyHandler );

			
			// If number no filters are enabled
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

			// Add chart error handler
			google.visualization.events.addListener(iwpgvChart, 'error', dashboardErrorHandler );

		}

	} // END function renderChart(data, divId







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
					// If filtered rows present the consider only filtered rows, otherwise sum all rows
					if (filteredRows.length > 0 ) {
						if (data.getValue(rowIndex, colIndex) && filteredRows.includes(rowIndex) ) {
							colSum[colIndex] += data.getValue(rowIndex, colIndex);
							nonNullRowCount[colIndex]++;
						}
					} else {
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








	function oneTimeDashboardReadyHandler() {


		if ( numRangeFilter) {

			// Update Number range slider low and high option values
			$("#iwpgv-dashboard input[id='numRangeFilter.minValue']").val(numRangeFilter.getState().lowValue.toPrecision(4));
			$("#iwpgv-dashboard input[id='numRangeFilter.maxValue']").val(numRangeFilter.getState().highValue.toPrecision(4));

			// Set the Number range filter inputs and bind change events
			$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMin").val(numRangeFilter.getState().lowValue.toPrecision(4)).change(function(){
				numRangeFilter.setState({"lowValue": $(this).val(), "highValue": numRangeFilter.getState().highValue.toPrecision(4)});
				numRangeFilter.draw();

				// Update Min, Max, average table
				if (!! minMaxAvgTable )
					updateMinMaxAvgTable ($(this).val(), numRangeFilter.getState().highValue);

				// Set the Number range filter inputs
				if ( chartRangeFilter) {
					$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeStart").val(numRangeFilter.getState().lowValue.toPrecision(4));
					$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeEnd").val(numRangeFilter.getState().highValue.toPrecision(4));

					// Set number range filter values and redraw
					chartRangeFilter.setState({"range": {"start": numRangeFilter.getState().lowValue.toPrecision(4), "end": numRangeFilter.getState().highValue.toPrecision(4)}});
					chartRangeFilter.draw();
				}

			});

			// Set the Number range filter inputs and bind change events
			$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMax").val(numRangeFilter.getState().highValue.toPrecision(4)).change(function(){
				numRangeFilter.setState({"lowValue": numRangeFilter.getState().lowValue.toPrecision(4), "highValue": $(this).val()});
				numRangeFilter.draw();

				// Update Min, Max, average table
				if (!! minMaxAvgTable )
					updateMinMaxAvgTable (numRangeFilter.getState().lowValue, $(this).val() );

				// Set the Number range filter inputs
				if ( chartRangeFilter) {
					$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeStart").val(numRangeFilter.getState().lowValue.toPrecision(4));
					$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeEnd").val(numRangeFilter.getState().highValue.toPrecision(4));

					// Set number range filter values and redraw
					chartRangeFilter.setState({"range": {"start": numRangeFilter.getState().lowValue.toPrecision(4), "end": numRangeFilter.getState().highValue.toPrecision(4)}});
					chartRangeFilter.draw();
				}
			});

		}

		if ( chartRangeFilter) {

			// Set the Number range filter inputs and bind change events
			$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeStart").val(chartRangeFilter.getState().range.start.toPrecision(4)).change(function(){
				chartRangeFilter.setState( {"range": {"start":  $(this).val(), "end": chartRangeFilter.getState().range.end.toPrecision(4) }});
				chartRangeFilter.draw();

				// Update Min, Max, average table
				if (!! minMaxAvgTable )
					updateMinMaxAvgTable ( $(this).val(), chartRangeFilter.getState().range.end );

				// Set the Number range filter inputs
				if ( numRangeFilter) {
					$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMin").val(chartRangeFilter.getState().range.start.toPrecision(4));
					$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMax").val(chartRangeFilter.getState().range.end.toPrecision(4));

					// Set number range filter values and redraw
					numRangeFilter.setState({"lowValue": chartRangeFilter.getState().range.start.toPrecision(4), "highValue": chartRangeFilter.getState().range.end.toPrecision(4)});
					numRangeFilter.draw();

				}

			});

			// Set the Number range filter inputs and bind change events
			$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeEnd").val(chartRangeFilter.getState().range.end.toPrecision(4)).change(function(){
				chartRangeFilter.setState( {"range": {"start": chartRangeFilter.getState().range.start.toPrecision(4), "end":  $(this).val() }});
				chartRangeFilter.draw();

				// Update Min, Max, average table
				if (!! minMaxAvgTable )
					updateMinMaxAvgTable ( chartRangeFilter.getState().range.start, $(this).val() );

				// Set the Number range filter inputs
				if ( numRangeFilter) {
					$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMin").val(chartRangeFilter.getState().range.start.toPrecision(4));
					$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMax").val(chartRangeFilter.getState().range.end.toPrecision(4));
				

					// Set number range filter values and redraw
					numRangeFilter.setState({"lowValue": chartRangeFilter.getState().range.start.toPrecision(4), "highValue": chartRangeFilter.getState().range.end.toPrecision(4)});
					numRangeFilter.draw();
				}

			});

			
		}

	}







	function dashboardReadyHandler() {

	
		

	}







	function dashboardErrorHandler(error) {

		// Display error message
		$("#iwpgv-dashboard #gvErrorMessages").append("<div class='notice notice-error is-dismissible'><p>" + error.message + "</p></div>");
		console.log(error);

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}







	function oneTimeChartReadyHandler () {
		
	
	}




	function numRangeFilterStateChangeHandler() {

		var lowValue = numRangeFilter.getState().lowValue.toPrecision(4);
		var highValue = numRangeFilter.getState().highValue.toPrecision(4);

		// Set the Number range filter inputs
		$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMin").val(lowValue);
		$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMax").val(highValue);

		if (chartRangeFilter) {

			// Set the Number range filter inputs
			$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeStart").val(lowValue);
			$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeEnd").val(highValue);
			
			// Set number range filter values and redraw
			chartRangeFilter.setState({"range": {"start": lowValue, "end": highValue}});
			chartRangeFilter.draw();
		}

		// Update Min, Max, average table
		if (!! minMaxAvgTable )
			updateMinMaxAvgTable (numRangeFilter.getState().lowValue, numRangeFilter.getState().highValue);

	}




	function chartRangeFilterStateChangeHandler() {

		var lowValue = chartRangeFilter.getState().range.start.toPrecision(4);
		var highValue = chartRangeFilter.getState().range.end.toPrecision(4);

		// Set the chart range filter inputs
		$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeStart").val(lowValue);
		$("#iwpgv-dashboard #crfStartEndInputs input#inputRangeEnd").val(highValue);

		if (numRangeFilter) {

			// Set the Number range filter inputs
			$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMin").val(lowValue);
			$("#iwpgv-dashboard #nrfHighLowInputs input#inputRangeMax").val(highValue);
			
			// Set number range filter values and redraw
			numRangeFilter.setState({"lowValue": lowValue, "highValue": highValue});
			numRangeFilter.draw();
		}

		// Update Min, Max, average table
		if (!! minMaxAvgTable )
			updateMinMaxAvgTable (chartRangeFilter.getState().range.start, chartRangeFilter.getState().range.end);

	}





	function updateMinMaxAvgTable (filteredMin, filteredMax) {

		// Get Number Range Filter high and low values; and filter nthe data accordingly
		var filteredRows = data.getFilteredRows([{
			column: parseInt(numRangeFilterOptions.filterColumnIndex),
			minValue: filteredMin,
			maxValue: filteredMax
		}]);

		// Update the Min, Max, Average datatable (if it has been created) and redraw its table
		if (!! minMaxAvgData) {

			// Remove all rows
			minMaxAvgData.removeRows(0, minMaxAvgData.getNumberOfRows());

			// Add new rows based on the new min, max and avg data
			minMaxAvgData.addRows(getMinMaxAvgTableRows (data, filteredRows));

			// redrwa the table
			minMaxAvgTable.draw();

		}
	}







	function columnIndexSelectHandler(columnIndexSelect, filterInputs, filterOptions, enableCondition, divId) {

		
		if ( enableCondition) {

				// Unhide all number range filter option inputs
			$(filterInputs).attr("disabled", false); //parent(".fieldWrapper").slideDown();

				// Set filter column index (default = null)
			filterOptions.filterColumnIndex = columnIndexSelect.val();

			switch($(columnIndexSelect).attr("id")) {

				case "numRangeFilter.filterColumnIndex":

					// Show number range inputs
					$("#iwpgv-dashboard #nrfHighLowInputs").slideDown();
					break;
			
				case "chartRangeFilter.filterColumnIndex":

					// Show chart range inputs
					$("#iwpgv-dashboard #crfStartEndInputs").slideDown();
					break;
			}

		} else {

			// Disable number range columnIndex
			filterInputs.attr("disabled", true);

			// Set filter column label (default = null)
			filterOptions.filterColumnIndex = null;

			switch($(columnIndexSelect).attr("id")) {

				case "numRangeFilter.filterColumnIndex":

					// Show number range inputs
					$("#iwpgv-dashboard #nrfHighLowInputs").slideUp();

					// Remove number range slider
					$("#iwpgv-dashboard #num-range-filter-" + divId + "-div").html("");
					break;
			
				case "chartRangeFilter.filterColumnIndex":

					// Show chart range inputs
					$("#iwpgv-dashboard #crfStartEndInputs").slideUp();

					// Remove chart range slider
					$("#iwpgv-dashboard #chart-range-filter-" + divId + "-div").html("");
					break;

				case "categoryFilter.filterColumnIndex":

					// Remove chart range slider
					$("#iwpgv-dashboard #category-filter-" + divId + "-div").html("");
					break;

				case "stringFilter.filterColumnIndex":

					// Remove chart range slider
					$("#iwpgv-dashboard #string-filter-" + divId + "-div").html("");
					break;
			}

		}

	}






	function filterInputHandler ( input, filterType, filterControlWrapper, filterInputs, filterColumnIndex, filterOption, filterDiv, filterHighLowInputs ) {

	// Number range filter enable checkbox behaviour
		if ( input.id == filterType + ".enable") {

			// If checkbox checked
				if ($(input).prop("checked")){
					
					// Enable number range columnIndex
					filterInputs.attr("disabled", false);

					columnIndexSelectHandler(filterColumnIndex, filterInputs, filterOption, filterColumnIndex.val(), divId);

				// If checkbox is not checked
				} else {

					// Disable filtercolumn index select field
					filterColumnIndex.attr("disabled", true);

					// Show number range inputs
					if (filterHighLowInputs) 
						filterHighLowInputs.slideUp();
				}


				// Number range filter enable checkbox behaviour on change 
			$(input).change(function() {

				$("#iwpgv-dashboard #gvErrorMessages").html("");

	 			// If enable checkbox is checked
	 			if ($(this).prop("checked")){

 					// Enable filtercolumn label select field
 					filterColumnIndex.attr("disabled", false);

 					columnIndexSelectHandler(filterColumnIndex, filterInputs, filterOption, filterColumnIndex.val(), divId);

					
				// If enable checkbox is not checked
 				} else {

 					// Enable filtercolumn label select field
 					filterColumnIndex.attr("disabled", true);

 					// Disable all option fields 
 					filterInputs.attr("disabled", true);

					// Remove number range slider
					filterDiv.html("");

					// Hide number range inputs
					if (filterHighLowInputs)
						filterHighLowInputs.slideUp();

 				}

 			 	//Render chart
				renderChart();

 			});

		// Number range Filter column index input behaviour
		} else if ( input.id == filterType + ".filterColumnIndex") {

			// get number range filter enable
 			var filterEnable = $("#iwpgv-dashboard input[id='" + filterType + ".enable']");

 			columnIndexSelectHandler(filterColumnIndex, filterInputs, filterOption, $(input).val() && filterEnable.prop("checked"), divId);

			// Number range filter column select field behaviour on change 
			$(input).change(function() {

				columnIndexSelectHandler(filterColumnIndex, filterInputs, filterOption, $(this).val(), divId);

				//Render chart
				renderChart();

				});

		// behavior for all other number range filter inputs
		}  else {

			$(input).change(function() {
				
				// checkbox behviour
	  			if ($(this).attr("type") == "checkbox") {
	  				optionValue = $(this).prop("checked");
	 			
	  			// All other input
	 			} else {
	 				optionValue = ($(this).val())? $(this).val() : null;
	 			}

				setChartOption(input, optionValue, filterControlWrapper );

			});


		}

	}







	function tableInputHandler( input, tableType, tableWrapper, tableInputs, tableDiv ) {


 		// table chart  filter behaviour
		if ( tableType + ".enable" == input.id ) {
			
			// If table chart enabled
				if ($(input).prop("checked")){

					// It at least one control is enabled
				if ( 
						( $("#iwpgv-dashboard input[id='numRangeFilter.enable']").prop("checked") && numRangeFilterOptions.filterColumnIndex) ||  
						( $("#iwpgv-dashboard input[id='chartRangeFilter.enable']").prop("checked") && chartRangeFilterOptions.filterColumnIndex ) || 
						( $("#iwpgv-dashboard input[id='categoryFilter.enable']").prop("checked") && categoryFilterOptions.filterColumnIndex ) ||  
						( $("#iwpgv-dashboard input[id='stringFilter.enable']").prop("checked") && stringFilterOptions.filterColumnIndex ) 
					) {

					// Enable all input all table chart option inputs
					$(tableInputs).attr("disabled", false); //parent(".fieldWrapper").slideDown();

					//renderChart();

				} else {
					// Display error message
					message = "Table chart is enableb but no control filters are active.  You must select at least one filter before you can see the table chart.";
					$("#iwpgv-dashboard #gvErrorMessages").html("<div class='notice notice-error is-dismissible'><p>" + message + "</p></div>");

					$(input).prop("checked", false);


				}

			} else {

				// Unhide all number range filter option inputs
				$(tableInputs).attr("disabled", true); //parent(".fieldWrapper").slideDown();

				// Remove chart range slider
				tableDiv.html("");

			}


			$(input).change(function() {

	 			if ($(this).prop("checked")){
				
					// It at least one control is enabled
					if ( 
							( $("#iwpgv-dashboard input[id='numRangeFilter.enable']").prop("checked") && numRangeFilterOptions.filterColumnIndex) ||  
							( $("#iwpgv-dashboard input[id='chartRangeFilter.enable']").prop("checked") && chartRangeFilterOptions.filterColumnIndex ) || 
							( $("#iwpgv-dashboard input[id='categoryFilter.enable']").prop("checked") && categoryFilterOptions.filterColumnIndex ) ||  
							( $("#iwpgv-dashboard input[id='stringFilter.enable']").prop("checked") && stringFilterOptions.filterColumnIndex ) 
						) {

						// Enable all input all table chart option inputs
						$(tableInputs).attr("disabled", false); //parent(".fieldWrapper").slideDown();

						renderChart();

					} else {
						// Display error message
						message = "Table chart is enableb but no control filters are active.  You must select at least one filter before you can see the table chart.";
						$("#iwpgv-dashboard #gvErrorMessages").html("<div class='notice notice-error is-dismissible'><p>" + message + "</p></div>");

						$(this).prop("checked", false);

					}
					


				} else {

					// Unhide all number range filter option inputs
					$(tableInputs).attr("disabled", true); //parent(".fieldWrapper").slideDown();
					
					// Remove chart range slider
					tableChartDiv.html("");

				}

 			});

		} else if ( !! input.id && input.id == tableType + ".pageSize") {

			if ($(input).val()) {
				$("#iwpgv-dashboard select[id='" + tableType + ".page']").attr("disabled", true);
			} else {
				$("#iwpgv-dashboard select[id='" + tableType + ".page']").attr("disabled", false);
			}

			$(input).change(function () {

				if ($(this).val()) {
					$("#iwpgv-dashboard select[id='" + tableType + ".page']").attr("disabled", true);
				} else {
					$("#iwpgv-dashboard select[id='" + tableType + ".page']").attr("disabled", false);
				}

				setChartOption(input, $(this).val(), tableWrapper );

			});

		
			
		// behavior for all other inputs
		}  else {

			$(input).change(function() {
				
				// checkbox behviour
	  			if ($(this).attr("type") == "checkbox") {
	  				optionValue = $(this).prop("checked");
	 			
	  			// All other input
	 			} else {
	 				optionValue = ($(this).val())? $(this).val() : null;
	 			}

				setChartOption(input, optionValue, tableWrapper );

			});

		}

	}







	function optionFromFieldId ( input ) {

		if ( ! $(input).attr("id") ) return;

		if (! $(input).attr("id").includes(".") ) return $(input).attr("id");

		// Initialize series id parts
		var parts = $(input).attr("id").split(".");
		//console.log(parts);
		
			// Set chart range input id (chanrt range options start with chartRangeFilter wich is eliminate as part[0])
			var optionId;
			
			switch (parts.length) {

				case 6:
					optionId = parts[1] + "." + parts[2] + "." + parts[3] + "." + parts[4] + "." + parts[5];
					break;

				case 5:
					optionId = parts[1] + "." + parts[2] + "." + parts[3] + "." + parts[4];
					break;

				case 4:
					optionId = parts[1] + "." + parts[2] + "." + parts[3];
					break;

				case 3:
					optionId = parts[1] + "." + parts[2];
					break;

				case 2:
					optionId = parts[1];
					break;

				default:
					optionId = false;
					break;
			}

			return optionId;

	}





	function colorPickerChangeCallback( event, ui ) {

		console.log( $(event.target).attr("class") );

		if ($(event.target).attr("class").includes( "chartOption")) {

			// set chart option	and redraw
 			iwpgvChart.setOption( optionFromFieldId ( event.target), ui.color.toString() );
  			iwpgvChart.draw();

		} else if ($(event.target).attr("class").includes( "numRangeOption")) {

			// set chart option	and redraw
 			numRangeFilter.setOption( optionFromFieldId ( event.target), ui.color.toString() );
  			numRangeFilter.draw();

  		} else if ($(event.target).attr("class").includes( "chartRangeOption")) {

			// set chart option	and redraw
 			chartRangeFilter.setOption( optionFromFieldId ( event.target), ui.color.toString() );
  			chartRangeFilter.draw();
  		}

	}




	function colorPickerClearCallback( event ) {


		if ( $(event.target).parent().find(".wp-color-picker").attr("class").includes("chartOption")) {
			
			// set chart option	and redraw
 			iwpgvChart.setOption( optionFromFieldId ( $(event.target).parent().find(".wp-color-picker") ), "transparent" );
  			iwpgvChart.draw();

 		} else if ( $(event.target).parent().find(".wp-color-picker").attr("class").includes("numRangeOption")) {
			
			// set chart option	and redraw
 			numRangeFilter.setOption( optionFromFieldId ( $(event.target).parent().find(".wp-color-picker") ), "transparent" );
  			numRangeFilter.draw();
  			
 		}  else if ( $(event.target).parent().find(".wp-color-picker").attr("class").includes("chartRangeOption")) {
			
			// set chart option	and redraw
 			chartRangeFilter.setOption( optionFromFieldId ( $(event.target).parent().find(".wp-color-picker") ), "transparent" );
  			chartRangeFilter.draw();
  			
 		}


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


