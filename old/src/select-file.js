import {
  toggleElement,
  hideElement,
  showElement,
  setSheetId,
  setChartTypeId,
  //   displayAdminMessage,
} from "./utilities";
import fetchData from "./fetch-data";

const selectFile = async function () {
  toggleElement("iwpgv__spinner");
  hideElement("iwpgv__warning");
  hideElement("iwpgv__dashboard");

  try {
    const formData = new FormData(
      document.getElementById("iwpgv__chartOptionsForm")
    );
    formData.append("action", iwpgv_obj.file_select_action);
    formData.append("nonce", iwpgv_obj.file_select_nonce);

    //send ajax resquest
    const jsonRes = await fetchData(formData);

    if (jsonRes.message) {
      displayAdminMessage(jsonRes.message);
    }

    // Success handler
    if (jsonRes.status && jsonRes.status === "success") {
      setSheetId(jsonRes.spreadsheet, "");
      setChartTypeId("");
      // document.getElementById('iwpgv__chart').innerHTML = ""
      // document.getElementById('iwpgv__num-range-filter').innerHTML = ""
    }
  } catch (error) {
    const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`;
    displayAdminMessage(message);
    console.log(error);
  } finally {
    toggleElement("iwpgv__spinner");
    showElement("iwpgv__warning");
  }
};

export default selectFile;
