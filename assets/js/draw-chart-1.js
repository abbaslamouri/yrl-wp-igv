import Plotly from 'plotly.js-dist'
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
import renderPanels from "./panels"
import Accordion from "./Accordion"
import {
  toggleElementById,
  showElementById,
  hideElementById,
  displayAdminMessage,
  chartOptionKey,
} from "./utilities"

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

    // set chart configuration
    chart.chartConfig = chart.chartLayout.config
    
    // const chartConfigInstance = new ChartConfig( chart.chartConfig, iwpgvObj )
    // panels.chartConfig = chartConfigInstance.panel()
    // chart.chartConfig = chartConfigInstance.options()

    // Assemble chart traces chart and panels
    panels.chartTraces = {
      id : `${iwpgvObj.prefix}__chartTracesPanel`,
      cssClasses : ['chartTraces', 'chart'],
      title : "Traces",
      sections : {}
    }

    let index = 1;
    while (index < spreadsheet[chart.chartParams.sheetId].labels.length) {
      const chartTraceInstance =  new ChartTrace( chart.chartTraces[index-1], index, iwpgvObj, chart, spreadsheet  ) 
      chart.chartTraces[index-1] = chartTraceInstance.options()
      panels.chartTraces.sections[index-1] = {}
      panels.chartTraces.sections[index-1].id = `${iwpgvObj.prefix}__chartTracesPanel__trace[${index-1}]`
      panels.chartTraces.sections[index-1].title = (spreadsheet[chart.chartParams.sheetId].labels.length) > 1 ? Object.values(spreadsheet[chart.chartParams.sheetId].labels)[index] : ""
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
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, chart.chartTraces, chart.chartLayout, chart.chartConfig).then (function() {

      toggleElementById( `${iwpgvObj.prefix}__spinner` )
      console.log("Done plotting Chart")

    })

    // Add range slider event handler
    yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
      const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...spreadsheet[chart.chartParams.sheetId].data[0])
      const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.sheetId].data[0])
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
    })


    // Unhide and set chart Table if enableTableChart is true
    if ( chart.chartParams.enableTableChart ) {

      // Assemble table chart and panels layout
      const tableChartConfigInstance = new TableChart( chart.tableChartConfig, iwpgvObj, "tableChartConfig", "Table Chart") 
      panels.tableChartConfig = tableChartConfigInstance.panel()
      chart.tableChartConfig = tableChartConfigInstance.options()

      // Set table header values
      const headerValues = []
      for ( let  i = 0; i < spreadsheet[chart.chartParams.sheetId].labels.length; i++ ) {
        headerValues.push([`<b>${spreadsheet[chart.chartParams.sheetId].labels[i]}</b>`]);
      }
      chart.tableChartConfig.header.values = headerValues


      // Set table default cell values
      chart.tableChartConfig.cells.values = spreadsheet[chart.chartParams.sheetId].data

      // Round cells values if rounding is not 0
      if ( chart.tableChartConfig.rounding) {
        const cellsValues = []
        for ( let  i = 0; i < spreadsheet[chart.chartParams.sheetId].data.length; i++ ) {
          cellsValues[i] =[]
          for ( let  j = 0; j < spreadsheet[chart.chartParams.sheetId].data[i].length; j++ ) {
            cellsValues[i][j] = ( parseFloat( spreadsheet[chart.chartParams.sheetId].data[i][j].toFixed( chart.tableChartConfig.rounding ) ) )
          }  
        }
        chart.tableChartConfig.cells.values = cellsValues  
      }

      // Set table cells alignment
      chart.tableChartConfig.cells.align = [chart.tableChartConfig.firstColAlign , chart.tableChartConfig.otherColsAlign]

      // Set table even and odd row colors
      const rowFillColors = []
      for ( let  j = 0; j < spreadsheet[chart.chartParams.sheetId].data[0].length; j++ ) {
        rowFillColors[j] = (j % 2 === 0) ? chart.tableChartConfig.evenRowColor : chart.tableChartConfig.oddRowColor
      }
      chart.tableChartConfig.cells.fill.color = [rowFillColors]

      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChartConfig], chart.tableChartConfig.layout, chart.chartConfig).then (function() {

        console.log("Done plotting Table")

      })
    
    }


    // Unhide and set chart Table if enableMinMaxTableChart is true
    if ( chart.chartParams.enableMinMaxTableChart ) {

      // Assemble Min/Max table chart and panels layout
      const minMaxTableChartConfigInstance = new TableChart( chart.minMaxTableChartConfig, iwpgvObj, "minMaxTableChartConfig", "Min/Max/Avg Table Chart") 
      panels.minMaxTableChartConfig = minMaxTableChartConfigInstance.panel()
      chart.minMaxTableChartConfig = minMaxTableChartConfigInstance.options()

      // Set table header
      const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
      chart.minMaxTableChartConfig.header.values = headerValues

      // Remove first row from data (xaxis data)
      const data = [...spreadsheet[chart.chartParams.sheetId].data]
      data.shift()

      // Set cell values
      const cellsValues = [[],[],[],[]];

      // Fetch header row and format as fist column for min-max-avg first column
      for ( const property in Object.values(spreadsheet[chart.chartParams.sheetId].labels)) {
        cellsValues[0].push(Object.values(spreadsheet[chart.chartParams.sheetId].labels)[property])
      }

      // Remove first column header
      cellsValues[0].shift()

      // Loop through plot data and remove all non-numeric rows (min, max and average require numbic data) and calculate min, max and avg
      data.forEach(element => {
        const newElement = []
        let k = 0
        for ( let j = 0; j <= element.length; j++) {
          if ( typeof element[j] === "number") {
            newElement[k] = element[j]
            k++
          }
        }
        const average = newElement.reduce((a, c) => a + c,) / newElement.length

        // Round if rounding is set
        if ( chart.tableChartConfig.rounding) {
          cellsValues[1].push( parseFloat( Math.min( ...newElement ).toFixed( chart.tableChartConfig.rounding ) ) )
          cellsValues[2].push( parseFloat( average.toFixed( chart.tableChartConfig.rounding ) ) )
          cellsValues[3].push( parseFloat( Math.max( ...newElement ).toFixed( chart.tableChartConfig.rounding ) ) )
        } else {
          cellsValues[1].push( Math.min(...newElement ) )
          cellsValues[2].push( average )
          cellsValues[3].push( Math.max( ...newElement ) )
        }
      });

      chart.minMaxTableChartConfig.cells.values = cellsValues

      // Set table cells alignment
      chart.minMaxTableChartConfig.cells.align = [chart.minMaxTableChartConfig.firstColAlign , chart.minMaxTableChartConfig.otherColsAlign]

      // Set table even and odd row colors
      const rowFillColors = []
      for ( let  j = 0; j < spreadsheet[chart.chartParams.sheetId].data[0].length; j++ ) {
        rowFillColors[j] = (j % 2 === 0) ? chart.minMaxTableChartConfig.evenRowColor : chart.minMaxTableChartConfig.oddRowColor
      }
      chart.minMaxTableChartConfig.cells.fill.color = [rowFillColors]


      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [chart.minMaxTableChartConfig], chart.minMaxTableChartConfig.layout, chart.chartConfig).then (function() {

        console.log("Done plotting MIn Max Table")

      })




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

      console.log(event.target.classList)

          // save chart
      if (event.target.id === `${iwpgvObj.prefix}__saveChart`) {
        // event.preventDefault();
        saveChart(chart, iwpgvObj);
        return
      }

      // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
      if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ) return 

      // Rabge Min and Range Max handlere
      if (event.target.id === `${iwpgvObj.prefix}__rangeMinInput` || event.target.id == `${iwpgvObj.prefix}__rangeMaxInput` ) {
        const newXAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value ?  document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value : xAxisMin
        const newXAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value ? document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value : xAxisMax

        const update = {
          'xaxis.range': [newXAxisMin, newXAxisMax],   // updates the xaxis range
        }
        Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, update)

        return
      }

      // Chart layout event handler
      if ( event.target.classList.contains( `${iwpgvObj.prefix}__chartLayout` ) ) {

        if (chartOptionKey(event.target.id).key.includes("config")) {
          console.log("PPPPPP",chartOptionKey(event.target.id).key.split(".")[1])
          console.log({ [chartOptionKey(event.target.id).key.split(".")[1]]: event.target.type === 'checkbox' ? event.target.checked : event.target.value} )
          Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)

          chart.chartConfig[chartOptionKey(event.target.id).key.split(".")[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
          console.log("GGGGGGG",chart.chartConfig )
          Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, chart.chartTraces, chart.chartLayout, chart.chartConfig)
          // Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, {}, { [chartOptionKey(event.target.id).key.split(".")[1]]: event.target.type === 'checkbox' ? event.target.checked : event.target.value} )
        } else {
          Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { [chartOptionKey(event.target.id).key]: event.target.type === 'checkbox' ? event.target.checked : event.target.value})
        }

      }

      // Chart traces event handler
      if ( event.target.classList.contains( `${iwpgvObj.prefix}__chartTraces` ) ) {

        console.log("Traces",chartOptionKey(event.target.id))
        const keyArr = chartOptionKey(event.target.id).key.split(".")
        console.log(keyArr)
        const traceNumber = keyArr.shift()
        const optionKey = keyArr.join(".")
        console.log(traceNumber, optionKey)
        console.log({ [optionKey]: event.target.type === 'checkbox' ? event.target.checked : event.target.value })

        Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: event.target.type === 'checkbox' ? event.target.checked : event.target.value }, traceNumber);


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
