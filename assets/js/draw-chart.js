import Plotly from 'plotly.js-dist'
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
import renderPanel from "./renderPanel"
import Accordion from "./Accordion"
import {
  toggleElementById,
  showElementById,
  hideElementById,
  displayAdminMessage,
  chartOptionKey,
  getMinMaxAvgData
} from "./utilities"

let form

const drawChart = function ( spreadsheet, chart, iwpgvObj) {

  form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )


  // Show spinner and hide warning, chart, table minmax table and minmax input fields
  toggleElementById( `${iwpgvObj.prefix}__spinner` )
  toggleElementById( `${iwpgvObj.prefix}__warning` )

  // Hide form
  // toggleElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

  try {

    Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
    Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
    Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

    // Assemble chart Params chart and panels
    const chartLayoutInstance = new ChartLayout( chart.chartLayout.options, iwpgvObj )
    chart.chartLayout.options = chartLayoutInstance.options()
    chart.chartLayout.panel.sections = chartLayoutInstance.sections()

    // set chart configuration
    chart.chartConfig.options = chart.chartLayout.options.config

    // Render chart layout panel
    renderPanel(chart.chartLayout.panel, iwpgvObj)

    let index = 1;
    while (index < spreadsheet[chart.chartParams.options.sheetId].labels.length) {
      chart.chartTraces.options[index-1] = ( chart.chartTraces.options[index-1] !== undefined )? chart.chartTraces.options[index-1] : {}
      const chartTraceInstance =  new ChartTrace( chart.chartTraces.options[index-1], spreadsheet, index, chart.chartParams.options.sheetId, chart.chartParams.options.chartType, iwpgvObj ) 
      chart.chartTraces.options[index-1] = chartTraceInstance.options()
      chart.chartTraces.panel.sections[`trace_${index-1}`] = chartTraceInstance.sections()
      index++
    }

    // Render chart traces panel
    renderPanel(chart.chartTraces.panel, iwpgvObj)

    // Unhide and set range slider min and max input fields if enableChartRangeSlider is true
    if ( chart.chartParams.options.enableRangeSlider ) {
      showElementById( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)

      // Set range slider to true
      chart.chartLayout.options.xaxis.rangeslider = true
    } else {
      hideElementById( `${iwpgvObj.prefix}__plotMinMax` )
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  null
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = null

      // Set range slider to false
      chart.chartLayout.options.xaxis.rangeslider =false

    }

     // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
     Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartConfig.options).then (function() {

      toggleElementById( `${iwpgvObj.prefix}__spinner` )
      console.log("Done plotting Chart")

    })

    // Add range slider event handler
    yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
      const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...spreadsheet[chart.chartParams.options.sheetId].data[0])
      const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)

      chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, x_start, x_end)
      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
      // Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, chart.chartConfig).then (function() {

      //   console.log("Done plotting MIn Max Table")

      // })

      Plotly.restyle(`${iwpgvObj.prefix}__plotlyMinMaxTable`,  chart.minMaxAvgTableChart.options );
    })

    // Set chart Table if enableTableChart is true
    if ( chart.chartParams.options.enableTableChart ) {

      // Assemble table chart and panels layout
      const tableChartInstance = new TableChart( chart.tableChart.options, iwpgvObj, "tableChart", "Table Chart" )
      chart.tableChart.options = tableChartInstance.options()
      chart.tableChart.panel.sections = tableChartInstance.sections()

      // Render chart traces panel
      renderPanel(chart.tableChart.panel, iwpgvObj)

      // Set table header values
      const headerValues = []
      for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].labels.length; i++ ) {
        headerValues.push([`<b>${spreadsheet[chart.chartParams.options.sheetId].labels[i]}</b>`]);
      }
      chart.tableChart.options.header.values = headerValues


      // Round cells values if rounding is not 0
      if ( chart.tableChart.options.rounding) {
        const cellsValues = []
        for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
          cellsValues[i] =[]
          for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
            cellsValues[i][j] = ( parseFloat( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( chart.tableChart.options.rounding ) ) )
          }  
        }
        chart.tableChart.options.cells.values = cellsValues  
      } else {
        // Set table default cell values
        chart.tableChart.options.cells.values = spreadsheet[chart.chartParams.options.sheetId].data
      }

      // Set table cells alignment
      chart.tableChart.options.cells.align = [chart.tableChart.options.firstColAlign , chart.tableChart.options.otherColsAlign]

      // Set table even and odd row colors
      const rowFillColors = []
      for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
        rowFillColors[j] = (j % 2 === 0) ? chart.tableChart.options.evenRowColor : chart.tableChart.options.oddRowColor
      }
      chart.tableChart.options.cells.fill.color = [rowFillColors]

      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartConfig).then (function() {

        console.log("Done plotting Table")

      })
    
    }

    // Unhide and set chart Table if enableMinMaxTableChart is true
    if ( chart.chartParams.options.enableMinMaxTableChart ) {

      // Assemble Min/Max table chart options and panel
      const minMaxAvgTableChartInstance = new TableChart( chart.minMaxAvgTableChart.options, iwpgvObj, "minMaxAvgTableChart", "Min/Max/Avg Table Chart" )
      chart.minMaxAvgTableChart.options = minMaxAvgTableChartInstance.options()
      chart.minMaxAvgTableChart.panel.sections = minMaxAvgTableChartInstance.sections()

      // Render chart traces panel
      renderPanel(chart.minMaxAvgTableChart.panel, iwpgvObj)

      // Set table header
      const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
      chart.minMaxAvgTableChart.options.header.values = headerValues

      const xAxisMin = Math.min(...spreadsheet[chart.chartParams.options.sheetId].data[0])
      const xAxisMax = Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])

      chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)

      // Set table cells alignment
      chart.minMaxAvgTableChart.options.cells.align = [chart.minMaxAvgTableChart.options.firstColAlign , chart.minMaxAvgTableChart.options.otherColsAlign]

      // Set table even and odd row colors
      const rowFillColors = []
      for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
        rowFillColors[j] = (j % 2 === 0) ? chart.minMaxAvgTableChart.options.evenRowColor : chart.minMaxAvgTableChart.options.oddRowColor
      }
      chart.minMaxAvgTableChart.options.cells.fill.color = [rowFillColors]


      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, chart.chartConfig).then (function() {

        console.log("Done plotting MIn Max Table")

      })

    }

    // Enable save button
    document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false

    document.querySelectorAll(`.accordion__toggle`).forEach (element=> {
      element.classList.remove("hidden")
    })

    document.querySelectorAll(`.accordion__content`).forEach (element=> {
      element.classList.remove("hidden")
    })

    // Add change event listener on all the document
    document.addEventListener("change", function (event) {
      
      event.preventDefault()

      console.log(event.target.id)

      // Range Min and Range Max handler
      if (event.target.closest("form") && event.target.closest("form").id == `${iwpgvObj.prefix}__plotMinMax` && ( event.target.id === `${iwpgvObj.prefix}__rangeMinInput` || event.target.id == `${iwpgvObj.prefix}__rangeMaxInput`) ) {

        const newXAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value ?  document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value : xAxisMin
        const newXAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value ? document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value : xAxisMax

        Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [newXAxisMin, newXAxisMax] })

        return
      }

      // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
      if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ) return 

      // Chart layout event handler
      // if ( event.target.classList.contains( `${iwpgvObj.prefix}__chartLayout` ) ) {

        if (chartOptionKey(event.target.id).key.includes("config")) {
          console.log("PPPPPP",chartOptionKey(event.target.id).key.split(".")[1])
          console.log({ [chartOptionKey(event.target.id).key.split(".")[1]]: event.target.type === 'checkbox' ? event.target.checked : event.target.value} )
          Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)

          chart.chartConfig[chartOptionKey(event.target.id).key.split(".")[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
          console.log("GGGGGGG",chart.chartConfig )
          Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, chart.chartTraces, chart.chartLayout, chart.chartConfig)
          // Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, {}, { [chartOptionKey(event.target.id).key.split(".")[1]]: event.target.type === 'checkbox' ? event.target.checked : event.target.value} )
        } else {
          console.log("PPPPPP",chartOptionKey(event.target.id))

          const parts = chartOptionKey(event.target.id).key.split(".")
          console.log("PARTS",parts)

          // let plotType

          switch(chartOptionKey(event.target.id).control) {
            case "tableChart":
              switch(parts.length){
                case 1:
                  // chart.tableChart.options[] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
                  break
                case 2:
                  chart.tableChart.options[parts[0]][parts[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
                  break
                case 3:
                  chart.tableChart.options[parts[0]][parts[1]][parts[2]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
                  break
              }
              Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartConfig)
              console.log(chart.tableChart.options)
              break
            default:
             
             break
          }

          // Plotly.relayout(`${iwpgvObj.prefix}__${plotType}`, { [chartOptionKey(event.target.id).key]: event.target.type === 'checkbox' ? event.target.checked : event.target.value})

        }

      // }

      //  // Chart layout event handler
      //  if ( event.target.classList.contains( `${iwpgvObj.prefix}__tableChart` ) ) {

      //  console.log({ [chartOptionKey(event.target.id).key]: event.target.type === 'checkbox' ? event.target.checked : event.target.value})
      //  Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { [chartOptionKey(event.target.id).key]: event.target.type === 'checkbox' ? event.target.checked : event.target.value})
      //  }

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
    // toggleElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
    // toggleElementById( `${iwpgvObj.prefix}__spinner` )
    // showElementById( `${prefix}__plotlyChart` )

  }

  

}

export default drawChart
