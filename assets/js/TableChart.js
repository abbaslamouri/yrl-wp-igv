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
      layout: {
        title: {
          text: ( this.tableConfig.layout !== undefined && this.tableConfig.layout.title !== undefined && this.tableConfig.layout.title.text!== undefined ) ? this.tableConfig.layout.title.text : this.panelTitle,
          font : {
            family : ( this.tableConfig.layout !== undefined && this.tableConfig.layout.title !== undefined && this.tableConfig.layout.title.font !== undefined && this.tableConfig.layout.title.font.family !== undefined ) ? this.tableConfig.layout.title.font.family : Object.keys(this.fontFamily)[13],
            size : ( this.tableConfig.layout !== undefined && this.tableConfig.layout.title !== undefined && this.tableConfig.layout.title.font !== undefined && this.tableConfig.layout.title.font.size !== undefined ) ? this.tableConfig.layout.title.font.size : 40,
            color : ( this.tableConfig.layout !== undefined && this.tableConfig.layout.title !== undefined && this.tableConfig.layout.title.font !== undefined && this.tableConfig.layout.title.font.color !== undefined ) ? this.tableConfig.layout.title.font.color : "#8D6E63",
          }
        },
      },
      firstColAlign: ( this.tableConfig.firstColAlign  !== undefined ) ? this.tableConfig.firstColAlign : "left",
      // otherColsAlign: ( this.tableConfig.otherColsAlign  !== undefined ) ? this.tableConfig.otherColsAlign : "center",
      evenRowColor: ( this.tableConfig.evenRowColor  !== undefined ) ? this.tableConfig.evenRowColor : "#CFD8DC",
      oddRowColor: ( this.tableConfig.oddRowColor  !== undefined ) ? this.tableConfig.oddRowColor : "#90A4AE",
      rounding: ( this.tableConfig.rounding  !== undefined ) ? this.tableConfig.rounding : 2,
      header:{
        align : ( this.tableConfig.header  !== undefined && this.tableConfig.header.align  !== undefined ) ? this.tableConfig.header.align : "center",
        height : ( this.tableConfig.header  !== undefined && this.tableConfig.header.height  !== undefined ) ? this.tableConfig.header.height : 10,
        line: {
          width: ( this.tableConfig.header  !== undefined && this.tableConfig.header.line  !== undefined && this.tableConfig.header.line.width  !== undefined ) ? this.tableConfig.header.line.width : 2,
          color: ( this.tableConfig.header  !== undefined && this.tableConfig.header.line  !== undefined && this.tableConfig.header.line.color  !== undefined ) ? this.tableConfig.header.line.color : "#AD1457",
        },
        fill: {
          color: ( this.tableConfig.header  !== undefined && this.tableConfig.header.fill  !== undefined && this.tableConfig.header.fill.color  !== undefined ) ? this.tableConfig.header.fill.color : "#BBDEFB",
        },
        font : {
          family : ( this.tableConfig.header !== undefined && this.tableConfig.header.font !== undefined && this.tableConfig.header.font.family !== undefined ) ? this.tableConfig.header.font.family : Object.keys(this.fontFamily)[13],
          size : ( this.tableConfig.header !== undefined && this.tableConfig.header.font !== undefined && this.tableConfig.header.font.size !== undefined ) ? this.tableConfig.header.font.size : 14,
          color : ( this.tableConfig.header !== undefined && this.tableConfig.header.font !== undefined && this.tableConfig.header.font.color !== undefined ) ? this.tableConfig.header.font.color : "FFFFFF",
        }
      },
      cells:{
        align : ( this.tableConfig.cells  !== undefined && this.tableConfig.cells.align  !== undefined ) ? this.tableConfig.cells.align : "center",
        height : ( this.tableConfig.cells  !== undefined && this.tableConfig.cells.height  !== undefined ) ? this.tableConfig.cells.height : 30,
        line: {
          width: ( this.tableConfig.cells  !== undefined && this.tableConfig.cells.line  !== undefined && this.tableConfig.cells.line.width  !== undefined ) ? this.tableConfig.cells.line.width : 1,
          color: ( this.tableConfig.cells  !== undefined && this.tableConfig.cells.line  !== undefined && this.tableConfig.cells.line.color  !== undefined ) ? this.tableConfig.cells.line.color : "#AD1457",
        },
        fill: {
          color: ( this.tableConfig.cells  !== undefined && this.tableConfig.cells.fill  !== undefined && this.tableConfig.cells.fill.color  !== undefined ) ? this.tableConfig.cells.fill.color : "#FFF8E1",
        },
        font : {
          family : ( this.tableConfig.cells !== undefined && this.tableConfig.cells.font !== undefined && this.tableConfig.cells.font.family !== undefined ) ? this.tableConfig.cells.font.family : Object.keys(this.fontFamily)[13],
          size : ( this.tableConfig.cells !== undefined && this.tableConfig.cells.font !== undefined && this.tableConfig.cells.font.size !== undefined ) ? this.tableConfig.cells.font.size : 10,
          color : ( this.tableConfig.cells !== undefined && this.tableConfig.cells.font !== undefined && this.tableConfig.cells.font.color !== undefined ) ? this.tableConfig.cells.font.color : "000000",
        }
      }
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
          intro : "",
          id : `${this.prefix}__${this.panelId}Panel__general`,
          title : "",
          fields : [
            [
              {
                id: `${this.panelId}[header][height]`, 
                title : "Header Row Height", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().header.height,
                hint : "Sets The height of cells."
              },
              {
                id: `${this.panelId}[header][align]`,
                title: "Header Alignment",
                type: "select",
                options: {
                  left: "Left",
                  center: "Center",
                  right: "Right"
                },
                value: this.options().header.align
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
            ],
            [
              {
                id: `${this.panelId}[evenRowColor]`,
                title: "Even Row Color",
                type: "color",
                value: this.options().evenRowColor,
                hint:""
              },
              {
                id: `${this.panelId}[oddRowColor]`,
                title: "Odd Row Color",
                type: "color",
                value: this.options().oddRowColor,
                hint:""
              },
            ],
            [
              {
                id: `${this.panelId}[cells][align]`,
                title: "Cells Alignment",
                type: "select",
                options: {
                  left: "Left",
                  center: "Center",
                  right: "Right"
                },
                value: this.options().cells.align
              },
              {
                id: `${this.panelId}[cells][line][width]`, 
                title : "Cells Line Width", 
                type : "number",
                min : 0,
                max : 2000,
                step : 1,
                value : this.options().cells.line.width,
                hint : ""
              },
            ],
          ]  
        }
      // }
    }
    
  }
  
}

export default TableChart