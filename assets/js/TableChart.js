// import ChartDefault from "./ChartDefault"


class TableChart{

  constructor(tableConfig, iwpgvObj, spreadsheet) {

    // super(chartTypes, colors,pointShapes, lineWidth, markerSize, fontNames);

    this.tableConfig = tableConfig
    this.prefix = iwpgvObj.prefix
   
  }

  options() {

    return {

      type: "table",
      header:{
        // align : ( this.tableConfig.header  !== undefined && this.tableConfig.header.align  !== undefined ) ? this.tableConfig.header.align : "center",
      },
      cells:{
        // align : ( this.tableConfig.cells  !== undefined && this.tableConfig.cells.align  !== undefined ) ? this.tableConfig.cells.align : "left",
      },
    }

  }


  panel() {

    return {

      id: `${this.prefix}__tableConfigPanel`,
      cssClasses : ['tableConfig', 'table'],
      title : "Table Configuration",
      intro : "THis is where you set all your table configurations",
      sections : {
        general : {
          intro : "lorem udfujdfsjsdj",
          id : `${this.prefix}__tableConfigPanel__general`,
          title : "hhhhh",
          fields : [
            [
              {
                id: "tableChartConfig[header][align]",
                title: "Header Alignment",
                type: "select",
                options: {
                  left: "Left",
                  center: "Center",
                  right: "Right"
                },
                value: this.options().header.align
              },
              {
                id: "tableChartConfig[cells][align]",
                title: "Cells Alignment",
                type: "select",
                options: {
                  left: "Left",
                  center: "Center",
                  right: "Right"
                },
                value: this.options().cells.align
              },
            ],
          ]  
        }
      }
    }
    
  }
  
}

export default TableChart