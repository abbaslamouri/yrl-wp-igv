import { fontFamily } from "./utilities"

class Title {

  constructor(inputOptions, prefix ) {

    this.inputOptions = inputOptions
    this.prefix = prefix

  }

  options() {

    return {

      text: ( this.inputOptions === undefined || this.inputOptions.text=== undefined ) ? "Ge AR/AR 8.0 - 12.0 &#181;m" : this.inputOptions.text ,
      x: ( this.inputOptions === undefined || this.inputOptions.x=== undefined ) ? 0.1 : this.inputOptions.x,
      y: ( this.inputOptions === undefined || this.inputOptions.y=== undefined ) ? 0.92 : this.inputOptions.y,
      font : {
        family : ( this.inputOptions === undefined || this.inputOptions.font === undefined || this.inputOptions.font.family === undefined ) ?  Object.keys(fontFamily())[13] : this.inputOptions.font.family,
        size : ( this.inputOptions === undefined || this.inputOptions.font === undefined || this.inputOptions.font.size === undefined ) ? 20 : this.inputOptions.font.size,
        color : ( this.inputOptions === undefined || this.inputOptions.font === undefined || this.inputOptions.font.color === undefined ) ? "#000a12" : this.inputOptions.font.color,
      }
    
    }
     
  }


  sections() {

    return {
    
      chartTitle : {
        intro : "Here you can modify the plot title basic options",
        // id : `${this.prefix}__chartTitleSubPanel`,
        // cssClasses:["chartTitle", "subPanel"],
        title : "",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "chartTitle[text]",
                cssClasses: ["no-hint"],
                title : "Chart Title",
                type : "text", 
                value : this.options().text,
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "chartTitle[font][family]",
                title : "Font Family",	
                type : "select",
                options : fontFamily(),
                value : this.options().font.family,
                disabled: ! this.options().text  ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "chartTitle[font][size]", 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().font.size,
                disabled: ! this.options().text  ? true : false,
                hint : "number greater than or equal to 1"
              },
              {
                id : "chartTitle[font][color]",
                // cssClasses: ["no-hint"],
                title : "Font Color",
                type : "color", 
                value : this.options().font.color,
                disabled: ! this.options().text  ? true : false,
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "chartTitle[x]",
                title : "Title Horizontal Position",	
                type : "number",
                min : 0,
                max : 1,
                step : 0.01,
                value : this.options().x,
                disabled: ! this.options().text  ? true : false,
                hint: "Sets the x position from '0' (left) to '1' (right)."
              },
              {
                id : "chartTitle[y]",
                title : "Title Vertical Position",	
                type : "number",
                min : 0,
                max : 1,
                step : 0.01,
                value : this.options().y,
                disabled: ! this.options().text  ? true : false,
                hint: "Sets the y position from '0' (bottom) to '1' (top)."
              },
            ],
          }
        ]  
      },
     
    }
    
  }

}

export default Title


