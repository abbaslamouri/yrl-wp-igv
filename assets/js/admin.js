import Plotly from 'plotly.js-basic-dist'
import fetchData from "./fetch-data"
import Accordion from "./Accordion"
import ChartParams from "./ChartParams"
import ChartLayout from "./ChartLayout"
// import mediaUploader from "./media-uploader"
import panel from "./panel"
import renderPanels from "./render-panels"
import renderChart from "./render-chart"
import listCharts from "./list-charts"
import chartParamsChangeHandler from "./chartparams-change-handler"
import { displayAdminMessage, showElementById, hideElementById, showInputField, setSheetIdOptions, charParamsChangeHandler, showchartParamsInputFields } from "./utilities"
import "../sass/admin.scss"
import selectFile from './select-file'


let iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
let iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}
let mediaUploader
// let jsonRes = {}
let spreadsheet = []



console.log("iwpgvObj", {...iwpgvObj})
console.log("iwpgvCharts", {...iwpgvCharts})

try {

  // throw new Error("Stop")

  // Check if server error
  if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message )

  // check if action is valid
  if (! iwpgvCharts.action && iwpgvCharts.action !== "listCharts" && iwpgvCharts.action !== "editChart" ) throw new Error( "Invalid action" )

  // List all charts
  if (iwpgvCharts.action && iwpgvCharts.action === "listCharts") {

    listCharts( iwpgvCharts.charts, iwpgvObj)

  }
  
  // Add new chart or edit an existing chart
  if ( iwpgvCharts.action && iwpgvCharts.action === "editChart" ) {

     // Assemble chart Params chart and panels
    const chartParamsInstance = new ChartParams( iwpgvCharts.chart.chartParams.options, iwpgvObj )
    iwpgvCharts.chart.chartParams.options = chartParamsInstance.options()
    iwpgvCharts.chart.chartParams.panel.sections = chartParamsInstance.sections()
    panel(iwpgvCharts.chart.chartParams.panel, iwpgvObj)
    document.querySelector( `.accordion__toggle.chartParams` ).classList.remove("hidden")
    document.querySelector( `.accordion__content.chartParams` ).classList.remove("hidden")
   
    if ( ! iwpgvCharts.spreadsheet) { // New chart
      
      //    jkjkjjjjkjkjk

    } else { // Edit an existing chart
   
      setSheetIdOptions(iwpgvCharts.spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`))
      document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = iwpgvCharts.chart.chartParams.options.sheetId

      spreadsheet = iwpgvCharts.spreadsheet

      // Show the remaining input fields in the chart params panel
      showchartParamsInputFields(iwpgvObj)

      renderChart( iwpgvCharts, iwpgvObj, spreadsheet )

    

    }

    // Initialize the media uploader
    if (mediaUploader) mediaUploader.open()
      
    mediaUploader = wp.media.frames.file_frame = wp.media({
      // title: "Select File",
      // button: {
      //   text: "Select File",
      // },
      multiple: false,
    });

    // Add media uploader event handler
    mediaUploader.on("select", async function () {

      // Get attachment
      const attachment = mediaUploader.state().get("selection").first().toJSON()

      // Bail if attachment can't be found
      if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

      // Update selected file and file Id
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value = attachment.filename
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileId]`).value = attachment.id

      spreadsheet = await selectFile( attachment, iwpgvObj )
      
      // Show all chart params panel input fields
      showchartParamsInputFields(iwpgvObj)

    })




     // Add click event listener to the chart params panel inoput fields
     document.getElementById(`${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`).addEventListener("click", async function (event) {
    
      event.preventDefault()

      mediaUploader.open()

      
      // // HIde chart and table charts
      // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
      // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
      // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

      // // Hide min and max input fields
      // hideElementById (`${iwpgvObj.prefix}__plotMinMax` )

      // mediaUploader( iwpgvCharts, iwpgvObj )
      //   document.addEventListener( "change", chartParamsChangeHandler )

      
          
    })

    document.addEventListener( "change", function () {

      // Bail if the clicked item is not inside the `${iwpgvCharts.prefix}__chartOptionsForm` form 
      if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return

      // Update chart params options
      iwpgvCharts.chart.chartParams.options.fileUpload = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value
      iwpgvCharts.chart.chartParams.options.sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value
      iwpgvCharts.chart.chartParams.options.chartType = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value
      iwpgvCharts.chart.chartParams.options.enableRangeSlider = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`).checked
      iwpgvCharts.chart.chartParams.options.enableTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`).checked
      iwpgvCharts.chart.chartParams.options.enableMinMaxTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`).checked


      // Bail if no file, sheet Id or chart type
      if( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value ||  ! document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value || ! document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value   ) return

      // console.log("======", spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId], iwpgvCharts.chart.chartTraces.options)

      const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
      // if (iwpgvCharts.chart.chartTraces.options.length && iwpgvCharts.chart.chartParams.options.sheetId && iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId] && jsonResjsonRes..spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length) {
        if (spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.chart.chartTraces.options.length) {
        // console.log("HERE")
        for (let i = spreadsheet[sheetIdInput.value].data.length-1; i < iwpgvCharts.chart.chartTraces.options.length; i++ ) {
          delete iwpgvCharts.chart.chartTraces.options[i]
          delete iwpgvCharts.chart.chartTraces.panel.sections[i]
        }
      }
      
      renderChart( iwpgvCharts, iwpgvObj, spreadsheet )

    } )



   

    


    

    // Show saveChart button
    // showElementById (`${iwpgvObj.prefix}__saveChart` )
      
    // // Add click event listener to the chart params panel inoput fields
    // document.getElementById(`${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`).addEventListener("click", async function (event) {
     
    //   event.preventDefault()
      
    //   // HIde chart and table charts
    //   Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
    //   Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
    //   Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

    //   // Hide min and max input fields
    //   hideElementById (`${iwpgvObj.prefix}__plotMinMax` )

    //   mediaUploader( iwpgvCharts, iwpgvObj )
    //     document.addEventListener( "change", chartParamsChangeHandler )

      
          
    // })



  }


} catch (error) {

  displayAdminMessage(error.message, "error",  iwpgvObj)
  console.log("CAUGHT ERROR", error)

} 

// Load accordion
new Accordion( { collapsed: false } )





















