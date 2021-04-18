class ChartDefault {

  constructor() {

    this.chartTypes = {						// All chart types supported by this plugin
      "" 						: "Select Chart Type",
			"LineChart" 	: "Line Chart",
			"ScatterChart" : "Scatter Chart",
			"BarChart" 		: "Bar Chart",
			"ColumnChart" : "Column Chart",
			"PieChart" 		: "Pie Chart",
    }

    this.colors = [ 								// Possible chart colors
			"#D32F2F", "#FF5733", "#536DFE", "#F9A825", "#558B2F", '#1976D2', '#00796B', '#581845', '#455A64', '#263238', '#303F9F', '#33691E', '#7B1FA2', '#0097A7', '#EF6C00', '#795548', '#FFA000'
		]
      
    this.pointShapes = [		// Possible chart point types
			'circle', 'square', 'triangle', 'diamond', 'star', 'polygon','circle', 'square', 'triangle', 'diamond', 'star', 'polygon'
		]

		this.lineWidth = 5
		this.markerSize = 16


    this.fontNames = {					// Chart possible fonts
      "arial": "Arial",
      'Times-Roman': "Times Roman",
      "courier": "Courier",
      "verdana": "Verdana",
      "georgia": " Georgia",
      "tahoma": "Tahoma"
    }

   
  }

  
  

}

export default ChartDefault;