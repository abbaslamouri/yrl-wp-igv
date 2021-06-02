
import { fontFamily } from "./utilities"

class ChartAxis  {

  constructor( inputOPtions, chartType, axisId, prefix ) {

    this.inputOPtions = inputOPtions
    this.axisId = axisId
    this.prefix = prefix

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

  options() {

    return {

      visible : ( this.inputOptions === undefined || this.inputOptions.visible === undefined ) ? true :  this.inputOptions.visible,
      type : ( this.inputOptions === undefined || this.inputOptions.type === undefined ) ? this.type : this.inputOptions.type,
      // color : ( this.inputOptions === undefined || this.inputOptions.color === undefined ) ? "#000a12" : this.inputOptions.color,
      autotypenumbers : ( this.inputOptions === undefined || this.inputOptions.autotypenumbers === undefined ) ? "convert types" : this.inputOptions.autotypenumbers,
      autorange : ( this.inputOptions === undefined || this.inputOptions.autorange === undefined ) ? true : this.inputOptions.autorange === "false" ? false : this.inputOptions.autorange === "true" ? true : this.inputOptions.autorange,
      rangemode : ( this.inputOptions === undefined || this.inputOptions.rangemode === undefined ) ? "normal" : this.inputOptions.rangemode,
      range : ( this.inputOptions === undefined || this.inputOptions.range === undefined ) ? [] : this.inputOptions.range,
      // range : ( this.inputOptions === undefined || this.inputOptions.range === undefined ) ? [Math.min(...this.xAxisData), Math.max(...this.xAxisData)] : this.inputOptions.range,
      fixedrange : ( this.inputOptions === undefined || this.inputOptions.fixedrange === undefined ) ? true : this.inputOptions.fixedrange,
      scaleanchor : ( this.inputOptions === undefined || this.inputOptions.scaleanchor === undefined ) ? null : this.inputOptions.scaleanchor,
      ticks : ( this.inputOptions === undefined || this.inputOptions.ticks === undefined ) ? "outside" : this.inputOptions.ticks,
      tickmode : ( this.inputOptions === undefined || this.inputOptions.tickmode === undefined ) ? "auto" : this.inputOptions.tickmode,
      nticks : ( this.inputOptions === undefined || this.inputOptions.nticks === undefined ) ? null : this.inputOptions.nticks,
      tick0 : ( this.inputOptions === undefined || this.inputOptions.tick0 === undefined ) ? null : this.inputOptions.tick0,
      dtick : ( this.inputOptions === undefined || this.inputOptions.dtick === undefined ) ? null : this.inputOptions.dtick,
      tickvals : ( this.inputOptions === undefined || this.inputOptions.tickvals === undefined ) ? [] : this.inputOptions.tickvals,
      ticktext : ( this.inputOptions === undefined || this.inputOptions.ticktext === undefined ) ? [] : this.inputOptions.ticktext,
      ticklabelposition : ( this.inputOptions === undefined || this.inputOptions.ticklabelposition === undefined ) ? "outside" : this.inputOptions.ticklabelposition,
      mirror : ( this.inputOptions === undefined || this.inputOptions.mirror === undefined ) ? false : this.inputOptions.mirror === "false" ? false : this.inputOptions.mirror === "true" ? true : this.inputOptions.mirror,
      ticklen : ( this.inputOptions === undefined || this.inputOptions.ticklen === undefined ) ? 10 : this.inputOptions.ticklen,
      tickwidth : ( this.inputOptions === undefined || this.inputOptions.tickwidth === undefined ) ? 2 : this.inputOptions.tickwidth,
      tickcolor : ( this.inputOptions === undefined || this.inputOptions.tickcolor === undefined ) ? "#263238" : this.inputOptions.tickcolor,
      showticklabels : ( this.inputOptions === undefined || this.inputOptions.showticklabels === undefined ) ? true : this.inputOptions.showticklabels,
      automargin : ( this.inputOptions === undefined || this.inputOptions.automargin === undefined ) ? true : this.inputOptions.automargin,
      showspikes : ( this.inputOptions === undefined || this.inputOptions.showspikes === undefined ) ? true : this.inputOptions.showspikes,
      spikecolor : ( this.inputOptions === undefined || this.inputOptions.spikecolor === undefined ) ? "#000a12" : this.inputOptions.spikecolor,
      spikethickness : ( this.inputOptions === undefined || this.inputOptions.spikethickness === undefined ) ? 2 : this.inputOptions.spikethickness,
      spikedash : ( this.inputOptions === undefined || this.inputOptions.spikedash === undefined ) ? "dash" : this.inputOptions.spikedash,
      spikemode : ( this.inputOptions === undefined || this.inputOptions.spikemode === undefined ) ? "toaxis" : this.inputOptions.spikemode,
      tickangle : ( this.inputOptions === undefined || this.inputOptions.tickangle === undefined ) ? 0 : this.inputOptions.tickangle,
      tickprefix : ( this.inputOptions === undefined || this.inputOptions.tickprefix === undefined ) ? "" : this.inputOptions.tickprefix,
      showtickprefix : ( this.inputOptions === undefined || this.inputOptions.showtickprefix === undefined ) ? "none" : this.inputOptions.showtickprefix,
      ticksuffix : ( this.inputOptions === undefined || this.inputOptions.ticksuffix === undefined ) ? "" : this.inputOptions.ticksuffix,
      showticksuffix : ( this.inputOptions === undefined || this.inputOptions.showticksuffix === undefined ) ? "none" : this.inputOptions.showticksuffix,
      showexponent : ( this.inputOptions === undefined || this.inputOptions.showexponent === undefined ) ? "all" : this.inputOptions.showexponent,
      exponentformat : ( this.inputOptions === undefined || this.inputOptions.exponentformat === undefined ) ? "power" : this.inputOptions.exponentformat,
      minexponent : ( this.inputOptions === undefined || this.inputOptions.minexponent === undefined ) ? 3 : this.inputOptions.minexponent,
      separatethousands : ( this.inputOptions === undefined || this.inputOptions.separatethousands === undefined ) ? true : this.inputOptions.separatethousands,
      showline : ( this.inputOptions === undefined || this.inputOptions.showline === undefined ) ? true : this.inputOptions.showline,
      linecolor : ( this.inputOptions === undefined || this.inputOptions.linecolor === undefined ) ? "#000a12" : this.inputOptions.linecolor,
      linewidth : ( this.inputOptions === undefined || this.inputOptions.linewidth === undefined ) ? 1 : this.inputOptions.linewidth,
      showgrid : ( this.inputOptions === undefined || this.inputOptions.showgrid === undefined ) ? true : this.inputOptions.showgrid,
      gridcolor : ( this.inputOptions === undefined || this.inputOptions.gridcolor === undefined ) ? "#4f5b62" : this.inputOptions.gridcolor,
      gridwidth : ( this.inputOptions === undefined || this.inputOptions.gridwidth === undefined ) ? 1 : this.inputOptions.gridwidth,
      zeroline : ( this.inputOptions === undefined || this.inputOptions.zeroline === undefined ) ? true : this.inputOptions.zeroline,
      zerolinecolor : ( this.inputOptions === undefined || this.inputOptions.zerolinecolor === undefined ) ? "#000a12" : this.inputOptions.zerolinecolor,
      zerolinewidth : ( this.inputOptions === undefined || this.inputOptions.zerolinewidth === undefined ) ? 1 : this.inputOptions.zerolinewidth,
      side : ( this.inputOptions === undefined || this.inputOptions.side === undefined ) ? "bottom" : this.inputOptions.side,
      anchor : ( this.inputOptions === undefined || this.inputOptions.anchor === undefined ) ? "free": this.inputOptions.anchoe,
      overlaying : ( this.inputOptions === undefined || this.inputOptions.overlaying === undefined ) ? false : this.inputOptions.overlaying,
      position : ( this.inputOptions === undefined || this.inputOptions.position === undefined ) ? 0 : this.inputOptions.position,
      tickfont: {
        family: ( this.inputOptions === undefined || this.inputOptions.tickfont === undefined || this.inputOptions.tickfont.family === undefined  ) ?  Object.keys(fontFamily())[1] : this.inputOptions.tickfont.family,
        size: ( this.inputOptions === undefined || this.inputOptions.tickfont === undefined || this.inputOptions.tickfont.size === undefined ) ? 20 : this.inputOptions.tickfont.size,
        color : ( this.inputOptions === undefined || this.inputOptions.tickfont === undefined || this.inputOptions.tickfont.color === undefined ) ? "#000a12" : this.inputOptions.tickfont.color,
      },
      title: {
        text : ( this.inputOptions === undefined || this.inputOptions.title === undefined || this.inputOptions.title.text === undefined ) ? "Wavelength ( &#181;m )" : this.inputOptions.title.text,
        font : {
          family: ( this.inputOptions === undefined ||  this.inputOptions.title === undefined || this.inputOptions.title.font === undefined || this.inputOptions.title.font.family === undefined  ) ?  Object.keys(fontFamily())[1] : this.inputOptions.title.font.family,
          size: ( this.inputOptions === undefined || this.inputOptions.title === undefined || this.inputOptions.title.font === undefined || this.inputOptions.title.font.size === undefined ) ? 20 : this.inputOptions.title.font.size,
          color : ( this.inputOptions === undefined || this.inputOptions.title === undefined || this.inputOptions.title.font === undefined || this.inputOptions.title.font.color === undefined ) ? "#000a12" : this.inputOptions.title.font.color,
        },
        standoff : ( this.inputOptions === undefined || this.inputOptions.title === undefined || this.inputOptions.title.standoff === undefined ) ? 10 : this.inputOptions.title.standoff ,
      },
      rangeslider : {
        visible : ( this.inputOptions === undefined || this.inputOptions.rangeslider === undefined || this.inputOptions.rangeslider.visible === undefined ) ? false : this.inputOptions.rangeslider.visible,
        thickness : ( this.inputOptions === undefined || this.inputOptions.rangeslider === undefined || this.inputOptions.rangeslider.thickness === undefined ) ?  0.12 : this.inputOptions.rangeslider.thickness,
        bgcolor : ( this.inputOptions === undefined || this.inputOptions.rangeslider === undefined || this.inputOptions.rangeslider.bgcolor === undefined ) ? "#e6ffff" : this.inputOptions.rangeslider.bgcolor,
        borderwidth : ( this.inputOptions === undefined || this.inputOptions.rangeslider === undefined || this.inputOptions.rangeslider.borderwidth === undefined ) ? 1 : this.inputOptions.rangeslider.borderwidth,
        bordercolor : ( this.inputOptions === undefined || this.inputOptions.rangeslider === undefined || this.inputOptions.rangeslider.bordercolor === undefined ) ?  "#263238" : this.inputOptions.rangeslider.bordercolor,
      }

    }
      
  }


  sections() {

    return {

      basicOptions: {
        intro : "Here you can modify the bottom x-axis general",
        // id : `${this.prefix}__${this.axisId}BasicOptionsSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Basic Options",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[visible]`,
                title : "Show",	
                type : "checkbox",
                value : this.options().visible,
                hint: "A single toggle to hide the axis while preserving interaction like dragging. Default is true when a cheater plot is present on the axis, otherwise false"
              },
              {
                id : `${this.axisId}[type]`,
                title : "Type",	
                type : "select",
                options : {
                  // "-": "Default",
                  linear: "Linear",
                  log: "Log",
                  date: "Date",
                  category: "Category",
                  multicategory:"Multi category"
                },
                value : this.options().type,
                disabled: ! this.options().visible  ? true : false,
                hint: "Sets the axis type. By default, plotly attempts to determined the axis type by looking into the data of the traces that referenced the axis in question."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              // {
              //   id : `${this.axisId}[color]`,
              //   title : "Colr",	
              //   type : "color",
              //   value : this.options().color,
              //   hint: "Sets default for all colors associated with this axis all at once: line, font, tick, and grid colors. Grid color is lightened by blending this with the plot background Individual pieces can override this."
              // },
              {
                id : `${this.axisId}[side]`, 
                title : "Side", 
                type : "select",
                options : {
                  bottom: "Bottom",
                  top: "Top"
                },
                value : this.options().side,
                disabled: ! this.options().visible  ? true : false,
                hint : "Determines whether a x (y) axis is positioned at the 'bottom' ('left') or 'top' ('right') of the plotting area."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[autotypenumbers]`,
                title : "Auto Type Numbers",	
                type : "select",
                options : {
                  "convert types": "Convert Types",
                  strict: "Strict",
                },
                value : this.options().autotypenumbers,
                disabled: ! this.options().visible  ? true : false,
                hint: "Using 'strict' a numeric string in trace data is not converted to a number. Using 'convert types' a numeric string in trace data may be treated as a number during automatic axis `type` detection. Defaults to layout.autotypenumbers."
              },
              {
                id : `${this.axisId}[autorange]`,
                title : "Auto Range",	
                type : "select",
                options : {
                  true: "Normal",
                  false: "Disabled",
                  reversed: "Reversed"
                },
                value : this.options().autorange,
                disabled: ( ! this.options().visible || this.options().type !== "linear"  )  ? true : false,
                hint: "Determines whether or not the range of this axis is computed in relation to the input data. See `rangemode` for more info. If `range` is provided, then `autorange` is set to 'false'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[fixedrange]`,
                title : "Fixed Range",	
                type : "checkbox",
                value : this.options().fixedrange,
                disabled: ! this.options().visible  ? true : false,
                hint: "Determines whether or not this axis is zoom-able. If true, then zoom is disabled."
              },
              {
                id : `${this.axisId}[rangemode]`,
                title : "Range Mode",	
                type : "select",
                options : {
                  normal: "Normal",
                  tozero: "To Zero",
                  nonnegative: "Non Negative"
                },
                value : this.options().rangemode,
                disabled: ( ! this.options().visible || ! this.options().autorange )  ? true : false,
                hint: "If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[range]`,
                title : "Range",	
                type : "text",
                value : this.options().range.join(),
                disabled: ( ! this.options().visible  || this.options().autorange ) ? true : false,
                hint: "Sets the range of this axis. If the axis `type` is 'log', then you must take the log of your desired range (e.g. to set the range from 1 to 100, set the range from 0 to 2). If the axis `type` is 'date', it should be date strings, like date data, though Date objects and unix milliseconds will be accepted and converted to strings. If the axis `type` is 'category', it should be numbers, using the scale where each category is assigned a serial number from zero in the order it appears."
              },
              {
                id : `${this.axisId}[scaleanchor]`,
                title : "Scale Anchor",	
                type : "text",
                value : this.options().scaleanchor,
                disabled: ! this.options().visible  ? true : false,
                hint: "If set to another axis id (e.g. `x2`, `y`), the range of this axis changes together with the range of the corresponding axis such that the scale of pixels per unit is in a constant ratio. Both axes are still zoomable, but when you zoom one, the other will zoom the same amount, keeping a fixed midpoint. `constrain` and `constraintoward` determine how we enforce the constraint. You can chain these, ie `yaxis: {scaleanchor: 'x'}, xaxis2: {scaleanchor: 'y'}` but you can only link axes of the same `type`. The linked axis can have the opposite letter (to constrain the aspect ratio) or the same letter (to match scales across subplots). Loops (`yaxis: {scaleanchor: 'x'}, xaxis: {scaleanchor: 'y'}` or longer) are redundant and the last constraint encountered will be ignored to avoid possible inconsistent constraints via `scaleratio`. Note that setting axes simultaneously in both a `scaleanchor` and a `matches` constraint is currently forbidden."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[mirror]`,
                title : "Mirror",	
                type : "select",
                options : {
                  true: "Enabled",
                  ticks: "Ticks",
                  false: "Disabled",
                  all: "All",
                  allticks: "All Ticks",
                },
                value : this.options().mirror,
                disabled:! this.options().visible  ? true : false,
                hint: "Determines if the axis lines and/or ticks are mirrored to the opposite side of the plotting area. If 'true', the axis lines are mirrored. If 'ticks', the axis lines and ticks are mirrored. If 'false', mirroring is disable. If 'all', axis lines are mirrored on all shared-axes subplots. If 'allticks', axis lines and ticks are mirrored on all shared-axes subplots."
              },
              {
                id : `${this.axisId}[automargin]`,
                title : "Auto Margin",	
                type : "checkbox",
                value : this.options().automargin,
                disabled:! this.options().visible  ? true : false,
                hint: "Determines whether long tick labels automatically grow the figure margins."
              },
            ],
          }
        ],
      },

      title: {
        intro : "Here you can modify the bottom x-axis title",
        // id : `${this.prefix}__${this.axisId}TitleSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Title",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[title][text]`,
                title : "Title",
                type : "text", 
                value : this.options().title.text,
                disabled: ! this.options().visible  ? true : false,
                hint: "Sets the title of the y-axis."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[title][font][family]`,
                title : "Title Font",	
                type : "select",
                options : fontFamily(),
                value : this.options().title.font.family,
                disabled: ( ! this.options().visible || ! this.options().title.text ) ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `${this.axisId}[title][font][size]`, 
                title : "Title Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().title.font.size,
                disabled: ( ! this.options().visible || ! this.options().title.text )  ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[title][font][color]`,
                title : "Title Font Color",
                type : "color", 
                value : this.options().title.font.color,
                disabled: ( ! this.options().visible || ! this.options().title.text ) ? true : false,
              },
              {
                id : `${this.axisId}[title][standoff]`,
                title : "Title Standoff ",
                type : "number",
                min : 0,
                max : 2000,
                step : 0.5,
                value : this.options().title.standoff,
                disabled: ( ! this.options().visible || ! this.options().title.text ) ? true : false,
                hint: "Sets the standoff distance (in px) between the axis labels and the title text The default value is a function of the axis tick labels, the title `font.size` and the axis `linewidth`. Note that the axis title position is always constrained within the margins, so the actual standoff distance is always less than the set or default value. By setting `standoff` and turning on `automargin`, plotly.js will push the margins to fit the axis title at given standoff distance."
              }
            ],
          }
        ]
      },

