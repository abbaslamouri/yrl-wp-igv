import Plotly from 'plotly.js-dist'
import cloneDeep from 'lodash.clonedeep'
import fetchData from "./fetch-data"
import editChart from "./edit-chart"
import deleteChart from "./delete-chart"
import { displayAdminMessage, createChartCard } from "./utilities"


const saveChart = async function ( chart, charts, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix ) {

  try {

    // Bail if there are no chart traces, a file or a sheet id
    if ( ! Object.values(chart.traces).length || ! chart.params.fileId || ! chart.params.sheetId ) throw new Error(  `Chart traces as well as a file name and a sheet ID are required to save a chart` )

    if ( chart.params.chartId === undefined ) { // There is a chart Id (edit)
      const chartId = ! charts.length ? 16327 : charts[charts.length-1].params.chartId + 1
      chart.params.chartId = chartId
      charts.push( chart )
      document.getElementById(`${prefix}__params[chartId]`).value = chartId
    }

    const response = await fetchData( wpRestUrl, "POST", wpRestNonce, JSON.stringify(charts) ) 
  
    // Convert response to json
    const jsonRes = await response.json();

    console.log("JSONRES-SAVE", jsonRes)

    // Bail is server response status = error
    if (response.status !== 200 ) throw new Error( jsonRes.message )





    // await saveChart( chart, charts, wpRestUrl, wpRestNonce, prefix )
    document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")

    const noChartsContaine = document.querySelector(`#${prefix}__admin .no-charts`)
    if ( noChartsContaine ) noChartsContaine.remove()

    createChartCard(chart, pluginUrl, `#${prefix}__admin .chart-library__content`, prefix)

    const newChart = cloneDeep( chart )

    newChart.layout.showlegend = false
    newChart.layout.hovermode = false
    newChart.layout.height = 300
    newChart.config.displayModeBar = false

    await Plotly.newPlot(`${prefix}__chart__${chart.params.chartId}`, newChart.traces, newChart.layout, newChart.config)




    // Success handler
    displayAdminMessage("Chart saved successfully", "success",  prefix)


    document.querySelectorAll( `#${prefix}__admin .card__edit-chart`).forEach( ( element ) => {

      element.addEventListener("click", async function (event) {  
        event.preventDefault()
  
        // Get chart Id
        // const chartId = event.target.closest(".card__edit-chart").dataset.chartId
        editChart( charts, chart.params.chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix )
  
      })
  
    })
  
  
    document.querySelectorAll( `#${prefix}__admin .card__delete-chart`).forEach( ( element ) => {
  
      element.addEventListener("click", async function (event) {  
        event.preventDefault()
  
        // Get chart Id
        // const chartId = event.target.closest(".card__delete-chart").dataset.chartId
        deleteChart(charts, chart.params.chartId, wpRestUrl, wpRestNonce, prefix)
  
      })
  
    })



  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

};

export default saveChart;
