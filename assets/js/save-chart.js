import fetchData from "./fetch-data"
import listCharts from "./list-charts"
import { displayAdminMessage } from "./utilities"

const saveChart = async function ( chart, charts, spreadsheet, sheets, pluginUrl, shortcodeText, wpRestUrl, wpRestNonce, mainAccordion, prefix ) {

  try {
    
    if ( ! Object.values(chart.traces).length || ! chart.params.fileId || ! chart.params.sheetId ) throw new Error(  `Chart traces as well as a file name and a sheet ID are required to save a chart` )

    document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")

    // get chart id
    if ( chart.params.chartId === null ) { // There is a chart Id (edit)
      const chartId = ! charts.length ? 16327 : charts[charts.length-1].params.chartId + 1
      chart.params.chartId = chartId
      charts.push( chart )
      sheets.push( {chartId, sheet: spreadsheet[chart.params.sheetId]} )
    }

    // Save charts
    const jsonRes = await fetchData( wpRestUrl, "POST", wpRestNonce, JSON.stringify(charts) ) 

    if ( ! Object.keys( jsonRes ).length  ) throw new Error(  `Something went terribly wrong. Chart may not have been saved` )

    // Remove the no-chart div it it exists
    if ( document.querySelector(`#${prefix}__admin .no-charts`) ) document.querySelector(`#${prefix}__admin .no-charts`).remove()

    // Clear chart library div
    document.querySelector(`#${prefix}__admin .chart-library__content`).innerHTML = ""

    // List all charts
    await listCharts( charts, sheets, pluginUrl, shortcodeText, wpRestUrl, wpRestNonce, mainAccordion, prefix)

    const chartUpdated = false
    return chartUpdated

  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default saveChart;
