import Plotly from 'plotly.js-dist'
import Swal from 'sweetalert2'
import cloneDeep from 'lodash.clonedeep'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import fetchData from "./fetch-data"
import fetchSpreadsheet from './fetch-spreadsheet'
import addNewChart from './add-new-chart'
import drawChart from './draw-chart'
import saveChart from './save-chart'
import deleteAxis from './delete-axis'
import deleteAnnotation from './delete-annotation'
import listCharts from "./list-charts"
import configHandler from "./config-handler"
import layoutHandler from "./layout-handler"
import traceHandler from "./trace-handler"
import annotationsPanels from "./annotations-panels"
import annotationsHandler from "./annotations-handler"
import axisHandler from "./axis-handler"
import Annotation from "./Annotation"
import TableTrace from "./TableTrace"
import tracesPanel from "./traces-panel"
import { displayAdminMessage, chartOptionKey, setSelectFieldOptions, resetChart, showToolTip, cancelChart, addMinMaxAvgTable, addRangeMinMaxInputs, minMaxRangesliderHandler} from "./utilities"
import "../sass/admin.scss"


if (  yrl_wp_plotly_charts_obj ) {

  const yrlPlotlyChartsObj = yrl_wp_plotly_charts_obj
  const charts = yrlPlotlyChartsObj.charts
  const sheets = yrlPlotlyChartsObj.sheets
  let emptyChart = { params: {}, layout: {}, config: {}, traces: [] } 
  let chart = {}
  let spreadsheet = []
  let response = null
  let jsonRes = null
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
            chart = addNewChart( chart, emptyChart, mainAccordion, prefix )

            // Set chart updated flag
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

      const chartId =  document.getElementById(`${prefix}__params[chartId]`).value 
      if ( chartId ) chart = charts.filter(element => element.params.chartId == chartId)[0]

      // Toggle warning and loading
      document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )
      document.querySelector( `#${prefix}__admin .loading` ).classList.remove( `hidden` )

      try {


        //fetch attachment
        const attachment = mediaUploader.state().get("selection").first().toJSON()

        // Bail if attachment can't be found
        if ( ! attachment || ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

        // set chart filename and file id and params file name and file id
        chart.params.fileName = attachment.filename
        document.getElementById(`${prefix}__params[fileName]`).value = chart.params.fileName
        chart.params.fileId = attachment.id
        document.getElementById(`${prefix}__params[fileId]`).value = chart.params.fileId

        // get min/max/avg/ checkbox
        chart.params.enableMinMaxAvgTable = document.getElementById(`${prefix}__params[enableMinMaxAvgTable]`).checked

        // Set chart params chart Id
        if ( undefined === chart.params.chartId ) chart.params.chartId  = null
        document.getElementById(`${prefix}__params[chartId]`).value = chart.params.chartId

        spreadsheet = await fetchSpreadsheet ( chart, wpRestUrl, wpRestNonce, prefix )

        // Set sheet select field options array
        setSelectFieldOptions( document.getElementById( `${prefix}__params[sheetId]` ), spreadsheet.map( el  => el.sheetName ) )

        // Set params sheet id and sheet select value
        chart.params.sheetId  = spreadsheet.length == 1 ? Object.keys(spreadsheet)[0]: ""
        document.getElementById( `${prefix}__params[sheetId]` ).value = chart.params.sheetId

        // Unhide file name, file id and chart id input fields
        document.getElementById( `${prefix}__params[fileName]` ).closest('.field-group' ).classList.remove ( 'hidden' )
        document.getElementById( `${prefix}__params[sheetId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
        document.getElementById( `${prefix}__params[chartId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
        document.getElementById( `${prefix}__params[enableMinMaxAvgTable]` ).closest('.field-group' ).classList.remove ( 'hidden' )

       
        // draw chart immediatelly if spreadsheet contains a single sheet
        if ( spreadsheet.length == 1  ) {
          document.getElementById( `${prefix}__params[sheetId]` ).disabled = true
          await drawChart ( chart, spreadsheet, prefix )
          document.querySelector( `#${prefix}__admin .loading` ).classList.add( `hidden` )

          // Close main accordion
          mainAccordion.closeAll()

        } else {
          document.querySelector( `#${prefix}__admin .warning` ).classList.remove( `hidden` )
          document.querySelector( `#${prefix}__admin .loading` ).classList.add( `hidden` )
        }

        // Set chart updated flag
        chartUpdated = true

      } catch (error) {

        displayAdminMessage(error.message, "error",  prefix)
        console.log("CAUGHT ERROR", error)
    
      }

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

        // set params sheet id
        chart.params.sheetId = event.target.value

        // get min/max/avg/ checkbox
        chart.params.enableMinMaxAvgTable = document.getElementById(`${prefix}__params[enableMinMaxAvgTable]`).checked

        // Unhide file name, file id and chart id input fields
        document.getElementById( `${prefix}__params[fileName]` ).closest('.field-group' ).classList.remove ( 'hidden' )
        document.getElementById( `${prefix}__params[sheetId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
        document.getElementById( `${prefix}__params[chartId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
        document.getElementById( `${prefix}__params[enableMinMaxAvgTable]` ).closest('.field-group' ).classList.remove ( 'hidden' )

        // Toggle warning
        document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )

        // draw chart
        await drawChart ( chart, spreadsheet, prefix )

        // Close main accordion
        mainAccordion.closeAll()

        // et chart updated flag
        chartUpdated = true

      } else if (event.target.id === `${prefix}__params[enableMinMaxAvgTable]`) {

        if ( value ) {

          const update = {'xaxis.domain': [0,.5]}
          Plotly.relayout( `${prefix}__plotlyChart`, update)

          addMinMaxAvgTable( chart, TableTrace, spreadsheet, arrayMin, arrayMax, arrayMean, floatRound )
          await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {
          tracesPanel( chart, spreadsheet, prefix )

          addRangeMinMaxInputs( chart, Plotly, floatRound, prefix )
          // Add range slider event handler
          eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

            minMaxRangesliderHandler( chart, eventData, spreadsheet, Plotly, arrayMin, arrayMax, arrayMean, floatRound, prefix  )

          })
        } else {

          const update = {'xaxis.domain': [0,1]}
          Plotly.relayout( `${prefix}__plotlyChart`, update)
          Plotly.deleteTraces( `${prefix}__plotlyChart`, chart.traces.length-1 )
          tracesPanel( chart, spreadsheet, prefix )

          document.getElementById( `${prefix}__plotMinMaxAvgForm` ).classList.add( 'hidden')



          console.log(chart.traces)
        }


        console.log(chart)

      } else {

        switch ( control ) {

          case "config":
            configHandler( chart, key, value, prefix )
            break

          case "layout":
            layoutHandler( chart, key, keyParts, value, prefix )
            break

            case "traces":
            traceHandler( chart, key, keyParts, value, Plotly, prefix )
            break

        }

      }
    
    } )


  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  }

}


