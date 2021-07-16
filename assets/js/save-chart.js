import Plotly from 'plotly.js-dist'
import cloneDeep from 'lodash.clonedeep'
import fetchData from "./fetch-data"
import editChart from "./edit-chart"
import deleteChart from "./delete-chart"
import listCharts from "./list-charts"

import { displayAdminMessage, createChartCard } from "./utilities"


const saveChart = async function ( chart, charts, spreadsheet, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix ) {

  try {

    // Bail if there are no chart traces, a file or a sheet id
    if ( ! Object.values(chart.traces).length || ! chart.params.fileId || ! chart.params.sheetId ) throw new Error(  `Chart traces as well as a file name and a sheet ID are required to save a chart` )

    document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")

    // get chart id
    if ( chart.params.chartId === null ) { // There is a chart Id (edit)
      chart.params.chartId = ! charts.length ? 16327 : charts[charts.length-1].params.chartId + 1
      charts.push( chart )
      document.getElementById(`${prefix}__params[chartId]`).value = chart.params.chartId
      sheets.push( {chartId, sheet: spreadsheet[chart.params.sheetId]} )
    }

   
    // Save charts
    const response = await fetchData( wpRestUrl, "POST", wpRestNonce, JSON.stringify(charts) ) 
    const jsonRes = await response.json();
    console.log("JSONRES-SAVE", jsonRes)

    // Bail is server response status = error
    if (response.status !== 200 ) throw new Error( jsonRes.message )

    // Remove the no-chart div it it exists
    if ( document.querySelector(`#${prefix}__admin .no-charts`) ) document.querySelector(`#${prefix}__admin .no-charts`).remove()

    document.querySelector(`#${prefix}__admin .chart-library__content`).innerHTML = ""

     // List all charts
    //  await listCharts( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix)

    // return 

    // Create a new chart card
    createChartCard(chart, pluginUrl, `#${prefix}__admin .chart-library__content`, prefix)

    // Clone chart
    const newChart = cloneDeep( chart )

    // Ser card chart default layout
    // newChart.layout.showlegend = false
    // newChart.layout.hovermode = false
    // newChart.layout.height = 300
    // newChart.config.displayModeBar = false

    newChart = fetchChartListDefaultOptions( newChart )

    // Plot card chart
    await Plotly.newPlot(`${prefix}__chart__${chart.params.chartId}`, newChart.traces, newChart.layout, newChart.config)

    // Success handler
    displayAdminMessage("Chart saved successfully", "success",  prefix)

    // document.querySelectorAll( `#${prefix}__admin .card__edit-chart`).forEach( ( element ) => {

    //   element.addEventListener("click", async function (event) {  
    //     event.preventDefault()
  
    //     // Get chart Id and edit chart
    //     const chartId = event.target.closest('.card__edit-chart').dataset.chartId
    //     const chart = charts.filter(element => element.params.chartId == chartId)[0]
    //     editChart( chart, chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix )
  
    //   })
  
    // })
  
  
    // document.querySelectorAll( `#${prefix}__admin .card__delete-chart`).forEach( ( element ) => {
  
    //   element.addEventListener("click", async function (event) {  
    //     event.preventDefault()
  
    //     // Get chart Id
    //     const chartId = event.target.closest(".card__delete-chart").dataset.chartId
    //     const chart = charts.filter(element => element.params.chartId == chartId)[0]
    //     deleteChart(charts, chartId, wpRestUrl, wpRestNonce, prefix)
  
    //   })
  
    // })


    // document.querySelector( `#${prefix}__admin .card__edit-chart`).forEach( ( element ) => {

    //   element.addEventListener("click", async function (event) {  
    //     event.preventDefault()
  
    //     // Get chart Id
    //     // const chartId = event.target.closest(".card__edit-chart").dataset.chartId
    //     editChart( charts, chart.params.chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix )
  
    //   })
  
    // })
  
  
    // document.querySelectorAll( `#${prefix}__admin .card__delete-chart`).forEach( ( element ) => {
  
    //   element.addEventListener("click", async function (event) {  
    //     event.preventDefault()
  
    //     // Get chart Id
    //     // const chartId = event.target.closest(".card__delete-chart").dataset.chartId
    //     deleteChart(charts, chart.params.chartId, wpRestUrl, wpRestNonce, prefix)
  
    //   })
  
    // })



  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default saveChart;
