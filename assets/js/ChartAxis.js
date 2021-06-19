
import { fontFamily } from "./utilities"

class ChartAxis  {

  constructor( layout, chartType, axisId, axisSide, axisOverlaying, axisMatches, rangesliderVisible) {

    this.layout = layout
    this.axisId = axisId
    this.axisSide = axisSide
    this.axisOverlaying = axisOverlaying
    this.axisMatches = axisMatches
    this.rangesliderVisible = rangesliderVisible

    this.axisSideOptions = ( axisId === "xaxis" || axisId === "xaxis2" ) ? { bottom: "Bottom", top: "Top" } :  { left: "Left", right: "Right" }
    

    switch (chartType) {
      case "LineChart":
      case "ScatterChart":
        this.type = "linear"
        break
      default:
        this.type = "-"
        break
    }
   
  }

  static defaultOptions( axisSide, axisOverlaying, axisTitleText ) {

    return {

      visible : true,
      type : "-",
      side: axisSide,
      autotypenumbers : "convert types",
      autorange : true,
      fixedrange : true,
      rangemode : "normal",
      range : [],
      mirror: true,
      automargin: true,
       title: {
        text: axisTitleText,
        font: {
          family: Object.keys(fontFamily())[12],
          size: 16,
          color: "#263238",
        },
        standoff: 20,
      },
      ticks: "outside",
      tickmode: "array",
      nticks: null,
      tick0: null,
      dtick: null,
      tickvals: [],
      ticktext: [],
      ticklabelposition: "outside",
      ticklen: 10,
      tickwidth: 1,
      tickcolor: "#CCCCCC",
      showticklabels: true,





      overlaying: axisOverlaying,


      
      // range : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].range === undefined ) ? [Math.min(...this.xAxisData), Math.max(...this.xAxisData)] : this.layout[this.axisId].range,



      // // color : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].color === undefined ) ? "#000a12" : this.layout[this.axisId].color,

      // // anchor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].anchor === undefined ) ? null: this.layout[this.axisId].anchor,
      // // matches : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].matches === undefined ) ? this.axisMatches: this.layout[this.axisId].matches,
      // rangemode : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].rangemode === undefined ) ? "normal" : this.layout[this.axisId].rangemode,
      // range : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].range === undefined ) ? [] : this.layout[this.axisId].range,
      // // range : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].range === undefined ) ? [Math.min(...this.xAxisData), Math.max(...this.xAxisData)] : this.layout[this.axisId].range,
     
      // // scaleanchor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].scaleanchor === undefined ) ? null : this.layout[this.axisId].scaleanchor,
      
      // mirror : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].mirror === undefined ) ? false : this.layout[this.axisId].mirror === "false" ? false : this.layout[this.axisId].mirror === "true" ? true : this.layout[this.axisId].mirror,
     
     
      // showspikes : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].showspikes === undefined ) ? true : this.layout[this.axisId].showspikes,
      // spikecolor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].spikecolor === undefined ) ? "#000a12" : this.layout[this.axisId].spikecolor,
      // spikethickness : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].spikethickness === undefined ) ? 2 : this.layout[this.axisId].spikethickness,
      // spikedash : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].spikedash === undefined ) ? "dash" : this.layout[this.axisId].spikedash,
      // spikemode : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].spikemode === undefined ) ? "toaxis" : this.layout[this.axisId].spikemode,
      // tickangle : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].tickangle === undefined ) ? 0 : this.layout[this.axisId].tickangle,
      // tickprefix : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].tickprefix === undefined ) ? "" : this.layout[this.axisId].tickprefix,
      // showtickprefix : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].showtickprefix === undefined ) ? "none" : this.layout[this.axisId].showtickprefix,
      // ticksuffix : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].ticksuffix === undefined ) ? "" : this.layout[this.axisId].ticksuffix,
      // showticksuffix : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].showticksuffix === undefined ) ? "none" : this.layout[this.axisId].showticksuffix,
      // showexponent : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].showexponent === undefined ) ? "all" : this.layout[this.axisId].showexponent,
      // exponentformat : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].exponentformat === undefined ) ? "power" : this.layout[this.axisId].exponentformat,
      // minexponent : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].minexponent === undefined ) ? 3 : this.layout[this.axisId].minexponent,
      // separatethousands : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].separatethousands === undefined ) ? true : this.layout[this.axisId].separatethousands,
      // showline : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].showline === undefined ) ? true : this.layout[this.axisId].showline,
      // linecolor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].linecolor === undefined ) ? "#000a12" : this.layout[this.axisId].linecolor,
      // linewidth : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].linewidth === undefined ) ? 1 : this.layout[this.axisId].linewidth,
      // showgrid : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].showgrid === undefined ) ? true : this.layout[this.axisId].showgrid,
      // gridcolor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].gridcolor === undefined ) ? "#4f5b62" : this.layout[this.axisId].gridcolor,
      // gridwidth : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].gridwidth === undefined ) ? 1 : this.layout[this.axisId].gridwidth,
      // zeroline : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].zeroline === undefined ) ? true : this.layout[this.axisId].zeroline,
      // zerolinecolor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].zerolinecolor === undefined ) ? "#000a12" : this.layout[this.axisId].zerolinecolor,
      // zerolinewidth : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].zerolinewidth === undefined ) ? 1 : this.layout[this.axisId].zerolinewidth,
      // // anchor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].anchor === undefined ) ? null: this.layout[this.axisId].anchor,
      // // matches : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].matches === undefined ) ? this.axisMatches: this.layout[this.axisId].matches,
      // position : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].position === undefined ) ? 0 : this.layout[this.axisId].position,
      // tickfont: {
      //   family: ( this.layout[this.axisId] === undefined || this.layout[this.axisId].tickfont === undefined || this.layout[this.axisId].tickfont.family === undefined  ) ?  Object.keys(fontFamily())[1] : this.layout[this.axisId].tickfont.family,
      //   size: ( this.layout[this.axisId] === undefined || this.layout[this.axisId].tickfont === undefined || this.layout[this.axisId].tickfont.size === undefined ) ? 20 : this.layout[this.axisId].tickfont.size,
      //   color : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].tickfont === undefined || this.layout[this.axisId].tickfont.color === undefined ) ? "#000a12" : this.layout[this.axisId].tickfont.color,
      // },
      // title: {
      //   text : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].title === undefined || this.layout[this.axisId].title.text === undefined ) ? "Wavelength ( &#181;m )" : this.layout[this.axisId].title.text,
      //   font : {
      //     family: ( this.layout[this.axisId] === undefined ||  this.layout[this.axisId].title === undefined || this.layout[this.axisId].title.font === undefined || this.layout[this.axisId].title.font.family === undefined  ) ?  Object.keys(fontFamily())[1] : this.layout[this.axisId].title.font.family,
      //     size: ( this.layout[this.axisId] === undefined || this.layout[this.axisId].title === undefined || this.layout[this.axisId].title.font === undefined || this.layout[this.axisId].title.font.size === undefined ) ? 20 : this.layout[this.axisId].title.font.size,
      //     color : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].title === undefined || this.layout[this.axisId].title.font === undefined || this.layout[this.axisId].title.font.color === undefined ) ? "#000a12" : this.layout[this.axisId].title.font.color,
      //   },
      //   standoff : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].title === undefined || this.layout[this.axisId].title.standoff === undefined ) ? 10 : this.layout[this.axisId].title.standoff ,
      // },
      // rangeslider : this.axisId === "left" || this.axisId === "right" ? null : {
      //   visible : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].rangeslider === undefined || this.layout[this.axisId].rangeslider.visible === undefined ) ? false : this.layout[this.axisId].rangeslider.rangesliderVisible,
      //   thickness : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].rangeslider === undefined || this.layout[this.axisId].rangeslider.thickness === undefined ) ?  0.12 : this.layout[this.axisId].rangeslider.thickness,
      //   bgcolor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].rangeslider === undefined || this.layout[this.axisId].rangeslider.bgcolor === undefined ) ? "#e6ffff" : this.layout[this.axisId].rangeslider.bgcolor,
      //   borderwidth : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].rangeslider === undefined || this.layout[this.axisId].rangeslider.borderwidth === undefined ) ? 1 : this.layout[this.axisId].rangeslider.borderwidth,
      //   bordercolor : ( this.layout[this.axisId] === undefined || this.layout[this.axisId].rangeslider === undefined || this.layout[this.axisId].rangeslider.bordercolor === undefined ) ?  "#263238" : this.layout[this.axisId].rangeslider.bordercolor,
      // }

    }
      
  }


