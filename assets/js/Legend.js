import { fontFamily } from "./utilities"

class Legend {

  constructor( layout ) {

    this.layout = layout

  }

  options() {

    return {

      showlegend : ( this.layout.showlegend === undefined ) ? false : this.layout.showlegend,
      legend : {
        bgcolor : ( this.layout.legend === undefined || this.layout.legend.bgcolor === undefined ) ? '#FFFFFF' : this.layout.legend.bgcolor,
        bordercolor : ( this.layout.legend=== undefined || this.layout.legend.bordercolor === undefined ) ? '#444444' : this.layout.legend.bordercolor,
        borderwidth : ( this.layout.legend === undefined  || this.layout.legend.borderwidth === undefined ) ? 0 : this.layout.legend.borderwidth,
        font : {
          family: ( this.layout.legend === undefined || this.layout.legend.font === undefined || this.layout.legend.font.family === undefined  ) ? Object.keys(fontFamily())[12] : this.layout.legend.font.family,
          size: ( this.layout.legend === undefined || this.layout.legend.font === undefined || this.layout.legend.font.size === undefined ) ? 14 : this.layout.legend.font.size,
          color : ( this.layout.legend === undefined || this.layout.legend.font === undefined || this.layout.legend.font.color === undefined ) ? "000a12" : this.layout.legend.font.color,
        },
        title: {
          text : ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.text === undefined ) ? "" : this.layout.legend.title.text,
          font : {
            family: ( this.layout.legend === undefined ||  this.layout.legend.title === undefined || this.layout.legend.title.font === undefined || this.layout.legend.title.font.family === undefined  ) ?  Object.keys(fontFamily())[12] : this.layout.legend.title.font.family,
            size: ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.font === undefined || this.layout.legend.title.font.size === undefined ) ? 14 : this.layout.legend.title.font.size,
            color : ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.font === undefined || this.layout.legend.title.font.color === undefined ) ? "#000a12" : this.layout.legend.title.font.color,
          },
          side : ( this.layout.legend === undefined || this.layout.legend.title === undefined || this.layout.legend.title.side === undefined ) ? "top" : this.layout.legend.title.side ,
        },
        orientation : ( this.layout.legend === undefined || this.layout.legend.orientation === undefined ) ? "v" : this.layout.legend.orientation,
        itemsizing : ( this.layout.legend === undefined || this.layout.legend.itemsizing === undefined ) ? "trace" : this.layout.legend.itemsizing,
        itemwidth : ( this.layout.legend === undefined || this.layout.legend.itemwidth === undefined ) ? 30 : this.layout.legend.itemwidth,
        itemclick : ( this.layout.legend === undefined || this.layout.legend.itemclick === undefined ) ? "toggle" : this.layout.legend.itemclick === "false" ? false : this.layout.legend.itemclick,
        itemdoubleclick : ( this.layout.legend === undefined || this.layout.legend.itemdoubleclick === undefined ) ? "toggle" : this.layout.legend.itemdoubleclick === "false" ? false : this.layout.legend.itemdoubleclick,
        x : ( this.layout.legend === undefined || this.layout.legend.x === undefined ) ?1.02 :  this.layout.legend.x,
        y : ( this.layout.legend === undefined || this.layout.legend.y === undefined ) ? 1.00 : this.layout.legend.y,
        valign : ( this.layout.legend === undefined || this.layout.legend.valign === undefined ) ? "middle" : this.layout.legend.valign,
      },
    
    }
     
  }


  sections() {

    return {
    
      legend : {
        intro : "Here you can modify the plot legend",
        title : "",
        fieldGroups : [
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id: "layout[showlegend]",
                title:"Show Legend",
                type: "checkbox",
                value: this.options().showlegend,
                hint: "Determines whether or not a legend is drawn. Default is `true` if there is a trace to show and any of these: a) Two or more traces would by default be shown in the legend. b) One pie trace is shown in the legend. c) One trace is explicitly given with `showlegend: true`"
              },
              {
                id: "layout[legend][valign]",
                title : "Text symbol Alignment",	
                type : "select",
                options : {
                  top: "Top",
                  middle: "Middle",
                  bottom: "Bottom"
                },
                value : this.options().legend.valign,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the vertical alignment of the symbols with respect to their associated text."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : "layout[legend][borderwidth]",
                title : "Border Width",	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.options().legend.borderwidth,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the width (in px) of the border enclosing the legend."
              },
              {
                id : "layout[legend][bordercolor]",
                title : "Border Color",
                type : "color", 
                value : this.options().legend.bordercolor,
                disabled: ! this.options().showlegend  || ! this.options().legend.borderwidth ? true : false,
                hint: "Sets the color of the border enclosing the legend."
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : "layout[legend][font][family]",
                title : "Font Family",	
                type : "select",
                options : fontFamily(),
                value : this.options().legend.font.family,
                disabled: ! this.options().showlegend ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : "layout[legend][font][size]", 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().legend.font.size,
                disabled: ! this.options().showlegend ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : "layout[legend][font][color]",
                title : "Font Color",
                type : "color", 
                value : this.options().legend.font.color,
                disabled: ! this.options().showlegend ? true : false,
              },
              {
                id : "layout[legend][orientation]",
                title : "Orientation",	
                type : "select",
                options : {
                  h: "Horizontal",
                  v: "Vertical"
                },
                value : this.options().legend.orientation,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the orientation of the legend."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[legend][itemsizing]",
                title : "Item Sizing",	
                type : "select",
                options : {
                  trace: "trace",
                  constant: "Constant"
                },
                value : this.options().legend.itemsizing,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Determines if the legend items symbols scale with their corresponding 'trace' attributes or remain 'constant' independent of the symbol size on the graph."
              },
              {
                id : "layout[legend][itemwidth]",
                title : "Item Width",	
                type : "number",
                min : 30,
                max : 1000,
                step : 1,
                value : this.options().legend.itemwidth,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the width (in px) of the legend item symbols (the part other than the title.text). Number greater than or equal to 30.  Default: 30."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[legend][itemclick]",
                title : "Item Click Behaviour",	
                type : "select",
                options : {
                  toggle: "Toggle",
                  toggleothers: "Toggle Other",
                  false: "Disabled"
                },
                value : this.options().legend.itemclick,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Determines the behavior on legend item click. 'toggle' toggles the visibility of the item clicked on the graph. 'toggleothers' makes the clicked item the sole visible item on the graph. 'false' disable legend item click interactions."
              },
              {
                id : "layout[legend][itemdoubleclick]",
                title : "Item Double Click Behaviour",	
                type : "select",
                options : {
                  toggle: "Toggle",
                  toggleothers: "Toggle Other",
                  false: "Disabled"
                },
                value : this.options().legend.itemdoubleclick,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Determines the behavior on legend item doubleclick. 'toggle' toggles the visibility of the item clicked on the graph. 'toggleothers' makes the clicked item the sole visible item on the graph. 'false' disable legend item click interactions."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[legend][x]",
                title : "Horizontal Position",	
                type : "number",
                min : -2,
                max : 3,
                step : 0.01,
                value : this.options().legend.x,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the x position (in normalized coordinates) of the legend. Defaults to '1.02' for vertical legends and defaults to '0' for horizontal legends."
              },
              {
                id : "layout[legend][y]",
                title : "Vertical Position",	
                type : "number",
                min : -2,
                max : 3,
                step : 0.01,
                value : this.options().legend.y,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the y position (in normalized coordinates) of the legend. Defaults to '1' for vertical legends, defaults to '-0.1' for horizontal legends on graphs w/o range sliders and defaults to '1.1' for horizontal legends on graph with one or multiple range sliders."
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : "layout[legend][bgcolor]",
                title : "Background Color",
                type : "color", 
                value : this.options().legend.bgcolor,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the legend background color. Defaults to `layout.paper_bgcolor`."
              },
              {
                id : "layout[legend][title][text]",
                title : "Legend Title",
                type : "text", 
                value : this.options().legend.title.text,
                disabled: ! this.options().showlegend ? true : false,
                hint: "Sets the title of the legend."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[legend][title][font][family]",
                title : "Legend Title Font",	
                type : "select",
                options : fontFamily(),
                value : this.options().legend.title.font.family,
              disabled: ( ! this.options().showlegend || ! this.options().legend.title.text ) ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : "layout[legend][title][font][size]", 
                title : "Legend Title Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().legend.title.font.size,
              disabled: ( ! this.options().showlegend || ! this.options().legend.title.text ) ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "forty-sixty"],
            inputFields: [
              {
                id : "layout[legend][title][font][color]",
                title : "Legend Title Font Color",
                type : "color", 
                value : this.options().legend.title.font.color,
              disabled: ( ! this.options().showlegend || ! this.options().legend.title.text ) ? true : false,
              },
              {
                id : "layout[legend][title][side]",
                title : "Legend Title Side ",
                type : "select",
                options : {
                  top: "Top",
                  left: "Left",
                  "top left": "Top Left"
                }, 
                value : this.options().legend.title.side,
                disabled: ( ! this.options().showlegend || ! this.options().legend.title.text ) ? true : false,
                hint: "Determines the location of legend's title with respect to the legend items. Defaulted to 'top' with `orientation` is 'h'. Defaulted to 'left' with `orientation` is 'v'. The 'top left' options could be used to expand legend area in both x and y sides."
              }
            ],
          },
        ]  
      },
     
    }
    
  }

}

export default Legend


