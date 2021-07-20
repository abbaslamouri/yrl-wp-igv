import Plotly from 'plotly.js-dist'
import Swal from 'sweetalert2'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import addNewChart from './add-new-chart'
import fileSelect from './file-select'
import saveChart from './save-chart'
import deleteAxis from './delete-axis'
import deleteAnnotation from './delete-annotation'
import listCharts from "./list-charts"
import configHandler from "./config-handler"
import layoutHandler from "./layout-handler"
import traceHandler from "./trace-handler"
import sheetHandler from "./sheet-handler"
import minMaxAvgHandler from "./minmaxavg-handler"
import annotationsHandler from "./annotations-handler"
import axisHandler from "./axis-handler"

import { displayAdminMessage, chartOptionKey, resetChart, showToolTip, cancelChart } from "./utilities"
import "../sass/admin.scss"


if (  yrl_wp_plotly_charts_obj ) {

  const yrlPlotlyChartsObj = yrl_wp_plotly_charts_obj
  const charts = yrlPlotlyChartsObj.charts
  const sheets = yrlPlotlyChartsObj.sheets
  var chart = {}
  let spreadsheet = []
  const prefix = yrlPlotlyChartsObj.prefix
  const shortcodeText = yrlPlotlyChartsObj.shortcodeText
  const wpRestUrl = yrlPlotlyChartsObj.wpRestUrl
  const wpRestNonce= yrlPlotlyChartsObj.wpRestNonce
  const pluginUrl =yrlPlotlyChartsObj.url
  let chartUpdated = false

  console.log("yrlPlotlyChartsObj", {...yrlPlotlyChartsObj})
  
  try {

    // Check that the necessary parameters are present
    if ( yrlPlotlyChartsObj.wpRestNonce === undefined  || yrlPlotlyChartsObj.wpRestUrl === undefined ) throw new Error( "Don't know where to go from here" )
    if ( yrlPlotlyChartsObj.charts === undefined ) throw new Error( " can't find charts" )

    // Create main accordion
    const mainAccordion = new Accordion( `#${prefix}__admin .main__Accordion`, { duration: 400 })
    mainAccordion.closeAll()

    // List all charts
    listCharts( charts, sheets, pluginUrl, shortcodeText, wpRestUrl, wpRestNonce, mainAccordion, prefix)

    // Initialize the media uploader
    if (mediaUploader) mediaUploader.open()
      
    let mediaUploader = wp.media.frames.file_frame = wp.media( { multiple: false } );

    // Add click event listener to the Add New Chart button
    document.querySelector( `#${prefix}__admin` ).addEventListener("click", async (event) => {
      displayAdminMessage(null, null,  prefix)

      const chartId =  document.getElementById(`${prefix}__params[chartId]`).value 
      if ( chartId ) chart = charts.filter(element => element.params.chartId == chartId)[0]

      if ( event.target.id.includes ( "deleteAxis" ) )  {
        event.preventDefault()
        deleteAxis(chart, event.target.id, prefix )

      } else if ( event.target.id.includes ( "deleteAnnotation" ) )  {
        event.preventDefault()
        deleteAnnotation(chart, event.target.id, prefix )

      } else if (event.target.classList.contains ( "form-group__tooltip-question-mark" )) {
        event.preventDefault()
        showToolTip( event.target, Swal, prefix)

      } else {

        switch ( event.target.id ) {

          case `${prefix}__addNewChart`:
            event.preventDefault()
            addNewChart( chart, mainAccordion, prefix )
            // console.log('chart', chart)
            chartUpdated = false
            break
  
          case `${prefix}__cancelChart`:
            event.preventDefault()
            if (chartUpdated) {
              cancelChart( Swal, prefix )
            } else {
              document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
            }
            break
  
          case `${prefix}__params[mediaUploadBtn]`:
            event.preventDefault()
  
            mediaUploader.open() 
            break
  
          case `${prefix}__saveChart`:
            event.preventDefault()
            chartUpdated = await saveChart( chart, charts, spreadsheet, sheets, pluginUrl, shortcodeText, wpRestUrl, wpRestNonce, mainAccordion, prefix )
            console.log("Saved", chart)
            break

          case `${prefix}__addAnnotation`:
            event.preventDefault()    
            chart = await annotationsHandler( chart, prefix )
            console.log("LLLLL", chart)
            break
  
          case `${prefix}__addNewXAxis`:
            event.preventDefault()
            axisHandler ( chart, "xaxis", prefix )
            break
  
          case `${prefix}__addNewYAxis`:
            event.preventDefault()
            axisHandler ( chart, "yaxis", prefix )
            break

          default:
            break
  
        }

      }
      
    })

    // Add media uploader event handler
    mediaUploader.on("select", async function () {

      const returnedObj = await fileSelect( JSON.parse( localStorage.getItem( 'chart')  ), charts, spreadsheet, wpRestUrl, wpRestNonce, mediaUploader, mainAccordion, prefix )

      // Set chart updated flag
      chart = returnedObj.chart
      spreadsheet = returnedObj.spreadsheet
      chartUpdated = true


    } )


    // Add change event listener to all input fields
    document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm` ).addEventListener( "change", async ( event ) => {
    event.preventDefault( )

      const control = chartOptionKey(event.target.id).control
      const key = chartOptionKey(event.target.id).key
      const keyParts = key.split(".")
      let value =  event.target.type === 'checkbox' ? event.target.checked : event.target.value
      console.group()
      console.log("Control", control)
      console.log("key", key)
      console.log("keyParts", keyParts)
      console.log("value", value)
      console.groupEnd()

      const chartId =  document.getElementById(`${prefix}__params[chartId]`).value 
      if ( chartId ) chart = charts.filter(element => element.params.chartId == chartId)[0]

      displayAdminMessage(null, null,  prefix)

      if( event.target.id === `${prefix}__params[sheetId]` ) {

         // Bail if there is no sheet id
        if ( event.target.value == "") return

        chartUpdated = await sheetHandler( chart, value, spreadsheet, mainAccordion, prefix )

      } else if (event.target.id === `${prefix}__params[enableMinMaxAvgTable]`) {

        await minMaxAvgHandler( chart, value, spreadsheet, wpRestUrl, wpRestNonce, prefix )

        console.log("BBBBBB", chart)


      } else {

        switch ( control ) {

          case "config":
            configHandler( chart, key, value, prefix )
            break

          case "layout":
            layoutHandler( chart, key, keyParts, value, prefix )
            break

            case "traces":
            traceHandler( chart, key, keyParts, value, spreadsheet, Plotly, prefix )
            break

        }

      }
    
    } )


  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  }

}


