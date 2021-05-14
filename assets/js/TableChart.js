// import ChartDefault from "./ChartDefault"

import ChartDefault from "./ChartDefault";


class TableChart extends ChartDefault{

  constructor(tableConfig, iwpgvObj, panelId, panelTitle, fontFamily) {

    super(fontFamily);

    this.tableConfig = tableConfig
    this.prefix = iwpgvObj.prefix
    this.panelId = panelId
    this.panelTitle = panelTitle
   
  }

  options() {

    return {

      type: "table",
      rounding: ( this.tableConfig.rounding  === undefined ) ? 3 : this.tableConfig.rounding,
      firstColAlign: ( this.tableConfig.firstColAlign  === undefined ) ? "center" : this.tableConfig.firstColAlign,
      evenRowColor: ( this.tableConfig.evenRowColor  === undefined ) ? "#b0bec5" : this.tableConfig.evenRowColor,
      oddRowColor: ( this.tableConfig.oddRowColor  === undefined ) ? "#e2f1f8" : this.tableConfig.oddRowColor,
      header:{
        height : ( this.tableConfig.header  === undefined || this.tableConfig.header.height  === undefined ) ? 30 : this.tableConfig.header.height,
        align : ( this.tableConfig.header  === undefined || this.tableConfig.header.align  === undefined ) ? "center" : this.tableConfig.header.align,
        line: {
          width: ( this.tableConfig.header  === undefined || this.tableConfig.header.line  === undefined || this.tableConfig.header.line.width  === undefined ) ? 1 : this.tableConfig.header.line.width,
          color: ( this.tableConfig.header  === undefined || this.tableConfig.header.line  === undefined || this.tableConfig.header.line.color  === undefined ) ? "#263238" : this.tableConfig.header.line.color,
        },
        fill: {
          color: ( this.tableConfig.header  === undefined || this.tableConfig.header.fill  === undefined || this.tableConfig.header.fill.color  === undefined ) ?  "#000a12" : this.tableConfig.header.fill.color,
        },
        font : {
          family : ( this.tableConfig.header === undefined || this.tableConfig.header.font === undefined || this.tableConfig.header.font.family === undefined ) ? Object.keys(this.fontFamily)[13] : this.tableConfig.header.font.family,
          size : ( this.tableConfig.header === undefined || this.tableConfig.header.font === undefined || this.tableConfig.header.font.size === undefined ) ? 14 : this.tableConfig.header.font.size,
          color : ( this.tableConfig.header === undefined || this.tableConfig.header.font === undefined || this.tableConfig.header.font.color === undefined ) ? "#eeeeee" : this.tableConfig.header.font.color,
        }
      },
      cells:{
        height : ( this.tableConfig.cells  === undefined || this.tableConfig.cells.height  === undefined ) ? 30 : this.tableConfig.cells.height,align : ( this.tableConfig.cells  === undefined || this.tableConfig.cells.align  === undefined ) ? "center" : this.tableConfig.cells.align,
        line: {
          width: ( this.tableConfig.cells  === undefined || this.tableConfig.cells.line  === undefined || this.tableConfig.cells.line.width  === undefined ) ? 1 : this.tableConfig.cells.line.width,
          color: ( this.tableConfig.cells  === undefined || this.tableConfig.cells.line  === undefined || this.tableConfig.cells.line.color  === undefined ) ?  "#263238" : this.tableConfig.cells.line.color,
        },
        fill: {
          color: ( this.tableConfig.cells  === undefined || this.tableConfig.cells.fill  === undefined || this.tableConfig.cells.fill.color  === undefined ) ? null : this.tableConfig.cells.fill.color,
        },
        font : {
          family : ( this.tableConfig.cells === undefined || this.tableConfig.cells.font === undefined || this.tableConfig.cells.font.family === undefined ) ? Object.keys(this.fontFamily)[13] : this.tableConfig.cells.font.family,
          size : ( this.tableConfig.cells === undefined || this.tableConfig.cells.font === undefined || this.tableConfig.cells.font.size === undefined ) ? 12 : this.tableConfig.cells.font.size,
          color : ( this.tableConfig.cells === undefined || this.tableConfig.cells.font === undefined || this.tableConfig.cells.font.color === undefined ) ?  "#000a12" : this.tableConfig.cells.font.color,
        }
      },
      layout: {
        height: ( this.tableConfig.layout === undefined || this.tableConfig.layout.height === undefined ) ? 160 : this.tableConfig.layout.height,
        autosize: ( this.tableConfig.layout === undefined || this.tableConfig.layout.autosize === undefined ) ? false : this.tableConfig.layout.autosize,
        title: {
          text: ( this.tableConfig.layout === undefined || this.tableConfig.layout.title === undefined || this.tableConfig.layout.title.text=== undefined ) ? this.panelTitle : this.tableConfig.layout.title.text,
          font : {
            family : ( this.tableConfig.layout === undefined || this.tableConfig.layout.title === undefined || this.tableConfig.layout.title.font === undefined || this.tableConfig.layout.title.font.family === undefined ) ? Object.keys(this.fontFamily)[13] : this.tableConfig.layout.title.font.family,
            size : ( this.tableConfig.layout === undefined || this.tableConfig.layout.title === undefined || this.tableConfig.layout.title.font === undefined || this.tableConfig.layout.title.font.size === undefined ) ? 16 : this.tableConfig.layout.title.font.size,
            color : ( this.tableConfig.layout === undefined || this.tableConfig.layout.title === undefined || this.tableConfig.layout.title.font === undefined || this.tableConfig.layout.title.font.color === undefined ) ? "#8D6E63" : this.tableConfig.layout.title.font.color,
          }
        },
        margin: {
          l : ( this.tableConfig.layout === undefined || this.tableConfig.layout.margin === undefined || this.tableConfig.layout.margin.l === undefined  ) ? 0 : this.tableConfig.layout.margin.l,
          r : ( this.tableConfig.layout === undefined || this.tableConfig.layout.margin === undefined || this.tableConfig.layout.margin.r === undefined  ) ? 0 : this.tableConfig.layout.margin.r,
          t : ( this.tableConfig.layout === undefined || this.tableConfig.layout.margin === undefined || this.tableConfig.layout.margin.t=== undefined  ) ? 0 : this.tableConfig.layout.margin.t,
          b : ( this.tableConfig.layout === undefined || this.tableConfig.layout.margin === undefined || this.tableConfig.layout.margin.b === undefined  ) ? 0 : this.tableConfig.layout.margin.b,
          pad: ( this.tableConfig.layout === undefined || this.tableConfig.layout.margin === undefined || this.tableConfig.layout.margin.pad === undefined  ) ? 0 : this.tableConfig.layout.margin.pad,
          autoexpand: ( this.tableConfig.layout === undefined || this.tableConfig.layout.margin === undefined || this.tableConfig.layout.margin.autoexpand === undefined  ) ? true : this.tableConfig.layout.margin.autoexpand,
        },
      },
     
     
      
    }

  }


