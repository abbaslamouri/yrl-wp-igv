import Plotly from 'plotly.js-basic-dist'
import ChartTrace from "./ChartTrace"
import {
  toggleElement,
  showElement,
  hideElement,
  displayAdminMessage,
  getTableHeader,
  getMinMaxAvgTableHeader,
  getPlotData,
  getMinMaxAvgData,
  getXAxisMinMax,
  getLayout,
  getConfig,
  getTraces,
  getTable,
  setSheetId,
  setChartTypeId,
  setChartWidth,
  setChartArea,
  setDependentFields,
  chartOptionKey,
} from "./utilities"
import fetchData from "./fetch-data"
import renderChart from "./render-chart"
import renderPanels from "./panels"
import Accordion from "./accordion"
import colorPicker from "./color-picker"

// const iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}

// const iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}
// const prefix = iwpgvObj.prefix ? iwpgvObj.prefix : ""

// console.log("OBJ", iwpgvObj)
// console.log("CHART", iwpgvCharts)


// import colorPicker from "./color-picker"

const drawChart = async function (target, iwpgvObj, prefix, spreadsheet) {

  // Show spinner and hide warning, chart, table minmax table and minmax input fields
  toggleElement( `${prefix}__spinner` )
  hideElement( `${prefix}__warning` )
  hideElement( `${prefix}__plotlyChart` )
  hideElement( `${prefix}__plotlyTableContainer` )
  hideElement( `${prefix}__plotMinMaxTableContainer` )
  hideElement( `${prefix}__plotMinMax` )

  try {

    // Create a form data object based on input change
    const form = target.closest("form")

    // Bail if no foem is found
    if ( !form ) throw new Error(  `Something went terribly wrong, we cannot find form ${form}` )

    // Create form data object
    const formData = new FormData(form)

    // Initialize chart
    const chart = {
      "chartParams" : {},
      "chartTraces" : {}
    }

    const panels = {
      "chartTraces" :{
        "id" : `${prefix}__chartTracesPanel`,
        'cssClass' : ['chatTraces', 'chart'],
        "title" : "Traces",
        'sections' : {}
      }
    }

    // Loop throu the form data to to populate the chart and the panels
    for (const property of formData ) {
      const inputParams = chartOptionKey(property[0])
      const fieldId = inputParams.key
      const fieldValue = property[1]
      chart[inputParams.control][fieldId] = fieldValue
    }

    // Bail if either the file, sheed or chart type is missing
    if (! chart.chartParams.fileUpload || ! chart.chartParams.sheetId || ! chart.chartParams.chartType) {
      throw new Error(  "Please select a file, a sheet and chart type to proceed." )
    }
    // Assemble chart traces and add to panels
    // const chartTraces = Object.keys( chart.chartTraces ).length ? chart.chartTraces : {}

    // Set traces
    // chart[`${this.prefix}__chartTracesPanel`] = [];
    // panels.chartTraces = {
    //   "id" : `${prefix}__chartTracesPanel`,
    //   'cssClass' : ['chatTraces', 'chart'],
    //   "title" : "Traces",
    //   'sections' : []
    // }

    let index = 1;
    // let chartTrace = {}
    let chartTraceInstance = {}
    while (index < spreadsheet[chart.chartParams.sheetId]["labels"].length) {
      chart.chartTraces[index-1] = {}
      // chartTrace = ( Object.keys( chart.chartTraces ).length && chart.chartTraces[index-1] !== "undefined" ) ? chart.chartTraces[index-1] : {}
      chartTraceInstance = new ChartTrace( {}, index, spreadsheet[chart.chartParams.sheetId]["labels"], chart.chartParams.chartType, prefix )
      // $trace_options_instance = new TraceOption( $trace_option , index, $sheet['labels'], $chart_type );
      // console.log("VVVVVVVVV",chart.chartTraces[index-1])
      // console.log(Object.keys( chart.chartTraces ).length)
      // console.log("Instance",chartTraceInstance)
      // console.log("trace",chartTrace)
      // console.log("chart.chartTraces",chart.chartTraces)
      chart.chartTraces[index-1] = chartTraceInstance.options();
      chart.chartTraces[index-1]["x"] = spreadsheet[chart.chartParams.sheetId]["data"][0];
      chart.chartTraces[index-1]["y"] = spreadsheet[chart.chartParams.sheetId]["data"][index];
      panels.chartTraces.sections[index-1] = {}
      panels.chartTraces.sections[index-1].id = `${prefix}__chartTracesPanel__trace[${index}-1]`;
      panels.chartTraces.sections[index-1].title = (spreadsheet[chart.chartParams.sheetId]["labels"].length) > 1 ? Object.values(spreadsheet[chart.chartParams.sheetId]["labels"])[index] : "";
      panels.chartTraces.sections[index-1].fields = chartTraceInstance.panel();
      // console.log("INDEX", index)
      index++;
    }

    console.log("C", chart)
    console.log("P", panels)


    // Update field panels and chart
    // panels.chartTraces = chartTraceInstance.panel()
    // chart.chartTraces = chartTraceInstance.params()

    // console.log("P", panels)

    formData.append("action", iwpgvObj.fetch_chart_options_n_panels_action);
    formData.append("nonce", iwpgvObj.fetch_chart_options_n_panels_nonce);

    // Wait for response
    const jsonRes = await fetchData(formData)

    // console.log("jsonRes",jsonRes.panels)

    // Display message if any
    // if (jsonRes && jsonRes.message) displayAdminMessage(jsonRes.message)
    

    // Render chart and panels on success
    // if (jsonRes.status && jsonRes.status === "success") {
      
      // Remove old acordion panel and Render Accordion Panels
      // form.innerHTML = ""
      // renderPanels(panels)

      // Set sheet Id select field
      // setSheetId(jsonRes.spreadsheet, jsonRes.chart.chartParams.sheetId)

      // Set chart type select field to 
      // setChartTypeId(jsonRes.chart.chartParams.chartType)

     // Enable save button
      document.getElementById(`${prefix}__saveChart`).disabled = false

      // Get x-axis min and max
      let xAxisMin = getXAxisMinMax(jsonRes.sheet.data).xAxisMin.toFixed(3)
      let xAxisMax = getXAxisMinMax(jsonRes.sheet.data).xAxisMax.toFixed(3)

      // Get traces
      // const traces = getTraces(jsonRes, getPlotData(jsonRes.sheet))

      // Bail if traces does not come back as an array  (not an arraymeans invalid chart type)
      // if (! Array.isArray(traces)) throw new Error( traces )

      // Hide spinner
      toggleElement( `${prefix}__spinner` )

      // let traces = jsonRes.chart.traceOptions

      // console.log("TRACES", traces)

      // Unhide chart div, Set its width and render plot
      showElement( `${prefix}__plotlyChart` )

      document.getElementById(`${prefix}__plotlyChart`).style.width = `${jsonRes.chart.chartLayout.width}%`
      Plotly.newPlot(`${prefix}__plotlyChart`, chart.chartTraces, jsonRes.chart.chartLayout, getConfig())
      

      // Render and show table if enableTableChart is true
      if (jsonRes.chart.chartParams.enableTableChart){
        let layout = {
          title: "DATA TABLE"
        }
        showElement( `${prefix}__plotlyTable` )
        Plotly.newPlot(`${prefix}__plotlyTable`, getTable(getTableHeader (jsonRes.sheet), getPlotData(jsonRes.sheet)), layout) 
      }

      // Render and show min max table if enableMinMaxTableChart is true
      if (jsonRes.chart.chartParams.enableMinMaxTableChart){
        showElement( `${prefix}__plotlyMinMaxTable` )
        Plotly.newPlot(`${prefix}__plotlyMinMaxTable`, getTable(getMinMaxAvgTableHeader(), getMinMaxAvgData(jsonRes.sheet))) 
      }

      // Unhide and set range slider min and max input fields if enableChartRangeSlider
      if ( jsonRes.chart.chartParams.enableChartRangeSlider && ! isNaN(xAxisMin) && ! isNaN(xAxisMax) ) {
        showElement( `${prefix}__plotMinMax` )
        document.getElementById(`${prefix}__rangeMinInput`).closest(".form__group").classList.remove("hidden")
        document.getElementById(`${prefix}__rangeMinInput`).value =  xAxisMin
        document.getElementById(`${prefix}__rangeMaxInput`).closest(".form__group").classList.remove("hidden")
        document.getElementById(`${prefix}__rangeMaxInput`).value = xAxisMax
      }

      // set color pickers
      colorPicker(getLayout( jsonRes.chart, xAxisMin, xAxisMax), jsonRes.chart.traceOptions, getConfig())

      yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
        // console.log('eventData',eventData)
        const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : null
        const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : null
        // console.log(x_start+"----"+x_end)
        document.getElementById(`${prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
        document.getElementById(`${prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
      })



      // Add change event listener on all the document
      document.addEventListener("change", function (event) {
      
        event.preventDefault()

        // Bail if input id is range min or ranmge max
        if (event.target.id === `${prefix}__rangeMinInput` || event.target.id == `${prefix}__rangeMaxInput` ) {
          const newXAxisMin = document.getElementById(`${prefix}__rangeMinInput`).value ?  document.getElementById(`${prefix}__rangeMinInput`).value : xAxisMin
          const newXAxisMax = document.getElementById(`${prefix}__rangeMaxInput`).value ? document.getElementById(`${prefix}__rangeMaxInput`).value : xAxisMax

          const update = {
            'xaxis.range': [newXAxisMin, newXAxisMax],   // updates the xaxis range
          };
          Plotly.relayout(`${prefix}__plotlyChart`, update)

          return
        }

        // Bail if input does not have yrl_wp_igv__charts class
        if (!event.target.classList.contains(`${prefix}__chart`)) return

        // target element id and value
        let fieldId = event.target.id
        let fieldVal = event.target.type == "checkbox" ? event.target.checked : event.target.value
          
        console.log(chartOptionKey(fieldId), fieldVal)

        switch (chartOptionKey(fieldId).control) {
          case "chartLayout":
          case "pieChartOptions":
          case "horAxisOptions":
          case "leftAxisOptions":
          case "rightAxisOptions":
          case "seriesOptions":
          case "trendlinesOptions":
            let optionKeys = chartOptionKey(fieldId).key.split(".")
            console.log(optionKeys)
            switch (optionKeys.length) {
            //   case 6:
            //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
            //       parts[3].split("]")[0]
            //     }.${parts[4].split("]")[0]}.${parts[5].split("]")[0]}`
            //     break
          
            //   case 5:
            //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
            //       parts[3].split("]")[0]
            //     }.${parts[4].split("]")[0]}`
            //     break
          
            //   case 4:
            //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
            //       parts[3].split("]")[0]
            //     }`
            //     break
          
              case 3:
                layout[optionKeys[0]][optionKeys[1]][optionKeys[2]] = fieldVal
                break
          
              case 2:
                layout[optionKeys[0]][optionKeys[1]] = fieldVal
                break
          
              default:
                optionKey = null
                break
            }
            // layout[chartOptionKey(fieldId).key]
            console.log("LAYOUT", layout)
            Plotly.newPlot(`${prefix}__plotlyChart`, traces, layout, config)
            // iwpgvChart.setOption(chartOptionKey(fieldId).key, fieldVal)
            // iwpgvChart.draw()
            break
      
          case "numRangeOptions":
            numRangeSlider.setOption(chartOptionKey(fieldId).key, fieldVal)
            numRangeSlider.draw()
            break
      
          case "chartRangeOptions":
            chartRangeSlider.setOption(chartOptionKey(fieldId).key, fieldVal)
            chartRangeSlider.draw()
            console.log(chartRangeSlider.getOptions())
            break
      
          case "minMaxTableChartOptions":
            iwpgvMinMaxAvg.setOption(chartOptionKey(fieldId).key, fieldVal)
            iwpgvMinMaxAvg.draw()
            break
      
          default:
            break
        }
     
      }, false
    )


     // Set accordion
     new Accordion({ collapsed: false })



     

      // Set sheetId and chart type
      // const sheetId = chart.chartParams.sheetId || chart.chartParams.sheetId === 0 ? chart.chartParams.sheetId : null

      // console.log("ZZZZZZxxxxxxx",sheetId)
      
      // setSheetId(jsonRes.spreadsheet, sheetId)
      // setChartTypeId(chart.chartParams.chartType)
 
      

      
 

      // const allRows = jsonRes.sheet.data
      // const labels = jsonRes.sheet.labels

      // // Get chart
      // const chart = jsonRes.chart


      // const tableHeader  = []
      // for ( const property in Object.values(jsonRes.sheet.labels)) {
      //   tableHeader.push([`<b>${Object.values(jsonRes.sheet.labels)[property]}</b>`])
      // }
      // console.log("HEADER", tableHeader)
  
      // Assemble table data (transpose sheet data array of objects)
      // const tableData = []
      // for (var j = 0 j < Object.keys(labels).length j++) {
      //   tableData[j] = []
      //   for (var i=0 i < allRows.length i++) {
      //     var row = allRows[i]
      //     tableData[j].push(Object.values(row)[j])
      //   }
      // }
  
      // Assemble traces
      // let  traces = []
      // for (var j=1 j < Object.keys(labels).length j++) {
      //   traces.push({ 
      //     x : tableData[0],
      //     y : tableData[j],
      //     mode: 'lines+markers',
      //     line: {
      //       color:jsonRes.colors[j],
      //       width: 5
      //     },
      //     marker: {
      //       color: 'green',
      //       size: 8
      //     },
      //     name: Object.values(labels)[j],
      //     connectgaps: chart.chartLayout.connectgaps
      //   })
      // }
  
     

     

  
      // const layout = {
      //   paper_bgcolor:chart.chartLayout.paper_bgcolor,
      //   height:chart.chartLayout.height,

      //   title: {
      //     text: chart.chartLayout.title.text,
      //     font: {
      //       family: chart.chartLayout.title.font.family,
      //       size: chart.chartLayout.title.font.size,
      //       color: chart.chartLayout.title.font.color,
      //     },
      //     // xref: "paper",
      //     x: chart.chartLayout.title.x,
      //     // xanchor:"auto",

      //     // yref: "paper",
      //     y: chart.chartLayout.title.y,
      //     // yanchor:"auto"
      //   },
      //   xaxis: {
      //     // rangeselector: selectorOptions,
      //     rangeslider: {},
      //     automargin: true,
      //     // autorange: 'reversed'
      //   },
      //   yaxis: {
      //     fixedrange: true,
      //     // autorange: 'reversed'
      //   },
      //   autosize: true, // set autosize to rescale,
  
      //   //  width:800,
        

      //   plot_bgcolor:"red",
       
      // }
  
      // const config = {
      //   responsive: true
      // }

    
     
  
     
  
      // var data = [{
      //   type: 'table',
      //   header: {
      //     values: tableHeader,
      //     align: "center",
      //     line: {width: 1, color: 'black'},
      //     fill: {color: "grey"},
      //     font: {family: "Arial", size: 12, color: "white"}
      //   },
      //   cells: {
      //     values: tableData,
      //     align: "center",
      //     line: {color: "black", width: 1},
      //     font: {family: "Arial", size: 11, color: ["black"]}
      //   }
      // }]
      
      

      

    

      

     

     

      // // Set chart width and chart area width
      // const chartOptionsWidth = document.getElementById(
      //   `${prefix}__chartOptions[width]`
      // )
      // chart.chartLayout.width = setChartWidth(
      //   chartOptionsWidth,
      //   `${prefix}__dashboard`
      // )
      // chart.chartLayout.chartArea.width = setChartArea(
      //   chart.chartLayout.chartArea,
      //   chart.chartParams.chartType
      // ).width
      // chart.chartLayout.chartArea.height = setChartArea(
      //   chart.chartLayout.chartArea,
      //   chart.chartParams.chartType
      // ).height

      // // Set chart range width and chart area width
      // if (chart.chartParams.enableChartRangeSlider && chart.chartRangeOptions) {
      //   const chartRangeOptionsWidth = document.getElementById(
      //     `${prefix}__chartRangeOptions[ui][chartOptions][width]`
      //   )
      //   chart.chartRangeOptions.ui.chartOptions.width = setChartWidth(
      //     chartRangeOptionsWidth,
      //     `${prefix}__filter-min-max`
      //   )

      //   chart.chartRangeOptions.ui.chartOptions.chartArea.width = setChartArea(
      //     chart.chartRangeOptions.ui.chartOptions.chartArea,
      //     chart.chartParams.chartType
      //   ).width
      //   chart.chartRangeOptions.ui.chartOptions.chartArea.height = setChartArea(
      //     chart.chartRangeOptions.ui.chartOptions.chartArea,
      //     chart.chartParams.chartType
      //   ).height
      // }

      // // Set dependent fields
      // const hasDependents = document.querySelectorAll(".hasDependents")
      // if (hasDependents) {
      //   hasDependents.forEach((el) => {
      //     setDependentFields(el)
      //   })
      // }

      // chart.chartLayout.annotations.boxStyle.gradient.x1 = `${chart.charLayout.annotations.boxStyle.gradient.x1}%`
      // chart.chartLayout.annotations.boxStyle.gradient.x2 = `${chart.charLayout.annotations.boxStyle.gradient.x2}%`
      // chart.chartLayout.annotations.boxStyle.gradient.y1 = `${chart.charLayout.annotations.boxStyle.gradient.y1}%`
      // chart.chartLayout.annotations.boxStyle.gradient.y2 = `${chart.charLayout.annotations.boxStyle.gradient.y2}%`

      // // Render chart
      // let containers = {
      //   dashboard: `${prefix}__dashboard`,
      //   chart: `${prefix}__chart`,
      //   tableChart: `${prefix}__table-chart`,
      //   numRange: `${prefix}__num-range-filter`,
      //   chartRange: `${prefix}__chart-range-filter`,
      //   minMaxTableChart: `${prefix}__min-max-table-chart`,
      // }
      // renderChart(
      //   jsonRes.chart,
      //   jsonRes.spreadsheet[chart.chartParams.sheetId],
      //   containers
      // )

     

      // // Set color pickers
      // colorPicker()
    // }

  } catch (error) {

    const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`
    displayAdminMessage(message)
    console.log(error)

  } finally {

    // toggleElement( `${prefix}__spinner` )
    // showElement( `${prefix}__plotlyChart` )

  }
}

export default drawChart
