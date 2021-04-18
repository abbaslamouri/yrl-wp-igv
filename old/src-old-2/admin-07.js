jQuery(document).ready(function($) {
	
	"use strict";

	// Load the Visualization API and the corechart package.
	//google.charts.load('current', {'packages':['line', 'table', 'controls']});
	google.charts.load('current', {'packages':['corechart','controls']});

	// Plugin page template id
	var templateId = iwpgv_object.templateId;

	// File panel setting id
	var filePanelId = iwpgv_object.filePanelId;

	// Chart panel setting id
	var chartPanelId = iwpgv_object.chartPanelId;

	var chartForm = $("#" + templateId + " form#chartForm");

	var fileId = $("#" + templateId + " select#fileId");

	var sheetId = $("#" + templateId + " select#sheetId");

	var chartType = $("#" + templateId + " select#chartType");

	var adminMessageDiv = $("#" + templateId + " .adminMessages");

	var gvErrorMessages = $("#" + templateId + " .gvErrorMessages");

	var rangeSlider = $("#" + templateId + " #rangeSlider");

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


	// Set accordion
	$("#" + templateId + " .accordion").iwpgvAccordion();

 
 	//**************************** New, Edit or list graph render ***************************

	// Fetch wp_localize chart object data
	var response = iwpgv_chart_object.response;
	
	if (response) {
		//console.log("New");

		console.log(response);

		// Display error message
		///adminMessageDiv.append(response.message) ;

		switch (response.get.action) {
				
			//New chart
			case "addNewChart":

				if (response.message.includes("notice-error")) {

					// Display Messages
					 $("#" + templateId + " #poststuff").html(response.message);
				} else {

					var hideOnLoad =  $("#" + templateId + " .hideOnLoad")
					$.each(hideOnLoad, function (i, input){
						$(input).slideUp();
					})

					// If there are files in the database, show file selection field
				  if (fileId.find("option").length > 1) {
				   	fileId.parent(".fieldWrapper").slideDown();
				  }

					// Add class active to file settings button and show adjacent panel 
					$("#" + templateId + " button.accordionButton.fileSettings").addClass("active").next(".panel#fileSettings").slideDown();
				 

					//chartOptions = response.chartOptions;

					// var button = "<button class='accordionButton series'>Series</button>";
					// button = button + "<div class = 'panel' id='series'></div>";
					// $("#" + templateId + " #chartSettings").after(button);

					//$("#" + templateId + " #dashboard").prepend("<div id ='emptyChart'> Please Upload or Select a file</div>");
					//$("#" + templateId + " #dashboard-admin-fields").append(response.extraPanels);

					// var button = "<button class='accordionButton trendlines'>Trendlines</button>";
					// button = button + "<div class = 'panel' id='trendlines'></div>";
					 //$("#" + templateId + " #chartSettings").after(response.extraPanels);

					//$("#" + templateId + " #dashboard").prepend("<div id ='emptyChart'> Please Upload or Select a file</div>");
					//$("#" + templateId + " .panel#trendlines").html(response.trendlines);

					

					//Render sheetId
					renderChart(response.sheet, response.divId, response.chartType, response.chartOptions);

					//fileId.parent(".fieldWrapper").slideDown();
					

											setColorPickers();	

		

					

				}
					
				break;

			// An existing chart is being edited
			case "editChart":				

				$.each(response.chartOptions, function (fieldId, fieldValue) {

					$("input[id='"+ fieldId + "'").val(fieldValue);

				});

					// Append sheetId selection options to the sheetId select field
				$("#" + templateId + " select#sheetId").append(response.sheetIdSelectOptions) ;

				sheetIdSelect.slideDown();
				chartType.slideDown();	
   
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


	// When the file upload form is submitted
	$(document).on("click", "#" + templateId + " input#fileUpload", function(event) {

		console.log("hello there");

	

			// If there are files in the database, show file selection field
				  //if (fileId.find("option").length > 1) {
				   	//fileId.parent(".fieldWrapper").slideDown();
				 // }

	});





	
	//******************************************  Upload new file   *******************************
	
	// When the file upload form is submitted
	$(document).on("click", "#" + templateId + " input#fileUploadSubmit", function(event) {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

		// Remove user-notice html text
		adminMessageDiv.html( "" );

   	// Remove existing sheetId selction options.
   	 $.each($(sheetId).children("option"), function(i, obj) {
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



	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function fileUploadRender(response) {

		// Display Messages
		adminMessageDiv.append(response.message) ;

		// show file, sheetId and chart type select if no error
		if (! response.message.includes("notice-error")) {

   	$("#iwpgv-dashboard #fileId").append(response.fileIdOption).val(response.fileId).parent(".fieldWrapper").slideDown();
		$("#iwpgv-dashboard #sheetId").append(response.sheetIdOptions).parent(".fieldWrapper").slideDown();
		$("#iwpgv-dashboard #chartType").val("").parent(".fieldWrapper").slideUp();

			// // Append new file option and file id to the the file select field and show the parent div
			// fileId.append(response.fileIdOption).val(response.fileId).parent(".fieldWrapper").slideDown();
			
			// // Append sheetId selection options to the sheetId select field and show parent div
			// sheetId.append(response.sheetIdOptions).parent(".fieldWrapper").slideDown();
	
			// // Reset chart type select field and hide its parent div
			// chartType.val("").parent(".fieldWrapper").slideUp();
		}
	  
	} // END  function fileUpload() {





	//*****************************************  Select file   *****************************************

	$(document).on("change", "#" + templateId + " select", function(event) {

		// Reset user-notice and Google visualization error message divs
		$(adminMessageDiv).html( "" );
		$(gvErrorMessages).html( "" );

		// Bail if not file, sheetId or chart type select
		if ($(this).attr("id") != "fileId" && $(this).attr("id") != "sheetId" && $(this).attr("id") != "chartType") return;
		
		// If a file was selected
		if ($(this).attr("id") == "fileId") {

			// If no file selected 
		   if (! $(this).val()) {
		   	$(adminMessageDiv).html(displayMessage("Please select a file", null));

		   	// hise sheetId and chart type selection field
		   	$(sheetI).parent(".fieldWrapper").slideUp();
		   	$(chartType).parent(".fieldWrapper").slideUp();
		   
		   // If a file is selected
		  	} else {

		   	// Remove existing sheetId selction options.
		   	 $.each($(sheetId).children("option"), function(i, obj) {
		    		if (obj.value !== "") {
		    			obj.remove();
		    		}
		    	});

		   	// append file id to form
		   	formData.append("fileId",  fileId.val() );

				// Append action hook to the form
				formData.append("action", iwpgv_object.file_select_action);
			
				// Append nonce to the form
				formData.append("nonce", iwpgv_object.file_select_nonce);

				// upload files
				ajaxRequest(formData);
			}
		
		} else {

			// Hide chart type select field until a file has been selected/uploaded   
	 		if ($(this).attr("id") == "sheetId") {
	 			 if ($(this).val() !== "") {
	 			 	$(chartType).parent(".fieldWrapper").slideDown();
	 			 } else {
	 			 	$(chartType).parent(".fieldWrapper").slideUp();
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
	function fileSelectRender(response) {

		//console.log("File Select Render");

		// Display error message
		adminMessageDiv.append(response.message) ;

		sheetId.append(response.sheetIdOptions).parent(".fieldWrapper").slideDown();
		
		// Append sheetId selection options to the sheetId select field and show parent div
		//sheetId.append(data.sheetIdOptions).parent(".fieldWrapper").slideDown();

	  // Reset chart type select field and hide its parent div
		//chartType.val("").parent(".fieldWrapper").slideUp();

	  
	} // END  function fileIdRender() {








	function refresh(){

		console.log("GHGHGHGH");

	   //// Remove user-notice html text
		adminMessageDiv.html( "" );

		$("#" + templateId + " #emptyChart").html("");

		//$("#" + templateId + " button.accordionButton").not(":first-child").slideDown();	

		// Check if a file id was submitted
	  if ( ! fileId.val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a file", null));
	  		return;
	  	}
	  
	  // Check if a sheetId id was submitted
	  if ( ! $("#" + templateId + " select#sheetId").val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a sheetId", null));
	  		return;
	  	}
	  
	  // Check if a chart type id was submitted
	  if ( ! $("#" + templateId + " select#chartType").val()) {
	  		adminMessageDiv.html(displayMessage("Please select a chart type", null));
	  		return;
	  	}
	  

	  	//var chartForm = $("#" + templateId + " form#chartForm");

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



	/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function refreshChart(response) {

		console.log("Refresh Chart");
		//console.log(response);

		//Display error message
		adminMessageDiv.append(response.message );

		// Remove class active from file settings button and hide adjacent panel 
		$("#" + templateId + " #dashboard-admin-fields").html(response.adminFields);


		// Remove class active from file settings button and hide adjacent panel 
		//$("#" + templateId + " button.accordionButton.fileSettings").removeClass("active").next(".panel#fileSettings").slideUp();
		//
		//$("#" + templateId + " #dashboard").prepend("<div id ='emptyChart'> Please Upload or Select a file</div>");
		//$("#" + templateId + " #trendlines").html(response.trendlines);
			// Append new file option and file id to the the file select field and show the parent div

		// show all accordion panel butons (initially hidden with css)
		//$("button.accordionButton").slideDown();
		
		

		

		// Hide accordion panels not relevemt to the chosen chart type
		//$.each(response.uniquePanels, function(buttonClass, uniquePanel) {
			//if ( ! uniquePanel.includes(response.post.chartType)) $(".accordionButton."+ buttonClass).slideUp();
		//});

		// Hide sub panels not relevemt to the chosen chart type
		//$.each(response.uniqueSubpanels, function(buttonClass, uniqueSubpanel) {
			//if ( ! uniqueSubpanel.includes(response.post.chartType)) $(".accordionButton."+ buttonClass).slideUp();
		//});

		// Hide option fields not relevemt to the chosen chart type 
		//$.each(response.uniqueFields, function(fieldId, uniqueField) {
			//if (! uniqueField.includes(response.post.chartType)) {
				//$("input[id='"+ fieldId+ "'").attr("disabled", true).parents(".fieldWrapper").slideUp();
				//$("select[id='"+ fieldId+ "'").attr("disabled", true).parents(".fieldWrapper").slideUp();
			//}
		//});	$("#" + templateId + " #dashboard-admin-fields").find("#sheetId").append(response.sheetIdOptions); //.parent(".fieldWrapper").slideDown();

							$("#" + templateId + " #dashboard-admin-fields").find("#sheetId").append(response.sheetIdOptions).val(response.post.sheetId).parent(".fieldWrapper").removeClass("hideOnLoad");



		
		// Render sheetId
		renderChart(response.sheet, response.divId, response.post.chartType, response.chartOptions);

									$("#" + templateId + " #dashboard-admin-fields").find("#fileId").val(response.fileId).parent(".fieldWrapper").removeClass("hideOnLoad");
									// Append sheetId selection options to the sheetId select field and show parent div
									// 
													

							$("#" + templateId + " #dashboard-admin-fields").find("#chartType").val(response.post.chartType).parent(".fieldWrapper").removeClass("hideOnLoad");


			// Add class active to file settings button and show adjacent panel 
					$("#" + templateId + " button.accordionButton.fileSettings").addClass("active").next(".panel#fileSettings").slideDown();

									//setColorPickers();

 // Show field hints on mouseover
				  $("#" + templateId + " .hint").mouseover(function(){
				  	$(this).parent().parent().children(".fieldHint").css("opacity", 1).slideDown();
				  });

				    // Hide field hints on mouseover
				   $("#" + templateId + " .hint").mouseout(function(){
						$(this).parent().parent().children(".fieldHint").css("opacity", 0).slideUp();
					});

																setColorPickers();	



	} // END  function fileUpload() {







	//*****************************************  Save Chart   *****************************************
	$(document).on('submit', "#" + templateId + " form#chartForm", function(event) {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

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
	  if ( ! fileId.val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a file", null));
	  
	  // Check if a sheetId id was submitted
	  } else if ( ! $("#" + templateId + " select#sheetId").val() ) {
	  		adminMessageDiv.html(displayMessage("Please select a sheetId", null));
	  
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









	$(document).on("click", "#" + templateId + " .showTrendlines", function(event) {

		var trendlines = iwpgvChart.getOption("trendlines");
		var seriesInputs = $(this).next().find("input, select");
		var value;
		console.log(trendlines);

		var seriesId = $(this).attr("id"); 
		//console.log();

		if ( $(this).prop("checked")) {
			$(this).next().slideDown();
			var arr = {}
			$.each(seriesInputs, function(i, input) {
				var parts = input.id.split(".");
				if(input.type == "checkbox") {
					if ( $(input).prop("checked")) {	
						value = true;
					} else {
						value =false;
					}
				} else {
					value = input.value
				}

				if (parts.length == 3) {
					//console.log(parts);
					//console.log(input.value);

					arr[parts[2]] = value

				}


			});
			//if (trendlines.length == 0) {
				//var xx = {};
				//xx.trendlines = {};
				trendlines[seriesId] = arr;
			//}

			

			console.log(trendlines);
			//series.trendlines[$(this).attr("id")]
			//var series = {};
			//series[] = arr;
		

		} else {
			console.log("ppppp")
			delete trendlines[seriesId];
			$(this).next().slideUp();

		}

			iwpgvChart.setOption("trendlines",  trendlines);
			iwpgvChart.draw();
	});

 	// Retreive showTrendlines checkboxes
			//var showTrendlines = $("#" + templateId + " input.showTrendlines");

			// retreive trendlines
		

  // Loop through all the showTrendlines checkboxes and hide corresponsding input fields and removove series trendlieines if the checkbox is unchecked
			// $.each(showTrendlines, function (i,seriesTrendlines){
			// 	//if (! seriesTrendlines.checked) {
			// 		//delete trendlines[seriesTrendlines.id];
			// 		var siblings = $(seriesTrendlines).siblings();
			// 		$.each(siblings, function(j, sibling){
			// 								console.log(sibling);

			// 			$(sibling).hide();
			// 		})
			// 		//$(seriesTrendlines).siblings().css("display", "none");
			// 	//}
			// });








	// *****************************************  When the chart title changes   *****************************************

	//$("input, select").change(function() {
	$(document).on("change", "#" + templateId + " input, select", function(event) {

		event.stopPropagation(); // Stop stuff happening
		event.preventDefault(); // Totally stop stuff happening

			var value;

		var fieldClass = $(this).attr("class");

		if (!! fieldClass && fieldClass.includes("chartOption")) {


			console.log($(this).attr("id"));
			console.log($(this).val());

			  	if ($(this).attr("type") == "checkbox") {
			 		iwpgvChart.setOption($(this).attr("id"), $(this).prop("checked"));
			 	} else if ($(this).attr("id").includes("ticks") || $(this).attr("id").includes("lineDashStyle")) {
			 		console.log("here here");
			 		value = $(this).val();
			 		var ticks = value.split(",");
			 		iwpgvChart.setOption( $(this).attr("id"), ticks);
			 	} else if ($(this).attr("id").includes("trendlinesxx")) {
			 			console.log("XXXXXX");
			 			var id = $(this).attr("id");
			 			var array = id.split(".");
			 			var trendlines = iwpgvChart.getOption("trendlines" );
			 			console.log($(this).parents("series-"+array[1]));
			 			$.each($("#trendlines input, #trendlines select"), function(i, input){
			 				//console.log(input.id);
			 			});


			 		if ($(this).attr("id").includes("type") && ! $(this).val()) {
			 			
			 			delete trendlines[array[1]];
			 			console.log(trendlines);
			 		//iwpgvChart.setOption("trendlines."+array[1], {});
			 		//console.log();
			 		
			 		


			 		} else {

			 		
			 		}
			 	} else if (!! $(this).attr("class") && $(this).attr("class").includes("hasFieldSuffix")) {
			 		var suffix = $(this).siblings(".fieldSuffix").html();
					console.log("HERETTTTT");
					//console.log($(this).val());
					iwpgvChart.setOption($(this).attr("id"), $(this).val() + suffix );
			 	} else {console.log("inputxxxxxx");
			  		iwpgvChart.setOption( $(this).attr("id"), $(this).val());
			 	}
			  	
			  iwpgvChart.draw();
			}

	});
















		/**
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function sheetIdSelectRender(data) {

		console.log("File Select Render");

		// Display error message
		adminMessageDiv.append(data.message) ;
		
		// Append sheetId selection options to the sheetId select field
		$("#" + templateId + " select#rangeSlider").append(data.rangeSlider) ;

		// Show sheetId selection field
	   chartType.slideDown();

	   // Reset chart type select
		$("#" + templateId + " select#fchartType").val("");


		// Show sheetId selection field
	   rangeSlider.slideDown();

	   // Reset chart type select
		$("#" + templateId + " select#rangeSlider").val("");

	  
	} // END  function fileIdRender() {






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
				if (response.post.action === iwpgv_object.sheetId_select_action) sheetIdSelectRender(response);

				 // If request action is draw chart
				if (response.post.action === iwpgv_object.refresh_chart_action) refreshChart(response);

				// If request action is file select
				if (response.post.action === iwpgv_object.chart_save_action) chartSaveRender(response);
				
				

				// hide  animation
				$("#" + templateId + " .adminAjaxLoading").slideUp();

			},
			error: function (jqXHR, response, error) {
				if (jqXHR.readyState === 4 && response === "error"){
					var output = "<div class='notice notice-error is-dismissible'>Status: " + jqXHR.status + ",   Error: PHP " + error + "</div>" ;
					adminMessageDiv.html(output);
				}

				// hide  animation
				$("#" + templateId + " .adminAjaxLoading").slideUp();

			}			
				 
			

		});  // END $.ajax ({

	 }  // END function ajaxRequest(){



	/**
	 * Renders Google Grap
	 * @param  {object} data           spreadsheetId data formatted for Google Visualization
	 * @param  {[string]} divId string reprresentin the index or name of the spreadsheetId
	 * @return {[]}                
	 */
	function renderChart(sheet, divId, chartType, chartOptions) {

		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawVisualization);

		function drawVisualization() {

			var data = getDatatable(sheet);

			if(rangeSlider === true){
			
			var filterColumnLabel = sheet.labels.B;

			// Instantiate a dashboard object.
			dashboard = new google.visualization.Dashboard(document.getElementById('dashboard-'+divId+'-div'));

			// Create a range slider, passing some options
			numRangeSlider = new google.visualization.ControlWrapper({
				'controlType': 'NumberRangeFilter',
				'containerId': 'filter-'+divId+'-div',
				'options': {}
			});

			
			// Create chart, passing some options
			iwpgvChart = new google.visualization.ChartWrapper({
				chartType: chartType,
				containerId: 'chart-'+divId+'-div',
				options: chartOptions
			
			 });


			//Create a table chart, passing some options
			// iwpgvTable = new google.visualization.ChartWrapper({
			// 	chartType: 'Table',
			// 	containerId: 'table-'+divId+'-div',
			
			
			//  });

			// Establish dependencies, declaring what filter drives the chart, table
			dashboard.bind(numRangeSlider, iwpgvChart); //.bind(numRangeSlider, iwpgvTable);

			// Draw the dashboard.
			dashboard.draw(data);
		} else {
			// Create chart, passing some options
			iwpgvChart = new google.visualization.ChartWrapper({
				dataTable: data,
				chartType: chartType,
				containerId: 'chart-'+divId+'-div',
				options: chartOptions
			
			 });

			iwpgvChart.draw();
		}


			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addListener(iwpgvChart, 'ready', chartReadyHandler );
			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addOneTimeListener(iwpgvChart, 'ready', oneTimeChartReadyHandler );



			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addListener(iwpgvChart, 'error', chartErrorHandler );

			// Listen for the rangeFilter change, then run MyReadyHandler
			//google.visualization.events.addListener(dashboard, 'error', dashboardErrorHandler );


		}   // END  function drawVisualization()


	} // END function renderChart(data, divId) {


	function chartReadyHandler() {

	
		
			//add class active to file settings button and show adjacent panel 
			//$("#" + templateId + " button.accordionButton.fileSettings").addClass("active").next(".panel#fileSettings").slideDown();	

			// Retreive showTrendlines checkboxes
			var showTrendlines = $("#" + templateId + " input.showTrendlines");


			// retreive trendlines
			var trendlines = iwpgvChart.getOption("trendlines");

			//console.log(trendlines);

			// Loop through all the showTrendlines checkboxes and hide corresponsding input fields and removove series trendlieines if the checkbox is unchecked
			$.each(showTrendlines, function (i,seriesTrendlines){
							//console.log(seriesTrendlines.id)

				if (! seriesTrendlines.checked) {
					//delete trendlines[seriesTrendlines.id];
					// var siblings = $(seriesTrendlines).siblings();
					// $.each(siblings, function(j, sibling){
					// 						console.log(sibling);

					// 	$(sibling).hide();
					// })
					//$(seriesTrendlines).siblings().css("display", "none");
				}
			});

	}



	function oneTimeChartReadyHandler (){



		
		var allInputs = $("#" + templateId + " input");
		//console.log(allInputs);
		$.each(allInputs, function(i, input) {
			//console.log(input);
			//console.log(input);
			if (!! $(input).attr("class") && $(input).attr("class").includes("hasFieldSuffix")){
				var suffix = $(input).siblings(".fieldSuffix").html();
				console.log($(input).attr("id"));
				//console.log($(input).val());
				iwpgvChart.setOption($(input).attr("id"), $(input).val() + suffix );
			}

		});

		iwpgvChart.draw();
	}



	function dashboardErrorHandler(error) {

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}




	function chartErrorHandler(error) {

		//console.log(error);

		// Display error message
		$("#" + templateId + " .gvErrorMessages").append(displayMessage(error.message) );
		console.log(displayMessage(error.message));;

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}






	/**
	 * Renders Google Grap
	 * @param  {object} data           spreadsheetId data formatted for Google Visualization
	 * @param  {[string]} divId string reprresentin the index or name of the spreadsheetId
	 * @return {[]}                
	 */
	function renderChartList(sheet, divId, chartType, chartOptions) {
		
		// console.log("renderChart");
		// console.log(chartType);
		// console.log(sheetId);
		// 

		// Set a callback to run when the Google Visualization API is loaded.
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {

			var data = getDatatable(sheet);

			//console.log(data);

			// set filter column label
			if (chartType == "LineChart") filterColumnLabel = sheetId.labels.A;
			if (chartType == "PieChart") filterColumnLabel = sheetId.labels.B;

		
			console.log(filterColumnLabel);
			
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
			data.addColumn({
				type: sheet.dataTypes[i], 
				label: label,
				role: sheet.roles[i]

				});
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

		return message; 

	} // END function compose_message ($errors, $success)  {


	function setColorPickers () {
	
 	// Setup WP Core color picker
	$("#iwpgv-dashboard input.color-picker").each(function(){
		$(this).wpColorPicker({
			change: function(event, ui){

				console.log("here");

		 	iwpgvChart.setOption($(this).attr("id"), ui.color.toString() );
		  	iwpgvChart.draw();

			},

    		//hide: true,
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

			// Add active class to the clicked button
			//$(this).addClass("active");
			//
			//$(this).next(".panel").slideUp();

		 	// Hide the active button sibling paels
		   $(this).siblings(".panel").slideUp();

		   // Show clicked button next panel
		   ($(this).hasClass("active"))? $(this).next(".panel").slideDown() : $(this).next(".panel").slideUp();
		});

      return this;
   };
 
}( jQuery ));


