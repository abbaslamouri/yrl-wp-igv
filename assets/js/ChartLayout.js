// import ChartDefault from "./ChartDefault"

import ChartDefault from "./ChartDefault";


class ChartLayout extends ChartDefault {

  constructor(layout, iwpgvObj, fontFamily) {

    super(fontFamily);

    this.layout = layout
    this.prefix = iwpgvObj.prefix

    console.log("this.layout.showMinMaxAvgTable", this.layout.showMinMaxAvgTable)

    // this.fontFamily = fontFamily
   
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
      hovermode : ( this.layout.hovermode === undefined ) ? false : this.layout.hovermode === "disabled" ? false : this.layout.hovermode,
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
        automargin : ( this.layout.xaxis === undefined|| this.layout.xaxis.automargin === undefined ) ? true : this.layout.xaxis.automargin,
        rangeslider : {
          visible : ( this.layout.xaxis === undefined || this.layout.xaxis.rangeslider === undefined || this.layout.xaxis.rangeslider.visible === undefined ) ? false : this.layout.xaxis.rangeslider.visible,
          bgcolor : ( this.layout.xaxis === undefined|| this.layout.xaxis.rangeslider === undefined|| this.layout.xaxis.rangeslider.bgcolor === undefined ) ? "#e6ffff" : this.layout.xaxis.rangeslider.bgcolor,
          thickness : ( this.layout.xaxis === undefined|| this.layout.xaxis.rangeslider === undefined|| this.layout.xaxis.rangeslider.thickness === undefined ) ?  0.1 : this.layout.xaxis.rangeslider.thickness,
        }
      },
      yaxis: {
        type : ( this.layout.yaxis === undefined || this.layout.yaxis.type === undefined ) ? "-" : this.layout.yaxis.type,
        visible : ( this.layout.yaxis === undefined || this.layout.yaxis.visible === undefined ) ? true : this.layout.yaxis.visible,
        color : ( this.layout.yaxis === undefined || this.layout.yaxis.color === undefined ) ? "#DD2C00" : this.layout.yaxis.color,
        fixedrange : ( this.layout.yaxis === undefined || this.layout.yaxis.fixedrange === undefined ) ? true : this.layout.yaxis.fixedrange,
        title: {
          text : ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.text === undefined ) ? "Title Text" : this.layout.yaxis.title.text,
          font : {
            family: ( this.layout.yaxis === undefined ||  this.layout.yaxis.title === undefined || this.layout.yaxis.title.font === undefined || this.layout.yaxis.title.font.family === undefined  ) ?  Object.keys(this.fontFamily)[1] : this.layout.yaxis.title.font.family,
            size: ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.font === undefined || this.layout.yaxis.title.font.size === undefined ) ? 20 : this.layout.yaxis.title.font.size,
            color : ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.font === undefined || this.layout.yaxis.title.font.color === undefined ) ? "#1B5E20" : this.layout.yaxis.title.font.color,
          },
          standoff : ( this.layout.yaxis === undefined || this.layout.yaxis.title === undefined || this.layout.yaxis.title.standoff === undefined ) ? 10 : this.layout.yaxis.title.standoff ,
        },
      },
      yaxis2: {
        type : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.type === undefined ) ? "-" : this.layout.yaxis2.type,
        overlaying : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.overlaying === undefined ) ? "y" : this.layout.yaxis2.overlaying,
        side : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.side === undefined ) ? "right" : this.layout.yaxis2.side,
        visible : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.visible === undefined ) ? true : this.layout.yaxis2.visible,
        color : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.color === undefined ) ? "#FF6D00" : this.layout.yaxis2.color,
        fixedrange : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.fixedrange === undefined ) ? true : this.layout.yaxis2.fixedrange,
        title: {
          text : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.text === undefined ) ? "Title Text" : this.layout.yaxis2.title.text,
          font : {
            family: ( this.layout.yaxis2 === undefined ||  this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.font === undefined || this.layout.yaxis2.title.font.family === undefined  ) ?  Object.keys(this.fontFamily)[1] : this.layout.yaxis2.title.font.family,
            size: ( this.layout.yaxis2 === undefined || this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.font === undefined || this.layout.yaxis2.title.font.size === undefined ) ? 20 : this.layout.yaxis2.title.font.size,
            color : ( this.layout.yaxis2 === undefined || this.layout.yaxis2.title === undefined || this.layout.yaxis2.title.font === undefined || this.layout.yaxis2.title.font.color === undefined ) ? "#808e95" : this.layout.yaxis2.title.font.color,
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
              id : "chartLayout[margin][pad]", 
              title : "Chart Padding", 
              type : "number",
              min : 0,
              max : 2000,
              step : 10,
              value : this.options().margin.pad,
              hint : "Sets the amount of padding (in px) between the plotting area and the axis lines"
            },
            {
              id : "chartLayout[config][responsive]",
              title : "Responsive ?",
              type : "checkbox", 
              value : this.options().config.responsive,
              hint: ""
            },
          ],
          [
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
          [
            {
              id : "chartLayout[font][family]",
              title : "Font Family",	
              type : "select",
              options : this.fontFamily,
              value : this.options().font.family,
              hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
            },
          ],
          [
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
            {
              id : "chartLayout[font][color]",
              // cssClasses: ["no-hint"],
              title : "Font Color",
              type : "color", 
              value : this.options().font.color,
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
                top: "Top",
                bottom: "Bottom"
              },
              value : this.options().yaxis2.type,
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


