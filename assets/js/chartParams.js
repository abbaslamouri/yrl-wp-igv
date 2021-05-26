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
      fileUpload : (this.params.fileUpload === undefined) ? null : this.params.fileUpload,
      fileId : (this.params.fileId === undefined) ? null :this.params.fileId,
      sheetId : (this.params.sheetId === undefined ) ? null : this.params.sheetId,
      chartType : (this.params.chartType === undefined ) ? null : this.params.chartType,
      chartId : (this.params.chartId === undefined ) ? null : this.params.chartId,
      // enableRangeSlider : ( this.params.enableChartRangeSlider ) ? true : true,
      // enableMinMaxTableChart : ( this.params.enableMinMaxTableChart  ) ? true : false,
      // enableTableChart : ( this.params.enableTableChart ) ? true : true,

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
        title : "nnnnn",
        intro : "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        fields : [
          [
            {
              id : "chartParams[mediaUploadBtn]",
              cssClasses : ['button', 'button-secondary', "btn"], 
              title : "Upload File", 
              type : "button",
              value : this.options()['mediaUploadBtn'],
            },
          ],
          [
            {
              id : "chartParams[fileUpload]",
              // cssClasses : ['hidden', "no-hint"],
              title : "Selected File", 
              readOnly: true,
              type : "text",
              value : this.options()['fileUpload'],
              // hint: "Check this box to enable Plotly Range Slider"
            }
          ],
          [
            {
              id : "chartParams[chartId]",
              // cssClasses : ['hidden', "no-hint"],
              title : "Chart ID", 
              readOnly: true,
              type : "text",
              value : this.options()['chartId'],
          },
          ],
          [
            {
              id : "chartParams[sheetId]",
              // cssClasses : ['hidden'],
              title : "Sheet",	
              type : "select",
              options : [],
              value : this.options()['sheetId'],
              hint: "Select sheet"
            },
          ],
          [
            {
              id : "chartParams[chartType]",
              // cssClasses : ['hidden'],
              title : "Chart Type",	
              type : "select",
              options : this.chartTypes,
              value : this.options()['chartType'],
              hint: "Select chart type"
            },
          ],
          [
            {
              id : "chartParams[fileId]",
              title : "",	
              type : "text",
              value : this.options()['fileId'],
            },
          ],
          [
            // {
            //   id: "chartParams[enableRangeSlider]",
            //   cssClasses : ['hidden'],
            //   title:"Enable Range Slider",
            //   type: "checkbox",
            //   value: this.options()['enableRangeSlider'],
            //   hint: "Check this box to enable Plotly Range Slider"
            // },
            // {
            //   id: "chartParams[enableMinMaxTableChart]",
            //   cssClasses : ['hidden'],
            //   title: "Enable Min/Max Table",
            //   type: "checkbox",
            //   value: this.options()['enableMinMaxTableChart'],
            //   hint: "Check this box to enable MIn/Max/Average Table.  THis option has no effect if the rangeslider visibility (found under Layout/Range Slider panel) is turned off."
            // },
            // {
            //   id: "chartParams[enableTableChart]",
            //   // cssClasses : ['hidden'],
            //   title: "Enable Table Chart",
            //   type: "checkbox",
            //   value: this.options()['enableTableChart'],
            //   hint: "bhbbkdsfjbfsdkjnsfdjnnjkf"
            // },
          ],
        ]  
      }
    }
  } 

}

export default ChartParams;