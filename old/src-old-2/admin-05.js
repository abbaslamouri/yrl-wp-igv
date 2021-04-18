jQuery(document).ready(function($) {
	
	"use strict";

	// Load the Visualization API and the corechart package.
	//google.charts.load('current', {'packages':['line', 'table', 'controls']});
	google.charts.load('current', {'packages':['corechart', 'table', 'controls']});

	// Plugin page template id
	var templateId = iwpgv_object.templateId;

	// File panel setting id
	var filePanelId = iwpgv_object.filePanelId;

	// Chart panel setting id
	var chartPanelId = iwpgv_object.chartPanelId;

	var fileSelect = $("#" + templateId + " select#fileSelect");

	var sheetSelect = $("#" + templateId + " select#sheet");

	var chartType = $("#" + templateId + " .chartType");

	var adminMessageDiv = $("#" + templateId + " .adminMessages");

	var rangeSliderColSelect = $("#" + templateId + " .rangeSliderColSelect");

	// Google chart dashboard
	var dashboard;

	// Google number range slider
	var numRangeSlider;

	// Google filter column label
	var filterColumnLabel;

	// Google chart
	var iwpgvChart;

	// Google table
	var iwpgvTable;

	var chartOptions;

	// form
	var formData = new FormData();

	// //Set up admin field accordion
	// var acc = document.getElementsByClassName("accordion");

	// for ( var i = 0; i < acc.length; i++) {
	//   acc[i].addEventListener("click", function() {
	// 	event.stopPropagation(); // Stop stuff happening
	// 	event.preventDefault(); // Totally stop stuff happening
		

	// 	//console.log($(panels));

	//     this.classList.toggle("active");
	//     var selectedPanel = this.nextElementSibling;
	//     if (selectedPanel.style.display === "block") {
	//       selectedPanel.style.display = "none";
	//     } else {
	//       selectedPanel.style.display = "block";
	//     }
	// 	// var panels = $(this).siblings(".panel");
	// 	// $.each($(panels), function(i, panel){
	// 	// 	if ($(panel).attr("class") !== $(selectedPanel).attr("class")) $(panel).hide();
	// 	// });

	//   });
	// }

	//var accordion = $("#" + templateId + " .accordion");
	$("#" + templateId + " .accordion").iwpgvAccordion();
	
	
		// // });

    
  //var allPanels = $('.accordion > .panel').hide();
    
  // $('.accordion > button').click(function() {
  // 	console.log( $(this).siblings(".panel"));
  //   $(this).siblings(".panel").slideUp();
  //   $(this).next(".panel").slideDown();
  //   return false;
  // });

	


	

	// // display the first panel of the accordion
	// var accordions = $("#" + templateId + " .accordion");
	// accordions.first().addClass("active");
 //   var panels = $("#" + templateId + " .panel");
 //   panels.first().css("display", "block");





   

 	// Setup WP Core color picker
	$("#" + templateId + " .color-picker").each(function(){
		$(this).wpColorPicker({
			change: function(event, ui){

		 	iwpgvChart.setOption($(this).attr("id"), ui.color.toString() );

		  	iwpgvChart.draw();

			},
		});
 	});




  // If there are no files, hide file selection field
   // if (fileSelect.find("option").length <2) {
   // 	$("#" + templateId + " .fileSelect").hide();
   // }

 	// Hide sheet select, chart type and range slider select fields until a file has been selected/uploaded   
 	// if (! fileSelect.find("option").val())  {
		// sheetSelect.hide();
		// chartType.hide();
		// rangeSliderColSelect.hide();

		// // $.each($(".accordion"), function(i,j){
		// // 	$(j).
		// // // 	if (j.id != "fileSettings") j.remove();
		// // });

		// //$("#" + templateId + " .accordion.main").not(":first").hide();
		// // $("#" + templateId + " .panel").first().show();	
  //  }

   // Hide chart type select field until a file has been selected/uploaded   
 	// if (! sheetSelect.val())  {
		// chartType.hide();
		// rangeSliderColSelect.hide();	
  //  }



 	//**************************** New, Edit or list graph render ***************************

	// Fetch wp_localize chart object data
	var response = iwpgv_chart_object.response;
	
	if (response) {

		console.log(response);

		// Display error message
		$("#" + templateId + " .admin-messages").append(response.message) ;

		switch (response.get.action) {
				
			// New chart
			case "addNewChart":

				// Render sheet
				renderChart(response.sheet, response.divId, response.chartType, response.chartOptions);
				break;

			// An existing chart is being edited
			case "editChart":				

				$.each(response.chartOptions, function (fieldId, fieldValue) {

					$("input[id='"+ fieldId + "'").val(fieldValue);

				});

					// Append sheet selection options to the sheet select field
				$("#" + templateId + " select#sheet").append(response.sheetSelectOptions) ;

				sheetSelect.show();
				chartType.show();	
   
				renderChart(response.sheet, response.divId, response.chartType, response.chartOptions );
				break;

			default:

				// Loop through all charts
				$.each(response.chartList, function(chartId, chart) {
					if (chart.error) { 
						$("#" + templateId + "-"+ chartId + "-metabox inside").html(chart.error);
					} else {
						renderChartList(chart.sheet, chartId, chart.chartType, chart.chartOptions);
					}
				});
			
		
		}
	}



	
	//******************************************  Upload new file   *******************************
	
	// When the file upload form is submitted
	$(document).on("click", "#" + templateId + " input#fileUploadSubmit", function(event) {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

		// Remove user-notice html text
		adminMessageDiv.html( "" );

   	// Remove existing sheet selction options.
   	 $.each($("#" + templateId + " select#sheet option"), function(i, obj) {
    		if (obj.value !== "") {
    			obj.remove();
    		}
    	});
		
		// Append form submit button input field
		formData.append("fileUploadSubmit", $("#" + templateId + " #fileUploadSubmit").val()); 

		// Append action hook to the form
		formData.append("action", iwpgv_object.file_upload_action);
		
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.file_upload_nonce);

		//Retreive files and append to form
		var formFiles =  $("#" + templateId + " input#fileUpload");   // uploaded files
		$.each($(formFiles), function(i, obj) {
			$.each(obj.files,function(j,file){
				formData.append('files[' + j + ']', file);
			});
		});

		// upload files
		ajaxRequest(formData);

	});




	//*****************************************  Select file   *****************************************

	$("#" + templateId + " .panel.fileSettings").on("change", "select", function(){

		//console.log("OPOPOPO");

		//console.log($(this).attr("id"));
		//console.log($(this).val());

		// Remove user-notice html text
		adminMessageDiv.html( "" );

			// Remove Google visualization error message
		$("#" + templateId + " .gvErrorMessages").html( "" );

		// If a file was selected
		if ($(this).attr("id") == "fileSelect") {

			// If no file selected 
		   if (! $(this).val()) {
		   	adminMessageDiv.html(displayMessage("Please select a file", null));

		   	// hise sheet selection field
		   	sheetSelect.hide();

		   	chartType.hide();


		   	$("#" + templateId + " .accordion").hide();	

		   
		   // If a file is selected
		  	} else {

		   	// Remove existing sheet selction options.
		   	 $.each($("#" + templateId + " select#sheet option"), function(i, obj) {
		    		if (obj.value !== "") {
		    			obj.remove();
		    		}
		    	});

		   	// append file id to form
		   	formData.append("fileSelect",  fileSelect.val() );

				// Append action hook to the form
				formData.append("action", iwpgv_object.file_select_action);
			
				// Append nonce to the form
				formData.append("nonce", iwpgv_object.file_select_nonce);

				// upload files
				ajaxRequest(formData);
			}
		
		} else {

			// Hide chart type select field until a file has been selected/uploaded   
	 		if ($(this).attr("id") == "sheet")  {
	 			if ( $(this).val() !== "") {
					chartType.show();
					//rangeSliderColSelect.show();		
	  			 } else {
	   			chartType.hide();
	   			//rangeSliderColSelect.hide();	
	   		}
	   	}

	   	refresh();
		
		}

	});




	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function fileSelectRender(data) {

		//console.log("File Select Render");

		// Display error message
		adminMessageDiv.append(data.message) ;
		
		// Append sheet selection options to the sheet select field
		sheetSelect.append(data.sheetSelectOptions);

	// Show sheet selection field
	   sheetSelect.show();

	   // Reset chart type select
		$("#" + templateId + " select#fchartType").val("");

	  
	} // END  function fileSelectRender() {












	function refresh(){

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

	   //// Remove user-notice html text
		adminMessageDiv.html( "" );

		// Check if a file id was submitted
	  if ( ! fileSelect.val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a file", null));
	  
	  // Check if a sheet id was submitted
	  } else if ( ! $("#" + templateId + " select#sheet").val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a sheet", null));
	  
	  // Check if a chart type id was submitted
	  } else if ( ! $("#" + templateId + " select#chartType").val()) {
	  		adminMessageDiv.html(displayMessage("Please select a chart type", null));
	  } else {

	  	var chartForm = $("#" + templateId + " form#chartForm");

	  	// Gather all chart inputs
     	$.each(chartForm[0], function(i, obj) {
     		formData.append(obj.id, obj.value);
     	});

	   // Append action hook to the form
		formData.append("action", iwpgv_object.refresh_chart_action);
		
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.refresh_chart_nonce);


		// upload files
		ajaxRequest(formData);

	   }
	  
	}



	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function refreshChart(response) {

		//console.log("Refresh Chart");
		//console.log(response);

		// Display error message
		adminMessageDiv.append(response.message ) ;


		$.each(response.uniqueFields, function(fieldId, chartTypes) {
			console.log(fieldId);
			if (! chartTypes.includes(response.post.chartType)) {
				$("input[id='"+ fieldId+ "'").attr("disabled", true).parents(".fieldWrapper").hide();
				$("select[id='"+ fieldId+ "'").attr("disabled", true).parents(".fieldWrapper").hide();
			}
		});

		$("#" + templateId + " .accordion").show();

		chartType.show();	


		// Render sheet
		renderChart(response.sheet, response.divId, response.post.chartType, null);


	} // END  function fileUpload() {







	//*****************************************  Save Chart   *****************************************
	$(document).on('submit', "#" + templateId + " form#chartForm", function() {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

		var colors= [];
		$.each($("#" + templateId + " form#chartForm .colors input:radio"), function(i,color) {
			console.log($(i).is(":checked"));
			if ($(color).is(":checked")) {
				colors.push(color.value);
			}
		});

					console.log(colors);

					$("#" + templateId + " form#chartForm .colors").on("click", "input:radio").click(function(){
						console.log($(this));
					});


		// Remove user-notice html text
		adminMessageDiv.html( "" );

		// Gather all chart inputs
     	$.each($(this)[0], function(i, obj) {
     		formData.append(obj.id, obj.value);
     	});

		// Append action hook to the form
		formData.append("action", iwpgv_object.chart_save_action);
	
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.chart_save_nonce);

		// upload files
		ajaxRequest(formData);
	//}
	
});











	$(document).on('click', "#" + templateId + " #refresh", function(){

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

	   // Reset gogle chart and filter divs
	   //$("#" + templateId + " #chart-edit-chart-div").html("");
	   //$("#" + templateId + " #filter-edit-chart-div").html("");
	   
	   //// Remove user-notice html text
		adminMessageDiv.html( "" );

		// Check if a file id was submitted
	  if ( ! fileSelect.val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a file", null));
	  
	  // Check if a sheet id was submitted
	  } else if ( ! $("#" + templateId + " select#sheet").val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a sheet", null));
	  
	  // Check if a chart type id was submitted
	  } else if ( ! $("#" + templateId + " select#chartType").val()) {
	  		adminMessageDiv.html(displayMessage("Please select a chart type", null));
	  } else {

	  	var chartForm = $("#" + templateId + " form#chartForm");

	  	// Gather all chart inputs
     	$.each(chartForm[0], function(i, obj) {
     		formData.append(obj.id, obj.value);
     	});

	   // Append action hook to the form
		formData.append("action", iwpgv_object.refresh_chart_action);
		
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.refresh_chart_nonce);
		//var form = document.getElementById('chartForm');

		//formData = new FormData(form);

		// upload files
		ajaxRequest(formData);

	   }
	  
	});



	//*****************************************  When the range input min changes   *****************************************

	$("#" + templateId + " #filters").on('change', $("#" + templateId + " input"), function(){

		 //// Remove user-notice html text
		adminMessageDiv.html( "" );

		// Remove user-notice html text
		//console.log($("#" + templateId + " #inputRangeMin").val());
		//console.log(numRangeSlider);
		//
		var minInputVal = $("#" + templateId + " input#inputRangeMin").val();
		var maxInputVal = $("#" + templateId + " input#inputRangeMax").val();

		if (! minInputVal) numRangeSlider.getState().lowValue;
		if (! maxInputVal) numRangeSlider.getState().highValue;

		if(
			isNaN(minInputVal) || minInputVal < numRangeSlider.getState().lowValue || minInputVal > numRangeSlider.getState().highValue || isNaN(maxInputVal) || maxInputVal < numRangeSlider.getState().lowValue || maxInputVal > numRangeSlider.getState().highValue ) {
			var message = "Please enter a number between" + numRangeSlider.getState().lowValue + " and " + numRangeSlider.getState().highValue;
			adminMessageDiv.html(displayMessage(message, null));

		} else {
 			numRangeSlider.setState({'lowValue': minInputVal, 'highValue': maxInputVal});
 			numRangeSlider.draw();
 		}

	});




	//*****************************************  When the chart title changes   *****************************************

	$("#" + templateId + " form#chartForm").on("change", "input, select", function() {


		console.log($(this).prop("checked"));

		  	if ($(this).attr("type") == "checkbox") {
		 		iwpgvChart.setOption($(this).attr("id"), $(this).prop("checked"));
		 	} else {
		  		iwpgvChart.setOption( $(this).attr("id"), $(this).val());
		  	}

		  iwpgvChart.draw();

	});







	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function fileUploadRender(data) {

		console.log("File Upload Render");

		// Display error message
		adminMessageDiv.append(data.message) ;

		// Append new file option to the the file select field
		fileSelect.append(data.fileSelectOption);
		
		// Make the new file option selected
		fileSelect.val(data.fileId);
		
		// Show the file select field
		$("#" + templateId + " .fileSelect").show();

		// Append sheet selection options to the sheet select field
		$("#" + templateId + " select#sheet").append(data.sheetSelectOptions) ;

		// Show sheet selection field if no error
		if (! data.message.includes("notice-error"))
	   	sheetSelect.show();
	
		// Reset chart type select
		$("#" + templateId + " select#chartType").val("");

		chartType.hide();

	  
	} // END  function fileUpload() {






		/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function sheetSelectRender(data) {

		console.log("File Select Render");

		// Display error message
		adminMessageDiv.append(data.message) ;
		
		// Append sheet selection options to the sheet select field
		$("#" + templateId + " select#rangeSliderColSelect").append(data.rangeSliderColSelect) ;

		// Show sheet selection field
	   chartType.show();

	   // Reset chart type select
		$("#" + templateId + " select#fchartType").val("");


		// Show sheet selection field
	   rangeSliderColSelect.show();

	   // Reset chart type select
		$("#" + templateId + " select#rangeSliderColSelect").val("");

	  
	} // END  function fileSelectRender() {






	function chartSaveRender(data) {


		// Display error message
		adminMessageDiv.append(data.message) ;
		$("#" + templateId + " #chartId").val(data.chartId) ;

		// Redirect
		//window.location.href = data.redirect;

	}









	/**
	 * Performs ajax request
	 * @param  {object} formData form data (may contain $_POST and/or $_FILES)
	 * @return {}          
	 */
	function ajaxRequest(formData){

		 // Show animation
		$("#" + templateId + " .adminAjaxLoading").toggle();

		// Remove user-notice html text
		adminMessageDiv.html( "" );

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
				if (response.post.action === iwpgv_object.sheet_select_action) sheetSelectRender(response);

				 // If request action is draw chart
				if (response.post.action === iwpgv_object.refresh_chart_action) refreshChart(response);

				// If request action is file select
				if (response.post.action === iwpgv_object.chart_save_action) chartSaveRender(response);
				
				

				// hide  animation
				$("#" + templateId + " .adminAjaxLoading").hide();

			},
			error: function (jqXHR, response, error) {
				if (jqXHR.readyState === 4 && response === "error"){
					var output = "<div class='notice notice-error is-dismissible'>Status: " + jqXHR.status + ",   Error: PHP " + error + "</div>" ;
					adminMessageDiv.html(output);
				}

				// hide  animation
				$("#" + templateId + " .adminAjaxLoading").hide();

			}			
				 
			

		});  // END $.ajax ({

	 }  // END function ajaxRequest(){



	/**
	 * Renders Google Grap
	 * @param  {object} data           spreadsheet data formatted for Google Visualization
	 * @param  {[string]} divId string reprresentin the index or name of the spreadsheet
	 * @return {[]}                
	 */
	function renderChart(sheet, divId, chartType, chartOptions) {
		
		// console.log("renderChart");
		 console.log(chartOptions);
		// console.log(sheet);
		// console.log(divId);

		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawDashboard);

		function drawDashboard() {

			var data = getDatatable(sheet);

			//console.log(sheet.data);

			// set filter column label
			if (chartType == "Line") filterColumnLabel = sheet.labels.A;
			if (chartType == "PieChart") filterColumnLabel = sheet.labels.B;

			// Instantiate a dashboard object.
			dashboard = new google.visualization.Dashboard(document.getElementById('dashboard-'+divId+'-div'));

			// Create a range slider, passing some options
			numRangeSlider = new google.visualization.ControlWrapper({
				'controlType': 'NumberRangeFilter',
				'containerId': 'filter-'+divId+'-div',
				'options': {
					'filterColumnLabel': filterColumnLabel,
					//minValue: 4000,
					//maxValue: 15000,
					'ui':{
						//'ticks': 8000,
						'labelStacking' : 'vertical'
					}
				},
				'state': {
					//lowValue: 8000,
					//highValue:14000,
				}
			});

			
			// Create chart, passing some options
			iwpgvChart = new google.visualization.ChartWrapper({
				chartType: chartType,
				containerId: 'chart-'+divId+'-div',
				options: chartOptions
			
			 });


			//Create a table chart, passing some options
			iwpgvTable = new google.visualization.ChartWrapper({
				chartType: 'Table',
				containerId: 'table-'+divId+'-div',
			
			
			 });

			// Establish dependencies, declaring what filter drives the chart, table
			dashboard.bind(numRangeSlider, iwpgvChart).bind(numRangeSlider, iwpgvTable);

			// Draw the dashboard.
			dashboard.draw(data);


			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addListener(iwpgvChart, 'error', chartErrorHandler );

			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addListener(dashboard, 'error', dashboardErrorHandler );


		}   // END  function drawDashboard()


	} // END function renderChart(data, divId) {



	function dashboardErrorHandler(error) {

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}




	function chartErrorHandler(error) {

		//console.log(error);

		// Display error message
		$("#" + templateId + " .gvErrorMessages").append(displayMessage(error.message) );

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}






	/**
	 * Renders Google Grap
	 * @param  {object} data           spreadsheet data formatted for Google Visualization
	 * @param  {[string]} divId string reprresentin the index or name of the spreadsheet
	 * @return {[]}                
	 */
	function renderChartList(sheet, divId, chartType, chartOptions) {
		
		// console.log("renderChart");
		// console.log(chartType);
		// console.log(sheet);
		// 

		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {

			var data = getDatatable(sheet);

			//console.log(data);

			// set filter column label
			if (chartType == "Line") filterColumnLabel = sheet.labels.A;
			if (chartType == "PieChart") filterColumnLabel = sheet.labels.B;

		
			console.log(chartOptions);
			
			//Create chart, passing some options
			iwpgvChart = new google.visualization.ChartWrapper({
				chartType: chartType,
				containerId: 'chart-'+divId+'-div',
				dataTable: data,
				 //options: chartOptions
			
			 });


			

			// Draw the dashboard.
			iwpgvChart.draw();

			$.each(chartOptions, function (fieldId, option) {

				iwpgvChart.setOption(fieldId, option);

			});

		

			iwpgvChart.draw();

		}   // END  function drawChart()


	} // END function renderChart(data, divId) {







	function getDatatable(sheet) {

		// Create empty datatable
		var data = new google.visualization.DataTable();
		
		// Loop through all the labels row to add datatable columns (eliminate all null values)
		$.each (sheet.labels, function (i, label) {
			data.addColumn(sheet.dataTypes[i], label);
		});

		// initial datatbale rows array
		var arr = [];
		
		// Loop through all rows, eliminate all null cell values and add only those rows that do not have cells with null data
		$.each (sheet.data, function (i, row) {
			
			// Initialize column array
			var element =[];

			// loop through all column element and add to the column array
			$.each (row, function (j, cell) {
				//if (cell !== null) {
					element.push(cell);
				//} else {
					//return true
				//}

			});

												//console.log(element.length);
																								//console.log(Object.keys(row).length);



			// Add the column array to the rows arrays if the rowe and comun length match (cells with null values are not added)
			arr.push(element);
		});

		// add all rows to datatable
		data.addRows(arr);

		// Return datatable
		return data;


	}






	function dashboardReadyHandler(error) {

	//console.log(error);

		// Display error message
		//adminMessageDiv.append(displayMessage(error.message)) ;

	}








	function getMinMaxAvg(data, cols) {

		//console.log(cols);

		// Initialize  the min, max and average of all the rows
		var dataMins = {};
		var dataMaxs = {};
		var dataAvgs = {};
		$.each (cols, function(i, col){
			dataMins[i]= {label: col.label, data: 1e100};
			dataMaxs[i]= {label: col.label, data: 0};
			dataAvgs[i]= {label: col.label, data: 0};
		});


		//Loop through all rows to get min, max and averages for each column
		$.each(data, function(rowIndex,rowObject) {

			$.each(rowObject, function (colIndex, cell) {
				if (cell < dataMins[colIndex].data) {
					dataMins[colIndex].data = cell;
				}
				if (cell > dataMaxs[colIndex].data) {
					dataMaxs[colIndex].data = cell;
				}

				dataAvgs[colIndex].data = cell + dataAvgs[colIndex].data;

			});

		});


		$.each (cols, function(i, col){		
			dataAvgs[i].data = dataAvgs[i].data/data.length;		
		});

		 var minAvgMax = [['Column','min','Average', 'Max']];

		$.each(dataMins, function(i, mins) {
			if ( i != 0) minAvgMax.push([dataMins[i].label, dataMins[i].data, dataAvgs[i].data, dataMaxs[i].data]);
		});

		return minAvgMax;

	}










	/**
	 * Composes error and success messsages
	 * @param  WP/Error $errors  wp error object
	 * @param  WP/Error $success wp error object (success)
	 * @return string          error and success message string
	 */
	function displayMessage (errors, success)  {

		// Prepare error output
		var message = "";
		if (errors) message = message + "<div class='notice notice-error is-dismissible'><p>" + errors + "</p></div>";
		
		// Prepare success output
		if (success) $message = message + "<div class='notice notice-success is-dismissible'><p>" + success + "</p></div>";

		return message 

	} // END function compose_message ($errors, $success)  {
 



});  // END jQuery(document).ready(function($) {






(function ( $ ) {

   $.fn.iwpgvAccordion = function() {

   	//this.children("panel").first().slideDown();
        
		// Add event listner to the accordion div buttons
		this.on("click", "button", function(event) {
			$(this).siblings("button").removeClass("active");
			$(this).addClass("active");
			console.log("Hello");
		 	event.stopPropagation(); // Stop stuff happening
			event.preventDefault(); // Totally stop stuff happening
			console.log( $(this).siblings(".panel"));
		   $(this).siblings(".panel").slideUp();
		   $(this).next(".panel").slideDown();
		});

      return this;
   }
 
}( jQuery ));


