import Plotly from 'plotly.js-basic-dist'
import fetchData from "./fetch-data"
import drawChart from "./draw-chart"
import { displayAdminMessage, toggleElementById, showElementById, setSheetIdOptions, showInputField, charParamsChangeHandler } from "./utilities"


let jsonRes = {} // Server response
let iwpgvObj
let iwpgvCharts

const mediaUploader = function ( ChartsObj, pluginObj ) {

  // Show warning
  showElementById( `${pluginObj.prefix}__warning` )


  iwpgvObj = {...pluginObj}
  iwpgvCharts = {...ChartsObj}

  // Initialize the media uploader
  let mediaUploader;
  if (mediaUploader) {
    mediaUploader.open();
    return;
  }
  mediaUploader = wp.media.frames.file_frame = wp.media({
    // title: "Select File",
    // button: {
    //   text: "Select File",
    // },
    multiple: false,
  });

  // Add media uploader event handler
  mediaUploader.on("select", async function () {

    try {

      // Get attachment
      const attachment = mediaUploader.state().get("selection").first().toJSON()

      // Bail if attachment can't be found
      if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

      // Update selected file and file Id
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value = attachment.filename
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileId]`).value = attachment.id
  
      // Get from 
      const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

      // Bail if no form is found
      if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

      // Create form object and append action and nonce
      const formData = new FormData( form )
      formData.append( "action", iwpgvObj.file_select_action );
      formData.append( "nonce", iwpgvObj.file_select_nonce );
      formData.append( "fileId", attachment.id );

      //send ajax resquest
      jsonRes = await fetchData(formData)
      console.log("JSONRES-UPLOAD", jsonRes)
      
      // Bail is server response status = error
      if (jsonRes.status && jsonRes.status === "error") throw new Error(  jsonRes.message )

      // Set sheet Id select field options, update sheet Id select field values
      setSheetIdOptions(jsonRes.spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
      document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = ""

      // Update chart type select field
      document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value = ""
      
      // Show all chart params panel input fields
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`) )

      // Add change event listener to all chart Params inputs
      // document.addEventListener( "change", charParamsHandler )

      
    } catch (error) {

      displayAdminMessage(error.message, "error",  iwpgvObj)
      // console.log("CAUGHT ERROR", error)

    }

   
  })

  mediaUploader.open()

}



const charParamsHandler = (event) => {

  // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
  if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return

  // Bail if no file, sheet Id or chart type
  if( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value ||  ! document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value || ! document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value   ) return

  // HIde chart and table charts
  Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  charParamsChangeHandler(jsonRes.spreadsheet, iwpgvCharts.chart, iwpgvObj)
   
  // Draw chart
  drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj)

  // Remove change event listener to all chart Params inputs
  document.removeEventListener( "change", charParamsHandler );


//   // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
//   if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return

//   // Bail if no file, sheet Id or chart type
//   if( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value ||  ! document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value || ! document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value   ) return

//   // HIde chart and table charts
//   Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
//   Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
//   Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

//   // remove layout panel toggle and panel
//   document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
//   document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
//   document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
//   document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

//   // Update chart params options
//   iwpgvCharts.chart.chartParams.options.fileUpload = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value
//   iwpgvCharts.chart.chartParams.options.sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value
//   iwpgvCharts.chart.chartParams.options.chartType = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value
//   iwpgvCharts.chart.chartParams.options.enableRangeSlider = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`).checked
//   iwpgvCharts.chart.chartParams.options.enableTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`).checked
//   iwpgvCharts.chart.chartParams.options.enableMinMaxTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`).checked

//   console.log("======", jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId], iwpgvCharts.chart.chartTraces.options)

//   const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
//   // if (iwpgvCharts.chart.chartTraces.options.length && iwpgvCharts.chart.chartParams.options.sheetId && iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId] && jsonRes.spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length) {
//     if (jsonRes.spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.chart.chartTraces.options.length) {
//     // console.log("HERE")
//     for (let i = jsonRes.spreadsheet[sheetIdInput.value].data.length-1; i < iwpgvCharts.chart.chartTraces.options.length; i++ ) {
//       delete iwpgvCharts.chart.chartTraces.options[i]
//       delete iwpgvCharts.chart.chartTraces.panel.sections[i]
//     }
//   }
  
//   // Delete extra rows if the length of the existing data object is greater thatn the new datat object
//   // if (iwpgvCharts.chart.chartParams.options.sheetId && jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId] && (jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length > jsonRes.spreadsheet[sheetIdInput.value].data.length )) {
//   //   for (let i = jsonRes.spreadsheet[sheetIdInput.value].data.length-1; i < jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length; i++ ) {
//   //     delete iwpgvCharts.chart.chartTraces.options[i]
//   //     delete iwpgvCharts.chart.chartTraces.panel.sections[i]
//   //   }
//   // }

 

//   // Draw chart
//   drawChart(jsonRes.spreadsheet, iwpgvCharts.chart, iwpgvObj)

}

export default mediaUploader;
