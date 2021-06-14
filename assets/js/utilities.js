const fontFamily = () => {

  return {

    "" : "Select Font Family",
    Arial : "Arial",
    Valto: "Balto",
    "Times New Roman": "Times New Roman",
    "Courier New": "Courier New",
    "Droid Sans": "Droid Sans",
    "Droid Serif": "Droid Serif",
    "Droid Sans Mono": "Droid Sans Mono",
    "Gravitas One": "Gravitas One",
    "Old Standard TT": " Old Standard TT",
    "Open Sans": "Open Sans",
    Overpass: "Overpass",
    "PT Sans Narrow": "PT Sans Narrow",
    Raleway: "Raleway"
    
  }

}

const colors = () => {

  return [		
    "#b71c1c", "#0d47a1", "#004d40", "#e65100", "#581845", "#795548", "#0097A7", "#558B2F", '#1976D2', "#212121", '#00796B', '#455A64', '#263238', '#303F9F', '#33691E', '#7B1FA2', '#EF6C00', '#FFA000'
  ]
}


const chartTypes = () => {

  return {
    "": "Select Chart Type",
    LineChart: "Line Chart",
    ScatterChart: "Scatter Chart",
    BarChart: "Bar Chart",
    ColumnChart: "Column Chart",
    PieChart: "Pie Chart",
  }

}


const displayAdminMessage = (message, status, iwpgvObj) => {
  const messageDiv = document.querySelector(`.${iwpgvObj.prefix}__admin .admin-messages`)
  if ( messageDiv ) messageDiv.innerHTML = `<div class='notice notice-${status} is-dismissible'><p>${message}</p></div>`
}


// Show Element
const showElementById =  (elementId) => {
  const element = document.getElementById(elementId)
  if (element) element.classList.remove("hidden")
}

// Hide Element
const hideElementById = (elementId) => {
  const element = document.getElementById(elementId)
  if (element) element.classList.add("hidden")
}


// Toggle spinner
const toggleElementById = (elementId) => {
  const element = document.getElementById(elementId)
  if (element && element.classList.contains("hidden")) {
    showElementById(elementId)
  } else {
    hideElementById(elementId)
  }
}


// Show Element
const showElementByClass = (elementClass) => {
  const element = document.querySelector(elementClass)
  if (element) element.classList.remove("hidden")
}

// Hide Element
const hideElementByClass = (elementClass) => {
  const element = document.querySelector(elementClass)
  if (element) element.classList.add("hidden")
}


// Toggle spinner
const toggleElementByClass = function (elementClass) {
  const element = document.querySelector(elementClass)
  if (element && element.classList.contains("hidden")) {
    showElementByClass(elementClass)
  } else {
    hideElementByClass(elementClass)
  }
}



// Show input fields
const showInputField = function ( fieldId ) {

  const element = document.getElementById( fieldId )
  element.classList.remove( "hidden" );
  element.closest(".form-group").classList.remove( "hidden" )

}


// Hide input fields
const hideInputField = function ( fieldId ) {

  const element = document.getElementById( fieldId )
  element.classList.add( "hidden" );
  element.closest( ".form-group" ).classList.add( "hidden" )

}



// Show input fields
const toggleInputField = function ( fieldId ) {

  const element = document.getElementById(fieldId)
  if (element.classList.contains( "hidden" ) ) {
    showInputField( fieldId )
  } else {
    hideInputField( fieldId )
  }

}





