import fetchData from "./fetch-data"
import { displayAdminMessage } from "./utilities"


let jsonRes = {} // Server response

const deleteChart = async function (chartId, iwpgvObj) {

  try {
    
    // Bail if there are no chart traces, a file or a sheet id
    if ( ! chartId ) throw new Error(  `Chart ID is required` )

    // Create form object and append action and nonce
    const formData = new FormData( )
    formData.append("action", iwpgvObj.delete_chart_action)
    formData.append("nonce", iwpgvObj.delete_chart_nonce)
    formData.append("chartId", chartId)

    //send ajax resquest
    jsonRes = await fetchData(formData)

    console.log("JSONRES-DELETE-CHART", jsonRes)

    // Bail is server response status = error
    if (jsonRes.status && jsonRes.status === "error ") throw new Error(  jsonRes.message )

    // Update chart Id field
    // document.getElementById(`${iwpgvObj.prefix}__chartParams[chartId]`).value = jsonRes.chartId

    // Success handler
    return jsonRes


  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } finally {
    
     // Hide warning and show spinner
    //  toggleElementById( `${iwpgvObj.prefix}__spinner` );
    //  showElementById( `${iwpgvObj.prefix}__warning`);

  }
};

export default deleteChart;
