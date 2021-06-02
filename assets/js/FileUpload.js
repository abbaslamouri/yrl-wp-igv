import { chartTypes } from "./utilities"

class FileUpload {

  constructor(inputOPtions, iwpgvObj) {

    this.inputOPtions = inputOPtions
    this.prefix = iwpgvObj.prefix
   
  }

  options() {

    return {

      mediaUploadBtn : 'Upload New File',
      fileName : (this.inputOPtions.fileName === undefined) ? null : this.inputOPtions.fileName,
      fileId : (this.inputOPtions.fileId === undefined) ? null :this.inputOPtions.fileId,
      sheetId : (this.inputOPtions.sheetId === undefined ) ? null : this.inputOPtions.sheetId,
      chartType : (this.inputOPtions.chartType === undefined ) ? null : this.inputOPtions.chartType,
      chartId : (this.inputOPtions.chartId === undefined ) ? null : this.params.chartId,

    }

  }


  // sections() {

  //   return {

  //     // id : `${this.prefix}__chartParamsPanel`,
  //     // cssClasses : ['chartParams', 'openOnLoad'],
  //     // title : "Parameters",
  //     // intro : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
  //     basicOptions : {
  //       // id : `${this.prefix}__chartParamsSubpanel-general`,
  //       // cssClasses:["chartParams", "subPanel"],
  //       title : "nnnnn",
  //       intro : "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
  //       fieldGroups : [
  //         {
  //           cssClasses : ["field-group", "fifty-fifty"],
  //           inputFields: [
  //             {
  //               id : "fileUpload[mediaUploadBtn]",
  //               // cssClasses : ['button', 'button-secondary', "btn"], 
  //               title : "Upload File", 
  //               type : "button",
  //               value : this.options()['mediaUploadBtn'],
  //             },
  //           ],
  //         },
  //         {
  //           cssClasses : ["field-group", "fifty-fifty"],
  //           inputFields: [
  //             {
  //               id : "fileUpload[fileName]",
  //               // cssClasses : ['hidden', "no-hint"],
  //               title : "Selected File", 
  //               readOnly: true,
  //               type : "text",
  //               value : this.options()['fileName'],
  //               // hint: "Check this box to enable Plotly Range Slider"
  //             }
  //           ],
  //         },
  //         {
  //           cssClasses : ["field-group", "fifty-fifty"],
  //           inputFields: [
  //             {
  //               id : "fileUpload[chartId]",
  //               // cssClasses : ['hidden', "no-hint"],
  //               title : "Chart ID", 
  //               readOnly: true,
  //               type : "text",
  //               value : this.options()['chartId'],
  //           },
  //           ],
  //         },
  //         {
  //           cssClasses : ["field-group", "fifty-fifty"],
  //             inputFields: [
  //             {
  //               id : "fileUpload[sheetId]",
  //               // cssClasses : ['hidden'],
  //               title : "Sheet",	
  //               type : "select",
  //               options : [],
  //               value : this.options()['sheetId'],
  //               hint: "Select sheet"
  //             },
  //           ],
  //         },
  //         {
  //           cssClasses : ["field-group", "fifty-fifty"],
  //           inputFields: [
  //             {
  //               id : "fileUpload[chartType]",
  //               // cssClasses : ['hidden'],
  //               title : "Chart Type",	
  //               type : "select",
  //               options : chartTypes(),
  //               value : this.options()['chartType'],
  //               hint: "Select chart type"
  //             },
  //           ],
  //         },
  //         {
  //           cssClasses : ["field-group", "fifty-fifty"],
  //           inputFields: [
  //             {
  //               id : "fileUpload[fileId]",
  //               title : "",	
  //               type : "text",
  //               value : this.options()['fileId'],
  //             },
  //           ],
  //         }
  //       ]  
  //     }
  //   }
  // } 

}

export default FileUpload;