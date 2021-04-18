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

// import colorPicker from "./color-picker";

const drawChart = async function (target) {
  // Bail if either value is null
  if (
    !document.getElementById("iwpgv__chartParams[sheetId]").value ||
    !document.getElementById("iwpgv__chartParams[chartType]").value
  ) {
    return;
  }
  toggleElement("iwpgv__spinner");
  hideElement("iwpgv__dashboard");
  hideElement("iwpgv__warning");

  try {
    // Find form whose submit button was clicked
    const form = target.closest("form");
    if (!form) throw "Can't find a form";

    // Assemble form data
    const formData = new FormData(form);
    formData.append("action", iwpgv_obj.sheet_chart_type_select_action);
    formData.append("nonce", iwpgv_obj.sheet_chart_type_select_nonce);

    // Wait for response
    const jsonRes = await fetchData(formData);

    if (jsonRes && jsonRes.message) {
      displayAdminMessage(jsonRes.message);
    }

    // Render chart and panels on success
    if (jsonRes.status && jsonRes.status === "success") {
      // Get chart
      let chart = jsonRes.chart;

      // Add accordion div to foem
      const form = document.getElementById(`iwpgv__chartOptionsForm`);
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
      document.getElementById("iwpgv__saveChart").disabled = false;

      // Set chart width and chart area width
      const chartOptionsWidth = document.getElementById(
        "iwpgv__chartOptions[width]"
      );
      chart.chartOptions.width = setChartWidth(
        chartOptionsWidth,
        "iwpgv__dashboard"
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
          "iwpgv__chartRangeOptions[ui][chartOptions][width]"
        );
        chart.chartRangeOptions.ui.chartOptions.width = setChartWidth(
          chartRangeOptionsWidth,
          "iwpgv__filter-min-max"
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
        dashboard: "iwpgv__dashboard",
        chart: "iwpgv__chart",
        tableChart: "iwpgv__table-chart",
        numRange: "iwpgv__num-range-filter",
        chartRange: "iwpgv__chart-range-filter",
        minMaxTableChart: "iwpgv__min-max-table-chart",
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
    toggleElement("iwpgv__spinner");
    showElement("iwpgv__dashboard");
  }
};

export default drawChart;
