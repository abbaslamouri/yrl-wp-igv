import Plotly from 'plotly.js-basic-dist'
import renderPanels from "./render-panels"
import fixChart from "./fix-chart"
import saveChart from "./save-chart"
import { showElementById, toggleElementById, hideElementById, getMinMaxAvgData, fetchMinMaxAvgTableChartOptions } from "./utilities"

const renderChart = ( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  toggleElementById( `${iwpgvObj.prefix}__spinner` )

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

  
  renderPanels( iwpgvCharts, iwpgvObj, spreadsheet )

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false
  showElementById( `${iwpgvObj.prefix}__saveChart` ) 
  document.getElementById(`${iwpgvObj.prefix}__saveChart`).addEventListener("click", function (event) {  
    event.preventDefault()
    saveChart(iwpgvCharts.chart, iwpgvObj)
  })


  fixChart( iwpgvCharts, iwpgvObj, spreadsheet)

  
  

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

  

  // charParamsChangeHandler(jsonRes.spreadsheet, iwpgvCharts.chart, iwpgvObj)
   
  // Draw chart
  // drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj)
  

  // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  

  
}


export default renderChart
