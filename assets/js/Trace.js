import { fontFamily, colors } from "./utilities"

class Trace {

  constructor( ) {

    // trace = trace;
    // index = index;

    //  console.log(trace)

  }

  static defaultOptions( index ) {

    return {

      type: "scatter",
      visible: true,
      showlegend: true,
      mode: "lines+markers",
      xaxis: "x",
      yaxis: "y",
      connectgaps: false,
      opacity: 1,

      marker: {
        symbol: 0,
        size: 6,
        opacity:1,
        color: colors()[index],
        line: {
          color: "#444444",
          width: 0
        },
        gradient: {
          type: "none",
          color: "#444444"
        },
        masdisplayed: 0
      },
      line: {
        dash: "solid",
        shape: "linear",
        width: 2,
        color: colors()[index],
        smoothing: 1,
        simplify: true
      },
      text: null,
      textfont: {
        family: "Raleway",
        color: colors()[index],
        size: 12,
      },
      textposition: "top center",
      hovertext: "",
      hoverinfo: "all",
      hoverlabel: {
        align: "auto",
        namelength: 15,
        font: {
          family: "Raleway",
          color: "#FFFFFF",
          size: 12,
        },
        bgcolor: colors()[index],
        bordercolor : "#000000",
      },
      error_y: {
        visible: false,
        type: "percent",
        value: 20,
        valueminus: 10,
        array: [],
        arrayminus: [],
        color: colors()[index],
        symmetric: false,
        thickness: 2,
        width: 4
      }
    }

  }

  static sections( trace, index, name ) {

    return {
      
      basicOptions: {
        intro : `Here you can modify the basic options of trace "${name}"`,
        title : "Basic Options",
        fieldGroups : [
          {
            cssClasses : ["field-group", "sixty-forty"],     
            inputFields : [
              {
                id : `traces[${index}][visible]`,  
                title : "Trace Visibility",  
                type : "select",
                options : {
                  true : "Visible",
                  false : "Hidden",
                  legendonly : "Legend Only",
                },
                value : trace.visible === undefined ? null : true === trace.visible ? "true" : false === trace.visible ? "false" : trace.visible,
                hint : "Determines whether or not this trace is visible. If 'legendonly', the trace is not drawn, but can appear as a legend item (provided that the legend itself is visible). Default: visible"
              },
              {
                id : `traces[${index}][showlegend]`, 
                title : "Show In Legend", 	
                type : "checkbox",
                value : trace.showlegend === undefined ? false : trace.showlegend,
                disabled: false === trace.visible  ? true : false,
                hint : "Determines whether or not an item corresponding to this trace is shown in the legend."
              },
            ]
          },
          {
            cssClasses : ["field-group"],     
            inputFields : [
              {
                id : `traces[${index}][mode]`, 
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
                value : trace.mode === undefined ? null :trace.mode,
                disabled: true !== trace.visible  ? true : false,
                hint : "Determines the drawing mode for this scatter trace. If the provided `mode` includes 'text' then the `text` elements appear at the coordinates. Otherwise, the `text` elements appear on hover. If there are less than 20 points and the trace is not stacked then the default is 'lines+markers'. Otherwise, 'lines'."
              },
            ]
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${index}][name]`,  
                title : "Name",  
                type : "text",
                value : trace.name === undefined ? null :trace.name,
                disabled: false === trace.visible  ? true : false,
                hint : "The trace name appear as the legend item and on hover."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${index}][xaxis]`, 
                title : "x-Axis", 	
                type : "select", 
                options : {
                  x : "Bottom",
                  x2 : "Top",
                },
                value : trace.xaxis === undefined ? null : trace.xaxis,
                disabled: true !== trace.visible || ! trace.showlegend  ? true : false,
                hint : "Sets a reference between this trace's x coordinates and a 2D cartesian x axis. If 'x' (the default value), the x coordinates refer to `layout.xaxis`. If 'x2', the x coordinates refer to `layout.xaxis2`, and so on."
              },
              {
                id : `traces[${index}][yaxis]`, 
                title : "Y-Axis", 	
                type : "select", 
                options : {
                  y : "Left",
                  y2 : "Right",
                },
                value : trace.yaxis === undefined ? null: trace.yaxis,
                disabled: true !== trace.visible  ? true : false,
                hint : "Sets a reference between this trace's y coordinates and a 2D cartesian y axis. If 'y' (the default value), the y coordinates refer to `layout.yaxis`. If 'y2', the y coordinates refer to `layout.yaxis2`, and so on."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],     
            inputFields : [
              {
                id : `traces[${index}][connectgaps]`, 
                title : "Connect Gaps", 
                type : "checkbox",
                value : trace.connectgaps === undefined ?  false : trace.connectgaps,
                disabled: true !== trace.visible  ? true : false,
                hint : "Determines whether or not gaps (i.e. {nan} or missing values) in the provided data arrays are connected."
              },
              {
                id : `traces[${index}][opacity]`, 
                title : "Trace Opacity", 	
                type : "number",
                min : 0,
                max : 1,
                step : 0.02,
                value : trace.opacity === undefined ? null : trace.opacity,
                disabled: true !== trace.visible  ? true : false,
                hint : "Sets the opacity of the trace."
              },
            ]
          },
        ],
      },

