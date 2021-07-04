import Plotly from 'plotly.js-dist'
import panels from "./panels"
import setParamsFields from './set-params-fields'
import { displayAdminMessage } from "./utilities"

const editChart = async function ( charts, chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix ) {

  mainAccordion.close(0)
  Plotly.purge(`${prefix}__plotlyChart`)
  Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)

  document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")

  document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )
  document.querySelector( `#${prefix}__admin .loading` ).classList.remove( `hidden` )

  try {

    const chart = charts.filter(chart => chart.params.chartId == chartId)[0]

    const spreadsheet = await setParamsFields( chart.params.fileName, chart.params.fileId, chart.params.sheetId, chart.params.chartType, chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix )

    Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config ).then( ( ) => {

      // document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )
      document.querySelector( `#${prefix}__admin .loading` ).classList.add( `hidden` )

      panels( chart, spreadsheet, prefix )

    } )

  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } 

}

export default editChart
