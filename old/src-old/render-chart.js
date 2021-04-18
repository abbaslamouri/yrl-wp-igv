import datatable from "./datatable";
import {
  showElement,
  hideElement,
  setChartWidth,
  chartOptionKey,
  setChartWidthHeight,
  setFieldSuffix,
  formatArrayFields,
  setDependentFields,
} from "./utilities";
import colorPicker from "./color-picker";


// Initialize google chart
let dashboard;
let iwpgvChart;
let numRangeSlider;
let iwpgvTableChart;
let dataTable;
// let dataView;
let minMaxDataTable;
let iwpgvMinMaxAvg;

// let chart;
// let containers;
// let settings;

const renderChart = function (chart, containers) {

  // Set a callback to run when the Google Visualization API is loaded.
  google.charts.setOnLoadCallback(drawDashboard);

  // chart = chart;
  // containers = containers
  // settings = settings;

  // instantiates a dashboard, a range slider and a pie chart,
  function drawDashboard() {
    //Create our data table.
    dataTable = datatable(chart.chartParams.sheet);

    if (
      chart.numRangeOptions.enable &&
      (chart.numRangeOptions.filterColumnIndex ||
        chart.numRangeOptions.filterColumnIndex === 0)
    ) {
      // Create a dashboard.
      dashboard = new google.visualization.Dashboard(
        document.getElementById(containers.dashboard)
      );

      // Draw number range slider
      numRangeSlider = new google.visualization.ControlWrapper({
        controlType: "NumberRangeFilter",
        containerId: containers.numRange,
        options: chart.numRangeOptions,
      });

      // Add chart ready event handler
      google.visualization.events.addOneTimeListener(
        numRangeSlider,
        "ready",
        numRangeOneTimeReadyHandler
      );

      // Draw chart
      iwpgvChart = new google.visualization.ChartWrapper({
        dataTable: dataTable,
        chartType: chart.chartParams.chartType,
        containerId: containers.chart,
        options: chart.chartOptions,
      });
      iwpgvChart.draw();
      dashboard.bind(numRangeSlider, iwpgvChart);

      // Draw table chart
      if (chart.tableChartOptions && chart.tableChartOptions.enable) {
        iwpgvTableChart = new google.visualization.ChartWrapper({
          dataTable: dataTable,
          chartType: "Table",
          containerId: containers.tableChart,
          options: chart.tableChartOptions,
        });
        iwpgvTableChart.draw();
        dashboard.bind(numRangeSlider, iwpgvTableChart);
      }

      // Add chart ready event handler
      google.visualization.events.addOneTimeListener(
        dashboard,
        "ready",
        function () {
          chartReadyHandler(chart, containers);
        }
      );

      // Add chart error event handler
      google.visualization.events.addListener(
        dashboard,
        "error",
        chartErrorHandler
      );

      // Draw the dashboard.
      dashboard.draw(dataTable);

      // Add number range state change listener
      google.visualization.events.addListener(
        numRangeSlider,
        "statechange",
        function () {
          rangeSliderStateChangeHandler();
        }
      );
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
          chartReadyHandler(chart, containers);
        }
      );

      // Add chart error event handler
      google.visualization.events.addListener(
        iwpgvChart,
        "error",
        chartErrorHandler
      );

      // ;drawChart(chart, chartDivId);
      iwpgvChart.draw();

      // Draw tableChart associated with chart
      if (chart.tableChartOptions && chart.tableChartOptions.enable) {
        iwpgvTableChart = new google.visualization.ChartWrapper({
          dataTable: dataTable,
          chartType: "Table",
          containerId: containers.tableChart,
          options: chart.tableChartOptions,
        });
        iwpgvTableChart.draw();
      }
    }

    // Draw Min Max table chart
    if (chart.minMaxTableChartOptions.enable) {
      const dataView = new google.visualization.DataView(dataTable);
      minMaxDataTable = fetchMinMaxDataTable(dataView);
      iwpgvMinMaxAvg = new google.visualization.ChartWrapper({
        dataTable: minMaxDataTable,
        chartType: "Table",
        containerId: containers.minMaxTableChart,
        options: chart.minMaxTableChartOptions,
      });
      iwpgvMinMaxAvg.draw();
    }
  }
};

