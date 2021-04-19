import ChartDefault from "./ChartDefault"


class ChartTrace{

  constructor(trace, index, iwpgvObj, iwpgvCharts, jsonRes) {

    // super(chartTypes, colors, pointShapes, lineWidth, markerSize, fontNames);

    this.trace = trace;
    this.spreadsheet = jsonRes.spreadsheet
    this.chart = iwpgvCharts.chart
    this.labels = Object.values(this.spreadsheet[this.chart.chartParams.sheetId]["labels"]);
    this.index = index;
    this.chartType = this.chart.chartParams.chartType;

    switch (this.chartType) {
      case "LineChart":
      case "ScatterChart":
        this.type = "scatter";
        this.mode = "lines+markers";
        break;
      default:
        this.type = null;
        this.mode = null;   
    }

    this.prefix = iwpgvObj.prefix

    this.colors = [ 								// Possible chart colors
			"#D32F2F", "#FF5733", "#536DFE", "#F9A825", "#558B2F", '#1976D2', '#00796B', '#581845', '#455A64', '#263238', '#303F9F', '#33691E', '#7B1FA2', '#0097A7', '#EF6C00', '#795548', '#FFA000'
		]
   
  }

  options() {

    return {

      'type' : ( typeof ( this.trace['type'] ) !== "undefined" ) ? this.trace['type'] :  this.type ,
      'mode' : typeof ( this.trace['mode'] ) !== "undefined" ? this.trace['mode'] : this.mode,
      'name' : this.labels[ this.index],
      'x' : this.spreadsheet[this.chart.chartParams.sheetId]["data"][0],
      'y' : this.spreadsheet[this.chart.chartParams.sheetId]["data"][this.index],
      
      'connectgaps' : typeof ( this.trace['connectgaps'] ) !== "undefined" ?  this.trace['connectgaps'] : false,
      'line' : {
        'color' : ( typeof ( this.trace['line'] ) !== "undefined" && typeof ( this.trace['line']['color'] ) !== "undefined" ) ? this.trace['line']['color'] : this.colors[this.index],
        'width': ( typeof ( this.trace['line'] ) !== "undefined" && typeof ( this.trace['line']['width'] ) !== "undefined" ) ? this.trace['line']['width'] : 2
      },
      'marker' : {
        'color' : ( typeof ( this.trace['marker'] ) !== "undefined" && typeof ( this.trace['marker']['color'] ) !== "undefined" ) ? this.trace['marker']['color'] : this.colors[this.index],
        'size': ( typeof ( this.trace['marker'] ) !== "undefined" && typeof ( this.trace['marker']['size'] ) !== "undefined" ) ? this.trace['marker']['size'] : 10
      },

    }

  }


  panel() {

    return [
      [
        {
          "id" : `chartTrace[${this.index}][name]`,  
          "title" : "Label in Legend",  
          "type" : "text",
          'value' : this.options()['name'],
          "hint" : "The trace name appear as the legend item and on hover."
        },
        {
          "id" : `chartTrace[${this.index}][mode]`, 
          "title" : "Mode", 	
          "type" : "select", 
          "options" : {
            "none" : "None",
            "markers" : "Markers",
            "lines" : "LInes",
            "lines+markers" : "LInes & Markers"
          },
          'value' :  this.options()['mode'],
          "hint" : "Determines the drawing mode for this scatter trace. If the provided `mode` includes 'text' then the `text` elements appear at the coordinates. Otherwise, the `text` elements appear on hover. If there are less than 20 points and the trace is not stacked then the default is 'lines+markers'. Otherwise, 'lines'."
        },
      ]
    ]
  }

}

export default ChartTrace;