      markers: {
        intro : `Here you can modify the markers of trace "${trace.name}`,
        title : "Markers",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${index}][marker][symbol]`, 
                title : "Symbol", 	
                type : "number",
                min : 1,
                max : 300,
                step : 1,
                value :  trace.marker === undefined || trace.marker.symbol === undefined ? null : trace.marker.symbol,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the marker symbol type. Adding 100 is equivalent to appending '-open' to a symbol name. Adding 200 is equivalent to appending '-dot' to a symbol name. Adding 300 is equivalent to appending '-open-dot' or 'dot-open' to a symbol name."
              },
              {
                id : `traces[${index}][marker][size]`, 
                title : "Size", 	
                type : "number",
                min : 1,
                max : 2000,
                step : 1,
                value : trace.marker === undefined || trace.marker.size === undefined ? null : trace.marker.size,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the marker size (in px).  Number or array of numbers greater than or equal to 0"
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${index}][marker][opacity]`, 
                title : "Opacity", 	
                type : "number",
                min : 0,
                max : 1,
                step : .01,
                value : trace.marker === undefined || trace.marker.opacity === undefined ? null : trace.marker.opacity,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the marker's opacity."
              },
              {
                id : `traces[${index}][marker][color]`,  
                title : "Color",  
                type : "color",
                value : trace.marker === undefined || trace.marker.color === undefined ? null : trace.marker.color,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" ) ? true : false,
                hint : ""
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${index}][marker][line][color]`, 
                title : "Line Color", 	
                type : "color",
                value : trace.marker === undefined || trace.marker.line === undefined || trace.marker.line.color === undefined ?  null : trace.marker.line.color,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" ) || parseInt(trace.marker.line.width) === 0  ? true : false,
                hint : "Sets themarker.linecolor. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.line.cmin` and `marker.line.cmax` if set."
              },
              {
                id : `traces[${index}][marker][line][width]`, 
                title : "Line Width", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : trace.marker === undefined || trace.marker.line === undefined || trace.marker.line.width === undefined ? null : trace.marker.line.width,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" )  ? true : false,
                hint : "Sets the width (in px) of the lines bounding the marker points.   Number or array of numbers greater than or equal to 0"
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${index}][marker][gradient][type]`, 
                title : "Gradient Type", 	
                type : "select",
                options : {
                  none : "None",
                  radial : "Radial",
                  horizontal : "Horizontal",
                  vertical: "Vertical"
                },
                value : trace.marker === undefined || trace.marker.gradient === undefined || trace.marker.gradient.type === undefined ? null : trace.marker.gradient.type,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the type of gradient used to fill the markers"
              },
              {
                id : `traces[${index}][marker][gradient][color]`, 
                title : "Gradient Color", 	
                type : "color",
                value :  trace.marker === undefined || trace.marker.gradient === undefined || trace.marker.gradient.color === undefined ? null : trace.marker.gradient.color,
                disabled: false === trace.visible || ! trace.mode.includes( "marker" ) || trace.marker.gradient.type === "none" ? true : false,
                hint : "Sets the final color of the gradient fill: the center color for radial, the right for horizontal, or the bottom for vertical."
              },
            ],
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${index}][marker][maxdisplayed]`, 
                title : "Maximum Displayed", 	
                type : "number",
                min : 0,
                max : 20000,
                step : 1,
                value : trace.marker === undefined || trace.marker.maxdisplayed === undefined ? null : trace.marker.maxdisplayed,
                disabled: true !== trace.visible || ! trace.mode.includes( "marker" )  ? true : false,
                hint : "Sets a maximum number of points to be drawn on the graph. '0' corresponds to no limit."
              },
            ],
          },
        ]
      },

      Line: {
        intro : `Here you can modify the lines of trace "${trace.name}`,
        title : "Lines",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${index}][line][dash]`, 
                title : "Type", 	
                type : "text",
                value : trace.line === undefined || trace.line.dash === undefined ? null : trace.line.dash,
                disabled: false === trace.visible || ! trace.mode.includes( "lines" ) ? true : false,
                hint : "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
              },
              {
                id : `traces[${index}][line][shape]`,  
                title : "Shape",  
                type : "select",
                options : {
                  linear : "Linear",
                  spline : "Spline",
                  hv : "Horizontal/Vertical",
                  vh: "Vertical/Horizontal",
                  hvh: "Horizontal/Vertical/Horizontal",
                  vhv: "Vertical/Horizontal/Vertical"
                },
                value : trace.line === undefined || trace.line.shape === undefined ? null : trace.line.shape,
                disabled: true !== trace.visible || ! trace.mode.includes( "lines" ) ? true : false,
                hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${index}][line][width]`, 
                title : "Width", 	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : trace.line === undefined || trace.line.width === undefined ? null : trace.line.width,
                disabled: false === trace.visible || ! trace.mode.includes( "lines" ) ? true : false,
                hint : "Sets the width of the trace line."
              },
              {
                id : `traces[${index}][line][color]`,  
                title : "Color",  
                type : "color",
                value : trace.line === undefined || trace.line.color === undefined ? null : trace.line.color,
                disabled: false === trace.visible || ! trace.mode.includes( "lines" ) || parseInt(trace.line.width) === 0 ? true : false,
                hint : "Sets the color of the trace line"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${index}][line][simplify]`, 
                title : "Simplify", 	
                type : "checkbox",
                value : trace.line === undefined && trace.line.simplify === undefined ? false : trace.line.simplify,
                disabled: true !== trace.visible || ! trace.mode.includes( "lines" )  ? true : false,
                hint : "Simplifies lines by removing nearly-collinear points. When transitioning lines, it may be desirable to disable this so that the number of points along the resulting SVG path is unaffected."
              },
              {
                id : `traces[${index}][line][smoothing]`, 
                title : "Smoothing", 	
                type : "number",
                min : 0,
                max : 1.3,
                step : 0.01,
                value : trace.line === undefined || trace.line.smoothing === undefined ? null : trace.line.smoothing,
                disabled: true !== trace.visible || ! trace.mode.includes( "lines" ) ? true : false,
                hint : "Has an effect only if `shape` is set to 'spline' Sets the amount of smoothing. '0' corresponds to no smoothing (equivalent to a 'linear' shape). Number between or equal to 0 and 1.3."
              }, 
            ],
          },
        ]
      },

      text: {
        intro : `Here you can modify the other of trace "${trace.name}`,
        title : "Text",
        fieldGroups : [
          {        
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${index}][text]`, 
                title : "Text", 	
                type : "text",
                value : trace.text === undefined ? null : Array.isArray( trace.text ) ? trace.text.join() : trace.text,
                disabled: true !== trace.visible || ! trace.mode.includes( "text" ) ? true : false,
                hint : "Sets text elements associated with each (x,y) pair. If a single string, the same string appears over all the data points. If an array of string, the items are mapped in order to the this trace's (x,y) coordinates. If trace `hoverinfo` contains a 'text' flag and 'hovertext' is not set, these elements will be seen in the hover labels."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${index}][textfont][family]`,
                title : "Text Font",	
                type : "select",
                options : fontFamily(),
                value : trace.textfont === undefined || trace.textfont.family === undefined ? null : trace.textfont.family,
                disabled: true !== trace.visible || ! trace.text || ! trace.mode.includes( "text" ) ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `traces[${index}][textfont][size]`, 
                title : "Text Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : trace.textfont === undefined || trace.textfont.size === undefined ? null : trace.textfont.size,
                disabled: true !== trace.visible || ! trace.text || ! trace.mode.includes( "text" ) ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${index}][textfont][color]`,
                title : "Text Font Color",
                type : "color", 
                value : trace.textfont === undefined || trace.textfont.color === undefined ? null : trace.textfont.color,
                disabled: true !== trace.visible || ! trace.text || ! trace.mode.includes( "text" ) ? true : false,
              }, 
              {
                id : `traces[${index}][textposition]`, 
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
                value : trace.textposition === undefined ? null : trace.textposition,
                disabled: true !== trace.visible || ! trace.text || ! trace.mode.includes( "text" ) ? true : false,
                hint : "Sets the positions of the `text` elements with respects to the (x,y) coordinates."
              },
            ],
          },
        ]
      },

      hoverlabel: {
        intro : `Here you can modify the other of trace "${trace.name}`,
        title : "Hover Text & Label",
        fieldGroups : [
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${index}][hovertext]`, 
                title : "Hover Text", 	
                type : "text",
                value : trace.hovertext === undefined ? null : Array.isArray( trace.hovertext ) ? trace.hovertext.join() : trace.hovertext,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
                hint : "Sets hover text elements associated with each (x,y) pair. If a single string, the same string appears over all the data points. If an array of string, the items are mapped in order to the this trace's (x,y) coordinates. To be seen, trace `hoverinfo` must contain a 'text' flag."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${index}][hoverlabel][align]`, 
                title : "Hover Label Alignment", 
                type : "select",
                options : {
                  auto : "Auto",
                  left : "Left",
                  right : "Right",
                },
                value : trace.hoverlabel === undefined || trace.hoverlabel.align === undefined ? null : trace.hoverlabel.align,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
                hint : "Type: enumerated or array of enumerateds , one of ( 'left' | 'right' | 'auto' ).  Sets the horizontal alignment of the text content within hover label box. Has an effect only if the hover label text spans more two or more lines.  Default: 'auto'"
              },
              {
                id : `traces[${index}][hoverlabel][namelength]`, 
                title : "Hover Label Name Length", 
                type : "number",
                min : -1,
                max : 1000,
                step : 1,
                value : trace.hoverlabel === undefined || trace.hoverlabel.namelength === undefined ? null : trace.hoverlabel.namelength,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
                hint : "Sets the default length (in number of characters) of the trace name in the hover labels for all traces. -1 shows the whole name regardless of length. 0-3 shows the first 0-3 characters, and an integer >3 will show the whole name if it is less than that many characters, but if it is longer, will truncate to `namelength - 3` characters and add an ellipsis."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${index}][hoverlabel][font][family]`,
                title : "Hover Label Font",	
                type : "select",
                options : fontFamily(),
                value : trace.hoverlabel === undefined || trace.hoverlabel.font === undefined || trace.hoverlabel.font.family === undefined ? null : trace.hoverlabel.font.family,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `traces[${index}][hoverlabel][font][color]`,
                title : "Hover Label Font Color",
                type : "color", 
                value : trace.hoverlabel === undefined || trace.hoverlabel.font === undefined || trace.hoverlabel.font.color === undefined ? null : trace.hoverlabel.font.color,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${index}][hoverlabel][bgcolor]`,
                title : "Hover label Bg. Color",
                type : "color", 
                value : trace.hoverlabel === undefined || trace.hoverlabel.bgcolor === undefined ? null : trace.hoverlabel.bgcolor,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
                hint: "Sets the background color of the hover labels for this trace"
              },
              {
                id : `traces[${index}][hoverlabel][font][size]`, 
                title : "Hover Label Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : trace.hoverlabel === undefined || trace.hoverlabel.font === undefined || trace.hoverlabel.font.size === undefined ? null : trace.hoverlabel.font.size,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${index}][hoverinfo]`, 
                title : "Hover Info.", 	
                type : "select",
                options : {
                  all: "All",
                  x : "X",
                  y : "Y",
                  text : "Text",
                  name : "Name",
                  "name+text": "Name & Text",
                  none : "None",
                  skip: "Skip",
                },
                value : trace.hoverinfo === undefined ? null : trace.hoverinfo,
                disabled: true !== trace.visible ? true : false,
                hint : "Any combination of 'x', 'y', 'z', 'text', 'name' joined with a '+' OR 'all' or 'none' or 'skip'. Examples: 'x', 'y', 'x+y', 'x+y+z', 'all'.  Determines which trace information appear on hover. If `none` or `skip` are set, no information is displayed upon hovering. But, if `none` is set, click and hover events are still fired.  Default: 'all'"
              },
              {
                id : `traces[${index}][hoverlabel][bordercolor]`,
                title : "Hover label Border Color",
                type : "color", 
                value : trace.hoverlabel === undefined || trace.hoverlabel.font === undefined || trace.hoverlabel.font.bordercolor === undefined ? null : trace.hoverlabel.font.bordercolor,
                disabled: true !== trace.visible || "skip" === trace.hoverinfo ||  "none" === trace.hoverinfo ? true : false,
                hint: "Sets the border color of the hover labels for this trace."
              },
            ],
          },
        ]
      },

      error_y: {
        intro : `Here you can modify the other of trace "${trace.name}`,
        title : "Error Y",
        fieldGroups : [
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${index}][error_y][visible]`,
                title : "Show Error",	
                type : "checkbox",
                value : trace.error_y === undefined && trace.error_y.visible === undefined ? false : trace.error_y.visible,
                disabled: true !== trace.visible  ? true : false,
                hint: "Determines whether or not this set of error bars is visible."
              },
              {
                id : `traces[${index}][error_y][type]`, 
                title : "Error Type", 
                type : "select",
                options : {
                  percent : "Percent",
                  constant : "Constant",
                  sqrt : "Square",
                  data: "Data",
                },
                value : trace.error_y === undefined || trace.error_y.type === undefined ? null : trace.error_y.type,
                disabled: ( ! trace.error_y.visible  || true !== trace.visible ) ? true : false,
                hint : "Determines the rule used to generate the error bars. If 'constant`, the bar lengths are of a constant value. Set this constant in `value`. If 'percent', the bar lengths correspond to a percentage of underlying data. Set this percentage in `value`. If 'sqrt', the bar lengths correspond to the square of the underlying data. If 'data', the bar lengths are set with data set `array`."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${index}][error_y][value]`,
                title : "Value",
                type : "number", 
                value : trace.error_y === undefined || trace.error_y.value === undefined ? null : trace.error_y.value,
                disabled: ( ! trace.error_y.visible || trace.error_y.type === "data" || true !== trace.visible ) ? true : false,
                hint: "Sets the value of either the percentage (if `type` is set to 'percent') or the constant (if `type` is set to 'constant') corresponding to the lengths of the error bars.  Number greater than or equal to 0"
              },
              {
                id : `traces[${index}][error_y][valueminus]`,
                title : "Value Minus",
                type : "number", 
                value : trace.error_y === undefined || trace.error_y.valueminus === undefined ? null : trace.error_y.valueminus,
                disabled: ( ! trace.error_y.visible || trace.error_y.type === "data" || trace.error_y.symmetric || true !== trace.visible ) ? true : false,
                hint: "Sets the value of either the percentage (if `type` is set to 'percent') or the constant (if `type` is set to 'constant') corresponding to the lengths of the error bars in the bottom (left) direction for vertical (horizontal) bars"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${index}][error_y][array]`,
                title : "Error Array",	
                type : "text",
                value : trace.error_y === undefined || trace.error_y.array === undefined ? null: trace.error_y.array.join(),
                disabled: ( ! trace.error_y.visible || trace.error_y.type !== "data" || true !== trace.visible ) ? true : false,
                hint: "Sets the data corresponding the length of each error bar. Values are plotted relative to the underlying data. Determines whether or not this set of error bars is array."
              },
              {
                id : `traces[${index}][error_y][arrayminus]`, 
                title : "Error Array Minus", 
                type : "text",
                value : trace.error_y === undefined || trace.error_y.arrayminus=== undefined ? null: trace.error_y.arrayminus.join(),
                disabled: ( ! trace.error_y.visible || trace.error_y.type !== "data" || trace.error_y.symmetric || true !== trace.visible ) ? true : false,
                hint : "Sets the data corresponding the length of each error bar in the bottom (left) direction for vertical (horizontal) bars Values are plotted relative to the underlying data."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${index}][error_y][thickness]`, 
                title : "Error Bar Line Thickness", 
                type : "number",
                min : 1,
                max : 1000,
                step : 1,
                value : trace.error_y === undefined || trace.error_y.thickness === undefined ? null : trace.error_y.thickness,
                disabled:  ( ! trace.error_y.visible || true !== trace.visible )  ? true : false,
                hint : "Sets the thickness (in px) of the error bars."
              },
              {
                id : `traces[${index}][error_y][color]`,
                title : "Error Bar Color",
                type : "color", 
                value :  trace.error_y === undefined || trace.error_y.color === undefined ? null : trace.error_y.color,
                disabled: ( ! trace.error_y.visible || true !== trace.visible )? true : false,
                hint: "Sets the stoke color of the error bars."
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${index}][error_y][symmetric]`,
                title : "Symmetric ?",
                type : "checkbox", 
                value : trace.error_y === undefined && trace.error_y.symmetric === undefined ? false : trace.error_y.symmetric,
                disabled: ( ! trace.error_y.visible || true !== trace.visible ) ? true : false,
                hint: "Determines whether or not the error bars have the same length in both direction (top/bottom for vertical bars, left/right for horizontal bars."
              },
              {
                id : `traces[${index}][error_y][width]`, 
                title : "Error Bar Width", 
                type : "number",
                min : 1,
                max : 1000,
                step : 1,
                value : trace.error_y === undefined || trace.error_y.width === undefined ? null : trace.error_y.width,
                disabled: ( ! trace.error_y.visible || true !== trace.visible )  ? true : false,
                hint : "Sets the width (in px) of the cross-bar at both ends of the error bars."
              },
            ],
          },
        ]
      }

    }
  }

}

export default Trace;