import Plotly from 'plotly.js-basic-dist'
import ChartParams from "./ChartParams"
import ChartLayout from "./ChartLayout"
import ChartConfig from "./ChartConfig"
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
  setChartWidth,
  setChartArea,
  setDependentFields,
  chartOptionKey,
  unhideInputField
} from "./utilities"
import fetchData from "./fetch-data"
import renderChart from "./render-chart"
import renderPanels from "./panels"
import Accordion from "./Accordion"
import colorPicker from "./color-picker"

// const iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}

// const iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}
// const prefix = iwpgvObj.prefix ? iwpgvObj.prefix : ""

// console.log("OBJ", iwpgvObj)
// console.log("CHART", iwpgvCharts)


// import colorPicker from "./color-picker"

const drawChart = async function (iwpgvObj, iwpgvCharts, jsonRes) {

  // Show spinner and hide warning, chart, table minmax table and minmax input fields
  toggleElement( `${iwpgvObj.prefix}__spinner` )
  toggleElement( `${iwpgvObj.prefix}__warning` )
  // hideElement( `${prefix}__plotlyChart` )
  // hideElement( `${prefix}__plotlyTableContainer` )
  // hideElement( `${prefix}__plotMinMaxTableContainer` )
  // hideElement( `${prefix}__plotMinMax` )

  console.log("here")

  try {

    // throw new Error( "Hello There")

    // Create a form data object based on input change
    const form = target.closest("form")

    // Bail if no foem is found
    if ( !form ) throw new Error(  `Something went terribly wrong, we cannot find form ${form}` )

    // Create form data object
    const formData = new FormData(form)

    // Loop throu the form data to to populate the chart and the panels
    for (const property of formData ) {
      const inputParams = chartOptionKey(property[0])
      const fieldId = inputParams.key
      const fieldValue = property[1]
      iwpgvCharts.chart[inputParams.control][fieldId] = fieldValue
    }

    console.log("KKKKKK",{...iwpgvCharts.chart})

    // Initialize panels
    const panels = {} 

    // Set panels chart params
    const chartParamsInstance = new ChartParams( iwpgvCharts.chart.chartParams, iwpgvObj )
    panels.chartParams = chartParamsInstance.panel()
    iwpgvCharts.chart.chartParams = chartParamsInstance.options()

    // Assemble chart and panels layout
    const chartLayoutInstance = ( ! Object.keys(iwpgvCharts.chart.chartLayout).length )  ?  new ChartLayout( {}, iwpgvObj ) : new ChartLayout( iwpgvCharts.chart.chartLayout, iwpgvObj ) 
    panels.chartLayout = chartLayoutInstance.panel()
    iwpgvCharts.chart.chartLayout = chartLayoutInstance.options()  

    // Assemble chart and panels configuration
    const chartConfigInstance = (! Object.keys(iwpgvCharts.chart.chartConfig).length ) ?  new ChartConfig( {}, iwpgvObj ) : new ChartConfig( iwpgvCharts.chart.chartConfig, iwpgvObj )
    panels.chartConfig = chartConfigInstance.panel()
    iwpgvCharts.chart.chartConfig = chartConfigInstance.options()
  

    // Bail if either the file, sheed or chart type is missing
    if (! iwpgvCharts.chart.chartParams.fileUpload || ! iwpgvCharts.chart.chartParams.sheetId || ! iwpgvCharts.chart.chartParams.chartType) {
      throw new Error(  "Please select a file, a sheet and chart type to proceed." )
    }


    // Assemble chart traces chart and panels
    panels.chartTraces = {
      "id" : `${iwpgvObj.prefix}__chartTracesPanel`,
      'cssClass' : ['chatTraces', 'chart'],
      "title" : "Traces",
      'sections' : {}
    }

    let index = 1;
    while (index < jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length) {
      iwpgvCharts.chart.chartTraces[index-1] = {}
      const chartTraceInstance = (! Object.keys(iwpgvCharts.chart.chartTraces).length )? new ChartTrace( {}, index, iwpgvObj, iwpgvCharts, jsonRes  ) : new ChartTrace( iwpgvCharts.chart.chartTraces[index-1], index, iwpgvObj, iwpgvCharts, jsonRes  )  
      iwpgvCharts.chart.chartTraces[index-1] = chartTraceInstance.options()
      panels.chartTraces.sections[index-1] = {}
      panels.chartTraces.sections[index-1].id = `${iwpgvObj.prefix}__chartTracesPanel__trace[${index-1}]`
      panels.chartTraces.sections[index-1].title = (jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length) > 1 ? Object.values(jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"])[index] : ""
      panels.chartTraces.sections[index-1].fields = chartTraceInstance.panel()
      index++
    }
    

    // Remove old acordion panel and Render Accordion Panels
    form.innerHTML = ""
    renderPanels(panels, iwpgvObj);

    // set color pickers
    // colorPicker()

    // Set sheet Id select field
    setSheetId(jsonRes.spreadsheet, iwpgvCharts.chart.chartParams.sheetId, iwpgvObj)

    // Set chart type select field
    document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value = iwpgvCharts.chart.chartParams.chartType

    // Unhide sheed Id select field, chart type select field and enableChartRangeSlider, enableMinMaxTableChart, enableTableChart checkboxes
    unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
    unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) )
    unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableChartRangeSlider]`) )
    unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`) )
    unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`) )

    // Enable save button
    document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false

    // Unhide and set range slider min and max input fields if enableChartRangeSlider
    if ( iwpgvCharts.chart.chartParams.enableChartRangeSlider ) {
      showElement( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0]).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0]).toFixed(3)

      iwpgvCharts.chart.chartLayout.xaxis.rangeslider.visible = true
      // const update = {
      //   "xaxis.rangeslider.visible" : true  // updates the xaxis range
      //  }
      // Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, update)

        // Add change event listener on all the document
      // document.addEventListener("change", function (event) {
      
      //   event.preventDefault()

      //   // Bail if input id is range min or ranmge max
      //   if (event.target.id === `${iwpgvObj.prefix}__rangeMinInput` || event.target.id == `${iwpgvObj.prefix}__rangeMaxInput` ) {

      //     const update = {
      //       'xaxis.range': [
      //         document.getElementById( `${iwpgvObj.prefix}__rangeMinInput` ).value,
      //         document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).value
      //       ],   // updates the xaxis range
      //     };
      //     Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, update)
      //   }
      // })

    } else {
      hideElement( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  null
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = null

      iwpgvCharts.chart.chartLayout.xaxis.rangeslider.visible = false
    }

    // Unhide chart div, Set its width and render plot
    showElement( `${iwpgvObj.prefix}__plotlyChart` )
    // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${iwpgvCharts.chart.chartLayout.width}%`
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, iwpgvCharts.chart.chartTraces, iwpgvCharts.chart.chartLayout, iwpgvCharts.chart.chartConfig)

    yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
      // console.log('eventData',eventData)
      const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0])
      const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0])
      // console.log(x_start+"----"+x_end)
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
    })

    // // Render and show table if enableTableChart is true
    // if (jsonRes.chart.chartParams.enableTableChart){
    //   let layout = {
    //     title: "DATA TABLE"
    //   }
    //   showElement( `${iwpgvObj.prefix}__plotlyTable` )
    //   Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, getTable(getTableHeader (jsonRes.sheet), getPlotData(jsonRes.sheet)), layout) 
    // }

    // Render and show min max table if enableMinMaxTableChart is true
    // if (iwpgvCharts.chart.chartParams.enableMinMaxTableChart){
    //   showElement( `${iwpgvObj.prefix}__plotlyMinMaxTable` )
    //   Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, getTable(getMinMaxAvgTableHeader(), getMinMaxAvgData(jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data))) 
    // }


    console.log("C", iwpgvCharts.chart)
    console.log("P", panels)


    // Update field panels and chart
    // panels.chartTraces = chartTraceInstance.panel()
    // chart.chartTraces = chartTraceInstance.params()

    // console.log("P", panels)

    // formData.append("action", iwpgvObj.fetch_chart_options_n_panels_action);
    // formData.append("nonce", iwpgvObj.fetch_chart_options_n_panels_nonce);

    // Wait for response
    // const jsonRes = await fetchData(formData)

    // console.log("jsonRes",jsonRes.panels)

    // Display message if any
    // if (jsonRes && jsonRes.message) displayAdminMessage(jsonRes.message)
    

    // Render chart and panels on success
    // if (jsonRes.status && jsonRes.status === "success") {
      
      

      // Set sheet Id select field
      // setSheetId(jsonRes.spreadsheet, jsonRes.chart.chartParams.sheetId)

      // Set chart type select field to 
      // setChartTypeId(jsonRes.chart.chartParams.chartType)

    

      

      // Get traces
      // const traces = getTraces(jsonRes, getPlotData(jsonRes.sheet))

      // Bail if traces does not come back as an array  (not an arraymeans invalid chart type)
      // if (! Array.isArray(traces)) throw new Error( traces )

      // Hide spinner
      // toggleElement( `${iwpgvObj.prefix}__spinner` )

      // let traces = jsonRes.chart.traceOptions

      // console.log("TRACES", traces)

     
      

    

    //   

    

    //   



    //   // Add change event listener on all the document
    //   document.addEventListener("change", function (event) {
      
    //     event.preventDefault()

    //     // Bail if input id is range min or ranmge max
    //     if (event.target.id === `${prefix}__rangeMinInput` || event.target.id == `${prefix}__rangeMaxInput` ) {
    //       const newXAxisMin = document.getElementById(`${prefix}__rangeMinInput`).value ?  document.getElementById(`${prefix}__rangeMinInput`).value : xAxisMin
    //       const newXAxisMax = document.getElementById(`${prefix}__rangeMaxInput`).value ? document.getElementById(`${prefix}__rangeMaxInput`).value : xAxisMax

    //       const update = {
    //         'xaxis.range': [newXAxisMin, newXAxisMax],   // updates the xaxis range
    //       };
    //       Plotly.relayout(`${prefix}__plotlyChart`, update)

    //       return
    //     }

    //     // Bail if input does not have yrl_wp_igv__charts class
    //     if (!event.target.classList.contains(`${prefix}__chart`)) return

    //     // target element id and value
    //     let fieldId = event.target.id
    //     let fieldVal = event.target.type == "checkbox" ? event.target.checked : event.target.value
          
    //     console.log(chartOptionKey(fieldId), fieldVal)

    //     switch (chartOptionKey(fieldId).control) {
    //       case "chartLayout":
    //       case "pieChartOptions":
    //       case "horAxisOptions":
    //       case "leftAxisOptions":
    //       case "rightAxisOptions":
    //       case "seriesOptions":
    //       case "trendlinesOptions":
    //         let optionKeys = chartOptionKey(fieldId).key.split(".")
    //         console.log(optionKeys)
    //         switch (optionKeys.length) {
    //         //   case 6:
    //         //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
    //         //       parts[3].split("]")[0]
    //         //     }.${parts[4].split("]")[0]}.${parts[5].split("]")[0]}`
    //         //     break
          
    //         //   case 5:
    //         //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
    //         //       parts[3].split("]")[0]
    //         //     }.${parts[4].split("]")[0]}`
    //         //     break
          
    //         //   case 4:
    //         //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
    //         //       parts[3].split("]")[0]
    //         //     }`
    //         //     break
          
    //           case 3:
    //             layout[optionKeys[0]][optionKeys[1]][optionKeys[2]] = fieldVal
    //             break
          
    //           case 2:
    //             layout[optionKeys[0]][optionKeys[1]] = fieldVal
    //             break
          
    //           default:
    //             optionKey = null
    //             break
    //         }
    //         // layout[chartOptionKey(fieldId).key]
    //         console.log("LAYOUT", layout)
    //         Plotly.newPlot(`${prefix}__plotlyChart`, traces, layout, config)
    //         // iwpgvChart.setOption(chartOptionKey(fieldId).key, fieldVal)
    //         // iwpgvChart.draw()
    //         break
      
    //       case "numRangeOptions":
    //         numRangeSlider.setOption(chartOptionKey(fieldId).key, fieldVal)
    //         numRangeSlider.draw()
    //         break
      
    //       case "chartRangeOptions":
    //         chartRangeSlider.setOption(chartOptionKey(fieldId).key, fieldVal)
    //         chartRangeSlider.draw()
    //         console.log(chartRangeSlider.getOptions())
    //         break
      
    //       case "minMaxTableChartOptions":
    //         iwpgvMinMaxAvg.setOption(chartOptionKey(fieldId).key, fieldVal)
    //         iwpgvMinMaxAvg.draw()
    //         break
      
    //       default:
    //         break
    //     }
     
    //   }, false
    // )


     

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

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } finally {

    toggleElement( `${iwpgvObj.prefix}__spinner` )
    // showElement( `${prefix}__plotlyChart` )

  }

  // Set accordion
  new Accordion( { collapsed: false } )

}




export default drawChart
