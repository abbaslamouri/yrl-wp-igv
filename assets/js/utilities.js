const displayAdminMessage = function (message, status, iwpgvObj) {
  const messageDiv = document.querySelector(`.${iwpgvObj.prefix} .admin-messages`)
  if ( messageDiv ) messageDiv.innerHTML = `<div class='notice notice-${status} is-dismissible'><p>${message}</p></div>`
}


// Show Element
const showElementById = function (elementId) {
  const element = document.getElementById(elementId)
  if (element) element.classList.remove("hidden")
}

// Hide Element
const hideElementById = function (elementId) {
  const element = document.getElementById(elementId)
  if (element) element.classList.add("hidden")
}


// Toggle spinner
const toggleElementById = function (elementId) {
  const element = document.getElementById(elementId)
  if (element && element.classList.contains("hidden")) {
    showElementById(elementId)
  } else {
    hideElementById(elementId)
  }
}



// Show input fields
const showInputField = function (fieldId) {

  fieldId.classList.remove("hidden");
  fieldId.closest(".form-group").classList.remove("hidden")

}


// Hide input fields
const hideInputField = function (fieldId) {

  fieldId.classList.add("hidden");
  fieldId.closest(".form-group").classList.add("hidden")

}



// Show input fields
const toggleInputField = function (fieldId) {

  if (fieldId.classList.contains("hidden") ) {
    showInputField
  } else {
    hideInputField
  }

}







// const removePanel =  ( panelId ) => {

//   if (panelId)  {
//     panelId.previousSibling.remove()
//     panelId.remove()
//   }

// }









