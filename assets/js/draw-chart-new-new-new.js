import Plotly from 'plotly.js-basic-dist'
import ChartParams from "./ChartParams"
import ChartLayout from "./ChartLayout"
import ChartConfig from "./ChartConfig"
import ChartTrace from "./ChartTrace"
import {
  toggleElementById,
  showElementById,
  hideElementById,
  displayAdminMessage,
  setSheetIdOptions,
  unhideInputField
} from "./utilities"
import renderPanels from "./panels"
import Accordion from "./Accordion"

let form

const drawChart = async function ( spreadsheet, chart, iwpgvObj) {

  form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

  // Show spinner and hide warning, chart, table minmax table and minmax input fields
  toggleElementById( `${iwpgvObj.prefix}__spinner` )
  toggleElementById( `${iwpgvObj.prefix}__warning` )

  try {

    // Bail if no foem is found
    // if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

    // const sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value
    // console.log(sheetId)

    // Initialize panels
    const panels = {} 

    // // Set panels chart params
    // const chartParamsInstance = new ChartParams( chart.chartParams, iwpgvObj )
    // panels.chartParams = chartParamsInstance.panel()
    // chart.chartParams = chartParamsInstance.options()

    // Assemble chart and panels layout
    const chartLayoutInstance = new ChartLayout( chart.chartLayout, iwpgvObj ) 
    panels.chartLayout = chartLayoutInstance.panel()
    chart.chartLayout = chartLayoutInstance.options()


    const chartConfigInstance = new ChartConfig( chart.chartConfig, iwpgvObj )
    panels.chartConfig = chartConfigInstance.panel()
    chart.chartConfig = chartConfigInstance.options()

    // if ( typeof (spreadsheet[chart.chartParams.sheetId]) !== "undefined" && spreadsheet[chart.chartParams.sheetId]["labels"].length < chart.chartTraces.length ) {
    //   chart.chartTraces.splice(0,spreadsheet[chart.chartParams.sheetId]["labels"].length+1)
    // }

    // Assemble chart traces chart and panels
    panels.chartTraces = {
      "id" : `${iwpgvObj.prefix}__chartTracesPanel`,
      'cssClass' : ['chatTraces', 'chart'],
      "title" : "Traces",
      'sections' : {}
    }


    let index = 1;
    while (index < spreadsheet[chart.chartParams.sheetId]["labels"].length) {
      const chartTraceInstance =  new ChartTrace( chart.chartTraces[index-1], index, iwpgvObj, chart, spreadsheet  ) 
      chart.chartTraces[index-1] = chartTraceInstance.options()
      panels.chartTraces.sections[index-1] = {}
      panels.chartTraces.sections[index-1].id = `${iwpgvObj.prefix}__chartTracesPanel__trace[${index-1}]`
      panels.chartTraces.sections[index-1].title = (spreadsheet[chart.chartParams.sheetId]["labels"].length) > 1 ? Object.values(spreadsheet[chart.chartParams.sheetId]["labels"])[index] : ""
      panels.chartTraces.sections[index-1].fields = chartTraceInstance.panel()
      index++
    }

    console.log("KKKKKKK",chart)
    // Remove old acordion panel and Render Accordion Panels
    // form.innerHTML = ""
    renderPanels(panels, iwpgvObj);
    

    


    // // Set sheet Id select field
    // setSheetIdOptions(spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`))
    // document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = chart.chartParams.sheetId

    // Unhide sheed Id select field, chart type select field and enableChartRangeSlider, enableMinMaxTableChart, enableTableChart checkboxes
    // unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
    // unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) )
    // unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`) )
    // unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`) )
    // unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`) )

    // Enable save button
    document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false

    // Unhide and set range slider min and max input fields if enableChartRangeSlider
    if ( chart.chartParams.enableChartRangeSlider ) {
      showElementById( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...spreadsheet[chart.chartParams.sheetId].data[0]).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...spreadsheet[chart.chartParams.sheetId].data[0]).toFixed(3)

      // chart.chartLayout.xaxis.rangeslider.visible = true

    } else {
      hideElementById( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  null
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = null

      // chart.chartLayout.xaxis.rangeslider.visible = false
    }

    // Unhide chart div, Set its width and render plot
    // showElementById( `${iwpgvObj.prefix}__plotlyChart` )
    
     // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).innerHTML =""
     Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);


    // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`


    // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, chart.chartTraces, chart.chartLayout, chart.chartConfig)
    

    yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
      // console.log('eventData',eventData)
      const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...spreadsheet[chart.chartParams.sheetId].data[0])
      const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.sheetId].data[0])
      // console.log(x_start+"----"+x_end)
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
    })

    console.log("C", chart)
    console.log("P", panels)


  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } finally {

    toggleElementById( `${iwpgvObj.prefix}__spinner` )
    // showElementById( `${prefix}__plotlyChart` )

  }

  // Set accordion
  new Accordion( { collapsed: false } )

}




export default drawChart
