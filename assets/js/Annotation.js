import { fontFamily, colors } from "./utilities"

class Annotation {

  constructor( annotation, index ) {

    this.annotation = annotation;
    // this.spreadsheet = spreadsheet
    // this.chart = chart
    // this.sheetId= sheetId
    // this.chartType= chartType
    // this.labels = Object.values(this.spreadsheet[this.sheetId]["labels"]);
    this.index = index;
    // fontFamily() = fontFamily
    // this.colors = colors
    // this.chartType = this.chart.chartParams.chartType;

    // switch (chartType) {
    //   case "LineChart":
    //   case "ScatterChart":
    //     // this.type = "scatter";
    //     this.mode = "lines+markers+text";
    //     break;
    //   default:
    //     // this.type = null;
    //     this.mode = null;   
    // }

  }
        

  options() {

    return {

      visible : ( this.annotation.visible === undefined ) ? true : this.annotation.visible,
      text : ( this.annotation.text === undefined ) ? "Hello World" : this.annotation.text,
      textangle : ( this.annotation.textangle === undefined ) ? 0: this.annotation.textangle,
      font : {
        family : (  this.annotation.font === undefined || this.annotation.font.family === undefined ) ? Object.keys(fontFamily())[13] : this.annotation.font.family,
        size : (  this.annotation.font === undefined || this.annotation.font.size === undefined ) ? 20 : this.annotation.font.size,
        color : (  this.annotation.font === undefined || this.annotation.font.color === undefined ) ? "#444444" : this.annotation.font.color,
      },
      width : ( this.annotation.width === undefined ) ? null : this.annotation.width,
      height : ( this.annotation.height === undefined ) ? null : this.annotation.height,
      opacity : ( this.annotation.opacity === undefined ) ? 1 : this.annotation.opacity,
      align : ( this.annotation.align === undefined ) ? "x" : this.annotation.align,
      valign : ( this.annotation.valign === undefined ) ? "y" : this.annotation.valign,
      bgcolor : ( this.annotation.bgcolor === undefined ) ? "#ffffff" :this.annotation.bgcolor,
      bordercolor : ( this.annotation.bordercolor === undefined ) ? "#333333" :this.annotation.bordercolor,
      borderpad : ( this.annotation.borderpad === undefined ) ? 1 :this.annotation.borderpad,
      borderwidth : ( this.annotation.borderwidth === undefined ) ? 1 :this.annotation.borderwidth,
      showarrow : ( this.annotation.showarrow === undefined ) ? true :this.annotation.showarrow,
      arrowcolor : ( this.annotation.arrowcolor === undefined ) ? "#444444" :this.annotation.arrowcolor,
      arrowhead : ( this.annotation.arrowhead === undefined ) ? 1 :this.annotation.arrowhead,
      startarrowhead : ( this.annotation.startarrowhead === undefined ) ? 1 :this.annotation.startarrowhead,
      arrowsize : ( this.annotation.arrowsize === undefined ) ? 1 :this.annotation.arrowsize,
      startarrowsize : ( this.annotation.startarrowsize === undefined ) ? 1 :this.annotation.startarrowsize,
      arrowside : ( this.annotation.arrowside === undefined ) ? "end" :this.annotation.arrowside,
      arrowwidth : ( this.annotation.arrowwidth === undefined ) ? 1 :this.annotation.arrowwidth,
      standoff : ( this.annotation.standoff === undefined ) ? 0 :this.annotation.standoff,
      startstandoff : ( this.annotation.startstandoff === undefined ) ? 0 :this.annotation.startstandoff,
      axref : ( this.annotation.axref === undefined ) ? "pixel" :this.annotation.axref,
      ayref : ( this.annotation.ayref === undefined ) ? "pixel" :this.annotation.ayref,
      ax : ( this.annotation.ax === undefined ) ? -30 :this.annotation.ax,
      ay : ( this.annotation.ay === undefined ) ? -40 :this.annotation.ay,
      xref : ( this.annotation.xref === undefined ) ? "pixel" :this.annotation.xref,
      yref : ( this.annotation.yref === undefined ) ? "pixel" :this.annotation.yref,
      x : ( this.annotation.x === undefined ) ? 2150 :this.annotation.x,
      y : ( this.annotation.y === undefined ) ? 20 :this.annotation.y,
      xshift : ( this.annotation.xshift === undefined ) ? 0 :this.annotation.xshift,
      yshift : ( this.annotation.yshift === undefined ) ? 0 :this.annotation.yshift,

    }

  }

