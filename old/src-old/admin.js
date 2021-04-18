import Accordion from "../src/accordion";
import { toggleSpinner } from "./utilities";
import drawChart from "./draw-chart";
import mediaUploader from "./media-uploader";
import saveChart from "./save-chart";
import chartList from "./chart-list";
import editChart from "./edit-chart";
import renderPanels from "./fields";

import "../sass/admin.scss";

// Load the Visualization API and the controls package.
google.charts.load("current", { packages: ["corechart", "controls"] });

// Load accordion
const accordion = new Accordion(".iwpgv .accordion");
accordion.accordion();

mediaUploader();

if (typeof iwpgv_charts !== "undefined") {
  console.log(iwpgv_charts);
  console.log(iwpgv_obj);

  if (iwpgv_charts.action && iwpgv_charts.action === "list-charts") {
    chartList();
  }

  if (iwpgv_charts.action && iwpgv_charts.action === "editChart") {
    renderPanels();

    // editChart();
  }
}

// Add change event listener on all the document
const iwpgvPlugin = document.querySelector(".iwpgv");
if (iwpgvPlugin) {
  iwpgvPlugin.addEventListener("change", async function (event) {
    if (!event.target.classList.contains("chartParam")) return;
    event.preventDefault();

    // Reset admin messages
    const adminMessages = document.querySelector(".iwpgv .admin-messages");
    if (adminMessages) adminMessages.innerHTML = "";

    if (
      event.target.id === "iwpgv__sheetId" ||
      event.target.id === "iwpgv__chartType" ||
      event.target.id === "iwpgv__enableSeries" ||
      event.target.id === "iwpgv__enableTrendlines"
    ) {
      drawChart(event.target);
    }
  });

  // Add change event listener on all the document
  iwpgvPlugin.addEventListener("click", async function () {
    const adminMessages = document.querySelector(".iwpgv .admin-messages");
    if (adminMessages) adminMessages.innerHTML = "";

    if (event.target === document.getElementById("iwpgv__save-chart")) {
      event.preventDefault();
      toggleSpinner();
      saveChart(event.target);
    }
  });

  // iwpgvPlugin.addEventListener('mouseover', function () {
  //   if(event.target.classList.contains('form-group__hint-alert')) {
  //     const parent = event.target.parentNode
  //     const grandParent = event.target.parentNode.parentNode
  //     const hint = parent.querySelector('.form-group__hint')
  //     const hintDiv = `<div class='field-group single-field show-hint'>${hint.innerHTML}</div>`
  //     grandParent.insertAdjacentHTML('afterend', hintDiv)
  //   }
  // })

  // iwpgvPlugin.addEventListener('mouseout', function () {
  //   if(event.target.classList.contains('form-group__hint-alert')) {
  //     const grandParent = event.target.parentNode.parentNode
  //     const showHint = grandParent.nextSibling
  //     if (showHint.classList.contains('show-hint')) {
  //       showHint.parentNode.removeChild(showHint)

  //     }
  //   }
  // })
}
