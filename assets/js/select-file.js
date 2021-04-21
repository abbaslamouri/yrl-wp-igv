import fetchData from "./fetch-data";
import drawChart from "./draw-chart"
import {
  toggleElement,
  hideElement,
  showElement,
  setSheetId,
  setChartTypeId,
  displayAdminMessage,
  unhideInputField,
  chartOptionKey
} from "./utilities";

const selectFile = async function ( iwpgvObj, iwpgvCharts ) {

  toggleElement( `${iwpgvObj.prefix}__spinner` );
  toggleElement( `${iwpgvObj.prefix}__warning`);
  // hideElement( `${iwpgvObj.prefix}__plotlyChart`);


  try {

    // throw new Error( "Hello There")


    // Create a form data object
    const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

    // Bail if no foem is found
    if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

    // Create form object
    const formData = new FormData( form )

    // Add action and nonce to form
    formData.append("action", iwpgvObj.file_select_action);
    formData.append("nonce", iwpgvObj.file_select_nonce);

    //send ajax resquest
    const jsonRes = await fetchData(formData);

    console.log("JSONRESPONSE", jsonRes)

    // Display message if any
    if (jsonRes.message) displayAdminMessage(jsonRes.message);
    
    // Success handler
    if (jsonRes.status && jsonRes.status === "success") {

      // Set sheet Id select field
      setSheetId(jsonRes.spreadsheet, "", iwpgvObj);

      // Set chart type select field
      document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value = ""


      // Unhide sheed Id and chart type input fields
      unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) );
      unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) );

      // Add change event listener to all inputs with class containg iwpgvObj.prefix__chartParams
      document.addEventListener("change", async function (event) {

        if (event.target.closest("form").id === `${iwpgvObj.prefix}__chartOptionsForm` && event.target.id.includes(`${iwpgvObj.prefix}__chartParams`)) {

          event.preventDefault();
          console.log(event.target.checked)

          drawChart(event.target, iwpgvObj, iwpgvCharts, jsonRes);

        }
        
      });
      
    } else {
      throw new Error( jsonRes.message)
    }
  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } finally {
    toggleElement(`${iwpgvObj.prefix}__spinner`);
    toggleElement(`${iwpgvObj.prefix}__warning`);
  }

};

export default selectFile;
