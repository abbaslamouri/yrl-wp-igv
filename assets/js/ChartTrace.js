import ChartDefault from "./ChartDefault"


class ChartTrace extends ChartDefault {

  constructor(trace, spreadsheet, index, sheetId, chartType, iwpgvObj, colors) {

    // console.log(trace)
    // return

    super(colors);

    this.trace = trace;
    this.spreadsheet = spreadsheet
    // this.chart = chart
    this.sheetId= sheetId
    this.chartType= chartType
    this.labels = Object.values(this.spreadsheet[this.sheetId]["labels"]);
    this.index = index;
    // this.chartType = this.chart.chartParams.chartType;

    switch (this.chartType) {
      case "LineChart":
      case "ScatterChart":
        this.type = "scatter";
        this.mode = "lines";
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
      visible : ( this.trace !== undefined && this.trace.visible !== undefined ) ? this.trace.visible : true,
      showlegend : ( this.trace !== undefined && this.trace.showlegend !== undefined ) ? this.trace.showlegend : true,
      opacity : ( this.trace !== undefined && this.trace.opacity !== undefined ) ? this.trace.opacity : 1,
      x : this.spreadsheet[this.sheetId].data[0],
      y : this.spreadsheet[this.sheetId].data[this.index],
      
      // connectgaps : ( this.trace !== undefined &&  this.trace.connectgaps !== undefined ) ?  this.trace.connectgaps : false,
      line : {
        color : ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.color !== undefined ) ? this.trace.line.color : this.colors[this.index],
        width: ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.width !== undefined ) ? this.trace.line.width : 3,
        shape: ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.shape !== undefined ) ? this.trace.line.shape : "spline",
        smoothing: ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.smoothing !== undefined ) ? this.trace.line.smoothing : 1,
        dash: ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.dash !== undefined ) ? this.trace.line.dash : "solid",
        simplify: ( this.trace !== undefined && this.trace.line !== undefined && this.trace.line.simplify !== undefined ) ? this.trace.line.simplify : true,
      },
      marker : {
        symbol : ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.symbol !== undefined ) ? this.trace.marker.symbol : 1,
        maxdisplayed : ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.maxdisplayed !== undefined ) ? this.trace.marker.maxdisplayed : 10,
        color : ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.color !== undefined ) ? this.trace.marker.color : this.colors[this.index],
        size: ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.size !== undefined ) ? this.trace.marker.size : 5,
        line: {
          width: ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.line !== undefined && this.trace.marker.line.width !== undefined ) ? this.trace.marker.line.width : 1,
          color: ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.line !== undefined && this.trace.marker.line.color !== undefined ) ? this.trace.marker.line.color : "#4A148C",
        },
        gradient: {
          type: ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.gradient !== undefined && this.trace.marker.gradient.type !== undefined ) ? this.trace.marker.gradient.type : "radial",
          color: ( this.trace !== undefined &&  this.trace.marker !== undefined && this.trace.marker.gradient !== undefined && this.trace.marker.gradient.color !== undefined ) ? this.trace.marker.gradient.color : "#E65100",
        }
      },

    }

  }


  sections() {

    return {
      // [`trace_${this.index-1}`]: {
        id : `${this.prefix}__chartTracesPanel__trace[${this.index-1}]`,
        cssClasses:["chartTraces", "subPanel"],
        title : (this.spreadsheet[this.sheetId].labels.length) > 1 ? Object.values(this.spreadsheet[this.sheetId].labels)[this.index] : "",
        intro : "general gfhfhjfj jsdkjfsdjkdfsjk lkdkklsd",
        fields : [
          [
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
            {
              id : `chartTraces[${this.index-1}][name]`,  
              title : "Label in Legend",  
              type : "text",
              value : this.options()['name'],
              hint : "The trace name appear as the legend item and on hover."
            },
            
          ],
          [
            {
              id : `chartTraces[${this.index-1}][line][shape]`,  
              title : "Trace Line Shape",  
              type : "select",
              options : {
                linear : "Linear",
                spline : "Spline",
                hv : "Horizontal/Vertical",
                vh: "Vertical/Horizontal",
                hvh: "Horizontal/Vertical/Horizontal",
                vhv: "Vertical/Horizontal/Vertical"
              },
              value : this.options().line.shape,
              hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
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
              id : `chartTraces[${this.index-1}][line][color]`,  
              title : "Trace Line Color",  
              type : "color",
              value : this.options().line.color,
              hint : ""
            },
            {
              id : `chartTraces[${this.index-1}][marker][color]`,  
              title : "Marker Color",  
              type : "color",
              value : this.options().marker.color,
              hint : ""
            },
          ],
          [
            {
              id : `chartTraces[${this.index-1}][marker][symbol]`, 
              title : "Marker Symbol", 	
              type : "number",
              min : 1,
              max : 300,
              step : 1,
              value :  this.options().marker.symbol,
              hint : "Sets the marker symbol type. Adding 100 is equivalent to appending '-open' to a symbol name. Adding 200 is equivalent to appending '-dot' to a symbol name. Adding 300 is equivalent to appending '-open-dot' or 'dot-open' to a symbol name."
            },
            {
              id : `chartTraces[${this.index-1}][marker][size]`, 
              title : "Marker Size", 	
              type : "number",
              min : 1,
              max : 2000,
              step : 1,
              value :  this.options().marker.size,
              hint : "Sets the marker size (in px)."
            },
          ],
          [
            {
              id : `chartTraces[${this.index-1}][marker][line][width]`, 
              title : "Marker Line Width", 	
              type : "number",
              min : 0,
              max : 100,
              step : 1,
              value :  this.options().marker.line.width,
              hint : "Sets the width (in px) of the lines bounding the marker points."
            },
            {
              id : `chartTraces[${this.index-1}][marker][maxdisplayed]`, 
              title : "Marker Max Displayed", 	
              type : "number",
              min : 1,
              max : 20000,
              step : 1,
              value :  this.options().marker.maxdisplayed,
              hint : "Sets a maximum number of points to be drawn on the graph. '0' corresponds to no limit."
            },
          ],
          [
            {
              id : `chartTraces[${this.index-1}][visible]`,  
              title : "Trace Visible",  
              type : "select",
              options : {
                enabled : "Enabled",
                disabled : "Disabled",
                legendonly : "Legend Only",
              },
              value : this.options().visible,
              hint : "Determines whether or not this trace is visible. If 'legendonly', the trace is not drawn, but can appear as a legend item (provided that the legend itself is visible)."
            },
            {
              id : `chartTraces[${this.index-1}][opacity]`, 
              title : "Trace Opacity", 	
              type : "number",
              min : 0,
              max : 1,
              step : 0.02,
              value :  this.options().opacity,
              hint : "Sets the opacity of the trace."
            },
          ],
          [
            {
              id : `chartTraces[${this.index-1}][marker][gradient][type]`, 
              title : "Marker Gradient Type", 	
              type : "select",
              options : {
                none : "None",
                radial : "Radial",
                horizontal : "Horizontal",
                vertical: "Vertical"
              },
              value :  this.options().marker.gradient.type,
              hint : "Sets the type of gradient used to fill the markers"
            },
          ],
          [
            {
              id : `chartTraces[${this.index-1}][marker][line][color]`, 
              title : "Marker Line Color", 	
              type : "color",
              value :  this.options().marker.line.color,
              hint : "Sets themarker.linecolor. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.line.cmin` and `marker.line.cmax` if set."
            },
            {
              id : `chartTraces[${this.index-1}][marker][gradient][color]`, 
              title : "Marker gradient Color", 	
              type : "color",
              value :  this.options().marker.gradient.color,
              hint : "Sets the final color of the gradient fill: the center color for radial, the right for horizontal, or the bottom for vertical."
            },
          ],
          [
            {
              id : `chartTraces[${this.index-1}][line][smoothing]`, 
              title : "line Smoothing", 	
              type : "number",
              min : 1,
              max : 1.3,
              step : 0.01,
              value :  this.options().line.smoothing,
              hint : "Has an effect only if `shape` is set to 'spline' Sets the amount of smoothing. '0' corresponds to no smoothing (equivalent to a 'linear' shape)."
            }, 
            {
              id : `chartTraces[${this.index-1}][line][dash]`, 
              title : "line Dash", 	
              type : "text",
              // options : {
              //   solid : "Solid",
              //   dash : "Dash",
              //   dot : "Dot",
              //   longdash: "Long Dash",
              //   dashdot: "Dash Dot",
              //   longdashdot: "Long Dash Dot"
              // },
              value :  this.options().line.dash,
              hint : "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
            },
          ],
          [
            {
              id : `chartTraces[${this.index-1}][line][simplify]`, 
              title : "line Simplify", 	
              type : "checkbox",
              value :  this.options().line.simplify,
              hint : "Simplifies lines by removing nearly-collinear points. When transitioning lines, it may be desirable to disable this so that the number of points along the resulting SVG path is unaffected."
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
      // }
    }
  }

}

export default ChartTrace;