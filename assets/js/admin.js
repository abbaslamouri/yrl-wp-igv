import Accordion from "./Accordion"
import ChartParams from "./ChartParams"
import mediaUploader from "./media-uploader"
import renderPanel from "./renderPanel"
import saveChart from "./save-chart"
import listCharts from "./list-charts"
import { displayAdminMessage, showElementById } from "./utilities"
import "../sass/admin.scss"


let iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
let iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}

console.log("iwpgvObj", iwpgvObj)
console.log("iwpgvCharts", {...iwpgvCharts})

try {

  // Check if server error
  if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message )

  // List all charts
  if (iwpgvCharts.action && iwpgvCharts.action === "listCharts") {

    listCharts( iwpgvCharts, iwpgvObj)

  } else {
  
    // Initialize chart and panels
    const chart = iwpgvCharts.chart
    for(const prop in chart) {
      chart[prop].options = {...chart[prop].options}
      chart[prop].panel.sections = {...chart[prop].panel.sections}
    }

    // Assemble chart Params chart and panels
    const chartParamsInstance = new ChartParams( chart.chartParams.options, iwpgvObj )
    chart.chartParams.options = chartParamsInstance.options()
    chart.chartParams.panel.sections = chartParamsInstance.sections()

    // Render chart params panel
    renderPanel(chart.chartParams.panel, iwpgvObj)

      // Add new or edit an existing chart
    if (iwpgvCharts.action && iwpgvCharts.action === "editChart") {

      showElementById (`${iwpgvObj.prefix}__saveChart` )
      
      // Add click event listener to the chart params panel inoput fields
      document.addEventListener("click", function (event) {

        // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
        if (! event.target.classList.contains( `${iwpgvObj.prefix}__chartParams`) || ! event.target.closest("form").id === `${iwpgvObj.prefix}__chartOptionsForm`) return
      
        // file uploader event listener
        if (event.target.id === `${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`) {
          event.preventDefault()
          mediaUploader( chart, iwpgvObj )
        }
        
        // Save chart event handler
        if (event.target.id === `${iwpgvObj.prefix}__saveChart`) {
          event.preventDefault()
          saveChart()
        }
        
      })

    }

  }


} catch (error) {

  displayAdminMessage(error.message, "error",  iwpgvObj)
  console.log("CAUGHT ERROR", error)

} 

// Load accordion
new Accordion( { collapsed: false } )

