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
import drawChart from './draw-chart'
import saveChart from './save-chart'
import deleteAxis from './delete-axis'
import deleteAnnotation from './delete-annotation'
import listCharts from "./list-charts"
// import cancelChart from "./cancel-chart"
import paramsHandler from "./params-handler"
import configHandler from "./config-handler"
import layoutHandler from "./layout-handler"
import traceHandler from "./trace-handler"
import annotationsHandler from "./annotations-handler"
import axisHandler from "./axis-handler"
import Annotation from "./Annotation"
import TableTrace from "./TableTrace"
import chartOptions from './options'
import panels from './panels'
import tracesPanel from "./traces-panel"
import { displayAdminMessage, hideOptions, chartOptionKey, trimArray, setSelectFieldOptions, resetChart, showToolTip, cancelChart , fetchMinMaxAvgCellValues, addMinMaxAvgTable, addRangeMinMaxInputs, minMaxRangesliderHandler, createChartCard, fetchChartListDefaultOptions} from "./utilities"
import "../sass/admin.scss"
// import { redraw } from 'plotly.js-basic-dist'

// console.log("yrlPlotlyChartsObj", {...yrl_wp_plotly_charts_obj})

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
    mainAccordion.closeAll()

    // List all charts
    listCharts( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix)

    // Initialize the media uploader
    if (mediaUploader) mediaUploader.open()
      
    let mediaUploader = wp.media.frames.file_frame = wp.media( { multiple: false } );

    // Add click event listener to the Add New Chart button
    document.querySelector( `#${prefix}__admin` ).addEventListener("click", async function (event) {
      displayAdminMessage(null, null,  prefix)

      const chartId =  document.getElementById(`${prefix}__params[chartId]`).value 
      if ( chartId ) chart = charts.filter(element => element.params.chartId == chartId)[0]


      if ( event.target.id.includes ( "deletAxis" ) )  {
        event.preventDefault()

        deleteAxis(chart, event.target.id, prefix )

      } else if ( event.target.id.includes ( "deletAnnotation" ) )  {
        event.preventDefault()

        deleteAnnotation(chart, event.target.id, prefix )

      } else if (event.target.classList.contains ( "form-group__tooltip-question-mark" )) {
        event.preventDefault()

        showToolTip( event.target, Swal, prefix)

      } else {

        switch ( event.target.id ) {

          case `${prefix}__addNewChart`:

            event.preventDefault()

            resetChart ( Plotly, prefix )
  
            // Unhide chart  and open first accordion panel 
            document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")

            // clone empty chart
            chart = cloneDeep(emptyChart)

            // hideOptions(prefix)
            mainAccordion.open(0)

            // Set chart updated flag
            chartUpdated = false

            console.log("NewTestCharts",charts)
            console.log("NewTestShets",sheets)

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
            if ( ! Object.values(chart.traces).length || ! chart.params.fileId || ! chart.params.sheetId ) throw new Error(  `Chart traces as well as a file name and a sheet ID are required to save a chart` )

            document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
        
            // get chart id
            if ( chart.params.chartId === null ) { // There is a chart Id (edit)
              const chartId = ! charts.length ? 16327 : charts[charts.length-1].params.chartId + 1
              chart.params.chartId = chartId
              charts.push( chart )
              sheets.push( {chartId, sheet: spreadsheet[chart.params.sheetId]} )
              // document.getElementById(`${prefix}__params[chartId]`).value = chartId
              // sheets[chartId] = spreadsheet[chart.params.sheetId]
            }
        
           
            // Save charts
            jsonRes = await fetchData( wpRestUrl, "POST", wpRestNonce, JSON.stringify(charts) ) 
            // jsonRes = await response.json();
            // console.log("JSONRES-SAVE", jsonRes)
        
            // // Bail is server response status = error
            // if (response.status !== 200 ) throw new Error( jsonRes.message )


            // Get Data
            jsonRes = await fetchData( wpRestUrl, "GET", wpRestNonce ) 
            // jsonRes = await response.json();
            // console.log("JSONRES-GET", jsonRes)
        
            // // Bail is server response status = error
            // if (response.status !== 200 ) throw new Error( jsonRes.message )


            
        
            // Remove the no-chart div it it exists
            if ( document.querySelector(`#${prefix}__admin .no-charts`) ) document.querySelector(`#${prefix}__admin .no-charts`).remove()
        
            document.querySelector(`#${prefix}__admin .chart-library__content`).innerHTML = ""
        
             // List all charts
            await listCharts( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix)
            // await saveChart( chart, charts, spreadsheet, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix )

            // Create a new chart card
            // createChartCard(chart, pluginUrl, `#${prefix}__admin .chart-library__content`, prefix)

            // Clone chart
            // const newChart = cloneDeep( chart )

            // Ser card chart default layout
            // newChart.layout.showlegend = false
            // newChart.layout.hovermode = false
            // newChart.layout.height = 300
            // newChart.config.displayModeBar = false
            // const newChart = fetchChartListDefaultOptions( cloneDeep( chart ) )


            // Plot card chart
            // await Plotly.newPlot(`${prefix}__chart__${chart.params.chartId}`, newChart.traces, newChart.layout, newChart.config)

            // Success handler
            displayAdminMessage("Chart saved successfully", "success",  prefix)



            chart = cloneDeep(emptyChart)
            console.log("DEEP", chart)
            console.log("charts", charts)
            console.log("sheets", sheets)
            // document.getElementById(`${prefix}__params[chartId]`).value = null
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

        console.log("SSSSSS", cloneDeep(chart))

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

        // Reset chart traces types
        // if (Object.keys(chart.traces).length)
        // for (const prop in chart.traces) {
        //   chart.traces[prop].type = null
        // }

       
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

      console.log(event.target)

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
            console.log(chart.layout)
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


