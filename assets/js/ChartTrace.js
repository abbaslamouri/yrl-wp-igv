import ChartDefault from "./ChartDefault"


class ChartTrace extends ChartDefault {

  constructor(trace, index, iwpgvObj, chart, spreadsheet, colors) {

    super(colors);

    this.trace = trace;
    this.spreadsheet = spreadsheet
    this.chart = chart
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
   
  }

  options() {

    return {

      // type : ( this.trace !== undefined &&  this.trace.type !== undefined ) ? this.trace.type :  this.type ,
      mode : ( this.trace !== undefined && this.trace.mode !== undefined ) ? this.trace.mode : this.mode,
      name : this.labels[ this.index],
      visible : ( this.trace !== undefined && this.trace.visible !== undefined ) ? this.trace.visible : "legendonly",
      // showlegend : ( this.trace !== undefined && this.trace.showlegend !== undefined ) ? this.trace.showlegend : true,
      // opacity : ( this.trace !== undefined && this.trace.opacity !== undefined ) ? this.trace.opacity : 0.5,
      x : this.spreadsheet[this.chart.chartParams.sheetId].data[0],
      y : this.spreadsheet[this.chart.chartParams.sheetId].data[this.index],
      
      // connectgaps : ( this.trace !== undefined &&  this.trace.connectgaps !== undefined ) ?  this.trace.connectgaps : false,
      line : {
        color : ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.color !== undefined ) ? this.trace.line.color : this.colors[this.index],
        width: ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.width !== undefined ) ? this.trace.line.width : 2
      },
      marker : {
        color : ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.color !== undefined ) ? this.trace.marker.color : this.colors[this.index],
        size: ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.size  !== undefined ) ? this.trace.marker.size : 5
      },

    }

  }


  panel() {

    return [
      [
        {
          id : `chartTraces[${this.index-1}][name]`,  
          title : "Label in Legend",  
          type : "text",
          value : this.options()['name'],
          hint : "The trace name appear as the legend item and on hover."
        },
        {
          id : `chartTraces[${this.index-1}][mode]`, 
          title : "Mode", 	
          type : "select", 
          options : {
            none : "None",
            markers : "Markers",
            lines : "LInes",
            "lines+markers" : "LInes & Markers"
          },
          value :  this.options()['mode'],
          hint : "Determines the drawing mode for this scatter trace. If the provided `mode` includes 'text' then the `text` elements appear at the coordinates. Otherwise, the `text` elements appear on hover. If there are less than 20 points and the trace is not stacked then the default is 'lines+markers'. Otherwise, 'lines'."
        },
      ],
      [
        {
          id : `chartTraces[${this.index-1}][line][color]`,  
          title : "Trace Line Color",  
          type : "color",
          value : this.options().line.color,
          hint : ""
        },
        {
          id : `chartTraces[${this.index-1}][line][width]`, 
          title : "Trace Line Width", 	
          type : "number",
          min : 1,
          max : 2000,
          step : 1,
          value :  this.options().line.width,
          hint : ""
        },
      ],
      [
        {
          id : `chartTraces[${this.index-1}][marker][color]`,  
          title : "Marker Color",  
          type : "color",
          value : this.options().marker.color,
          hint : ""
        },
        {
          id : `chartTraces[${this.index-1}][marker][size]`, 
          title : "Marker Size", 	
          type : "number",
          min : 1,
          max : 2000,
          step : 1,
          value :  this.options().marker.size,
          hint : ""
        },
      ],
      [
        {
          id : `chartTraces[${this.index-1}][visible]`,  
          title : "Trace Visible",  
          type : "select",
          options : {
            true : true,
            false : false,
            legendonly : "Legend Only",
          },
          value : this.options().visible,
          hint : "Determines whether or not this trace is visible. If 'legendonly', the trace is not drawn, but can appear as a legend item (provided that the legend itself is visible)."
        },
        {
          id : `chartTraces[${this.index-1}][opacity]`, 
          title : "Trace Opacity", 	
          type : "number",
          min : 0.02,
          max : 1,
          step : 0.02,
          value :  this.options().opacity,
          hint : "Sets the opacity of the trace."
        },
        {
          id : `chartTraces[${this.index-1}][showlegend]`, 
          title : "Show Legend", 	
          type : "checkbox",
          value :  this.options().showlegend,
          hint : "Determines whether or not an item corresponding to this trace is shown in the legend."
        },
      ]
    ]
  }

}

export default ChartTrace;