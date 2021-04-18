// Toggle spinner
const toggleElement = function (elementId) {
  const element = document.getElementById(elementId);

  if (element && element.classList.contains("hidden")) {
    showElement(elementId);
  } else {
    hideElement(elementId);
  }
};

// Show Element
const showElement = function (elementId) {
  const element = document.getElementById(elementId);
  if (element) element.classList.remove("hidden");
};

// Hide Element
const hideElement = function (elementId) {
  const element = document.getElementById(elementId);
  if (element) element.classList.add("hidden");
};

const displayAdminMessage = function (message) {
  document.querySelector(".iwpgv .admin-messages").innerHTML = message;
};

// Show sheet select field
const setSheetId = function (spreadsheet) {
  const sheetId = document.getElementById("iwpgv__chartParams[sheetId]");
  sheetId.options.length = 0;

  sheetId.options[sheetId.options.length] = new Option(
    "Select Sheet",
    "",
    false,
    false
  );
  for (const $prop in spreadsheet) {
    sheetId.options[sheetId.options.length] = new Option(
      spreadsheet[$prop]["sheetName"],
      $prop,
      false,
      false
    );
  }
  sheetId.closest(".form-group").classList.remove("hidden");
};

// Show chart type select field
const setChartTypeId = function (chartType) {
  chartTypeSelect = document.getElementById("iwpgv__chartType");
  chartTypeSelect.value = chartType;
  chartTypeSelect.closest(".form-group").classList.remove("hidden");
};

const togglePanel = function (panelId) {
  const panel = document.getElementById(panelId);
  // Get the next sibling to the .accordion__toggle class
  let siblings = panel.previousElementSibling;
  let toggle;
  while (siblings) {
    if (siblings.classList.contains("accordion__toggle")) toggle = siblings;
    siblings = siblings.previousElementSibling;
  }

  // Get the svg element inside the class with .accordion__toggle
  const svg = toggle.querySelector("svg use");

  if (panel.classList.contains("active")) {
    panel.classList.remove("visible");
    panel.classList.remove("active");
    panel.classList.add("hidden");
    svg.href.baseVal =
      svg.href.baseVal.split("svg#icon")[0] + "svg#icon-keyboard_arrow_right";
  } else {
    panel.classList.add("visible");
    panel.classList.add("active");
    panel.classList.remove("hidden");
    svg.href.baseVal =
      svg.href.baseVal.split("svg#icon")[0] + "svg#icon-keyboard_arrow_down";
  }
};

const setChartWidth = function (chart) {
  let width;
  const chartRect = document
    .getElementById("iwpgv__dashboard")
    .getBoundingClientRect();
  let widthInput = document.getElementById("iwpgv__chartOptions[width]");
  if (widthInput.value * 1 < 100) {
    width = 0.01 * widthInput.value * chartRect.width;
  } else {
    width = "100%";
    widthInput.value = 100;
  }

  return width;
};

const setDependentFields = function (el) {
  const dependents = el.dataset.dependents;
  const elVal = el.type === "checkbox" ? el.checked : el.value;
  dependents.split(",").forEach((dependent) => {
    elementDiv = document.getElementById(`iwpgv__${dependent}`);
    if (!elVal) {
      elementDiv.disabled = true;
    } else {
      elementDiv.disabled = false;
    }
  });
};

// const toggleDashboard = function () {
//   const dashboard = document.getElementById("iwpgv__dashboard");
//   if (dashboard.classList.contains("hidden")) {
//     dashboard.classList.remove("hidden");
//   } else {
//     dashboard.classList.add("hidden");
//   }
// };

// // Show
// const showDashboard= function(target) {
//   target.classList.remove("hidden");
// }

// // Hide
// const hideSDashboard = function(target) {
//   target.classList.add("hidden");
// }

// const toggleWarning = function () {
//   const warning = document.querySelector(".iwpgv .chart-view .warning");
//   if (warning.classList.contains("hidden")) {
//     warning.classList.remove("hidden");
//   } else {
//     warning.classList.add("hidden");
//   }
// };

// const setFieldSuffix = function (el, fieldVal) {
//   // Add suffix to all form fields with suffix (i.e. %)
//   const suffix = el.parentNode.querySelector(".form-group__suffix");
//   if (suffix) {
//     // var xx = new google.visualization.NumberFormat({ suffix: "%" });
//     // formatter.formatValue(fieldVal);
//     return `${fieldVal}${suffix.innerHTML}`;
//     //return fieldVal;
//     // return `${fieldVal}${suffix.innerText}`;
//   }

//   return fieldVal;
// };

const formatArrayFields = function (el) {
  if (!el.value) return null;
  const arr = el.value.split(",");
  return arr.map((element) => 1 * element);
};

