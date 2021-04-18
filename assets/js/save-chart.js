import {
  toggleElement,
  hideElement,
  showElement,
  setSheetId,
  setChartTypeId,
  displayAdminMessage
} from "./utilities";
import fetchData from "./fetch-data";

// const iwpgvCharts = yrl_wp_igv_charts
const iwpgvObj = yrl_wp_igv_obj
const prefix = iwpgvObj.prefix

const saveChart = async function () {

  toggleElement(`${prefix}__spinner`);
  hideElement(`${prefix}__warning`);
  hideElement(`${prefix}__dashboard`);

  try {

     // Bail if there is no file or no sheet or chart type is selected
    const chartTypeSelect = document.getElementById(`${prefix}__chartParams[fileUpload]`);
    const sheetSelect = document.getElementById(`${prefix}__chartParams[sheetId]`);
    const fileUpload = document.getElementById(`${prefix}__chartParams[chartType]`);
    if (!fileUpload.value || !sheetSelect.value || !chartTypeSelect.value) {
      throw new Error ("Please draw chart first")
    }


    const formData = new FormData(
       document.getElementById(`${prefix}__chartOptionsForm`)
    );

    
    formData.append("action", iwpgvObj.save_chart_action);
    formData.append("nonce", iwpgvObj.save_chart_nonce);

    //send ajax resquest
    const jsonRes = await fetchData(formData);
    
    if (jsonRes.message) {
      displayAdminMessage(jsonRes.message);
    }

    // Success handler
    if (jsonRes.status && jsonRes.status === "success") {
      setSheetId(jsonRes.spreadsheet, "");
      setChartTypeId("");
    }

  } catch (error) {
    console.log("ERRROR", error)
    displayAdminMessage(error.message);
  } finally {
    toggleElement(`${prefix}__spinner`);
    showElement(`${prefix}__warning`);
  }
};

export default saveChart;
