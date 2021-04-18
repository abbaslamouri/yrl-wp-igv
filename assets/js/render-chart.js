import datatable from "./datatable";
import {
  showElement,
  hideElement,
  setChartWidth,
  chartOptionKey,
  resizeChartArea,
  setDependentFields,
  formatArrayFields,
} from "./utilities";
import colorPicker from "./color-picker";

let iwpgvChart;
let numRangeSlider;
let minMaxDataTable;
let iwpgvMinMaxAvg;
let iwpgvTableChart;
let dataTable;
let chartRangeSlider;



const renderChart = function (chart, sheet, containers, flag="list") {
  // Set a callback to run when the Google Visualization API is loaded.
  google.charts.setOnLoadCallback(drawDashboard);

  // instantiates a dashboard, a range slider and a pie chart,
  function drawDashboard() {
    //Create our data table.
    dataTable = datatable(sheet);

    // Draw chart
    iwpgvChart = new google.visualization.ChartWrapper({
      dataTable: dataTable,
      chartType: chart.chartParams.chartType,
      containerId: containers.chart,
      options: chart.chartOptions,
    });

    // Draw number range slider
    if (
      (chart.chartParams.enableNumRangeSlider ||
      chart.chartParams.enableChartRangeSlider ||
      chart.chartParams.enableTableChart) && flag !=="list"
    ) {
      // Create a dashboard.
      const dashboard = new google.visualization.Dashboard(
        document.getElementById(containers.dashboard)
      );


      if (
        chart.chartParams.enableNumRangeSlider &&
        chart.numRangeOptions &&
        (chart.numRangeOptions.filterColumnIndex ||
          chart.numRangeOptions.filterColumnIndex === 0)
      ) {
        // Create number range slider
        numRangeSlider = new google.visualization.ControlWrapper({
          controlType: "NumberRangeFilter",
          containerId: containers.numRange,
          options: chart.numRangeOptions,
        });
        dashboard.bind(numRangeSlider, iwpgvChart);

        // Unhide number range container div
        document.getElementById("yrl_wp_igv__num-range-filter").classList.remove("hidden");
        

        // Add chart ready event handler
        google.visualization.events.addOneTimeListener(
          numRangeSlider,
          "ready",
          function () {
            numRangeOneTimeReadyHandler();
          }
        );

        // Add number range state change listener
        google.visualization.events.addListener(
          numRangeSlider,
          "statechange",
          function () {
            numRangeStateChangeHandler(chart, containers);
          }
        );
      }

      // Draw chart range slider
      if (
        chart.chartParams.enableChartRangeSlider &&
        chart.chartRangeOptions &&
        (chart.chartRangeOptions.filterColumnIndex ||
          chart.chartRangeOptions.filterColumnIndex === 0)
      ) {
        // Create number range slider
        chartRangeSlider = new google.visualization.ControlWrapper({
          controlType: "ChartRangeFilter",
          containerId: containers.chartRange,
          options: chart.chartRangeOptions,
        });
        dashboard.bind(chartRangeSlider, iwpgvChart);

        // Unhide number range container div
        document
          .getElementById("yrl_wp_igv__chart-range-filter")
          .classList.remove("hidden");

        // // Add chart ready event handler
        // google.visualization.events.addOneTimeListener(
        //   numRangeSlider,
        //   "ready",
        //   function () {
        //     numRangeOneTimeReadyHandler();
        //   }
        // );

        // // Add number range state change listener
        // google.visualization.events.addListener(
        //   numRangeSlider,
        //   "statechange",
        //   function () {
        //     numRangeStateChangeHandler(chart, containers);
        //   }
        // );
      }

      // Draw table chart if enabled
      if (chart.chartParams.enableTableChart) {
        iwpgvTableChart = new google.visualization.ChartWrapper({
          dataTable: dataTable,
          chartType: "Table",
          containerId: containers.tableChart,
          options: chart.tableChartOptions,
        });
        if (chart.chartParams.enableNumRangeSlider)
          dashboard.bind(numRangeSlider, iwpgvTableChart);

        if (chart.chartParams.enableChartRangeSlider)
          dashboard.bind(chartRangeSlider, iwpgvTableChart);

        // Unhide table cahrt container div
        document
          .getElementById("yrl_wp_igv__table-chart")
          .classList.remove("hidden");
      }

      // Add chart ready event handler
      google.visualization.events.addOneTimeListener(
        dashboard,
        "ready",
        function () {
          chartOneTimeReadyHandler(chart, sheet, containers);
        }
      );

      // Add dashboard  error event handler
      google.visualization.events.addListener(
        dashboard,
        "error",
        chartErrorHandler
      );

      // Draw the dashboard.
      dashboard.draw(dataTable);
    } else {
     
      // Draw chart
      iwpgvChart = new google.visualization.ChartWrapper({
        dataTable: dataTable,
        chartType: chart.chartParams.chartType,
        containerId: containers.chart,
        options: chart.chartOptions,
      });

      // Add chart ready event handler
      google.visualization.events.addOneTimeListener(
        iwpgvChart,
        "ready",
        function () {
          chartOneTimeReadyHandler(
            chart,
            sheet,
            containers,
            iwpgvChart,
            numRangeSlider
          );
        }
      );

      // Add dashboard  error event handler
      google.visualization.events.addListener(
        iwpgvChart,
        "error",
        chartErrorHandler
      );

      iwpgvChart.draw();
    }

    // Draw Min Max table chart
    if (chart.chartParams.enableMinMaxTableChart) {
      const dataView = new google.visualization.DataView(dataTable);
      minMaxDataTable = fetchMinMaxDataTable(dataView);
      iwpgvMinMaxAvg = new google.visualization.ChartWrapper({
        dataTable: minMaxDataTable,
        chartType: "Table",
        containerId: containers.minMaxTableChart,
        options: chart.minMaxTableChartOptions,
      });
      iwpgvMinMaxAvg.draw();
      // Unhide number range container div
      document
        .getElementById("yrl_wp_igv__min-max-table-chart")
        .classList.remove("hidden");
    }

    // Hide number range slider if not enabled
    if (!chart.chartParams.enableNumRangeSlider && document.getElementById(containers.numRange)) {
      document.getElementById(containers.numRange).innerHTML = "";

      // Unhide number range container div
      document
        .getElementById("yrl_wp_igv__num-range-filter")
        .classList.add("hidden");
    }

    // Hide chart rabge slider if not enabled
    if (!chart.chartParams.enableChartRangeSlider && document.getElementById(containers.chartRange)) {
      document.getElementById(containers.chartRange).innerHTML = "";

      // Unhide number range container div
      document
        .getElementById("yrl_wp_igv__chart-range-filter")
        .classList.add("hidden");
    }

    if (!chart.chartParams.enableTableChart && document.getElementById(containers.tableChart)) {
      document.getElementById(containers.tableChart).innerHTML = "";

      // Unhide table cahrt container div
      document.getElementById("yrl_wp_igv__table-chart").classList.add("hidden");
    }

    if (!chart.chartParams.enableMinMaxTableChart && document.getElementById(containers.minMaxTableChart)) {
      document.getElementById(containers.minMaxTableChart).innerHTML = "";

      // Unhide table cahrt container div
      document
        .getElementById("yrl_wp_igv__min-max-table-chart")
        .classList.add("hidden");
    }
  }
};