      ticks: {
        intro : "Here you can modify the bottom x-axis ticks",
        // id : `${this.prefix}__${this.axisId}TicksSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Ticks",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[ticks]`,
                title : "Show Ticks",	
                type : "select",
                options : {
                  "": "Hide",
                  inside: "Inside",
                  outside: "Outside"
                },
                value : this.options().ticks,
                disabled: ! this.options().visible  ? true : false,
                hint: "Determines whether ticks are drawn or not. If '', this axis' ticks are not drawn. If 'outside' ('inside'), this axis' are drawn outside (inside) the axis lines."
              },
              {
                id : `${this.axisId}[tickmode]`,
                title : "Tick Mode",	
                type : "select",
                options : {
                  auto: "Auto",
                  linear: "Linear",
                  array: "Array"
                },
                value : this.options().tickmode,
                disabled: ( ! this.options().visible || this.options().ticks === "" ) ? true : false,
                hint: "Sets the tick mode for this axis. If 'auto', the number of ticks is set via `nticks`. If 'linear', the placement of the ticks is determined by a starting position `tick0` and a tick step `dtick` ('linear' is the default value if `tick0` and `dtick` are provided). If 'array', the placement of the ticks is set via `tickvals` and the tick text is `ticktext`. ('array' is the default value if `tickvals` is provided)."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[nticks]`,
                title : "Number of Ticks",	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().nticks,
                disabled: ( ! this.options().visible || this.options().ticks === "" || this.options().tickmode !== "auto" )  ? true : false,
                hint: "Specifies the maximum number of ticks for the particular axis. The actual number of ticks will be chosen automatically to be less than or equal to `nticks`. Has an effect only if `tickmode` is set to 'auto'."
              },
              {
                id : `${this.axisId}[tick0]`,
                title : "First Tick Position",	
                type : "number",
                value : this.options().tick0,
                disabled: ( ! this.options().visible || this.options().ticks === "" || this.options().tickmode !== "linear"  ) ? true : false,
                hint: "Sets the placement of the first tick on this axis. Use with `dtick`. If the axis `type` is 'log', then you must take the log of your starting tick (e.g. to set the starting tick to 100, set the `tick0` to 2) except when `dtick`='L<f>' (see `dtick` for more info). If the axis `type` is 'date', it should be a date string, like date data. If the axis `type` is 'category', it should be a number, using the scale where each category is assigned a serial number from zero in the order it appears."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[dtick]`,
                title : "Tick Spacing",	
                type : "number",
                value : this.options().dtick,
                disabled: ( ! this.options().visible || this.options().ticks === "" || this.options().tickmode !== "linear"  )  ? true : false,
                hint: "Sets the step in-between ticks on this axis. Use with `tick0`. Must be a positive number, or special strings available to 'log' and 'date' axes. If the axis `type` is 'log', then ticks are set every 10^(n'dtick) where n is the tick number. For example, to set a tick mark at 1, 10, 100, 1000, ... set dtick to 1. To set tick marks at 1, 100, 10000, ... set dtick to 2. To set tick marks at 1, 5, 25, 125, 625, 3125, ... set dtick to log_10(5), or 0.69897000433. 'log' has several special values; 'L<f>', where `f` is a positive number, gives ticks linearly spaced in value (but not position). For example `tick0` = 0.1, `dtick` = 'L0.5' will put ticks at 0.1, 0.6, 1.1, 1.6 etc. To show powers of 10 plus small digits between, use 'D1' (all digits) or 'D2' (only 2 and 5). `tick0` is ignored for 'D1' and 'D2'. If the axis `type` is 'date', then you must convert the time to milliseconds. For example, to set the interval between ticks to one day, set `dtick` to 86400000.0. 'date' also has special values 'M<n>' gives ticks spaced by a number of months. `n` must be a positive integer. To set ticks on the 15th of every third month, set `tick0` to '2000-01-15' and `dtick` to 'M3'. To set ticks every 4 years, set `dtick` to 'M48'"
              },
              {
                id : `${this.axisId}[tickvals]`,
                title : "Tick Positions",	
                type : "text",
                value : this.options().tickvals.join(),
                disabled: ( ! this.options().visible || this.options().ticks === "" || this.options().tickmode !== "array" ) ? true : false,
                hint: "Sets the values at which ticks on this axis appear. Only has an effect if `tickmode` is set to 'array'. Used with `ticktext`."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[ticktext]`,
                title : "Tick Label",	
                type : "text",
                value : this.options().ticktext.join(),
                disabled:  ( ! this.options().visible || this.options().ticks === "" || this.options().tickmode !== "array" ) ? true : false,
                hint: "Sets the text displayed at the ticks position via `tickvals`. Only has an effect if `tickmode` is set to 'array'. Used with `tickvals`"
              },
              {
                id : `${this.axisId}[ticklen]`,
                title : "Tick Length",	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().ticklen,
                disabled:  ( ! this.options().visible || this.options().ticks === "" ) ? true : false,
                // disabled: this.options().tickmode !== "array" ? true : false,
                hint: "Sets the tick length (in px)."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[tickwidth]`,
                title : "Tick width",	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().tickwidth,
                disabled:  ( ! this.options().visible || this.options().ticks === "" ) ? true : false,
                // disabled: this.options().tickmode !== "array" ? true : false,
                hint: "Sets the tick width (in px)."
              },
              {
                id : `${this.axisId}[tickcolor]`,
                title : "Tick color",	
                type : "color",
                value : this.options().tickcolor,
                disabled:  ( ! this.options().visible || this.options().ticks === "" ) ? true : false,
                hint: "Sets the tick color."
              },
            ],
          }
        ],
      },

      ticklabels: {
        intro : "Here you can modify the bottom x-axis ticklabels",
        // id : `${this.prefix}__${this.axisId}TicklabelsSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Tick Labels",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[showticklabels]`,
                title : "Show Tick Labels",	
                type : "checkbox",
                value : this.options().showticklabels,
                disabled: ! this.options().visible ? true : false,
                hint: "Determines whether or not the tick labels are drawn."
              },
              {
                id : `${this.axisId}[ticklabelposition]`,
                title : "Tick Label Position",	
                type : "select",
                options : {
                  outside: "Outside",
                  inside: "Inside",
                  "outside left": "Outside Left",
                  "inside left": "Inside Left",
                  "outside right": "Outside Right",
                  "inside right": "Inside Right",
                },
                value : this.options().ticklabelposition,
                disabled: ( ! this.options().visible || ! this.options().showticklabels ) ? true : false,
                hint: "Determines where tick labels are drawn with respect to the axis Please note that top or bottom has no effect on x axes or when `ticklabelmode` is set to 'period'. Similarly left or right has no effect on y axes or when `ticklabelmode` is set to 'period'. Has no effect on 'multicategory' axes or when `tickson` is set to 'boundaries'. When used on axes linked by `matches` or `scaleanchor`, no extra padding for inside labels would be added by autorange, so that the scales could match."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[tickfont][family]`,
                title : "Tick Label Font",	
                type : "select",
                options : fontFamily(),
                value : this.options().tickfont.family,
                disabled: ( ! this.options().visible || ! this.options().showticklabels )  ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `${this.axisId}[tickfont][size]`, 
                title : "Tick  Label Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().tickfont.size,
                disabled: ( ! this.options().visible || ! this.options().showticklabels )  ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[tickfont][color]`,
                title : "Tick Font Color",
                type : "color", 
                value : this.options().tickfont.color,
                disabled: ( ! this.options().visible || ! this.options().showticklabels )  ? true : false,
              },
              {
                id : `${this.axisId}[tickangle]`, 
                title : "Tick Label Angle", 
                type : "number",
                min : -180,
                max : 180,
                step : 1,
                value : this.options().tickangle,
                disabled: ( ! this.options().visible || ! this.options().showticklabels ) ? true : false,
                hint : "Sets the angle of the tick labels with respect to the horizontal. For example, a `tickangle` of -90 draws the tick labels vertically."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[showtickprefix]`, 
                title : "Show Tick Label Prefix", 
                type : "select",
                options : {
                  all: "All",
                  first: "First",
                  last: "Last",
                  none: "None",
                },
                value : this.options().showtickprefix,
                disabled: ( ! this.options().visible || ! this.options().showticklabels )  ? true : false,
                hint : "If 'all', all tick labels are displayed with a prefix. If 'first', only the first tick is displayed with a prefix. If 'last', only the last tick is displayed with a suffix. If 'none', tick prefixes are hidden.Sets a tick label prefix."
              },
              {
                id : `${this.axisId}[tickprefix]`, 
                title : "Tick Label Prefix", 
                type : "Text",
                value : this.options().tickprefix,
                disabled: ( ! this.options().visible || ! this.options().showticklabels || this.options().showtickprefix === "none" )  ? true : false,
                hint : "Sets a tick label prefix."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[showticksuffix]`, 
                title : "Show Tick Label Suffix", 
                type : "select",
                options : {
                  all: "All",
                  first: "First",
                  last: "Last",
                  none: "None",
                },
                value : this.options().showticksuffix,
                disabled: ( ! this.options().visible || ! this.options().showticklabels )  ? true : false,
                hint : "If 'all', all tick labels are displayed with a suffix. If 'first', only the first tick is displayed with a suffix. If 'last', only the last tick is displayed with a suffix. If 'none', tick suffixes are hidden.Sets a tick label suffix."
              },
              {
                id : `${this.axisId}[ticksuffix]`, 
                title : "Tick Label Suffix", 
                type : "Text",
                value : this.options().ticksuffix,
                disabled: ( ! this.options().visible || ! this.options().showticklabels  || this.options().showticksuffix === "none" )  ? true : false,
                hint : "Sets a tick label suffix."
              },
            ],
          }
        ],
      },

      spikes: {
        intro : "Here you can modify the bottom x-axis spikes",
        // id : `${this.prefix}__${this.axisId}SpikesSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Spikes",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[showspikes]`,
                title : "Show Spikes",	
                type : "checkbox",
                value : this.options().showspikes,
                // disabled:! this.options().visible  ? true : false,
                hint: "Determines whether or not spikes (aka droplines) are drawn for this axis. Note: This only takes affect when hovermode = closest"
              },
              {
                id : `${this.axisId}[spikemode]`,
                title : "Spike Mode",	
                type : "select",
                options : {
                  toaxis: "To Axis",
                  across: "Across",
                  marker: "Marker",
                  "toaxis+across": "To Axis & Across",
                  "toaxis+marker": "To Axis & Marker",
                  "across+marker": "Across & Marker",
                  "toaxis+across+marker": "To Axis & Across & Marker",
                },
                value : this.options().spikemode,
                disabled: ! this.options().showspikes ? true : false,
                hint: "Determines the drawing mode for the spike line If 'toaxis', the line is drawn from the data point to the axis the series is plotted on. If 'across', the line is drawn across the entire plot area, and supercedes 'toaxis'. If 'marker', then a marker dot is drawn on the axis the series is plotted on"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[spikedash]`,
                title : "Spike Type",	
                type : "select",
                options : {
                  solid: "Solid",
                  dot: "Dot",
                  dash: "Dash",
                  longdash: "Long Dash",
                  dashdot: "Dash Dot",
                  longdashdot: "Long Dash Dot"
                },
                value : this.options().spikedash,
                disabled: ! this.options().showspikes ? true : false,
                hint: "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
              },
              {
                id : `${this.axisId}[spikethickness]`,
                title : "Spike Thiclness",	
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().spikethickness,
                disabled: ! this.options().showspikes ? true : false,
                hint: "Sets the width (in px) of the zero line."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[spikecolor]`,
                title : "Spike Color",	
                type : "color",
                value : this.options().spikecolor,
                disabled: ! this.options().showspikes ? true : false,
                hint: "Sets the spike color. If undefined, will use the series colorSets the tick color."
              },
            ],
          }
        ],
      },

      exponent: {
        intro : "Here you can modify the bottom x-axis exponent",
        // id : `${this.prefix}__${this.axisId}ExponentSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Exponent",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[showexponent]`, 
                title : "Show Exponent", 
                type : "select",
                options : {
                  all: "All",
                  first: "First",
                  last: "Last",
                  none: "None",
                },
                value : this.options().showexponent,
                disabled: ! this.options().visible  ? true : false,
                hint : "If 'all', all exponents are shown besides their significands. If 'first', only the exponent of the first tick is shown. If 'last', only the exponent of the last tick is shown. If 'none', no exponents appear."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[exponentformat]`, 
                title : "Exponent Format", 
                type : "select",
                options : {
                  e: "e",
                  E: "E",
                  power: "Power",
                  SI: "SI",
                  B: "B",
                  none: "None",
                },
                value : this.options().exponentformat,
                disabled: ( ! this.options().visible || this.options().showexponent === "none" )  ? true : false,
                hint : "Determines a formatting rule for the tick exponents. For example, consider the number 1,000,000,000. If 'none', it appears as 1,000,000,000. If 'e', 1e+9. If 'E', 1E+9. If 'power', 1x10^9 (with 9 in a super script). If 'SI', 1G. If 'B', 1B.If 'all', all exponents are shown besides their significands. If 'first', only the exponent of the first tick is shown. If 'last', only the exponent of the last tick is shown. If 'none', no exponents appear."
              },
              {
                id : `${this.axisId}[minexponent]`, 
                title : "Minimum Exponent", 
                type : "number",
                value : this.options().minexponent,
                disabled: ( ! this.options().visible || this.options().showexponent === "none" ) ? true : false,
                hint : "Hide SI prefix for 10^n if |n| is below this number. This only has an effect when `tickformat` is 'SI' or 'B'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[separatethousands]`, 
                title : "Seperate Thousands", 
                type : "checkbox",
                value : this.options().separatethousands,
                disabled: ! this.options().visible  ? true : false,
                hint : "If 'true', even 4-digit integers are separated"
              },
            ],
          }
        ],
      },

      linesGrids: {
        intro : "Here you can modify the bottom x-axis linesGrids",
        // id : `${this.prefix}__${this.axisId}LinesGridsSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Lines & Grids",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[showline]`, 
                title : "Show Axis Bounding Line", 
                type : "checkbox",
                value : this.options().showline,
                disabled: ! this.options().visible  ? true : false,
                hint : "Determines whether or not a line bounding this axis is drawn."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[linewidth]`, 
                title : "Axis Bounding Line Width", 
                type : "number",
                min : 1,
                max : 100,
                step : 1,
                value : this.options().linewidth,
                disabled: (! this.options().visible || ! this.options().showline ) ? true : false,
                hint : "Sets the width (in px) of the axis line."
              },
              {
                id : `${this.axisId}[linecolor]`, 
                title : "Axis Bounding Line Color", 
                type : "color",
                value : this.options().linecolor,
                disabled: ( ! this.options().visible || ! this.options().showline )  ? true : false,
                hint : "Sets the axis line color."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[zeroline]`, 
                title : "Show Zero Line", 
                type : "checkbox",
                value : this.options().zeroline,
                disabled: ! this.options().visible  ? true : false,
                hint : "Determines whether or not a line is drawn at along the 0 value of this axis. If 'true', the zero line is drawn on top of the grid lines."
              },
              {
                id : `${this.axisId}[showgrid]`, 
                title : "Show Grid", 
                type : "checkbox",
                value : this.options().showgrid,
                disabled: ! this.options().visible  ? true : false,
                hint : "Determines whether or not grid lines are drawn. If 'true', the grid lines are drawn at every tick mark.Determines whether or not a line bounding this axis is drawn."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[zerolinecolor]`, 
                title : "Zero Line Color", 
                type : "color",
                value : this.options().zerolinecolor,
                disabled: ( ! this.options().visible || ! this.options().zeroline )  ? true : false,
                hint : "Sets the line color of the zero line."
              },
              {
                id : `${this.axisId}[zerolinewidth]`, 
                title : "Zero Line Width", 
                type : "number",
                min : 1,
                max : 100,
                step : 1,
                value : this.options().zerolinewidth,
                disabled: ( ! this.options().visible || ! this.options().zeroline )  ? true : false,
                hint : "Sets the width (in px) of the zero line."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[gridwidth]`, 
                title : "Grid Width", 
                type : "number",
                min : 1,
                max : 100,
                step : 1,
                value : this.options().gridwidth,
                disabled: ( ! this.options().visible || ! this.options().showgrid ) ? true : false,
                hint : "Sets the width (in px) of the grid lines."
              },
              {
                id : `${this.axisId}[gridcolor]`, 
                title : "GridColor", 
                type : "color",
                value : this.options().gridcolor,
                disabled: ( ! this.options().visible || ! this.options().showgrid )  ? true : false,
                hint : "Sets the color of the grid lines."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              // {
              //   id : `${this.axisId}[anchor]`, 
              //   title : "Anchor", 
              //   type : "text",
              //   value : this.options().anchor,
              //   hint : "If set to an opposite-letter axis id (e.g. `x2`, `y`), this axis is bound to the corresponding opposite-letter axis. If set to 'free', this axis' position is determined by `position`.  Set anchoe and position to undefined to ignore both"
              // },
              // {
              //   id : `${this.axisId}[position]`, 
              //   title : "Position", 
              //   type : "number",
              //   min : 0,
              //   max : 1,
              //   step : 0.05,
              //   value : this.options().position,
              //   disabled: this.options().anchor !== "free" ? true : false,
              //   hint : "Sets the position of this axis in the plotting space (in normalized coordinates). Only has an effect if `anchor` is set to 'free'. Set anchoe and position to undefined to ignore both"
              // },         
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              // {
              //   id : `${this.axisId}[overlaying]`, 
              //   title : "Overlaying", 
              //   type : "text",
              //   value : this.options().overlaying,
              //   hint : "If set a same-letter axis id, this axis is overlaid on top of the corresponding same-letter axis, with traces and axes visible for both axes. If 'false', this axis does not overlay any same-letter axes. In this case, for axes with overlapping domains only the highest-numbered axis will be visible."
              // },
            ]
          }
        ]
        
      },

      rangeslider : {
        intro : "Here you can modify the plot x-axis range slider",
        // id : `${this.prefix}__${this.axisId}RangesliderSubPanel`,
        // cssClasses:[`${this.axisId}`, "subPanel"],
        title : "Range Slider",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : `${this.axisId}[rangeslider][visible]`,
                title : "Show Range Slider",
                type : "checkbox", 
                value : this.options().rangeslider.visible,
                hint: "Determines whether or not the range slider will be visible. If visible, perpendicular axes will be set to `fixedrange`"
              },
              // {
              //   id :  `${this.axisId}[showMinMaxAvgTable]`,
              //   title : "Show Min/Max/Avg Table",
              //   type : "checkbox", 
              //   value : this.options().showMinMaxAvgTable,
              //   disabled: ! this.options().rangeslider.visible ? true : false,
              //   hint: "Determines whether or not the Min/Max?Avg table will be visible"
              // },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id: `${this.axisId}[rangeslider][thickness]`,
                title:"Range Slider Height",
                type : "number",
                min : 0,
                max : 1,
                step : 0.01,
                value: this.options().rangeslider.thickness,
                disabled: ! this.options().rangeslider.visible ? true : false,
                hint: "The height of the range slider as a fraction of the total plot area height (0 - 1)."
              },
              {
                id : `${this.axisId}[rangeslider][bgcolor]`,
                title : "Background Color",
                type : "color", 
                value : this.options().rangeslider.bgcolor,
                disabled: ! this.options().rangeslider.visible ? true : false,
                hint: "Sets the background color of the range slider."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id: `${this.axisId}[rangeslider][borderwidth]`,
                title:"Border Width",
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value: this.options().rangeslider.borderwidth,
                disabled: ! this.options().rangeslider.visible ? true : false,
                hint: "Sets the border width of the range slider."
              },
              {
                id : `${this.axisId}[rangeslider][bordercolor]`,
                title : "Border Color",
                type : "color", 
                value : this.options().rangeslider.bordercolor,
                disabled: ! this.options().rangeslider.visible ? true : false,
                hint: "Sets the border color of the range slider."
              },
            ]
          }
        ]  
      }, 

    }
    
  }
  

}

export default ChartAxis;


