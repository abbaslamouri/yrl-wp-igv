import Plotly from 'plotly.js-dist'
import cloneDeep from 'lodash.clonedeep'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import setParamsFields from './set-params-fields'
import saveChart from './save-chart'
import deleteAxis from './delete-axis'
import deleteAnnotation from './delete-annotation'
import listCharts from "./list-charts"
import { displayAdminMessage, hideOptions, chartOptionKey } from "./utilities"
import cancelChart from "./cancel-chart"
import paramsHandler from "./params-handler"
import configHandler from "./config-handler"
import layoutHandler from "./layout-handler"
import traceHandler from "./trace-handler"
import annotationsHandler from "./annotations-handler"
import axisHandler from "./axis-handler"
import Annotation from "./Annotation"
import "../sass/admin.scss"

// console.log("yrlPlotlyChartsObj", {...yrl_wp_plotly_charts_obj})

if (  yrl_wp_plotly_charts_obj ) {

  const yrlPlotlyChartsObj = yrl_wp_plotly_charts_obj
  const charts = yrlPlotlyChartsObj.charts
  const sheets = yrlPlotlyChartsObj.sheets
  let emptyChart = { params: {}, layout: {}, config: {}, traces: [] } 
  let chart = {}
  let spreadsheet = []
  const prefix = yrlPlotlyChartsObj.prefix
  const wpRestUrl = yrlPlotlyChartsObj.wp_rest_url
  const wpRestNonce= yrlPlotlyChartsObj.wp_rest_nonce
  const pluginUrl =yrlPlotlyChartsObj.url
  let chartUpdated = false

  console.log("yrlPlotlyChartsObj", {...yrlPlotlyChartsObj})
  
  try {

    // Check that the necessary parameters are present
    if ( yrlPlotlyChartsObj.wp_rest_nonce === undefined  || yrlPlotlyChartsObj.wp_rest_url === undefined ) throw new Error( "Don't know where to go from here" )
    if ( yrlPlotlyChartsObj.charts === undefined ) throw new Error( " can't find charts" )

    // Create main accordion
    const mainAccordion = new Accordion( `#${prefix}__admin .main__Accordion`, { duration: 400 })

    // List all charts
    listCharts( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix)

    // Initialize the media uploader
    if (mediaUploader) mediaUploader.open()
      
    let mediaUploader = wp.media.frames.file_frame = wp.media( { multiple: false } );

    // Add click event listener to the Add New Chart button
    document.addEventListener("click", async function (event) {

      if ( event.target.id.includes ( "deletAxis" ) )  {

        deleteAxis(chart, event.target.id, prefix )

      } else  if ( event.target.id.includes ( "deletAnnotation" ) )  {

        deleteAnnotation(chart, event.target.id, prefix )

      } else  {

        switch ( event.target.id ) {

          case `${prefix}__addNewChart`:
            event.preventDefault()
            Plotly.purge(`${prefix}__plotlyChart`)
            Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
  
            // Unhide chart  and open first accordion panel 
            document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")
            mainAccordion.close(0)
            chart = cloneDeep(emptyChart)
            hideOptions(prefix)
            mainAccordion.open(0)
            chartUpdated = false
  
            break
  
          case `${prefix}__cancelChart`:
            event.preventDefault()
  
          console.log("UPDATED", chartUpdated)
            if (chartUpdated) {
              cancelChart( chart, prefix )
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
            saveChart( chart, charts, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix )
            chartUpdated = false
            break
  
          case `${prefix}__addAnnotation`:
            event.preventDefault()
            Plotly.purge(`${prefix}__plotlyChart`)
            const index = chart.layout.annotations === undefined ? 0 : chart.layout.annotations.length
            chart.layout.annotations[index] = Annotation.defaultOptions()
            chart.layout.annotations[index].x = chart.layout.xaxis.range[0] +  (chart.layout.annotations.length )*(( chart.layout.xaxis.range[1] - chart.layout.xaxis.range[0])/10)
            chart.layout.annotations[index].y = ( chart.layout.yaxis.range[1] + chart.layout.yaxis.range[0])/2
            Plotly.plot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )           
            annotationsHandler( chart, prefix )
            break
  
          case `${prefix}__addNewXAxis`:
            event.preventDefault()
            axisHandler ( chart, "xaxis", prefix )
            break
  
          case `${prefix}__addNewYAxis`:
            event.preventDefault()
            axisHandler ( chart, "yaxis", prefix )
            break
  
        }

      }

     
      
    })


    // Add media uploader event handler
    mediaUploader.on("select", async function () {

      document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )
      document.querySelector( `#${prefix}__admin .loading` ).classList.remove( `hidden` )

      try {

        const attachment = mediaUploader.state().get("selection").first().toJSON()

        // Bail if attachment can't be found
        if ( ! attachment || ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

        const chartId = chart.params !== undefined && chart.params.chartId !== undefined ? chart.params.chartId : null

        spreadsheet = await setParamsFields( attachment.filename, attachment.id, null, "", chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix )

        chartUpdated = true

      } catch (error) {

        displayAdminMessage(error.message, "error",  prefix)
        console.log("CAUGHT ERROR", error)
    
      }

      document.querySelector( `#${prefix}__admin .warning` ).classList.remove( `hidden` )
      document.querySelector( `#${prefix}__admin .loading` ).classList.add( `hidden` )

    } )

    // Add change event listener to all input fields
    document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm` ).addEventListener( "input", async function ( event ) {
    event.preventDefault( )

      displayAdminMessage(null, null,  prefix)

      if( event.target.id.includes( `params` ) ) {
        // chart = cloneDeep(emptyChart)
        await paramsHandler( chart, spreadsheet, mainAccordion, prefix  )
        // xaxesAccordion = new Accordion( `#${prefix}__admin .xaxes__Accordion`, { duration: 400 } )
        console.log("CHART", chart)

      } else {

        const control = chartOptionKey(event.target.id).control
        const key = chartOptionKey(event.target.id).key
        const keyParts = key.split(".")
        let value =  event.target.type === 'checkbox' ? event.target.checked : event.target.value
        // console.group()
        // console.log("Control", control)
        // console.log("key", key)
        // console.log("keyParts", keyParts)
        // console.log("value", value)
        // console.groupEnd()

        switch ( control ) {

          case "config":
            configHandler( chart, key, value, prefix )
            break

          case "layout":
            layoutHandler( chart, key, value, prefix )
            console.log(chart.layout)
            break

            case "traces":
            traceHandler( chart, key, value, prefix )
            break

        }

      }
    
    } )


  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  }

}


