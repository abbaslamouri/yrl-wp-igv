import Plotly from 'plotly.js-dist'
import swal from 'sweetalert'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'

import selectFile from './select-file'
import saveChart from './save-chart'

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
import layoutPanels from "./create-layout-panels"
import { displayAdminMessage, setSheetIdOptions, createPanel, createPanelSections, hideOptions } from "./utilities"
import { fileUploadFields } from "./event-listeners"
import "../sass/admin.scss"

// console.log("iwpgvObj", {...yrl_wp_igv_obj})
// console.log("iwpgvCharts", {...yrl_wp_igv_charts})

if ( yrl_wp_igv_obj ) {

  // let iwpgvCharts = typeof yrl_wp_igv_charts !== undefined ?  yrl_wp_igv_charts : {}
  const iwpgvObj = typeof yrl_wp_igv_obj !== undefined ? yrl_wp_igv_obj : {}
  let mediaUploader
  let jsonRes = {}
  // let chart = {}
  let chart = { fileUpload: {}, layout: {}, config: {}, traces: [] } 
  // const chart = {...emptyChart}
  // let traceSeed = undefined !== iwpgvCharts.traceSeed  ?  iwpgvCharts.traceSeed : {}
  let spreadsheet = []
  // let fontFamily = iwpgvCharts.fontFamily
  // let colors = iwpgvCharts.colors
  const prefix = iwpgvObj.prefix

  const wpRestUrl = iwpgvObj.wp_rest_url
  const wpRestNonce= iwpgvObj.wp_rest_nonce

  const charts = iwpgvObj.charts

  console.log("CHARTS", charts)

  console.log("iwpgvObj", {...iwpgvObj})
  


  try {


    // throw new Error( "iwpgvObj && iwpgvCharts missing")

    // if ( ! Object.values( iwpgvObj ) || ! Object.values( iwpgvCharts ).length ) throw new Error( "iwpgvObj and/or iwpgvCharts missing")

    // Check if server error
    if ( iwpgvObj.wp_rest_nonce === undefined  || iwpgvObj.wp_rest_url === undefined ) throw new Error( " can't go anywhere from here" )
    if ( iwpgvObj.charts === undefined ) throw new Error( " can't find charts" )

    // check if action is valid
    // if (! iwpgvCharts.action && iwpgvCharts.action !== "listCharts" && iwpgvCharts.action !== "editChart" ) throw new Error( "Invalid action" )

    // List all charts
    listCharts( charts, prefix)

    // Create mainaccordion and open first panel
    const mainAccordion = new Accordion( `#${prefix}__admin .main__Accordion`, { duration: 400 })

    
    // Add click event listener to the Add New Chart button
    document.getElementById(`${prefix}__addNewChart`).addEventListener("click", async function (event) {

      event.preventDefault()

      // Unhide chart
      document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")

      // const chart = emptyChart

      // Create mainaccordion and open first panel
      // const mainAccordion = new Accordion( `#${prefix}__admin .main__Accordion`, { duration: 400 })
      if (chart.fileUpload.chartId) {
        mainAccordion.closeAll()
      } else {
        mainAccordion.open(0)
      }

      // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.remove( "hidden" )
      
      // Retreive new chart options
      chart.layout = { ...chart.layout, ...BasicOptions.defaultOptions( ) }
      chart.config.responsive = chart.layout.responsive
      chart.config.staticPlot = chart.layout.staticPlot
  
      chart.layout = { ...chart.layout, ...Title.defaultOptions( ) }
      chart.layout = { ...chart.layout, ...Legend.defaultOptions( ) }
      chart.layout = { ...chart.layout, ...Hoverlabel.defaultOptions( ) }
  
      chart.layout = { ...chart.layout, ...Modebar.defaultOptions( ) }
      chart.config.displayModeBar = chart.layout.displayModeBar
      chart.config.displaylogo = chart.layout.displaylogo
      
      chart.layout.xaxis = ChartAxis.defaultOptions( "xaxis", "bottom", null, "Wavelength ( &#181;m )", null ) 
      chart.layout.xaxis2 = ChartAxis.defaultOptions( "xaxis2", "top", "x", "Wavelength ( &#181;m )", "x" )
      chart.layout.yaxis = ChartAxis.defaultOptions( "yaxis", "left", null, "Transmittance ( % )", null )
      chart.layout.yaxis2 = ChartAxis.defaultOptions( "yaxis2", "right", "y", "Reflectance ( % )", "y" )

      // Render input fields and set chart options
      // drawChart( chart, spreadsheet, prefix )


      // // Create mainaccordion and open first panel
      // const mainAccordion = new Accordion( `#${prefix}__admin .main__Accordion`, { duration: 400 })
      // mainAccordion.open(0)

    })

    // Add click event listener to the Cancel Chart button
    document.getElementById(`${prefix}__cancelChart`).addEventListener("click", async function (event) {
      event.preventDefault()
      
      if ( !chart.traces.length ) {
        document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
        return
      }

      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this chart",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
          Plotly.purge(`${prefix}__plotlyChart`)
          Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
          document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
          hideOptions(prefix)
          // swal(`Chart (ID=${event.target.closest(".delete-chart").dataset.chartId}) has been deleted!`, {
          //   icon: "success",
          // });

          chart = { fileUpload: {}, layout: {}, config: {}, traces: [] } 
        } 
      })


      
    })

    // Initialize the media uploader
    if (mediaUploader) mediaUploader.open()
      
    mediaUploader = wp.media.frames.file_frame = wp.media({
      // title: "Select File",
      // button: {
      //   text: "Select File",
      // },
      multiple: false,
    });

     // Add click event listener to the media uploader button
     document.getElementById(`${prefix}__fileUpload[mediaUploadBtn]`).addEventListener("click", async function (event) {
      event.preventDefault()
      mediaUploader.open()      
    })

    // Add media uploader event handler
    mediaUploader.on("select", async function () {
      // Hide chart and table charts
      Plotly.purge(`${prefix}__plotlyChart`)
      Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)

      const attachment = mediaUploader.state().get("selection").first().toJSON()
      spreadsheet = await selectFile( attachment, wpRestUrl, wpRestNonce, prefix )

    } )

     // Add change event listener to all input fields
     document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm`).addEventListener( "change", async function (event) {

      event.preventDefault()

      // Update chart params options
      chart.fileUpload.fileName = document.getElementById( `${prefix}__fileUpload[fileName]` ).value
      chart.fileUpload.fileId = document.getElementById( `${prefix}__fileUpload[fileId]` ).value
      chart.fileUpload.sheetId = document.getElementById( `${prefix}__fileUpload[sheetId]` ).value
      chart.fileUpload.chartType = document.getElementById( `${prefix}__fileUpload[chartType]` ).value

      // Bail if no file, sheet Id or chart type
      if( ! event.target.classList.contains(`fileUpload`) || ! chart.fileUpload.fileName ||  ! chart.fileUpload.fileId || ! chart.fileUpload.sheetId || ! chart.fileUpload.chartType   ) return

      // Hide warning and unhide loading
      document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")
      document.querySelector( `#${prefix}__admin .loading` ).classList.remove("hidden")


      // fileUploadFields( event, chart, spreadsheet, prefix )

      createTraces( chart, spreadsheet, prefix )

      document.querySelector( `#${prefix}__admin .loading` ).classList.add("hidden")

      await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

      layoutPanels( chart, prefix )

      mainAccordion.closeAll()

      // Enable save button  // Add click event listener to the chart params panel inoput fields
      document.getElementById( `${prefix}__saveChart` ).disabled = false
      document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
  
    } )


    // Add click event listener to the Save Chart button
    document.getElementById(`${prefix}__saveChart`).addEventListener("click", function (event) {  
      event.preventDefault()
      saveChart( chart, charts, wpRestUrl, wpRestNonce, prefix )
    })
   





      






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


