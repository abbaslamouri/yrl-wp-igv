import { fontFamily } from "./utilities"

class Title {

  constructor(layout ) {

    console.log(Object.keys(fontFamily()))

    this.layout = layout

  }

  options() {

    return {

      title : {
        text: ( this.layout.title === undefined || this.layout.title.text=== undefined ) ? "Ge AR/AR 8.0 - 12.0 &#181;m" : this.layout.title.text ,
        x: ( this.layout.title === undefined || this.layout.title.x=== undefined ) ? 0.1 : this.layout.title.x,
        y: ( this.layout.title === undefined || this.layout.title.y=== undefined ) ? null : this.layout.title.y,
        font : {
          family : ( this.layout.title === undefined || this.layout.title.font === undefined || this.layout.title.font.family === undefined ) ?  Object.keys(fontFamily())[12] : this.layout.title.font.family,
          size : ( this.layout.title === undefined || this.layout.title.font === undefined || this.layout.title.font.size === undefined ) ? 14 : this.layout.title.font.size,
          color : ( this.layout.title === undefined || this.layout.title.font === undefined || this.layout.title.font.color === undefined ) ? "#000a12" : this.layout.title.font.color,
        },
        // pad: {
        //   l : ( this.layout.title === undefined || this.layout.title.pad === undefined || this.layout.title.pad.l === undefined ) ? 80 : this.layout.title.pad.l,
        //   r : ( this.layout.title === undefined || this.layout.title.pad === undefined || this.layout.title.pad.r === undefined ) ? 80 : this.layout.title.pad.r,
        //   t : ( this.layout.title === undefined || this.layout.title.pad === undefined || this.layout.title.pad.t === undefined ) ? 100 : this.layout.title.pad.t,
        //   b : ( this.layout.title === undefined || this.layout.title.pad === undefined || this.layout.pad.b === undefined ) ? 80 : this.layout.pad.b,
        // },
      }
    
    }
     
  }


  sections() {

    return {
    
      chartTitle : {
        intro : "Here you can modify the plot title basic options",
        title : "",
        fieldGroups : [
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : "layout[title][text]",
                cssClasses: ["no-hint"],
                title : "Chart Title",
                type : "text", 
                value : this.options().title.text,
              },
              {
                id : "layout[title][font][color]",
                // cssClasses: ["no-hint"],
                title : "Font Color",
                type : "color", 
                value : this.options().title.font.color,
                disabled: ! this.options().title.text  ? true : false,
              },
            ],
          },
          {
            cssClasses : ["field-group", "sixty-forty"],
            inputFields: [
              {
                id : "layout[title][font][family]",
                title : "Font Family",	
                type : "select",
                options : fontFamily(),
                value : this.options().title.font.family,
                disabled: ! this.options().title.text  ? true : false,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : "layout[title][font][size]", 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().title.font.size,
                disabled: ! this.options().title.text  ? true : false,
                hint : "number greater than or equal to 1"
              },
            ],
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[title][x]",
                title : "Horizontal Position",	
                type : "number",
                min : 0,
                max : 1,
                step : 0.01,
                value : this.options().title.x,
                disabled: ! this.options().title.text  ? true : false,
                hint: "Sets the x position from '0' (left) to '1' (right)."
              },
              {
                id : "layout[title][y]",
                title : "Vertical Position",	
                type : "number",
                min : 0,
                max : 1,
                step : 0.01,
                value : this.options().title.y,
                disabled: ! this.options().title.text  ? true : false,
                hint: "Sets the y position from '0' (bottom) to '1' (top)."
              },
            ],
          },
          // {
          //   cssClasses : ["field-group", "fifty-fifty"],
          //   inputFields: [
          //     {
          //       id : "layout[title][pad][l]",
          //       title : "Padding Left",	
          //       type : "number",
          //       min : 0,
          //       max : 1000,
          //       step : 1,
          //       value : this.options().title.pad.l,
          //       disabled: ! this.options().title.text  ? true : false,
          //       hint: "Sets the x position from '0' (left) to '1' (right)."
          //     },
          //     {
          //       id : "layout[title][pad][r]",
          //       title : "Padding Right",	
          //       type : "number",
          //       min : 0,
          //       max : 1000,
          //       step : 1,
          //       value : this.options().title.pad.r,
          //       disabled: ! this.options().title.text  ? true : false,
          //       hint: "Sets the y position from '0' (bottom) to '1' (top)."
          //     },
          //   ],
          // },
          // {
          //   cssClasses : ["field-group", "fifty-fifty"],
          //   inputFields: [
          //     {
          //       id : "layout[title][pad][t]",
          //       title : "Padding Top",	
          //       type : "number",
          //       min : 0,
          //       max : 1000,
          //       step : 1,
          //       value : this.options().title.pad.t,
          //       disabled: ! this.options().title.text  ? true : false,
          //       hint: "Sets the x position from '0' (left) to '1' (right)."
          //     },
          //     {
          //       id : "layout[title][pad][b]",
          //       title : "Padding Bottom",	
          //       type : "number",
          //       min : 0,
          //       max : 1000,
          //       step : 1,
          //       value : this.options().title.pad.b,
          //       disabled: ! this.options().title.text  ? true : false,
          //       hint: "Sets the y position from '0' (bottom) to '1' (top)."
          //     },
          //   ],
          // }
        ]  
      },
     
    }
    
  }

}

export default Title


