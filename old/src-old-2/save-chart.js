import { toggleSpinner,  formatArrayFields } from "./utilities";
import fetchData from "./fetch-data";

const saveFile = async function () {
  // const dashboard = document.getElementById("iwpgv__dashboard");
  // dashboard.classList.add("hidden");

  const adminMessages = document.querySelector(".iwpgv .admin-messages");

  const chartTypeSelect = document.getElementById("iwpgv__chartType");
  const sheetSelect = document.getElementById("iwpgv__sheetId");
  const fileUpload = document.getElementById("iwpgv__fileUpload");

  // Bail if either value is null
  if (!fileUpload.value || !sheetSelect.value || !chartTypeSelect.value) {
    toggleSpinner();
    console.log("FILEUPLOAD", fileUpload);

    const message =
      "<div class='notice notice-error is-dismissible'><p>Please draw chart first</p></div>";

    adminMessages.innerHTML = message;

    return;
  }

  let formData = new FormData(document.getElementById("iwpgv__options"));

  const inputs = document.querySelectorAll(".iwpgv input, .iwpgv select");

  inputs.forEach((input) => {
    let value;

    if (input.type !== "button") {
      // if (input.type === "checkbox") {
      //   value = input.checked;
      // } else {
      //   value = input.value;
      // }
      // split array fields (returns array)
      // if (input.classList.contains("array-field")) {
        // value = JSON.stringify(formatArrayFields(input));
      // }

      // console.log(input.id, input.value)


      // formData.append(input.id, input.value);
    }
  });

  try {
    // Assemble form data
    formData.append("action", iwpgv_obj.save_chart_action);
    formData.append("nonce", iwpgv_obj.save_chart_nonce);

    // Wait for response
    const jsonRes = await fetchData(formData);

    // Display message
    if (jsonRes && jsonRes.message) {
      adminMessages.innerHTML = jsonRes.message;
    }

    // Render chart on success
    if (jsonRes.status && jsonRes.status === "success") {
      // dashboard.classList.remove("hidden");
      // window.location.href = iwpgv_obj.plugin_url
    } else if (jsonRes.status && jsonRes.status === "error") {
      // Unhide the dashboard div
      // dashboard.classList.add("hidden");
    }
  } catch (error) {
    const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`;
    displayAdminMessage(message);
    console.log(error);
  } finally {
    toggleSpinner();
  }
};

export default saveFile;
