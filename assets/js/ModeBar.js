
class Modebar {

  constructor(layout, config) {

    this.layout = layout
    this.config = config

  }

  options() {

    return {
 
      displayModeBar : ( this.config.displayModeBar  === undefined ) ? true :this.config.displayModeBar,
      displaylogo : ( this.config.displaylogo  === undefined ) ? true : this.config.displaylogo,
      modebar : {
        orientation : ( this.layout.modebar === undefined || this.layout.modebar.orientation === undefined ) ? "h" : this.layout.modebar.orientation,
        bgcolor : ( this.layout.modebar === undefined || this.layout.modebar.bgcolor === undefined ) ? "#eeeeee" : this.layout.modebar.bgcolor,
        color : ( this.layout.modebar === undefined || this.layout.modebar.color === undefined ) ? "#000a12" : this.layout.modebar.color,
        activecolor : ( this.layout.modebar === undefined || this.layout.modebar.activecolor === undefined ) ? "#b0bec5" : this.layout.modebar.activecolor,
      },
    
    }
     
  }


  sections() {

    return {
    
      hoverLabel : {
        intro : "Here you can modify the plot modbar options",
        title : "",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "config[displayModeBar]",
                title : "Display ModeBar ?",
                type : "checkbox", 
                value : this.options().displayModeBar,
                hint: ""
              },
              {
                id : "config[displaylogo]",
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
                id : "layout[modebar][bgcolor]",
                title : "Background Color",
                type : "color", 
                value : this.options().modebar.bgcolor,
                disabled: ! this.options().displayModeBar ? true: false,
                hint: "Sets the background color of the modebar."
              },
              {
                id: "layout[modebar][orientation]",
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
                id : "layout[modebar][color]",
                title : "Icon Color",
                type : "color", 
                value : this.options().modebar.color,
                disabled: ! this.options().displayModeBar ? true: false,
                hint: "Sets the color of the icons in the modebar."
              },
              {
                id : "layout[modebar][activecolor]",
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


