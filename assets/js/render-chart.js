import Plotly from 'plotly.js-basic-dist'
import renderPanels from "./render-panels"
import fixChart from "./fix-chart"
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

  // Enable save button
  document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false
  showElementById( `${iwpgvObj.prefix}__saveChart` ) 

  fixChart( iwpgvCharts, iwpgvObj, spreadsheet)
  

  // Add range slider event handler
  yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
    const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data[0])
    const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data[0])
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
    const cellsValues = getMinMaxAvgData({...iwpgvCharts.chart }, spreadsheet, x_start, x_end)
    // iminMaxAvgTableChart.options.cells.values = getMinMaxAvgData(iwpgvCharts.chart, spreadsheet, x_start, x_end)
    // const minMaxAvgTableChartOptions = fetchMinMaxAvgTableChartOptions( iwpgvCharts.chart, spreadsheet )
    console.log("?????", cellsValues, x_start )
    console.log("CCCCCC", iwpgvCharts.chart.minMaxAvgTableChart.options.cells.values)
    // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)
    Plotly.restyle(`${iwpgvObj.prefix}__plotlyMinMaxTable`,  {"cells.values": cellsValues } )
    // Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [minMaxAvgTableChartOptions])
  })

  
  

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
