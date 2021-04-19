import fetchData from "./fetch-data";
import drawChart from "./draw-chart"
import {
  toggleElement,
  hideElement,
  showElement,
  setSheetId,
  setChartTypeId,
  displayAdminMessage,
  chartOptionKey
} from "./utilities";

const selectFile = async function (iwpgvObj, prefix) {

  toggleElement( `${prefix}__spinner` );
  toggleElement( `${prefix}__warning`);
  // hideElement( `${prefix}__plotlyChart`);


  try {

    // Create a form data object
    const form = document.getElementById( `${prefix}__chartOptionsForm` )

    // Bail if no foem is found
    if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${prefix}__chartOptionsForm` )

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
      setSheetId(jsonRes.spreadsheet, "");

      // Deselect chart type select field
      setChartTypeId("");

      // Add change event listener to all inputs with class containg prefix__chartParams
      document.addEventListener("change", async function (event) {
        // Reset admin messages
        // const adminMessages = document.querySelector(`.${prefix} .admin-messages`);
        // if (adminMessages) adminMessages.innerHTML = "";


        if (event.target.closest("form").id === `${prefix}__chartOptionsForm`) {

        event.preventDefault();

        drawChart(event.target, iwpgvObj, prefix, jsonRes.spreadsheet);

        }
      });
      
    } else {
      throw new Error( jsonRes.message)
    }
  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj.prefix)
    console.log("CAUGHT ERROR", error)

  } finally {
    toggleElement(`${prefix}__spinner`);
    toggleElement(`${prefix}__warning`);
  }

};

export default selectFile;
