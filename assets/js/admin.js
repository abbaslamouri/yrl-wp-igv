import Plotly from 'plotly.js-dist'
import cloneDeep from 'lodash.clonedeep'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import setParamsFields from './set-params-fields'
import saveChart from './save-chart'
import listCharts from "./list-charts"
import { displayAdminMessage, hideOptions, chartOptionKey } from "./utilities"
import cancelChart from "./cancel-chart"
import paramsHandler from "./params-handler"
import configHandler from "./config-handler"
import layoutHandler from "./layout-handler"
import traceHandler from "./trace-handler"
import annotations from "./annotations"
import chartAxis from "./chart-axis"
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
    let xaxesAccordion = null

    // console.log(xaxesAccordion)
    // List all charts
    listCharts( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix)

    // Initialize the media uploader
    if (mediaUploader) mediaUploader.open()
      
    let mediaUploader = wp.media.frames.file_frame = wp.media( { multiple: false } );

    // Add click event listener to the Add New Chart button
    document.addEventListener("click", async function (event) {

      if ( event.target.id.includes ( "deleteAxis" ) )  {
        console.log( event.target.id)

        const key = chartOptionKey(event.target.id).key
        console.log(key)
        delete chart.layout[key]
        console.log(chart.layout)
        document.getElementById( event.target.id ).closest(".ac").remove()

      } else {

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
            annotations( chart, prefix )
            break
  
          case `${prefix}__addNewXAxis`:
            event.preventDefault()
            chartAxis ( chart, "xaxis", prefix )
            break
  
          case `${prefix}__addNewYAxis`:
            event.preventDefault()
            chartAxis ( chart, "yaxis", prefix )
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
        console.group()
        console.log("Control", control)
        console.log("key", key)
        console.log("keyParts", keyParts)
        console.log("value", value)
        console.groupEnd()

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


