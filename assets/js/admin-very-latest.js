import Plotly from 'plotly.js-dist'
import selectFile from './select-file'
import saveChart from './save-chart'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import BasicOptions from './BasicOptions'
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Modebar from "./Modebar"
import ChartAxis from "./ChartAxis"
import drawChart from "./draw-chart"
import listCharts from "./list-charts"
import { displayAdminMessage, setSheetIdOptions, createPanel, createPanelSections } from "./utilities"
import "../sass/admin.scss"

// console.log("iwpgvObj", {...yrl_wp_igv_obj})
// console.log("iwpgvCharts", {...yrl_wp_igv_charts})

if ( "undefined" !== typeof yrl_wp_igv_charts ) {

  let iwpgvCharts = typeof yrl_wp_igv_charts !== undefined ?  yrl_wp_igv_charts : {}
  let iwpgvObj = typeof yrl_wp_igv_obj !== undefined ? yrl_wp_igv_obj : {}
  let mediaUploader
  let jsonRes = {}
  // let chart = {}
  let chart = { fileUpload: {}, layout: {}, config: {}, traces: [] } 
  let charts = undefined !== iwpgvCharts.charts ? iwpgvCharts.charts : {}
  // let traceSeed = undefined !== iwpgvCharts.traceSeed  ?  iwpgvCharts.traceSeed : {}
  let spreadsheet =  undefined !== iwpgvCharts.spreadsheet ?  iwpgvCharts.spreadsheet : []
  // let fontFamily = iwpgvCharts.fontFamily
  // let colors = iwpgvCharts.colors
  let prefix = iwpgvObj.prefix

  let wpRestUrl = iwpgvObj.wp_rest_url

  // const wpRestUrl = `http://sandbox/wp-json/${iwpgvObj.plugin}/v1/charts/chart`
  // const wpRestUrl = `http://wp-sandbox:8888/wp-json/${iwpgvObj.plugin}/v1/charts`

  console.log("iwpgvObj", {...iwpgvObj})
  console.log("iwpgvCharts", {...iwpgvCharts})


  try {


    // throw new Error( "iwpgvObj && iwpgvCharts missing")

    if ( ! Object.values( iwpgvObj ) || ! Object.values( iwpgvCharts ).length ) throw new Error( "iwpgvObj and/or iwpgvCharts missing")

    // Check if server error
    if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message )

    // check if action is valid
    if (! iwpgvCharts.action && iwpgvCharts.action !== "listCharts" && iwpgvCharts.action !== "editChart" ) throw new Error( "Invalid action" )

    // List all charts
    if ( iwpgvCharts.action === "listCharts" ||  iwpgvCharts.action === "deleteChart" ) listCharts( charts, prefix)

    // Add new chart or edit an existing chart
    if ( iwpgvCharts.action === "editChart" ) {

      if ( null !== iwpgvCharts.chart ) {
        chart = iwpgvCharts.chart
        chart.config.responsive = chart.layout.responsive !== undefined ? chart.layout.responsive : false 
        chart.config.staticPlot = chart.layout.staticPlot !== undefined ? chart.layout.staticPlot : false
    
      }  else {
        
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
        
      }

      console.log("CCHH",chart)


      // chart.fileUpload = {...chart.fileUpload}
      // chart.traces = {...chart.traces}
      // chart.minMaxAvgTable = {...chart.minMaxAvgTable}
      // chart.minMaxAvgTable.header = {...chart.minMaxAvgTable.header}
      // chart.minMaxAvgTable.cells = {...chart.minMaxAvgTable.cells}
    
      if ( chart.fileUpload.chartId) {
        
        // Set sheetId select field value and options 
        if ( ! spreadsheet) throw new Error( `Spreadsheet for chart (ID: ${chart.fileUpload.chartId}) (File: ${chart.fileUpload.fileName}) is missing` )
          
        // Set fileupload input parameters
        document.getElementsByName(`${prefix}__fileUpload[fileName]`)[0].value = chart.fileUpload.fileName
        document.getElementsByName(`${prefix}__fileUpload[chartId]`)[0].value = chart.fileUpload.chartId
        setSheetIdOptions(spreadsheet, document.getElementsByName(`${prefix}__fileUpload[sheetId]`)[0])
        document.getElementsByName(`${prefix}__fileUpload[sheetId]`)[0].value = chart.fileUpload.sheetId
        document.getElementsByName(`${prefix}__fileUpload[chartType]`)[0].value = chart.fileUpload.chartType
        document.getElementsByName(`${prefix}__fileUpload[fileId]`)[0].value = chart.fileUpload.fileId

        document.querySelector( `.${prefix}__admin .warning` ).classList.add("hidden")


        // Draw chart
        drawChart( chart, spreadsheet, prefix )


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

        // toggleElementByClass( `.${prefix}__admin .spinner` )
        document.querySelector( `.${prefix}__admin .warning` ).classList.add("hidden")
        document.querySelector( `.${prefix}__admin .loading` ).classList.remove("hidden")

        // Hide all but chart params panels
        // hidePanels()

        // Hide admin messages
        // document.querySelector(`.${prefix}__admin .admin-messages`).innerHTML = ""

        // Hide chart and table charts
        Plotly.purge(`${prefix}__plotlyChart`)
        Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)

        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .basicOptionsAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .configAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .tracesAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .titleAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .legendAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .hoverlabelAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .modebarAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxisAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxis2Ac`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxisAc`).classList.add( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxis2Ac`).classList.add( "hidden" )

        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.add( "hidden" )


        // Hide min/max inputs if visible
        // hideElementById( `${prefix}__plotMinMax` )

        document.getElementById(`${prefix}__saveChart`).disabled = true

        // Get attachment
        const attachment = mediaUploader.state().get("selection").first().toJSON()

        // Bail if attachment can't be found
        if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

        // Update selected file and file Id
        document.getElementsByName(`${prefix}__fileUpload[fileName]`)[0].value = attachment.filename
        document.getElementsByName(`${prefix}__fileUpload[fileId]`)[0].value = attachment.id

        // Fetch response
        jsonRes = await selectFile( attachment, iwpgvObj )
        console.log("JSONRES-UPLOAD", jsonRes)
    
        // Bail is server response status = error
        if (jsonRes.status && jsonRes.status === "error") throw new Error(  jsonRes.message )

        // get spreadsheet
        spreadsheet = jsonRes.spreadsheet
        // chart.fileUpload = {}

        // Set sheet Id select field options, update sheet Id select field values
        const sheetIdInput = document.getElementsByName( `${prefix}__fileUpload[sheetId]` )[0]
        setSheetIdOptions (spreadsheet, sheetIdInput )
        sheetIdInput.selectedIndex = sheetIdInput.options.length == 2 ? 1 : ""

        // Set chart type value
        document.getElementsByName( `${prefix}__fileUpload[chartType]` )[0].value = ""

        // Unhide sheet Id and chart type select fields
        document.getElementsByName( `${prefix}__fileUpload[fileName]` )[0].closest( ".field-group" ).classList.remove( "hidden" )
        document.getElementsByName( `${prefix}__fileUpload[sheetId]` )[0].closest( ".field-group" ).classList.remove( "hidden" )
        document.getElementsByName( `${prefix}__fileUpload[chartType]` )[0].closest( ".field-group" ).classList.remove( "hidden" )

        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.remove( "hidden" )


        // toggleElementByClass( `.${prefix}__admin .spinner` )
        document.querySelector( `.${prefix}__admin .warning` ).classList.remove("hidden")
        document.querySelector( `.${prefix}__admin .loading` ).classList.add("hidden")

        // // Set sheetId select field options and show it
        // showInputField( `${prefix}__fileUpload[sheetId]` )

        // // Show remaining chart params fields
        // showInputField( `${prefix}__fileUpload[fileUpload]` )
        // showInputField( `${prefix}__fileUpload[chartType]` )

        // toggleElementByClass( `.${prefix}__admin .spinner` )
        // toggleElementByClass( `.${prefix}__admin .warning` )
        // toggleElementByClass( `.${prefix}__admin .loading` )


      })


      // Add click event listener to the chart params panel inoput fields
      document.getElementsByName(`${prefix}__fileUpload[mediaUploadBtn]`)[0].addEventListener("click", async function (event) {
        event.preventDefault()
        mediaUploader.open()      
      })



      // Add click event listener to the Save Chart button
      document.getElementById(`${prefix}__saveChart`).addEventListener("click", function (event) {  
        event.preventDefault()
        saveChart( wpRestUrl, chart, iwpgvObj )
      })

      // Add change event listener to all input fields
      document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm`).addEventListener( "change", function (event) {

        // Update chart params options
        chart.fileUpload.fileName = document.getElementsByName( `${prefix}__fileUpload[fileName]` )[0].value
        chart.fileUpload.fileId = document.getElementsByName( `${prefix}__fileUpload[fileId]` )[0].value
        chart.fileUpload.sheetId = document.getElementsByName( `${prefix}__fileUpload[sheetId]` )[0].value
        chart.fileUpload.chartType = document.getElementsByName( `${prefix}__fileUpload[chartType]` )[0].value

        // Bail if no file, sheet Id or chart type
        if( ! event.target.classList.contains(`fileUpload`) || ! chart.fileUpload.fileName ||  ! chart.fileUpload.fileId || ! chart.fileUpload.sheetId || ! chart.fileUpload.chartType   ) return

        // Hide warning and unhide loading
        document.querySelector( `.${prefix}__admin .warning` ).classList.add("hidden")
        document.querySelector( `.${prefix}__admin .loading` ).classList.remove("hidden")

        // Remove extra traces if new spreasheet contains less columns than old spreasheet
        if (chart.traces) {
          const count = Object.values({...chart.traces}).length
            if ( spreadsheet[chart.fileUpload.sheetId].data.length < count +1 ) {
            for ( let i = spreadsheet[chart.fileUpload.sheetId].data.length-1; i <= count; i++ ) {
              delete chart.traces[i]
            }
          }
        }

        // Render input fields and set chart options
        drawChart( chart, spreadsheet, prefix )

        mainAccordion.closeAll()



      
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .basicOptionsPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .configPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .tracesPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .titlePanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .legendPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .hoverlabelPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .modebarPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxisPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxis2Panel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxisPanel`).classList.remove( "hidden" )
        // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxis2Panel`).classList.remove( "hidden" )

        // // Close all accordion panels
        // mainAccordion.closeAll()


      } )

      document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.remove( "hidden" )

      // Create mainaccordion and open first panel
      const mainAccordion = new Accordion( `.${prefix}__admin .main__Accordion`, { duration: 400 })
      if (chart.fileUpload.chartId) {
        mainAccordion.closeAll()
      } else {
        mainAccordion.open(0)
      }

      // new Accordion( tracesAccordionDiv, { duration: 400 } )


      // new Accordion( `.${prefix}__admin .legend__Accordion`, { duration: 400 })
      // new Accordion( `.${prefix}__admin .legend__Accordion`, { duration: 400 })
      // new Accordion( `.${prefix}__admin .xaxis__Accordion`, { duration: 400 })
      // new Accordion( `.${prefix}__admin .xaxis2__Accordion`, { duration: 400 })
      // new Accordion( `.${prefix}__admin .yaxis__Accordion`, { duration: 400 })
      // new Accordion( `.${prefix}__admin .yaxis2__Accordion`, { duration: 400 })
     

    }

    // console.log(document.querySelector(`.${prefix}__admin #rest-api`))

    // document.querySelector(`.${prefix}__admin #rest-api`).addEventListener("click", async function (event) {  
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

