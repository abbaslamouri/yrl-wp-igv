import fetchData from "./fetch-data"
import { displayAdminMessage, showElementById } from "./utilities"


// let jsonRes = {} // Server response

const saveChart = async function (chart, iwpgvObj) {

  try {

    // Bail if there are no chart traces, a file or a sheet id
    if (! Object.values(chart.chartTraces.options).length || ! chart.chartParams.options.fileUpload || ! chart.chartParams.options.sheetId ) {
      throw new Error(  `Chart traces as well as a file name and a sheet ID are required to save a chart` )
    }

    // Get from node
    const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

    // Bail if no form is found
    if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

    // Create form object and append action and nonce
    const formData = new FormData( form )
    formData.append("action", iwpgvObj.save_chart_action)
    formData.append("nonce", iwpgvObj.save_chart_nonce)
    formData.append("chart", chart)

    //send ajax resquest
    const jsonRes = await fetchData(formData)

    console.log("JSONRES-SAVE", jsonRes)

    // Bail is server response status = error
    if (jsonRes.status && jsonRes.status === "error ") throw new Error(  jsonRes.message )

    // Update chart Id field
    document.getElementById(`${iwpgvObj.prefix}__chartParams[chartId]`).value = jsonRes.chartId
    showElementById(`${iwpgvObj.prefix}__chartParams[chartId]`)

    // Success handler
    if (jsonRes.status && ( jsonRes.status === "success" || jsonRes.status === "unchanged" ) ) displayAdminMessage(jsonRes.message, "success",  iwpgvObj)

    console.log("PPPP",jsonRes.status)



  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } finally {
    
     // Hide warning and show spinner
    //  toggleElementById( `${iwpgvObj.prefix}__spinner` );
    //  showElementById( `${iwpgvObj.prefix}__warning`);

  }
};

export default saveChart;
