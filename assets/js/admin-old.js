import Plotly from 'plotly.js-basic-dist'
import Accordion from "./Accordion"
import ChartParams from "./ChartParams"
import mediaUploader from "./media-uploader"
import renderPanel from "./render-panel"
import drawChart from "./draw-chart";
import listCharts from "./list-charts"
import { displayAdminMessage, showElementById, hideElementById, showInputField, setSheetIdOptions, charParamsChangeHandler } from "./utilities"
import "../sass/admin.scss"


let ChartsObj = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
let pluginObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}


console.log("pluginObj", {...pluginObj})
console.log("ChartsObj", {...ChartsObj})

try {

  // Check if server error
  if ( ChartsObj.status === "error" ) throw new Error( ChartsObj.message )

  // List all charts
  if (ChartsObj.action && ChartsObj.action === "listCharts") {

    listCharts( ChartsObj.charts, pluginObj)

  } 
  
  // Add new chart or edit an existing chart
  if ( ChartsObj.action && ChartsObj.action === "editChart" ) {

    // Initialize chart and panels
    const chart = ChartsObj.chart

    // Assemble chart Params chart and panels
    const chartParamsInstance = new ChartParams( chart.chartParams.options, pluginObj )
    chart.chartParams.options = chartParamsInstance.options()
    chart.chartParams.panel.sections = chartParamsInstance.sections()

    // Render chart params panel
    renderPanel(chart.chartParams.panel, pluginObj)

    // Show the panel toogle and content divs
    document.querySelector( `.accordion__toggle.chartParams` ).classList.remove("hidden")
    document.querySelector( `.accordion__content.chartParams` ).classList.remove("hidden")


    if (ChartsObj.spreadsheet) { // Edit an existing chart

      // Set sheet Id select field options, update sheet Id select field values
   
      setSheetIdOptions(ChartsObj.spreadsheet, document.getElementById(`${pluginObj.prefix}__chartParams[sheetId]`))
      document.getElementById(`${pluginObj.prefix}__chartParams[sheetId]`).value = chart.chartParams.options.sheetId

      // Show the remaining input fields in the chart params panel
      // showInputField( document.getElementById(`${pluginObj.prefix}__fileUpload]`) )
      showInputField( document.getElementById(`${pluginObj.prefix}__chartParams[sheetId]`) )
      showInputField( document.getElementById(`${pluginObj.prefix}__chartParams[chartType]`) )
      showInputField( document.getElementById(`${pluginObj.prefix}__chartParams[enableRangeSlider]`) )
      showInputField( document.getElementById(`${pluginObj.prefix}__chartParams[enableTableChart]`) )
      showInputField( document.getElementById(`${pluginObj.prefix}__chartParams[enableMinMaxTableChart]`) )
     

      // Call draw chart
      drawChart(ChartsObj.spreadsheet, ChartsObj, pluginObj)
      console.log("QWQWQWQW")

      // Add change event listener to all chart Params inputs
      document.addEventListener( "change", charParamsHandler )

    }

    // Show saveChart button
    showElementById (`${pluginObj.prefix}__saveChart` )
      
    // Add click event listener to the chart params panel inoput fields
    document.getElementById(`${pluginObj.prefix}__chartParams[mediaUploadBtn]`).addEventListener("click", function (event) {
     
      event.preventDefault()
      
      // HIde chart and table charts
      Plotly.purge(`${pluginObj.prefix}__plotlyChart`)
      Plotly.purge(`${pluginObj.prefix}__plotlyTable`)
      Plotly.purge(`${pluginObj.prefix}__plotlyMinMaxTable`)

      // Hide min and max input fields
      hideElementById (`${pluginObj.prefix}__plotMinMax` )

      mediaUploader( ChartsObj, pluginObj )
          
    })

  }


} catch (error) {

  displayAdminMessage(error.message, "error",  pluginObj)
  console.log("CAUGHT ERROR", error)

} 

// Load accordion
new Accordion( { collapsed: false } )



const charParamsHandler = (event) => {

  // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
  if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return

  // Bail if no file, sheet Id or chart type
  if( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value ||  ! document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value || ! document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value   ) return

  // HIde chart and table charts
  Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  charParamsChangeHandler(jsonRes.spreadsheet, iwpgvCharts.chart, iwpgvObj)
   
  // Draw chart
  drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj)

  // Remove change event listener to all chart Params inputs
  document.removeEventListener( "change", charParamsHandler );

}




