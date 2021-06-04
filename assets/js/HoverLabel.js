import { fontFamily } from "./utilities"

class Hoverlabel {

  constructor(inputOptions, prefix ) {

    this.inputOptions = inputOptions
    this.prefix = prefix

  }

  options() {

    return {

      hovermode : ( this.inputOptions.hovermode === undefined ) ? "closest" : "false" === this.inputOptions.hovermode ? false : this.inputOptions.hovermode,
      hoverlabel: {
        bgcolor: ( this.inputOptions.hoverlabel === undefined || this.inputOptions.hoverlabel.bgcolor=== undefined ) ? "#e2f1f8" : this.inputOptions.hoverlabel.bgcolor,
        bordercolor: ( this.inputOptions.hoverlabel === undefined || this.inputOptions.hoverlabel.bordercolor=== undefined ) ? "#000a12" : this.inputOptions.hoverlabel.bordercolor,
        align: ( this.inputOptions.hoverlabel === undefined || this.inputOptions.hoverlabel.align=== undefined ) ? "right" : this.inputOptions.hoverlabel.align,
        namelength: ( this.inputOptions.hoverlabel === undefined || this.inputOptions.hoverlabel.namelength=== undefined ) ? -1 : this.inputOptions.hoverlabel.namelength,
        font : {
          family : ( this.inputOptions.hoverlabel === undefined || this.inputOptions.hoverlabel.font === undefined || this.inputOptions.hoverlabel.font.family === undefined ) ? Object.keys(fontFamily())[13] : this.inputOptions.hoverlabel.font.family,
          size : ( this.inputOptions.hoverlabel === undefined || this.inputOptions.hoverlabel.font === undefined || this.inputOptions.hoverlabel.font.size === undefined ) ? 20 : this.inputOptions.hoverlabel.font.size,
          color : ( this.inputOptions.hoverlabel === undefined || this.inputOptions.hoverlabel.font === undefined || this.inputOptions.hoverlabel.font.color === undefined ) ? "#000a12" : this.inputOptions.hoverlabel.font.color,
        }
      },
    
    }
     
  }


  sections() {

    return {
    
      hoverLabel : {
        intro : "Here you can modify the plot title basic options",
        // id : `${this.prefix}__hoverLabelSubPanel`,
        // cssClasses:["hoverLabel", "subPanel"],
        title : "",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "hoverlabel[hovermode]",
                title : "Hover Mode",	
                type : "select",
                options : {
                  x: "X",
                  y: "Y",
                  closest: "Closest",
                  false: "Disabled",
                  "x unified": "X Unified",
                  "y unified": "Y Unified"
                },
                value : this.options().hovermode,
                hint: "Determines the mode of hover interactions. If 'closest', a single hoverlabel will appear for the 'closest' point within the `hoverdistance`. If 'x' (or 'y'), multiple hoverlabels will appear for multiple points at the 'closest' x- (or y-) coordinate within the `hoverdistance`, with the caveat that no more than one hoverlabel will appear per trace. If 'x unified' (or 'y unified'), a single hoverlabel will appear multiple points at the closest x- (or y-) coordinate within the `hoverdistance` with the caveat that no more than one hoverlabel will appear per trace. In this mode, spikelines are enabled by default perpendicular to the specified axis. If false, hover interactions are disabled. If `clickmode` includes the 'select' flag, `hovermode` defaults to 'closest'. If `clickmode` lacks the 'select' flag, it defaults to 'x' or 'y' (depending on the trace's `orientation` value) for plots based on cartesian coordinates. For anything else the default value is 'closest'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "hoverlabel[hoverlabel][bgcolor]",
                title : "Background Color",
                type : "color", 
                value : this.options().hoverlabel.bgcolor,
                disabled: false === this.options().hovermode ? true : false,
                hint: "Sets the background color of all hover labels on graph"
              },
              {
                id : "hoverlabel[hoverlabel][bordercolor]",
                title : "Border Color",
                type : "color", 
                value : this.options().hoverlabel.bordercolor,
                disabled: false === this.options().hovermode ? true : false,
                hint: "Sets the border color of all hover labels on graph."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "hoverlabel[hoverlabel][font][family]",
                title : "Hover label Font",	
                type : "select",
                options : fontFamily(),
                value : this.options().hoverlabel.font.family,
                disabled: false === this.options().hovermode ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "hoverlabel[hoverlabel][font][size]", 
                title : "Hover Label Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().hoverlabel.font.size,
                disabled: false === this.options().hovermode ? true : false,
                hint : "number greater than or equal to 1"
              },
              {
                id : "hoverlabel[hoverlabel][font][color]",
                title : "Hover Label Font Color",
                type : "color", 
                value : this.options().hoverlabel.font.color,
                disabled: false === this.options().hovermode ? true : false,
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "hoverlabel[hoverlabel][align]",
                title : "Hover label Alignmentr",	
                type : "select",
                options : {
                  left: "Left",
                  right: "Right",
                  auto: "Auto"
                },
                value : this.options().hoverlabel.align,
                disabled: false === this.options().hovermode ? true : false,
                hint: "Sets the horizontal alignment of the text content within hover label box. Has an effect only if the hover label text spans more two or more lines"
              },
              {
                id : "hoverlabel[hoverlabel][namelength]", 
                title : "Hover Label Length", 
                type : "number",
                min : -1,
                max : 2000,
                step : 1,
                value : this.options().hoverlabel.namelength,
                disabled: false === this.options().hovermode ? true : false,
                hint : "Sets the default length (in number of characters) of the trace name in the hover labels for all traces. -1 shows the whole name regardless of length. 0-3 shows the first 0-3 characters, and an integer >3 will show the whole name if it is less than that many characters, but if it is longer, will truncate to `namelength - 3` characters and add an ellipsis."
              },
            ]
          }
        ]  
      },
    }
  }

}

export default Hoverlabel


