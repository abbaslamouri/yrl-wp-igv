class ChartParams {

  constructor(params, iwpgvObj) {

    // super(chartTypes, colors,pointShapes, lineWidth, markerSize, fontNames);

    this.params = params
    this.prefix = iwpgvObj.prefix

    this.chartTypes = {						// All chart types supported by this plugin
      "": "Select Chart Type",
			LineChart: "Line Chart",
			ScatterChart: "Scatter Chart",
			BarChart: "Bar Chart",
			ColumnChart: "Column Chart",
			PieChart: "Pie Chart",
    }
   
  }

  options() {

    return {

      mediaUploadBtn : 'Upload New File',
      fileUpload : (this.params.fileUpload !== undefined) ? this.params.fileUpload : null,
      fileId : (this.params.fileId !== undefined) ? this.params.fileId : null,
      sheetId : (this.params.sheetId !== undefined ) ? this.params.sheetId : null,
      chartType : (this.params.chartType !== undefined ) ? this.params.chartType : null,
      chartId : (this.params.chartId !== undefined ) ? this.params.chartId : null,
      enableRangeSlider : ( this.params.enableChartRangeSlider ) ? true : true,
      enableMinMaxTableChart : ( this.params.enableMinMaxTableChart  ) ? true : true,
      enableTableChart : ( this.params.enableTableChart ) ? true : true,

    }

  }


  sections() {

    return {

      // id : `${this.prefix}__chartParamsPanel`,
      // cssClasses : ['chartParams', 'openOnLoad'],
      // title : "Parameters",
      // intro : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
      general : {
        id : `${this.prefix}__chartParamsSubpanel-general`,
        cssClasses:["chartParams", "subPanel"],
        title : "",
        intro : "",
        fields : [
          [
            {
              id : "chartParams[mediaUploadBtn]",
              cssClasses : ['button', 'button-secondary'], 
              // title : "Upload File", 
              type : "button",
              value : this.options()['mediaUploadBtn'],
            },
          ],
          [
            {
              id : "chartParams[fileUpload]",
              title : "Selected File", 
              readOnly: true,
              type : "text",
              value : this.options()['fileUpload'],
            }
          ],
          [
            {
              id : "chartParams[chartId]",
              title : "Chart ID", 
              // readOnly: true,
              type : "text",
              value : this.options()['chartId'],
              hint: "bhbbkdsfjbfsdkjnsfdjnnjkf"
            },
          ],
          [
            {
              id : "chartParams[sheetId]",
              cssClasses : ['hidden'],
              title : "Sheet",	
              type : "select",
              options : [],
              value : this.options()['sheetId'],
              hint: "bhbbkdsfjbfsdkjnsfdjnnjkf"
            },
          ],
          [
            {
              id : "chartParams[chartType]",
              cssClasses : ['hidden'],
              title : "Chart Type",	
              type : "select",
              options : this.chartTypes,
              value : this.options()['chartType'],
              hint: "bhbbkdsfjbfsdkjnsfdjnnjkf"
            },
          ],
          [
            {
              id : "chartParams[fileId]",
              title : "",	
              type : "hidden",
              value : this.options()['fileId'],
            },
          ],
          [
            {
              id: "chartParams[enableRangeSlider]",
              // cssClasses : ['hidden'],
              title:"Enable Range Slider",
              type: "checkbox",
              value: this.options()['enableRangeSlider'],
              hint: "bhbbkdsfjbfsdkjnsfdjnnjkf"
            },
            {
              id: "chartParams[enableMinMaxTableChart]",
              // cssClasses : ['hidden'],
              title: "Enable Min/Max Table",
              type: "checkbox",
              value: this.options()['enableMinMaxTableChart'],
              hint: "bhbbkdsfjbfsdkjnsfdjnnjkf"
            },
            {
              id: "chartParams[enableTableChart]",
              // cssClasses : ['hidden'],
              title: "Enable Table Chart",
              type: "checkbox",
              value: this.options()['enableTableChart'],
              hint: "bhbbkdsfjbfsdkjnsfdjnnjkf"
            },
          ],
        ]  
      }
    }
  } 

}

export default ChartParams;