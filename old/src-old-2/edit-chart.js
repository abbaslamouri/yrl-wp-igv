import {
  toggleSpinner,
  setChartOption,
  setFieldSuffix,
  formatArrayFields,
  setDependentFields,
  setChartWidthHeight,
} from "./utilities";
import fetchData from "./fetch-data";
import renderChart from "./render-chart";
import colorPicker from "./color-picker";

const editChart = function (containers) {
  toggleSpinner();

  // Set Chart ID value
  const chartId = document.getElementById("iwpgv__chartId");
  chartId.value = iwpgv_charts.chart.chartParams.chartId;

  // Set File ID value
  const fileName = document.getElementById("iwpgv__fileUpload");
  fileName.value = iwpgv_charts.chart.chartParams.fileName;

  // Append sheet option list to the sheet Id select field and unhide
  const sheetId = document.getElementById("iwpgv__sheetId");
  sheetId.options.length = 0;
  sheetId.innerHTML = iwpgv_charts.sheetIdOptions;
  sheetId.value = iwpgv_charts.chart.chartParams.sheetId;
  sheetId.closest(".form-group").classList.remove("hidden");

  // Set chart type selected value and unhide
  const chartType = document.getElementById("iwpgv__chartType");
  chartType.value = iwpgv_charts.chart.chartParams.chartType;
  chartType.closest(".form-group").classList.remove("hidden");

  // Enable save button
  const iwpgv__submit = document.getElementById("iwpgv__save-chart");
  iwpgv__submit.disabled = false;

  let chart = iwpgv_charts.chart;

  let chartOptions = chart.chartOptions;

  if (chart.pieChartOptions) {
    chartOptions = Object.assign(chartOptions, chart.pieChartOptions);
  }
  chartOptions["hAxis"] = chart.horAxisOptions ? chart.horAxisOptions : {};
  chartOptions["vAxes"] = {};
  chartOptions["vAxes"][0] = chart.leftAxisOptions ? chart.leftAxisOptions : {};
  chartOptions["vAxes"][1] = chart.rightAxisOptions
    ? chart.rightAxisOptions
    : {};
  chartOptions["series"] = chart.seriesOptions ? chart.seriesOptions : {};
  chartOptions["trendlines"] = chart.trendlinesOptions
    ? chart.trendlinesOptions
    : {};

  delete chart.pieChartOptions;
  delete chart.horAxisOptions;
  delete chart.leftAxisOptions;
  delete chart.rightAxisOptions;
  delete chart.seriesOptions;
  delete chart.trendlinesOptions;

  // Get the width and height of the dashboard container div and set chart width and height to 75% of the container div.
  const chartRect = document
    .getElementById("iwpgv__dashboard")
    .getBoundingClientRect();

  chart.chartOptions.width =
    chart.chartOptions.width === 100
      ? "100%"
      : 0.01 * chart.chartOptions.width * chartRect.width;

  const hasDependents = document.querySelectorAll(".hasDependents");
  if (hasDependents) {
    hasDependents.forEach((el) => {
      setDependentFields(el);
    });
  }

  let i = 0;
  while (i < chart.chartOptions.series.length) {
    const serie = document.getElementById(
      `iwpgv__seriesOptions[series][${i}][color]`
    );
    if (serie) {
      serie.value = chart.chartOptions.series[i].color;
    }

    const trendline = document.getElementById(
      `iwpgv__trendlinesOptions[trendlines][${i}][color]`
    );
    if (trendline) {
      trendline.value = chart.chartOptions.trendlines[i].color;
    }
    i++;
  }

  // Set chart area heigth and width
  chart.chartOptions.chartArea.width = `${chart.chartOptions.chartArea.width}%`;
  chart.chartOptions.chartArea.height = `${chart.chartOptions.chartArea.height}%`;

  // Render chart
  renderChart(chart, containers, iwpgv_charts.settings);

  // Set color pickers
  colorPicker();
};

export default editChart;
