import fetchData from "./fetch-data"
import { displayAdminMessage } from "./utilities"


const saveChart = async function ( chart, charts, wpRestUrl, wpRestNonce, prefix ) {

  try {

    // Bail if there are no chart traces, a file or a sheet id
    if ( ! Object.values(chart.traces).length || ! chart.fileUpload.fileId || ! chart.fileUpload.sheetId ) throw new Error(  `Chart traces as well as a file name and a sheet ID are required to save a chart` )

    if ( chart.fileUpload.chartId === undefined ) { // There is a chart Id (edit)
      const chartId = ! charts.length ? 16327 : charts[charts.length-1].fileUpload.chartId + 1
      chart.fileUpload.chartId = chartId
      charts.push( chart )
      document.getElementById(`${prefix}__fileUpload[chartId]`).value = chartId
    }

    const response = await fetchData( wpRestUrl, "POST", wpRestNonce, JSON.stringify(charts) ) 
      
    // const response = await fetch(wpRestUrl, {
    //   method: "POST",
    //   body: JSON.stringify(charts),
    //   headers: {'X-WP-Nonce': wpRestNonce }
    // });
  
    // Convert response to json
    const jsonRes = await response.json();

    console.log("JSONRES-SAVE", jsonRes)

    // Bail is server response status = error
    if (response.status !== 200 ) throw new Error( jsonRes.message )

    // Success handler
    displayAdminMessage("Chart saved successfully", "success",  prefix)

  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

};

export default saveChart;
