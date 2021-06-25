import fetchData from "./fetch-data"
import { displayAdminMessage, showElementById, toggleInputField } from "./utilities"


// let jsonRes = {} // Server response

const saveChart = async function ( chart, wpRestUrl, wpRestNonce, prefix ) {

  try {

    // Bail if there are no chart traces, a file or a sheet id
    if ( ! Object.values(chart.traces).length || ! chart.fileUpload.fileId || ! chart.fileUpload.sheetId ) throw new Error(  `Chart traces as well as a file name and a sheet ID are required to save a chart` )
    

    // // Get from node
    // const form = document.getElementById( `${prefix}__chartOptionsForm` )

    // // Bail if no form is found
    // if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${prefix}__chartOptionsForm` )

    
    const response = await fetch(wpRestUrl, {
      method: "POST",
      body: JSON.stringify(chart),
      headers: {'X-WP-Nonce': wpRestNonce }
    });
  
    // Convert response to json
    const jsonRes = await response.json();
    // return jsonRes;

    console.log("JSONRES-SAVE", jsonRes)

    // Bail is server response status = error
    if (response.status !== 200 ) throw new Error(  jsonRes.message )

    // Update chart Id field
    document.getElementsByName(`${prefix}__fileUpload[chartId]`)[0].value = jsonRes.chartId
    chart.fileUpload.chartId =  jsonRes
    // toggleInputField(`${prefix}__fileUpload[chartId]`)

    // Success handler
    displayAdminMessage("Chart saved successfully", "success",  prefix)


  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } finally {
    
     // Hide warning and show spinner
    //  toggleElementById( `${prefix}__spinner` );
    //  showElementById( `${prefix}__warning`);

  }
};

export default saveChart;