// Dashboard custom error handler
function chartErrorHandler(error) {
  console.log(error);

  // Display custom error message
  const adminMessage = document.querySelector(".iwpgv .admin-messages");
  const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`;
  if (adminMessage) adminMessage.innerHTML = message;

  // Remove google default error
  google.visualization.errors.removeError(error.id);
}

// Dashboaed ready handler
function chartOneTimeReadyHandler(chart, sheet, containers) {
  // Add change event listener on all the document
  document.addEventListener(
    "change",
    function () {
      chartOptionsChange(chart, sheet, containers);
    },
    false
  );
  document.addEventListener(
    "keyup",
    function () {
      chartOptionsChange(chart, sheet, containers);
    },
    false
  );

  // Add window resize event listener and redraw chart
  window.addEventListener("resize", () => {
    resizeChart(chart, sheet, containers);
  });

  // set color pickers
  colorPicker(iwpgvChart, chartRangeSlider);
}

const chartOptionsChange = function (chart, sheet, containers) {
  event.preventDefault();

  // Bail if input does not have yrl_wp_igv__charts class
  if (!event.target.classList.contains("yrl_wp_igv__chart")) return;

  // target element id and value
  let fieldId = event.target.id;
  let fieldVal =
    event.target.type == "checkbox" ? event.target.checked : event.target.value;

  // set chart width and chart area width

  if (fieldId === "yrl_wp_igv__chartOptions[width]") {
    const chartOptionsWidth = document.getElementById(
      "yrl_wp_igv__chartOptions[width]"
    );
    fieldVal = setChartWidth(chartOptionsWidth, "yrl_wp_igv__dashboard");
  }

  if (
    fieldId === "yrl_wp_igv__chartOptions[chartArea][width]" ||
    fieldId === "yrl_wp_igv__chartOptions[chartArea][height]"
  ) {
    fieldVal = resizeChartArea(fieldId);
  }

  // Set range chart width/height and chart area width/height
  if (fieldId === "yrl_wp_igv__chartRangeOptions[ui][chartOptions][width]") {
    const chartRangeOptionsWidth = document.getElementById(
      "yrl_wp_igv__chartRangeOptions[ui][chartOptions][width]"
    );
    fieldVal = setChartWidth(chartRangeOptionsWidth, "yrl_wp_igv__filter-min-max");
  }
  if (
    fieldId ===
      "yrl_wp_igv__chartRangeOptions[ui][chartOptions][chartArea][width]" ||
    fieldId === "yrl_wp_igv__chartRangeOptions[ui][chartOptions][chartArea][height]"
  ) {
    fieldVal = resizeChartArea(fieldId);
  }

  // Set fields with dependents
  if (event.target.classList.contains("hasDependents")) {
    setDependentFields(event.target);
  }

  // split array fields (returns array)
  if (event.target.classList.contains("array-field")) {
    fieldVal = formatArrayFields(event.target);
  }

  console.log(chartOptionKey(fieldId), fieldVal);
  console.log(chartOptionKey(fieldId))
  switch (chartOptionKey(fieldId).control) {
    case "chartOptions":
    case "pieChartOptions":
    case "horAxisOptions":
    case "leftAxisOptions":
    case "rightAxisOptions":
    case "seriesOptions":
    case "trendlinesOptions":
      console.log("Layout", layout)
      // iwpgvChart.setOption(chartOptionKey(fieldId).key, fieldVal);
      // iwpgvChart.draw();
      break;

    case "numRangeOptions":
      numRangeSlider.setOption(chartOptionKey(fieldId).key, fieldVal);
      numRangeSlider.draw();
      break;

    case "chartRangeOptions":
      chartRangeSlider.setOption(chartOptionKey(fieldId).key, fieldVal);
      chartRangeSlider.draw();
      console.log(chartRangeSlider.getOptions());
      break;

    case "minMaxTableChartOptions":
      iwpgvMinMaxAvg.setOption(chartOptionKey(fieldId).key, fieldVal);
      iwpgvMinMaxAvg.draw();
      break;

    default:
      break;
  }
};

function numRangeOneTimeReadyHandler() {
  document.getElementById(
    "yrl_wp_igv__numRangeOptions[minValue]"
  ).value = numRangeSlider.getState().lowValue;
  document.getElementById(
    "yrl_wp_igv__numRangeOptions[maxValue]"
  ).value = numRangeSlider.getState().highValue;
}

function numRangeStateChangeHandler(chart, containers) {
  if (
    chart.minMaxTableChartOptions &&
    chart.chartParams.enableMinMaxTableChart
  ) {
    // const dt = numberFilteredTable();
    // Set filtered rows
    const filteredRows = dataTable.getFilteredRows([
      {
        column: chart.numRangeOptions.filterColumnIndex * 1,
        minValue: numRangeSlider.getState().lowValue,
        maxValue: numRangeSlider.getState().highValue,
      },
    ]);

    const dataView = new google.visualization.DataView(dataTable);
    dataView.setRows(filteredRows);

    // // Fetch min max datatable
    minMaxDataTable = fetchMinMaxDataTable(dataView);

    iwpgvMinMaxAvg = new google.visualization.ChartWrapper({
      dataTable: minMaxDataTable,
      chartType: "Table",
      containerId: containers.minMaxTableChart,
      options: chart.minMaxTableChartOptions,
    });
    iwpgvMinMaxAvg.draw();
  }

  document.getElementById(
    "yrl_wp_igv__numRangeOptions[minValue]"
  ).value = numRangeSlider.getState().lowValue;
  document.getElementById(
    "yrl_wp_igv__numRangeOptions[maxValue]"
  ).value = numRangeSlider.getState().highValue;
}

const resizeChart = function (chart, sheet, containers) {
  let timeout = false;
  showElement("yrl_wp_igv__spinner");

  // Hide chart, resize chart, wait 2 sec then show chart
  if(document.getElementById("yrl_wp_igv__chart"))
    document.getElementById("yrl_wp_igv__chart").style.opacity = 0;
  iwpgvChart.setOption("width", "1");
  iwpgvChart.draw();
  iwpgvChart.setOption("width", setChartWidth(chart, "yrl_wp_igv__dashboard"));
  iwpgvChart.draw();

  // clear the timeout
  clearTimeout(timeout);

  // start timing for event "completion"
  timeout = setTimeout(function () {
    hideElement("yrl_wp_igv__spinner");
    if(document.getElementById("yrl_wp_igv__chart"))
      document.getElementById("yrl_wp_igv__chart").style.opacity = 1;
  }, 2000);
};

function fetchMinMaxDataTable(data) {
  // let i = settings.includeMinMaxTableFirstCol ? 0 : 1;

  let i = 1;

  let groupData = [];

  while (i < data.getNumberOfColumns()) {
    const aggregateData = getJointTableRow(data, i);
    if (aggregateData) {
      groupData.push(google.visualization.data.group(data, ...aggregateData));
    }
    i++;
  }

  if (groupData.length == 1) return groupData[0];

  let joinData = google.visualization.data.join(
    groupData[0],
    groupData[1],
    "full",
    [
      [0, 0],
      [1, 1],
      [2, 2],
      [3, 3],
    ],
    [],
    [],
    [],
    []
  );
  if (groupData === 2) return joinData;
  i = 2;

  while (i < groupData.length) {
    // console.log("PPPPP", i, groupData[i]);
    // console.log(11111);
    if (groupData[i]) {
      // console.log(22222);
      joinData = google.visualization.data.join(
        joinData,
        groupData[i],
        "full",
        [
          [0, 0],
          [1, 1],
          [2, 2],
          [3, 3],
        ],
        [],
        [],
        [],
        []
      );
    }
    i++;
  }

  return joinData;
}

function getJointTableRow(data, i) {
  // group across entire table -- use modifier to return same value for every row

  if (
    data.getColumnType(i) === "number" &&
    (data.getColumnRole(i) === "domain" || data.getColumnRole(i) === "data")
  ) {
    return [
      [
        {
          column: i,
          modifier: function () {
            return data.getColumnLabel(i);
          },
          type: "string",
        },
      ],
      [
        // aggregation functions
        {
          column: i,
          aggregation: google.visualization.data.min,
          type: "number",
          label: "Minimum",
        },
        {
          column: i,
          aggregation: google.visualization.data.avg,
          type: "number",
          label: "Average",
        },
        {
          column: i,
          aggregation: google.visualization.data.max,
          type: "number",
          label: "Maximum",
        },
      ],
    ];
  } else {
    return false;
  }
}

export default renderChart;
