import fetchData from "./fetch-data"
import { displayAdminMessage, showElementById, toggleInputField } from "./utilities"


// let jsonRes = {} // Server response

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
      
    

    // // Get from node
    // const form = document.getElementById( `${prefix}__chartOptionsForm` )

    // // Bail if no form is found
    // if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${prefix}__chartOptionsForm` )

    
    const response = await fetch(wpRestUrl, {
      method: "POST",
      body: JSON.stringify(charts),
      headers: {'X-WP-Nonce': wpRestNonce }
    });
  
    // Convert response to json
    const jsonRes = await response.json();
    // return jsonRes;

    console.log("JSONRES-SAVE", jsonRes)

    // Bail is server response status = error
    if (response.status !== 200 ) throw new Error(  jsonRes.message )

    // Update chart Id field
    // document.getElementsByName(`${prefix}__fileUpload[chartId]`)[0].value = jsonRes.chartId
    // chart.fileUpload.chartId =  jsonRes
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
