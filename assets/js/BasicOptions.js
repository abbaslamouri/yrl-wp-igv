import { fontFamily } from "./utilities"

class BasicOPtions {

  constructor( layout ) {

    this.layout = layout
    // this.prefix = prefix
   
  }

  options() {

    return {
      
      responsive : ( this.layout.responsive  === undefined ) ? true : this.layout.responsive,
      staticPlot : ( this.layout.staticPlot  === undefined ) ? false : this.layout.staticPlot,
      width : ( this.layout.width === undefined ) ? null : this.layout.width,
      height : ( this.layout.height === undefined ) ? 300 : this.layout.height,
      font : {
        family : (  this.layout.font === undefined || this.layout.font.family === undefined ) ? Object.keys(fontFamily())[13] : this.layout.font.family,
        size : (  this.layout.font === undefined || this.layout.font.size === undefined ) ? 14 : this.layout.font.size,
        color : (  this.layout.font === undefined || this.layout.font.color === undefined ) ? "#000a12" : this.layout.font.color,
      },
      margin: {
        l : ( this.layout.margin === undefined || this.layout.margin.l === undefined ) ? 80 : this.layout.margin.l,
        r : ( this.layout.margin === undefined || this.layout.margin.r === undefined ) ? 80 : this.layout.margin.r,
        t : ( this.layout.margin === undefined || this.layout.margin.t === undefined ) ? 100 : this.layout.margin.t,
        b : ( this.layout.margin === undefined || this.layout.margin.b === undefined ) ? 80 : this.layout.margin.b,
        pad: ( this.layout.margin === undefined || this.layout.margin.pad === undefined ) ? 0 : this.layout.margin.pad,
        autoexpand: ( this.layout.margin === undefined || this.layout.margin.autoexpand === undefined ) ? true : this.layout.margin.autoexpand,
      },
      paper_bgcolor : ( this.layout.paper_bgcolor === undefined ) ? "#b0bec5" :  this.layout.paper_bgcolor,
      plot_bgcolor : ( this.layout.plot_bgcolor === undefined ) ? "#eeeeee" : this.layout.plot_bgcolor,
      autosize : ( this.layout.autosize === undefined ) ? null : this.layout.autosize,
    }

  }


  sections() {

    return {
      
      basicOptions: {
        intro : "Here you can modify the bottom x-axis general",
        title : "Basic Options",
        fieldGroups : [
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[width]", 
                title : "Plot Width", 
                type : "number",
                min : 10,
                max : 2000,
                step : 10,
                value : this.options().width,
                hint : "Sets the plot's width (in px)."
              },
              {
                id : "layout[height]", 
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
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[font][family]",
                title : "Font Family",	
                type : "select",
                options : fontFamily(),
                value : this.options().font.family,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : "layout[font][size]", 
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
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[font][color]",
                title : "Font Color",
                type : "color", 
                value : this.options().font.color,
              },
              {
                id : "layout[responsive]",
                title : "Responsive",
                type : "checkbox", 
                value : this.options().responsive,
                hint: ""
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[staticPlot]",
                title : "Static PLot ?",
                type : "checkbox", 
                value : this.options().staticPlot,
                hint: ""
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[paper_bgcolor]",
                title : "Paper Color",
                type : "color", 
                value : this.options().paper_bgcolor,
                hint : "Sets the plot's height (in px)."
              },
              {
                id : "layout[plot_bgcolor]",
                title : "Background Color",
                type : "color", 
                value : this.options().plot_bgcolor,
                hint : "Sets the plot's height (in px)."
              },
            ]
          },
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[margin][pad]", 
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
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[margin][l]", 
                title : "Left Margin", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().margin.l,
                hint : "Sets the left margin (in px)."
              },
              {
                id : "layout[margin][r]", 
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
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[margin][t]", 
                title : "Top Margin", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().margin.t,
                hint : "Sets the topmargin (in px)."
              },
              {
                id : "layout[margin][b]", 
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
          {
            cssClasses : ["field-group", "fifty-fifty"],
            inputFields: [
              {
                id : "layout[margin][autoexpand]", 
                title : "Margin Auto Expand", 
                type : "checkbox",
                value : this.options().margin.autoexpand,
                hint : "Turns on/off margin expansion computations. Legends, colorbars, updatemenus, sliders, axis rangeselector and rangeslider are allowed to push the margins by defaults."
              },
              {
                id : "layout[autosize]", 
                title : "Auto Size on Relayout", 
                type : "checkbox",
                value : this.options().autosize,
                hint : "Determines whether or not a layout width or height that has been left undefined by the user is initialized on each relayout. Note that, regardless of this attribute, an undefined layout width or height is always initialized on the first call to plot."
              },
            ]
          }
        ]
      },
     
    }

  }

}

export default BasicOPtions;


