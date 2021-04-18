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
const setSheetId = function (spreadsheet, value) {
  // Get sheet node
  const sheetId = document.getElementById("iwpgv__chartParams[sheetId]");

  // Remove all options
  sheetId.options.length = 0;

  // Add default option
  sheetId.options[sheetId.options.length] = new Option(
    "Select Sheet",
    "",
    false,
    false
  );

  // Loop through all sheets in the spreadsheet and set options
  for (const $prop in spreadsheet) {
    sheetId.options[sheetId.options.length] = new Option(
      spreadsheet[$prop]["sheetName"],
      $prop,
      false,
      false
    );
  }

  // Set value
  if (value) {
    sheetId.value = value;
  } else {
    if (sheetId.options.length === 2) sheetId.value = sheetId.options[1].value;
  }

  // Add/Remove classes
  sheetId.classList.remove("hidden");
  sheetId.closest(".form-group").classList.remove("hidden");
  sheetId.closest(".field-group").classList.remove("hidden");
};

// Show chart type select field
const setChartTypeId = function (value) {
  chartTypeId = document.getElementById("iwpgv__chartParams[chartType]");
  chartTypeId.value = value;
  chartTypeId.classList.remove("hidden");
  chartTypeId.closest(".form-group").classList.remove("hidden");
  chartTypeId.closest(".field-group").classList.remove("hidden");
};

const setChartWidth = function (widthInput, boundingBoxId) {
  let width;
  const chartRect = document
    .getElementById(boundingBoxId)
    .getBoundingClientRect();
  if (widthInput.value * 1 < 100) {
    width = 0.01 * widthInput.value * chartRect.width;
  } else {
    width = "100%";
    widthInput.value = 100;
  }

  return width;
};

const setChartArea = function (chartArea, chartType) {
  let width;
  let height;
  if (chartType === "PieChart") {
    const chartAreaBgColor = chartArea.backgroundColor;
    for (const option in chartArea) {
      const optionInput = document.getElementById(
        `iwpgv__chartOptions[chartArea][${option}]`
      );
      if (optionInput) optionInput.disabled = true;
    }

    for (const option in chartAreaBgColor) {
      const optionInput = document.getElementById(
        `iwpgv__chartOptions[chartArea][backgroundColor][${option}]`
      );
      if (optionInput) optionInput.disabled = true;
      if (optionInput.classList.contains("color-picker")) {
        optionInput.classList.remove("color-picker");
      }
    }
    chartArea = null;
  } else {
    // Set chart area heigth and width
    width = chartArea.width ? `${chartArea.width}%` : null;
    height = chartArea.height ? `${chartArea.height}%` : null;
  }

  return { width, height };
};

const resizeChartArea = function (fieldId) {
  let fieldVal;
  const widthInput = document.getElementById(fieldId);
  if (widthInput.value < 100) {
    fieldVal = `${widthInput.value}%`;
  } else {
    fieldVal = "100%";
    widthInput.value = 100;
  }
  console.log(fieldVal);

  return fieldVal;
};

const setDependentFields = function (el) {
  const dependents = el.dataset.dependents.split(",");
  const elVal = el.type === "checkbox" ? el.checked : el.value;
  dependents.forEach((dependent) => {
    elementDiv = document.getElementById(`iwpgv__${dependent}`);
    if (!elVal) {
      elementDiv.disabled = true;
    } else {
      elementDiv.disabled = false;
    }
  });
};

const formatArrayFields = function (el) {
  if (!el.value) return null;
  const arr = el.value.split(",");
  return arr.map((element) => 1 * element);
};

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

  return { control: controlWrapper, key: optionKey };
}

module.exports = {
  toggleElement,
  showElement,
  hideElement,
  setSheetId,
  setChartTypeId,
  displayAdminMessage,
  setChartWidth,
  setChartArea,
  resizeChartArea,
  setDependentFields,
  chartOptionKey,
  formatArrayFields,
  // setChartOption,
  // // toggleWarning,
  // // setChartFieldsOptions,
  // // setChartWidthHeight,
  // // setFieldSuffix,
  // // toggleDashboard,
  // fetchMinMaxDataTable,
  // togglePanel,
};
