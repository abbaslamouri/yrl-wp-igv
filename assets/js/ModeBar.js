
class Modebar {

  constructor(inputOptions, prefix ) {

    this.inputOptions = inputOptions
    this.prefix = prefix

  }

  options() {

    return {
 
      // displayModeBar : ( this.inputOptions.displayModeBar  === undefined ) ? false :this.inputOptions.displayModeBar,
      // displaylogo : ( this.inputOptions.displaylogo  === undefined ) ? true : this.inputOptions.displaylogo,
      // modebar : {
      //   orientation : ( this.inputOptions.modebar === undefined || this.inputOptions.modebar.orientation === undefined ) ? "h" : this.inputOptions.modebar.orientation,
      //   bgcolor : ( this.inputOptions.modebar === undefined || this.inputOptions.modebar.bgcolor === undefined ) ? "#eeeeee" : this.inputOptions.modebar.bgcolor,
      //   color : ( this.inputOptions.modebar === undefined || this.inputOptions.modebar.color === undefined ) ? "#000a12" : this.inputOptions.modebar.color,
      //   activecolor : ( this.inputOptions.modebar === undefined || this.inputOptions.modebar.activecolor === undefined ) ? "#b0bec5" : this.inputOptions.modebar.activecolor,
      // },
    
    }
     
  }


  sections() {

    return {
    
      hoverLabel : {
        intro : "Here you can modify the plot modbar options",
        // id : `${this.prefix}__modeBarSubPanel`,
        // cssClasses:["modeBar", "subPanel"],
        title : "",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "modebar[displayModeBar]",
                title : "Display ModeBar ?",
                type : "checkbox", 
                value : this.options().displayModeBar,
                hint: ""
              },
              {
                id : "modebar[displaylogo]",
                title : "Display Plotly Logo ?",
                type : "checkbox", 
                value : this.options().displaylogo,
                disabled: ! this.options().displayModeBar ? true: false,
                hint: ""
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "modebar[modebar][bgcolor]",
                title : "Background Color",
                type : "color", 
                value : this.options().modebar.bgcolor,
                disabled: ! this.options().displayModeBar ? true: false,
                hint: "Sets the background color of the modebar."
              },
              {
                id: "modebar[modebar][orientation]",
                title:"Modebar Orientation",
                type : "select",
                options : {
                  h: "Horizontal",
                  v: "Vertical"
                },
                value: this.options().modebar.orientation,
                disabled: ! this.options().displayModeBar ? true: false,
                hint: "Sets the orientation of the modebar"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "modebar[modebar][color]",
                title : "Icon Color",
                type : "color", 
                value : this.options().modebar.color,
                disabled: ! this.options().displayModeBar ? true: false,
                hint: "Sets the color of the icons in the modebar."
              },
              {
                id : "modebar[modebar][activecolor]",
                title : "Active Icon Color",
                type : "color", 
                value : this.options().modebar.activecolor,
                disabled: ! this.options().displayModeBar ? true: false,
                hint: "Sets the color of the active or hovered on icons in the modebar."
              },
            ],
          }
        ]  
      }
    }
  }
}

export default Modebar


