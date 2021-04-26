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
  setSheetIdOptions,
  unhideInputField
} from "./utilities"
import renderPanels from "./panels"
import Accordion from "./Accordion"

let form


const drawChart = async function ( spreadsheet, iwpgvCharts, iwpgvObj) {

  form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

  // Show spinner and hide warning, chart, table minmax table and minmax input fields
  toggleElement( `${iwpgvObj.prefix}__spinner` )
  toggleElement( `${iwpgvObj.prefix}__warning` )

  try {

    // Bail if no foem is found
    if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

    // Initialize panels
    const panels = {} 

    // Set panels chart params
    const chartParamsInstance = new ChartParams( iwpgvCharts.chart.chartParams, iwpgvObj )
    panels.chartParams = chartParamsInstance.panel()
    iwpgvCharts.chart.chartParams = chartParamsInstance.options()

    // Assemble chart and panels layout
    const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout, iwpgvObj ) 
    panels.chartLayout = chartLayoutInstance.panel()
    iwpgvCharts.chart.chartLayout = chartLayoutInstance.options()
    const chartConfigInstance = new ChartConfig( iwpgvCharts.chart.chartConfig, iwpgvObj )
    panels.chartConfig = chartConfigInstance.panel()
    iwpgvCharts.chart.chartConfig = chartConfigInstance.options()

    // console.log("LLLLLLL",{...iwpgvCharts.chart})
  

    // // Bail if either the file, sheed or chart type is missing
    // if (! iwpgvCharts.chart.chartParams.fileUpload || ! iwpgvCharts.chart.chartParams.sheetId || ! iwpgvCharts.chart.chartParams.chartType) {
    //   throw new Error(  "Please select a file, a sheet and chart type to proceed." )
    // }

    // console.log("ZZZZZZZQQQQQQQQ",  spreadsheet)

    console.log("NUMBERS", spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length, iwpgvCharts.chart.chartTraces.length)

    // if ( typeof (spreadsheet[iwpgvCharts.chart.chartParams.sheetId]) !== "undefined" && spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length < iwpgvCharts.chart.chartTraces.length ) {

    //   console.log("NUMBERS", spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length, iwpgvCharts.chart.chartTraces.length)
    //   iwpgvCharts.chart.chartTraces.splice(0,spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length+100)
    // }

    // Assemble chart traces chart and panels
    panels.chartTraces = {
      "id" : `${iwpgvObj.prefix}__chartTracesPanel`,
      'cssClass' : ['chatTraces', 'chart'],
      "title" : "Traces",
      'sections' : {}
    }


    let index = 1;
    while (index < spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length) {
      const chartTraceInstance =  new ChartTrace( iwpgvCharts.chart.chartTraces[index-1], index, iwpgvObj, iwpgvCharts, spreadsheet  ) 
      iwpgvCharts.chart.chartTraces[index-1] = chartTraceInstance.options()
      panels.chartTraces.sections[index-1] = {}
      panels.chartTraces.sections[index-1].id = `${iwpgvObj.prefix}__chartTracesPanel__trace[${index-1}]`
      panels.chartTraces.sections[index-1].title = (spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"].length) > 1 ? Object.values(spreadsheet[iwpgvCharts.chart.chartParams.sheetId]["labels"])[index] : ""
      panels.chartTraces.sections[index-1].fields = chartTraceInstance.panel()
      index++
    }
    
    // Remove old acordion panel and Render Accordion Panels
    form.innerHTML = ""
    renderPanels(panels, iwpgvObj);

    // Set sheet Id select field
    setSheetIdOptions(spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`))
    document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = iwpgvCharts.chart.chartParams.sheetId

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
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0]).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0]).toFixed(3)

      iwpgvCharts.chart.chartLayout.xaxis.rangeslider.visible = true

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
     // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).innerHTML =""
     Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);

    // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${iwpgvCharts.chart.chartLayout.width}%`
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, iwpgvCharts.chart.chartTraces, iwpgvCharts.chart.chartLayout, iwpgvCharts.chart.chartConfig)
    

    yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
      // console.log('eventData',eventData)
      const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0])
      const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[iwpgvCharts.chart.chartParams.sheetId].data[0])
      // console.log(x_start+"----"+x_end)
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
    })

    console.log("C", iwpgvCharts.chart)
    console.log("P", panels)


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