const fetchTableChartData = ( chart, spreadsheet ) => {

  // Set table header values
  const headerValues = []
  for ( let  i = 0; i < spreadsheet[chart.fileUpload.sheetId].labels.length; i++ ) {
    headerValues.push([`<b>${spreadsheet[chart.fileUpload.sheetId].labels[i]}</b>`]);
  }
  chart.tableChart.options.header.values = headerValues

    // Set table header alignment
  // chart.tableChart.options.header.align = [chart.tableChart.options.firstColAlign, chart.tableChart.options.header.align]


  // Round cells values if rounding is not 0
  // if ( chart.tableChart.options.rounding) {
  //   const cellValues = []
  //   for ( let  i = 0; i < spreadsheet[chart.fileUpload.sheetId].data.length; i++ ) {
  //     cellValues[i] =[]
  //     for ( let  j = 0; j < spreadsheet[chart.fileUpload.sheetId].data[i].length; j++ ) {
  //       cellValues[i][j] = ( spreadsheet[chart.fileUpload.sheetId].data[i][j].toFixed( chart.tableChart.options.rounding ) ) 
  //     }  
  //   }
  //   chart.tableChart.options.cells.values = cellValues  
  // } else {
    chart.tableChart.options.cells.values = spreadsheet[chart.fileUpload.sheetId].data
  // }

    // Set table cells alignment
    // chart.tableChart.options.cells.align = [chart.tableChart.options.firstColAlign, chart.tableChart.options.cells.align]
    // chart.tableChart.options.header.align = [chart.tableChart.options.firstColAlign, chart.tableChart.options.header.align]

  // Set table even and odd row colors
  // const rowFillColors = []
  // for ( let  j = 0; j < spreadsheet[chart.fileUpload.sheetId].data[0].length; j++ ) {
    // rowFillColors[j] = (j % 2 === 0) ? chart.tableChart.options.oddRowColor : chart.tableChart.options.evenRowColor
  // }
  // chart.tableChart.options.cells.fill.color = [rowFillColors]

  return chart.tableChart.options

}






const fetchMinMaxAvgTableData = (chart, spreadsheet, xAxisMin = null, xAxisMax = null) => {

  //  // Set table header
  //  const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
  //  chart.minMaxAvgTable.header.values = headerValues

  //  chart.minMaxAvgTable.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)

   // Set table cells alignment
  //  chart.minMaxAvgTable.cells.align = [chart.minMaxAvgTable.firstColAlign , chart.minMaxAvgTable.cells.align]
  //  chart.minMaxAvgTable.header.align = [chart.minMaxAvgTable.firstColAlign , chart.minMaxAvgTable.header.align]

   // Set table even and odd row colors
  //  const rowFillColors = []
  //  for ( let  j = 0; j < spreadsheet[chart.fileUpload.sheetId].data[0].length; j++ ) {
  //    rowFillColors[j] = (j % 2 === 0) ? chart.minMaxAvgTable.evenRowColor : chart.minMaxAvgTable.oddRowColor
  //  }
  //  chart.minMaxAvgTable.cells.fill.color = [rowFillColors]

   

}






