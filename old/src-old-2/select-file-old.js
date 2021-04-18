import { toggleSpinner } from "./utilities";
import fetchData from "./fetch-data";

const selectFile = async function (target) {
 // Hide warning box and dashboard
 document
 .querySelector(".iwpgv .chart-view .warning")
 .classList.add("hidden");
document.getElementById("iwpgv__dashboard").classList.add("hidden");

// Bail if f=ile id value is not set
if (!document.querySelector(".iwpgv select[name='fileId']").value) {
 // Hide warning box
 document
   .querySelector(".iwpgv .chart-view .warning")
   .classList.remove("hidden");
 toggleSpinner();
 return;
}

try {
 // Find form whose submit button was clicked
 const form = target.closest("form");
 if (!form) throw "Can't find a form";

 // Assemble form data
 const formData = new FormData(form);
 formData.append("action", iwpgv_obj.file_select_action);
 formData.append("nonce", iwpgv_obj.file_select_nonce);

 const jsonRes = await fetchData(formData);

 // Display message
 if (jsonRes && jsonRes.message) {
   document.querySelector(".iwpgv .admin-messages").innerHTML =
     jsonRes.message;
 }

 if (jsonRes.status && jsonRes.status === "success") {
   // Append sheet option list to the sheet Id select field
   const sheetSelect = document.querySelector(
     ".iwpgv select[name='sheetId']"
   );
   sheetSelect.options.length = 0;
   sheetSelect.innerHTML = jsonRes.sheetIdOptions;
   sheetSelect.closest('.form-group').classList.remove("hidden");

   // Unhide chart type select field
   const chartTypeSelect = document.querySelector(
     ".iwpgv select[name='chartType']"
   );
   chartTypeSelect.closest('.form-group').classList.remove("hidden");
 } else if (jsonRes.status && jsonRes.status === "error") {
   // Handle error here
 }

 toggleSpinner();

 // Unhide warning box
 document
   .querySelector(".iwpgv .chart-view .warning")
   .classList.remove("hidden");
} catch (error) {
 console.log(error.message);
 toggleSpinner();

 // Unhide warning box
 document
   .querySelector(".iwpgv .chart-view .warning")
   .classList.remove("hidden");
}

return;
};

export default selectFile;
