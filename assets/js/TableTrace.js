import { fontFamily, colors } from "./utilities"

class Trace {

  constructor( ) { }

  static defaultOptions( index, name = null, x = null, y = null) {

    return {
      rounding: 3,
      firstColAlign: "center",
      evenRowColor: "#b0bec5",
      oddRowColor: "#e2f1f8",
      type: 'table',
      name: name,
      visible: true,
      hoverinfo: "all",
      domain: {
        x: [.65,1],
        y: [0,1],
        row: null,
        column: null
      },
      cells:{
        values: y,
        prefix: null,
        suffix: null,
        height: 20,
        align : "center",
        line: {
          width: 1,
          color: "#263238",
        },
        fill: {
          color: "#e2f1f8",
        },
        font : {
          family :Object.keys(fontFamily())[12],
          size : 12,
          color : "#000a12",
        },
      },
      header:{
        values: x,
        prefix: null,
        suffix: null,
        height: 28,
        align : "center",
        line: {
          width: 1,
          color: "#263238",
        },
        fill: {
          color: "#000a12",
        },
        font : {
          family :Object.keys(fontFamily())[12],
          size : 14,
          color : "#eeeeee",
        }
      },
      hoverlabel: {
        bgcolor: colors()[index],
        bordercolor : "#000000",
        font: {
          family: "Raleway",
          color: "#FFFFFF",
          size: 12,
        },
        align: "auto",
        namelength: 15,
      },
      

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
              id : `traces[${index}][type]`,  
              title : "Trace Type",  
              type : "text",
              value : trace.type === undefined ? this.defaultOptions(index).type : trace.type,
              readonly : true
            },
           
          ]
        },
        {
          cssClasses : ["field-group", "sixty-forty"],     
          inputFields : [
            {
              id : `traces[${index}][firstColAlign]`,  
              title: "First Column Alignment",
              type: "select",
              options: {
                left: "Left",
                center: "Center",
                right: "Right"
              },
              value : trace.firstColAlign === undefined ? this.defaultOptions(index).firstColAlign : trace.firstColAlign,
              hint : "Determines whether or not this trace is visible. If 'legendonly', the trace is not drawn, but can appear as a legend item (provided that the legend itself is visible)."
            },
            {
              id : `traces[${index}][showlegend]`, 
              title : "Show In Legend", 	
              type : "checkbox",
              value : trace.showlegend === undefined ? this.defaultOptions(index).showlegend : trace.showlegend,
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
              value : trace.mode === undefined ? this.defaultOptions(index).mode : trace.mode,
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
              value : trace.name === undefined ? this.defaultOptions(index).name : trace.name,
              disabled: false === trace.visible  ? true : false,
              hint : "Sets the trace name. The trace name appear as the legend item and on hove"
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
              options : null,
              value : trace.xaxis !== undefined ? this.defaultOptions(index).xaxis : trace.xaxis,
              disabled: true !== trace.visible || ! trace.showlegend  ? true : false,
              hint : "Sets a reference between this trace's x coordinates and a 2D cartesian x axis. If 'x' (the default value), the x coordinates refer to `layout.xaxis`. If 'x2', the x coordinates refer to `layout.xaxis2`, and so on."
            },
            {
              id : `traces[${index}][yaxis]`, 
              title : "Y-Axis", 	
              type : "select", 
              options : null,
              value : trace.yaxis !== undefined ? this.defaultOptions(index).yaxis : trace.yaxis,
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
              value : trace.connectgaps !== undefined ?  this.defaultOptions(index).connectgaps : trace.connectgaps,
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
              value : trace.opacity !== undefined ? this.defaultOptions(index).opacity : trace.opacity,
              disabled: true !== trace.visible  ? true : false,
              hint : "Sets the opacity of the trace."
            },
          ]
        },
      ],
      },

      // marker: {
      // intro : `Here you can modify the markers of trace "${trace.name}`,
      // title : "Markers",
      // fieldGroups : [
      //   {
      //     cssClasses : ["field-group", "fifty-fifty"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][marker][symbol]`, 
      //         title : "Symbol", 	
      //         type : "number",
      //         min : 1,
      //         max : 300,
      //         step : 1,
      //         value :  trace.marker === undefined && trace.marker.symbol === undefined ? this.defaultOptions(index).marker.symbol : trace.marker.symbol,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) ? true : false,
      //         hint : "Sets the marker symbol type. Adding 100 is equivalent to appending '-open' to a symbol name. Adding 200 is equivalent to appending '-dot' to a symbol name. Adding 300 is equivalent to appending '-open-dot' or 'dot-open' to a symbol name."
      //       },
      //       {
      //         id : `traces[${index}][marker][size]`, 
      //         title : "Size", 	
      //         type : "number",
      //         min : 1,
      //         max : 2000,
      //         step : 1,
      //         value : trace.marker === undefined && trace.marker.size === undefined ? this.defaultOptions(index).marker.size : trace.marker.size,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) ? true : false,
      //         hint : "Sets the marker size (in px).  Number or array of numbers greater than or equal to 0"
      //       },
      //     ],
      //   },
      //   {
      //     cssClasses : ["field-group", "sixty-forty"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][marker][opacity]`, 
      //         title : "Opacity", 	
      //         type : "number",
      //         min : 0,
      //         max : 1,
      //         step : .01,
      //         value : trace.marker === undefined && trace.marker.opacity === undefined ? this.defaultOptions(index).marker.opacity : trace.marker.opacity,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) ? true : false,
      //         hint : "Sets the marker's opacity."
      //       },
      //       {
      //         id : `traces[${index}][marker][color]`,  
      //         title : "Color",  
      //         type : "color",
      //         value : trace.marker === undefined && trace.marker.color === undefined ? this.defaultOptions(index).marker.color : trace.marker.color,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) ? true : false,
      //         hint : "Sets the marker color. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.cmin` and `marker.cmax` if set."
      //       },
      //     ],
      //   },
      //   {
      //     cssClasses : ["field-group", "forty-sixty"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][marker][line][color]`, 
      //         title : "Line Color", 	
      //         type : "color",
      //         value : trace.marker === undefined && trace.marker.line === undefined && trace.marker.line.color === undefined ?  this.defaultOptions(index).marker.line.color : trace.marker.line.color,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) || ( trace.marker !== undefined && trace.marker.line !== undefined && trace.marker.width !== undefined && parseInt(trace.marker.line.width) === 0 ) ? true : false,
      //         hint : "Sets themarker.linecolor. It accepts either a specific color or an array of numbers that are mapped to the colorscale relative to the max and min values of the array or relative to `marker.line.cmin` and `marker.line.cmax` if set."
      //       },
      //       {
      //         id : `traces[${index}][marker][line][width]`, 
      //         title : "Line Width", 	
      //         type : "number",
      //         min : 0,
      //         max : 100,
      //         step : 1,
      //         value : trace.marker === undefined && trace.marker.line === undefined && trace.marker.line.width === undefined ? this.defaultOptions(index).marker.line.width : trace.marker.line.width,
      //         disabled: false === trace.visible ||( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) ? true : false,
      //         hint : "Sets the width (in px) of the lines bounding the marker points.   Number or array of numbers greater than or equal to 0"
      //       },
      //     ],
      //   },
      //   {
      //     cssClasses : ["field-group", "sixty-forty"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][marker][gradient][type]`, 
      //         title : "Gradient Type", 	
      //         type : "select",
      //         options : {
      //           none : "None",
      //           radial : "Radial",
      //           horizontal : "Horizontal",
      //           vertical: "Vertical"
      //         },
      //         value : trace.marker === undefined && trace.marker.gradient === undefined && trace.marker.gradient.type === undefined ? this.defaultOptions(index).marker.gradient.type : trace.marker.gradient.type,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) ? true : false,
      //         hint : "Sets the type of gradient used to fill the markers"
      //       },
      //       {
      //         id : `traces[${index}][marker][gradient][color]`, 
      //         title : "Gradient Color", 	
      //         type : "color",
      //         value :  trace.marker === undefined && trace.marker.gradient === undefined && trace.marker.gradient.color === undefined ? this.defaultOptions(index).marker.gradient.color : trace.marker.gradient.color,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) || ( trace.marker !== undefined && trace.marker.gradient && trace.marker.gradient.type !== undefined && trace.marker.gradient.type === "none" ) ? true : false,
      //         hint : "Sets the final color of the gradient fill: the center color for radial, the right for horizontal, or the bottom for vertical."
      //       },
      //     ],
      //   },
      //   {
      //     cssClasses : ["field-group"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][marker][maxdisplayed]`, 
      //         title : "Maximum Displayed", 	
      //         type : "number",
      //         min : 0,
      //         max : 20000,
      //         step : 1,
      //         value : trace.marker === undefined && trace.marker.maxdisplayed === undefined ? this.defaultOptions(index).marker.maxdisplayed : trace.marker.maxdisplayed,
      //         disabled: true !== trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "marker" ) ) ? true : false,
      //         hint : "Sets a maximum number of points to be drawn on the graph. '0' corresponds to no limit."
      //       },
      //     ],
      //   },
      // ]
      // },

      // line: {
      // intro : `Here you can modify the lines of trace "${trace.name}`,
      // title : "Lines",
      // fieldGroups : [
      //   {
      //     cssClasses : ["field-group", "fifty-fifty"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][line][dash]`, 
      //         title : "Type", 	
      //         type : "text",
      //         value : trace.line === undefined && trace.line.dash === undefined ? this.defaultOptions(index).line.dash : trace.line.dash,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "lines" ) )  ? true : false,
      //         hint : "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
      //       },
      //       {
      //         id : `traces[${index}][line][shape]`,  
      //         title : "Shape",  
      //         type : "select",
      //         options : {
      //           linear : "Linear",
      //           spline : "Spline",
      //           hv : "Horizontal/Vertical",
      //           vh: "Vertical/Horizontal",
      //           hvh: "Horizontal/Vertical/Horizontal",
      //           vhv: "Vertical/Horizontal/Vertical"
      //         },
      //         value : trace.line === undefined && trace.line.shape === undefined ? this.defaultOptions(index).line.shape : trace.line.shape,
      //         disabled: true !== trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "lines" ) ) ? true : false,
      //         hint : "Determines the line shape. With 'spline' the lines are drawn using spline interpolation. The other available values correspond to step-wise line shapes."
      //       },
      //     ],
      //   },
      //   {
      //     cssClasses : ["field-group", "sixty-forty"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][line][width]`, 
      //         title : "Width", 	
      //         type : "number",
      //         min : 0,
      //         max : 2000,
      //         step : 1,
      //         value : trace.line === undefined && trace.line.width === undefined ? this.defaultOptions(index).line.width : trace.line.width,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "lines" ) ) ? true : false,
      //         hint : "Sets the width of the trace line."
      //       },
      //       {
      //         id : `traces[${index}][line][color]`,  
      //         title : "Color",  
      //         type : "color",
      //         value : trace.line === undefined && trace.line.color === undefined ? this.defaultOptions(index).line.color : trace.line.color,
      //         disabled: false === trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "lines" ) ) || ( trace.line !== undefined && trace.line.width !== undefined &&parseInt(trace.line.width) === 0 ) ? true : false,
      //         hint : "Sets the color of the trace line"
      //       },
      //     ],
      //   },
      //   {
      //     cssClasses : ["field-group", "forty-sixty"],
      //     inputFields: [
      //       {
      //         id : `traces[${index}][line][simplify]`, 
      //         title : "Simplify", 	
      //         type : "checkbox",
      //         value : trace.line === undefined && trace.line.simplify === undefined ? false : trace.line.simplify,
      //         disabled: true !== trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "lines" ) )  ? true : false,
      //         hint : "Simplifies lines by removing nearly-collinear points. When transitioning lines, it may be desirable to disable this so that the number of points along the resulting SVG path is unaffected."
      //       },
      //       {
      //         id : `traces[${index}][line][smoothing]`, 
      //         title : "Smoothing", 	
      //         type : "number",
      //         min : 0,
      //         max : 1.3,
      //         step : 0.01,
      //         value : trace.line === undefined && trace.line.smoothing === undefined ? this.defaultOptions(index).line.smoothing : trace.line.smoothing,
      //         disabled: true !== trace.visible || ( trace.mode !== undefined && ! trace.mode.includes( "lines" ) ) ? true : false,
      //         hint : "Has an effect only if `shape` is set to 'spline' Sets the amount of smoothing. '0' corresponds to no smoothing (equivalent to a 'linear' shape). Number between or equal to 0 and 1.3."
      //       }, 
      //     ],
      //   },
      // ]
      // }
    }
     
  }

}

export default Trace;