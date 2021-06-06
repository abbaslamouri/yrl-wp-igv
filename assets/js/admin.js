import Plotly from 'plotly.js-dist'
import selectFile from './select-file'
import saveChart from './save-chart'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import drawChart from "./draw-chart"
import listCharts from "./list-charts"
import { displayAdminMessage, setSheetIdOptions,  } from "./utilities"
import "../sass/admin.scss"

// console.log("iwpgvObj", {...yrl_wp_igv_obj})
// console.log("iwpgvCharts", {...yrl_wp_igv_charts})

if ( "undefined" !== typeof yrl_wp_igv_charts ) {

  let iwpgvCharts = typeof yrl_wp_igv_charts !== undefined ?  yrl_wp_igv_charts : {}
  let iwpgvObj = typeof yrl_wp_igv_obj !== undefined ? yrl_wp_igv_obj : {}
  let mediaUploader
  let jsonRes = {}
  let chart = undefined !== iwpgvCharts.chart ? iwpgvCharts.chart :{}
  let charts = undefined !== iwpgvCharts.charts ? iwpgvCharts.charts : {}
  let spreadsheet = iwpgvCharts.spreadsheet
  let prefix = iwpgvObj.prefix

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

      chart.fileUpload = chart.fileUpload.length ? chart.fileUpload : {}
      chart.traces = chart.traces.length ? chart.traces : {}

      console.log(chart)
    
      if ( chart.fileUpload.chartId) {
        
        // Set sheetId select field value and options 
        if ( ! spreadsheet) throw new Error( `Spreadsheet for chart (ID: ${chart.fileUpload.chartId}) (File: ${chart.fileUpload.fileName}) is missing` )
          
        setSheetIdOptions(spreadsheet, document.getElementsByName(`${prefix}__fileUpload[sheetId]`)[0])
        document.getElementsByName(`${prefix}__fileUpload[sheetId]`)[0].value = chart.fileUpload.sheetId

        
        // Draw chart
        drawChart( iwpgvCharts, prefix, spreadsheet )
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
        saveChart(chart, iwpgvObj)
        return false
      })

      // Add change event listener to all input fields
      document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm`).addEventListener( "change", function (event) {

        // Bail if the clicked item is not inside the `${iwpgvCharts.prefix}__chartOptionsForm` form 
        if ( ! event.target.classList.contains(`fileUpload`)  ) return

        document.querySelector( `.${prefix}__admin .warning` ).classList.add("hidden")
        document.querySelector( `.${prefix}__admin .loading` ).classList.remove("hidden")

        // Update chart params options
        chart.fileUpload.fileName = document.getElementsByName( `${prefix}__fileUpload[fileName]` )[0].value
        chart.fileUpload.fileId = document.getElementsByName( `${prefix}__fileUpload[fileId]` )[0].value
        chart.fileUpload.sheetId = document.getElementsByName( `${prefix}__fileUpload[sheetId]` )[0].value
        chart.fileUpload.chartType = document.getElementsByName( `${prefix}__fileUpload[chartType]` )[0].value

        // Bail if no file, sheet Id or chart type
        if( ! chart.fileUpload.fileName ||  ! chart.fileUpload.fileId || ! chart.fileUpload.sheetId || ! chart.fileUpload.chartType   ) return

        // Remove extra traces if new spreasheet contains less columns than old spreasheet
        if (chart.traces) {
          const sheetIdInput =  document.getElementsByName(`${prefix}__fileUpload[sheetId]`)[0]
            if (spreadsheet[sheetIdInput.value].data.length < chart.traces.length) {
            for (let i = spreadsheet[sheetIdInput.value].data.length-1; i < chart.traces.length; i++ ) {
              delete chart.traces[i]
            }
          }
        }

        // Render input fields and set chart options
        drawChart( chart, spreadsheet, prefix )

        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .basicOptionsPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .configPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .tracesPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .titlePanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .legendPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .hoverlabelPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .modebarPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxisPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxis2Panel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxisPanel`).classList.remove( "hidden" )
        document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxis2Panel`).classList.remove( "hidden" )

        // Close all accordion panels
        mainAccordion.closeAll()

        document.querySelector( `.${prefix}__admin .loading` ).classList.add("hidden")

      } )

      document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .fileUploadPanel .ac-panel`).classList.remove( "hidden" )  
  
      // Create mainaccordion and open first panel
      const mainAccordion = new Accordion( `.${prefix}__admin .main__Acc`, { duration: 400 })
      mainAccordion.open(0)

      new Accordion( `.${prefix}__admin .xaxis__Acc`, { duration: 400 })
      new Accordion( `.${prefix}__admin .xaxis2__Acc`, { duration: 400 })
      new Accordion( `.${prefix}__admin .yaxis__Acc`, { duration: 400 })
      new Accordion( `.${prefix}__admin .yaxis2__Acc`, { duration: 400 })


    }

  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  }

}



// export default plotlyChart;


