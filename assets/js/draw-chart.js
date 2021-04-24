import Plotly from 'plotly.js-dist'
import ChartParams from "./ChartParams"
import ChartLayout from "./ChartLayout"
import ChartConfig from "./ChartConfig"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
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

const drawChart = function ( spreadsheet, chart, iwpgvObj) {

  form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

  // Show spinner and hide warning, chart, table minmax table and minmax input fields
  toggleElementById( `${iwpgvObj.prefix}__spinner` )
  toggleElementById( `${iwpgvObj.prefix}__warning` )

  // Hide form
  toggleElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

  try {

    // Initialize panels
    const panels = {} 

    // Assemble chart and panels layout
    const chartLayoutInstance = new ChartLayout( chart.chartLayout, iwpgvObj ) 
    panels.chartLayout = chartLayoutInstance.panel()
    chart.chartLayout = chartLayoutInstance.options()


    const chartConfigInstance = new ChartConfig( chart.chartConfig, iwpgvObj )
    panels.chartConfig = chartConfigInstance.panel()
    chart.chartConfig = chartConfigInstance.options()

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

    

    
  

    

    // Unhide and set range slider min and max input fields if enableChartRangeSlider is true
    if ( chart.chartParams.enableRangeSlider ) {
      showElementById( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...spreadsheet[chart.chartParams.sheetId].data[0]).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...spreadsheet[chart.chartParams.sheetId].data[0]).toFixed(3)

      // Set range slider to true
      chart.chartLayout.xaxis.rangeslider = true
    } else {
      hideElementById( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  null
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = null

      // Set range slider to false
      chart.chartLayout.xaxis.rangeslider =false
    }

    // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, chart.chartTraces, chart.chartLayout, chart.chartConfig)
    // .then (function() {

    //   toggleElementById( `${iwpgvObj.prefix}__spinner` )
    //   console.log("Done plotting Chart")

    // })

    

    // Add range slider event handler
    yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
      const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...spreadsheet[chart.chartParams.sheetId].data[0])
      const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.sheetId].data[0])
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
    })


    // Unhide and set chart Table if if enableTableChart is true
    if ( chart.chartParams.enableTableChart ) {

      console.log("KKKKKKKZZZZZZ")
      // Assemble chart and panels layout
      const tableChartConfigInstance = new TableChart( chart.tableChartConfig, iwpgvObj, spreadsheet ) 
      panels.tableChartConfig = tableChartConfigInstance.panel()
      chart.tableChartConfig = tableChartConfigInstance.options()

      const headerValues = []
      for ( let  i = 0; i < spreadsheet[chart.chartParams.sheetId]["labels"].length; i++ ) {
        headerValues.push([`<b>${spreadsheet[chart.chartParams.sheetId]["labels"][i]}</b>`]);
      }
      console.log(headerValues)

      const cellsValues = []
      for ( let  i = 0; i < spreadsheet[chart.chartParams.sheetId]["labels"].length; i++ ) {
        cellsValues.push(spreadsheet[chart.chartParams.sheetId].data[i]);
      }
      console.log(cellsValues)

      chart.tableChartConfig.header.values = headerValues
      chart.tableChartConfig.cells.values = cellsValues

      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChartConfig])
      // .then (function() {

      //   toggleElementById( `${iwpgvObj.prefix}__spinner` )
      //   console.log("Done plotting Table")

      // })
    
    }

    console.log("C", chart)
    console.log("P", panels)

    // Render and display the accordion panels
    renderPanels(panels, iwpgvObj);

    // Enable save button
    document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false

    


    // Add change event listener on all the document
    document.addEventListener("change", function (event) {
      
      event.preventDefault()

      // Rabge Min and Range Max handlere
      if (event.target.id === `${iwpgvObj.prefix}__rangeMinInput` || event.target.id == `${iwpgvObj.prefix}__rangeMaxInput` ) {
        const newXAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value ?  document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value : xAxisMin
        const newXAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value ? document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value : xAxisMax

        const update = {
          'xaxis.range': [newXAxisMin, newXAxisMax],   // updates the xaxis range
        };
        Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, update)

        return
      }

    })

  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } finally {

    // Load accordion
    new Accordion( { collapsed: false } )

    // Show form
    toggleElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
    // toggleElementById( `${iwpgvObj.prefix}__spinner` )
    // showElementById( `${prefix}__plotlyChart` )

  }

  

}

export default drawChart
