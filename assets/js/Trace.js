import { fontFamily } from "./utilities"

class Trace {

  constructor( trace, index ) {

    this.trace = trace;
    this.index = index;

     console.log(this.trace)

  }

  options() {

    return {

      // type : ( this.trace === undefined ||  this.trace.type === undefined ) ? this.trace.type :  this.type,
      // name : this.labels[this.index],
      // visible : ( this.trace.visible === undefined ) ? true : "false" === this.trace.visible ? false : "true" === this.trace.visible ? true : this.trace.visible,
      // showlegend : ( this.trace.showlegend === undefined ) ? true : this.trace.showlegend,
      // opacity : ( this.trace.opacity === undefined ) ? 1 : this.trace.opacity,
      // mode : ( this.trace.mode === undefined ) ? this.mode :this.trace.mode,
      // text : ( this.trace.text === undefined ) ? ["a", "b", "c"] : this.trace.text,
      // textposition : ( this.trace.textposition === undefined ) ? "bottom center" : this.trace.textposition,
      // hovertext : ( this.trace.hovertext === undefined ) ? "" : this.trace.hovertext,
      // hoverinfo : ( this.trace.hoverinfo === undefined ) ? "all" : this.trace.hoverinfo,
      // x : this.spreadsheet[this.sheetId].data[0],
      // y : this.spreadsheet[this.sheetId].data[this.index],
      // xaxis : ( this.trace.xaxis === undefined ) ? "x" : this.trace.xaxis,
      // yaxis : ( this.trace.yaxis === undefined ) ? "y" : this.trace.yaxis,
      // connectgaps : (  this.trace.connectgaps === undefined ) ?  false : this.trace.connectgaps,
      // textfont : {
      //   family : (  this.trace.textfont === undefined || this.trace.textfont.family === undefined ) ? Object.keys(fontFamily())[13] : this.trace.textfont.family,
      //   size : (  this.trace.textfont === undefined || this.trace.textfont.size === undefined ) ? 20 : this.trace.textfont.size,
      //   color : (  this.trace.textfont === undefined || this.trace.textfont.color === undefined ) ? "#1B5E20" : this.trace.textfont.color,
      // },
      // marker : {
      //   symbol : (  this.trace.marker === undefined || this.trace.marker.symbol === undefined ) ? 1 : this.trace.marker.symbol,
      //   opacity: (  this.trace.marker === undefined || this.trace.marker.opacity === undefined ) ? 1 : this.trace.marker.opacity,
      //   size: (  this.trace.marker === undefined || this.trace.marker.size === undefined ) ? 5 : this.trace.marker.size,
      //   maxdisplayed : (  this.trace.marker === undefined || this.trace.marker.maxdisplayed === undefined ) ? 10 : this.trace.marker.maxdisplayed,
      //   color : (  this.trace.marker === undefined || this.trace.marker.color === undefined ) ? colors()[this.index] : this.trace.marker.color,
      //   line: {
      //     width: (  this.trace.marker === undefined || this.trace.marker.line === undefined || this.trace.marker.line.width === undefined ) ? 1 : this.trace.marker.line.width,
      //     color: (  this.trace.marker === undefined || this.trace.marker.line === undefined || this.trace.marker.line.color === undefined ) ?  "#4A148C" : this.trace.marker.line.color,
      //   },
      //   gradient: {
      //     type: (  this.trace.marker === undefined || this.trace.marker.gradient === undefined || this.trace.marker.gradient.type === undefined ) ? "radial" : this.trace.marker.gradient.type,
      //     color: (  this.trace.marker === undefined || this.trace.marker.gradient === undefined || this.trace.marker.gradient.color === undefined ) ? "#E65100" : this.trace.marker.gradient.color,
      //   },  
      // },
      // line : {
      //   shape: ( this.trace.line === undefined || this.trace.line.shape === undefined ) ? "spline" : this.trace.line.shape,
      //   width: ( this.trace.line === undefined || this.trace.line.width === undefined ) ? 2 : this.trace.line.width,
      //   color : ( this.trace.line === undefined || this.trace.line.color === undefined ) ? colors()[this.index] : this.trace.line.color,
      //   dash: ( this.trace.line === undefined || this.trace.line.dash === undefined ) ? "solid" : this.trace.line.dash,
      //   smoothing: ( this.trace.line === undefined || this.trace.line.smoothing === undefined ) ? 1 : this.trace.line.smoothing,
      //   simplify: ( this.trace.line === undefined || this.trace.line.simplify === undefined ) ? true : this.trace.line.simplify,
      // },
      // error_y : {
      //   visible: ( this.trace.error_y === undefined || this.trace.error_y.visible === undefined ) ? false : this.trace.error_y.visible,
      //   type: ( this.trace.error_y === undefined || this.trace.error_y.type === undefined ) ? "percent": this.trace.error_y.type,
      //   symmetric: ( this.trace.error_y === undefined || this.trace.error_y.symmetric === undefined ) ? true: this.trace.error_y.symmetric,
      //   value: ( this.trace.error_y === undefined || this.trace.error_y.value === undefined ) ? 20 : this.trace.error_y.value,
      //   valueminus: ( this.trace.error_y === undefined || this.trace.error_y.valueminus === undefined ) ? 20 : this.trace.error_y.valueminus,
      //   array: ( this.trace.error_y === undefined || this.trace.error_y.array === undefined ) ? []: this.trace.error_y.array,
      //   arrayminus: ( this.trace.error_y === undefined || this.trace.error_y.arrayminus === undefined ) ? [] : this.trace.error_y.arrayminus,
      //   color: ( this.trace.error_y === undefined || this.trace.error_y.color === undefined ) ? colors()[this.index] : this.trace.error_y.color,
      //   thickness: ( this.trace.error_y === undefined || this.trace.error_y.thickness === undefined ) ? 2: this.trace.error_y.thickness,
      //   width: ( this.trace.error_y === undefined || this.trace.error_y.width === undefined ) ? 4: this.trace.error_y.width,
      // },
      // hoverlabel : {
      //   bgcolor: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.bgcolor === undefined ) ? null : this.trace.hoverlabel.bgcolor,
      //   bordercolor: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.bordercolor === undefined ) ? "#000a12" : this.trace.hoverlabel.bordercolor,
      //   font : {
      //     family : ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.family === undefined ) ? Object.keys(fontFamily())[13] : this.trace.hoverlabel.font.family,
      //     size : ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.size === undefined ) ? 20 : this.trace.hoverlabel.font.size,
      //     color : ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.color === undefined ) ? null: this.trace.hoverlabel.font.color,
      //   },
      //   align: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.align === undefined ) ? "auto" : this.trace.hoverlabel.align,
      //   namelength: ( this.trace.hoverlabel === undefined || this.trace.hoverlabel.namelength === undefined ) ? -1 : this.trace.hoverlabel.namelength,
      // }
    }

  }

