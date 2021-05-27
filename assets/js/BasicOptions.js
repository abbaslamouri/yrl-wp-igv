import { fontFamily } from "./utilities"

class BasicOPtions {

  constructor( inputOptions, iwpgvObj ) {

    this.inputOptions = inputOptions
    this.prefix = iwpgvObj.prefix
   
  }

  options() {

    return {
      
      responsive : ( this.inputOptions.responsive  === undefined ) ? true : this.inputOptions.responsive,
      width : ( this.inputOptions.width === undefined ) ? null : this.inputOptions.width,
      height : ( this.inputOptions.height === undefined ) ? 500 : this.inputOptions.height,
      font : {
        family : (  this.inputOptions.font === undefined || this.inputOptions.font.family === undefined ) ? Object.keys(fontFamily())[13] : this.inputOptions.font.family,
        size : (  this.inputOptions.font === undefined || this.inputOptions.font.size === undefined ) ? 20 : this.inputOptions.font.size,
        color : (  this.inputOptions.font === undefined || this.inputOptions.font.color === undefined ) ? "#000a12" : this.inputOptions.font.color,
      },
      margin: {
        l : ( this.inputOptions.margin === undefined || this.inputOptions.margin.l === undefined ) ? 100 : this.inputOptions.margin.l,
        r : ( this.inputOptions.margin === undefined || this.inputOptions.margin.r === undefined ) ? 100 : this.inputOptions.margin.r,
        t : ( this.inputOptions.margin === undefined || this.inputOptions.margin.t === undefined ) ? 110 : this.inputOptions.margin.t,
        b : ( this.inputOptions.margin === undefined || this.inputOptions.margin.b === undefined ) ? 150 : this.inputOptions.margin.b,
        pad: ( this.inputOptions.margin === undefined || this.inputOptions.margin.pad === undefined ) ? 20 : this.inputOptions.margin.pad,
        autoexpand: ( this.inputOptions.margin === undefined || this.inputOptions.margin.autoexpand === undefined ) ? true : this.inputOptions.margin.autoexpand,
      },
      paper_bgcolor : ( this.inputOptions.paper_bgcolor === undefined ) ? "#b0bec5" :  this.inputOptions.paper_bgcolor,
      plot_bgcolor : ( this.inputOptions.plot_bgcolor === undefined ) ? "#eeeeee" : this.inputOptions.plot_bgcolor,
      autosize : ( this.inputOptions.autosize === undefined ) ? true : this.inputOptions.autosize,
    }

  }


  inputFields() {

    return {

      field_01 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[width]", 
            title : "Plot Width", 
            type : "number",
            min : 10,
            max : 2000,
            step : 10,
            value : this.options().width,
            hint : "Sets the plot's width (in px)."
          },
          {
            id : "basicOptions[height]", 
            title : "Plot Height", 
            type : "number",
            min : 10,
            max : 2000,
            step : 10,
            value : this.options().height,
            hint : "Sets the plot's height (in px)."
          },
        ]
      },
      field_02 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[font][family]",
            title : "Font Family",	
            type : "select",
            options : this.fontFamily,
            value : this.options().font.family,
            hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
          },
          {
            id : "basicOptions[font][size]", 
            title : "Font Size", 
            type : "number",
            min : 1,
            max : 100,
            step : 0.5,
            value : this.options().font.size,
            hint : "number greater than or equal to 1"
          },
        ]
      },
      field_03 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[font][color]",
            title : "Font Color",
            type : "color", 
            value : this.options().font.color,
          },
          {
            id : "basicOptions[responsive]",
            title : "Responsive ?",
            type : "checkbox", 
            value : this.options().responsive,
            hint: ""
          },
        ]
      },
      field_04 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[paper_bgcolor]",
            title : "Paper Color",
            type : "color", 
            value : this.options().paper_bgcolor,
            hint : "Sets the plot's height (in px)."
          },
          {
            id : "basicOptions[plot_bgcolor]",
            title : "Background Color",
            type : "color", 
            value : this.options().plot_bgcolor,
            hint : "Sets the plot's height (in px)."
          },
        ]
      },
      field_05 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[margin][pad]", 
            title : "Chart Padding", 
            type : "number",
            min : 0,
            max : 2000,
            step : 1,
            value : this.options().margin.pad,
            hint : "Sets the amount of padding (in px) between the plotting area and the axis lines"
          },
        ]
      },
      field_06 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[margin][l]", 
            title : "Left Margin", 
            type : "number",
            min : 0,
            max : 2000,
            step : 1,
            value : this.options().margin.l,
            hint : "Sets the left margin (in px)."
          },
          {
            id : "basicOptions[margin][r]", 
            title : "Right Margin", 
            type : "number",
            min : 0,
            max : 2000,
            step : 1,
            value : this.options().margin.r,
            hint : "Sets the right margin (in px)."
          },
        ]
      },
      field_07 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[margin][t]", 
            title : "Top Margin", 
            type : "number",
            min : 0,
            max : 2000,
            step : 1,
            value : this.options().margin.t,
            hint : "Sets the topmargin (in px)."
          },
          {
            id : "basicOptions[margin][b]", 
            title : "Bottom Margin", 
            type : "number",
            min : 0,
            max : 2000,
            step : 10,
            value : this.options().margin.b,
            hint : "Sets the bottom margin (in px)."
          },
        ]
      },
      field_08 : {
        cssClass : "field-group fifty-fifty",
        inputs: [
          {
            id : "basicOptions[margin][autoexpand]", 
            title : "Margin Auto Expand", 
            type : "checkbox",
            value : this.options().margin.autoexpand,
            hint : "Turns on/off margin expansion computations. Legends, colorbars, updatemenus, sliders, axis rangeselector and rangeslider are allowed to push the margins by defaults."
          },
          {
            id : "basicOptions[autosize]", 
            title : "Auto Size on Relayout", 
            type : "checkbox",
            value : this.options().autosize,
            hint : "Determines whether or not a layout width or height that has been left undefined by the user is initialized on each relayout. Note that, regardless of this attribute, an undefined layout width or height is always initialized on the first call to plot."
          },
        ]
      }      
     
    }

  }

}

export default BasicOPtions;


