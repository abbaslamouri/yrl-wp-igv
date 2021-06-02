import { fontFamily, colors } from "./utilities"


class Trace {

  constructor(trace, spreadsheet, index, sheetId, chartType, iwpgvObj ) {


    this.trace = trace;
    this.spreadsheet = spreadsheet
    // this.chart = chart
    this.sheetId= sheetId
    // this.chartType= chartType
    this.labels = Object.values(this.spreadsheet[this.sheetId]["labels"]);
    this.index = index;
    // this.chartType = this.chart.chartParams.chartType;

    switch (chartType) {
      case "LineChart":
      case "ScatterChart":
        // this.type = "scatter";
        this.mode = "lines+markers";
        break;
      default:
        // this.type = null;
        this.mode = null;   
    }

    this.prefix = iwpgvObj.prefix
   
  }

  options() {

    return {

      // type : ( this.trace === undefined ||  this.trace.type === undefined ) ? this.trace.type :  this.type,
      name : this.labels[this.index],
      visible : ( this.trace.visible === undefined ) ? true : "false" === this.trace.visible ? false : "true" === this.trace.visible ? true : this.trace.visible,
      showlegend : ( this.trace.showlegend === undefined ) ? true : this.trace.showlegend,
      opacity : ( this.trace.opacity === undefined ) ? 1 : this.trace.opacity,
      mode : ( this.trace.mode === undefined ) ? this.mode :this.trace.mode,
      text : ( this.trace.text === undefined ) ? ["Hi", "there"] : this.trace.text,
      textposition : ( this.trace.textposition === undefined ) ? "bottom center" : this.trace.textposition,
      hovertext : ( this.trace.hovertext === undefined ) ? "" : this.trace.hovertext,
      hoverinfo : ( this.trace.hoverinfo === undefined ) ? "all" : this.trace.hoverinfo,
      x : this.spreadsheet[this.sheetId].data[0],
      y : this.spreadsheet[this.sheetId].data[this.index],
      xaxis : ( this.trace.xaxis === undefined ) ? "x" : this.trace.xaxis,
      yaxis : ( this.trace.yaxis === undefined ) ? "y" : this.trace.yaxis,
      connectgaps : (  this.trace.connectgaps === undefined ) ?  false : this.trace.connectgaps,
      textfont : {
        family : (  this.trace.textfont === undefined || this.trace.textfont.family === undefined ) ? Object.keys(fontFamily())[13] : this.trace.textfont.family,
        size : (  this.trace.textfont === undefined || this.trace.textfont.size === undefined ) ? 20 : this.trace.textfont.size,
        color : (  this.trace.textfont === undefined || this.trace.textfont.color === undefined ) ? "#1B5E20" : this.trace.textfont.color,
      },
      marker : {
        symbol : (  this.trace.marker === undefined || this.trace.marker.symbol === undefined ) ? 1 : this.trace.marker.symbol,
        opacity: (  this.trace.marker === undefined || this.trace.marker.opacity === undefined ) ? 1 : this.trace.marker.opacity,
        size: (  this.trace.marker === undefined || this.trace.marker.size === undefined ) ? 5 : this.trace.marker.size,
        maxdisplayed : (  this.trace.marker === undefined || this.trace.marker.maxdisplayed === undefined ) ? 10 : this.trace.marker.maxdisplayed,
        color : (  this.trace.marker === undefined || this.trace.marker.color === undefined ) ? colors[this.index] : this.trace.marker.color,
        line: {
          width: (  this.trace.marker === undefined || this.trace.marker.line === undefined || this.trace.marker.line.width === undefined ) ? 1 : this.trace.marker.line.width,
          color: (  this.trace.marker === undefined || this.trace.marker.line === undefined || this.trace.marker.line.color === undefined ) ?  "#4A148C" : this.trace.marker.line.color,
        },
        gradient: {
          type: (  this.trace.marker === undefined || this.trace.marker.gradient === undefined || this.trace.marker.gradient.type === undefined ) ? "radial" : this.trace.marker.gradient.type,
          color: (  this.trace.marker === undefined || this.trace.marker.gradient === undefined || this.trace.marker.gradient.color === undefined ) ? "#E65100" : this.trace.marker.gradient.color,
        },  
      },
      line : {
        shape: ( this.trace.line === undefined || this.trace.line.shape === undefined ) ? "spline" : this.trace.line.shape,
        width: ( this.trace.line === undefined || this.trace.line.width === undefined ) ? 2 : this.trace.line.width,
        color : ( this.trace.line === undefined || this.trace.line.color === undefined ) ? colors[this.index] : this.trace.line.color,
        dash: ( this.trace.line === undefined || this.trace.line.dash === undefined ) ? "solid" : this.trace.line.dash,
        smoothing: ( this.trace.line === undefined || this.trace.line.smoothing === undefined ) ? 1 : this.trace.line.smoothing,
        simplify: ( this.trace.line === undefined || this.trace.line.simplify === undefined ) ? true : this.trace.line.simplify,
      },
      error_y : {
        visible: ( this.trace.error_y === undefined || this.trace.error_y.visible === undefined ) ? false : this.trace.error_y.visible,
        type: ( this.trace.error_y === undefined || this.trace.error_y.type === undefined ) ? "percent": this.trace.error_y.type,
        symmetric: ( this.trace.error_y === undefined || this.trace.error_y.symmetric === undefined ) ? true: this.trace.error_y.symmetric,
        value: ( this.trace.error_y === undefined || this.trace.error_y.value === undefined ) ? 20 : this.trace.error_y.value,
        valueminus: ( this.trace.error_y === undefined || this.trace.error_y.valueminus === undefined ) ? 20 : this.trace.error_y.valueminus,
        array: ( this.trace.error_y === undefined || this.trace.error_y.array === undefined ) ? []: this.trace.error_y.array,
        arrayminus: ( this.trace.error_y === undefined || this.trace.error_y.arrayminus === undefined ) ? [] : this.trace.error_y.arrayminus,
        color: ( this.trace.error_y === undefined || this.trace.error_y.color === undefined ) ? colors[this.index] : this.trace.error_y.color,
        thickness: ( this.trace.error_y === undefined || this.trace.error_y.thickness === undefined ) ? 2: this.trace.error_y.thickness,
        width: ( this.trace.error_y === undefined || this.trace.error_y.width === undefined ) ? 4: this.trace.error_y.width,
      },
      hoverlabel : {
        bgcolor: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.bgcolor === undefined ) ? "#eeeeee" : this.trace.hoverlabel.bgcolor,
        bordercolor: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.bordercolor === undefined ) ? "#000a12" : this.trace.hoverlabel.bordercolor,
        font : {
          family : ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.family === undefined ) ? Object.keys(fontFamily())[13] : this.trace.hoverlabel.font.family,
          size : ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.size === undefined ) ? 20 : this.trace.hoverlabel.font.size,
          color : ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.color === undefined ) ? "#1B5E20" : this.trace.hoverlabel.font.color,
        },
        align: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.align === undefined ) ? "auto" : this.trace.hoverlabel.align,
        namelength: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.namelength === undefined ) ? -1 : this.trace.hoverlabel.namelength,
      }
    }

  }
  
  // id : `${this.prefix}__chartTracesPanel__trace[${this.index-1}]`,
  // cssClasses:["chartTraces", "subPanel"],
  // title : (this.spreadsheet[this.sheetId].labels.length) > 1 ? Object.values(this.spreadsheet[this.sheetId].labels)[this.index] : "",
  // intro : "general gfhfhjfj jsdkjfsdjkdfsjk lkdkklsd",


  sections() {

    return {
      
      basicOptions: {
        intro : `Here you can modify the basic options of trace "${this.labels[this.index]}`,
        title : "Basic Options",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],     
            inputFields : [
              {
                id : `chartTraces[${this.index-1}][visible]`,  
                title : "Trace Visibility",  
                type : "select",
                options : {
                  true : "Visible",
                  false : "Hidden",
                  legendonly : "Legend Only",
                },
                value : this.options().visible,
                hint : "Determines whether or not this trace is visible. If 'legendonly', the trace is not drawn, but can appear as a legend item (provided that the legend itself is visible)."
              },
              {
                id : `chartTraces[${this.index-1}][mode]`, 
                title : "Mode", 	
                type : "select", 
                options : {
                  none : "None",
                  lines : "Lines",
                  markers : "Markers",
                  text: "text",
                  "lines+markers" : "Lines & Markers",
                  "markers+text" : "Markers & Text",
                  "lines+markers+text" : "Lines, Markers & Text"
                },
                value :  this.options().mode,
                disabled: false === this.options().visible  ? true : false,
                hint : "Determines the drawing mode for this scatter trace. If the provided `mode` includes 'text' then the `text` elements appear at the coordinates. Otherwise, the `text` elements appear on hover. If there are less than 20 points and the trace is not stacked then the default is 'lines+markers'. Otherwise, 'lines'."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][xaxis]`, 
                title : "x-Axis", 	
                type : "select", 
                options : {
                  x : "Bottom",
                  x2 : "Top",
                },
                value :  this.options().xaxis,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Sets a reference between this trace's x coordinates and a 2D cartesian x axis. If 'x' (the default value), the x coordinates refer to `layout.xaxis`. If 'x2', the x coordinates refer to `layout.xaxis2`, and so on."
              },
              {
                id : `chartTraces[${this.index-1}][yaxis]`, 
                title : "Y-Axis", 	
                type : "select", 
                options : {
                  y : "Left",
                  y2 : "Right",
                },
                value :  this.options().yaxis,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Sets a reference between this trace's y coordinates and a 2D cartesian y axis. If 'y' (the default value), the y coordinates refer to `layout.yaxis`. If 'y2', the y coordinates refer to `layout.yaxis2`, and so on."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][showlegend]`, 
                title : "Show In Legend", 	
                type : "checkbox",
                value :  this.options().showlegend,
                disabled: false == this.options().visible  ? true : false,
                hint : "Determines whether or not an item corresponding to this trace is shown in the legend."
              },
              {
                id : `chartTraces[${this.index-1}][name]`,  
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
        intro : `Here you can modify the markers of trace "${this.labels[this.index]}`,
        title : "Markers",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][marker][symbol]`, 
                title : "Marker Symbol", 	
                type : "number",
                min : 1,
                max : 300,
                step : 1,
                value :  this.options().marker.symbol,
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
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
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets the marker size (in px)."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][marker][opacity]`, 
                title : "Marker Opacity", 	
                type : "number",
                min : 0,
                max : 1,
                step : .01,
                value :  this.options().marker.opacity,
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets the marker's opacity."
              },
              {
                id : `chartTraces[${this.index-1}][marker][color]`,  
                title : "Marker Color",  
                type : "color",
                value : this.options().marker.color,
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : ""
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][marker][line][color]`, 
                title : "Marker Line Color", 	
                type : "color",
                value :  this.options().marker.line.color,
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets themarker.linecolor. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.line.cmin` and `marker.line.cmax` if set."
              },
              {
                id : `chartTraces[${this.index-1}][marker][line][width]`, 
                title : "Marker Line Width", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value :  this.options().marker.line.width,
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets the width (in px) of the lines bounding the marker points."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
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
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets the type of gradient used to fill the markers"
              },
              {
                id : `chartTraces[${this.index-1}][marker][gradient][color]`, 
                title : "Marker gradient Color", 	
                type : "color",
                value :  this.options().marker.gradient.color,
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets the final color of the gradient fill: the center color for radial, the right for horizontal, or the bottom for vertical."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][marker][maxdisplayed]`, 
                title : "Marker Max Displayed", 	
                type : "number",
                min : 1,
                max : 20000,
                step : 1,
                value :  this.options().marker.maxdisplayed,
                disabled: ( false == this.options().visible || ! this.options().mode.includes( "marker" ) )  ? true : false,
                hint : "Sets a maximum number of points to be drawn on the graph. '0' corresponds to no limit."
              },
              {
                id : `chartTraces[${this.index-1}][opacity]`, 
                title : "Trace Opacity", 	
                type : "number",
                min : 0,
                max : 1,
                step : 0.02,
                value :  this.options().opacity,
                disabled: false === this.options().visible  ? true : false,
                hint : "Sets the opacity of the trace."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][line][shape]`,  
                title : "Line Shape",  
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
                disabled: ( true !== this.options().visible || ! this.options().mode.includes( "lines" ) )  ? true : false,
                hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
              },
              {
                id : `chartTraces[${this.index-1}][line][width]`, 
                title : "Line Width", 	
                type : "number",
                min : 1,
                max : 2000,
                step : 1,
                value :  this.options().line.width,
                disabled: ( false === this.options().visible || ! this.options().mode.includes( "lines" ) )  ? true : false,
                hint : "Sets the width of the trace line."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][line][dash]`, 
                title : "Line Type", 	
                type : "text",
                value :  this.options().line.dash,
                disabled: ( false === this.options().visible || ! this.options().mode.includes( "lines" ) )  ? true : false,
                hint : "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
              },
              {
                id : `chartTraces[${this.index-1}][line][color]`,  
                title : "Line Color",  
                type : "color",
                value : this.options().line.color,
                disabled: ( false === this.options().visible || ! this.options().mode.includes( "lines" ) )  ? true : false,
                hint : "Sets the color of the trace line"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][line][smoothing]`, 
                title : "line Smoothing", 	
                type : "number",
                min : 1,
                max : 1.3,
                step : 0.01,
                value :  this.options().line.smoothing,
                disabled: ( true !== this.options().visible || ! this.options().mode.includes( "lines" ) )  ? true : false,
                hint : "Has an effect only if `shape` is set to 'spline' Sets the amount of smoothing. '0' corresponds to no smoothing (equivalent to a 'linear' shape)."
              }, 
              {
                id : `chartTraces[${this.index-1}][line][simplify]`, 
                title : "line Simplify", 	
                type : "checkbox",
                value :  this.options().line.simplify,
                disabled: ( true !== this.options().visible || ! this.options().mode.includes( "lines" ) )  ? true : false,
                hint : "Simplifies lines by removing nearly-collinear points. When transitioning lines, it may be desirable to disable this so that the number of points along the resulting SVG path is unaffected."
              },
            ],
          },
          {        
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][text]`, 
                title : "Text", 	
                type : "text",
                value :  Array.isArray( this.options( ).text ) ? this.options().text.join() : this.options().text,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Sets text elements associated with each (x,y) pair. If a single string, the same string appears over all the data points. If an array of string, the items are mapped in order to the this trace's (x,y) coordinates. If trace `hoverinfo` contains a 'text' flag and 'hovertext' is not set, these elements will be seen in the hover labels."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][textposition]`, 
                title : "Text Position", 	
                type : "select",
                options : {
                  "top left" : "Top Left",
                  "top center" : "Top Center",
                  "top right": "Top Right",
                  "middle left" : "Middle Left",
                  "middle center" : "Middle Center",
                  "middle right": "Middle Right",
                  "bottom left" : "Bottom Left",
                  "bottom center" : "Bottom Center",
                  "bottom right": "Bottom Right",
                },
                value :  this.options().textposition,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Sets the positions of the `text` elements with respects to the (x,y) coordinates."
              },
              {
                id : `chartTraces[${this.index-1}][textfont][family]`,
                title : "Text Font",	
                type : "select",
                options : fontFamily(),
                value : this.options().textfont.family,
                disabled: true !== this.options().visible  ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][textfont][color]`,
                title : "Text Font Color",
                type : "color", 
                value : this.options().textfont.color,
                disabled: true !== this.options().visible  ? true : false,
              }, 
              {
                id : `chartTraces[${this.index-1}][textfont][size]`, 
                title : "Text Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().textfont.size,
                disabled: true !== this.options().visible  ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][hovertext]`, 
                title : "Hover Text", 	
                type : "text",
                value :  Array.isArray( this.options().hovertext ) ? this.options().hovertext.join() : this.options().hovertext,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Sets hover text elements associated with each (x,y) pair. If a single string, the same string appears over all the data points. If an array of string, the items are mapped in order to the this trace's (x,y) coordinates. To be seen, trace `hoverinfo` must contain a 'text' flag."
              },
              {
                id : `chartTraces[${this.index-1}][hoverinfo]`, 
                title : "Hover Info.", 	
                type : "select",
                options : {
                  all: "All",
                  x : "X",
                  y : "Y",
                  text : "Text",
                  name : "Name",
                  "name+rext": "Name & Text",
                  none : "None",
                  skip: "Skip",
                },
                value :  this.options().hoverinfo,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Any combination of 'x', 'y', 'z', 'text', 'name' joined with a '+' OR 'all' or 'none' or 'skip'. Examples: 'x', 'y', 'x+y', 'x+y+z', 'all'.  Determines which trace information appear on hover. If `none` or `skip` are set, no information is displayed upon hovering. But, if `none` is set, click and hover events are still fired.  Default: 'all'"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][hoverlabel][align]`, 
                title : "Hover Label Alignment", 
                type : "select",
                options : {
                  auto : "Auto",
                  left : "Left",
                  right : "Right",
                },
                value : this.options().hoverlabel.align,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Type: enumerated or array of enumerateds , one of ( 'left' | 'right' | 'auto' ).  Sets the horizontal alignment of the text content within hover label box. Has an effect only if the hover label text spans more two or more lines.  Default: 'auto'"
              },
              {
                id : `chartTraces[${this.index-1}][hoverlabel][namelength]`, 
                title : "Hover Label Name Length", 
                type : "number",
                min : -1,
                max : 1000,
                step : 1,
                value : this.options().hoverlabel.namelength,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Sets the default length (in number of characters) of the trace name in the hover labels for all traces. -1 shows the whole name regardless of length. 0-3 shows the first 0-3 characters, and an integer >3 will show the whole name if it is less than that many characters, but if it is longer, will truncate to `namelength - 3` characters and add an ellipsis."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][hoverlabel][font][family]`,
                title : "Hover Label Font",	
                type : "select",
                options : fontFamily(),
                value : this.options().hoverlabel.font.family,
                disabled: true !== this.options().visible  ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `chartTraces[${this.index-1}][hoverlabel][font][size]`, 
                title : "Hover Label Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().hoverlabel.font.size,
                disabled: true !== this.options().visible  ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][hoverlabel][font][color]`,
                title : "Hover Label Font Color",
                type : "color", 
                value : this.options().hoverlabel.font.color,
                disabled: true !== this.options().visible  ? true : false,
              },
              {
                id : `chartTraces[${this.index-1}][hoverlabel][bgcolor]`,
                title : "Hover label Bg. Color",
                type : "color", 
                value : this.options().hoverlabel.bgcolor,
                disabled: true !== this.options().visible  ? true : false,
                hint: "Sets the background color of the hover labels for this trace"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][hoverlabel][bordercolor]`,
                title : "Hover label Border Color",
                type : "color", 
                value : this.options().hoverlabel.bordercolor,
                disabled: true !== this.options().visible  ? true : false,
                hint: "Sets the border color of the hover labels for this trace."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][error_y][visible]`,
                title : "Show Error",	
                type : "checkbox",
                value : this.options().error_y.visible,
                disabled: true !== this.options().visible  ? true : false,
                hint: "Determines whether or not this set of error bars is visible."
              },
              {
                id : `chartTraces[${this.index-1}][error_y][type]`, 
                title : "Error Type", 
                type : "select",
                options : {
                  percent : "Percent",
                  constant : "Constant",
                  sqrt : "Square Root",
                  data: "Data",
                },
                value : this.options().error_y.type,
                disabled: ( ! this.options().error_y.visible  || true !== this.options().visible ) ? true : false,
                hint : "Determines the rule used to generate the error bars. If 'constant`, the bar lengths are of a constant value. Set this constant in `value`. If 'percent', the bar lengths correspond to a percentage of underlying data. Set this percentage in `value`. If 'sqrt', the bar lengths correspond to the square of the underlying data. If 'data', the bar lengths are set with data set `array`."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][error_y][value]`,
                title : "Value",
                type : "number", 
                value : this.options().error_y.value,
                disabled: ( ! this.options().error_y.visible || this.options().error_y.type === "data" || true !== this.options().visible ) ? true : false,
                hint: "Sets the value of either the percentage (if `type` is set to 'percent') or the constant (if `type` is set to 'constant') corresponding to the lengths of the error bars."
              },
              {
                id : `chartTraces[${this.index-1}][error_y][valueminus]`,
                title : "Value Minus",
                type : "number", 
                value : this.options().error_y.valueminus,
                disabled: ( ! this.options().error_y.visible || this.options().error_y.type === "data" || this.options().error_y.symmetric || true !== this.options().visible ) ? true : false,
                hint: "Sets the value of either the percentage (if `type` is set to 'percent') or the constant (if `type` is set to 'constant') corresponding to the lengths of the error bars in the bottom (left) direction for vertical (horizontal) bars"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][error_y][array]`,
                title : "Error Array",	
                type : "text",
                value : this.options().error_y.array.join(),
                disabled: ( ! this.options().error_y.visible || this.options().error_y.type !== "data" || true !== this.options().visible ) ? true : false,
                hint: "Sets the data corresponding the length of each error bar. Values are plotted relative to the underlying data. Determines whether or not this set of error bars is array."
              },
              {
                id : `chartTraces[${this.index-1}][error_y][arrayminus]`, 
                title : "Error Array Minus", 
                type : "text",
                value : this.options().error_y.arrayminus.join(),
                disabled: ( ! this.options().error_y.visible || this.options().error_y.type !== "data" || this.options().error_y.symmetric || true !== this.options().visible ) ? true : false,
                hint : "Sets the data corresponding the length of each error bar in the bottom (left) direction for vertical (horizontal) bars Values are plotted relative to the underlying data."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][error_y][color]`,
                title : "Error Bar Color",
                type : "color", 
                value : this.options().error_y.color,
                disabled: ( ! this.options().error_y.visible || true !== this.options().visible )? true : false,
                hint: "Sets the stoke color of the error bars."
              },
              {
                id : `chartTraces[${this.index-1}][error_y][symmetric]`,
                title : "Symmetric ?",
                type : "checkbox", 
                value : this.options().error_y.symmetric,
                disabled: ( ! this.options().error_y.visible || true !== this.options().visible ) ? true : false,
                hint: "Determines whether or not the error bars have the same length in both direction (top/bottom for vertical bars, left/right for horizontal bars."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][error_y][width]`, 
                title : "Error Bar Width", 
                type : "number",
                min : 1,
                max : 1000,
                step : 1,
                value : this.options().error_y.width,
                disabled: ( ! this.options().error_y.visible || true !== this.options().visible )  ? true : false,
                hint : "Sets the width (in px) of the cross-bar at both ends of the error bars."
              },
              {
                id : `chartTraces[${this.index-1}][error_y][thickness]`, 
                title : "Error Bar Line Thickness", 
                type : "number",
                min : 1,
                max : 1000,
                step : 1,
                value : this.options().error_y.thickness,
                disabled:  ( ! this.options().error_y.visible || true !== this.options().visible )  ? true : false,
                hint : "Sets the thickness (in px) of the error bars."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `chartTraces[${this.index-1}][connectgaps]`, 
                title : "Connect Gaps", 
                type : "checkbox",
                value : this.options().connectgaps,
                disabled: true !== this.options().visible  ? true : false,
                hint : "Determines whether or not gaps (i.e. {nan} or missing values) in the provided data arrays are connected."
              },
            ]
          }
        ]
      }
    }
  }

}

export default Trace;