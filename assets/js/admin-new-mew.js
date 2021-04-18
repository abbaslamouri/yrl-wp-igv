import Accordion from "./accordion"
import ChartParams from "./chartParams"
import mediaUploader from "./media-uploader"
import renderPanels from "./panels"
import drawChart from "./draw-chart"
import saveChart from "./save-chart"
import listCharts from "./list-charts"
import "../sass/admin.scss"


const iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
const iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}

const prefix = iwpgvObj.prefix ? iwpgvObj.prefix : ""
const chart = iwpgvCharts.chart
const charts = iwpgvCharts.charts
const action = iwpgvCharts.action

console.log("OBJ", {...iwpgvObj})
console.log("CHART", {...iwpgvCharts})

// Initialize panels
const panels = {}

// Assemble chart params and add to panels
const chartParams = (chart.lenght && typeof( chart.chartParams )!== "undefined") ? chart.chartParams : {}
const chartParamsInstance = new ChartParams( chartParams, prefix )
panels.chartParams = chartParamsInstance.panel()
chart.chartParams = chartParamsInstance.options()

// console.log("CHART",chart)
// console.log("PANELS",panels)

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

// // Add change event listener to all inputs with class containg prefix__chartParams
// document.addEventListener("change", async function (event) {
//   // Reset admin messages
//   const adminMessages = document.querySelector(`.${prefix} .admin-messages`);
//   if (adminMessages) adminMessages.innerHTML = "";

//   if (event.target.classList.contains(`${prefix}__chartParams`)) {
//     event.preventDefault();
//     drawChart(event.target, iwpgvObj, prefix, chart, panels);
//   }
// });

// // Add click event listener to field hint question  markss
// document.addEventListener("mouseover", async function (event) {
 
//   if (event.target.classList.contains("form-group__hintQMark")) {
//     event.preventDefault();
//     event.target.nextSibling.classList.remove("hidden")
//   }
// });

// // Add click event listener to field hint question  markss
// document.addEventListener("mouseout", async function (event) {
 
//   if (event.target.classList.contains("form-group__hintQMark")) {
//     event.preventDefault();
//     event.target.nextSibling.classList.add("hidden")
//   }
// });

// Load accordion
// const accordions = document.querySelectorAll(".accordion");
new Accordion({ collapsed: false });
// console.log(accordion);
// accordion.accordion();
