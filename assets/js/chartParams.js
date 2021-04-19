import ChartDefault from "./ChartDefault"


class ChartParams {

  constructor(params, iwpgvObj) {

    // super(chartTypes, colors,pointShapes, lineWidth, markerSize, fontNames);

    this.params = params
    this.prefix = iwpgvObj.prefix

    this.chartTypes = {						// All chart types supported by this plugin
      "" 						: "Select Chart Type",
			"LineChart" 	: "Line Chart",
			"ScatterChart" : "Scatter Chart",
			"BarChart" 		: "Bar Chart",
			"ColumnChart" : "Column Chart",
			"PieChart" 		: "Pie Chart",
    }
   
  }

  options() {

    return {

      "mediaUploadBtn" : 'Upload New File',
      "fileUpload" : ( typeof ( this.params.fileUpload ) !== "undefined") ? this.params.fileUpload : null,
      "sheetId" : ( typeof ( this.params.sheetId ) !== "undefined" ) ? this.params.sheetId : null,
      "chartType" : ( typeof ( this.params.chartType ) !== "undefined" ) ? this.params.chartType : null,
      "chartId" : ( typeof ( this.params.chartId ) !== "undefined" ) ? this.params.chartId : null,
      'enableChartRangeSlider' : ( typeof ( this.params.enableChartRangeSlider ) !== "undefined" ) ? true : false,
      'enableMinMaxTableChart' : ( typeof ( this.params.enableMinMaxTableChart ) !== "undefined" ) ? true : false,
      'enableTableChart' : ( typeof ( this.params.enableTableChart ) !== "undefined" ) ? true : false,
      "theme" : ( typeof ( this.paramstheme ) !== "undefined" ) ? this.paramstheme : null

    }

  }


  panel() {

      return {

        "id" : `${this.prefix}__chartParamsPanel`,
        'cssClasses' : ['chartParams', 'openOnLoad'],
        "title" : "Chart Params",
        'intro' : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
        "sections" : {
          "general" : {
            'intro' : "jkljkljkljklkjlkjljkljkljkjkljklkjl",
            "id" : `${this.prefix}__chartParamsSubpanel-general`,
            "title" : "",
            "fields" : [
              [
                {
                  "id" : "chartParams[mediaUploadBtn]",
                  'cssClasses' : ['button', 'button-secondary'], 
                  "title" : "Upload File", 
                  "type" : "button",
                  "value" : this.options()['mediaUploadBtn'],
                  'hint' : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
                },
                
                {
                  "id" : "chartParams[fileUpload]",
                  "title" : "Upload", 
                  "type" : "text",
                  "value" : this.options()['fileUpload'],
                  'hint' : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
                }
              ],
              [
                {
                  "id" : "chartParams[sheetId]",
                  'cssClasses' : ['hidden'],
                  "title" : "Sheet",	
                  "type" : "select",
                  "options" : [],
                  "value" : this.options()['sheetId'],
                },
              ],
              [
                {
                  "id" : "chartParams[chartType]",
                  'cssClasses' : ['hidden'],
                  "title" : "Chart Type",	
                  "type" : "select",
                  "options" : this.chartTypes,
                  "value" : this.options()['chartType'],
                },
              ],
              [
                {
                  "id" : "chartParams[chartId]",
                  "title" : "",	
                  "type" : "hidden",
                  "value" : this.options()['chartId'],
                },
              ],
              [
                {
                  "id": "chartParams[enableChartRangeSlider]",
                  'cssClasses' : ['hidden'],
                  "title":"Enable Range Slider",
                  "type": "checkbox",
                  "value": this.options()['enableChartRangeSlider'],
                },
                {
                  "id": "chartParams[enableMinMaxTableChart]",
                  'cssClasses' : ['hidden'],
                  "title": "Enable Min/Max Table",
                  "type": "checkbox",
                  "value": this.options()['enableMinMaxTableChart'],
                },
                {
                  "id": "chartParams[enableTableChart]",
                  'cssClasses' : ['hidden'],
                  "title": "Enable Table Chart",
                  "type": "checkbox",
                  "value": this.options()['enableTableChart'],
                },
              ],
            ]  
          }
        }
      }
    }
  

}

export default ChartParams;