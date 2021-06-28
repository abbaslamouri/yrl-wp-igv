import Plotly from 'plotly.js-dist'
import cloneDeep from 'lodash.clonedeep'
import swal from 'sweetalert'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'

import selectFile from './select-file'
import saveChart from './save-chart'
import resetChart from './reset-chart'

import Trace from './Trace'
import BasicOptions from './BasicOptions'
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Modebar from "./Modebar"
import ChartAxis from "./ChartAxis"
import drawChart from "./draw-chart"
import listCharts from "./list-charts"
import createTraces from "./create-traces"
import { displayAdminMessage, setSheetIdOptions, createPanel, createPanelSections, hideOptions, createChartCard, chartsListDefaultLayout } from "./utilities"
import addNewChartBtnHandler from "./add-new-chart-handler"
import cancelChartBtnHandler from "./cancel-chart-handler"
import fileParamsHandler from "./fileparams-handler"
import "../sass/admin.scss"

// console.log("iwpgvObj", {...yrl_wp_igv_obj})

if ( yrl_wp_igv_obj ) {

  const iwpgvObj = yrl_wp_igv_obj
  const charts = iwpgvObj.charts
  const sheets = iwpgvObj.sheets
  let mediaUploader
  let jsonRes = {}
  let emptyChart = { fileUpload: {}, layout: {}, config: {}, traces: [] } 
  let chart = {}
  let spreadsheet = []
  const prefix = iwpgvObj.prefix
  const wpRestUrl = iwpgvObj.wp_rest_url
  const wpRestNonce= iwpgvObj.wp_rest_nonce
  const pluginUrl =iwpgvObj.url

  console.log("iwpgvObj", {...iwpgvObj})
  
  try {

    // Check that the necessary parameters are present
    if ( iwpgvObj.wp_rest_nonce === undefined  || iwpgvObj.wp_rest_url === undefined ) throw new Error( " can't go anywhere from here" )
    if ( iwpgvObj.charts === undefined ) throw new Error( " can't find charts" )

    // List all charts
    listCharts( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, prefix)

    // Create main accordion
    const mainAccordion = new Accordion( `#${prefix}__admin .main__Accordion`, { duration: 400 })

    // Initialize the media uploader
    if (mediaUploader) mediaUploader.open()
      
    mediaUploader = wp.media.frames.file_frame = wp.media({
      // title: "Select File",
      // button: {
      //   text: "Select File",
      // },
      multiple: false,
    });

    // Add click event listener to the Add New Chart button
    document.addEventListener("click", async function (event) {
      event.preventDefault()

      switch ( event.target.id ) {

        case `${prefix}__addNewChart`:
          // Unhide chart  and open first accordion panel 
          document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")
          mainAccordion.close(0)
          resetChart(chart, prefix)
          // if ( ! chart.fileUpload.chartId ) 
          chart = cloneDeep(emptyChart)
          // console.log("NMNM", chart)
          // addNewChartBtnHandler( chart, prefix )
          mainAccordion.open(0)
          // chart.fileUpload = {}
          break

        // case `${prefix}__cancelChart`:
        //   console.log("NBBBBB", chart)
        //   cancelChartBtnHandler( chart, prefix )
        //   break

        case `${prefix}__fileUpload[mediaUploadBtn]`:
          mediaUploader.open() 
          break

        case `${prefix}__saveChart`:
          await saveChart( chart, charts, wpRestUrl, wpRestNonce, prefix )
          document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")

          const noChartsContaine = document.querySelector(`#${prefix}__admin .no-charts`)
          if ( noChartsContaine ) noChartsContaine.remove()

          createChartCard(chart, pluginUrl, `#${prefix}__admin .chart-library__content`, prefix)

          // chartsListDefaultLayout(chart)

          const newChart = cloneDeep( chart )
      
          newChart.layout.showlegend = false
          newChart.layout.hovermode = false
          newChart.layout.height = 300
          newChart.config.displayModeBar = false

          await Plotly.newPlot(`${prefix}__chart__${chart.fileUpload.chartId}`, newChart.traces, newChart.layout, newChart.config)
          
          break
      }
      
    })

    // Add media uploader event handler
    mediaUploader.on("select", async function () {
      // Hide chart and table charts
      Plotly.purge(`${prefix}__plotlyChart`)
      Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)

      const attachment = mediaUploader.state().get("selection").first().toJSON()
      spreadsheet = await selectFile( attachment, wpRestUrl, wpRestNonce, prefix )
       
      // Add change event listener to all input fields
      document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm`).addEventListener( "change", async function (event) {
        event.preventDefault()

        if( event.target.classList.contains(`fileUpload`) ) fileParamsHandler( chart, spreadsheet, mainAccordion, prefix  )
        
      } )

    } )

   


    
   





      






    // throw new Error( " Hi there" )

    // Add new chart or edit an existing chart
    // if ( iwpgvCharts.action === "editChart" ) {

    //   if ( null !== iwpgvCharts.chart ) {
    //     chart = iwpgvCharts.chart
    //     chart.config.responsive = chart.layout.responsive !== undefined ? chart.layout.responsive : false 
    //     chart.config.staticPlot = chart.layout.staticPlot !== undefined ? chart.layout.staticPlot : false
    
    //   }  else {
        
    //     chart.layout = { ...chart.layout, ...BasicOptions.defaultOptions( ) }
    //     chart.config.responsive = chart.layout.responsive
    //     chart.config.staticPlot = chart.layout.staticPlot
    
    //     chart.layout = { ...chart.layout, ...Title.defaultOptions( ) }
    //     chart.layout = { ...chart.layout, ...Legend.defaultOptions( ) }
    //     chart.layout = { ...chart.layout, ...Hoverlabel.defaultOptions( ) }
    
    //     chart.layout = { ...chart.layout, ...Modebar.defaultOptions( ) }
    //     chart.config.displayModeBar = chart.layout.displayModeBar
    //     chart.config.displaylogo = chart.layout.displaylogo
        
    //     chart.layout.xaxis = ChartAxis.defaultOptions( "xaxis", "bottom", null, "Wavelength ( &#181;m )", null ) 
    //     chart.layout.xaxis2 = ChartAxis.defaultOptions( "xaxis2", "top", "x", "Wavelength ( &#181;m )", "x" )
    //     chart.layout.yaxis = ChartAxis.defaultOptions( "yaxis", "left", null, "Transmittance ( % )", null )
    //     chart.layout.yaxis2 = ChartAxis.defaultOptions( "yaxis2", "right", "y", "Reflectance ( % )", "y" )
        
    //   }

    //   console.log("CCHH",chart)


    //   // chart.fileUpload = {...chart.fileUpload}
    //   // chart.traces = {...chart.traces}
    //   // chart.minMaxAvgTable = {...chart.minMaxAvgTable}
    //   // chart.minMaxAvgTable.header = {...chart.minMaxAvgTable.header}
    //   // chart.minMaxAvgTable.cells = {...chart.minMaxAvgTable.cells}
    
    //   if ( chart.fileUpload.chartId) {
        
    //     // Set sheetId select field value and options 
    //     if ( ! spreadsheet) throw new Error( `Spreadsheet for chart (ID: ${chart.fileUpload.chartId}) (File: ${chart.fileUpload.fileName}) is missing` )
          
    //     // Set fileupload input parameters
    //     document.getElementById(`${prefix}__fileUpload[fileName]`).value = chart.fileUpload.fileName
    //     document.getElementById(`${prefix}__fileUpload[chartId]`).value = chart.fileUpload.chartId
    //     setSheetIdOptions(spreadsheet, document.getElementById(`${prefix}__fileUpload[sheetId]`))
    //     document.getElementById(`${prefix}__fileUpload[sheetId]`).value = chart.fileUpload.sheetId
    //     document.getElementById(`${prefix}__fileUpload[chartType]`).value = chart.fileUpload.chartType
    //     document.getElementById(`${prefix}__fileUpload[fileId]`).value = chart.fileUpload.fileId

    //     document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")


    //     // Draw chart
    //     drawChart( chart, spreadsheet, prefix )


    //   }


    //   // Initialize the media uploader
    //   if (mediaUploader) mediaUploader.open()
        
    //   mediaUploader = wp.media.frames.file_frame = wp.media({
    //     // title: "Select File",
    //     // button: {
    //     //   text: "Select File",
    //     // },
    //     multiple: false,
    //   });

    //   // Add media uploader event handler
    //   mediaUploader.on("select", async function () {

    //     // toggleElementByClass( `#${prefix}__admin .spinner` )
    //     document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")
    //     document.querySelector( `#${prefix}__admin .loading` ).classList.remove("hidden")

    //     // Hide all but chart params panels
    //     // hidePanels()

    //     // Hide admin messages
    //     // document.querySelector(`#${prefix}__admin .admin-messages`).innerHTML = ""

    //     // Hide chart and table charts
    //     Plotly.purge(`${prefix}__plotlyChart`)
    //     Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)

    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .basicOptionsAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .configAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .tracesAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .titleAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .legendAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .hoverlabelAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .modebarAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxisAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxis2Ac`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxisAc`).classList.add( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxis2Ac`).classList.add( "hidden" )

    //     document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.add( "hidden" )


    //     // Hide min/max inputs if visible
    //     // hideElementById( `${prefix}__plotMinMax` )

    //     document.getElementById(`${prefix}__saveChart`).disabled = true

    //     // Get attachment
    //     const attachment = mediaUploader.state().get("selection").first().toJSON()

    //     // Bail if attachment can't be found
    //     if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

    //     // Update selected file and file Id
    //     document.getElementById(`${prefix}__fileUpload[fileName]`).value = attachment.filename
    //     document.getElementById(`${prefix}__fileUpload[fileId]`).value = attachment.id

    //     // Fetch response
    //     jsonRes = await selectFile( attachment, iwpgvObj )
    //     console.log("JSONRES-UPLOAD", jsonRes)
    
    //     // Bail is server response status = error
    //     if (jsonRes.status && jsonRes.status === "error") throw new Error(  jsonRes.message )

    //     // get spreadsheet
    //     spreadsheet = jsonRes.spreadsheet
    //     // chart.fileUpload = {}

    //     // Set sheet Id select field options, update sheet Id select field values
    //     const sheetIdInput = document.getElementById( `${prefix}__fileUpload[sheetId]` )
    //     setSheetIdOptions (spreadsheet, sheetIdInput )
    //     sheetIdInput.selectedIndex = sheetIdInput.options.length == 2 ? 1 : ""

    //     // Set chart type value
    //     document.getElementById( `${prefix}__fileUpload[chartType]` ).value = ""

    //     // Unhide sheet Id and chart type select fields
    //     document.getElementById( `${prefix}__fileUpload[fileName]` ).closest( ".field-group" ).classList.remove( "hidden" )
    //     document.getElementById( `${prefix}__fileUpload[sheetId]` ).closest( ".field-group" ).classList.remove( "hidden" )
    //     document.getElementById( `${prefix}__fileUpload[chartType]` ).closest( ".field-group" ).classList.remove( "hidden" )

    //     document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.remove( "hidden" )


    //     // toggleElementByClass( `#${prefix}__admin .spinner` )
    //     document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
    //     document.querySelector( `#${prefix}__admin .loading` ).classList.add("hidden")

    //     // // Set sheetId select field options and show it
    //     // showInputField( `${prefix}__fileUpload[sheetId]` )

    //     // // Show remaining chart params fields
    //     // showInputField( `${prefix}__fileUpload[fileUpload]` )
    //     // showInputField( `${prefix}__fileUpload[chartType]` )

    //     // toggleElementByClass( `#${prefix}__admin .spinner` )
    //     // toggleElementByClass( `#${prefix}__admin .warning` )
    //     // toggleElementByClass( `#${prefix}__admin .loading` )


    //   })


    //   // Add click event listener to the chart params panel inoput fields
    //   document.getElementById(`${prefix}__fileUpload[mediaUploadBtn]`).addEventListener("click", async function (event) {
    //     event.preventDefault()
    //     mediaUploader.open()      
    //   })



    

    //   // Add change event listener to all input fields
    //   document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm`).addEventListener( "change", function (event) {

    //     // Update chart params options
    //     chart.fileUpload.fileName = document.getElementById( `${prefix}__fileUpload[fileName]` ).value
    //     chart.fileUpload.fileId = document.getElementById( `${prefix}__fileUpload[fileId]` ).value
    //     chart.fileUpload.sheetId = document.getElementById( `${prefix}__fileUpload[sheetId]` ).value
    //     chart.fileUpload.chartType = document.getElementById( `${prefix}__fileUpload[chartType]` ).value

    //     // Bail if no file, sheet Id or chart type
    //     if( ! event.target.classList.contains(`fileUpload`) || ! chart.fileUpload.fileName ||  ! chart.fileUpload.fileId || ! chart.fileUpload.sheetId || ! chart.fileUpload.chartType   ) return

    //     // Hide warning and unhide loading
    //     document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")
    //     document.querySelector( `#${prefix}__admin .loading` ).classList.remove("hidden")

    //     // Remove extra traces if new spreasheet contains less columns than old spreasheet
    //     if (chart.traces) {
    //       const count = Object.values({...chart.traces}).length
    //         if ( spreadsheet[chart.fileUpload.sheetId].data.length < count +1 ) {
    //         for ( let i = spreadsheet[chart.fileUpload.sheetId].data.length-1; i <= count; i++ ) {
    //           delete chart.traces[i]
    //         }
    //       }
    //     }

    //     // Render input fields and set chart options
    //     drawChart( chart, spreadsheet, prefix )

    //     mainAccordion.closeAll()



      
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .basicOptionsPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .configPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .tracesPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .titlePanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .legendPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .hoverlabelPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .modebarPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxisPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxis2Panel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxisPanel`).classList.remove( "hidden" )
    //     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxis2Panel`).classList.remove( "hidden" )

    //     // // Close all accordion panels
    //     // mainAccordion.closeAll()


    //   } )

    //   document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.remove( "hidden" )

    

    //   // new Accordion( tracesAccordionDiv, { duration: 400 } )


    //   // new Accordion( `#${prefix}__admin .legend__Accordion`, { duration: 400 })
    //   // new Accordion( `#${prefix}__admin .legend__Accordion`, { duration: 400 })
    //   // new Accordion( `#${prefix}__admin .xaxis__Accordion`, { duration: 400 })
    //   // new Accordion( `#${prefix}__admin .xaxis2__Accordion`, { duration: 400 })
    //   // new Accordion( `#${prefix}__admin .yaxis__Accordion`, { duration: 400 })
    //   // new Accordion( `#${prefix}__admin .yaxis2__Accordion`, { duration: 400 })
     

    // }

    // console.log(document.querySelector(`#${prefix}__admin #rest-api`))

    // document.querySelector(`#${prefix}__admin #rest-api`).addEventListener("click", async function (event) {  
    //   event.preventDefault()
      
    //   const response = await fetch(`http://sandbox/wp-json/${iwpgvObj.plugin}/v1/charts/charts`, {
    //     method: "GET",
    //     headers: {'X-WP-Nonce': iwpgvObj.rest_api_nonce }
    //   })

    //   // Convert response to json
    //   const jsonRes = await response.json();
    //   console.log(jsonRes)
    // })



  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  }

}



// export default plotlyChart;


