import {
  toggleElement,
  showElement,
  hideElement,
  displayAdminMessage,
  setSheetId,
  setChartTypeId,
  setChartWidth,
  setChartArea,
  setDependentFields,
} from "./utilities";
import fetchData from "./fetch-data";
import renderChart from "./render-chart";
import renderPanels from "./panels";
import Accordion from "./accordion";

const iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}
const prefix = iwpgvObj.prefix ? iwpgvObj.prefix : ""

// import colorPicker from "./color-picker";

const drawChart = async function (target) {

  // Bail if either value is null
  if (
    !document.getElementById(`${prefix}__chartParams[sheetId]`).value ||
    !document.getElementById(`${prefix}__chartParams[chartType]`).value
  ) {
    return;
  }
  toggleElement(`${prefix}__spinner`);
  hideElement(`${prefix}__dashboard`);
  hideElement(`${prefix}__warning`);

  try {
    // Find form whose submit button was clicked
    const form = target.closest("form")
    if (!form) throw new Error(  "Can't find a form" )

    // Assemble form data
    const formData = new FormData(form);
    formData.append("action", iwpgvObj.fetch_chart_options_n_panels_action);
    formData.append("nonce", iwpgvObj.fetch_chart_options_n_panels_nonce);

   // Display the key/value pairs
// for (var pair of formData.entries()) {
//   console.log(pair[0]+ ', ' + pair[1]); 
// }

    // Wait for response
    const jsonRes = await fetchData(formData);

    console.log(jsonRes)

    if (jsonRes && jsonRes.message) {
      displayAdminMessage(jsonRes.message);
    }

    // Render chart and panels on success
    if (jsonRes.status && jsonRes.status === "success") {
      // Get chart
      let chart = jsonRes.chart;

      // Add accordion div to foem
      const form = document.getElementById(`${prefix}__chartOptionsForm`);
      if (form) {
        form.innerHTML = "";
      } else {
        return;
      }
      // Render Panels
      renderPanels(jsonRes.panels);

      // Set sheetId and chart type
      const sheetId =
        chart.chartParams.sheetId || chart.chartParams === 0
          ? chart.chartParams.sheetId
          : null;
      setSheetId(jsonRes.spreadsheet, sheetId);
      setChartTypeId(chart.chartParams.chartType);

      // Enable save button
      document.getElementById(`${prefix}__saveChart`).disabled = false;

      // Set chart width and chart area width
      const chartOptionsWidth = document.getElementById(
        `${prefix}__chartOptions[width]`
      );
      chart.chartOptions.width = setChartWidth(
        chartOptionsWidth,
        `${prefix}__dashboard`
      );
      chart.chartOptions.chartArea.width = setChartArea(
        chart.chartOptions.chartArea,
        chart.chartParams.chartType
      ).width;
      chart.chartOptions.chartArea.height = setChartArea(
        chart.chartOptions.chartArea,
        chart.chartParams.chartType
      ).height;

      // Set chart range width and chart area width
      if (chart.chartParams.enableChartRangeSlider && chart.chartRangeOptions) {
        const chartRangeOptionsWidth = document.getElementById(
          `${prefix}__chartRangeOptions[ui][chartOptions][width]`
        );
        chart.chartRangeOptions.ui.chartOptions.width = setChartWidth(
          chartRangeOptionsWidth,
          `${prefix}__filter-min-max`
        );

        chart.chartRangeOptions.ui.chartOptions.chartArea.width = setChartArea(
          chart.chartRangeOptions.ui.chartOptions.chartArea,
          chart.chartParams.chartType
        ).width;
        chart.chartRangeOptions.ui.chartOptions.chartArea.height = setChartArea(
          chart.chartRangeOptions.ui.chartOptions.chartArea,
          chart.chartParams.chartType
        ).height;
      }

      // Set dependent fields
      const hasDependents = document.querySelectorAll(".hasDependents");
      if (hasDependents) {
        hasDependents.forEach((el) => {
          setDependentFields(el);
        });
      }

      chart.chartOptions.annotations.boxStyle.gradient.x1 = `${chart.chartOptions.annotations.boxStyle.gradient.x1}%`;
      chart.chartOptions.annotations.boxStyle.gradient.x2 = `${chart.chartOptions.annotations.boxStyle.gradient.x2}%`;
      chart.chartOptions.annotations.boxStyle.gradient.y1 = `${chart.chartOptions.annotations.boxStyle.gradient.y1}%`;
      chart.chartOptions.annotations.boxStyle.gradient.y2 = `${chart.chartOptions.annotations.boxStyle.gradient.y2}%`;

      // Render chart
      let containers = {
        dashboard: `${prefix}__dashboard`,
        chart: `${prefix}__chart`,
        tableChart: `${prefix}__table-chart`,
        numRange: `${prefix}__num-range-filter`,
        chartRange: `${prefix}__chart-range-filter`,
        minMaxTableChart: `${prefix}__min-max-table-chart`,
      };
      renderChart(
        jsonRes.chart,
        jsonRes.spreadsheet[chart.chartParams.sheetId],
        containers
      );

      // Set accordion
      new Accordion({ collapsed: false });

      // // Set color pickers
      // colorPicker();
    }
  } catch (error) {
    const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`;
    displayAdminMessage(message);
    console.log(error);
  } finally {
    toggleElement(`${prefix}__spinner`);
    showElement(`${prefix}__dashboard`);
  }
};

export default drawChart;