// Chart custom error handler
function chartReadyHandler(chart, containers) {

  // Add window resize event listener and redraw chart
  window.addEventListener("resize", () => { 
    let timeout = false;
    showElement('iwpgv__spinner');
    // clear the timeout
    clearTimeout(timeout);
    document.getElementById("iwpgv__chart").innerHTML = "";
    // start timing for event "completion"
    timeout = setTimeout(function () {
      console.log(chart)
      renderChart(chart, containers);
      hideElement('iwpgv__spinner');
    }, 1000);
  });

  console.log("OPTIONS", iwpgvChart.getOptions());


  // Add change event listener on all the document
  document.querySelector(".iwpgv").addEventListener("change", function() {
    chartOptionsChange(chart, containers)
  }, false);
  document.querySelector(".iwpgv").addEventListener("keyup", function() {
    chartOptionsChange(chart, containers)
  }, false);

  // set color pickers
  colorPicker(iwpgvChart);

}

const chartOptionsChange = function (chart, containers) {
  event.preventDefault();

  // Bail if input does not have iwpgv__charts class
  if (!event.target.classList.contains("iwpgv__charts") || event.target.id === "iwpgv__chartOptions[enableSeries]") return;

  // target element id and value
  let fieldId = event.target.id;
  let fieldVal = event.target.type == "checkbox" ? event.target.checked : event.target.value;

  if (fieldId === 'iwpgv__chartOptions[width]') fieldVal = setChartWidth(chart)

  // Set chart area heigth and width
  if (fieldId === "iwpgv__chartOptions[chartArea][width]") {
    const widthInput = document.getElementById(
      "iwpgv__chartOptions[chartArea][width]"
    );
    if (widthInput.value < 100) {
      fieldVal = `${widthInput.value}%`;
    } else {
      fieldVal = "100%";
      widthInput.value = 100;
    }
  }

  // Set chart area heigth and width
  if (fieldId === "iwpgv__chartOptions[chartArea][height]") {
    const heightInput = document.getElementById(
      "iwpgv__chartOptions[chartArea][height]"
    );
    if (heightInput.value < 100) {
      fieldVal = `${heightInput.value}%`;
    } else {
      fieldVal = "100%";
      heightInput.value = 100;
    }
  }


  if (event.target.classList.contains("hasDependents")) {
    setDependentFields(event.target);
  }


  

  // // Handle chart height change
  // if (fieldId.includes("chartOptions[height]")) {
  //   const heightSuffix = document.querySelector(
  //     'input[name="iwpgv__chartOptions[height]-suffix"]:checked'
  //   ).value;
  //   chart.chartOptions.heightSuffix = heightSuffix;
  //   fieldId = "iwpgv__chartOptions[height]";
  //   fieldVal = setChartWidthHeight(chart).height;
  // }

  // split array fields (returns array)
  if (event.target.classList.contains("array-field")) {
    fieldVal = formatArrayFields(event.target);
  }

  console.log(fieldId, fieldVal);
  console.log(chart.chartOptions);

  switch (chartOptionKey(fieldId)[0]) {
    case "chartOptions":
    case "pieChartOptions":
    case "horAxisOptions":
    case "leftAxisOptions":
    case "rightAxisOptions":
    case "seriesOptions":
    case "trendlinesOptions":
      iwpgvChart.setOption(chartOptionKey(fieldId)[1], fieldVal);
      iwpgvChart.draw();
      break;

    case "tableChartOptions":
      if (chartOptionKey(fieldId)[1] === "enable") {
        document.getElementById("iwpgv__table-chart").innerHTML = "";
        chart.tableChartOptions.enable = !chart.tableChartOptions.enable;
        // Render chart
        renderChart(chart, containers.chart, settings);
      } else {
        iwpgvTableChart.setOption(chartOptionKey(fieldId)[1], fieldVal);
        iwpgvTableChart.draw();
      }
      break;

    case "numRangeOptions":
      if (chartOptionKey(fieldId)[1] === "enable") {
        // if (! chart.numRangeOptions.enable ) {
        document.getElementById("iwpgv__num-range-filter").innerHTML = "";
        // } else {
        chart.numRangeOptions.enable = !chart.numRangeOptions.enable;
        //Render chart
        renderChart(chart, containers.chart, settings);
        // }
      } else {
        numRangeSlider.setOption(chartOptionKey(fieldId)[1], fieldVal);
        numRangeSlider.draw();
      }
      break;

    case "minMaxTableChartOptions":
      if (chartOptionKey(fieldId)[1] === "enable") {
        document.getElementById("iwpgv__min-max-table").innerHTML = "";
        chart.minMaxTableChartOptions.enable = !chart.minMaxTableChartOptions
          .enable;
        // Render chart
        renderChart(chart, containers.chart, settings);
      } else {
        iwpgvTableChart.setOption(chartOptionKey(fieldId)[1], fieldVal);
        iwpgvTableChart.draw();
      }
      break;

    default:
      break;
  }

  // iwpgvChart.setOption(chartOptionKey(fieldId), fieldVal);
  // iwpgvChart.draw();

  // chart = setChartOption(fieldId, fieldVal, chart);
};

