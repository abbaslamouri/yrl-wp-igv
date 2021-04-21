// import ChartDefault from "./ChartDefault"


class ChartLayout{

  constructor(layout, iwpgvObj) {

    // super(chartTypes, colors,pointShapes, lineWidth, markerSize, fontNames);

    this.layout = layout
    this.prefix = iwpgvObj.prefix

    this.fontFamily = {	
      "" : "Select Font Family",
      arial : "Arial",
      balto: "Balto",
      TimesNewRoman: "Times New Roman",
      courierNew: "Courier New",
      droidSans: "Droid Sans",
      droidSerif: "Droid Serif",
      droidSansMono: "Droid Sans Mono",
      gravitasOne: "Gravitas One",
      oldStandardTT: " Old Standard TT",
      openSans: "Open Sans",
      overpass: "Overpass",
      ptSansNarrow: "PT Sans Narrow",
      raleway: "Raleway"
    }


   
  }

  options() {

    return {

      paper_bgcolor : ( typeof( this.layout.paper_bgcolor ) !== "undefined" ) ? this.layout.paper_bgcolor: "#CCCCCC",
      plot_bgcolor : ( typeof( this.layout.plot_bgcolor ) !== "undefined" ) ? this.layout.plot_bgcolor : "#FFFFFF",
      width : ( typeof( this.layout.width ) !== "undefined" ) ? this.layout.width : 1000,
      height : ( typeof( this.layout.height ) !== "undefined" ) ? this.layout.height : 400,
      autosize : ( typeof ( this.layout['autosize']) !== "undefined" ) ? this.layout['autosize'] : true,
      hovermode : ( typeof ( this.layout['hovermode']) !== "undefined" ) ? this.layout['hovermode'] : "closest",
      font : {
        family : ( typeof( this.layout ) !== "undefined" && typeof( this.layout.font ) !== "undefined" && typeof( this.layout.font.family ) !== "undefined" ) ? this.layout.font.family : Object.keys(this.fontFamily)[13],
        size : ( typeof( this.layout ) !== "undefined" && typeof( this.layout.font ) !== "undefined" && typeof( this.layout.font.size ) !== "undefined" ) ? this.layout.font.size : 20,
        color : ( typeof( this.layout ) !== "undefined" && typeof( this.layout.font ) !== "undefined" && typeof( this.layout.font.color ) !== "undefined" ) ? this.layout.font.color : "#4b636e",
      },
      title: {
        text: ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.text) !== "undefined" ) ? this.layout.title.text : 'Hi There',
        x: ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.x) !== "undefined" ) ? this.layout.title.x : 0.5,
        y: ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.y) !== "undefined" ) ? this.layout.title.y : 0.9,
        font : {
          family : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.family ) !== "undefined" ) ? this.layout.title.font.family : Object.keys(this.fontFamily)[13],
          size : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.size ) !== "undefined" ) ? this.layout.title.font.size : 40,
          color : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.color ) !== "undefined" ) ? this.layout.title.font.color : "#008080",
        }
      },
      showlegend : ( typeof( this.layout.showlegend ) !== "undefined" ) ? this.layout.showlegend : true,
      legend : {
        bgcolor : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.bgcolor ) !== "undefined" ) ? this.layout.legend.bgcolor : '#ffc246',
        bordercolor : ( typeof( this.layout.legend) !== "undefined" && typeof( this.layout.legend.bordercolor ) !== "undefined" ) ? this.layout.legend.bordercolor : '#67daff',
        borderwidth : ( typeof( this.layout.legend ) !== "undefined"  && typeof( this.layout.legend.borderwidth ) !== "undefined" ) ? this.layout.legend.borderwidth : 2,
        font : {
          family: ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.family ) !== "undefined"  ) ? this.layout.legend.font.family : Object.keys(this.fontFamily)[13],
          size: ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.size ) !== "undefined" ) ? this.layout.legend.font.size : 20,
          color : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.color ) !== "undefined" ) ? this.layout.legend.font.color : "#39796b",
        },
        title: {
          text : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.text ) !== "undefined" ) ? this.layout.legend.text : 'My Legend',
          font : {
            family: ( typeof( this.layout.legend ) !== "undefined" &&  typeof( this.layout.legend.title ) !== "undefined" && typeof( this.layout.legend.title.font ) !== "undefined" && typeof( this.layout.legend.title.font.family ) !== "undefined"  ) ? this.layout.legend.title.font.family : Object.keys(this.fontFamily)[1],
            size: ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.title ) !== "undefined" && typeof( this.layout.legend.title.font ) !== "undefined" && typeof( this.layout.legend.title.font.size ) !== "undefined" ) ? this.layout.legend.title.font.size : 20,
            color : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.title ) !== "undefined" && typeof( this.layout.legend.title.font ) !== "undefined" && typeof( this.layout.legend.title.font.color ) !== "undefined" ) ? this.layout.legend.title.font.color : "#ae52d4",
          },
          side : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.title ) !== "undefined" && typeof( this.layout.legend.title.side ) !== "undefined" ) ? this.layout.legend.title.side : 'top left',
        },
        orientation : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.orientation ) !== "undefined" ) ? this.layout.legend.orientation : 'v',
        itemsizing : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.itemsizing ) !== "undefined" ) ? this.layout.legend.itemsizing : 'constant',
        itemwidth : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.itemwidth ) !== "undefined" ) ? this.layout.legend.itemwidth : 100,
        itemclick : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.itemclick ) !== "undefined" ) ? this.layout.legend.itemclick : "toggle",
        x : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.x ) !== "undefined" ) ? this.layout.legend.x : 1.01,
        y : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.y ) !== "undefined" ) ? this.layout.legend.y : 0.5,
        valign : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.valign ) !== "undefined" ) ? this.layout.legend.valign : "middle",
      },
      xaxis : {
        automargin : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.automargin ) !== "undefined" ) ? this.layout.xaxis.automargin : true,
        rangeslider : {
          visible : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.visible ) !== "undefined" ) ? this.layout.xaxis.rangeslider.visible : false,
          bgcolor : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.bgcolor ) !== "undefined" ) ? this.layout.xaxis.rangeslider.bgcolor : "teal",
          thickness : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.thickness ) !== "undefined" ) ? this.layout.xaxis.rangeslider.thickness : 0.2,
        }
      },
      yaxis: {
        fixedrange : ( typeof ( this.layout.yaxis ) !== "undefined" && typeof ( this.layout.yaxis.fixedrange ) !== "undefined" ) ? this.layout.yaxis.fixedrange : true,
      },
      margin: {
        l : ( typeof( this.layout.l ) !== "undefined" ) ? this.layout.l : 80,
        r : ( typeof( this.layout.r ) !== "undefined" ) ? this.layout.r : 80,
        t : ( typeof( this.layout.t ) !== "undefined" ) ? this.layout.t : 100,
        b : ( typeof( this.layout.b ) !== "undefined" ) ? this.layout.b : 80,
        pad: ( typeof( this.layout.pad ) !== "undefined" ) ? this.layout.pad : 20,
        autoexpand: ( typeof( this.layout.autoexpand ) !== "undefined" ) ? this.layout.autoexpand : true,
      },
      modebar : {
        orientation : ( typeof( this.layout ) !== "undefined" && typeof( this.layout.modebar ) !== "undefined" && typeof( this.layout.modebar.orientation ) !== "undefined" ) ? this.layout.modebar.orientation : 'h',
        bgcolor : ( typeof( this.layout ) !== "undefined" && typeof( this.layout.modebar ) !== "undefined" && typeof( this.layout.modebar.bgcolor ) !== "undefined" ) ? this.layout.modebar.bgcolor : "#519657",
        color : ( typeof( this.layout ) !== "undefined" && typeof( this.layout.modebar ) !== "undefined" && typeof( this.layout.modebar.color ) !== "undefined" ) ? this.layout.modebar.color : "#7f0000",
        activecolor : ( typeof( this.layout ) !== "undefined" && typeof( this.layout.modebar ) !== "undefined" && typeof( this.layout.modebar.activecolor ) !== "undefined" ) ? this.layout.modebar.activecolor : "#f05545",
      },
      hoverlabel: {
        bgcolor: ( typeof( this.layout.hoverlabel ) !== "undefined" && typeof( this.layout.hoverlabel.bgcolor) !== "undefined" ) ? this.layout.hoverlabel.bgcolor : null,
        bordercolor: ( typeof( this.layout.hoverlabel ) !== "undefined" && typeof( this.layout.hoverlabel.bordercolor) !== "undefined" ) ? this.layout.hoverlabel.bordercolor : null,
        align: ( typeof( this.layout.hoverlabel ) !== "undefined" && typeof( this.layout.hoverlabel.align) !== "undefined" ) ? this.layout.hoverlabel.align : 'right',
        namelength: ( typeof( this.layout.hoverlabel ) !== "undefined" && typeof( this.layout.hoverlabel.namelength) !== "undefined" ) ? this.layout.hoverlabel.namelength : -1,
        font : {
          family : ( typeof( this.layout.hoverlabel ) !== "undefined" && typeof( this.layout.hoverlabel.font ) !== "undefined" && typeof( this.layout.hoverlabel.font.family ) !== "undefined" ) ? this.layout.hoverlabel.font.family : Object.keys(this.fontFamily)[13],
          size : ( typeof( this.layout.hoverlabel ) !== "undefined" && typeof( this.layout.hoverlabel.font ) !== "undefined" && typeof( this.layout.hoverlabel.font.size ) !== "undefined" ) ? this.layout.hoverlabel.font.size : 20,
          color : ( typeof( this.layout.hoverlabel ) !== "undefined" && typeof( this.layout.hoverlabel.font ) !== "undefined" && typeof( this.layout.hoverlabel.font.color ) !== "undefined" ) ? this.layout.hoverlabel.font.color : null,
        }
      },

    }

  }


  panel() {

    return {

      id : `${this.prefix}__chartLayoutPanel`,
      cssClasses : ['chartLayout', 'chart'],
      title : "Chart Layout",
      intro : "This is the layout pnel",
      sections : {
        General: {
          intro : "Here you can modify the plot general",
          id : `${this.prefix}__chartLayoutPanel__general`,
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
                cssClasses : ["color-picker"],
                value : this.options().paper_bgcolor,
              },
              {
                id : "chartLayout[plot_bgcolor]",
                title : "Background Color",
                type : "color", 
                // "cssClasses" : ["color-picker"],
                value : this.options().plot_bgcolor,
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
                id : "chartLayout[margin][autoexpand]", 
                title : "Margin Auto Expand", 
                type : "checkbox",
                value : this.options().margin.autoexpand,
                hint : "Turns on/off margin expansion computations. Legends, colorbars, updatemenus, sliders, axis rangeselector and rangeslider are allowed to push the margins by defaults."
              },
            ],
            [
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
                title : "Font Color",
                type : "color", 
                value : this.options().font.color,
              },
            ],
            [
              {
                id : "chartLayout[hovermode]",
                title : "Hover Mode",	
                type : "select",
                options : {
                  x: "X",
                  y: "Y",
                  closest: "Closest",
                  "false": "Disabled",
                  "x unified": "X Unified",
                  "y unified": "Y Unified"
                },
                value : this.options().hovermode,
                hint: "Determines the mode of hover interactions. If 'closest', a single hoverlabel will appear for the 'closest' point within the `hoverdistance`. If 'x' (or 'y'), multiple hoverlabels will appear for multiple points at the 'closest' x- (or y-) coordinate within the `hoverdistance`, with the caveat that no more than one hoverlabel will appear per trace. If 'x unified' (or 'y unified'), a single hoverlabel will appear multiple points at the closest x- (or y-) coordinate within the `hoverdistance` with the caveat that no more than one hoverlabel will appear per trace. In this mode, spikelines are enabled by default perpendicular to the specified axis. If false, hover interactions are disabled. If `clickmode` includes the 'select' flag, `hovermode` defaults to 'closest'. If `clickmode` lacks the 'select' flag, it defaults to 'x' or 'y' (depending on the trace's `orientation` value) for plots based on cartesian coordinates. For anything else the default value is 'closest'."
              },
            ]
          ]  
        },
        title : {
          intro : "Here you can modify the plot title",
          id : `${this.prefix}__chartLayoutPanel__title`,
          title : "Title",
          fields : [
            [
              {
                id : "chartLayout[title][text]",
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
                step : 0.1,
                value : this.options().title.x,
                hint: "Sets the x position from '0' (left) to '1' (right)."
              },
              {
                id : "chartLayout[title][y]",
                title : "Title Vertical Position",	
                type : "number",
                min : 0,
                max : 1,
                step : 0.1,
                value : this.options().title.y,
                hint: "Sets the y position from '0' (bottom) to '1' (top)."
              },
            ],
          ]  
        },
        legend : {
          intro : "Here you can modify the plot legend",
          id : `${this.prefix}__chartLayoutPanel__legend`,
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
              {
                id : "chartLayout[legend][itemclick]",
                title : "Item Click Behaviour",	
                type : "select",
                options : {
                  toggle: "Toggle",
                  toggleothers: "Toggle Other",
                  false: "Disable"
                },
                value : this.options().legend.itemclick,
                hint: "Determines the behavior on legend item click. 'toggle' toggles the visibility of the item clicked on the graph. 'toggleothers' makes the clicked item the sole visible item on the graph. 'false' disable legend item click interactions."
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
        hoverLabel : {
          intro : "Here you can modify the plot Hover Label",
          id : `${this.prefix}__chartLayoutPanel__hoverlabel`,
          title : "Hover Label",
          fields : [
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
          title : "Modebar",
          fields : [
            [
              {
                id : "chartLayout[modebar][bgcolor]",
                title : "Background Color",
                type : "color", 
                value : this.options().modebar.bgcolor,
                hint: "Sets the background color of the modebar."
              },
              {
                id : "chartLayout[modebar][color]",
                title : "Icon Color",
                type : "color", 
                value : this.options().modebar.color,
                hint: "Sets the color of the icons in the modebar."
              },
            ],
            [
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
      }
    }
    
  }
  

}

export default ChartLayout;


