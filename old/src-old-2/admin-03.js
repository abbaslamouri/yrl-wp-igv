jQuery(document).ready(function($) {
	
	"use strict";


	// Load the Visualization API and the corechart package.
	google.charts.load('current', {'packages':['line', 'table', 'controls']});

	// Plugin page template id
	var templateId = iwpgv_object.template_id;

	// Google range slider
	var donutRangeSlider;

	// Plugin name
	var plugin = iwpgv_object.plugin;

	// Ajaxt post type
	var ajaxPost = "POST";

	// form
	var formData = new FormData();

	// File select field id
	//var fileId =  $("#" + templateId + " select#fileSelect");

	// file select field options
   //var fileOptions = $("#" + templateId + " select#fileSelect option");

   // sheet select field id
	//var sheet =  $("#" + templateId + " select#sheet");

	// Sheet select field options
	//var sheetOptions = $("#" + templateId + " select#sheet option");

	// chart type field id
	//var chartType =  $("#" + templateId + " select#chartType");


	
	// Set up admin field accordion
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
	  acc[i].addEventListener("click", function() {
		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

	    this.classList.toggle("active");
	    var panel = this.nextElementSibling;
	    if (panel.style.display === "block") {
	      panel.style.display = "none";
	    } else {
	      panel.style.display = "block";
	    }
	  });
	}

	// display the first panel of the accordion
	var accordions = $("#" + templateId + " .accordion");
	accordions.first().addClass("active");
   var panels = $("#" + templateId + " .panel");
   panels.first().css("display", "block");


 	// Setup WP Core color picker
	$("#" + templateId + " .color-picker").each(function(){
		$(this).wpColorPicker();
 	});



  // If there are no files, hide file selection field
   if ($("#" + templateId + " select#fileSelect option").length <2) {
   	$("#" + templateId + " .fileSelect").hide();
   }

 	// Hide sheet select and chart type select fields until a file has been selected/uploaded   
 	if (! $("#" + templateId + " select#fileSelect option").val())  {
		$("#" + templateId + " .sheet").hide();
		$("#" + templateId + " .chartType").hide();	
   }

   // Hide chart type select field until a file has been selected/uploaded   
 	if (! $("#" + templateId + " select#sheet").val())  {
		$("#" + templateId + " .chartType").hide();	
   }

	//**************************** New, Edit or list graph render ***************************

	// Fetch wp_localize chart object data
	var response = iwpgv_chart_object.response;
	
	if (response) {

		// Display error message
		$("#" + templateId + " .admin-messages").append(response.message) ;

		switch (response.get.action) {

			// New chart
			case "addNewChart":

				// Render sheet
				renderGraph(response.sheet.data, response.chartDivId, "Line", response.chartOptions);
				break;

			// An existing chart is being edited
			case "edit-chart":
				renderGraph(response.sheet["sheet-data"], "edit-chart");
				break;

			default:
				// Loop through all charts
				$.each(response.payload, function(chartKey, element) {

					var chartTitle = element.chart["chartTitle"];
					var chartTitleColor = element.chart["chartTitleColor"];
					var chartType = element.chart["chartType"];

					var chartOptions = fetchOptions(element, payload["admin-fields"]);

					// Render sheet
					renderGraph(element["sheet-data"], chartKey, chartType, chartOptions);
				});
			
		}
	}



	//******************************************  Upload new file   *******************************
	
	// When the file upload form is submitted
	$(document).on("click", "#" + templateId + " #fileUploadSubmit", function(event) {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

		// Disable file select field
   	//$("#" + templateId + " fileId").attr("disabled", true);

   		// Hide sheet selection field
	  // $("#" + templateId + " .sheet").hide();

	   // Hide chart selection field 
	   //$("#" + templateId + " .chartTypeId").hide();

	   // Remove existing sheet selction options.
    	// var fileOptions = $("#" + templateId + " select#file option");
   	 // $.each(fileOptions, function(i, obj) {
    	// 	if (obj.value !== "") {
    	// 		obj.remove();
    	// 	}
    	// });

   	// Remove existing sheet selction options.
   	 $.each($("#" + templateId + " select#sheet option"), function(i, obj) {
    		if (obj.value !== "") {
    			obj.remove();
    		}
    	});

   	  // Reset google chart and filter divs
	   //$("#" + templateId + " #chart-edit-chart-div").html("");
	   //$("#" + templateId + " #filter-edit-chart-div").html("");

		// Creted and updated record dates from inputs
		//formData.append("created-date", $("#" + templateId + " #created-date").val()); 
		//formData.append("updated-date", $("#" + templateId + " #updated-date").val()); 
		
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

	$(document).on('change', $("#" + templateId + " select#fileSelect"), function(){

	   // Retreive file id
	   //var fileId = $("#" + templateId + " select#fileSelect");

	    // sheet select field id
		//var sheet =  $("#" + templateId + " select#sheet");

	   // Sheet select field options
		//var sheetOptions = $("#" + templateId + " select#sheet option");

	   // If no file selected 
	   if (! $("#" + templateId + " select#fileSelect").val()) {
	   	displayMessage("Please select a filemmmmmm", null);

	   	// Show sheet selection field
	   	$("#" + templateId + " .sheet").hide();


	   	// Hide sheet selection field
		  // sheet.hide();
	   
	   // If a file is selected
	  	} else {



	   	// Disable file select field
	   	//$("#" + templateId + " #file-select").attr("disabled", true);
	   	//
	   	//	// Remove existing sheet selction options.
	   	 $.each($("#" + templateId + " select#sheet option"), function(i, obj) {
	    		if (obj.value !== "") {
	    			obj.remove();
	    		}
	    	});

	   // Show sheet selection field
	   $("#" + templateId + " .sheet").hide();


		 // Hide chart selection field 
	   //$("#" + templateId + " .chartType").show();


	   


	   

	   	// append file id to form
	   	formData.append("fileId", $("#" + templateId + " select#fileSelect").val() );

			// Append action hook to the form
			formData.append("action", iwpgv_object.file_select_action);
		
			// Append nonce to the form
			formData.append("nonce", iwpgv_object.file_select_nonce);

			// upload files
			ajaxRequest(formData);
		}

		
	});



//*****************  Fetch Chart after sheet and chart type are selected   *****************

$(document).on('change', $("#" + templateId + " select#sheet"), function() {

	console.log($("#" + templateId + " select#sheet"));

	 // Hide chart type select field until a file has been selected/uploaded   
 	if ($("#" + templateId + " select#sheet").val())  {
		$("#" + templateId + " select#chartType").show();	
   } else {
   	$("#" + templateId + " select#chartType").hide();
   }

   //fetchChart();

});

$(document).on('change', "#" + templateId + " #refresh", function(){



		   // Reset gogle chart and filter divs
		   $("#" + templateId + " #chart-edit-chart-div").html("");
		   $("#" + templateId + " #filter-edit-chart-div").html("");

	 // Check if a file id, a chart id and a chart type have been selected
  if ( ! fileId.val() ) {
  		displayMessage("Please select a file", null);
  
  } else if ( ! sheet.val()) {
  	displayMessage("Please select a sheet", null);
  
  } else if ( ! chartType.val()) {
  	displayMessage("Please select a chart type", null);
  } else {


	//var fileId = $("#" + templateId + " #file-select").val();
	formData.append("fileSelect", fileId.val());

	//var sheet = $("#" + templateId + " #sheet-select").val();
	formData.append("sheet", sheet.val());

	//var chartType = $("#" + templateId + " #chart-type-select").val();
	formData.append("chartType", chartType.val());

	//var chartTitle = $("#" + templateId + " #input-chart-title").val();
	//formData.append("chart-title", chartTitle);

   // Append action hook to the form
	formData.append("action", iwpgv_object.get_chart_action);
	
	// Append nonce to the form
	formData.append("nonce", iwpgv_object.get_chart_nonce);

	// upload files
	ajaxRequest(formData);

   }
  
//fetchChart();

});












//*****************************************  Save Chart   *****************************************
$(document).on('submit', "#" + templateId + " form#chartFormcccccccc", function() {

	console.log("KKKKKKlll");
	console.log($(this));

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening
 

    // $("#" + templateId + " .sheet-select").hide();
    // $("#" + templateId + " .chart-type-select").hide();
    // $("#" + templateId + " #chart-new-div").html("");
    // $("#" + templateId + " #filter-new-div").html("");


    //  $("#" + templateId + " #file-select").attr("disabled", true);

     $.each($(this)[0], function(i, obj) {
     //if ($.inArray(obj.id, adminFields) >= 0) {

     	//console.log($.inArray(obj.id, adminFields) >= 0);
     		formData.append(obj.id, obj.value);
     		//[obj.idconsole.log(obj.value);
     //}
     });



   // Retreive file id
   var fileId = $("#" + templateId + " #file-select").val();

    // Retreive file id
   var sheet = $("#" + templateId + " #sheet-select").val();

   //if (fileId && sheet) {

   

		// Append action hook to the form
		formData.append("action", iwpgv_object.chart_save_action);
	
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.chart_save_nonce);

		//var form = $(this)[0];



		//formData.append("form", $(this[0]));

		// upload files
		ajaxRequest(formData);
	//}
	
});











// $(document).on('keyup change paste', "#" + templateId + " #select-chart-type", function(){

// 		console.log("MMMMMMMMM");

   
// 	fetchChart();

	
// });

// $(document).on('keyup change paste', "#" + templateId + " #input-chart-title", function(){

// 		console.log("asdfdsadsadasdasasdsad");

   
// 	fetchChart();

	
// });



	//****************************  Add new chart button clicked  ***************
	
	// When Add New Chart Button is clicked
	$(document).on("click", "#" + templateId + " #add-new-chartxxxxxx", function(event) {

		//event.preventDefault();

		// show/hide Add New Chart form and button
		$("#" + templateId + " #new-chart").toggle();

	});







	

//****************************  delete file    ***************
	
	// When the file upload form is submitted
	$(document).on("submit", "#" + templateId + " form#file-delete-form", function(event) {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

		formData.append("file-delete-submit", $("#" + templateId + " #file-delete-submit").val()); 

		formData.append("file-id", $("#" + templateId + " #file-id").val()); 

		// Append action hook to the form
		formData.append("action", iwpgv_object.file_delete_action);
		
		// Append nonce to the form
		formData.append("nonce", iwpgv_object.file_delete_nonce);

		// upload files
		ajaxRequest(formData);

	});





	function fetchOptions(element, adminFields) {
		var chartOptions= {
			title: chartTitle,
			titlePosition : "in",
			titleTextStyle: {
				color: chartTitleColor
			},
			curveType: 'function',
			//width: 500,
			height: 400,
			legend: 'side',
			//majorTicks : 5,
			hAxis : {
				//minValue : 6000,
				//maxValue : xAxisMax,
				//title : xAxisLabel,
				//gridlines :{count :10},
				// ticks: [2,4,6,8,10,12,14,16,18,20],
			},
			
			vAxis : {
				//minValue : Math.floor(yAxisMin),
				//maxValue : Math.ceil(yAxisMax),
				title : '' ,
				//gridlines : {count :5},
				//ticks: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
			},
			//view: {'columns': [0, 3]}
		};
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
		if (errors) {
			 message = message + "<div class='notice notice-error is-dismissible'><p>" + errors + "</p></div>";
			
		}
		
		// Prepare success output
		if (success) {
			$message = message + "<div class='notice notice-success is-dismissible'><p>" + success + "</p></div>";

		}

		$("#" + templateId + " .admin-messages").html(message);

	} // END function compose_message ($errors, $success)  {






	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function fileUploadRender(data) {

		console.log("File Upload Render");

		// Display error message
		$("#" + templateId + " .admin-messages").append(data.message) ;

		// Add new file option to file selection field
		// var files = data.files;
		// var options = "";
		// $.each(files, function(i, file) {
		// 	options = options + "<option value ='" + i + "' >" + file.fileName + "</option>"
		// })
		

		$("#" + templateId + " select#fileSelect").append(data.fileSelectOption);
		$("#" + templateId + " select#fileSelect").val(data.fileId);
		$("#" + templateId + " .fileSelect").show();

		// Display file name
		$("#" + templateId + " select#sheet").append(data.sheetSelectOptions) ;

		// Show sheet selection field
	   $("#" + templateId + " .sheet").show();

		
		
		
		// Display file name
		//$("#" + templateId + " #selected-file h2").append(data.fileName);

		// Enable file select field
		//$("#" + templateId + " #file-select").attr("disabled", false);

	
		// Reset chart type select
		$("#" + templateId + " select#chartType").val("");

	   // Show chart selection field 
	   //$("#" + templateId + " .chartType").show();

	  
	} // END  function fileUpload() {






	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function fileSelectRender(data) {

		console.log("File Select Render");
		console.log(data.sheetSelectOptions);

		// Display error message
		$("#" + templateId + " .admin-messages").append(data.message) ;
		
			// Display file name
		$("#" + templateId + " select#sheet").append(data.sheetSelectOptions) ;

	// Show sheet selection field
	   $("#" + templateId + " .sheet").show();


	   // Reset chart type select
		$("#" + templateId + " select#fchartType").val("");


		// Display file name
		//$("#" + templateId + " #selected-file h2").append(data["file-name"]);

		// Enable file select field
		//$("#" + templateId + " #file-select").attr("disabled", false);

		// Reset chart type select
		//$("#" + templateId + " #chart-type-select").val("");

			// Show sheet selection field
	  // $("#" + templateId + " .sheet-select").show();

	   // Show chart selection field 
	   //$("#" + templateId + " .chart-type-select").show();

	  
	} // END  function fileSelectRender() {


	
	function fetchChart() {

 	


	}













	function chartSaveRender(data) {


		// Display error message
		$("#" + templateId + " .admin-messages").append(data.message) ;

		//window.location.href = "https://sandbox.yrlus.com";

	}







	





	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function sheetChartRender(response) {

		console.log("PPPPXXXX");

		// Display error message
		$("#" + templateId + " .admin-messages").append(response.message ) ;

		var chartType = $("#" + templateId + " #chart-type-select").val() ;

		var chartTitle = $("#" + templateId + " #chart-title").val();
		var chartTitleColor = $("#" + templateId + " #chart-title-color").val ;

		console.log($("#" + templateId + " #chart-title").val());

		var chartOptions= {
			title: chartTitle,
			titlePosition : "in",
			titleTextStyle: {
				color: chartTitleColor
			},
			curveType: 'function',
			//width: 500,
			height: 400,
			legend: 'side',
			//majorTicks : 5,
			hAxis : {
				//minValue : 6000,
				//maxValue : xAxisMax,
				//title : xAxisLabel,
				//gridlines :{count :10},
				// ticks: [2,4,6,8,10,12,14,16,18,20],
			},
			
			vAxis : {
				//minValue : Math.floor(yAxisMin),
				//maxValue : Math.ceil(yAxisMax),
				title : '' ,
				//gridlines : {count :5},
				//ticks: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
			},
			//view: {'columns': [0, 3]}
		};




		
		// rebder  graph
		renderGraph(response.sheet, "edit-chart", chartType, chartOptions );

	  
	} // END  function fileUpload() {



	/**
	 * Renders a list of graphs
	 * @param  {array} data array of chart data from spreadsheet (1st row contains column labels, 2nd row conatians data types)
	 * @return {}      
	 */
	function chartListRender(response) 
	{
		console.log("LLLLLLfffff");
		//console.log(response);
	  
		// rebder test graph
		//renderGraph(response["default-spreadsheet"][0]["sheets"][0].data, "new" );
		
		// Loop through spreadsheets and render each sheet graph
		$.each(response.data, function(responseKey, responseData) {

			console.log(responseData);

			// Retreive sheets
			//var sheets = spreadsheetData.sheets;

			// Render sheet
			renderGraph(responseData.spreadsheet, responseKey );
			

		}); // END $.each(ajaxRes.response, function(divKey, sheetData)

	} // END  function viewGraps() {



		function fileDelete() {
			
		}





	/**
	 * Renders a list of graphs
	 * @param  {array} data array of chart data from spreadsheet (1st row contains column labels, 2nd row conatians data types)
	 * @return {}      
	 */
	function graphUpdateRender(data) 
	{
		console.log("MMMMMMM");
		console.log(data);
	  
		// rebder test graph
		renderGraph(data.sheets[0].data, "new" );
		
		// Loop through spreadsheets and render each sheet graph
		// $.each(data.spreadsheets, function(divKey, spreadsheetData) {

		// 	// Retreive sheets
		// 	var sheets = spreadsheetData.sheets;

		// 	// Render sheet
		// 	renderGraph(sheets[0].data, divKey );
			

		// }); // END $.each(ajaxRes.response, function(divKey, sheetData)

	} // END  function viewGraps() {





	/**
	 * Performs ajax request
	 * @param  {object} formData form data (may contain $_POST and/or $_FILES)
	 * @return {}          
	 */
	function ajaxRequest(formData){

		 // Show animation
		$("#" + templateId + " .admin-ajax-loading").toggle();

		// Remove user-notice html text
		$("#" + templateId + " .admin-messages").html( "" );

		$.ajax ({
			type: ajaxPost,
			url: iwpgv_object.url, 
			data: formData,
			contentType: false,
			processData: false,
			//dataType: "json",
			success: function(response, status, jqXHR) {
			  response = JSON.parse(response);
				console.log(response);

				 // If request action is file upload
				if (response.post.action === iwpgv_object.file_upload_action) {
					fileUploadRender(response);
				}

 				// If request action is file select
				if (response.post.action === iwpgv_object.file_select_action) {
					fileSelectRender(response);
				}
				 // If request action is file delete
				if (response.post.action === iwpgv_object.file_delete_action) {
					fileDelete(response);
				}

				 // If request action is file select
				if (response.post.action === iwpgv_object.get_chart_action) {
					sheetChartRender(response);
				}


					 // If request action is file select
				if (response.post.action === iwpgv_object.chart_save_action) {
					chartSaveRender(response);
				}


				// If request action is chart list
				if (response.post.action === iwpgv_object.chart_list_action) {
					chartListRender(response);
				}

				// If request action is chart list
				if (response.post.action === iwpgv_object.chart_update_action) {
					graphUpdateRender(response);
				}

				

				 // hide  animation
				$("#" + templateId + " .admin-ajax-loading").hide();


			},
			error: function (jqXHR, response, error) {
				if (jqXHR.readyState === 4 && response === "error"){
					var output = "<div class='notice notice-error is-dismissible'>Status: " + jqXHR.status + ",   Error: PHP " + error + "</div>" ;
					$("#" + templateId + " .admin-messages").html(output);
				}
			}

		});  // END $.ajax ({

	 }  // END function ajaxRequest(){


	/**
	 * Renders Google Grap
	 * @param  {object} data           spreadsheet data formatted for Google Visualization
	 * @param  {[string]} divKey string reprresentin the index or name of the spreadsheet
	 * @return {[]}                
	 */
	function renderGraph(sheetData, divKey, chartType, chartOptions) {
		console.log("KKKKK");
console.log(chartOptions);

		// Intialize table data
		var data;

		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawDashboard);

		function drawDashboard() {

	
			// Create datatable
	 		data = new google.visualization.DataTable(sheetData);


			
		

			 //console.log(data);
		
			// set x-axis label
			var xAxisLabel = data.getColumnLabel(0);

						 console.log(xAxisLabel);


			// Set the x-axis min and max values
			var xAxisRange = data.getColumnRange(0);
			var xAxisMin = xAxisRange.min;
			var xAxisMax = xAxisRange.max;

			console.log("++++++++");

			// set y-axis min and max
			var colCount = data.getNumberOfColumns();
			var yAxisMin = 1E100;
			var yAxisMax = 0;
			
			var tempCol = [];
			var i = 1;
			do {
			  tempCol = data.getColumnRange(i);
			  //console.log(tempCol);

			  yAxisMin = (tempCol.min < yAxisMin) ? tempCol.min : yAxisMin;
			  yAxisMax = (tempCol.max > yAxisMax) ? tempCol.max : yAxisMax;
			  i++;
			}
			while (i < colCount);

			//console.log(Math.floor(yAxisMin), Math.ceil(yAxisMax));


			//console.log(xAxisLabel, chartType, xAxisRange);


			// Instantiate a dashboard object.
			var dashboard = new google.visualization.Dashboard(
				document.getElementById('dashboard-'+divKey+'-div'));

			// Create a range slider, passing some options
			donutRangeSlider = new google.visualization.ControlWrapper({
				'controlType': 'NumberRangeFilter',
				'containerId': 'filter-'+divKey+'-div',
				'options': {
					'filterColumnLabel': xAxisLabel,
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


			
			// Create a line chart, passing some options
			//var lineChart = new google.charts.Line('chart-'+divKey+'-div');
			var thisChart = new google.visualization.ChartWrapper({
				chartType: chartType,
				containerId: 'chart-'+divKey+'-div',
				options: chartOptions
			
			 });

			// Establish dependencies, declaring what filter drives the chart,
        	// so that the chart will only display entries that are let through
        	// given the chosen filter range.
			dashboard.bind(donutRangeSlider, thisChart);
			
			// Draw the dashboard.
			dashboard.draw(data);
			//dashboard.draw(data, google.charts.Line.convertOptions(chartOptions));

			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addListener(dashboard, 'ready', myReadyHandler);

		}   // END  function drawDashboard()


	} // END function renderGraph(data, divKey) {



	function myReadyHandler() {

		// Get the google visualization min and max values
		//var rangeMin = donutRangeSlider.getState().lowValue;
		//var rangeMax = donutRangeSlider.getState().highValue;
		//console.log(rangeMin, rangeMax);
		//
		//
	}
 

});  // END jQuery(document).ready(function($) {


