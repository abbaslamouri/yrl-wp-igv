import Accordion from "./accordion";
import mediaUploader from "./media-uploader";
import renderPanels from "./panels";
import drawChart from "./draw-chart";

import "../sass/admin.scss";

// Load the Visualization API and the controls package.
google.charts.load("current", { packages: ["corechart", "controls"] });

if (typeof iwpgv_charts !== "undefined") {
  if (iwpgv_charts.action && iwpgv_charts.action === "editChart") {
    renderPanels(iwpgv_charts.panels);
  }
}

// Add click event listener
document.addEventListener("click", function (event) {
  // file uploader event listener
  if (event.target.id === "iwpgv__chartParams[mediaUploadBtn]") {
    event.preventDefault();
    mediaUploader();
  }
});

// Add change event listener
document.addEventListener("change", async function (event) {
  // Reset admin messages
  const adminMessages = document.querySelector(".iwpgv .admin-messages");
  if (adminMessages) adminMessages.innerHTML = "";

  if (event.target.classList.contains("iwpgv__chartParams")) {
    event.preventDefault();
    drawChart(event.target);
  }
});

// Load accordion
// const accordions = document.querySelectorAll(".accordion");
new Accordion({ collapsed: false });
// console.log(accordion);
// accordion.accordion();