  sections() {

    return {
      
      basicOptions: {
        intro : `Here you can modify the basic options of annotation "${this.annotation.text}`,
        title : "Basic Options",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],     
            inputFields : [
              {
                id : `layout[annotations][${this.index}][visible]`,  
                title : "Visibility",  
                type : "checkbox",
                value : this.options().visible,
                hint : "Determines whether or not this annotation is visible."
              },
              {
                id : `layout[annotations][${this.index}][arrowside]`, 
                title : "Arrow Side", 	
                type : "select", 
                options : {
                  start : "Start",
                  end : "End",
                  "start+end" : "Start & End",
                  none: "None",
                },
                value : this.options().arrowside,
                disabled: false === this.options().visible  ? true : false,
                hint : "Sets the annotation arrow head position."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],     
            inputFields : [
              {
                id : `layout[annotations][${this.index}][text]`,  
                title : "Text",  
                type : "text",
                value : this.options().text,
                hint : "Sets the text associated with this annotation. Plotly uses a subset of HTML tags to do things like newline (<br>), bold (<b></b>), italics (<i></i>), hyperlinks (<a href='...'></a>). Tags <em>, <sup>, <sub> <span> are also supported."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],     
            inputFields : [
              {
                id : `layout[annotations][${this.index}][width]`, 
                title : "Width", 	
                type : "number",
                min : 1,
                max : 3000,
                step : 1,
                value : this.options().width,
                disabled: false === this.options().visible  ? true : false,
                hint : "number greater than or equal to 1.  Sets an explicit width for the text box. null (default) lets the text set the box width. Wider text will be clipped. There is no automatic wrapping; use <br> to start a new line."
              },
              {
                id : `layout[annotations][${this.index}][height]`, 
                title : "Height", 	
                type : "number",
                min : 1,
                max : 3000,
                step : 1,
                value : this.options().height,
                disabled: false === this.options().visible  ? true : false,
                hint : "number greater than or equal to 1.  Sets an explicit height for the text box. null (default) lets the text set the box height. Taller text will be clipped."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][opacity]`, 
                title : "Opacity", 	
                type : "number",
                min : 0,
                max : 1,
                step : .02,
                value : this.options().opacity,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Sets a reference between this trace's x coordinates and a 2D cartesian x axis. If 'x' (the default value), the x coordinates refer to `layout.xaxis`. If 'x2', the x coordinates refer to `layout.xaxis2`, and so on."
              },
              {
                id : `layout[annotations][${this.index}][align]`, 
                title : "Horizontal Alignment", 	
                type : "select", 
                options : {
                  left : "Left",
                  center : "Center",
                  right : "Right"
                },
                value : this.options().align,
                disabled: false === this.options().visible  ? true : false,
                hint : "Sets a reference between this trace's y coordinates and a 2D cartesian y axis. If 'y' (the default value), the y coordinates refer to `layout.yaxis`. If 'y2', the y coordinates refer to `layout.yaxis2`, and so on."
              },
              {
                id : `layout[annotations][${this.index}][valign]`, 
                title : "Vertical Alignment", 	
                type : "select", 
                options : {
                  top : "Top",
                  middle : "Middle",
                  bottom : "Bottom"
                },
                value : this.options().valign,
                disabled: false === this.options().visible  ? true : false,
                hint : "Sets a reference between this trace's y coordinates and a 2D cartesian y axis. If 'y' (the default value), the y coordinates refer to `layout.yaxis`. If 'y2', the y coordinates refer to `layout.yaxis2`, and so on."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][textangle]`, 
                title : "Text Angle", 	
                type : "number",
                min : 0,
                max : 360,
                step : 1,
                value : this.options().textangle,
                disabled: false == this.options().visible  ? true : false,
                hint : "Determines whether or not an item corresponding to this trace is shown in the legend."
              },
              {
                id : `layout[annotations][${this.index}][name]`,  
                title : "Label in Legend",  
                type : "text",
                value : this.options().name,
                disabled: false == this.options().visible  ? true : false,
                hint : "The trace name appear as the legend item and on hover."
              },
            ],
          },
        ],
      },