  sections() {

    return {

      // id: `${this.prefix}__${this.panelId}Panel`,
      // cssClasses : ['tableConfig', 'table'],
      // title : `${this.panelTitle}`,
      // intro : "THis is where you set all your table configurations",
      // sections : {
        general : {
          intro : "Set general here",
          id : `${this.prefix}__${this.panelId}Panel__general`,
          title : "General",
          fields : [
            [
              {
                id: `${this.panelId}[type]`,
                cssClasses: ["hidden"],
                title : "hidden", 
                type : "text",
                value : this.options().type,
                hint : "Chart type"
              },
            ],
            [
              {
                id: `${this.panelId}[layout][height]`, 
                title : "Height", 
                type : "number",
                min : 100,
                max : 2000,
                step : 1,
                value : this.options().layout.height,
                hint : ""
              },
              {
                id: `${this.panelId}[layout][autosize]`,
                title: "Auto Size",
                type: "checkbox",
                value: this.options().layout.autosize,
                hint: "Determines whether or not a layout width or height that has been left undefined by the user is initialized on each relayout. Note that, regardless of this attribute, an undefined layout width or height is always initialized on the first call to plot."
              },
            ],
            [
              {
                id: `${this.panelId}[firstColAlign]`,
                title: "First Column Alignment",
                type: "select",
                options: {
                  left: "Left",
                  center: "Center",
                  right: "Right"
                },
                value: this.options().firstColAlign
              },
              {
                id: `${this.panelId}[rounding]`, 
                title : "Number Rounding", 
                type : "number",
                min : 0,
                max : 10,
                step : 1,
                value : this.options().rounding,
                hint : "Sets the number of decimal places for the table data"
              },
            ],
            [
              {
                id: `${this.panelId}[evenRowColor]`,
                title: "Even Row Color",
                type: "color",
                value: this.options().evenRowColor,
                hint:"Odd rows color"
              },
              {
                id: `${this.panelId}[oddRowColor]`,
                title: "Odd Row Color",
                type: "color",
                value: this.options().oddRowColor,
                hint:"Even rows color"
              },
            ],
            [
              {
                id : `${this.panelId}[layout][title][text]`, 
                title : "Table Title", 
                type : "text",
                value : this.options().layout.title.text,
                hint : ""
              },
              {
                id : `${this.panelId}[layout][title][font][size]`, 
                title : "Title Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().layout.title.font.size,
                hint : "number greater than or equal to 1"
              },
            ],
            [
              {
                id : `${this.panelId}[layout][title][font][family]`,
                title : "Title Font",	
                type : "select",
                options : this.fontFamily,
                value : this.options().layout.title.font.family,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : `${this.panelId}[layout][title][font][color]`,
                title : "Title Font Color",
                type : "color", 
                value : this.options().layout.title.font.color,
              },
            ],
            [
              {
                id : `${this.panelId}[layout][margin][l]`, 
                title : "Left Margin", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().layout.margin.l,
                hint : "Sets the left margin (in px)."
              },
              {
                id : `${this.panelId}[layout][margin][r]`, 
                title : "Right Margin", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().layout.margin.r,
                hint : "Sets the right margin (in px)."
              },
            ],
            [
              {
                id : `${this.panelId}[layout][margin][t]`, 
                title : "Top Margin", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().layout.margin.t,
                hint : "Sets the topmargin (in px)."
              },
              {
                id : `${this.panelId}[layout][margin][b]`, 
                title : "Bottom Margin", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().layout.margin.b,
                hint : "Sets the bottom margin (in px)."
              },
            ],
            [
              {
                id : `${this.panelId}[layout][margin][pad]`, 
                title : "Chart Padding", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().layout.margin.pad,
                hint : "Sets the amount of padding (in px) between the plotting area and the axis lines"
              },
              {
                id : `${this.panelId}[layout][margin][autoexpand]`, 
                title : "Margin Auto Expand", 
                type : "checkbox",
                value : this.options().layout.margin.autoexpand,
                hint : "Turns on/off margin expansion computations. Legends, colorbars, updatemenus, sliders, axis rangeselector and rangeslider are allowed to push the margins by defaults."
              },
            ],
          ]  
        },
        header : {
          intro : "Set header here",
          id : `${this.prefix}__${this.panelId}Panel__general`,
          title : "Header",
          fields : [
            [
              {
                id: `${this.panelId}[header][height]`, 
                title : "Row Height", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().header.height,
                hint : "Sets The height of cells."
              },
              {
                id: `${this.panelId}[header][align]`,
                title: "Alignment",
                type: "select",
                options: {
                  left: "Left",
                  center: "Center",
                  right: "Right"
                },
                value: this.options().header.align,
                hint: "Sets the horizontal alignment of the `text` within the box. Has an effect only if `text` spans two or more lines (i.e. `text` contains one or more <br> HTML tags) or if an explicit width is set to override the text width."
              },
            ],
            [
              {
                id: `${this.panelId}[header][line][width]`, 
                title : "Border Line Width", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().header.line.width,
                hint : "Sets The width of the header cells lines"
              },
              {
                id: `${this.panelId}[header][line][color]`,
                title: "Border Line Color",
                type: "color",
                value: this.options().header.line.color,
                hint: "Sets The color of the header cells lines"
              },
            ],
            [
              {
                id: `${this.panelId}[header][fill][color]`,
                title: "Fill Color",
                type: "color",
                value: this.options().header.fill.color,
                hint: "Sets The color of the header cells fill color"
              },
              {
                id : `${this.panelId}[header][font][family]`,
                title : "Font Family",	
                type : "select",
                options : this.fontFamily,
                value : this.options().header.font.family,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
            ],
            [
              {
                id : `${this.panelId}[header][font][size]`, 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().header.font.size,
                hint : "number greater than or equal to 1"
              },
              {
                id : `${this.panelId}[header][font][color]`,
                title : "Font Color",
                type : "color", 
                value : this.options().header.font.color,
              },
            ],
          ]  
        },
        cells : {
          intro : "Set cells here",
          id : `${this.prefix}__${this.panelId}Panel__general`,
          title : "Cells",
          fields : [
            [
              {
                id: `${this.panelId}[cells][height]`, 
                title : "Row Height", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().cells.height,
                hint : "Sets The height of cells."
              },
              {
                id: `${this.panelId}[cells][align]`,
                title: "Alignment",
                type: "select",
                options: {
                  left: "Left",
                  center: "Center",
                  right: "Right"
                },
                value: this.options().cells.align,
                hint: "Sets the horizontal alignment of the `text` within the box. Has an effect only if `text` spans two or more lines (i.e. `text` contains one or more <br> HTML tags) or if an explicit width is set to override the text width."
              },
            ],
            [
              {
                id: `${this.panelId}[cells][line][width]`, 
                title : "Border Line Width", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().cells.line.width,
                hint : "Sets The width of the cell cells lines"
              },
              {
                id: `${this.panelId}[cells][line][color]`,
                title: "Border Line Color",
                type: "color",
                value: this.options().cells.line.color,
                hint: "Sets The color of the cell cells lines"
              },
            ],
            [
              {
                id: `${this.panelId}[cells][fill][color]`,
                // cssClasses: ["hidden"],
                title: "Fill Color",
                type: "color",
                value: this.options().cells.fill.color,
                hint: "Sets The color of the cell cells fill color"
              },
              {
                id : `${this.panelId}[cells][font][family]`,
                title : "Font Family",	
                type : "select",
                options : this.fontFamily,
                value : this.options().cells.font.family,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
            ],
            [
              {
                id : `${this.panelId}[cells][font][size]`, 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().cells.font.size,
                hint : "number greater than or equal to 1"
              },
              {
                id : `${this.panelId}[cells][font][color]`,
                title : "Font Color",
                type : "color", 
                value : this.options().cells.font.color,
              },
            ],
          ] 
        }
      // }
    }
    
  }
  
}

export default TableChart