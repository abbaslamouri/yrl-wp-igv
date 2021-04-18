jQuery(document).ready(function($) {

	
	"use strict";

 	google.charts.load('current', {'packages':['corechart', 'table', 'controls']});


	// Show animation
	$("#iwpgv-dashboard .admin-ajax-loading").show();
	
	// Remove user-notice html text
	$('#iwpgv-dashboard .admin-messages').html( "" );


	//Initiate a new form
	var formData = new FormData();

	// Retreive form inputs
	formData.append("chart-type", $("#iwpgv-dashboard #chart-type").val()); 
	formData.append("chart-notes", $("#iwpgv-dashboard #chart-notes").val());
	formData.append("created-date", $("#iwpgv-dashboard #created-date").val()); 
	formData.append("updated-date", $("#iwpgv-dashboard #updated-date").val()); 
	formData.append("file-upload-submit", $("#iwpgv-dashboard #file-upload-submit").val()); 

	// Append action hook to the form
	formData.append("action",  iwpgv_chart_list_object.action);
	
	// Append nonce to the form
	formData.append("nonce", iwpgv_chart_list_object.nonce);


	$.ajax ({
	 	type: "POST",
	 	url: iwpgv_chart_list_object.url, 
	 	data: formData,
	 	contentType: false,
	 	processData: false,
		//dataType: "json",
		success: function( response, status, jqXHR ) {

			// Parse json
			var allData = JSON.parse(response);

			console.log(allData);


			/********************************************* Test file graph****************************/

			// All the sheet data is return in allData (1st rows contains the column labels and the second row conatians the data types)
			var data = allData[0].data; //data formated for plotting in the google visualization
			var labelRow = data.cols; // column labels
			var rows = data.rows; // Data without the column labels used to extract the min, max and average

			//Retreive the number of rows and coloumns
			var colCount = labelRow.length;
			var rowCount = rows.length;

			// Set the filter column for Google Visualization donutRangeSlider ControlWrapper
			var xAxisLabel = labelRow[0].label;

			// Set a callback to run when the Google Visualization API is loaded.
			google.charts.setOnLoadCallback(drawDashboard);

			function drawDashboard() {
				
				// Create a dashboard.
				var dashboard = new google.visualization.Dashboard(
					document.getElementById('dashboard-new-div'));

				// Create a range slider, passing some options
				var donutRangeSlider = new google.visualization.ControlWrapper({
					'controlType': 'NumberRangeFilter',
					'containerId': 'filter-new-div',
					'options': {
						'filterColumnLabel': xAxisLabel,
						'ui':{
							//'ticks': 8000,
							'labelStacking' : 'vertical'
						}
					}
				});

				// Create a line chart, passing some options
				var lineChart = new google.visualization.ChartWrapper({
					chartType: 'LineChart',
					containerId: 'new-chart-div',
					options: {
						title: '',
						curveType: 'function',
						width: 1000,
						height: 600,
						legend: 'bottom',
						//majorTicks : 5,
						hAxis : {
							//minValue : 2000,
							//maxValue : 20000,
							title : xAxisLabel,
							gridlines :{count :10},
							ticks: [2000,4000,6000,8000,10000,12000,14000,16000,18000,20000],
						},
						
						vAxis : {
							minValue : 0,
							maxValue : 100,
							title : '' ,
							gridlines : {count :10},
							ticks: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
						},
						backgroundColor: 'white'
					}
				});

				// Bind dashboard
				dashboard.bind(donutRangeSlider, lineChart);
				
				// Draw the dashboard.
				dashboard.draw(data);

				// Listen for the rangeFilter change, then run MyReadyHandler
				//google.visualization.events.addListener(dashboard, 'ready', myReadyHandler);

			}   // END  function drawDashboard()


			/********************************************* All graphs in the database****************************/

			// Loop through spreadsheet
			$.each(allData[1], function(sheetID, sheetData) {

				//  All the sheet data is return in allData (1st rows contains the column labels and the second row conatians the data types)
				var data = sheetData.data; //data formated for plotting in the google visualization
				var labelRow = data.cols; // column labels
				var rows = data.rows; // Data without the column labels used to extract the min, max and average

				//Retreive the number of rows and coloumns
				var colCount = labelRow.length;
				var rowCount = rows.length;

				// Set the filter column for Google Visualization donutRangeSlider ControlWrapper
				var xAxisLabel = labelRow[0].label;

				// Set a callback to run when the Google Visualization API is loaded.
				google.charts.setOnLoadCallback(drawDashboard);

				function drawDashboard() {
					
					// Create a dashboard.
					var dashboard = new google.visualization.Dashboard(
						document.getElementById('dashboard-'+sheetID+'-div'));

					// Create a range slider, passing some options
					var donutRangeSlider = new google.visualization.ControlWrapper({
						'controlType': 'NumberRangeFilter',
						'containerId': 'filter-'+sheetID+'-div',
						'options': {
							'filterColumnLabel': xAxisLabel,
							'ui':{
								//'ticks': 8000,
								'labelStacking' : 'vertical'
							}
						}
					});

					// Create a line chart, passing some options
					var lineChart = new google.visualization.ChartWrapper({
						chartType: 'LineChart',
						containerId: 'chart-list-'+sheetID+'-div',
						options: {
							title: '',
							curveType: 'function',
							width: 350,
							height: 300,
							legend: 'bottom',
							//majorTicks : 5,
							hAxis : {
								//minValue : 2000,
								//maxValue : 20000,
								title : xAxisLabel,
								gridlines :{count :10},
								ticks: [2,4,6,8,10,12,14,16,18,20],
							},
							
							vAxis : {
								minValue : 0,
								maxValue : 100,
								title : '' ,
								gridlines : {count :10},
								ticks: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
							}
						}
					});

					// Bind dashboard
					dashboard.bind(donutRangeSlider, lineChart);
					
					// Draw the dashboard.
					dashboard.draw(data);

					// Listen for the rangeFilter change, then run MyReadyHandler
					//google.visualization.events.addListener(dashboard, 'ready', myReadyHandler);

				}   // END  function drawDashboard()

	 		}); // END $.each(allData, function(sheetID, sheetData)

			$("#iwpgv-dashboard .admin-ajax-loading").hide();         

		} // END success: function( response, status, jqXHR ) {


	}); // END $.ajax ({


});  // END jQuery(document).ready(function($) {


