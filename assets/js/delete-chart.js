import cloneDeep from 'lodash.clonedeep'
import fetchData from "./fetch-data"
import { displayAdminMessage } from "./utilities"

const deleteChart = async function (chartId, charts, wpRestUrl, wpRestNonce, prefix) {

  try {

     // Bail if there are no chart tr
     if ( ! chartId ) throw new Error(  `Chart ID is required` )
    
    const newCharts = charts.filter(chart => chart.fileUpload.chartId != chartId)

    // charts = cloneDeep( newCharts )

    console.log("NEW", newCharts)
    
    const response = await fetch(wpRestUrl, {
      method: "POST",
      body: JSON.stringify(newCharts),
      headers: {'X-WP-Nonce': wpRestNonce }
    });
  
    // Convert response to json
    const jsonRes = await response.json();

    console.log("JSONRES-DELETE", jsonRes)

    // Bail is server response status = error
    if (response.status !== 200 ) throw new Error(  jsonRes.message )

    const card = document.getElementById(`${prefix}__chart__${chartId}__card`)
    document.querySelector(`#${prefix}__admin .chart-library__content`).removeChild(card)

    // Success handler
    displayAdminMessage("Chart deleted successfully", "success",  prefix)

    return newCharts

  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } 

}

export default deleteChart;