// const setChartWidthHeight = function (chart, target) {
//   let targetInput = document.getElementById(`iwpgv__chartOptions[${target}]`);

//   // Get the width and height of the dashboard container div and set chart width and height to 75% of the container div.
//   const chartRect = document
//     .getElementById("iwpgv__dashboard")
//     .getBoundingClientRect();

//   if (target === 'width') {
//     if (targetInput.value <= 100) {
//       return Math.round(0.01 * targetInput.value * chartRect[target]);
//     } else {
//       targetInput.value = 100;
//       return chartRect[target];
//     }
//   } else {
//     return targetInput.value;
//   }

//   // if (chart.chartOptions.heightSuffix === "percent") {
//   //   chartHeight = chartHeight <= 100 ? chartHeight : 100;
//   //   height = Math.round(Math.max(400, 0.01 * chartHeight * chartRect.height));
//   // } else {
//   //   height = chartHeight;
//   // }
// };

// Get chart option key from feld Id
function chartOptionKey(fieldId) {
  if (!fieldId) return;
  const parts = fieldId.split("[");
  if (parts.length == 0) return;

  const controlWrapper = parts[0].split("__")[1];

  // console.log(fieldId)

  let optionKey;

  switch (parts.length) {
    case 6:
      optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
        parts[3].split("]")[0]
      }.${parts[4].split("]")[0]}.${parts[5].split("]")[0]}`;
      break;

    case 5:
      optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
        parts[3].split("]")[0]
      }.${parts[4].split("]")[0]}`;
      break;

    case 4:
      optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
        parts[3].split("]")[0]
      }`;
      break;

    case 3:
      optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}`;
      break;

    case 2:
      optionKey = parts[1].split("]")[0];
      break;

    default:
      optionKey = null;
      break;
  }

  return [controlWrapper, optionKey];
}

// Get chart option key from feld Id
function setChartOption(fieldId, fieldVal, chart) {
  if (!fieldId) return;

  // console.log(fieldId)

  const parts = fieldId.split("[");
  if (parts.length == 0) return chart;

  let controlWrapper = parts[0].split("__")[1];
  switch (controlWrapper) {
    case "pieChartOptions":
    case "horAxisOptions":
    case "leftAxisOptions":
    case "rightAxisOptions":
    case "seriesOptions":
    case "trendlinesOptions":
      controlWrapper = "chartOptions";
      break;
  }

  let part1 = "";
  let part2 = "";
  let part3 = "";
  let part4 = "";
  let part5 = "";
  let part6 = "";

  switch (parts.length) {
    case 7:
      part1 = parts[1].split("]")[0];
      part2 = parts[2].split("]")[0];
      part3 = parts[3].split("]")[0];
      part4 = parts[4].split("]")[0];
      part5 = parts[5].split("]")[0];
      part6 = parts[6].split("]")[0];
      chart[controlWrapper][part1][part2][part3][part4][part5][
        part6
      ] = fieldVal;
      break;

    case 6:
      part1 = parts[1].split("]")[0];
      part2 = parts[2].split("]")[0];
      part3 = parts[3].split("]")[0];
      part4 = parts[4].split("]")[0];
      part5 = parts[5].split("]")[0];
      chart[controlWrapper][part1][part2][part3][part4][part5] = fieldVal;
      break;

    case 5:
      part1 = parts[1].split("]")[0];
      part2 = parts[2].split("]")[0];
      part3 = parts[3].split("]")[0];
      part4 = parts[4].split("]")[0];
      chart[controlWrapper][part1][part2][part3][part4] = fieldVal;
      break;

    case 4:
      part1 = parts[1].split("]")[0];
      part2 = parts[2].split("]")[0];
      part3 = parts[3].split("]")[0];
      chart[controlWrapper][part1][part2][part3] = fieldVal;
      break;

    case 3:
      part1 = parts[1].split("]")[0];
      part2 = parts[2].split("]")[0];
      chart[controlWrapper][part1][part2] = fieldVal;
      break;

    case 2:
      part1 = parts[1].split("]")[0];
      chart[controlWrapper][part1] = fieldVal;
      break;

    default:
      break;
  }

  return chart;
}

const fetchMinMaxDataTable = function (data) {
  // Intialize the join datatable

  // let i = $settings.includeMinMaxTableFirstCol ? 0 : 1;
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
};

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

module.exports = {
  toggleElement,
  showElement,
  hideElement,
  setSheetId,
  setChartTypeId,
  togglePanel,
  displayAdminMessage,
  setChartWidth,
  setDependentFields,
  chartOptionKey,
  setChartOption,
  // toggleWarning,
  // setChartFieldsOptions,
  // setChartWidthHeight,
  // setFieldSuffix,
  formatArrayFields,
  // toggleDashboard,
  fetchMinMaxDataTable,
};
