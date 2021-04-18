import fetchData from "./fetch-data";
import renderChart from "./render-chart";

const selectFile = async function (target) {
  try {
    // Find form whose submit button was clicked
    const form = target.closest("form");
    if (!form) throw "Can't find a form";

    // Assemble form data
    const formData = new FormData(form);
    formData.append("action", iwpgv_obj.sheet_select_action);
    formData.append("nonce", iwpgv_obj.sheet_select_nonce);

    const jsonRes = await fetchData(formData);

    // Display message
    if (jsonRes && jsonRes.message) {
      document.querySelector(".admin-messages").innerHTML = jsonRes.message;
    }

    if (jsonRes.status === "success") {
      renderChart(jsonRes.sheet, jsonRes.chartType, jsonRes.chartOptions);
    }

    return jsonRes;
  } catch (error) {
    console.log(error.message);
  }
};

export default selectFile;
