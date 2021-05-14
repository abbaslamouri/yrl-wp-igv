// import ChartDefault from "./ChartDefault"

import ChartDefault from "./ChartDefault";


class ChartLayout extends ChartDefault {

  constructor(layout, xAxisData, iwpgvObj, fontFamily) {

    super(fontFamily);

    this.layout = layout
    this.prefix = iwpgvObj.prefix
    this.xAxisData = xAxisData
   
  }

  options() {

    return {

      config:{
        responsive : ( this.layout.config  === undefined || this.layout.config.responsive  === undefined ) ? true : this.layout.config.responsive,
        displayModeBar : ( this.layout.config  === undefined || this.layout.config.displayModeBar  === undefined ) ? true :this.layout.config.displayModeBar,
        displaylogo : ( this.layout.config  === undefined || this.layout.config.displaylogo  === undefined ) ? true : this.layout.config.displaylogo,
      },
      showlegend : ( this.layout.showlegend === undefined ) ? false : this.layout.showlegend,
      showMinMaxAvgTable : ( this.layout.showMinMaxAvgTable === undefined ) ? false : this.layout.showMinMaxAvgTable,
      paper_bgcolor : ( this.layout.paper_bgcolor === undefined ) ? "#b0bec5" :  this.layout.paper_bgcolor,
      plot_bgcolor : ( this.layout.plot_bgcolor === undefined ) ? "#eeeeee" : this.layout.plot_bgcolor,
      width : ( this.layout.width === undefined ) ? null : this.layout.width,
      height : ( this.layout.height === undefined ) ? 450 : this.layout.height,
      autosize : ( this.layout.autosize === undefined ) ? true : this.layout.autosize,
      hovermode : ( this.layout.hovermode === undefined ) ? "closest" : this.layout.hovermode === "disabled" ? false : this.layout.hovermode,
      font : {
        family : ( this.layout === undefined || this.layout.font === undefined || this.layout.font.family === undefined ) ? Object.keys(this.fontFamily)[13] : this.layout.font.family,
        size : ( this.layout === undefined || this.layout.font === undefined || this.layout.font.size === undefined ) ? 20 : this.layout.font.size,
        color : ( this.layout === undefined || this.layout.font === undefined || this.layout.font.color === undefined ) ? "#000a12" : this.layout.font.color,
      },
      title: {
        text: ( this.layout.title === undefined || this.layout.title.text=== undefined ) ? 'Ge AR/AR 8.0 - 12.0 &#181;m' : this.layout.title.text ,
        x: ( this.layout.title === undefined || this.layout.title.x=== undefined ) ? 0.1 : this.layout.title.x,
        y: ( this.layout.title === undefined || this.layout.title.y=== undefined ) ? 0.92 : this.layout.title.y,
        font : {
          family : ( this.layout.title === undefined || this.layout.title.font === undefined || this.layout.title.font.family === undefined ) ?  Object.keys(this.fontFamily)[13] : this.layout.title.font.family,
          size : ( this.layout.title === undefined || this.layout.title.font === undefined || this.layout.title.font.size === undefined ) ? 20 : this.layout.title.font.size,
          color : ( this.layout.title === undefined || this.layout.title.font === undefined || this.layout.title.font.color === undefined ) ? "#000a12" : this.layout.title.font.color,
        }
      },
      legend : {
        bgcolor : ( this.layout.legend === undefined || this.layout.legend.bgcolor === undefined ) ? '#e2f1f8' : this.layout.legend.bgcolor,
        bordercolor : ( this.layout.legend=== undefined || this.layout.legend.bordercolor === undefined ) ? '#000a12' : this.layout.legend.bordercolor,
        borderwidth : ( this.layout.legend === undefined  || this.layout.legend.borderwidth === undefined ) ? 1 : this.layout.legend.borderwidth,
        font : {
          family: ( this.layout.legend === undefined || this.layout.legend.font === undefined || this.layout.legend.font.family === undefined  ) ? Object.keys(this.fontFamily)[13] : this.layout.legend.font.family,
          size: ( this.layout.legend === undefined || this.layout.legend.font === undefined || this.layout.legend.font.size === undefined ) ? 14 : this.layout.legend.font.size,
          color : ( this.layout.legend === undefined || this.layout.legend.font === undefined || this.layout.legend.font.color === undefined ) ? "000a12" : this.layout.legend.font.color,
        },
        title: {
          text : ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.text === undefined ) ? "" : this.layout.legend.title.text,
          font : {
            family: ( this.layout.legend === undefined ||  this.layout.legend.title === undefined || this.layout.legend.title.font === undefined || this.layout.legend.title.font.family === undefined  ) ?  Object.keys(this.fontFamily)[1] : this.layout.legend.title.font.family,
            size: ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.font === undefined || this.layout.legend.title.font.size === undefined ) ? 20 : this.layout.legend.title.font.size,
            color : ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.font === undefined || this.layout.legend.title.font.color === undefined ) ? "#000a12" : this.layout.legend.title.font.color,
          },
          side : ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.side === undefined ) ? "top" : this.layout.legend.title.side ,
        },
        orientation : ( this.layout.legend === undefined || this.layout.legend.orientation === undefined ) ? "v" : this.layout.legend.orientation,
        itemsizing : ( this.layout.legend === undefined || this.layout.legend.itemsizing === undefined ) ? "trace" : this.layout.legend.itemsizing,
        itemwidth : ( this.layout.legend === undefined || this.layout.legend.itemwidth === undefined ) ? 50 : this.layout.legend.itemwidth,
        itemclick : ( this.layout.legend === undefined || this.layout.legend.itemclick === undefined ) ? false : this.layout.legend.itemclick === "disabled" ? false : this.layout.legend.itemclick,
        itemdoubleclick : ( this.layout.legend === undefined || this.layout.legend.itemdoubleclick === undefined ) ? false : this.layout.legend.itemdoubleclick === "disabled" ? false : this.layout.legend.itemdoubleclick,
        x : ( this.layout.legend === undefined || this.layout.legend.x === undefined ) ?1.04 :  this.layout.legend.x,
        y : ( this.layout.legend === undefined || this.layout.legend.y === undefined ) ? 1.00 : this.layout.legend.y,
        valign : ( this.layout.legend === undefined || this.layout.legend.valign === undefined ) ? "middle" : this.layout.legend.valign,
      },
      xaxis : {
        type : ( this.layout.xaxis === undefined || this.layout.xaxis.type === undefined ) ? "-" : this.layout.xaxis.type,
        side : ( this.layout.xaxis === undefined || this.layout.xaxis.side === undefined ) ? "bottom" : this.layout.xaxis.side,
        visible : ( this.layout.xaxis === undefined || this.layout.xaxis.visible === undefined ) ? true :  this.layout.xaxis.visible,
        color : ( this.layout.xaxis === undefined || this.layout.xaxis.color === undefined ) ? "#000a12" : this.layout.xaxis.color,
        autotypenumbers : ( this.layout.xaxis === undefined || this.layout.xaxis.autotypenumbers === undefined ) ? "convert types" : this.layout.xaxis.autotypenumbers,
        autorange : ( this.layout.xaxis === undefined || this.layout.xaxis.autorange === undefined ) ? false : this.layout.xaxis.autorange === "false" ? false : this.layout.xaxis.autorange === "true" ? true : this.layout.xaxis.autorange,
        rangemode : ( this.layout.xaxis === undefined || this.layout.xaxis.rangemode === undefined ) ? "normal" : this.layout.xaxis.rangemode,
        range : ( this.layout.xaxis === undefined || this.layout.xaxis.range === undefined ) ? [Math.min(...this.xAxisData), Math.max(...this.xAxisData)] : this.layout.xaxis.range,
        fixedrange : ( this.layout.xaxis === undefined || this.layout.xaxis.fixedrange === undefined ) ? true : this.layout.xaxis.fixedrange,
        scaleanchor : ( this.layout.xaxis === undefined || this.layout.xaxis.scaleanchor === undefined ) ? null : this.layout.xaxis.scaleanchor,
        ticks : ( this.layout.xaxis === undefined || this.layout.xaxis.ticks === undefined ) ? "inside" : this.layout.xaxis.ticks,
        tickmode : ( this.layout.xaxis === undefined || this.layout.xaxis.tickmode === undefined ) ? "auto" : this.layout.xaxis.tickmode,
        nticks : ( this.layout.xaxis === undefined || this.layout.xaxis.nticks === undefined ) ? 0 : this.layout.xaxis.nticks,
        tick0 : ( this.layout.xaxis === undefined || this.layout.xaxis.tick0 === undefined ) ? null : this.layout.xaxis.tick0,
        dtick : ( this.layout.xaxis === undefined || this.layout.xaxis.dtick === undefined ) ? null : this.layout.xaxis.dtick,
        tickvals : ( this.layout.xaxis === undefined || this.layout.xaxis.tickvals === undefined ) ? [] : this.layout.xaxis.tickvals,
        ticktext : ( this.layout.xaxis === undefined || this.layout.xaxis.ticktext === undefined ) ? [] : this.layout.xaxis.ticktext,
        ticklabelposition : ( this.layout.xaxis === undefined || this.layout.xaxis.ticklabelposition === undefined ) ? "outside" : this.layout.xaxis.ticklabelposition,
        mirror : ( this.layout.xaxis === undefined || this.layout.xaxis.mirror === undefined ) ? "all" : this.layout.xaxis.mirror === "false" ? false : this.layout.xaxis.mirror === "true" ? true : this.layout.xaxis.mirror,
        ticklen : ( this.layout.xaxis === undefined || this.layout.xaxis.ticklen === undefined ) ? 10 : this.layout.xaxis.ticklen,
        tickwidth : ( this.layout.xaxis === undefined || this.layout.xaxis.tickwidth === undefined ) ? 2 : this.layout.xaxis.tickwidth,
        tickcolor : ( this.layout.xaxis === undefined || this.layout.xaxis.tickcolor === undefined ) ? "#263238" : this.layout.xaxis.tickcolor,
        showticklabels : ( this.layout.xaxis === undefined || this.layout.xaxis.showticklabels === undefined ) ? true : this.layout.xaxis.showticklabels,
        automargin : ( this.layout.xaxis === undefined || this.layout.xaxis.automargin === undefined ) ? true : this.layout.xaxis.automargin,
        showspikes : ( this.layout.xaxis === undefined || this.layout.xaxis.showspikes === undefined ) ? true : this.layout.xaxis.showspikes,
        spikecolor : ( this.layout.xaxis === undefined || this.layout.xaxis.spikecolor === undefined ) ? "#000a12" : this.layout.xaxis.spikecolor,
        spikethickness : ( this.layout.xaxis === undefined || this.layout.xaxis.spikethickness === undefined ) ? 2 : this.layout.xaxis.spikethickness,
        spikedash : ( this.layout.xaxis === undefined || this.layout.xaxis.spikedash === undefined ) ? "dash" : this.layout.xaxis.spikedash,
        spikemode : ( this.layout.xaxis === undefined || this.layout.xaxis.spikemode === undefined ) ? "toaxis" : this.layout.xaxis.spikemode,
        tickangle : ( this.layout.xaxis === undefined || this.layout.xaxis.tickangle === undefined ) ? 45 : this.layout.xaxis.tickangle,
        tickprefix : ( this.layout.xaxis === undefined || this.layout.xaxis.tickprefix === undefined ) ? "" : this.layout.xaxis.tickprefix,
        showtickprefix : ( this.layout.xaxis === undefined || this.layout.xaxis.showtickprefix === undefined ) ? "none" : this.layout.xaxis.showtickprefix,
        ticksuffix : ( this.layout.xaxis === undefined || this.layout.xaxis.ticksuffix === undefined ) ? "" : this.layout.xaxis.ticksuffix,
        showticksuffix : ( this.layout.xaxis === undefined || this.layout.xaxis.showticksuffix === undefined ) ? "none" : this.layout.xaxis.showticksuffix,
        showexponent : ( this.layout.xaxis === undefined || this.layout.xaxis.showexponent === undefined ) ? "all" : this.layout.xaxis.showexponent,
        exponentformat : ( this.layout.xaxis === undefined || this.layout.xaxis.exponentformat === undefined ) ? "e" : this.layout.xaxis.exponentformat,
        minexponent : ( this.layout.xaxis === undefined || this.layout.xaxis.minexponent === undefined ) ? 3 : this.layout.xaxis.minexponent,
        separatethousands : ( this.layout.xaxis === undefined || this.layout.xaxis.separatethousands === undefined ) ? true : this.layout.xaxis.separatethousands,
        showline : ( this.layout.xaxis === undefined || this.layout.xaxis.showline === undefined ) ? true : this.layout.xaxis.showline,
        linecolor : ( this.layout.xaxis === undefined || this.layout.xaxis.linecolor === undefined ) ? "red" : this.layout.xaxis.linecolor,
        linewidth : ( this.layout.xaxis === undefined || this.layout.xaxis.linewidth === undefined ) ? 1 : this.layout.xaxis.linewidth,
        showgrid : ( this.layout.xaxis === undefined || this.layout.xaxis.showgrid === undefined ) ? true : this.layout.xaxis.showgrid,
        gridcolor : ( this.layout.xaxis === undefined || this.layout.xaxis.gridcolor === undefined ) ? "green" : this.layout.xaxis.gridcolor,
        gridwidth : ( this.layout.xaxis === undefined || this.layout.xaxis.gridwidth === undefined ) ? 2 : this.layout.xaxis.gridwidth,
        
        
        tickfont: {
          family: ( this.layout.xaxis === undefined || this.layout.xaxis.tickfont === undefined || this.layout.xaxis.tickfont.family === undefined  ) ?  Object.keys(this.fontFamily)[1] : this.layout.xaxis.tickfont.family,
          size: ( this.layout.xaxis === undefined || this.layout.xaxis.tickfont === undefined || this.layout.xaxis.tickfont.size === undefined ) ? 20 : this.layout.xaxis.tickfont.size,
          color : ( this.layout.xaxis === undefined || this.layout.xaxis.tickfont === undefined || this.layout.xaxis.tickfont.color === undefined ) ? "#000a12" : this.layout.xaxis.tickfont.color,
        },


       
        title: {
          text : ( this.layout.xaxis === undefined || this.layout.xaxis.title === undefined || this.layout.xaxis.title.text === undefined ) ? "Wavelength ( &#181;m )" : this.layout.xaxis.title.text,
          font : {
            family: ( this.layout.xaxis === undefined ||  this.layout.xaxis.title === undefined || this.layout.xaxis.title.font === undefined || this.layout.xaxis.title.font.family === undefined  ) ?  Object.keys(this.fontFamily)[1] : this.layout.xaxis.title.font.family,
            size: ( this.layout.xaxis === undefined || this.layout.xaxis.title === undefined || this.layout.xaxis.title.font === undefined || this.layout.xaxis.title.font.size === undefined ) ? 20 : this.layout.xaxis.title.font.size,
            color : ( this.layout.xaxis === undefined || this.layout.xaxis.title === undefined || this.layout.xaxis.title.font === undefined || this.layout.xaxis.title.font.color === undefined ) ? "#000a12" : this.layout.xaxis.title.font.color,
          },
          standoff : ( this.layout.xaxis === undefined || this.layout.xaxis.title === undefined || this.layout.xaxis.title.standoff === undefined ) ? 10 : this.layout.xaxis.title.standoff ,
        },
        rangeslider : {
          visible : ( this.layout.xaxis === undefined || this.layout.xaxis.rangeslider === undefined || this.layout.xaxis.rangeslider.visible === undefined ) ? false : this.layout.xaxis.rangeslider.visible,
          bgcolor : ( this.layout.xaxis === undefined || this.layout.xaxis.rangeslider === undefined || this.layout.xaxis.rangeslider.bgcolor === undefined ) ? "#e6ffff" : this.layout.xaxis.rangeslider.bgcolor,
          thickness : ( this.layout.xaxis === undefined || this.layout.xaxis.rangeslider === undefined || this.layout.xaxis.rangeslider.thickness === undefined ) ?  0.1 : this.layout.xaxis.rangeslider.thickness,
        }
      },
      yaxis: {
        type : ( this.layout.yaxis === undefined || this.layout.yaxis.type === undefined ) ? "-" : this.layout.yaxis.type,
        side : ( this.layout.yaxis === undefined || this.layout.yaxis.side === undefined ) ? "left" : this.layout.yaxis.side,
        visible : ( this.layout.yaxis === undefined || this.layout.yaxis.visible === undefined ) ? true : this.layout.yaxis.visible,
        color : ( this.layout.yaxis === undefined || this.layout.yaxis.color === undefined ) ? "#000a12" : this.layout.yaxis.color,
        fixedrange : ( this.layout.yaxis === undefined || this.layout.yaxis.fixedrange === undefined ) ? true : this.layout.yaxis.fixedrange,
        title: {
          text : ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.text === undefined ) ? "Title Text" : this.layout.yaxis.title.text,
          font : {
            family: ( this.layout.yaxis === undefined ||  this.layout.yaxis.title === undefined || this.layout.yaxis.title.font === undefined || this.layout.yaxis.title.font.family === undefined  ) ?  Object.keys(this.fontFamily)[1] : this.layout.yaxis.title.font.family,
            size: ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.font === undefined || this.layout.yaxis.title.font.size === undefined ) ? 20 : this.layout.yaxis.title.font.size,
            color : ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.font === undefined || this.layout.yaxis.title.font.color === undefined ) ? "#000a12" : this.layout.yaxis.title.font.color,
          },
          standoff : ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.standoff === undefined ) ? 10 : this.layout.yaxis.title.standoff ,
        },
      },
      yaxis2: {
        type : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.type === undefined ) ? "-" : this.layout.yaxis2.type,
        overlaying : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.overlaying === undefined ) ? "y" : this.layout.yaxis2.overlaying,
        side : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.side === undefined ) ? "right" : this.layout.yaxis2.side,
        visible : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.visible === undefined ) ? true : this.layout.yaxis2.visible,
        color : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.color === undefined ) ? "#000a12" : this.layout.yaxis2.color,
        fixedrange : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.fixedrange === undefined ) ? true : this.layout.yaxis2.fixedrange,
        title: {
          text : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.text === undefined ) ? "Title Text" : this.layout.yaxis2.title.text,
          font : {
            family: ( this.layout.yaxis2 === undefined ||  this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.font === undefined || this.layout.yaxis2.title.font.family === undefined  ) ?  Object.keys(this.fontFamily)[1] : this.layout.yaxis2.title.font.family,
            size: ( this.layout.yaxis2 === undefined || this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.font === undefined || this.layout.yaxis2.title.font.size === undefined ) ? 20 : this.layout.yaxis2.title.font.size,
            color : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.font === undefined || this.layout.yaxis2.title.font.color === undefined ) ? "#000a12" : this.layout.yaxis2.title.font.color,
          },
          standoff : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.standoff === undefined ) ? 10 : this.layout.yaxis2.title.standoff ,
        },
      },
      margin: {
        l : ( this.layout.margin === undefined || this.layout.margin.l === undefined ) ? 80 : this.layout.margin.l,
        r : ( this.layout.margin === undefined || this.layout.margin.r === undefined ) ? 80 : this.layout.margin.r,
        t : ( this.layout.margin === undefined || this.layout.margin.t === undefined ) ? 100 : this.layout.margin.t,
        b : ( this.layout.margin === undefined || this.layout.margin.b === undefined ) ? 20 : this.layout.margin.b,
        pad: ( this.layout.margin === undefined || this.layout.margin.pad === undefined ) ? 20 : this.layout.margin.pad,
        autoexpand: ( this.layout.margin === undefined || this.layout.margin.autoexpand === undefined ) ? true : this.layout.margin.autoexpand,
      },
      modebar : {
        orientation : ( this.layout.modebar === undefined || this.layout.modebar.orientation === undefined ) ? "h" : this.layout.modebar.orientation,
        bgcolor : ( this.layout.modebar === undefined || this.layout.modebar.bgcolor === undefined ) ? "#eeeeee" : this.layout.modebar.bgcolor,
        color : ( this.layout.modebar === undefined || this.layout.modebar.color === undefined ) ? "#000a12" : this.layout.modebar.color,
        activecolor : ( this.layout.modebar === undefined || this.layout.modebar.activecolor === undefined ) ? "#b0bec5" : this.layout.modebar.activecolor,
      },
      hoverlabel: {
        bgcolor: ( this.layout.hoverlabel === undefined || this.layout.hoverlabel.bgcolor=== undefined ) ? "#e2f1f8" : this.layout.hoverlabel.bgcolor,
        bordercolor: ( this.layout.hoverlabel === undefined || this.layout.hoverlabel.bordercolor=== undefined ) ? "#000a12" : this.layout.hoverlabel.bordercolor,
        align: ( this.layout.hoverlabel === undefined || this.layout.hoverlabel.align=== undefined ) ? "right" : this.layout.hoverlabel.align,
        namelength: ( this.layout.hoverlabel === undefined || this.layout.hoverlabel.namelength=== undefined ) ? -1 : this.layout.hoverlabel.namelength,
        font : {
          family : ( this.layout.hoverlabel === undefined || this.layout.hoverlabel.font === undefined || this.layout.hoverlabel.font.family === undefined ) ? Object.keys(this.fontFamily)[13] : this.layout.hoverlabel.font.family,
          size : ( this.layout.hoverlabel === undefined || this.layout.hoverlabel.font === undefined || this.layout.hoverlabel.font.size === undefined ) ? 20 : this.layout.hoverlabel.font.size,
          color : ( this.layout.hoverlabel === undefined || this.layout.hoverlabel.font === undefined || this.layout.hoverlabel.font.color === undefined ) ? "#000a12" : this.layout.hoverlabel.font.color,
        }
      },

    }

  }


  sections() {

    return {

      // id : `${this.prefix}__chartLayoutPanel`,
      // cssClasses : ['chartLayout', 'chart'],
      // title : "Layout",
      // intro : "This is the layout pnel",
      // sections : {
      general: {
        intro : "Here you can modify the plot general",
        id : `${this.prefix}__chartLayoutPanel__general`,
        cssClasses:["chartLayout", "subPanel"],
        title : "General",
        fields : [
          [
            {
              id : "chartLayout[width]", 
              title : "Plot Width", 
              type : "number",
              min : 10,
              max : 2000,
              step : 10,
              value : this.options().width,
              hint : "Sets the plot's width (in px)."
            },
            
          ],
          [
            {
              id : "chartLayout[font][family]",
              title : "Font Family",	
              type : "select",
              options : this.fontFamily,
              value : this.options().font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
            {
              id : "chartLayout[font][size]", 
              title : "Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().font.size,
              hint : "number greater than or equal to 1"
            },
           
          ],
          [
            {
              id : "chartLayout[paper_bgcolor]",
              title : "Paper Color",
              type : "color", 
              value : this.options().paper_bgcolor,
              hint : "Sets the plot's height (in px)."
            },
            {
              id : "chartLayout[plot_bgcolor]",
              title : "Background Color",
              type : "color", 
              value : this.options().plot_bgcolor,
              hint : "Sets the plot's height (in px)."
            },
            {
              id : "chartLayout[font][color]",
              title : "Font Color",
              type : "color", 
              value : this.options().font.color,
            },
          ],
          [
            {
              id : "chartLayout[height]", 
              title : "Plot Height", 
              type : "number",
              min : 10,
              max : 2000,
              step : 10,
              value : this.options().height,
              hint : "Sets the plot's height (in px)."
            },
            {
              id : "chartLayout[margin][pad]", 
              title : "Chart Padding", 
              type : "number",
              min : 0,
              max : 2000,
              step : 10,
              value : this.options().margin.pad,
              hint : "Sets the amount of padding (in px) between the plotting area and the axis lines"
            },
          ],
          [
            {
              id : "chartLayout[margin][l]", 
              title : "Left Margin", 
              type : "number",
              min : 0,
              max : 2000,
              step : 10,
              value : this.options().margin.l,
              hint : "Sets the left margin (in px)."
            },
            {
              id : "chartLayout[margin][r]", 
              title : "Right Margin", 
              type : "number",
              min : 0,
              max : 2000,
              step : 10,
              value : this.options().margin.r,
              hint : "Sets the right margin (in px)."
            },
          ],
          [
            {
              id : "chartLayout[margin][t]", 
              title : "Top Margin", 
              type : "number",
              min : 0,
              max : 2000,
              step : 10,
              value : this.options().margin.t,
              hint : "Sets the topmargin (in px)."
            },
            {
              id : "chartLayout[margin][b]", 
              title : "Bottom Margin", 
              type : "number",
              min : 0,
              max : 2000,
              step : 10,
              value : this.options().margin.b,
              hint : "Sets the bottom margin (in px)."
            },
          ],
          [
            {
              id : "chartLayout[config][responsive]",
              title : "Responsive ?",
              type : "checkbox", 
              value : this.options().config.responsive,
              hint: ""
            },
            {
              id : "chartLayout[margin][autoexpand]", 
              title : "Margin Auto Expand", 
              type : "checkbox",
              value : this.options().margin.autoexpand,
              hint : "Turns on/off margin expansion computations. Legends, colorbars, updatemenus, sliders, axis rangeselector and rangeslider are allowed to push the margins by defaults."
            },
            {
              id : "chartLayout[autosize]", 
              title : "Auto Size on Relayout", 
              type : "checkbox",
              value : this.options().autosize,
              hint : "Determines whether or not a layout width or height that has been left undefined by the user is initialized on each relayout. Note that, regardless of this attribute, an undefined layout width or height is always initialized on the first call to plot."
            },
          ],
         
        ]  
      },
      xaxis : {
        intro : "Here you can modify the bottom x-axis",
        id : `${this.prefix}__chartLayoutPanel__xaxis`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Bottom Axis",
        fields : [
          [
            {
              id : "chartLayout[xaxis][type]",
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
              value : this.options().xaxis.type,
              hint: "Sets the axis type. By default, plotly attempts to determined the axis type by looking into the data of the traces that referenced the axis in question."
            },
            {
              id : "chartLayout[xaxis][side]",
              title : "Side",	
              type : "select",
              options : {
                bottom: "Bottom",
                top: "Top",
              },
              value : this.options().xaxis.side,
              hint: "Determines whether a x (y) axis is positioned at the 'bottom' ('left') or 'top' ('right') of the plotting area."
            },
            {
              id : "chartLayout[xaxis][visible]",
              title : "Show",	
              type : "checkbox",
              value : this.options().xaxis.visible,
              hint: "A single toggle to hide the axis while preserving interaction like dragging. Default is true when a cheater plot is present on the axis, otherwise false"
            },
          ],
          [
            {
              id : "chartLayout[xaxis][color]",
              title : "Colr",	
              type : "color",
              value : this.options().xaxis.color,
              hint: "Sets default for all colors associated with this axis all at once: line, font, tick, and grid colors. Grid color is lightened by blending this with the plot background Individual pieces can override this."
            },
            {
              id : "chartLayout[xaxis][title][text]",
              title : "Title",
              type : "text", 
              value : this.options().xaxis.title.text,
              hint: "Sets the title of the y-axis."
            },
            {
              id : "chartLayout[xaxis][title][standoff]",
              title : "Standoff ",
              type : "number",
              min : 0,
              max : 2000,
              step : 0.5,
              value : this.options().xaxis.title.standoff,
              hint: "Sets the standoff distance (in px) between the axis labels and the title text The default value is a function of the axis tick labels, the title `font.size` and the axis `linewidth`. Note that the axis title position is always constrained within the margins, so the actual standoff distance is always less than the set or default value. By setting `standoff` and turning on `automargin`, plotly.js will push the margins to fit the axis title at given standoff distance."
            }
          ],
          [
            {
              id : "chartLayout[xaxis][title][font][family]",
              title : "Title Font",	
              type : "select",
              options : this.fontFamily,
              value : this.options().xaxis.title.font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
            {
              id : "chartLayout[xaxis][title][font][size]", 
              title : "Title Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().xaxis.title.font.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[xaxis][title][font][color]",
              title : "Title Font Color",
              type : "color", 
              value : this.options().xaxis.title.font.color,
            },
          ],
          [
            {
              id : "chartLayout[xaxis][autotypenumbers]",
              title : "Auto Type Numbers",	
              type : "select",
              options : {
                "convert types": "Convert Types",
                strict: "Strict",
              },
              value : this.options().xaxis.autotypenumbers,
              hint: "Using 'strict' a numeric string in trace data is not converted to a number. Using 'convert types' a numeric string in trace data may be treated as a number during automatic axis `type` detection. Defaults to layout.autotypenumbers."
            },
            {
              id : "chartLayout[xaxis][autorange]",
              title : "Auto Range",	
              type : "select",
              options : {
                true: "Enabled",
                false: "Disabled",
                reversed: "Reversed"
              },
              value : true === this.options().xaxis.autorange ? "true" : false === this.options().xaxis.autorange ? "false" : this.options().xaxis.autorange,
              hint: "Determines whether or not the range of this axis is computed in relation to the input data. See `rangemode` for more info. If `range` is provided, then `autorange` is set to 'false'."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][rangemode]",
              title : "Range Mode",	
              type : "select",
              options : {
                normal: "Normal",
                tozero: "To Zero",
                nonnegative: "Non Negative"
              },
              value : this.options().xaxis.rangemode,
              hint: "If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes."
            },
            {
              id : "chartLayout[xaxis][range]",
              title : "Range",	
              type : "text",
              value : this.options().xaxis.range.join(),
              hint: "Sets the range of this axis. If the axis `type` is 'log', then you must take the log of your desired range (e.g. to set the range from 1 to 100, set the range from 0 to 2). If the axis `type` is 'date', it should be date strings, like date data, though Date objects and unix milliseconds will be accepted and converted to strings. If the axis `type` is 'category', it should be numbers, using the scale where each category is assigned a serial number from zero in the order it appears."
            },
            {
              id : "chartLayout[xaxis][fixedrange]",
              title : "Fixed Range",	
              type : "checkbox",
              value : this.options().xaxis.fixedrange,
              hint: "Determines whether or not this axis is zoom-able. If true, then zoom is disabled."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][scaleanchor]",
              title : "Scale Anchor",	
              type : "text",
              value : this.options().xaxis.scaleanchor,
              hint: "If set to another axis id (e.g. `x2`, `y`), the range of this axis changes together with the range of the corresponding axis such that the scale of pixels per unit is in a constant ratio. Both axes are still zoomable, but when you zoom one, the other will zoom the same amount, keeping a fixed midpoint. `constrain` and `constraintoward` determine how we enforce the constraint. You can chain these, ie `yaxis: {scaleanchor: 'x'}, xaxis2: {scaleanchor: 'y'}` but you can only link axes of the same `type`. The linked axis can have the opposite letter (to constrain the aspect ratio) or the same letter (to match scales across subplots). Loops (`yaxis: {scaleanchor: 'x'}, xaxis: {scaleanchor: 'y'}` or longer) are redundant and the last constraint encountered will be ignored to avoid possible inconsistent constraints via `scaleratio`. Note that setting axes simultaneously in both a `scaleanchor` and a `matches` constraint is currently forbidden."
            },

            {
              id : "chartLayout[xaxis][ticks]",
              title : "Ticks",	
              type : "select",
              options : {
                "": "Hide",
                inside: "Inside",
                outside: "Outside"
              },
              value : this.options().xaxis.ticks,
              hint: "Determines whether ticks are drawn or not. If '', this axis' ticks are not drawn. If 'outside' ('inside'), this axis' are drawn outside (inside) the axis lines."
            },
            {
              id : "chartLayout[xaxis][tickmode]",
              title : "Tick Mode",	
              type : "select",
              options : {
                auto: "Auto",
                linear: "Linear",
                array: "Array"
              },
              value : this.options().xaxis.tickmode,
              hint: "Sets the tick mode for this axis. If 'auto', the number of ticks is set via `nticks`. If 'linear', the placement of the ticks is determined by a starting position `tick0` and a tick step `dtick` ('linear' is the default value if `tick0` and `dtick` are provided). If 'array', the placement of the ticks is set via `tickvals` and the tick text is `ticktext`. ('array' is the default value if `tickvals` is provided)."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][nticks]",
              title : "Number of Ticks",	
              type : "number",
              min : 0,
              max : 2000,
              step : 1,
              value : this.options().xaxis.nticks,
              hint: "Specifies the maximum number of ticks for the particular axis. The actual number of ticks will be chosen automatically to be less than or equal to `nticks`. Has an effect only if `tickmode` is set to 'auto'."
            },
            {
              id : "chartLayout[xaxis][tick0]",
              title : "First Tick Position",	
              type : "number",
              value : this.options().xaxis.tick0,
              hint: "Sets the tick mode for this axis. If 'auto', the number of ticks is set via `nticks`. If 'linear', the placement of the ticks is determined by a starting position `tick0` and a tick step `dtick` ('linear' is the default value if `tick0` and `dtick` are provided). If 'array', the placement of the ticks is set via `tickvals` and the tick text is `ticktext`. ('array' is the default value if `tickvals` is provided)."
            },
            {
              id : "chartLayout[xaxis][dtick]",
              title : "Number of Ticks",	
              type : "number",
              value : this.options().xaxis.dtick,
              hint: "Sets the step in-between ticks on this axis. Use with `tick0`. Must be a positive number, or special strings available to 'log' and 'date' axes. If the axis `type` is 'log', then ticks are set every 10^(n'dtick) where n is the tick number. For example, to set a tick mark at 1, 10, 100, 1000, ... set dtick to 1. To set tick marks at 1, 100, 10000, ... set dtick to 2. To set tick marks at 1, 5, 25, 125, 625, 3125, ... set dtick to log_10(5), or 0.69897000433. 'log' has several special values; 'L<f>', where `f` is a positive number, gives ticks linearly spaced in value (but not position). For example `tick0` = 0.1, `dtick` = 'L0.5' will put ticks at 0.1, 0.6, 1.1, 1.6 etc. To show powers of 10 plus small digits between, use 'D1' (all digits) or 'D2' (only 2 and 5). `tick0` is ignored for 'D1' and 'D2'. If the axis `type` is 'date', then you must convert the time to milliseconds. For example, to set the interval between ticks to one day, set `dtick` to 86400000.0. 'date' also has special values 'M<n>' gives ticks spaced by a number of months. `n` must be a positive integer. To set ticks on the 15th of every third month, set `tick0` to '2000-01-15' and `dtick` to 'M3'. To set ticks every 4 years, set `dtick` to 'M48'"
            },
          ],
          [
            {
              id : "chartLayout[xaxis][tickvals]",
              title : "Ticks Positions",	
              type : "text",
              value : this.options().xaxis.tickvals.join(),
              readOnly: this.options().xaxis.tickmode !== "array" ? true : false,
              hint: "Sets the values at which ticks on this axis appear. Only has an effect if `tickmode` is set to 'array'. Used with `ticktext`."
            },
            {
              id : "chartLayout[xaxis][ticktext]",
              title : "Ticks Texts",	
              type : "text",
              value : this.options().xaxis.ticktext.join(),
              readOnly: this.options().xaxis.tickmode !== "array" ? true : false,
              hint: "Sets the text displayed at the ticks position via `tickvals`. Only has an effect if `tickmode` is set to 'array'. Used with `tickvals`"
            },
            {
              id : "chartLayout[xaxis][ticklabelposition]",
              title : "Tick Label Position",	
              type : "select",
              options : {
                outside: "Outside",
                inside: "Inside",
                "outside top": "Outside Top",
                "inside top": "Inside Top",
                "outside left": "Outside Left",
                "inside left": "Inside Left",
                "outside right": "Outside Right",
                "inside right": "Inside Right",
                "outside bottom": "Outside Bottom",
                "inside bottom": "Inside Bottom"
              },
              value : this.options().xaxis.ticklabelposition,
              hint: "Determines where tick labels are drawn with respect to the axis Please note that top or bottom has no effect on x axes or when `ticklabelmode` is set to 'period'. Similarly left or right has no effect on y axes or when `ticklabelmode` is set to 'period'. Has no effect on 'multicategory' axes or when `tickson` is set to 'boundaries'. When used on axes linked by `matches` or `scaleanchor`, no extra padding for inside labels would be added by autorange, so that the scales could match."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][mirror]",
              title : "Mirror",	
              type : "select",
              options : {
                true: "Yes",
                ticks: "Ticks",
                false: "No",
                all: "All",
                allticks: "All Ticks",
              },
              value : true === this.options().xaxis.mirror  ? "true" : false === this.options().xaxis.mirror ? "false" : this.options().xaxis.mirror,
              hint: "Determines if the axis lines or/and ticks are mirrored to the opposite side of the plotting area. If 'true', the axis lines are mirrored. If 'ticks', the axis lines and ticks are mirrored. If 'false', mirroring is disable. If 'all', axis lines are mirrored on all shared-axes subplots. If 'allticks', axis lines and ticks are mirrored on all shared-axes subplots."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][ticklen]",
              title : "Tick Length",	
              type : "number",
              min : 0,
              max : 2000,
              step : 1,
              value : this.options().xaxis.ticklen,
              hint: "Sets the tick length (in px)."
            },
            {
              id : "chartLayout[xaxis][tickwidth]",
              title : "Tick width",	
              type : "number",
              min : 0,
              max : 2000,
              step : 1,
              value : this.options().xaxis.tickwidth,
              hint: "Sets the tick width (in px)."
            },
            {
              id : "chartLayout[xaxis][tickcolor]",
              title : "Tick color",	
              type : "color",
              value : this.options().xaxis.tickcolor,
              hint: "Sets the tick color."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][showticklabels]",
              title : "Show Tick Labels",	
              type : "checkbox",
              value : this.options().xaxis.showticklabels,
              hint: "Determines whether or not the tick labels are drawn."
            },
            {
              id : "chartLayout[xaxis][automargin]",
              title : "Auto Margin",	
              type : "checkbox",
              value : this.options().xaxis.automargin,
              hint: "Determines whether long tick labels automatically grow the figure margins."
            },
            {
              id : "chartLayout[xaxis][showspikes]",
              title : "Show Spikes",	
              type : "checkbox",
              value : this.options().xaxis.showspikes,
              hint: "Determines whether or not spikes (aka droplines) are drawn for this axis. Note: This only takes affect when hovermode = closest"
            },
          ],
          [
            {
              id : "chartLayout[xaxis][spikedash]",
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
              value : this.options().xaxis.spikedash,
              hint: "Sets the dash style of lines. Set to a dash type string ('solid', 'dot', 'dash', 'longdash', 'dashdot', or 'longdashdot') or a dash length list in px (eg '5px,10px,2px,2px')."
            },
            {
              id : "chartLayout[xaxis][spikethickness]",
              title : "Spike Thiclness",	
              type : "number",
              min : 0,
              max : 2000,
              step : 1,
              value : this.options().xaxis.spikethickness,
              hint: "Sets the width (in px) of the zero line."
            },
            {
              id : "chartLayout[xaxis][spikecolor]",
              title : "Spike Color",	
              type : "color",
              value : this.options().xaxis.spikecolor,
              hint: "Sets the spike color. If undefined, will use the series colorSets the tick color."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][spikemode]",
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
              value : this.options().xaxis.spikemode,
              hint: "Determines the drawing mode for the spike line If 'toaxis', the line is drawn from the data point to the axis the series is plotted on. If 'across', the line is drawn across the entire plot area, and supercedes 'toaxis'. If 'marker', then a marker dot is drawn on the axis the series is plotted on"
            },
          ],
          [
            {
              id : "chartLayout[xaxis][tickfont][family]",
              title : "Tick Font",	
              type : "select",
              options : this.fontFamily,
              value : this.options().xaxis.tickfont.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
            {
              id : "chartLayout[xaxis][tickfont][size]", 
              title : "Tick Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().xaxis.tickfont.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[xaxis][tickfont][color]",
              title : "Tick Font Color",
              type : "color", 
              value : this.options().xaxis.tickfont.color,
            },
          ],
          [
            {
              id : "chartLayout[xaxis][tickangle]", 
              title : "Tick Abgle", 
              type : "number",
              min : -180,
              max : 180,
              step : 1,
              value : this.options().xaxis.tickangle,
              hint : "Sets the angle of the tick labels with respect to the horizontal. For example, a `tickangle` of -90 draws the tick labels vertically."
            },
            {
              id : "chartLayout[xaxis][tickprefix]", 
              title : "Tick Prefix", 
              type : "Text",
              value : this.options().xaxis.tickprefix,
              hint : "Sets a tick label prefix."
            },
            {
              id : "chartLayout[xaxis][showtickprefix]", 
              title : "Show Tick Prefix", 
              type : "select",
              options : {
                all: "All",
                first: "First",
                last: "Last",
                none: "None",
              },
              value : this.options().xaxis.showtickprefix,
              hint : "If 'all', all tick labels are displayed with a prefix. If 'first', only the first tick is displayed with a prefix. If 'last', only the last tick is displayed with a suffix. If 'none', tick prefixes are hidden.Sets a tick label prefix."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][ticksuffix]", 
              title : "Tick Suffix", 
              type : "Text",
              value : this.options().xaxis.ticksuffix,
              hint : "Sets a tick label suffix."
            },
            {
              id : "chartLayout[xaxis][showticksuffix]", 
              title : "Show Tick Suffix", 
              type : "select",
              options : {
                all: "All",
                first: "First",
                last: "Last",
                none: "None",
              },
              value : this.options().xaxis.showticksuffix,
              hint : "If 'all', all tick labels are displayed with a suffix. If 'first', only the first tick is displayed with a suffix. If 'last', only the last tick is displayed with a suffix. If 'none', tick suffixes are hidden.Sets a tick label suffix."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][showexponent]", 
              title : "Show Exponent", 
              type : "select",
              options : {
                all: "All",
                first: "First",
                last: "Last",
                none: "None",
              },
              value : this.options().xaxis.showexponent,
              hint : "If 'all', all exponents are shown besides their significands. If 'first', only the exponent of the first tick is shown. If 'last', only the exponent of the last tick is shown. If 'none', no exponents appear."
            },
            {
              id : "chartLayout[xaxis][exponentformat]", 
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
              value : this.options().xaxis.exponentformat,
              hint : "Determines a formatting rule for the tick exponents. For example, consider the number 1,000,000,000. If 'none', it appears as 1,000,000,000. If 'e', 1e+9. If 'E', 1E+9. If 'power', 1x10^9 (with 9 in a super script). If 'SI', 1G. If 'B', 1B.If 'all', all exponents are shown besides their significands. If 'first', only the exponent of the first tick is shown. If 'last', only the exponent of the last tick is shown. If 'none', no exponents appear."
            },
            {
              id : "chartLayout[xaxis][minexponent]", 
              title : "Minimum Exponent", 
              type : "number",
              value : this.options().xaxis.minexponent,
              hint : "Hide SI prefix for 10^n if |n| is below this number. This only has an effect when `tickformat` is 'SI' or 'B'."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][separatethousands]", 
              title : "Seperate Thousands", 
              type : "checkbox",
              value : this.options().xaxis.separatethousands,
              hint : "If 'true', even 4-digit integers are separated"
            },
          ],
          [
            {
              id : "chartLayout[xaxis][showline]", 
              title : "Show Bounding Line", 
              type : "checkbox",
              value : this.options().xaxis.showline,
              hint : "Determines whether or not a line bounding this axis is drawn."
            },
            {
              id : "chartLayout[xaxis][linewidth]", 
              title : "Line Width", 
              type : "number",
              min : 1,
              max : 100,
              step : 1,
              value : this.options().xaxis.linewidth,
              hint : "Sets the width (in px) of the axis line."
            },
            {
              id : "chartLayout[xaxis][linecolor]", 
              title : "Line Color", 
              type : "color",
              value : this.options().xaxis.linecolor,
              hint : "Sets the axis line color."
            },
          ],
          [
            {
              id : "chartLayout[xaxis][showgrid]", 
              title : "Show Grid", 
              type : "checkbox",
              value : this.options().xaxis.showgrid,
              hint : "Determines whether or not grid lines are drawn. If 'true', the grid lines are drawn at every tick mark.Determines whether or not a line bounding this axis is drawn."
            },
            {
              id : "chartLayout[xaxis][gridwidth]", 
              title : "Grid Width", 
              type : "number",
              min : 1,
              max : 100,
              step : 1,
              value : this.options().xaxis.gridwidth,
              hint : "Sets the width (in px) of the grid lines."
            },
            {
              id : "chartLayout[xaxis][gridcolor]", 
              title : "GridColor", 
              type : "color",
              value : this.options().xaxis.gridcolor,
              hint : "Sets the color of the grid lines."
            },
          ],
        ]  
      },
      title : {
        intro : "Here you can modify the plot title",
        id : `${this.prefix}__chartLayoutPanel__title`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Title",
        fields : [
          [
            {
              id : "chartLayout[title][text]",
              cssClasses: ["no-hint"],
              title : "Chart Title",
              type : "text", 
              value : this.options().title.text,
            },
          ],
          [
            {
              id : "chartLayout[title][font][family]",
              title : "Font Family",	
              type : "select",
              options : this.fontFamily,
              value : this.options().title.font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
          ],
          [
            {
              id : "chartLayout[title][font][size]", 
              title : "Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().title.font.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[title][font][color]",
              // cssClasses: ["no-hint"],
              title : "Font Color",
              type : "color", 
              value : this.options().title.font.color,
            },
          ],
          [
            {
              id : "chartLayout[title][x]",
              title : "Title Horizontal Position",	
              type : "number",
              min : 0,
              max : 1,
              step : 0.01,
              value : this.options().title.x,
              hint: "Sets the x position from '0' (left) to '1' (right)."
            },
            {
              id : "chartLayout[title][y]",
              title : "Title Vertical Position",	
              type : "number",
              min : 0,
              max : 1,
              step : 0.01,
              value : this.options().title.y,
              hint: "Sets the y position from '0' (bottom) to '1' (top)."
            },
          ],
        ]  
      },
      legend : {
        intro : "Here you can modify the plot legend",
        id : `${this.prefix}__chartLayoutPanel__legend`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Legend",
        fields : [
          [
            {
              id: "chartLayout[showlegend]",
              title:"Show Legend",
              type: "checkbox",
              value: this.options().showlegend,
              hint: "Determines whether or not a legend is drawn. Default is `true` if there is a trace to show and any of these: a) Two or more traces would by default be shown in the legend. b) One pie trace is shown in the legend. c) One trace is explicitly given with `showlegend: true`"
            },
            {
              id : "chartLayout[legend][valign]",
              title : "Text symbol Alignment",	
              type : "select",
              options : {
                top: "Top",
                middle: "Middle",
                bottom: "Bottom"
              },
              value : this.options().legend.valign,
              hint: "Sets the vertical alignment of the symbols with respect to their associated text."
            },
          ],
          [
            {
              id : "chartLayout[legend][bgcolor]",
              title : "Background Color",
              type : "color", 
              value : this.options().legend.bgcolor,
              hint: "Sets the legend background color. Defaults to `layout.paper_bgcolor`."
            },
            {
              id : "chartLayout[legend][bordercolor]",
              title : "Border Color",
              type : "color", 
              value : this.options().legend.bordercolor,
              hint: "Sets the color of the border enclosing the legend."
            },
          ],
          [
            {
              id : "chartLayout[legend][font][family]",
              title : "Font Family",	
              type : "select",
              options : this.fontFamily,
              value : this.options().legend.font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
          ],
          [
            {
              id : "chartLayout[legend][font][size]", 
              title : "Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().legend.font.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[legend][font][color]",
              title : "Font Color",
              type : "color", 
              value : this.options().legend.font.color,
            },
          ],
          [
            {
              id : "chartLayout[legend][borderwidth]",
              title : "Border Width",	
              type : "number",
              min : 0,
              max : 100,
              step : 1,
              value : this.options().legend.borderwidth,
              hint: "Sets the width (in px) of the border enclosing the legend."
            },
            {
              id : "chartLayout[legend][orientation]",
              title : "Orientation",	
              type : "select",
              options : {
                h: "Horizontal",
                v: "Vertical"
              },
              value : this.options().legend.orientation,
              hint: "Sets the orientation of the legend."
            },
          ],
          [
            {
              id : "chartLayout[legend][itemsizing]",
              title : "Item Sizing",	
              type : "select",
              options : {
                trace: "trace",
                constant: "Constant"
              },
              value : this.options().legend.itemsizing,
              hint: "Determines if the legend items symbols scale with their corresponding 'trace' attributes or remain 'constant' independent of the symbol size on the graph."
            },
            {
              id : "chartLayout[legend][itemwidth]",
              title : "Item Width",	
              type : "number",
              min : 30,
              max : 1000,
              step : 1,
              value : this.options().legend.itemwidth,
              hint: "Sets the width (in px) of the legend item symbols (the part other than the title.text)."
            },
          ],
          [
            {
              id : "chartLayout[legend][itemclick]",
              title : "Item Click Behaviour",	
              type : "select",
              options : {
                toggle: "Toggle",
                toggleothers: "Toggle Other",
                disabled: "Disabled"
              },
              value : ( this.options().legend.itemclick ) ? this.options().legend.itemclick : "disabled",
              hint: "Determines the behavior on legend item click. 'toggle' toggles the visibility of the item clicked on the graph. 'toggleothers' makes the clicked item the sole visible item on the graph. 'false' disable legend item click interactions."
            },
            {
              id : "chartLayout[legend][itemdoubleclick]",
              title : "Item Double Click Behaviour",	
              type : "select",
              options : {
                toggle: "Toggle",
                toggleothers: "Toggle Other",
                disabled: "Disabled"
              },
              value : ( this.options().legend.itemdoubleclick ) ? this.options().legend.itemdoubleclick : "disabled",
              hint: "Determines the behavior on legend item doubleclick. 'toggle' toggles the visibility of the item clicked on the graph. 'toggleothers' makes the clicked item the sole visible item on the graph. 'false' disable legend item click interactions."
            },
          ],
          [
            {
              id : "chartLayout[legend][x]",
              title : "Horizontal Position",	
              type : "number",
              min : -2,
              max : 3,
              step : 0.01,
              value : this.options().legend.x,
              hint: "Sets the x position (in normalized coordinates) of the legend. Defaults to '1.02' for vertical legends and defaults to '0' for horizontal legends."
            },
            {
              id : "chartLayout[legend][y]",
              title : "Vertical Position",	
              type : "number",
              min : -2,
              max : 3,
              step : 0.01,
              value : this.options().legend.y,
              hint: "Sets the y position (in normalized coordinates) of the legend. Defaults to '1' for vertical legends, defaults to '-0.1' for horizontal legends on graphs w/o range sliders and defaults to '1.1' for horizontal legends on graph with one or multiple range sliders."
            },
          ],
          [
            {
              id : "chartLayout[legend][title][text]",
              title : "Legend Title",
              type : "text", 
              value : this.options().legend.title.text,
              hint: "Sets the title of the legend."
            },
            {
              id : "chartLayout[legend][title][side]",
              title : "Legend Title Side ",
              type : "select",
              options : {
                top: "Top",
                left: "Left",
                "top left": "Top Left"
              }, 
              value : this.options().legend.title.side,
              hint: "Determines the location of legend's title with respect to the legend items. Defaulted to 'top' with `orientation` is 'h'. Defaulted to 'left' with `orientation` is 'v'. The 'top left' options could be used to expand legend area in both x and y sides."
            }
          ],
          [
            {
              id : "chartLayout[legend][title][font][family]",
              title : "Legend Title Font",	
              type : "select",
              options : this.fontFamily,
              value : this.options().legend.title.font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
          ],
          [
            {
              id : "chartLayout[legend][title][font][size]", 
              title : "Legend Title Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().legend.title.font.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[legend][title][font][color]",
              title : "Legend Title Font Color",
              type : "color", 
              value : this.options().legend.title.font.color,
            },
          ],
        ]  
      },
      yaxis : {
        intro : "Here you can modify the left y-axis",
        id : `${this.prefix}__chartLayoutPanel__yaxis`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Left Axis",
        fields : [
          [
            {
              id : "chartLayout[yaxis][type]",
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
              value : this.options().yaxis.type,
              hint: "Sets the axis type. By default, plotly attempts to determined the axis type by looking into the data of the traces that referenced the axis in question."
            },
          ],
          [
            {
              id : "chartLayout[yaxis][visible]",
              title : "Show Axis",
              type : "checkbox", 
              value : this.options().yaxis.visible,
              hint: "A single toggle to hide the axis while preserving interaction like dragging. Default is true when a cheater plot is present on the axis, otherwise false"
            },
            {
              id : "chartLayout[yaxis][color]",
              title : "Color",
              type : "color", 
              value : this.options().yaxis.color,
              hint: "Sets default for all colors associated with this axis all at once: line, font, tick, and grid colors. Grid color is lightened by blending this with the plot background Individual pieces can override this."
            },
          ],
          [
            {
              id : "chartLayout[yaxis][fixedrange]",
              title : "Zoomable",
              type : "checkbox", 
              value : this.options().yaxis.fixedrange,
              hint: "Determines whether or not this axis is zoom-able. If true, then zoom is disabled."
            },
          ],
          [
            {
              id : "chartLayout[yaxis][title][text]",
              title : "Title",
              type : "text", 
              value : this.options().yaxis.title.text,
              hint: "Sets the title of the y-axis."
            },
            {
              id : "chartLayout[yaxis][title][standoff]",
              title : "Standoff ",
              type : "number",
              min : 0,
              max : 2000,
              step : 0.5,
              value : this.options().yaxis.title.standoff,
              hint: "Sets the standoff distance (in px) between the axis labels and the title text The default value is a function of the axis tick labels, the title `font.size` and the axis `linewidth`. Note that the axis title position is always constrained within the margins, so the actual standoff distance is always less than the set or default value. By setting `standoff` and turning on `automargin`, plotly.js will push the margins to fit the axis title at given standoff distance."
            }
          ],
          [
            {
              id : "chartLayout[yaxis][title][font][family]",
              title : "Title Font",	
              type : "select",
              options : this.fontFamily,
              value : this.options().yaxis.title.font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
          ],
          [
            {
              id : "chartLayout[yaxis][title][font][size]", 
              title : "Title Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().yaxis.title.font.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[yaxis][title][font][color]",
              title : "Title Font Color",
              type : "color", 
              value : this.options().yaxis.title.font.color,
            },
          ],
        ]  
      },
      yaxis2 : {
        intro : "Here you can modify the right y-axis",
        id : `${this.prefix}__chartLayoutPanel__yaxis2`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Right Axis",
        fields : [
          [
            {
              id : "chartLayout[yaxis2][type]",
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
              value : this.options().yaxis2.type,
              hint: "Sets the axis type. By default, plotly attempts to determined the axis type by looking into the data of the traces that referenced the axis in question."
            },
            {
              id : "chartLayout[yaxis2][side]",
              title : "Side",	
              type : "select",
              options : {
                left: "Left",
                right: "Right",
              },
              value : this.options().yaxis2.side,
              hint: "Determines whether a x (y) axis is positioned at the 'bottom' ('left') or 'top' ('right') of the plotting area."
            },
          ],
          [
            {
              id : "chartLayout[yaxis2][visible]",
              title : "Show Axis",
              type : "checkbox", 
              value : this.options().yaxis2.visible,
              hint: "A single toggle to hide the axis while preserving interaction like dragging. Default is true when a cheater plot is present on the axis, otherwise false"
            },
            {
              id : "chartLayout[yaxis2][color]",
              title : "Color",
              type : "color", 
              value : this.options().yaxis2.color,
              hint: "Sets default for all colors associated with this axis all at once: line, font, tick, and grid colors. Grid color is lightened by blending this with the plot background Individual pieces can override this."
            },
          ],
          [
            {
              id : "chartLayout[yaxis2][fixedrange]",
              title : "Zoomable",
              type : "checkbox", 
              value : this.options().yaxis2.fixedrange,
              hint: "Determines whether or not this axis is zoom-able. If true, then zoom is disabled."
            },
          ],
          [
            {
              id : "chartLayout[yaxis2][title][text]",
              title : "Title",
              type : "text", 
              value : this.options().yaxis2.title.text,
              hint: "Sets the title of the y-axis."
            },
            {
              id : "chartLayout[yaxis2][title][standoff]",
              title : "Standoff ",
              type : "number",
              min : 0,
              max : 2000,
              step : 0.5,
              value : this.options().yaxis2.title.standoff,
              hint: "Sets the standoff distance (in px) between the axis labels and the title text The default value is a function of the axis tick labels, the title `font.size` and the axis `linewidth`. Note that the axis title position is always constrained within the margins, so the actual standoff distance is always less than the set or default value. By setting `standoff` and turning on `automargin`, plotly.js will push the margins to fit the axis title at given standoff distance."
            }
          ],
          [
            {
              id : "chartLayout[yaxis2][title][font][family]",
              title : "Title Font",	
              type : "select",
              options : this.fontFamily,
              value : this.options().yaxis2.title.font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
          ],
          [
            {
              id : "chartLayout[yaxis2][title][font][size]", 
              title : "Title Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().yaxis2.title.font.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[yaxis2][title][font][color]",
              title : "Title Font Color",
              type : "color", 
              value : this.options().yaxis2.title.font.color,
            },
          ],
        ]  
      },
      hoverLabel : {
        intro : "Here you can modify the plot Hover Label",
        id : `${this.prefix}__chartLayoutPanel__hoverlabel`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Hover Label",
        fields : [
          [
            {
              id : "chartLayout[hovermode]",
              title : "Hover Mode",	
              type : "select",
              options : {
                x: "X",
                y: "Y",
                closest: "Closest",
                disabled: "Disabled",
                "x unified": "X Unified",
                "y unified": "Y Unified"
              },
              value : (this.options().hovermode)? this.options().hovermode : "disabled",
              hint: "Determines the mode of hover interactions. If 'closest', a single hoverlabel will appear for the 'closest' point within the `hoverdistance`. If 'x' (or 'y'), multiple hoverlabels will appear for multiple points at the 'closest' x- (or y-) coordinate within the `hoverdistance`, with the caveat that no more than one hoverlabel will appear per trace. If 'x unified' (or 'y unified'), a single hoverlabel will appear multiple points at the closest x- (or y-) coordinate within the `hoverdistance` with the caveat that no more than one hoverlabel will appear per trace. In this mode, spikelines are enabled by default perpendicular to the specified axis. If false, hover interactions are disabled. If `clickmode` includes the 'select' flag, `hovermode` defaults to 'closest'. If `clickmode` lacks the 'select' flag, it defaults to 'x' or 'y' (depending on the trace's `orientation` value) for plots based on cartesian coordinates. For anything else the default value is 'closest'."
            },
          ],
          [
            {
              id : "chartLayout[hoverlabel][bgcolor]",
              title : "Background Color",
              type : "color", 
              value : this.options().hoverlabel.bgcolor,
              hint: "Sets the background color of all hover labels on graph"
            },
            {
              id : "chartLayout[hoverlabel][bordercolor]",
              title : "Border Color",
              type : "color", 
              value : this.options().hoverlabel.bordercolor,
              hint: "Sets the border color of all hover labels on graph."
            },
          ],
          [
            {
              id : "chartLayout[hoverlabel][font][family]",
              title : "Hover label Font",	
              type : "select",
              options : this.fontFamily,
              value : this.options().hoverlabel.font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
          ],
          [
            {
              id : "chartLayout[hoverlabel][font][size]", 
              title : "Hover Label Font Size", 
              type : "number",
              min : 1,
              max : 100,
              step : 0.5,
              value : this.options().hoverlabel.font.size,
              hint : "number greater than or equal to 1"
            },
            {
              id : "chartLayout[hoverlabel][font][color]",
              title : "Hover Label Font Color",
              type : "color", 
              value : this.options().hoverlabel.font.color,
            },
          ],
          [
            {
              id : "chartLayout[hoverlabel][align]",
              title : "Hover label Alignmentr",	
              type : "select",
              options : {
                left: "Left",
                right: "Right",
                auto: "Auto"
              },
              value : this.options().hoverlabel.align,
              hint: "Sets the horizontal alignment of the text content within hover label box. Has an effect only if the hover label text spans more two or more lines"
            },
            {
              id : "chartLayout[hoverlabel][namelength]", 
              title : "Hover Label Length", 
              type : "number",
              min : -1,
              max : 2000,
              step : 1,
              value : this.options().hoverlabel.namelength,
              hint : "Sets the default length (in number of characters) of the trace name in the hover labels for all traces. -1 shows the whole name regardless of length. 0-3 shows the first 0-3 characters, and an integer >3 will show the whole name if it is less than that many characters, but if it is longer, will truncate to `namelength - 3` characters and add an ellipsis."
            },
          ]
        ]  
      },
      modebar : {
        intro : "Here you can modify the plot modebar",
        id : `${this.prefix}__chartLayoutPanel__modebar`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Modebar",
        fields : [
          [
            
            {
              id : "chartLayout[config][displayModeBar]",
              title : "Display ModeBar ?",
              type : "checkbox", 
              value : this.options().config.displayModeBar,
              hint: ""
            },
            {
              id : "chartLayout[config][displaylogo]",
              title : "Display Plotly Logo ?",
              type : "checkbox", 
              value : this.options().config.displaylogo,
              disabled: this.options().config.displayModeBar ? false : true,
              hint: ""
            },
          ],
          [
            {
              id : "chartLayout[modebar][bgcolor]",
              title : "Background Color",
              type : "color", 
              value : this.options().modebar.bgcolor,
              hint: "Sets the background color of the modebar."
            },
            {
              id: "chartLayout[modebar][orientation]",
              title:"Modebar Orientation",
              type : "select",
              options : {
                h: "Horizontal",
                v: "Vertical"
              },
              value: this.options().modebar.orientation,
              hint: "Sets the orientation of the modebar"
            },
          ],
          [
            {
              id : "chartLayout[modebar][color]",
              title : "Icon Color",
              type : "color", 
              value : this.options().modebar.color,
              hint: "Sets the color of the icons in the modebar."
            },
            {
              id : "chartLayout[modebar][activecolor]",
              title : "Active Icon Color",
              type : "color", 
              value : this.options().modebar.activecolor,
              hint: "Sets the color of the active or hovered on icons in the modebar."
            },
          ],
        ]  
      },
      rangeslider : {
        intro : "Here you can modify the plot x-axis range slider",
        id : `${this.prefix}__chartLayoutPanel__xaxis_rangeslider`,
        cssClasses:["chartLayout", "subPanel"],
        title : "Range Slider",
        fields : [
          [
            {
              id : "chartLayout[xaxis][rangeslider][visible]",
              title : "Show Range Slider",
              type : "checkbox", 
              value : this.options().xaxis.rangeslider.visible,
              hint: "Determines whether or not the range slider will be visible. If visible, perpendicular axes will be set to `fixedrange`"
            },
            {
              id : "chartLayout[showMinMaxAvgTable]",
              title : "Show Min/Max/Avg Table",
              type : "checkbox", 
              value : this.options().showMinMaxAvgTable,
              hint: "Determines whether or not the Min/Max?Avg table will be visible"
            },
          ],
          [
            {
              id: "chartLayout[xaxis][rangeslider][thickness]",
              title:"Range Slider Height",
              type : "number",
              min : 0,
              max : 1,
              step : 0.01,
              value: this.options().xaxis.rangeslider.thickness,
              hint: "The height of the range slider as a fraction of the total plot area height (0 - 1)."
            },
            {
              id : "chartLayout[xaxis][rangeslider][bgcolor]",
              title : "Range Slider Background Color",
              type : "color", 
              value : this.options().xaxis.rangeslider.bgcolor,
              hint: "Sets the background color of the range slider."
            },
          ]
        ]  
      },
      // config : {
      //   intro : "Here you can modify the plot configuration",
      //   id : `${this.prefix}__chartLayoutPanel__config`,
      //   cssClasses:["chartLayout", "subPanel"],
      //   title : "Configuration",
      //   fields : [
          
      //   ]  
      // }
    }
    
  }
  

}

export default ChartLayout;


