import Plotly from 'plotly.js-dist'
import renderPanels from "./render-panels"
import renderChart from "./render-chart"
import saveChart from "./save-chart"
import { showElementById, toggleElementById, hideElementById, getMinMaxAvgData, fetchMinMaxAvgTableChartOptions } from "./utilities"

const drawChart = ( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  // toggleElementById( `${iwpgvObj.prefix}__spinner` )

  // Hide chart and table charts
  Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // Hide min/max inputs if visible
  hideElementById( `${iwpgvObj.prefix}__plotMinMax` )

  // remove layout panel toggle and panel
  document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false
  showElementById( `${iwpgvObj.prefix}__saveChart` ) 
  // document.getElementById(`${iwpgvObj.prefix}__saveChart`).addEventListener("click", function (event) {  
  //   event.preventDefault()
  //   saveChart(iwpgvCharts.chart, iwpgvObj)
  // })

  
  renderPanels( iwpgvCharts, iwpgvObj, spreadsheet )

  
  renderChart( iwpgvCharts, iwpgvObj, spreadsheet)

  
}


export default drawChart
