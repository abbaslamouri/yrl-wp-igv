import fetchData from "./fetch-data";
import drawChart from "./draw-chart"
import {
  toggleElement,
  hideElement,
  showElement,
  setSheetIdOptions,
  setChartTypeId,
  displayAdminMessage,
  unhideInputField,
  chartOptionKey
} from "./utilities";

const selectFile = async function ( attachment, iwpgvObj ) {


  try {

    // Get from 
    const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
 
    // Bail if no form is found
    if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

    // Create form object and append action and nonce
    const formData = new FormData( form )
    formData.append( "action", iwpgvObj.file_select_action );
    formData.append( "nonce", iwpgvObj.file_select_nonce );
    formData.append( "fileId", attachment.id );

    //send ajax resquest
    const jsonRes = await fetchData(formData)
    console.log("JSONRES-UPLOAD", jsonRes)
    
    // Bail is server response status = error
    if (jsonRes.status && jsonRes.status === "error") throw new Error(  jsonRes.message )

    // Set sheet Id select field options, update sheet Id select field values
    setSheetIdOptions(jsonRes.spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
    document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = ""

    // Update chart type select field
    document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value = ""

    return jsonRes.spreadsheet


  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } 

};

export default selectFile;
