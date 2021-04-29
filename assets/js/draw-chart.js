import Plotly from 'plotly.js-dist'
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
import renderPanel from "./renderPanel"
import Accordion from "./Accordion"
import saveChart from "./save-chart"
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
    // chart.chartLayout.options.config = chart.chartLayout.options.config

    // Render chart layout panel
    renderPanel(chart.chartLayout.panel, iwpgvObj)

    let index = 1;
    while (index < spreadsheet[chart.chartParams.options.sheetId].labels.length) {
      chart.chartTraces.options[index-1] = ( chart.chartTraces.options[index-1] !== undefined )? chart.chartTraces.options[index-1] : {}
      const chartTraceInstance =  new ChartTrace( chart.chartTraces.options[index-1], spreadsheet, index, chart.chartParams.options.sheetId, chart.chartParams.options.chartType, iwpgvObj ) 
      chart.chartTraces.options[index-1] = chartTraceInstance.options()
      chart.chartTraces.panel.sections[index-1] = chartTraceInstance.sections()
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

    chart.chartLayout.options.hovermode = ( chart.chartLayout.hovermode ) ? chart.chartLayout.hovermode : false

     // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
     Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config).then (function() {

      toggleElementById( `${iwpgvObj.prefix}__spinner` )
      // console.log("Done plotting Chart")

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

       // Set table header alignment
      chart.tableChart.options.header.align = [chart.tableChart.options.firstColAlign, chart.tableChart.options.header.align]


      // Round cells values if rounding is not 0
      if ( chart.tableChart.options.rounding) {
        const cellValues = []
        for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
          cellValues[i] =[]
          for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
            cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( chart.tableChart.options.rounding ) ) 
          }  
        }
        chart.tableChart.options.cells.values = cellValues  
      } else {
        chart.tableChart.options.cells.values = spreadsheet[chart.chartParams.options.sheetId].data
      }

       // Set table cells alignment
       chart.tableChart.options.cells.align = [chart.tableChart.options.firstColAlign, chart.tableChart.options.cells.align]

      // Set table even and odd row colors
      const rowFillColors = []
      for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
        rowFillColors[j] = (j % 2 === 0) ? chart.tableChart.options.oddRowColor : chart.tableChart.options.evenRowColor
      }
      chart.tableChart.options.cells.fill.color = [rowFillColors]

      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartConfig).then (function() {

        // console.log("Done plotting Table")

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

        // console.log("Done plotting MIn Max Table")

      })

    }

    // Enable save button
    document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false
    
    // Add click event listener to the chart params panel inoput fields
    document.addEventListener("click", function (event) {
      
      // Save chart event handler
      if (event.target.closest("form") && event.target.closest("form").id === `${iwpgvObj.prefix}__chartOptionsForm` && event.target.id === `${iwpgvObj.prefix}__saveChart`) {
        event.preventDefault()
        saveChart(chart, iwpgvObj)
      }
      
    })

    document.querySelectorAll(`.accordion__toggle`).forEach (element=> {
      element.classList.remove("hidden")
    })

    document.querySelectorAll(`.accordion__content`).forEach (element=> {
      element.classList.remove("hidden")
    })

    // Add change event listener on all the document
    document.addEventListener("change", function (event) {
      
      event.preventDefault()


      if (event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)) return

      // Range Min and Range Max handler
      if (event.target.closest("form") && event.target.closest("form").id == `${iwpgvObj.prefix}__plotMinMax` && ( event.target.id === `${iwpgvObj.prefix}__rangeMinInput` || event.target.id == `${iwpgvObj.prefix}__rangeMaxInput`) ) {

        const newXAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value ?  document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value : xAxisMin
        const newXAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value ? document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value : xAxisMax

        Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [newXAxisMin, newXAxisMax] })

        return
      }

      // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
      if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ) return

        const control = chartOptionKey(event.target.id).control
        const key = chartOptionKey(event.target.id).key
        const parts = key.split(".")
        let value = event.target.value
        console.log("PARTS",control, key, parts, value)


      // Chart layout event handler
        if( control === "chartLayout" )  {
          if (key.includes("config")) {
            // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
            chart.chartLayout.options.config[key.split(".")[1]] = event.target.type === 'checkbox' ? event.target.checked : value
            Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config)
          } else {
          if (key === "hovermode" || key === "legend.itemclick" ) value = ( value !== "disabled" ) ? value : false
          Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.config)
          }

        } else if( control === "tableChart" || control ==="minMaxAvgTableChart" )  {

          const plotlyTable = ( control === "tableChart" ) ? `${iwpgvObj.prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${iwpgvObj.prefix}__plotlyMinMaxTable` : null
         
          switch(parts.length){
            case 1:
              if (parts[0] === "firstColAlign") {
                chart[control].options.cells.align = [event.target.value, chart[control].options.cells.align[1]]
                chart[control].options.header.align = [event.target.value, chart[control].options.header.align[1]]
              } else if (parts[0] === "rounding") {
                const cellValues = []
                for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
                  cellValues[i] =[]
                  for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
                    cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( event.target.value ) ) 
                  }  
                }
                chart[control].options.cells.values = cellValues
              } else if (  parts[0] === "evenRowColor" ) {
                 // Set table even and odd row colors
                const rowFillColors = []
                for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                  rowFillColors[j] = (j % 2 === 0) ? chart[control].options.oddRowColor : event.target.value 
                }
                chart[control].options.cells.fill.color = [rowFillColors]
              } else if (  parts[0] === "oddRowColor" ) {
                // Set table even and odd row colors
                const rowFillColors = []
                for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                  rowFillColors[j] = (j % 2 === 0) ? event.target.value : chart[control].options.evenRowColor
                }
                chart[control].options.cells.fill.color = [rowFillColors]
               } else {
                chart[control].options[parts[0]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
              }
              break
            case 2:
              chart[control].options[parts[0]][parts[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
              break
            case 3:
              chart[control].options[parts[0]][parts[1]][parts[2]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
              break
            case 4:
                chart[control].options[parts[0]][parts[1]][parts[2]][parts[3]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
              break
          }

          if (plotlyTable) Plotly.newPlot(plotlyTable, [chart[control].options], chart[control].options.layout, chart.chartConfig)
          

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
      console.log(chart[control].options)

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
