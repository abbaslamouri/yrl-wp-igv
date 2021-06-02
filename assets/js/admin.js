import Plotly from 'plotly.js-dist'
import selectFile from './select-file'
import saveChart from './save-chart'
// import FileUpload from "./FileUpload"
// import panel from "./panel"
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import drawChart from "./draw-chart"
import listCharts from "./list-charts"
import { displayAdminMessage, toggleElementByClass, setSheetIdOptions, showInputField, hidePanels } from "./utilities"
import "../sass/admin.scss"

if (  typeof yrl_wp_igv_charts !== "undefined" ) {

  let iwpgvCharts = typeof yrl_wp_igv_charts !== undefined ?  yrl_wp_igv_charts : {}
  let iwpgvObj = typeof yrl_wp_igv_obj !== undefined ? yrl_wp_igv_obj : {}
  let mediaUploader
  let jsonRes = {}
  let chart = iwpgvCharts.chart
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
    if ( iwpgvCharts.action === "listCharts" ||  iwpgvCharts.action === "deleteChart" ) listCharts( iwpgvCharts.charts, iwpgvObj)

    // Add new chart or edit an existing chart
    if ( iwpgvCharts.action === "editChart" ) {
    
      if ( chart.fileUpload && chart.fileUpload.chartId) {

        // spreadsheet = iwpgvCharts.spreadsheet
        
        // Set sheetId select field value and options 
        if ( spreadsheet) {
          setSheetIdOptions(spreadsheet, document.getElementById(`${prefix}__fileUpload[sheetId]`))
          document.getElementById(`${prefix}__fileUpload[sheetId]`).value = chart.fileUpload.options.sheetId
          // showInputField( `${prefix}__fileUpload[sheetId]` )
        }

        // Show remaining chart params fields
        // if ( chart.fileUpload && chart.fileUpload.chartId ) showInputField( `${prefix}__fileUpload[chartId]` )
        // showInputField( `${prefix}__fileUpload[fileUpload]` )
        // showInputField( `${prefix}__fileUpload[chartType]` )
        
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
        // toggleElementByClass( `.${prefix}__admin .warning` )
        // toggleElementByClass( `.${prefix}__admin .loading` )

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
        chart.fileUpload = {}

        // Set sheet Id select field options, update sheet Id select field values
        const sheetIdInput = document.getElementsByName( `${prefix}__fileUpload[sheetId]` )[0]
        setSheetIdOptions (spreadsheet, sheetIdInput )
        console.log(sheetIdInput.options)
        sheetIdInput.selectedIndex = sheetIdInput.options.length == 2 ? 1 : ""

        // Set chart type value
        document.getElementsByName( `${prefix}__fileUpload[chartType]` )[0].value = ""

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
        saveChart(iwpgvCharts.chart, iwpgvObj)
        return false
      })

      // Add change event listener to all input fields
      document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm`).addEventListener( "change", function (event) {

        // Bail if the clicked item is not inside the `${iwpgvCharts.prefix}__chartOptionsForm` form 
        if ( ! event.target.classList.contains(`fileUpload`)  ) return

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

        // Close all accordion panels
        mainAccordion.closeAll()

      } )

    }

  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  }
  
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .fileUploadPanel .ac-panel`).classList.remove( "hidden" )  

  // Create mainaccordion and open first panel
  const mainAccordion = new Accordion( `.${prefix}__admin .main__Acc`, { duration: 400 })
  mainAccordion.open(0)

 }



// export default plotlyChart;


