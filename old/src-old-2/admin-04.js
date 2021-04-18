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
		$(this).wpColorPicker(

			 {
			 	change: function(event, ui){

		 	iwpgvChart.setOption($(this).attr("id"), ui.color.toString() );

		  iwpgvChart.draw();

			 		//var inputClass = $(this).attr("id");
			 		//console.log("#" + templateId + " #" + inputClass + "-hidden");
			 		//console.log($(this).parent());
			 		//var thisDiv = event.target;

			 		//console.log($(thisDiv).parent());

			 		//$(this).closest("input").val(ui.color.toString());
			 		//console.log($("input").hasClass(inputClass + "-hidden"));
			 		//$("input#hiddenxxx").val(ui.color.toString());

			 		//$("#" + templateId + " #" + inputClass + "-hidden").val(ui.color.toString());

			 	},
			 }


		);
 	});




  // If there are no files, hide file selection field
   if (fileSelect.find("option").length <2) {
   	$("#" + templateId + " .fileSelect").hide();
   }

 	// Hide sheet select, chart type and range slider select fields until a file has been selected/uploaded   
 	if (! fileSelect.find("option").val())  {
		sheetSelect.hide();
		chartType.hide();
		rangeSliderColSelect.hide();

		$("#" + templateId + " #chartSettings").hide();	
   }

   // Hide chart type select field until a file has been selected/uploaded   
 	if (! sheetSelect.val())  {
		chartType.hide();
		rangeSliderColSelect.hide();	
   }


   // Disable save button if no file is selected
	// if (! fileSelect.val() || ! $("#" + templateId + " select#sheet").val() || ! $("#" + templateId + " select#chartType").val()) {
	// 	$("#" + templateId + " #saveChart").attr("disabled", true);
	// } else {
	// 	$("#" + templateId + " #saveChart").attr("disabled", false);

	// }


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

					//console.log("#" + templateId + " #"+ fieldId);
					// if(fieldId != "fileUpload") {
					// 	console.log(fieldId);

					$("input[id='"+ fieldId + "'").val(fieldValue);
				// } else {
				// 	console.log("ppppppp");
				// }
				// // set chart Id field
				//$("#" + templateId + " #chartId").val(response.chartId) ;


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

		console.log("OPOPOPO");

		console.log($(this).attr("id"));
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

		// // If a sheet was selected	
		// } else if ($(this).attr("id") == "sheet") {

		// 	if (! $("#" + templateId + " #fileSelect").val())
		// 		adminMessageDiv.html(displayMessage("Please select a file", null));

		// 	// If no sheet selected 
		//    if (! $(this).val()) {
		//    	adminMessageDiv.html(displayMessage("Please select a sheet", null));

		//    	chartType.hide();
		// 		rangeSliderColSelect.hide();
		   
		//    // If a sheet is selected
		//   	} else {

		//    	// Remove existing sheet selction options.
		//    	 $.each($("#" + templateId + " select#rangeSliderColSelect option"), function(i, obj) {
		//     		if (obj.value !== "") {
		//     			obj.remove();
		//     		}
		//     	});

		//    	 // Reset form data
		//    	 formData = new FormData();

		//    	// append file id to form
		//    	formData.append("fileSelect",  $("#" + templateId + " #fileSelect").val() );


		//    	// append file id to form
		//    	formData.append("sheet",  $("#" + templateId + " select#sheet").val() );

		// 		// Append action hook to the form
		// 		formData.append("action", iwpgv_object.sheet_select_action);
			
		// 		// Append nonce to the form
		// 		formData.append("nonce", iwpgv_object.sheet_select_nonce);

		// 		// upload files
		// 		ajaxRequest(formData);
		// 	}

		// If either sheet or chart type was selected
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







	//*****************************************  Save Chart   *****************************************
	$(document).on('submit', "#" + templateId + " form#chartForm", function() {

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





	function refresh(){

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


		// upload files
		ajaxRequest(formData);

	   }
	  
	}





	// //*****************************************  Select file   *****************************************

	// $("#" + templateId + " .fileSelect").on('change', fileSelect, function(){

	// 	// Remove user-notice html text
	// 	adminMessageDiv.html( "" );

	//    // If no file selected 
	//    if (! fileSelect.val()) {
	//    	adminMessageDiv.html(displayMessage("Please select a file", null));

	//    	// Show sheet selection field
	//    	sheetSelect.hide();
	   
	//    // If a file is selected
	//   	} else {

	//    	//	// Remove existing sheet selction options.
	//    	 $.each($("#" + templateId + " select#sheet option"), function(i, obj) {
	//     		if (obj.value !== "") {
	//     			obj.remove();
	//     		}
	//     	});

	//    	// append file id to form
	//    	formData.append("fileId", fileSelect.val() );

	// 		// Append action hook to the form
	// 		formData.append("action", iwpgv_object.file_select_action);
		
	// 		// Append nonce to the form
	// 		formData.append("nonce", iwpgv_object.file_select_nonce);

	// 		// upload files
	// 		ajaxRequest(formData);
	// 	}

	// });












	// //*****************************************  Select file   *****************************************

	// $("#" + templateId + " #chartForm").on('change', $("#" + templateId + " input"), function(e){

	// 	// Remove user-notice html text
	// 	adminMessageDiv.html( "" );

	//  var id = e.target.id;


	// });









	// //*****************  sheet select   *****************

	// sheetSelect.on('change', $("#" + templateId + " select#sheet"), function() {
		
	// 	// Remove user-notice html text
	// 	adminMessageDiv.html( "" );

	// 	 // Hide chart type select field until a file has been selected/uploaded   
	//  	if (!! $("#" + templateId + " select#sheet").val())  {
	// 		chartType.show();	
	//    } else {
	//    	chartType.hide();
	//    }

	//    //fetchChart();

	// });





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
		//
		//

	 //   // If no file selected 
	 //   if (! fileSelect.val()) {
	 //   	adminMessageDiv.html(displayMessage("Please select a file", null));

	 //   	// Show sheet selection field
	 //   	sheetSelect.hide();
	   
	 //   // If a file is selected
	 //  	} else {

	 //   	//	// Remove existing sheet selction options.
	 //   	 $.each($("#" + templateId + " select#sheet option"), function(i, obj) {
	 //    		if (obj.value !== "") {
	 //    			obj.remove();
	 //    		}
	 //    	});

	 //   	// append file id to form
	 //   	formData.append("fileId", fileSelect.val() );

		// 	// Append action hook to the form
		// 	formData.append("action", iwpgv_object.file_select_action);
		
		// 	// Append nonce to the form
		// 	formData.append("nonce", iwpgv_object.file_select_nonce);

		// 	// upload files
		// 	ajaxRequest(formData);
		// }

	});




	//*****************************************  When the chart title changes   *****************************************

	$("#" + templateId + " form#chartForm").on("change", "input, select", function() {


		console.log($(this).prop("checked"));

		 //console.log($(this).val());

		 // $.each($(this).find("input, select"), function(i, input) {
		 // 			//console.log($(this));

		 // 	console.log(input.id + "=======" + input.value);
		 // 	if (input.type == "checkbox") {
		 // 		//iwpgvChart.setOption(input.id, input.checked);
		 // 	} else if (input.id == "title"){
		 // 		if ($("#" + templateId + " #chartType").val() == "Line"){
		 // 					 			console.log("sssssssssssssssss");

		 // 			iwpgvChart.setOption("chart", { "title": input.value} );
		 // 		} else {
		 // 			console.log("FOOOOOOOFFFFFF");
		 // 			console.log(input.value);
		 // 			iwpgvChart.setOption("title", input.value );
		 // 		}
		 // 	}  else if (input.id == "title.position") {

		 // 	} else {

		 // 		iwpgvChart.setOption(input.id, input.value );
		 // 		//iwpgvChart.setOption("legend.position", "left" );
		 // 	}




		 // });
		  	if ($(this).attr("type") == "checkbox") {
		 		iwpgvChart.setOption($(this).attr("id"), $(this).prop("checked"));
		 	} else {
		  		iwpgvChart.setOption( $(this).attr("id"), $(this).val());
		  	}
		  // {
		  // 	"title": "OOOOOOOOOO",
		  // 	"title.color": "red"
		  // });

		  iwpgvChart.draw();

		// // Disable save button if no file is selected
		// if (! fileSelect.val() || ! $("#" + templateId + " select#sheet").val() || ! $("#" + templateId + " select#chartType").val()) {
		// 	$("#" + templateId + " #saveChart").attr("disabled", true);
		// } else {
		// 	$("#" + templateId + " #saveChart").attr("disabled", false);

		// }

		// if ($(this).attr("id") == "title") {
		 	//iwpgvChart.setOption($(this).attr("id"),"PPPPPPPPP"  );

		 	//iwpgvChart.setOption("titleTextStyle.color", "blue"  );

		 	//iwpgvChart.setOption("titleTextStyle.fontSize", 30  );
		// } else {

		// 	var id = $(this).attr("id");
		// 	var val =  $(this).val();
		// 	iwpgvChart.setOption("title", {id:val}  );
		// }

			// if ( $(this).attr("id") == "chartTitle"){

			// 	iwpgvChart.setOption("title", $(this).val()  );
			// 	//iwpgvChart.setOption("chart",  {"title": $(this).val()}  );
			// } else if ( $(this).attr("id") == "chartSubtitle"){
			// 	iwpgvChart.setOption("chart",  {"subtitle": $(this).val()}  );
			// }	else {
			// 	iwpgvChart.setOption($(this).attr("id"), $(this).val()  );
			// }

		//  //// Remove user-notice html text
		// adminMessageDiv.html( "" );
		//  	 		//console.log($(this).find("input, select, checkbox");

 	//  		//iwpgvChart.setOption( "chart", {"title": $("#" + templateId + " #chartTitle") });
 	 		
  //         //programmaticChart.draw();
  //         var xx ={};
  //         $.each ($(this).find("input, select, checkbox"), function (i, obj) {
  //         	console.log(obj.id);
  //         	if (obj.id == "chartTitle") iwpgvChart.setOption("chart",  {"chartTitle": obj.value}  );
  //         	//iwpgvChart.setOption( "'"+obj.id +"'", obj.value  );
  //         });


  //        var options =  lineChartDefaultOptions(xx);
  //        console.log(options);
  //        //iwpgvChart.setOption( chart, {"title": $("#" + templateId + " #chartTitle").val( )  });
         //iwpgvChart.setOption( "chart", {"subtitle": $("#" + templateId + " #chartTitle").val( ) });

         

		// Remove user-notice html text
		//console.log($("#" + templateId + " #inputRangeMin").val());
		//console.log(numRangeSlider);
		//
		// var minInputVal = $("#" + templateId + " input#inputRangeMin").val();
		// var maxInputVal = $("#" + templateId + " input#inputRangeMax").val();

		// if (! minInputVal) numRangeSlider.getState().lowValue
		// if (! maxInputVal) numRangeSlider.getState().highValue

		// if(
		// 	isNaN(minInputVal) || minInputVal < numRangeSlider.getState().lowValue || minInputVal > numRangeSlider.getState().highValue || isNaN(maxInputVal) || maxInputVal < numRangeSlider.getState().lowValue || maxInputVal > numRangeSlider.getState().highValue ) {
		// 	var message = "Please enter a number between" + numRangeSlider.getState().lowValue + " and " + numRangeSlider.getState().highValue 
		// 	adminMessageDiv.html(displayMessage(message, null));

		// } else {
 	// 		numRangeSlider.setState({'lowValue': minInputVal, 'highValue': maxInputVal});
 	// 		numRangeSlider.draw();
 	// 		console.log("PPPPPPPZZ");
 	// 	}
		//
		//

	 //   // If no file selected 
	 //   if (! fileSelect.val()) {
	 //   	adminMessageDiv.html(displayMessage("Please select a file", null));

	 //   	// Show sheet selection field
	 //   	sheetSelect.hide();
	   
	 //   // If a file is selected
	 //  	} else {

	 //   	//	// Remove existing sheet selction options.
	 //   	 $.each($("#" + templateId + " select#sheet option"), function(i, obj) {
	 //    		if (obj.value !== "") {
	 //    			obj.remove();
	 //    		}
	 //    	});

	 //   	// append file id to form
	 //   	formData.append("fileId", fileSelect.val() );

		// 	// Append action hook to the form
		// 	formData.append("action", iwpgv_object.file_select_action);
		
		// 	// Append nonce to the form
		// 	formData.append("nonce", iwpgv_object.file_select_nonce);

		// 	// upload files
		// 	ajaxRequest(formData);
		// }

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
	function fileSelectRender(data) {

		//console.log("File Select Render");

		// Display error message
		adminMessageDiv.append(data.message) ;
		
		// Append sheet selection options to the sheet select field
		sheetSelect.append(data.sheetSelectOptions);

	// Show sheet selection field
	   sheetSelect.show();


		$("#" + templateId + " #chartSettings").show();	

	   // Reset chart type select
		$("#" + templateId + " select#fchartType").val("");

	  
	} // END  function fileSelectRender() {





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
	 * Renders file upload
	 * @param  {array} data array of chart data and error/success messages
	 * @return {}      
	 */
	function refreshChart(response) {

		console.log("Refresh Chart");
		console.log(response);

		// Display error message
		adminMessageDiv.append(response.message ) ;


		$.each(response.uniqueFields, function(fieldId, chartTypes) {
			console.log("#" + templateId + " #"+ fieldId);
			if (! chartTypes.includes(response.post.chartType)) $("input[id='"+ fieldId+ "'").attr("disabled", true).parent(".fieldWrapper").hide();
		});

		// Render sheet
		renderChart(response.sheet, response.divId, response.post.chartType, null);


	} // END  function fileUpload() {







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
				// options:  {
				// 	height: 400,
				// 	page: "enable",
				// 	pageSize: 5,
				// 	//pagingButtons: "auto",
				// 	width: "100%"
				// }
			
			 });

			// Establish dependencies, declaring what filter drives the chart, table
			dashboard.bind(numRangeSlider, iwpgvChart).bind(numRangeSlider, iwpgvTable);

	// $.each(pieChartDefaultOptions(), function(i, option) {
		 	//console.log(i);
		 //	iwpgvChart.setOption(i, option );
		// });

			// Draw the dashboard.
			dashboard.draw(data);


			// var options = {};
			//  $.each(chartOptions, function(fieldId, option) {
			// 	options[fieldId] = option;

		 // 	// //console.log(fieldId + "=======" + option);
			//  // 	//if (input.type == "checkbox") {
			//  // 	//
			//  // 	if (fieldId == "title" && chartOptions.chartType == "Line"){
			//  // 		iwpgvChart.setOption("chart", { "title": option, "subtitle" : chartOptions.subtitle} );
			//  // 	} 	else if (fieldId.indexOf("bold") > 0) {
		 // 	// 		if(option) {
		 // 	// 			iwpgvChart.setOption(fieldId, true);
		 // 	// 		} else {
			// 	// 		iwpgvChart.setOption(fieldId, false);	
			// 	// 	}		 	//} else if (fieldId == "subtitle" && chartOptions.chartType == "Line"){
			//  // 		//iwpgvChart.setOption("chart", { "subtitle": option} );
			//  // 	} else {

			//  // 		iwpgvChart.setOption(fieldId, option);
			//  // 	//} else if (input.id == "title" && $("#" + templateId + " #chartType").val() == "Line"){
			//  // 		//iwpgvChart.setOption("chart", { "title": input.value} );
			//  // 	//} else {

			//  // 		//iwpgvChart.setOption(input.id, input.value );
			//  // 		//iwpgvChart.setOption("legend.position", "left" );
			//  // 	}


		 // });

			//  console.log(options);
			//iwpgvChart.setOptions(chartOptions);
		  //iwpgvChart.draw();

					  //iwpgvChart.draw();


			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addListener(iwpgvChart, 'error', chartErrorHandler );

			// Listen for the rangeFilter change, then run MyReadyHandler
			google.visualization.events.addListener(dashboard, 'error', dashboardErrorHandler );

		


			// Listen for the rangeFilter change, then run MyReadyHandler
			//google.visualization.events.addListener(iwpgvChart, 'ready', chartReadyHandler );







			//var minAvgMaxTable = new google.visualization.Table(document.getElementById('min-max-'+divId+'-div'));




			

			// Listen for the rangeFilter change, then run MyReadyHandler
			//google.visualization.events.addListener(dashboard, 'ready', dashboardReadyHandler );

			// Listen for the rangeFilter change, then run MyReadyHandler
			//google.visualization.events.addListener(dashboard, 'error', chartErrorHandler );

			
		

			

		// 	// Listen for the rangeFilter change, then run MyReadyHandler
		// 	google.visualization.events.addListener(numRangeSlider, 'statechange', function() {

		// 		var rangeMin = numRangeSlider.getState().lowValue;
		// 		var rangeMax = numRangeSlider.getState().highValue;
		// 		console.log(rangeMin);


		// 		var newRows = data.getFilteredRows([{column: 0, minValue: rangeMin, maxValue:rangeMax }])
		// 		var newData = new google.visualization.DataTable();

		// 		var newData = [];
		// 		$.each(newRows, function (i, newRowIndex) {

		// 			var temp = [];
		// 			for (var k = 0; k < data.getNumberOfColumns(); k++) {
  // 						temp[k] = data.getValue(newRowIndex, k)
		// 			}

		// 			newData[i] = temp;

		// 		});

		// 			var minAvgMax = google.visualization.arrayToDataTable(getMinMaxAvg(newData, data.vg));


  //       			minAvgMaxTable.draw(
  //       				minAvgMax, 
  //       				{
  //       					height: "100%",
		// 					//page: "enable",
		// 					//pageSize: 5,
		// 					//pagingButtons: "auto",
		// 					width: "100%"
		// 				}
		// 			);


		// // 		 var minAvgMax = [['Column','min','Average', 'Max']];

		// // 		$.each(dataMins, function(i, mins) {
		// // 	if ( i != 0) minAvgMax.push([dataMins[i].label, dataMins[i].data, dataAvgs[i].data, dataMaxs[i].data]);
		// // });

		// // return minAvgMax;


		// 							//console.log(newData);


		// 						//console.log(data.getFilteredRows([{column: 0, minValue: rangeMin, maxValue:rangeMax }]));



		// 	});

	


		// 	var newData = [];
		// 		for (var i = 0; i < data.getNumberOfRows(); i++) {
		// 			var col = [];
		// 			for (var j = 0; j < data.getNumberOfColumns(); j++) {
  // 						col[j] = data.getValue(i, j);
		// 			}

		// 			newData[i] = col;

		// 		}		
			

		// 	var minAvgMax = google.visualization.arrayToDataTable(getMinMaxAvg(newData, data.vg));


  //       minAvgMaxTable.draw(
  //       	minAvgMax, 
  //       	{
  //       					height: "100%",
		// 					//page: "enable",
		// 					//pageSize: 5,
		// 					//pagingButtons: "auto",
		// 					width: "100%"
		// 				}
  //       );



		}   // END  function drawDashboard()


	} // END function renderChart(data, divId) {



	function dashboardErrorHandler(error) {

		//console.log(error);

		// Display error message
		//$("#" + templateId + " .gvErrorMessages").append(displayMessage(error.message) );

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}


	// function chartReadyHandler() {

		
	// 	 console.log("Chart Ready Handler");


	// 	 $.each(pieChartDefaultOptions(), function(i, option) {
	// 	 	console.log(i);
	// 	 	iwpgvChart.setOption(i, option );
	// 	 });

	// 	  iwpgvChart.draw();

	// 	// Display error message
	// 	//$("#" + templateId + " .gvErrorMessages").append(displayMessage(error.message) );

	// 	// Remove google default error
	// 	//google.visualization.errors.removeError(error.id);

	// }


	function chartErrorHandler(error) {

		//console.log(error);

		// Display error message
		$("#" + templateId + " .gvErrorMessages").append(displayMessage(error.message) );

		// Remove google default error
		google.visualization.errors.removeError(error.id);

	}





	 // $.each(pieChartDefaultOptions(), function(i, option) {
		//  	console.log(i);
		//  	iwpgvChart.setOption(i, option );
		//  });

		// 	// Draw the dashboard.

		// 			  iwpgvChart.draw();






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

			//iwpgvChart = new google.visualization.LineChart(document.getElementById('chart-'+divId+'-div'));
        //iwpgvChart.draw(data, chartOptions);

			// Instantiate a dashboard object.
			//dashboard = new google.visualization.Dashboard(document.getElementById('dashboard-'+divId+'-div'));

			// // Create a range slider, passing some options
			// numRangeSlider = new google.visualization.ControlWrapper({
			// 	'controlType': 'NumberRangeFilter',
			// 	'containerId': 'filter-'+divId+'-div',
			// 	'options': {
			// 		'filterColumnLabel': filterColumnLabel,
			// 		//minValue: 4000,
			// 		//maxValue: 15000,
			// 		'ui':{
			// 			//'ticks': 8000,
			// 			'labelStacking' : 'vertical'
			// 		}
			// 	},
			// 	'state': {
			// 		//lowValue: 8000,
			// 		//highValue:14000,
			// 	}
			// });
			console.log(chartOptions);
			
			//Create chart, passing some options
			iwpgvChart = new google.visualization.ChartWrapper({
				chartType: chartType,
				containerId: 'chart-'+divId+'-div',
				dataTable: data,
				 //options: chartOptions
			
			 });


			// //Create a table chart, passing some options
			// iwpgvTable = new google.visualization.ChartWrapper({
			// 	chartType: 'Table',
			// 	containerId: 'table-'+divId+'-div',
			// 	options:  {
			// 		height: 400,
			// 		page: "enable",
			// 		pageSize: 5,
			// 		//pagingButtons: "auto",
			// 		width: "100%"
			// 	}
			
			//  });

			// Establish dependencies, declaring what filter drives the chart, table
			//dashboard.bind(null, iwpgvChart); //.bind(numRangeSlider, iwpgvTable);

			// Draw the dashboard.
			iwpgvChart.draw();

			$.each(chartOptions, function (fieldId, option) {

				iwpgvChart.setOption(fieldId, option);

			});

		

			iwpgvChart.draw();







			//var minAvgMaxTable = new google.visualization.Table(document.getElementById('min-max-'+divId+'-div'));




			

			// Listen for the rangeFilter change, then run MyReadyHandler
			//google.visualization.events.addListener(dashboard, 'ready', dashboardReadyHandler );

			// Listen for the rangeFilter change, then run MyReadyHandler
			//google.visualization.events.addListener(dashboard, 'error', chartErrorHandler );

			
		

			

		// 	// Listen for the rangeFilter change, then run MyReadyHandler
		// 	google.visualization.events.addListener(numRangeSlider, 'statechange', function() {

		// 		var rangeMin = numRangeSlider.getState().lowValue;
		// 		var rangeMax = numRangeSlider.getState().highValue;
		// 		console.log(rangeMin);


		// 		var newRows = data.getFilteredRows([{column: 0, minValue: rangeMin, maxValue:rangeMax }])
		// 		var newData = new google.visualization.DataTable();

		// 		var newData = [];
		// 		$.each(newRows, function (i, newRowIndex) {

		// 			var temp = [];
		// 			for (var k = 0; k < data.getNumberOfColumns(); k++) {
  // 						temp[k] = data.getValue(newRowIndex, k)
		// 			}

		// 			newData[i] = temp;

		// 		});

		// 			var minAvgMax = google.visualization.arrayToDataTable(getMinMaxAvg(newData, data.vg));


  //       			minAvgMaxTable.draw(
  //       				minAvgMax, 
  //       				{
  //       					height: "100%",
		// 					//page: "enable",
		// 					//pageSize: 5,
		// 					//pagingButtons: "auto",
		// 					width: "100%"
		// 				}
		// 			);


		// // 		 var minAvgMax = [['Column','min','Average', 'Max']];

		// // 		$.each(dataMins, function(i, mins) {
		// // 	if ( i != 0) minAvgMax.push([dataMins[i].label, dataMins[i].data, dataAvgs[i].data, dataMaxs[i].data]);
		// // });

		// // return minAvgMax;


		// 							//console.log(newData);


		// 						//console.log(data.getFilteredRows([{column: 0, minValue: rangeMin, maxValue:rangeMax }]));



		// 	});

	


		// 	var newData = [];
		// 		for (var i = 0; i < data.getNumberOfRows(); i++) {
		// 			var col = [];
		// 			for (var j = 0; j < data.getNumberOfColumns(); j++) {
  // 						col[j] = data.getValue(i, j);
		// 			}

		// 			newData[i] = col;

		// 		}		
			

		// 	var minAvgMax = google.visualization.arrayToDataTable(getMinMaxAvg(newData, data.vg));


  //       minAvgMaxTable.draw(
  //       	minAvgMax, 
  //       	{
  //       					height: "100%",
		// 					//page: "enable",
		// 					//pageSize: 5,
		// 					//pagingButtons: "auto",
		// 					width: "100%"
		// 				}
  //       );



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



	


	function defaultChartOptions () {
		
		options = {

			chartArea: {
         	backgroundColor: {
         		stroke: "green",
         		strokeWidth: 6
         	},
         	left:50,
         	top:50,
         	width:'100%',
         	height:'100%'
         },

          legend: {
         	position: 'right',
         	textStyle: {
         		color: 'blue', 
         		fontSize: 16,
         		fontName: "couurier",
         		bold: true,
         		italic: true
         	},
         	alignment: "center",
         	maxLines: 3	
         },

          backgroundColor : {
         	fill: "transparent",
         	stroke: "red",
         	strokeWidth: 5
         },


         fontSize: 24,
         fontName: "arial",

    };

		return options;

	}






	function lineChartDefaultOptions (obj) {

		//console.log(obj);
		
		// var options = 

	 // 	 	`chart, {
	 // 	 		title: ` + obj.chartTitle + `,
	 // 	 		subTitle: `+ obj.chartSubTitle+ `
	 // 	 	}`

         // titleTextStyle: {
         // 	color: obj.chartTitleColor, 
         // 	fontSize: obj.chartTitlefontSize,
         // 	fontName: obj.chartTitleFontName,
         // 	bold: obj.chartTitleBold,
         // 	italic: obj.chartTitleItalic
         // },

    //;

		return options;
	}






	function pieChartDefaultOptions () {
		
		var options = {

	 	 	"title": 'My Daily Activities',
         "titleTextStyle.color" : 'blue',
         "titleTextStyle.fontSize" : 16, 
         // 	// fontSize: 16,
         // 	// fontName: "couurier",
         // 	// bold: true,
         // 	// italic: true
         // },

         // pieSliceBorderColor : "teal",
         // pieSliceTextStyle :{
         // 	color: "brown",
         // 	fontName: "courier",
         // 	fontSize: 9
         // },
         //  width: "400",
         // height: "400",


         // pieSliceTex: 'value',
         // is3D: false, // either pie hole or is3D
         // pieHole: 0.1,  // You can't combine the pieHole and is3D options; if you do, pieHole will be ignored.
        	// pieSliceText: 'label',
        	// pieStartAngle: 100,
        	// slices: {
        	// 	0: {offset: 0.2, color: "transparent"},
         //    1: {offset: 0.5},
         // },
         // sliceVisibilityThreshold: null,

    };

		return options;
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