  sections() {

    return {
      
      basicOptions: {
        intro : `Here you can modify the basic options of trace "${this.trace.name}"`,
        title : "Basic Options",
        fieldGroups : [
          {
            cssClasses : ["field-group", "sixty-forty"],     
            inputFields : [
              {
                id : `traces[${this.index}][visible]`,  
                title : "Trace Visibility",  
                type : "select",
                options : {
                  true : "Visible",
                  false : "Hidden",
                  legendonly : "Legend Only",
                },
                value : this.trace.visible === undefined ? null : true === this.trace.visible ? "true" : false === this.trace.visible ? "false" : this.trace.visible,
                hint : "Determines whether or not this trace is visible. If 'legendonly', the trace is not drawn, but can appear as a legend item (provided that the legend itself is visible). Default: visible"
              },
              {
                id : `traces[${this.index}][showlegend]`, 
                title : "Show In Legend", 	
                type : "checkbox",
                value : this.trace.showlegend === undefined ? null : this.trace.showlegend,
                disabled: false === this.trace.visible  ? true : false,
                hint : "Determines whether or not an item corresponding to this trace is shown in the legend."
              },
            ]
          },
          {
            cssClasses : ["field-group"],     
            inputFields : [
              {
                id : `traces[${this.index}][mode]`, 
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
                value : this.trace.mode === undefined ? null :this.trace.mode,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Determines the drawing mode for this scatter trace. If the provided `mode` includes 'text' then the `text` elements appear at the coordinates. Otherwise, the `text` elements appear on hover. If there are less than 20 points and the trace is not stacked then the default is 'lines+markers'. Otherwise, 'lines'."
              },
            ]
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${this.index}][name]`,  
                title : "Name",  
                type : "text",
                value : this.trace.name === undefined ? null :this.trace.name,
                disabled: false === this.trace.visible  ? true : false,
                hint : "The trace name appear as the legend item and on hover."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][xaxis]`, 
                title : "x-Axis", 	
                type : "select", 
                options : {
                  x : "Bottom",
                  x2 : "Top",
                },
                value : this.trace.xaxis === undefined ? null : this.trace.xaxis,
                disabled: true !== this.trace.visible || ! this.trace.showlegend  ? true : false,
                hint : "Sets a reference between this trace's x coordinates and a 2D cartesian x axis. If 'x' (the default value), the x coordinates refer to `layout.xaxis`. If 'x2', the x coordinates refer to `layout.xaxis2`, and so on."
              },
              {
                id : `traces[${this.index}][yaxis]`, 
                title : "Y-Axis", 	
                type : "select", 
                options : {
                  y : "Left",
                  y2 : "Right",
                },
                value : this.trace.yaxis === undefined ? null: this.trace.yaxis,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Sets a reference between this trace's y coordinates and a 2D cartesian y axis. If 'y' (the default value), the y coordinates refer to `layout.yaxis`. If 'y2', the y coordinates refer to `layout.yaxis2`, and so on."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],     
            inputFields : [
              {
                id : `traces[${this.index}][connectgaps]`, 
                title : "Connect Gaps", 
                type : "checkbox",
                value : this.trace.connectgaps === undefined ?  null : this.trace.connectgaps,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Determines whether or not gaps (i.e. {nan} or missing values) in the provided data arrays are connected."
              },
              {
                id : `traces[${this.index}][opacity]`, 
                title : "Trace Opacity", 	
                type : "number",
                min : 0,
                max : 1,
                step : 0.02,
                value : this.trace.opacity === undefined ? null : this.trace.opacity,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Sets the opacity of the trace."
              },
            ]
          },
        ],
      },

      markers: {
        intro : `Here you can modify the markers of trace "${this.trace.name}`,
        title : "Markers",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][marker][symbol]`, 
                title : "Symbol", 	
                type : "number",
                min : 1,
                max : 300,
                step : 1,
                value :  this.trace.marker === undefined || this.trace.marker.symbol === undefined ? null : this.trace.marker.symbol,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the marker symbol type. Adding 100 is equivalent to appending '-open' to a symbol name. Adding 200 is equivalent to appending '-dot' to a symbol name. Adding 300 is equivalent to appending '-open-dot' or 'dot-open' to a symbol name."
              },
              {
                id : `traces[${this.index}][marker][size]`, 
                title : "Size", 	
                type : "number",
                min : 1,
                max : 2000,
                step : 1,
                value : this.trace.marker === undefined || this.trace.marker.size === undefined ? null : this.trace.marker.size,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the marker size (in px).  Number or array of numbers greater than or equal to 0"
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${this.index}][marker][opacity]`, 
                title : "Opacity", 	
                type : "number",
                min : 0,
                max : 1,
                step : .01,
                value : this.trace.marker === undefined || this.trace.marker.opacity === undefined ? null : this.trace.marker.opacity,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the marker's opacity."
              },
              {
                id : `traces[${this.index}][marker][color]`,  
                title : "Color",  
                type : "color",
                value : this.trace.marker === undefined || this.trace.marker.color === undefined ? null : this.trace.marker.color,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" ) ? true : false,
                hint : ""
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${this.index}][marker][line][color]`, 
                title : "Line Color", 	
                type : "color",
                value : this.trace.marker === undefined || this.trace.marker.line === undefined || this.trace.marker.line.color === undefined ?  null : this.trace.marker.line.color,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" ) || parseInt(this.trace.marker.line.width) === 0  ? true : false,
                hint : "Sets themarker.linecolor. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.line.cmin` and `marker.line.cmax` if set."
              },
              {
                id : `traces[${this.index}][marker][line][width]`, 
                title : "Line Width", 	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.trace.marker === undefined || this.trace.marker.line === undefined || this.trace.marker.line.width === undefined ? null : this.trace.marker.line.width,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" )  ? true : false,
                hint : "Sets the width (in px) of the lines bounding the marker points.   Number or array of numbers greater than or equal to 0"
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${this.index}][marker][gradient][type]`, 
                title : "Gradient Type", 	
                type : "select",
                options : {
                  none : "None",
                  radial : "Radial",
                  horizontal : "Horizontal",
                  vertical: "Vertical"
                },
                value : this.trace.marker === undefined || this.trace.marker.gradient === undefined || this.trace.marker.gradient.type === undefined ? null : this.trace.marker.gradient.type,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" ) ? true : false,
                hint : "Sets the type of gradient used to fill the markers"
              },
              {
                id : `traces[${this.index}][marker][gradient][color]`, 
                title : "Gradient Color", 	
                type : "color",
                value :  this.trace.marker === undefined || this.trace.marker.gradient === undefined || this.trace.marker.gradient.color === undefined ? null : this.trace.marker.gradient.color,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "marker" ) || this.trace.marker.gradient.type === "none" ? true : false,
                hint : "Sets the final color of the gradient fill: the center color for radial, the right for horizontal, or the bottom for vertical."
              },
            ],
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${this.index}][marker][maxdisplayed]`, 
                title : "Maximum Displayed", 	
                type : "number",
                min : 0,
                max : 20000,
                step : 1,
                value : this.trace.marker === undefined || this.trace.marker.maxdisplayed === undefined ? null : this.trace.marker.maxdisplayed,
                disabled: true !== this.trace.visible || ! this.trace.mode.includes( "marker" )  ? true : false,
                hint : "Sets a maximum number of points to be drawn on the graph. '0' corresponds to no limit."
              },
            ],
          },
        ]
      },

      Line: {
        intro : `Here you can modify the lines of trace "${this.trace.name}`,
        title : "Lines",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][line][dash]`, 
                title : "Type", 	
                type : "text",
                value : this.trace.line === undefined || this.trace.line.dash === undefined ? null : this.trace.line.dash,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "lines" ) ? true : false,
                hint : "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
              },
              {
                id : `traces[${this.index}][line][shape]`,  
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
                value : this.trace.line === undefined || this.trace.line.shape === undefined ? null : this.trace.line.shape,
                disabled: true !== this.trace.visible || ! this.trace.mode.includes( "lines" ) ? true : false,
                hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${this.index}][line][width]`, 
                title : "Width", 	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.trace.line === undefined || this.trace.line.width === undefined ? null : this.trace.line.width,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "lines" ) ? true : false,
                hint : "Sets the width of the trace line."
              },
              {
                id : `traces[${this.index}][line][color]`,  
                title : "Color",  
                type : "color",
                value : this.trace.line === undefined || this.trace.line.color === undefined ? null : this.trace.line.color,
                disabled: false === this.trace.visible || ! this.trace.mode.includes( "lines" ) || parseInt(this.trace.line.width) === 0 ? true : false,
                hint : "Sets the color of the trace line"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${this.index}][line][simplify]`, 
                title : "Simplify", 	
                type : "checkbox",
                value : this.trace.line === undefined || this.trace.line.simplify === undefined ? null : this.trace.line.simplify,
                disabled: true !== this.trace.visible || ! this.trace.mode.includes( "lines" )  ? true : false,
                hint : "Simplifies lines by removing nearly-collinear points. When transitioning lines, it may be desirable to disable this so that the number of points along the resulting SVG path is unaffected."
              },
              {
                id : `traces[${this.index}][line][smoothing]`, 
                title : "Smoothing", 	
                type : "number",
                min : 0,
                max : 1.3,
                step : 0.01,
                value : this.trace.line === undefined || this.trace.line.smoothing === undefined ? null : this.trace.line.smoothing,
                disabled: true !== this.trace.visible || ! this.trace.mode.includes( "lines" ) ? true : false,
                hint : "Has an effect only if `shape` is set to 'spline' Sets the amount of smoothing. '0' corresponds to no smoothing (equivalent to a 'linear' shape). Number between or equal to 0 and 1.3."
              }, 
            ],
          },
        ]
      },

      text: {
        intro : `Here you can modify the other of trace "${this.trace.name}`,
        title : "Text",
        fieldGroups : [
          {        
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${this.index}][text]`, 
                title : "Text", 	
                type : "text",
                value : this.trace.text === undefined ? null : Array.isArray( this.trace.text ) ? this.trace.text.join() : this.trace.text,
                disabled: true !== this.trace.visible || ! this.trace.mode.includes( "text" ) ? true : false,
                hint : "Sets text elements associated with each (x,y) pair. If a single string, the same string appears over all the data points. If an array of string, the items are mapped in order to the this trace's (x,y) coordinates. If trace `hoverinfo` contains a 'text' flag and 'hovertext' is not set, these elements will be seen in the hover labels."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${this.index}][textfont][family]`,
                title : "Text Font",	
                type : "select",
                options : fontFamily(),
                value : this.trace.textfont === undefined || this.trace.textfont.family === undefined ? null : this.trace.textfont.family,
                disabled: true !== this.trace.visible || ! this.trace.text || ! this.trace.mode.includes( "text" ) ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `traces[${this.index}][textfont][size]`, 
                title : "Text Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.trace.textfont === undefined || this.trace.textfont.size === undefined ? null : this.trace.textfont.size,
                disabled: true !== this.trace.visible || ! this.trace.text || ! this.trace.mode.includes( "text" ) ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${this.index}][textfont][color]`,
                title : "Text Font Color",
                type : "color", 
                value : this.trace.textfont === undefined || this.trace.textfont.color === undefined ? null : this.trace.textfont.color,
                disabled: true !== this.trace.visible || ! this.trace.text || ! this.trace.mode.includes( "text" ) ? true : false,
              }, 
              {
                id : `traces[${this.index}][textposition]`, 
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
                value : this.trace.textposition === undefined ? null : this.trace.textposition,
                disabled: true !== this.trace.visible || ! this.trace.text || ! this.trace.mode.includes( "text" ) ? true : false,
                hint : "Sets the positions of the `text` elements with respects to the (x,y) coordinates."
              },
            ],
          },
        ]
      },

      // hovertext: {
      //   intro : `Here you can modify the other of trace "${this.trace.name}`,
      //   title : "Hover Text",
      //   fieldGroups : [
          
      //   ]
      // },

      hoverlabel: {
        intro : `Here you can modify the other of trace "${this.trace.name}`,
        title : "Hover Text & Label",
        fieldGroups : [
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `traces[${this.index}][hovertext]`, 
                title : "Hover Text", 	
                type : "text",
                value : this.trace.hovertext === undefined ? null : Array.isArray( this.trace.hovertext ) ? this.trace.hovertext.join() : this.trace.hovertext,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Sets hover text elements associated with each (x,y) pair. If a single string, the same string appears over all the data points. If an array of string, the items are mapped in order to the this trace's (x,y) coordinates. To be seen, trace `hoverinfo` must contain a 'text' flag."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][hoverlabel][align]`, 
                title : "Hover Label Alignment", 
                type : "select",
                options : {
                  auto : "Auto",
                  left : "Left",
                  right : "Right",
                },
                value : this.trace.hoverlabel === undefined || this.trace.hoverlabel.align === undefined ? null : this.trace.hoverlabel.align,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Type: enumerated or array of enumerateds , one of ( 'left' | 'right' | 'auto' ).  Sets the horizontal alignment of the text content within hover label box. Has an effect only if the hover label text spans more two or more lines.  Default: 'auto'"
              },
              {
                id : `traces[${this.index}][hoverlabel][namelength]`, 
                title : "Hover Label Name Length", 
                type : "number",
                min : -1,
                max : 1000,
                step : 1,
                value : this.trace.hoverlabel === undefined || this.trace.hoverlabel.namelength === undefined ? null : this.trace.hoverlabel.namelength,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Sets the default length (in number of characters) of the trace name in the hover labels for all traces. -1 shows the whole name regardless of length. 0-3 shows the first 0-3 characters, and an integer >3 will show the whole name if it is less than that many characters, but if it is longer, will truncate to `namelength - 3` characters and add an ellipsis."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${this.index}][hoverlabel][font][family]`,
                title : "Hover Label Font",	
                type : "select",
                options : fontFamily(),
                value : this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.family === undefined ? null : this.trace.hoverlabel.font.family,
                disabled: true !== this.trace.visible  ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `traces[${this.index}][hoverlabel][font][color]`,
                title : "Hover Label Font Color",
                type : "color", 
                value : this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.color === undefined ? null : this.trace.hoverlabel.font.color,
                disabled: true !== this.trace.visible  ? true : false,
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `traces[${this.index}][hoverlabel][bgcolor]`,
                title : "Hover label Bg. Color",
                type : "color", 
                value : this.trace.hoverlabel === undefined || this.trace.hoverlabel.bgcolor === undefined ? null : this.trace.hoverlabel.bgcolor,
                disabled: true !== this.trace.visible  ? true : false,
                hint: "Sets the background color of the hover labels for this trace"
              },
              {
                id : `traces[${this.index}][hoverlabel][font][size]`, 
                title : "Hover Label Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.size === undefined ? null : this.trace.hoverlabel.font.size,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `traces[${this.index}][hoverinfo]`, 
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
                value : this.trace.hoverinfo === undefined ? null : this.trace.hoverinfo,
                disabled: true !== this.trace.visible  ? true : false,
                hint : "Any combination of 'x', 'y', 'z', 'text', 'name' joined with a '+' OR 'all' or 'none' or 'skip'. Examples: 'x', 'y', 'x+y', 'x+y+z', 'all'.  Determines which trace information appear on hover. If `none` or `skip` are set, no information is displayed upon hovering. But, if `none` is set, click and hover events are still fired.  Default: 'all'"
              },
              {
                id : `traces[${this.index}][hoverlabel][bordercolor]`,
                title : "Hover label Border Color",
                type : "color", 
                value : this.trace.hoverlabel === undefined || this.trace.hoverlabel.font === undefined || this.trace.hoverlabel.font.bordercolor === undefined ? null : this.trace.hoverlabel.font.bordercolor,
                disabled: true !== this.trace.visible  ? true : false,
                hint: "Sets the border color of the hover labels for this trace."
              },
            ],
          },
        ]
      },

      error_y: {
        intro : `Here you can modify the other of trace "${this.trace.name}`,
        title : "Error Y",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][error_y][visible]`,
                title : "Show Error",	
                type : "checkbox",
                value : this.trace.error_y === undefined || this.trace.error_y.visible === undefined ? false : this.trace.error_y.visible,
                disabled: true !== this.trace.visible  ? true : false,
                hint: "Determines whether or not this set of error bars is visible."
              },
              {
                id : `traces[${this.index}][error_y][type]`, 
                title : "Error Type", 
                type : "select",
                options : {
                  percent : "Percent",
                  constant : "Constant",
                  sqrt : "Square",
                  data: "Data",
                },
                value : this.trace.error_y === undefined || this.trace.error_y.type === undefined ? "percent": this.trace.error_y.type,
                disabled: ( ! this.trace.error_y.visible  || true !== this.trace.visible ) ? true : false,
                hint : "Determines the rule used to generate the error bars. If 'constant`, the bar lengths are of a constant value. Set this constant in `value`. If 'percent', the bar lengths correspond to a percentage of underlying data. Set this percentage in `value`. If 'sqrt', the bar lengths correspond to the square of the underlying data. If 'data', the bar lengths are set with data set `array`."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][error_y][value]`,
                title : "Value",
                type : "number", 
                value : this.trace.error_y === undefined || this.trace.error_y.value === undefined ? 10 : this.trace.error_y.value,
                disabled: ( ! this.trace.error_y.visible || this.trace.error_y.type === "data" || true !== this.trace.visible ) ? true : false,
                hint: "Sets the value of either the percentage (if `type` is set to 'percent') or the constant (if `type` is set to 'constant') corresponding to the lengths of the error bars.  Number greater than or equal to 0"
              },
              {
                id : `traces[${this.index}][error_y][valueminus]`,
                title : "Value Minus",
                type : "number", 
                value : this.trace.error_y === undefined || this.trace.error_y.valueminus === undefined ? 20 : this.trace.error_y.valueminus,
                disabled: ( ! this.trace.error_y.visible || this.trace.error_y.type === "data" || this.trace.error_y.symmetric || true !== this.trace.visible ) ? true : false,
                hint: "Sets the value of either the percentage (if `type` is set to 'percent') or the constant (if `type` is set to 'constant') corresponding to the lengths of the error bars in the bottom (left) direction for vertical (horizontal) bars"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][error_y][array]`,
                title : "Error Array",	
                type : "text",
                value : this.trace.error_y === undefined || this.trace.error_y.array === undefined ? []: this.trace.error_y.array.join(),
                disabled: ( ! this.trace.error_y.visible || this.trace.error_y.type !== "data" || true !== this.trace.visible ) ? true : false,
                hint: "Sets the data corresponding the length of each error bar. Values are plotted relative to the underlying data. Determines whether or not this set of error bars is array."
              },
              {
                id : `traces[${this.index}][error_y][arrayminus]`, 
                title : "Error Array Minus", 
                type : "text",
                value : this.trace.error_y === undefined || this.trace.error_y.arrayminus=== undefined ? []: this.trace.error_y.arrayminus.join(),
                disabled: ( ! this.trace.error_y.visible || this.trace.error_y.type !== "data" || this.trace.error_y.symmetric || true !== this.trace.visible ) ? true : false,
                hint : "Sets the data corresponding the length of each error bar in the bottom (left) direction for vertical (horizontal) bars Values are plotted relative to the underlying data."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][error_y][color]`,
                title : "Error Bar Color",
                type : "color", 
                value :  this.trace.error_y === undefined || this.trace.error_y.color === undefined ? null : this.trace.error_y.color,
                disabled: ( ! this.trace.error_y.visible || true !== this.trace.visible )? true : false,
                hint: "Sets the stoke color of the error bars."
              },
              {
                id : `traces[${this.index}][error_y][symmetric]`,
                title : "Symmetric ?",
                type : "checkbox", 
                value : this.trace.error_y === undefined || this.trace.error_y.symmetric === undefined ? false: this.trace.error_y.symmetric,
                disabled: ( ! this.trace.error_y.visible || true !== this.trace.visible ) ? true : false,
                hint: "Determines whether or not the error bars have the same length in both direction (top/bottom for vertical bars, left/right for horizontal bars."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `traces[${this.index}][error_y][width]`, 
                title : "Error Bar Width", 
                type : "number",
                min : 1,
                max : 1000,
                step : 1,
                value : this.trace.error_y === undefined || this.trace.error_y.width === undefined ? 4: this.trace.error_y.width,
                disabled: ( ! this.trace.error_y.visible || true !== this.trace.visible )  ? true : false,
                hint : "Sets the width (in px) of the cross-bar at both ends of the error bars."
              },
              {
                id : `traces[${this.index}][error_y][thickness]`, 
                title : "Error Bar Line Thickness", 
                type : "number",
                min : 1,
                max : 1000,
                step : 1,
                value : this.trace.error_y === undefined || this.trace.error_y.thickness === undefined ? 1 : this.trace.error_y.thickness,
                disabled:  ( ! this.trace.error_y.visible || true !== this.trace.visible )  ? true : false,
                hint : "Sets the thickness (in px) of the error bars."
              },
            ],
          },
        ]
      }

    }
  }

}

export default Trace;