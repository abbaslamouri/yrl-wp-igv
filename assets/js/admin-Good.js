import Plotly from 'plotly.js-dist'
// import Accordion from "./Accordion"
import selectFile from './select-file'
import saveChart from './save-chart'
import ChartParams from "./ChartParams"
import panel from "./panel"
import drawChart from "./draw-chart"
import listCharts from "./list-charts"
import { displayAdminMessage, toggleElementByClass, setSheetIdOptions, showInputField, hidePanels } from "./utilities"
import "../sass/admin.scss"

import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';

// const plotlyChart = () => {

if (  typeof yrl_wp_igv_charts !== "undefined" ) {

  let iwpgvCharts = typeof yrl_wp_igv_charts !== undefined ?  yrl_wp_igv_charts : {}
  let iwpgvObj = typeof yrl_wp_igv_obj !== undefined ? yrl_wp_igv_obj : {}
  let mediaUploader
  let spreadsheet = []
  // let oldIwpgvCharts = Object.assign({}, iwpgvCharts)

  console.log("iwpgvObj", {...iwpgvObj})
  console.log("iwpgvCharts", {...iwpgvCharts})


  try {

    // throw new Error( "iwpgvObj && iwpgvCharts missing")

    if ( ! Object.values( iwpgvObj ) || ! Object.values( iwpgvCharts ).length ) throw new Error( "iwpgvObj && iwpgvCharts missing")

    // Check if server error
    if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message )

    // check if action is valid
    if (! iwpgvCharts.action && iwpgvCharts.action !== "listCharts" && iwpgvCharts.action !== "editChart" ) throw new Error( "Invalid action" )

    // List all charts
    if (iwpgvCharts.action && ( iwpgvCharts.action === "listCharts" ||  iwpgvCharts.action === "deleteChart" ) ) {

      listCharts( iwpgvCharts.charts, iwpgvObj)

    }
    
    // Add new chart or edit an existing chart
    if ( iwpgvCharts.action && iwpgvCharts.action === "editChart" ) {

      // Assemble chart Params chart and panels
      const chartParamsInstance = new ChartParams( iwpgvCharts.chart.chartParams.options, iwpgvObj )
      iwpgvCharts.chart.chartParams.options = chartParamsInstance.options()
      iwpgvCharts.chart.chartParams.panel.sections = chartParamsInstance.sections()
      panel(iwpgvCharts.chart.chartParams.panel, iwpgvObj)
      document.querySelector( `.accordion__toggle.chartParams` ).classList.remove("hidden")
      document.querySelector( `.accordion__content.chartParams` ).classList.remove("hidden")
      // document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).readOnly = true
    
      if ( iwpgvCharts.chart.chartParams.options.chartId) {

        spreadsheet = iwpgvCharts.spreadsheet
        
        // Set sheetId select field value and options 
        if ( spreadsheet) {
          setSheetIdOptions(spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`))
          document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = iwpgvCharts.chart.chartParams.options.sheetId
          showInputField( `${iwpgvObj.prefix}__chartParams[sheetId]` )
        }

        // Show remaining chart params fields
        if ( iwpgvCharts.chart.chartParams.options.chartId ) showInputField( `${iwpgvObj.prefix}__chartParams[chartId]` )
        showInputField( `${iwpgvObj.prefix}__chartParams[fileUpload]` )
        showInputField( `${iwpgvObj.prefix}__chartParams[chartType]` )
        
        // Draw chart
        drawChart( iwpgvCharts, iwpgvObj, spreadsheet )
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

        toggleElementByClass( `.${iwpgvObj.prefix}__admin .spinner` )
        toggleElementByClass( `.${iwpgvObj.prefix}__admin .warning` )
        toggleElementByClass( `.${iwpgvObj.prefix}__admin .loading` )

        // Hide all but chart params panels
        hidePanels()

        // Hide admin messages
        document.querySelector(`.${iwpgvObj.prefix}__admin .admin-messages`).innerHTML = ""

        // Hide chart and table charts
        Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
        // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
        Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxAvgTable`)

        // Hide min/max inputs if visible
        // hideElementById( `${iwpgvObj.prefix}__plotMinMax` )

        document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = true

        // Get attachment
        const attachment = mediaUploader.state().get("selection").first().toJSON()

        // Bail if attachment can't be found
        if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

        // Update selected file and file Id
        document.getElementsByName(`${iwpgvObj.prefix}__chartParams[fileUpload]`)[0].value = attachment.filename
        document.getElementsByName(`${iwpgvObj.prefix}__chartParams[fileId]`)[0].value = attachment.id

        // Fetch spreadsheet
        spreadsheet = await selectFile( attachment, iwpgvObj )

        // Set sheetId select field options and show it
        setSheetIdOptions (spreadsheet, document.getElementsByName( `${iwpgvObj.prefix}__chartParams[sheetId]` )[0] )
        showInputField( `${iwpgvObj.prefix}__chartParams[sheetId]` )

        // Show remaining chart params fields
        showInputField( `${iwpgvObj.prefix}__chartParams[fileUpload]` )
        showInputField( `${iwpgvObj.prefix}__chartParams[chartType]` )

        toggleElementByClass( `.${iwpgvObj.prefix}__admin .spinner` )
        toggleElementByClass( `.${iwpgvObj.prefix}__admin .warning` )
        toggleElementByClass( `.${iwpgvObj.prefix}__admin .loading` )


      })


      // Add click event listener to the chart params panel inoput fields
      document.getElementsByName(`${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`)[0].addEventListener("click", async function (event) {
        event.preventDefault()
        mediaUploader.open()      
      })


      document.getElementById(`${iwpgvObj.prefix}__saveChart`).addEventListener("click", function (event) {  
        event.preventDefault()
        saveChart(iwpgvCharts.chart, iwpgvObj)
        return false
      })


      document.addEventListener( "change", function (event) {

        // Bail if the clicked item is not inside the `${iwpgvCharts.prefix}__chartOptionsForm` form 
        if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return

        // Update chart params options
        iwpgvCharts.chart.chartParams.options.fileUpload = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value
        iwpgvCharts.chart.chartParams.options.sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value
        iwpgvCharts.chart.chartParams.options.chartType = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value
        // iwpgvCharts.chart.chartParams.options.enableRangeSlider = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`).checked
        // iwpgvCharts.chart.chartParams.options.enableTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`).checked
        // iwpgvCharts.chart.chartParams.options.enableMinMaxTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`).checked




        // if ( ! iwpgvCharts.chart.chartParams.options.enableMinMaxTableChart ) {
        //   document.querySelector( `.accordion__toggle.minMaxAvgTableChart.panel` ).classList.add("hidden")
        //   document.querySelector( `.accordion__content.minMaxAvgTableChart.panel` ).classList.add("hidden")
        // }



        // iwpgvCharts.chart.chartLayout.options.xaxis = {rangeslider: {visible: iwpgvCharts.chart.chartParams.options.enableRangeSlider}}


        // Bail if no file, sheet Id or chart type
        if( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value ||  ! document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value || ! document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value   ) return

        // Remove extra traces if new spreasheet contains less columns than old spreasheet
        const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
          if (spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.chart.chartTraces.options.length) {
          // console.log("HERE")
          for (let i = spreadsheet[sheetIdInput.value].data.length-1; i < iwpgvCharts.chart.chartTraces.options.length; i++ ) {
            delete iwpgvCharts.chart.chartTraces.options[i]
            delete iwpgvCharts.chart.chartTraces.panel.sections[i]
          }
        }



        // document.addEventListener( "change", chartParamsHandler )






        // console.log("======", jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId], iwpgvCharts.chart.chartTraces.options)

        // const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
        // // if (iwpgvCharts.chart.chartTraces.options.length && iwpgvCharts.chart.chartParams.options.sheetId && iwpgvCharts.jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId] && jsonResjsonRes..spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length) {
        //   if (jsonRes.spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.chart.chartTraces.options.length) {
        //   // console.log("HERE")
        //   for (let i = jsonRes.spreadsheet[sheetIdInput.value].data.length-1; i < iwpgvCharts.chart.chartTraces.options.length; i++ ) {
        //     delete iwpgvCharts.chart.chartTraces.options[i]
        //     delete iwpgvCharts.chart.chartTraces.panel.sections[i]
        //   }
        // }

        

        
        drawChart( iwpgvCharts, iwpgvObj, spreadsheet )


      } )



    }


  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } 

  // Load accordion
  // new Accordion( { collapsed: false }, iwpgvObj )
  const accordion1Options = { 
    // elementClass: "accordion-1__Panel",
    // triggerClass: "accordion-1__Trigger",
    // panelClass: "accordion-1__Content",
    // activeClass: "isActive",
    // duration: 400,
  }
  const accordion1 = new Accordion( '.accordion-1', accordion1Options );
  accordion1.openAll()




 }



// export default plotlyChart;