const showchartParamsInputFields = ( iwpgvObj ) => {

  showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
  showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) )
  showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`) )
  showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`) )
  showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`) )

}


const showPanels = ( ) => {

  document.querySelector( `.accordion__toggle.chartLayout` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.chartLayout` ).classList.remove("hidden")
  document.querySelector( `.accordion__toggle.chartTraces` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.chartTraces` ).classList.remove("hidden")
  document.querySelector( `.accordion__toggle.tableChart` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.tableChart` ).classList.remove("hidden")
  document.querySelector( `.accordion__toggle.minMaxAvgTableChart` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.minMaxAvgTableChart` ).classList.remove("hidden")

}



const hidePanels = ( ) => {

  document.querySelector( `.accordion__toggle.chartLayout` ).classList.add("hidden")
  document.querySelector( `.accordion__content.chartLayout` ).classList.add("hidden")
  document.querySelector( `.accordion__toggle.chartTraces` ).classList.add("hidden")
  document.querySelector( `.accordion__content.chartTraces` ).classList.add("hidden")
  document.querySelector( `.accordion__toggle.tableChart` ).classList.add("hidden")
  document.querySelector( `.accordion__content.tableChart` ).classList.add("hidden")
  document.querySelector( `.accordion__toggle.minMaxAvgTableChart` ).classList.add("hidden")
  document.querySelector( `.accordion__content.minMaxAvgTableChart` ).classList.add("hidden")

}
































const getTableHeader = function (sheet) {
  const tableHeader  = []
  for ( const property in Object.values(sheet.labels)) {
    tableHeader.push([`<b>${Object.values(sheet.labels)[property]}</b>`])
  }
  return tableHeader
}


const getMinMaxAvgTableHeader = function () {
 
  return  [["<b>Trace</b>"], ["<b>Min</b>"], ["<b>Average</b>"], ["<b>Max</b>"]]

}




const getMinMaxAvgData = function (chart, spreadsheet, xAxisMin = null, xAxisMax = null ) {

  const min = ( xAxisMin ) ? xAxisMin : Math.min(...spreadsheet[chart.chartParams.options.sheetId].data[0])
  const max = ( xAxisMax ) ? xAxisMax : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])

  // Remove first row from data (xaxis data)
  const data = [...spreadsheet[chart.chartParams.options.sheetId].data]
  
  const indeces = []
  let i=0
  data[0].forEach(element => {
    // console.log(element)
    if (element > min && element < max ) {
      indeces.push(i) 
    }
    i++
  })

  const newData = []
  data.forEach(element => {
    newData.push(element.slice(indeces[0],[...indeces].pop()+1 ))
  })  
  

  newData.shift()

  // Set cell values
  const minMaxAvgTableData = [[],[],[],[]];

  // Fetch header row and format as fist column for min-max-avg first column
  for ( const property in Object.values(spreadsheet[chart.chartParams.options.sheetId].labels)) {
    minMaxAvgTableData[0].push(Object.values(spreadsheet[chart.chartParams.options.sheetId].labels)[property])
  }

  // Remove first column header
  minMaxAvgTableData[0].shift()


  // Loop through plot data and remove all non-numeric rows (min, max and average require numbic data) and calculate min, max and avg
  newData.forEach(element => {
    const newElement = []
    let k = 0
    for ( let j = 0; j <= element.length; j++) {
      if ( typeof element[j] === "number" ) {
        newElement[k] = element[j]
        k++
      }
    }

    

    const average = newElement.reduce((a, c) => a + c,) / newElement.length

    // Round if rounding is set
    if ( chart.minMaxAvgTableChart.options.rounding) {
      minMaxAvgTableData[1].push( parseFloat( Math.min( ...newElement ).toFixed( chart.minMaxAvgTableChart.options.rounding ) ) )
      minMaxAvgTableData[2].push( parseFloat( average.toFixed( chart.minMaxAvgTableChart.options.rounding ) ) )
      minMaxAvgTableData[3].push( parseFloat( Math.max( ...newElement ).toFixed( chart.minMaxAvgTableChart.options.rounding ) ) )
    } else {
      minMaxAvgTableData[1].push( Math.min(...newElement ) )
      minMaxAvgTableData[2].push( average )
      minMaxAvgTableData[3].push( Math.max( ...newElement ) )
    }
  });
  
  return minMaxAvgTableData

}








const getPlotData = function (sheet) {

  // Format data for plotly plot
  const plotData = []
  for (var j = 0; j < Object.keys(sheet.labels).length; j++) {
    plotData[j] = []
    for (var i=0; i < sheet.data.length; i++) {
      var row = sheet.data[i];
      plotData[j].push(Object.values(row)[j]) 
    }
  }

  // rest data to 3 decimal places if numeric
  newPlotData = []
  plotData.forEach(element => {
    const newElement = []
    let k = 0
    for ( let j = 0; j < element.length; j++) {
      if ( typeof element[j] === "number") {
        newElement[k] = parseFloat(element[j].toFixed(3))
      } else {
        newElement[k] = element[j]
      }
      k++
    }
   
    newPlotData.push(newElement)
  })

  console.log("PLOT DATSA",newPlotData)

  return newPlotData
}



// const getXAxisMinMax = function(data) {
 
//   return {"xAxisMin": Math.min(data), "xAxisMax": Math.max(data)}

// }


const getLayout = function (chart, xAxisMin = null, xAxisMax = null) {
  
  return {
    paper_bgcolor:chart.chartLayout.paper_bgcolor,
    plot_bgcolor:chart.chartLayout.plot_bgcolor,
    height:chart.chartLayout.height,
    title: {
      text: chart.chartLayout.title.text,
      x: chart.chartLayout.title.x,
      y: chart.chartLayout.title.y,
      font: {
        family: chart.chartLayout.title.font.family,
        size: chart.chartLayout.title.font.size,
        color: chart.chartLayout.title.font.color,
      },
      xaxis: {
        automargin: true,
        rangeslider: {
          visible:(chart.chartParams.enableChartRangeSlider)? true : false,
          bgcolor:"teal",
          thickness: 0.2,
          // range: [xAxisMin, xAxisMax]
        },
        
        // autorange: 'reversed'
        // rangeselector: selectorOptions,
      },
      yaxis: {
        fixedrange: true,
        // autorange: 'reversed'
      },
      autosize: true, // set autosize to rescale,
      // xref: "paper",
     
      // xanchor:"auto",

      // yref: "paper",
     
      // yanchor:"auto"
    },
   
    //  width:800,
    
  }

}



const getConfig = function () {
  const config = {
    responsive: true,
    displayModeBar: false
  }
  return config
}


const getTraces = function(jsonRes, plotData) {

  const  chartType = jsonRes.chart.chartParams.chartType
  
  let traces = []

  switch(chartType) {

    case 'LineChart': 
    case 'ScatterChart':
      for (var j=1; j < Object.keys(jsonRes.sheet.labels).length; j++) {
        traces.push({ 
          x : plotData[0],
          y : plotData[j],
          type: "scatter",
          mode: 'lines+markers',
          line: {
            color:jsonRes.colors[j],
            width: jsonRes.lineWidth
          },
          marker: {
            color: jsonRes.colors[j],
            size: jsonRes.markerSize
          },
          name: Object.values(jsonRes.sheet.labels)[j],
          connectgaps: jsonRes.chart.chartLayout.connectgaps
        })
      }

      break
  
    case 'PieChart':  // if (x === 'value2')
        traces.push({ 
          labels : plotData[0],
          values : plotData[1],
          type: "pie"
        })
      
    
      break
  
    default:
      traces = `${chartType} is not a valid chart type`
      
      break
  }

 console.log(traces)
  return traces
}


const getTable = function (tableHeader, plotData) {

  return [{
    type: 'table',
    header: {
      values: tableHeader,
      align: "center",
      line: {width: 1, color: 'black'},
      fill: {color: "grey"},
      font: {family: "Arial", size: 12, color: "white"}
    },
    cells: {
      values: plotData,
      align: "center",
      line: {color: "black", width: 1},
      font: {family: "Arial", size: 11, color: ["black"]}
    }
  }]

}



// Show sheet select field
const setSheetIdOptions = function (spreadsheet, sheetIdInput) {

  // // Get sheet node
  // const sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)

  // Remove all options
  sheetIdInput.options.length = 0;

  // Add default option
  sheetIdInput.options[sheetIdInput.options.length] = new Option(
    "Select Sheet",
    "",
    false,
    false
  );

  // Loop through all sheets in the spreadsheet and set options
  for (const prop in spreadsheet) {
    sheetIdInput.options[sheetIdInput.options.length] = new Option(
      spreadsheet[prop]["sheetName"],
      prop,
      false,
      false
    );
  }

  // // Set value
  // if (value) {
  //   sheetIdInput.value = value;
  // } else {
  //   if (sheetIdInput.options.length === 2) sheetIdInput.value = sheetIdInput.options[1].value;
  // }
  // return sheetIdInput.value

  // Add/Remove classes
  // sheetIdInput.classList.remove("hidden");
  // sheetIdInput.closest(".form-group").classList.remove("hidden");
  // sheetIdInput.closest(".field-group").classList.remove("hidden");
}

const appendFormSaveBtn = function (form, iwpgvObj) {

  const saveChartBtn = document.createElement("button")
  saveChartBtn.classList.add("button")
  saveChartBtn.classList.add("button-primary")
  saveChartBtn.id = `${iwpgvObj.prefix}__saveChart`
  saveChartBtn.name = `${iwpgvObj.prefix}__saveChart`
  saveChartBtn.disabled = true;
  const saveChartBtnText = document.createTextNode("Save Chart")
  saveChartBtn.appendChild(saveChartBtnText)
  form.appendChild(saveChartBtn)

}













// Get chart option key from feld Id
function chartOptionKey(fieldId) {
  if (!fieldId) return;
  const parts = fieldId.split("[");
  if (parts.length == 0) return;

  const controlWrapper = parts[0].split("__")[1];

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
  displayAdminMessage,
  showElementById,
  hideElementById,
  toggleElementById,
  showInputField,
  hideInputField,
  toggleInputField,
  setSheetIdOptions,
  showchartParamsInputFields,
  showPanels,
  hidePanels,
  


  
  getTableHeader,
  getMinMaxAvgTableHeader,
  getPlotData,
  getMinMaxAvgData,
  // getXAxisMinMax,
  getLayout,
  getConfig,
  getTraces,
  getTable,
  
  chartOptionKey,
  appendFormSaveBtn
  // setChartOption,
  // // toggleWarning,
  // // setChartFieldsOptions,
  // // setChartWidthHeight,
  // // setFieldSuffix,
  // // toggleDashboard,
  // fetchMinMaxDataTable,
  // togglePanel,
};