      markers: {
        intro : `Here you can modify the markers of trace "${this.options().name}`,
        title : "Markers",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][showarrow]`, 
                title : "show Arrow", 	
                type : "checkbox",
                value : this.options().showarrow,
                disabled: false == this.options().visible ? true : false,
                hint : "Sets the marker symbol type. Adding 100 is equivalent to appending '-open' to a symbol name. Adding 200 is equivalent to appending '-dot' to a symbol name. Adding 300 is equivalent to appending '-open-dot' or 'dot-open' to a symbol name."
              },
              {
                id : `layout[annotations][${this.index}][[borderpad]`, 
                title : "Border Padding", 	
                type : "number",
                min : 1,
                max : 2000,
                step : 1,
                value : this.options().borderpad,
                disabled: false == this.options().visible ? true : false,
                hint : "Sets the marker size (in px)."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][borderwidth]`, 
                title : "Border Width", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.options().borderwidth,
                disabled: false == this.options().visible? true : false,
                hint : "Sets the marker's opacity."
              },
              {
                id : `layout[annotations][${this.index}][arrowcolor]`,  
                title : "Arrow Color",  
                type : "color",
                value : this.options().arrowcolor,
                disabled: false == this.options().visible ? true : false,
                hint : ""
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][arrowhead]`, 
                title : "Arrow Head", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.options().arrowhead,
                disabled: false == this.options().visible? true : false,
                hint : "Sets the marker's opacity."
              },
              {
                id : `layout[annotations][${this.index}][startarrowhead]`, 
                title : "Start Arrow Head", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.options().startarrowhead,
                disabled: false == this.options().visible? true : false,
                hint : "Sets the marker's opacity."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][arrowsize]`, 
                title : "Arrow Size", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.options().arrowsize,
                disabled: false == this.options().visible? true : false,
                hint : "Sets the marker's opacity."
              },
              {
                id : `layout[annotations][${this.index}][startarrowsize]`, 
                title : "Start Arrow Size", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.options().startarrowsize,
                disabled: false == this.options().visible? true : false,
                hint : "Sets the marker's opacity."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][bgcolor]`, 
                title : "Background Color", 	
                type : "color",
                value :  this.options().bgcolor,
                // disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets themarker.linecolor. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.line.cmin` and `marker.line.cmax` if set."
              },
              {
                id : `layout[annotations][${this.index}][bordercolor]`, 
                title : "Border Color", 	
                type : "color",
                value :  this.options().bordercolor,
                disabled: false == this.options().visible  ? true : false,
                hint : "Sets themarker.linecolor. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.line.cmin` and `marker.line.cmax` if set."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][arrowwidth]`, 
                title : "Arrow Width", 	
                type : "number",
                min : 0,
                max : 100,
                step : .1,
                value : this.options().arrowwidth,
                disabled: false == this.options().visible ? true : false,
                hint : "Sets the type of gradient used to fill the markers"
              },
              {
                id : `layout[annotations][${this.index}][standoff]`, 
                title : "Standoff", 	
                type : "number",
                min : 0,
                max : 1000,
                step : 1,
                value : this.options().standoff,
                disabled: false == this.options().visible ? true : false,
                hint : "Sets the type of gradient used to fill the markers"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][startstandoff]`, 
                title : "Start Standoff", 	
                type : "number",
                min : 0,
                max : 1000,
                step : 1,
                value : this.options().startstandoff,
                disabled: false == this.options().visible ? true : false,
                hint : "Sets the type of gradient used to fill the markers"
              },
            ],
          },
        ]
      },

      Line: {
        intro : `Here you can modify the lines of trace "${this.options().name}`,
        title : "Lines",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][axref]`,  
                title : "Arrow X Reference",  
                type : "text",
                value : this.options().axref,
                disabled: true !== this.options().visible ? true : false,
                hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
              },
              {
                id : `layout[annotations][${this.index}][ayref]`,  
                title : "Arrow Y Reference",  
                type : "text",
                value : this.options().ayref,
                disabled: true !== this.options().visible ? true : false,
                hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][ax]`, 
                title : "Arrow X", 	
                type : "number",
                // min : 1,
                // max : 2000,
                // step : 1,
                value :  this.options().ax,
                disabled: false === this.options().visible ? true : false,
                hint : "Sets the width of the trace line."
              },
              {
                id : `layout[annotations][${this.index}][ay]`, 
                title : "Arrow Y", 	
                type : "number",
                // min : 1,
                // may : 2000,
                // step : 1,
                value :  this.options().ay,
                disabled: false === this.options().visible ? true : false,
                hint : "Sets the width of the trace line."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][xref]`,  
                title : "X Reference",  
                type : "text",
                value : this.options().xref,
                disabled: true !== this.options().visible ? true : false,
                hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
              },
              {
                id : `layout[annotations][${this.index}][yref]`,  
                title : "Y Reference",  
                type : "text",
                value : this.options().yref,
                disabled: true !== this.options().visible ? true : false,
                hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][x]`, 
                title : "X", 	
                type : "number",
                // min : 1,
                // mx : 2000,
                // step : 1,
                value :  this.options().x,
                disabled: false === this.options().visible ? true : false,
                hint : "Sets the width of the trace line."
              },
              {
                id : `layout[annotations][${this.index}][y]`, 
                title : "Y", 	
                type : "number",
                // min : 1,
                // may : 2000,
                // step : 1,
                value :  this.options().y,
                disabled: false === this.options().visible ? true : false,
                hint : "Sets the width of the trace line."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[annotations][${this.index}][xshift]`, 
                title : "X Shift", 	
                type : "number",
                min : 1,
                max : 2000,
                step : 1,
                value :  this.options().xshift,
                disabled: true !== this.options().visible ? true : false,
                hint : "Has an effect only if `shape` is set to 'spline' Sets the amount of smoothing. '0' corresponds to no smoothing (equivalent to a 'linear' shape)."
              }, 
              {
                id : `layout[annotations][${this.index}][yshift]`, 
                title : "Y Shift", 	
                type : "number",
                min : 1,
                max : 2000,
                step : 1,
                value :  this.options().yshift,
                disabled: true !== this.options().visible ? true : false,
                hint : "Has an effect only if `shape` is set to 'spline' Sets the amount of smoothing. '0' corresponds to no smoothing (equivalent to a 'linear' shape)."
              }, 
            ],
          },
        ]
      },
    }
  }

}

export default Annotation;