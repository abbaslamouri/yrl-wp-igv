import datatable from "./datatable";
import { fetchMinMaxDataTable } from "./utilities";
import renderChart from "./render-chart";

import "../sass/public.scss";

// Load the Visualization API and the controls package.
google.charts.load("current", { packages: ["corechart", "controls"] });

console.log(iwpgv_front_end_chart);

const chart = iwpgv_front_end_chart.chart;
const id = chart.chartParams.chartId;
chart.chartOptions.width = `${chart.chartOptions.width}%`;
if (chart.chartParams.chartType === "PieChart") {
  chart.chartOptions.chartArea = null;
} else {
  chart.chartOptions.chartArea.width = `${
    chart.chartOptions.chartArea.width * 1
  }%`;
  chart.chartOptions.chartArea.height = `${
    chart.chartOptions.chartArea.height * 1
  }%`;
  chart.chartOptions.chartArea.top = chart.chartOptions.chartArea.top * 1;
  chart.chartOptions.chartArea.left = chart.chartOptions.chartArea.left * 1;
}

console.log(chart);

let containers = {
  dashboard: `iwpgv__front-end-dashboard-${id}`,
  chart: `iwpgv__front-end-chart-${id}`,
  tableChart: `iwpgv__front-end-table-chart-${id}`,
  numRange: `iwpgv__front-end-num-range-filter-${id}`,
  minMaxTableChart: `iwpgv__front-end-min-max-table-chart-${id}`,
};

renderChart(chart, containers, {});

// // Set a callback to run when the Google Visualization API is loaded.
// google.charts.setOnLoadCallback(drawDashboard);

// // instantiates a dashboard, a range slider and a pie chart,
// function drawDashboard() {
//   //Create our data table.
//   const dataTable = datatable(chart.chartParams.sheet);
//   console.log( )

//   if (
//     Object.keys(chart.numRangeOptions).length > 0 &&
//     (chart.numRangeOptions.filterColumnIndex ||
//       chart.numRangeOptions.filterColumnIndex === 0)
//   ) {

//     console.log("PPPPPPPP")
//     // Create a dashboard.
//     const dashboard = new google.visualization.Dashboard(
//       document.getElementById(`iwpgv__front-end-dashboard-${id}`)
//     );

//     // Draw number range slider
//     const numRangeSlider = new google.visualization.ControlWrapper({
//       controlType: "NumberRangeFilter",
//       containerId: `iwpgv__front-end-num-range-filter-${id}`,
//       options: chart.numRangeOptions,
//     });

//     // Draw chart
//     const iwpgvChart = new google.visualization.ChartWrapper({
//       dataTable: dataTable,
//       chartType: chart.chartParams.chartType,
//       containerId: `iwpgv__front-end-chart-${id}`,
//       options: chart.chartOptions,
//     });
//     iwpgvChart.draw();
//     dashboard.bind(numRangeSlider, iwpgvChart);

//     // Draw the dashboard.
//     dashboard.draw(dataTable);

//   } else {

//     // Draw chart
//     const iwpgvChart = new google.visualization.ChartWrapper({
//       dataTable: dataTable,
//       chartType: chart.chartParams.chartType,
//       containerId: `iwpgv__front-end-chart-${id}`,
//       options: chart.chartOptions,
//     });

//     // ;drawChart(chart, chartDivId);
//     iwpgvChart.draw();

//   }

//   // Draw Min Max table chart
//   if (Object.keys(chart.minMaxTableChartOptions > 0)) {
//     const dataView = new google.visualization.DataView(dataTable);

//     const minMaxDataTable = fetchMinMaxDataTable(dataView);
//     const iwpgvMinMaxAvg = new google.visualization.ChartWrapper({
//       dataTable: minMaxDataTable,
//       chartType: "Table",
//       containerId: `iwpgv__front-end-min-max-table-chart-${id}`,
//       options: chart.minMaxTableChartOptions,
//     });
//     iwpgvMinMaxAvg.draw();
//   }
// }
