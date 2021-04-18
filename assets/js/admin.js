import Accordion from "./accordion"
import ChartParams from "./ChartParams"
import ChartLayout from "./ChartLayout"
import mediaUploader from "./media-uploader"
import renderPanels from "./panels"
import saveChart from "./save-chart"
import listCharts from "./list-charts"
import {
  displayAdminMessage,
} from "./utilities"
import "../sass/admin.scss"


const iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
const iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}

console.log("OBJ", iwpgvObj)
console.log("CHART", iwpgvCharts)

try {

  // throw new Error( "Hello There")


  // Check if server error
  if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message );
  
  // Initialize parameters
  const prefix = iwpgvObj.prefix ? iwpgvObj.prefix : ""
  const chart = iwpgvCharts.chart
  const charts = iwpgvCharts.charts
  const action = iwpgvCharts.action

  // throw new Error( "Hello There")


  // Initialize panels
  const panels = { "chartParams" : {}, "chartLayout" : {}, "chartTraces" : {} };

  // Assemble chart and panels chatparams
  // const chartParams = (chart.lenght && typeof( chart.chartParams )!== "undefined") ? chart.chartParams : {}
  const chartParamsInstance = new ChartParams( chart.chartParams, prefix )
  panels.chartParams = chartParamsInstance.panel()
  chart.chartParams = chartParamsInstance.options()

  // Assemble chart and panels chatparams
  // const chartParams = (chart.lenght && typeof( chart.chartParams )!== "undefined") ? chart.chartParams : {}
  const chartLayoutInstance = new ChartLayout( chart.chartLayout, prefix )
  panels.chartLayout = chartLayoutInstance.panel()
  chart.chartLayout = chartLayoutInstance.options()

  console.log("CHART",chart)
  console.log("PANELS",panels)

  throw new Error( "Hello There")

  // Render panels
  // renderPanels(panels, prefix)

  // Render accordion panels
  if (Object.keys(iwpgvCharts).length) {

    // Add new or edit an existing chart
    if (action && action === "editChart") {
      renderPanels(panels, prefix);
    }

    // List all charts
    if (action && action === "listCharts") {
      listCharts(charts, prefix)
    }
  }

  // Add click event listener
  document.addEventListener("click", function (event) {
    
    // file uploader event listener
    if (event.target.id === `${prefix}__chartParams[mediaUploadBtn]`) {
      event.preventDefault();
      mediaUploader(iwpgvObj, prefix);
    }

    // save chart
    if (event.target.id === `${prefix}__saveChart`) {
      event.preventDefault();
      saveChart();
    }


  });

  // Load accordion
  // const accordions = document.querySelectorAll(".accordion");
  new Accordion({ collapsed: false });
  // console.log(accordion);
  // accordion.accordion();

} catch (error) {

  const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`
  displayAdminMessage(message)
  console.log(error)

} finally {

  // toggleElement( `${prefix}__spinner` )
  // showElement( `${prefix}__plotlyChart` )

}