// Chart custom error handler
function chartErrorHandler(error) {
  console.log(error);

  // Display custom error message
  const adminMessage = document.querySelector(".iwpgv .admin-messages");
  const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`;
  if (adminMessage) adminMessage.innerHTML = message;

  // Remove google default error
  google.visualization.errors.removeError(error.id);
}

function numRangeOneTimeReadyHandler() {
  document.getElementById(
    "iwpgv__numRangeOptions[minValue]"
  ).value = numRangeSlider.getState().lowValue;
  document.getElementById(
    "iwpgv__numRangeOptions[maxValue]"
  ).value = numRangeSlider.getState().highValue;
  // const columnRange = dataTable.getColumnRange(
  //   chart.numRangeOptions.filterColumnIndex * 1
  // );
  // // dataTable.getColumnRange(columnIndex);
  // document.getElementById("iwpgv__numRangeOptions[minValue]").value =
  //   1 * columnRange.min.toFixed(2);
  // document.getElementById("iwpgv__numRangeOptions[maxValue]").value =
  //   1 * columnRange.max.toFixed(2);
  // chart.numRangeOptions.minValue = columnRange.min;
  // chart.numRangeOptions.maxValue = columnRange.max;
}

function rangeSliderStateChangeHandler() {
  if (chart.minMaxTableChartOptions && settings.enableMinMaxTable) {
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
    "iwpgv__numRangeOptions[minValue]"
  ).value = numRangeSlider.getState().lowValue;
  document.getElementById(
    "iwpgv__numRangeOptions[maxValue]"
  ).value = numRangeSlider.getState().highValue;

  // iwpgvMinMaxAvg = DrawMinMaxTable(chart);
  // iwpgvMinMaxAvg.draw();

  // Draw Min Max table chart
  //  DrawMinMaxTable(chart)
}

// const numberFilteredTable = function () {
//   let dt = dataTable.clone();

//   let i = 0;

//   while (i < dataTable.getNumberOfColumns()) {
//     if (i < dt.getNumberOfColumns()) {
//       if (
//         dt.getColumnType(i) !== "number" ||
//         (dt.getColumnType(i) === "number" &&
//           dt.getColumnRole(i) !== "domain" &&
//           dt.getColumnRole(i) !== "data")
//       ) {
//         dt.removeColumn(i);
//         i = -1;
//       }
//     }
//     i++;
//   }

//   return dt;
// };

function fetchMinMaxDataTable(data) {
  // Intialize the join datatable

  let i = settings.includeMinMaxTableFirstCol ? 0 : 1;

  let groupData = [];


  while (i < data.getNumberOfColumns()) {
    const aggregateData = getJointTableRow(data, i)
    if (aggregateData) {
      groupData.push(
        google.visualization.data.group(data, ...aggregateData)
      );
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
  if (groupData === 2) return joinData
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