  static sections(layout, axisId) {

    const axisSideOptions = ( axisId === "xaxis" || axisId === "xaxis2" ) ? { bottom: "Bottom", top: "Top" } :  { left: "Left", right: "Right" }

    return {

      basicOptions: {
        intro : "Here you can modify the bottom x-axis general",
        title : "Basic Options",
        fieldGroups : [
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `layout[${axisId}][visible]`,
                title : "Show",	
                type : "checkbox",
                value : layout[axisId].visible === undefined ? false : true,
                hint: "A single toggle to hide the axis while preserving interaction like dragging. Default is true when a cheater plot is present on the axis, otherwise false"
              },
              {
                id : `layout[${axisId}][type]`,
                title : "Type",	
                type : "select",
                options : {
                  "-": "Default",
                  linear: "Linear",
                  log: "Log",
                  date: "Date",
                  category: "Category",
                  multicategory:"Multi category"
                },
                value : layout[axisId].type,
                disabled: ! layout[axisId].visible  ? true : false,
                hint: "Sets the axis type. By default, plotly attempts to determined the axis type by looking into the data of the traces that referenced the axis in question.  Default: '-'"
              },
            ],
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `layout[${axisId}][side]`, 
                title : "Side", 
                type : "select",
                options : axisSideOptions,
                value : layout[axisId].side,
                disabled: ! layout[axisId].visible  ? true : false,
                hint : "Determines whether a x (y) axis is positioned at the 'bottom' ('left') or 'top' ('right') of the plotting area."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[${axisId}][autotypenumbers]`,
                title : "Auto Type Numbers",	
                type : "select",
                options : {
                  "convert types": "Convert Types",
                  strict: "Strict",
                },
                value : layout[axisId].autotypenumbers,
                disabled: ! layout[axisId].visible  ? true : false,
                hint: "Using 'strict' a numeric string in trace data is not converted to a number. Using 'convert types' a numeric string in trace data may be treated as a number during automatic axis `type` detection. Defaults to layout.autotypenumbers."
              },
              {
                id : `layout[${axisId}][autorange]`,
                title : "Auto Range",	
                type : "select",
                options : {
                  true: "Normal",
                  false: "Disabled",
                  reversed: "Reversed"
                },
                value : layout[axisId].autorange,
                disabled: ! layout[axisId].visible || layout[axisId].type !== "linear" ? true : false,
                hint: "Determines whether or not the range of this axis is computed in relation to the input data. See `rangemode` for more info. If `range` is provided, then `autorange` is set to 'false'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `layout[${axisId}][fixedrange]`,
                title : "Fixed Range",	
                type : "checkbox",
                value : layout[axisId].fixedrange === undefined ? false : true,
                disabled: ! layout[axisId].visible  ? true : false,
                hint: "Determines whether or not this axis is zoom-able. If true, then zoom is disabled."
              },
              {
                id : `layout[${axisId}][rangemode]`,
                title : "Range Mode",	
                type : "select",
                options : {
                  normal: "Normal",
                  tozero: "To Zero",
                  nonnegative: "Non Negative"
                },
                value : layout[axisId].rangemode,
                disabled: ! layout[axisId].visible || ! layout[axisId].autorange ? true : false,
                hint: "If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes."
              },
            ],
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `layout[${axisId}][range]`,
                title : "Range",	
                type : "text",
                value : undefined !== layout[axisId].range ? layout[axisId].range.join() : null,
                disabled: ! layout[axisId].visible ? true : false,
                // disabled: ( ! layout[axisId].visible  || layout[axisId].autorange ) ? true : false,
                hint: "Sets the range of this axis. If the axis `type` is 'log', then you must take the log of your desired range (e.g. to set the range from 1 to 100, set the range from 0 to 2). If the axis `type` is 'date', it should be date strings, like date data, though Date objects and unix milliseconds will be accepted and converted to strings. If the axis `type` is 'category', it should be numbers, using the scale where each category is assigned a serial number from zero in the order it appears."
              },
              // {
              //   id : `layout[${axisId}][scaleanchor]`,
              //   title : "Scale Anchor",	
              //   type : "text",
              //   value : layout[axisId].scaleanchor,
              //   disabled: ! layout[axisId].visible  ? true : false,
              //   hint: "If set to another axis id (e.g. `x2`, `y`), the range of this axis changes together with the range of the corresponding axis such that the scale of pixels per unit is in a constant ratio. Both axes are still zoomable, but when you zoom one, the other will zoom the same amount, keeping a fixed midpoint. `constrain` and `constraintoward` determine how we enforce the constraint. You can chain these, ie `yaxis: {scaleanchor: 'x'}, xaxis2: {scaleanchor: 'y'}` but you can only link axes of the same `type`. The linked axis can have the opposite letter (to constrain the aspect ratio) or the same letter (to match scales across subplots). Loops (`yaxis: {scaleanchor: 'x'}, xaxis: {scaleanchor: 'y'}` or longer) are redundant and the last constraint encountered will be ignored to avoid possible inconsistent constraints via `scaleratio`. Note that setting axes simultaneously in both a `scaleanchor` and a `matches` constraint is currently forbidden."
              // },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `layout[${axisId}][mirror]`,
                title : "Mirror",	
                type : "select",
                options : {
                  true: "Enabled",
                  ticks: "Ticks",
                  false: "Disabled",
                  all: "All",
                  allticks: "All Ticks",
                },
                value : layout[axisId].mirror,
                disabled:! layout[axisId].visible  ? true : false,
                hint: "Determines if the axis lines and/or ticks are mirrored to the opposite side of the plotting area. If 'true', the axis lines are mirrored. If 'ticks', the axis lines and ticks are mirrored. If 'false', mirroring is disable. If 'all', axis lines are mirrored on all shared-axes subplots. If 'allticks', axis lines and ticks are mirrored on all shared-axes subplots."
              },
              {
                id : `layout[${axisId}][automargin]`,
                title : "Auto Margin",	
                type : "checkbox",
                value : layout[axisId].automargin === undefined ? true : false,
                disabled:! layout[axisId].visible  ? true : false,
                hint: "Determines whether long tick labels automatically grow the figure margins."
              },
            ],
          }
        ],
      },

      title: {
        intro : "Here you can modify the bottom x-axis title",
        title : "Title",
        fieldGroups : [
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `layout[${axisId}][title][text]`,
                title : "Title",
                type : "text", 
                value : layout[axisId].title.text,
                disabled: ! layout[axisId].visible  ? true : false,
                hint: "Sets the title of the y-axis."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[${axisId}][title][font][family]`,
                title : "Font",	
                type : "select",
                options : fontFamily(),
                value : layout[axisId].title.font.family,
                disabled: ! layout[axisId].visible || ! layout[axisId].title.text ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `layout[${axisId}][title][font][size]`, 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : layout[axisId].title.font.size,
                disabled: ! layout[axisId].visible || ! layout[axisId].title.text ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `layout[${axisId}][title][font][color]`,
                title : "Color",
                type : "color", 
                value : layout[axisId].title.font.color,
                disabled: ! layout[axisId].visible || ! layout[axisId].title.text ? true : false,
              },
              {
                id : `layout[${axisId}][title][standoff]`,
                title : "Standoff ",
                type : "number",
                min : 0,
                max : 2000,
                step : 0.5,
                value : layout[axisId].title.standoff,
                disabled: ! layout[axisId].visible || ! layout[axisId].title.text ? true : false,
                hint: "Sets the standoff distance (in px) between the axis labels and the title text The default value is a function of the axis tick labels, the title `font.size` and the axis `linewidth`. Note that the axis title position is always constrained within the margins, so the actual standoff distance is always less than the set or default value. By setting `standoff` and turning on `automargin`, plotly.js will push the margins to fit the axis title at given standoff distance."
              }
            ],
          }
        ]
      },

      ticks: {
        intro : "Here you can modify the bottom x-axis ticks",
        title : "Ticks",
        fieldGroups : [
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : `layout[${axisId}][ticks]`,
                title : "Show Ticks",	
                type : "select",
                options : {
                  "": "Hidden",
                  inside: "Inside",
                  outside: "Outside"
                },
                value : layout[axisId].ticks,
                disabled: ! layout[axisId].visible  ? true : false,
                hint: "Determines whether ticks are drawn or not. If '', this axis' ticks are not drawn. If 'outside' ('inside'), this axis' are drawn outside (inside) the axis lines."
              },
              {
                id : `layout[${axisId}][ticklen]`,
                title : "Tick Length",	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : layout[axisId].ticklen,
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" ? true : false,
                // disabled: layout[axisId].tickmode !== "array" ? true : false,
                hint: "Sets the tick length (in px)."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[${axisId}][tickmode]`,
                title : "Tick Mode",	
                type : "select",
                options : {
                  auto: "Auto",
                  linear: "Linear",
                  array: "Array"
                },
                value : layout[axisId].tickmode,
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" ? true : false,
                hint: "Sets the tick mode for this axis. If 'auto', the number of ticks is set via `nticks`. If 'linear', the placement of the ticks is determined by a starting position `tick0` and a tick step `dtick` ('linear' is the default value if `tick0` and `dtick` are provided). If 'array', the placement of the ticks is set via `tickvals` and the tick text is `ticktext`. ('array' is the default value if `tickvals` is provided)."
              },
              {
                id : `layout[${axisId}][nticks]`,
                title : "Number of Ticks",	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : layout[axisId].nticks,
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" || layout[axisId].tickmode !== "auto" ? true : false,
                hint: "Specifies the maximum number of ticks for the particular axis. The actual number of ticks will be chosen automatically to be less than or equal to `nticks`. Has an effect only if `tickmode` is set to 'auto'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `layout[${axisId}][tick0]`,
                title : "First Tick Position",	
                type : "number",
                value : layout[axisId].tick0,
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" || layout[axisId].tickmode !== "linear"  ? true : false,
                hint: "Sets the placement of the first tick on this axis. Use with `dtick`. If the axis `type` is 'log', then you must take the log of your starting tick (e.g. to set the starting tick to 100, set the `tick0` to 2) except when `dtick`='L<f>' (see `dtick` for more info). If the axis `type` is 'date', it should be a date string, like date data. If the axis `type` is 'category', it should be a number, using the scale where each category is assigned a serial number from zero in the order it appears."
              },
              {
                id : `layout[${axisId}][dtick]`,
                title : "Tick Spacing",	
                type : "number",
                value : layout[axisId].dtick,
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" || layout[axisId].tickmode !== "linear" ? true : false,
                hint: "Sets the step in-between ticks on this axis. Use with `tick0`. Must be a positive number, or special strings available to 'log' and 'date' axes. If the axis `type` is 'log', then ticks are set every 10^(n'dtick) where n is the tick number. For example, to set a tick mark at 1, 10, 100, 1000, ... set dtick to 1. To set tick marks at 1, 100, 10000, ... set dtick to 2. To set tick marks at 1, 5, 25, 125, 625, 3125, ... set dtick to log_10(5), or 0.69897000433. 'log' has several special values; 'L<f>', where `f` is a positive number, gives ticks linearly spaced in value (but not position). For example `tick0` = 0.1, `dtick` = 'L0.5' will put ticks at 0.1, 0.6, 1.1, 1.6 etc. To show powers of 10 plus small digits between, use 'D1' (all digits) or 'D2' (only 2 and 5). `tick0` is ignored for 'D1' and 'D2'. If the axis `type` is 'date', then you must convert the time to milliseconds. For example, to set the interval between ticks to one day, set `dtick` to 86400000.0. 'date' also has special values 'M<n>' gives ticks spaced by a number of months. `n` must be a positive integer. To set ticks on the 15th of every third month, set `tick0` to '2000-01-15' and `dtick` to 'M3'. To set ticks every 4 years, set `dtick` to 'M48'"
              },
            ],
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `layout[${axisId}][tickvals]`,
                title : "Tick Positions",	
                type : "text",
                value : layout[axisId].tickvals.join(),
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" || layout[axisId].tickmode !== "array" ? true : false,
                hint: "Sets the values at which ticks on this axis appear. Only has an effect if `tickmode` is set to 'array'. Used with `ticktext`."
              },
            ],
          },
          {
            cssClasses : ["field-group"],
            inputFields: [
              {
                id : `layout[${axisId}][ticktext]`,
                title : "Tick Label",	
                type : "text",
                value : layout[axisId].ticktext.join(),
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" || layout[axisId].tickmode !== "array"  || ! layout[axisId].tickvals.length  ? true : false,
                hint: "Sets the text displayed at the ticks position via `tickvals`. Only has an effect if `tickmode` is set to 'array'. Used with `tickvals`"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : `layout[${axisId}][tickcolor]`,
                title : "Tick color",	
                type : "color",
                value : layout[axisId].tickcolor,
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" ? true : false,
                hint: "Sets the tick color."
              },
              {
                id : `layout[${axisId}][tickwidth]`,
                title : "Tick width",	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : layout[axisId].tickwidth,
                disabled: ! layout[axisId].visible || layout[axisId].ticks === "" ? true : false,
                // disabled: layout[axisId].tickmode !== "array" ? true : false,
                hint: "Sets the tick width (in px)."
              },
            ],
          }
        ],
      },

      // ticklabels: {
      //   intro : "Here you can modify the bottom x-axis ticklabels",
      //   // id : `${this.prefix}__${this.axisId}TicklabelsSubPanel`,
      //   // cssClasses:[`${this.axisId}`, "subPanel"],
      //   title : "Tick Labels",
      //   fieldGroups : [
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][showticklabels]`,
      //           title : "Show Tick Labels",	
      //           type : "checkbox",
      //           value : layout[axisId].showticklabels,
      //           disabled: ! layout[axisId].visible ? true : false,
      //           hint: "Determines whether or not the tick labels are drawn."
      //         },
      //         {
      //           id : `layout[${axisId}][ticklabelposition]`,
      //           title : "Tick Label Position",	
      //           type : "select",
      //           options : {
      //             outside: "Outside",
      //             inside: "Inside",
      //             "outside left": "Outside Left",
      //             "inside left": "Inside Left",
      //             "outside right": "Outside Right",
      //             "inside right": "Inside Right",
      //           },
      //           value : layout[axisId].ticklabelposition,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels ) ? true : false,
      //           hint: "Determines where tick labels are drawn with respect to the axis Please note that top or bottom has no effect on x axes or when `ticklabelmode` is set to 'period'. Similarly left or right has no effect on y axes or when `ticklabelmode` is set to 'period'. Has no effect on 'multicategory' axes or when `tickson` is set to 'boundaries'. When used on axes linked by `matches` or `scaleanchor`, no extra padding for inside labels would be added by autorange, so that the scales could match."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][tickfont][family]`,
      //           title : "Tick Label Font",	
      //           type : "select",
      //           options : fontFamily(),
      //           value : layout[axisId].tickfont.family,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels )  ? true : false,
      //           hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
      //         },
      //         {
      //           id : `layout[${axisId}][tickfont][size]`, 
      //           title : "Tick  Label Font Size", 
      //           type : "number",
      //           min : 1,
      //           max : 100,
      //           step : 0.5,
      //           value : layout[axisId].tickfont.size,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels )  ? true : false,
      //           hint : "number greater than or equal to 1"
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][tickfont][color]`,
      //           title : "Tick Font Color",
      //           type : "color", 
      //           value : layout[axisId].tickfont.color,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels )  ? true : false,
      //         },
      //         {
      //           id : `layout[${axisId}][tickangle]`, 
      //           title : "Tick Label Angle", 
      //           type : "number",
      //           min : -180,
      //           max : 180,
      //           step : 1,
      //           value : layout[axisId].tickangle,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels ) ? true : false,
      //           hint : "Sets the angle of the tick labels with respect to the horizontal. For example, a `tickangle` of -90 draws the tick labels vertically."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][showtickprefix]`, 
      //           title : "Show Tick Label Prefix", 
      //           type : "select",
      //           options : {
      //             all: "All",
      //             first: "First",
      //             last: "Last",
      //             none: "None",
      //           },
      //           value : layout[axisId].showtickprefix,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels )  ? true : false,
      //           hint : "If 'all', all tick labels are displayed with a prefix. If 'first', only the first tick is displayed with a prefix. If 'last', only the last tick is displayed with a suffix. If 'none', tick prefixes are hidden.Sets a tick label prefix."
      //         },
      //         {
      //           id : `layout[${axisId}][tickprefix]`, 
      //           title : "Tick Label Prefix", 
      //           type : "Text",
      //           value : layout[axisId].tickprefix,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels || layout[axisId].showtickprefix === "none" )  ? true : false,
      //           hint : "Sets a tick label prefix."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][showticksuffix]`, 
      //           title : "Show Tick Label Suffix", 
      //           type : "select",
      //           options : {
      //             all: "All",
      //             first: "First",
      //             last: "Last",
      //             none: "None",
      //           },
      //           value : layout[axisId].showticksuffix,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels )  ? true : false,
      //           hint : "If 'all', all tick labels are displayed with a suffix. If 'first', only the first tick is displayed with a suffix. If 'last', only the last tick is displayed with a suffix. If 'none', tick suffixes are hidden.Sets a tick label suffix."
      //         },
      //         {
      //           id : `layout[${axisId}][ticksuffix]`, 
      //           title : "Tick Label Suffix", 
      //           type : "Text",
      //           value : layout[axisId].ticksuffix,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showticklabels  || layout[axisId].showticksuffix === "none" )  ? true : false,
      //           hint : "Sets a tick label suffix."
      //         },
      //       ],
      //     }
      //   ],
      // },

      // spikes: {
      //   intro : "Here you can modify the bottom x-axis spikes",
      //   // id : `${this.prefix}__${this.axisId}SpikesSubPanel`,
      //   // cssClasses:[`${this.axisId}`, "subPanel"],
      //   title : "Spikes",
      //   fieldGroups : [
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][showspikes]`,
      //           title : "Show Spikes",	
      //           type : "checkbox",
      //           value : layout[axisId].showspikes,
      //           // disabled:! layout[axisId].visible  ? true : false,
      //           hint: "Determines whether or not spikes (aka droplines) are drawn for this axis. Note: This only takes affect when hovermode = closest"
      //         },
      //         {
      //           id : `layout[${axisId}][spikemode]`,
      //           title : "Spike Mode",	
      //           type : "select",
      //           options : {
      //             toaxis: "To Axis",
      //             across: "Across",
      //             marker: "Marker",
      //             "toaxis+across": "To Axis & Across",
      //             "toaxis+marker": "To Axis & Marker",
      //             "across+marker": "Across & Marker",
      //             "toaxis+across+marker": "To Axis & Across & Marker",
      //           },
      //           value : layout[axisId].spikemode,
      //           disabled: ! layout[axisId].showspikes ? true : false,
      //           hint: "Determines the drawing mode for the spike line If 'toaxis', the line is drawn from the data point to the axis the series is plotted on. If 'across', the line is drawn across the entire plot area, and supercedes 'toaxis'. If 'marker', then a marker dot is drawn on the axis the series is plotted on"
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][spikedash]`,
      //           title : "Spike Type",	
      //           type : "select",
      //           options : {
      //             solid: "Solid",
      //             dot: "Dot",
      //             dash: "Dash",
      //             longdash: "Long Dash",
      //             dashdot: "Dash Dot",
      //             longdashdot: "Long Dash Dot"
      //           },
      //           value : layout[axisId].spikedash,
      //           disabled: ! layout[axisId].showspikes ? true : false,
      //           hint: "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
      //         },
      //         {
      //           id : `layout[${axisId}][spikethickness]`,
      //           title : "Spike Thiclness",	
      //           type : "number",
      //           min : 0,
      //           max : 2000,
      //           step : 1,
      //           value : layout[axisId].spikethickness,
      //           disabled: ! layout[axisId].showspikes ? true : false,
      //           hint: "Sets the width (in px) of the zero line."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][spikecolor]`,
      //           title : "Spike Color",	
      //           type : "color",
      //           value : layout[axisId].spikecolor,
      //           disabled: ! layout[axisId].showspikes ? true : false,
      //           hint: "Sets the spike color. If undefined, will use the series colorSets the tick color."
      //         },
      //       ],
      //     }
      //   ],
      // },

      // exponent: {
      //   intro : "Here you can modify the bottom x-axis exponent",
      //   // id : `${this.prefix}__${this.axisId}ExponentSubPanel`,
      //   // cssClasses:[`${this.axisId}`, "subPanel"],
      //   title : "Exponent",
      //   fieldGroups : [
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][showexponent]`, 
      //           title : "Show Exponent", 
      //           type : "select",
      //           options : {
      //             all: "All",
      //             first: "First",
      //             last: "Last",
      //             none: "None",
      //           },
      //           value : layout[axisId].showexponent,
      //           disabled: ! layout[axisId].visible  ? true : false,
      //           hint : "If 'all', all exponents are shown besides their significands. If 'first', only the exponent of the first tick is shown. If 'last', only the exponent of the last tick is shown. If 'none', no exponents appear."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][exponentformat]`, 
      //           title : "Exponent Format", 
      //           type : "select",
      //           options : {
      //             e: "e",
      //             E: "E",
      //             power: "Power",
      //             SI: "SI",
      //             B: "B",
      //             none: "None",
      //           },
      //           value : layout[axisId].exponentformat,
      //           disabled: ( ! layout[axisId].visible || layout[axisId].showexponent === "none" )  ? true : false,
      //           hint : "Determines a formatting rule for the tick exponents. For example, consider the number 1,000,000,000. If 'none', it appears as 1,000,000,000. If 'e', 1e+9. If 'E', 1E+9. If 'power', 1x10^9 (with 9 in a super script). If 'SI', 1G. If 'B', 1B.If 'all', all exponents are shown besides their significands. If 'first', only the exponent of the first tick is shown. If 'last', only the exponent of the last tick is shown. If 'none', no exponents appear."
      //         },
      //         {
      //           id : `layout[${axisId}][minexponent]`, 
      //           title : "Minimum Exponent", 
      //           type : "number",
      //           value : layout[axisId].minexponent,
      //           disabled: ( ! layout[axisId].visible || layout[axisId].showexponent === "none" ) ? true : false,
      //           hint : "Hide SI prefix for 10^n if |n| is below this number. This only has an effect when `tickformat` is 'SI' or 'B'."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][separatethousands]`, 
      //           title : "Seperate Thousands", 
      //           type : "checkbox",
      //           value : layout[axisId].separatethousands,
      //           disabled: ! layout[axisId].visible  ? true : false,
      //           hint : "If 'true', even 4-digit integers are separated"
      //         },
      //       ],
      //     }
      //   ],
      // },

      // linesGrids: {
      //   intro : "Here you can modify the bottom x-axis linesGrids",
      //   // id : `${this.prefix}__${this.axisId}LinesGridsSubPanel`,
      //   // cssClasses:[`${this.axisId}`, "subPanel"],
      //   title : "Lines & Grids",
      //   fieldGroups : [
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][showline]`, 
      //           title : "Show Axis Bounding Line", 
      //           type : "checkbox",
      //           value : layout[axisId].showline,
      //           disabled: ! layout[axisId].visible  ? true : false,
      //           hint : "Determines whether or not a line bounding this axis is drawn."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][linewidth]`, 
      //           title : "Axis Bounding Line Width", 
      //           type : "number",
      //           min : 1,
      //           max : 100,
      //           step : 1,
      //           value : layout[axisId].linewidth,
      //           disabled: (! layout[axisId].visible || ! layout[axisId].showline ) ? true : false,
      //           hint : "Sets the width (in px) of the axis line."
      //         },
      //         {
      //           id : `layout[${axisId}][linecolor]`, 
      //           title : "Axis Bounding Line Color", 
      //           type : "color",
      //           value : layout[axisId].linecolor,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showline )  ? true : false,
      //           hint : "Sets the axis line color."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][zeroline]`, 
      //           title : "Show Zero Line", 
      //           type : "checkbox",
      //           value : layout[axisId].zeroline,
      //           disabled: ! layout[axisId].visible  ? true : false,
      //           hint : "Determines whether or not a line is drawn at along the 0 value of this axis. If 'true', the zero line is drawn on top of the grid lines."
      //         },
      //         {
      //           id : `layout[${axisId}][showgrid]`, 
      //           title : "Show Grid", 
      //           type : "checkbox",
      //           value : layout[axisId].showgrid,
      //           disabled: ! layout[axisId].visible  ? true : false,
      //           hint : "Determines whether or not grid lines are drawn. If 'true', the grid lines are drawn at every tick mark.Determines whether or not a line bounding this axis is drawn."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][zerolinecolor]`, 
      //           title : "Zero Line Color", 
      //           type : "color",
      //           value : layout[axisId].zerolinecolor,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].zeroline )  ? true : false,
      //           hint : "Sets the line color of the zero line."
      //         },
      //         {
      //           id : `layout[${axisId}][zerolinewidth]`, 
      //           title : "Zero Line Width", 
      //           type : "number",
      //           min : 1,
      //           max : 100,
      //           step : 1,
      //           value : layout[axisId].zerolinewidth,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].zeroline )  ? true : false,
      //           hint : "Sets the width (in px) of the zero line."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][gridwidth]`, 
      //           title : "Grid Width", 
      //           type : "number",
      //           min : 1,
      //           max : 100,
      //           step : 1,
      //           value : layout[axisId].gridwidth,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showgrid ) ? true : false,
      //           hint : "Sets the width (in px) of the grid lines."
      //         },
      //         {
      //           id : `layout[${axisId}][gridcolor]`, 
      //           title : "GridColor", 
      //           type : "color",
      //           value : layout[axisId].gridcolor,
      //           disabled: ( ! layout[axisId].visible || ! layout[axisId].showgrid )  ? true : false,
      //           hint : "Sets the color of the grid lines."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         // {
      //         //   id : `layout[${axisId}][anchor]`, 
      //         //   title : "Anchor", 
      //         //   type : "text",
      //         //   value : layout[axisId].anchor,
      //         //   hint : "If set to an opposite-letter axis id (e.g. `x2`, `y`), this axis is bound to the corresponding opposite-letter axis. If set to 'free', this axis' position is determined by `position`.  Set anchoe and position to undefined to ignore both"
      //         // },
      //         // {
      //         //   id : `layout[${axisId}][position]`, 
      //         //   title : "Position", 
      //         //   type : "number",
      //         //   min : 0,
      //         //   max : 1,
      //         //   step : 0.05,
      //         //   value : layout[axisId].position,
      //         //   disabled: layout[axisId].anchor !== "free" ? true : false,
      //         //   hint : "Sets the position of this axis in the plotting space (in normalized coordinates). Only has an effect if `anchor` is set to 'free'. Set anchoe and position to undefined to ignore both"
      //         // },         
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][overlaying]`, 
      //           title : "Overlaying", 
      //           type : "text",
      //           value : layout[axisId].overlaying,
      //           hint : "If set a same-letter axis id, this axis is overlaid on top of the corresponding same-letter axis, with traces and axes visible for both axes. If 'false', this axis does not overlay any same-letter axes. In this case, for axes with overlapping domains only the highest-numbered axis will be visible."
      //         },
      //       ]
      //     }
      //   ]
        
      // },

      // rangeslider : this.axisId === "left" || this.axisId === "right" ? null : {
      //   intro : "Here you can modify the plot x-axis range slider",
      //   // id : `${this.prefix}__${this.axisId}RangesliderSubPanel`,
      //   // cssClasses:[`${this.axisId}`, "subPanel"],
      //   title : "Range Slider",
      //   fieldGroups : [
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id : `layout[${axisId}][rangeslider][visible]`,
      //           title : "Show Range Slider",
      //           type : "checkbox", 
      //           value : layout[axisId].rangeslider.visible,
      //           hint: "Determines whether or not the range slider will be visible. If visible, perpendicular axes will be set to `fixedrange`"
      //         },
      //         // {
      //         //   id :  `${this.axisId}[showMinMaxAvgTable]`,
      //         //   title : "Show Min/Max/Avg Table",
      //         //   type : "checkbox", 
      //         //   value : layout[axisId].showMinMaxAvgTable,
      //         //   disabled: ! layout[axisId].rangeslider.visible ? true : false,
      //         //   hint: "Determines whether or not the Min/Max?Avg table will be visible"
      //         // },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id: `${this.axisId}[rangeslider][thickness]`,
      //           title:"Range Slider Height",
      //           type : "number",
      //           min : 0,
      //           max : 1,
      //           step : 0.01,
      //           value: layout[axisId].rangeslider.thickness,
      //           disabled: ! layout[axisId].rangeslider.visible ? true : false,
      //           hint: "The height of the range slider as a fraction of the total plot area height (0 - 1)."
      //         },
      //         {
      //           id : `layout[${axisId}][rangeslider][bgcolor]`,
      //           title : "Background Color",
      //           type : "color", 
      //           value : layout[axisId].rangeslider.bgcolor,
      //           disabled: ! layout[axisId].rangeslider.visible ? true : false,
      //           hint: "Sets the background color of the range slider."
      //         },
      //       ],
      //     },
      //     {
      //       cssClasses : ["field-group", "fifty-fifty"],
      //       inputFields: [
      //         {
      //           id: `${this.axisId}[rangeslider][borderwidth]`,
      //           title:"Border Width",
      //           type : "number",
      //           min : 0,
      //           max : 100,
      //           step : 1,
      //           value: layout[axisId].rangeslider.borderwidth,
      //           disabled: ! layout[axisId].rangeslider.visible ? true : false,
      //           hint: "Sets the border width of the range slider."
      //         },
      //         {
      //           id : `layout[${axisId}][rangeslider][bordercolor]`,
      //           title : "Border Color",
      //           type : "color", 
      //           value : layout[axisId].rangeslider.bordercolor,
      //           disabled: ! layout[axisId].rangeslider.visible ? true : false,
      //           hint: "Sets the border color of the range slider."
      //         },
      //       ]
      //     }
      //   ]  
      // }, 

    }
    
  }
  

}

export default ChartAxis;


