import Accordion from "./Accordion"
// import ColorPicker from "./ColorPicker"
import ChartParams from "./ChartParams"
import ChartLayout from "./ChartLayout"
import mediaUploader from "./media-uploader"
import renderPanels from "./panels"
import saveChart from "./save-chart"
import listCharts from "./list-charts"
import { displayAdminMessage, appendFormSaveBtn } from "./utilities"
import "../sass/admin.scss"


let iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
let iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}

console.log("iwpgvObj", iwpgvObj)
console.log("iwpgvCharts", iwpgvCharts)




// Toggle Spinner, Wraning and Dasjbaord Div
// toggleElement( `${iwpgvObj.prefix}__spinner` );
// toggleElement( `${iwpgvObj.prefix}__warning `);
// hideElement( `${prefix}__plotlyChart`);

// const canvas = document.getElementById("main-canvas")

// canvas.width = window.innerWidth
// canvas.height = window.innerHeight

// const ctx = canvas.getContext("2d")
// ctx.beginPath()
// ctx.rect(25,5,200,100)
// ctx.fillStyle = 'teal'
// ctx.stroke()
// ctx.fill()

// ctx.beginPath()
// ctx.arc(400, 200, 100, 0, 2*Math.PI, )
// ctx.strokeStyle = "blue"
// ctx.fillStyle = 'yellow'
// ctx.stroke()
// ctx.fill()

try {

  // throw new Error( "Hello There")

  // Check if server error
  if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message );
  
  // Initialize parameters
  // const prefix = iwpgvObj.prefix ? iwpgvObj.prefix : ""
  // const chartId = iwpgvCharts.chartId
  // const chart = iwpgvCharts.chart
  // const charts = iwpgvCharts.charts
  // const action = iwpgvCharts.action

  // throw new Error( "Hello There")


  // Initialize panels
  const panels = {};

  // Assemble chart and panels chatparams
  // const chartParams = (chart.lenght && typeof( chart.chartParams )!== "undefined") ? chart.chartParams : {}
  panels.chartParams ={}
  const chartParamsInstance = new ChartParams( iwpgvCharts.chart.chartParams, iwpgvObj )
  panels.chartParams = chartParamsInstance.panel()
  iwpgvCharts.chart.chartParams = chartParamsInstance.options()

  if (iwpgvCharts.chartId) {

    // Assemble chart and panels chatparams
    // const chartParams = (chart.lenght && typeof( chart.chartParams )!== "undefined") ? chart.chartParams : {}
    panels.chartLayout = {}
    const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout, iwpgvObj )
    panels.chartLayout = chartLayoutInstance.panel()
    iwpgvCharts.chart.chartLayout = chartLayoutInstance.options()

  }

  // console.log("CHART",chart)
  // console.log("PANELS",panels)

  // throw new Error( "Hello There")

  // Render panels
  // renderPanels(panels, iwpgvObj.prefix)

  // Render accordion panels
  // if (Object.keys(iwpgvCharts).length) {

    // Add new or edit an existing chart
  if (iwpgvCharts.action && iwpgvCharts.action === "editChart") {

    // Display panels
    appendFormSaveBtn(document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm`), iwpgvObj)
    renderPanels(panels, iwpgvObj);
    

    // Add click event listener
    document.addEventListener("click", function (event) {

      // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
      if (! event.target.classList.contains( `${iwpgvObj.prefix}__chartParams`) || ! event.target.closest("form").id === `${iwpgvObj.prefix}__chartOptionsForm`) return
    
      // file uploader event listener
      if (event.target.id === `${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`) {
        event.preventDefault();
        mediaUploader( iwpgvObj );
      }

      // save chart
      if (event.target.id === `${iwpgvObj.prefix}__saveChart`) {
        event.preventDefault();
        saveChart();
      }


    });
  }

  // List all charts
  if (iwpgvCharts.action && iwpgvCharts.action === "listCharts") {
    listCharts( iwpgvCharts, iwpgvObj)
  }
  // }

} catch (error) {

  displayAdminMessage(error.message, "error",  iwpgvObj)
  console.log("CAUGHT ERROR", error)

} finally {

  // toggleElement( `${iwpgvObj.prefix}__spinner` )
  // showElement( `${iwpgvObj.prefix}__plotlyChart` )

}

// Load accordion
// const accordions = document.querySelectorAll(".accordion");
new Accordion( { collapsed: false } );
// console.log(accordion);
// accordion.accordion();