const getMinMaxAvgData = function (chart, spreadsheet, xAxisMin = null, xAxisMax = null ) {

  console.log("CCHH", chart)


  const min = ( xAxisMin ) ? xAxisMin : chart.layout.xaxis.range[0]
  const max = ( xAxisMax ) ? xAxisMax : chart.layout.xaxis.range[1]

  // Remove first row from data (xaxis data)
  const data = [...spreadsheet[chart.fileUpload.sheetId].data]
  
  const indeces = []
  let i=0
  data[0].forEach(element => {
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
  for ( const property in Object.values(spreadsheet[chart.fileUpload.sheetId].labels)) {
    minMaxAvgTableData[0].push(Object.values(spreadsheet[chart.fileUpload.sheetId].labels)[property])
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

    if ( newElement.length ) {

    
      const average = newElement.reduce((a, c) => a + c,) / newElement.length

      // Round if rounding is set
      if ( chart.minMaxAvgTable.rounding) {
        minMaxAvgTableData[1].push( parseFloat( Math.min( ...newElement ).toFixed( chart.minMaxAvgTable.rounding ) ) )
        minMaxAvgTableData[2].push( parseFloat( average.toFixed( chart.minMaxAvgTable.rounding ) ) )
        minMaxAvgTableData[3].push( parseFloat( Math.max( ...newElement ).toFixed( chart.minMaxAvgTable.rounding ) ) )
      } else {
        minMaxAvgTableData[1].push( Math.min(...newElement ) )
        minMaxAvgTableData[2].push( average )
        minMaxAvgTableData[3].push( Math.max( ...newElement ) )
      }

    } else {
      minMaxAvgTableData[1].push( null )
      minMaxAvgTableData[2].push( null )
      minMaxAvgTableData[3].push( null )
    }

  });
  
  return minMaxAvgTableData

}











// const removePanel =  ( panelId ) => {

//   if (panelId)  {
//     panelId.previousSibling.remove()
//     panelId.remove()
//   }

// }









// const showchartParamsInputFields = ( iwpgvObj ) => {

//   showInputField( `${iwpgvObj.prefix}__chartParams[fileUpload]` )
//   // showInputField( `${iwpgvObj.prefix}__chartParams[chartId]` )
//   showInputField( `${iwpgvObj.prefix}__chartParams[sheetId]` )
//   showInputField( `${iwpgvObj.prefix}__chartParams[chartType]` )
//   // showInputField( `${iwpgvObj.prefix}__chartParams[enableRangeSlider]` )
//   // showInputField( `${iwpgvObj.prefix}__chartParams[enableTableChart]` )
//   showInputField( `${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]` )

// }


const showPanels = ( ) => {

  document.querySelector( `.accordion__toggle.chartLayout` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.chartLayout` ).classList.remove("hidden")
  document.querySelector( `.accordion__toggle.chartTraces` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.chartTraces` ).classList.remove("hidden")
  // document.querySelector( `.accordion__toggle.tableChart` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.tableChart` ).classList.remove("hidden")
  document.querySelector( `.accordion__toggle.minMaxAvgTableChart` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.minMaxAvgTableChart` ).classList.remove("hidden")

}



const hidePanels = ( ) => {

  document.querySelector( `.accordion__toggle.chartLayout` ).classList.add("hidden")
  document.querySelector( `.accordion__content.chartLayout` ).classList.add("hidden")
  document.querySelector( `.accordion__toggle.chartTraces` ).classList.add("hidden")
  document.querySelector( `.accordion__content.chartTraces` ).classList.add("hidden")
  // document.querySelector( `.accordion__toggle.tableChart` ).classList.add("hidden")
  // document.querySelector( `.accordion__content.tableChart` ).classList.add("hidden")
  document.querySelector( `.accordion__toggle.minMaxAvgTableChart` ).classList.add("hidden")
  document.querySelector( `.accordion__content.minMaxAvgTableChart` ).classList.add("hidden")

}
































// const getTableHeader = function (sheet) {
//   const tableHeader  = []
//   for ( const property in Object.values(sheet.labels)) {
//     tableHeader.push([`<b>${Object.values(sheet.labels)[property]}</b>`])
//   }
//   return tableHeader
// }


// const getMinMaxAvgTableHeader = function () {
 
//   return  [["<b>Trace</b>"], ["<b>Min</b>"], ["<b>Average</b>"], ["<b>Max</b>"]]

// }












// const getPlotData = function (sheet) {

//   // Format data for plotly plot
//   const plotData = []
//   for (var j = 0; j < Object.keys(sheet.labels).length; j++) {
//     plotData[j] = []
//     for (var i=0; i < sheet.data.length; i++) {
//       var row = sheet.data[i];
//       plotData[j].push(Object.values(row)[j]) 
//     }
//   }

//   // rest data to 3 decimal places if numeric
//   newPlotData = []
//   plotData.forEach(element => {
//     const newElement = []
//     let k = 0
//     for ( let j = 0; j < element.length; j++) {
//       if ( typeof element[j] === "number") {
//         newElement[k] = parseFloat(element[j].toFixed(3))
//       } else {
//         newElement[k] = element[j]
//       }
//       k++
//     }
   
//     newPlotData.push(newElement)
//   })

//   return newPlotData
// }



// const getXAxisMinMax = function(data) {
 
//   return {"xAxisMin": Math.min(data), "xAxisMax": Math.max(data)}

// }


// const getLayout = function (chart, xAxisMin = null, xAxisMax = null) {
  
//   return {
//     paper_bgcolor:chart.chartLayout.paper_bgcolor,
//     plot_bgcolor:chart.chartLayout.plot_bgcolor,
//     height:chart.chartLayout.height,
//     title: {
//       text: chart.chartLayout.title.text,
//       x: chart.chartLayout.title.x,
//       y: chart.chartLayout.title.y,
//       font: {
//         family: chart.chartLayout.title.font.family,
//         size: chart.chartLayout.title.font.size,
//         color: chart.chartLayout.title.font.color,
//       },
//       xaxis: {
//         automargin: true,
//         rangeslider: {
//           visible:(chart.chartParams.enableChartRangeSlider)? true : false,
//           bgcolor:"teal",
//           thickness: 0.2,
//           // range: [xAxisMin, xAxisMax]
//         },
        
//         // autorange: 'reversed'
//         // rangeselector: selectorOptions,
//       },
//       yaxis: {
//         fixedrange: true,
//         // autorange: 'reversed'
//       },
//       autosize: true, // set autosize to rescale,
//       // xref: "paper",
     
//       // xanchor:"auto",

//       // yref: "paper",
     
//       // yanchor:"auto"
//     },
   
//     //  width:800,
    
//   }

// }



// const getConfig = function () {
//   const config = {
//     responsive: true,
//     displayModeBar: false
//   }
//   return config
// }


// const getTraces = function(jsonRes, plotData) {

//   const  chartType = jsonRes.chart.chartParams.chartType
  
//   let traces = []

//   switch(chartType) {

//     case 'LineChart': 
//     case 'ScatterChart':
//       for (var j=1; j < Object.keys(jsonRes.sheet.labels).length; j++) {
//         traces.push({ 
//           x : plotData[0],
//           y : plotData[j],
//           type: "scatter",
//           mode: 'lines+markers',
//           line: {
//             color:jsonRes.colors[j],
//             width: jsonRes.lineWidth
//           },
//           marker: {
//             color: jsonRes.colors[j],
//             size: jsonRes.markerSize
//           },
//           name: Object.values(jsonRes.sheet.labels)[j],
//           connectgaps: jsonRes.chart.chartLayout.connectgaps
//         })
//       }

//       break
  
//     case 'PieChart':  // if (x === 'value2')
//         traces.push({ 
//           labels : plotData[0],
//           values : plotData[1],
//           type: "pie"
//         })
      
    
//       break
  
//     default:
//       traces = `${chartType} is not a valid chart type`
      
//       break
//   }

//   return traces
// }


// const getTable = function (tableHeader, plotData) {

//   return [{
//     type: 'table',
//     header: {
//       values: tableHeader,
//       align: "center",
//       line: {width: 1, color: 'black'},
//       fill: {color: "grey"},
//       font: {family: "Arial", size: 12, color: "white"}
//     },
//     cells: {
//       values: plotData,
//       align: "center",
//       line: {color: "black", width: 1},
//       font: {family: "Arial", size: 11, color: ["black"]}
//     }
//   }]

// }


const createPanel = ( acCssClass, targetTitle, intro ) => {

   // Create accordion panel
   const ac = document.createElement( "div" )
   ac.classList.add( "ac", acCssClass )

   // Create intro p
   const introDiv = document.createElement( "div" )
   introDiv.classList.add( "ac-text", "intro" )

   // Create intro text and add to intro div
   const introText = document.createTextNode(intro)
   introDiv.appendChild(introText)

   // Create ac header
   const acHeader = document.createElement( "h2" )
   acHeader.classList.add( "ac-header" )

   // Create trigger button
   const acTrigger = document.createElement( "div" )
   acTrigger.classList.add( "ac-trigger" )

   // Create heading title and add to trigger button
   const headingTitle = document.createTextNode( targetTitle )
   acTrigger.appendChild( headingTitle )

   // Add trigger button to header
   acHeader.appendChild( acTrigger )

   // Add header to ac
   ac.appendChild( acHeader )

   // Create accordion content
   const acPanel = document.createElement( "div" )
   acPanel.classList.add( "ac-panel" )

  // Add p tag to content
  acPanel.appendChild(introDiv)

   // Add content to ac
   ac.appendChild(acPanel)

   return ac

}




const fetchformGroup = (field, prefix) => {

  // const field = row.inputFields[el]
  // console.log("FIELD", field)


  // Create form group
  const formGroup = document.createElement( "div" )
  formGroup.classList.add("form-group");

  // Create input/select field and add css class
  let inputField = field.type === "select" ? document.createElement("select"): document.createElement("input")
  inputField.classList.add("form-group__input")

  // Add field type or options depending on whether input field is select or otherwise
  if (field.type === "select") {

    inputField.classList.add("form-group__input-select")
    // Remove all options
    inputField.options.length = 0

    // Add options
    for (const prop in field.options) {
      inputField.options[inputField.options.length] = new Option(
        field.options[prop],
        prop,
        false,
        false
      );
    }
  } else {
    inputField.type = field.type
    if (field.type === "checkbox") inputField.classList.add("form-group__input-checkbox")
    if (field.type === "color") inputField.classList.add("form-group__input-color")
  }

  // Set field id, name, classlist and value attributes
  inputField.id = `${prefix}__${field.id}`
  inputField.name = `${prefix}__${field.id}`
  if (field.type === "checkbox") {
    if (field.value) inputField.checked = true
  } else {
    inputField.value = field.value
  }
  if (field.cssClasses) {
    for (const cssClass in field.cssClasses) {
      inputField.classList.add(field.cssClasses[cssClass])
    }
  }

  // add min, number input max, max, step AND PLAVE HOLDERif any
  if ( field.min ) inputField.min = field.min
  if ( field.max ) inputField.max = field.max
  if ( field.step ) inputField.step = field.step
  if (field.title ) inputField.placeholder = field.title
  if ( field.readOnly ) inputField.readOnly = true
  if ( field.disabled ) inputField.disabled = true
  if ( field.required ) inputField.required = true

  // Add form group box to form group
  formGroup.appendChild(inputField)

    // Create label
    if (field.type !== "hidden") {
    const labelElem = document.createElement( "label" )
    labelElem.classList.add("form-group__label");
    labelElem.htmlFor = `${prefix}__${field.id}`
    const labelText = field.title
      ? document.createTextNode( field.title )
      : document.createTextNode( "\u00A0" );
    labelElem.appendChild( labelText );
    formGroup.appendChild( labelElem );
  }

  // Add hint to form group
  if (field.hint) {

    // Create tooltip div
    const tooltip = document.createElement("div")
    tooltip.classList.add("form-group__tooltip")

    if (field.type === "checkbox") tooltip.classList.add("form-group__tooltip-ttCheckbox")
    if (field.type === "color") tooltip.classList.add("form-group__tooltip-ttColor")


    // Create tooltip question mark span and add to tooltip div
    const tooltipQuestionMarkDiv = document.createElement("div")
    tooltipQuestionMarkDiv.classList.add("form-group__tooltip-question-mark")
    const tooltipQuestionMark = document.createTextNode("?")
    tooltipQuestionMarkDiv.appendChild(tooltipQuestionMark)
    tooltip.appendChild(tooltipQuestionMarkDiv)

    // Create tooltip hint and ad to tooltip
    const tooltipHintDiv = document.createElement("div")
    tooltipHintDiv.classList.add("form-group__tooltip-hint")
    const hintText = document.createTextNode(field.hint)
    tooltipHintDiv.appendChild(hintText)
    tooltip.appendChild(tooltipHintDiv)

    // Add tooltip to form group
    formGroup.appendChild(tooltip)

  } 

  // hide for group if input field has a hidden class
  if (field.cssClasses && field.cssClasses.includes("hidden")) {
    formGroup.classList.add("hidden")
  }

  return formGroup
  
}


const createPanelSectionsOld = ( sections, prefix, level2AccordionDivCssClass, level2AcDivCssClass, level3AccordionDiv, level3AcDivCssClass ) => {

  for ( const section in sections ) {

    // const level3AcDivCssClass = `traces${index-1}${section}Ac`

    // Add trace panel to accordion
    level3AccordionDiv.appendChild( createPanel(  `${level3AcDivCssClass}`, sections[section].title, sections[section].intro ) )

    const acPanel = document.querySelector( `${level2AccordionDivCssClass} .${level2AcDivCssClass} .ac-panel .${level3AcDivCssClass} .ac-panel`)


    for (const fieldRow in sections[section].fieldGroups) {
        
      const row = sections[section].fieldGroups[fieldRow];

      //Create field group and add apprpriate css classes
      const fieldGroup = document.createElement( "div" )
      if (row.cssClasses) {
        for (const cssClass in row.cssClasses) {
          fieldGroup.classList.add(row.cssClasses[cssClass])
        }
      }
      
      // Loop through fields
      for (const el in row.inputFields) {

        const formGroup = fetchformGroup( row.inputFields[el], prefix )
        
        fieldGroup.appendChild( formGroup )
        // console.log(fieldGroup)

        // Add field group to content
        // if (Object.keys(panelSections).length > 1) {
          acPanel.appendChild( fieldGroup )
        // } else {
          // panelContent.appendChild( fieldGroup )
        // }

      }

    }

    // return acPanel

  }

}




const createPanelSections = ( sections, sectionsContainer, optionId, prefix ) => {

  // Loop through all sections
  for ( const section in sections ) {

    if (Object.values(sections).length > 1 ) {
      sectionsContainer.appendChild( createPanel(  `${optionId}_${section}Ac`, sections[section].title, sections[section].intro ) )
      fieldGroupsContainer = sectionsContainer.querySelector( `.${optionId}_${section}Ac .ac-panel`)
    } else {
      fieldGroupsContainer = sectionsContainer
      
      //Create intro paragraph and add it to sestion container
      const introP = document.createElement("p")
      introP.classList.add("ac-text", "intro")
      introText = document.createTextNode( sections[section].intro )
      introP.appendChild(introText)
      fieldGroupsContainer.appendChild(introP)
    }

    // Loop rhtough sections 
    for (const fieldRow in sections[section].fieldGroups) {
        
      const row = sections[section].fieldGroups[fieldRow];

      //Create field group and add apprpriate css classes
      const fieldGroup = document.createElement( "div" )
      if (row.cssClasses) {
        for (const cssClass in row.cssClasses) {
          fieldGroup.classList.add(row.cssClasses[cssClass])
        }
      }
      
      // Loop through fields
      for (const el in row.inputFields) {
        const formGroup = fetchformGroup( row.inputFields[el], prefix )
        fieldGroup.appendChild( formGroup )
        fieldGroupsContainer.appendChild( fieldGroup )
      }

    }

  }

}





// Show sheet select field
const setSheetIdOptions = (spreadsheet, sheetIdInput) => {

  // Remove all options
  sheetIdInput.options.length = 0

  // Add default option
  sheetIdInput.options[sheetIdInput.options.length] = new Option( "Select Sheet", "", false, false )

  // Loop through all sheets in the spreadsheet and set options
  for (const prop in spreadsheet) {
    sheetIdInput.options[sheetIdInput.options.length] = new Option( spreadsheet[prop]["sheetName"], prop, false, false ) 
  }

}

// const appendFormSaveBtn = function (form, iwpgvObj) {

//   const saveChartBtn = document.createElement("button")
//   saveChartBtn.classList.add("button")
//   saveChartBtn.classList.add("button-primary")
//   saveChartBtn.id = `${iwpgvObj.prefix}__saveChart`
//   saveChartBtn.name = `${iwpgvObj.prefix}__saveChart`
//   saveChartBtn.disabled = true;
//   const saveChartBtnText = document.createTextNode("Save Chart")
//   saveChartBtn.appendChild(saveChartBtnText)
//   form.appendChild(saveChartBtn)

// }













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
  fontFamily,
  colors,
  chartTypes,
  displayAdminMessage,
  showElementById,
  hideElementById,
  toggleElementById,
  showElementByClass,
  hideElementByClass,
  toggleElementByClass,
  showInputField,
  hideInputField,
  toggleInputField,
  createPanel,
  createPanelSections,
  setSheetIdOptions,
  showPanels,
  hidePanels,
  fetchTableChartData,
  fetchMinMaxAvgTableData,
  getMinMaxAvgData,
  chartOptionKey,
  fetchformGroup
};
