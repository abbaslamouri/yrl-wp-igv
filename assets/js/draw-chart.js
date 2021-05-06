import Plotly from 'plotly.js-dist'
import renderPanels from "./render-panels"
import renderChart from "./render-chart"
import { showElementById, toggleElementById, hideElementById, getMinMaxAvgData, fetchMinMaxAvgTableChartOptions, chartOptionKey } from "./utilities"

const drawChart = async( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  // toggleElementById( `${iwpgvObj.prefix}__spinner` )

  const chart = iwpgvCharts.chart

  // Hide chart and table charts
  Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // Hide min/max inputs if visible
  hideElementById( `${iwpgvObj.prefix}__plotMinMax` )

  // remove layout panel toggle and panel
  document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false
  showElementById( `${iwpgvObj.prefix}__saveChart` )

  renderPanels( iwpgvCharts, iwpgvObj, spreadsheet )

  console.log("iwpgvCharts", iwpgvCharts)

  
  await renderChart( iwpgvCharts, iwpgvObj, spreadsheet)

  // Add range slider event handler
  eval(`${iwpgvObj.prefix}__plotlyChart`).on('plotly_relayout',function(eventData){  
    const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
    const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(xAxisMin).toFixed(3)
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(xAxisMax).toFixed(3)
    chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
    // Plotly.restyle( `${iwpgvObj.prefix}__plotlyMinMaxTable`, { "cells.values": [getMinMaxAvgData( chart, spreadsheet, xAxisMin, xAxisMax)] } )
  })


    document.addEventListener("change", function (event) {
      
      event.preventDefault()

      switch( event.target.id ){

        case `${iwpgvObj.prefix}__rangeMinInput`:
        case `${iwpgvObj.prefix}__rangeMaxInput`:
          const xAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value
          const xAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value
          Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [xAxisMin, xAxisMax] })
          break

        default:
          const control = chartOptionKey(event.target.id).control
          const key = chartOptionKey(event.target.id).key
          const keyParts = key.split(".")
          let value = event.target.value

          console.log("Control", control)
          console.log("key", key)
          console.log("keyParts", keyParts)
          console.log("value", value)

          switch ( control ) {

            case "chartLayout":
              if ( key.includes( "config" ) ) {
                chart.chartLayout.options.config[key.split(".")[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
                if ( chart.chartLayout.options.config.displayModeBar ){
                  document.getElementById(`${iwpgvObj.prefix}__chartLayout[config][displaylogo]`).disabled = false
                }else {
                  document.getElementById(`${iwpgvObj.prefix}__chartLayout[config][displaylogo]`).disabled = true
                }
                Plotly.newPlot( `${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config )
              } else if (key === "hovermode" || key === "legend.itemclick" ) {
                value = ( event.target.value !== "disabled" ) ? event.target.value : false
                Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.chartLayout.options.config )
              } else {
                switch(keyParts.length){
                  case 1:
                    chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 2:
                    chart[control].options[keyParts[0]][keyParts[1]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 3:
                    chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 4:
                      chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 5:
                      chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]][keyParts[4]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                }
                Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.chartLayout.options.config )
              }
              break

            case "chartTraces":
              const keyArr = key.split(".")
              const traceNumber = keyArr.shift()
              const optionKey = keyArr.join(".")
              if ( optionKey === "visible" && event.target.value === "disabled" ) value = false
              if ( optionKey === "visible" && event.target.value === "enabled" ) value = true
              Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: event.target.type === 'checkbox' ? event.target.checked : value }, traceNumber);
              break

            case "tableChart":
            case "minMaxAvgTableChart":
              const plotlyTable = ( control === "tableChart" ) ? `${iwpgvObj.prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${iwpgvObj.prefix}__plotlyMinMaxTable` : null
              const rowFillColors = []
              switch( key ) {
                case "firstColAlign":
                  chart[control].options.cells.align = [value, chart[control].options.cells.align[1]]
                  chart[control].options.header.align = [value, chart[control].options.header.align[1]]
                  break
                case "rounding":
                  const cellValues = []
                  for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
                    cellValues[i] =[]
                    for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
                      cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( value ) ) 
                    }  
                  }
                  chart[control].options.cells.values = cellValues
                  if ( plotlyTable === `${iwpgvObj.prefix}__plotlyMinMaxTable` ) {
                    console.log("HEHTRD")
                    const xAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value
                    const xAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value
                    chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
                    console.log(chart)
                  }
                  break
                case "evenRowColor":
                  // const rowFillColors = []
                  for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                    rowFillColors[j] = (j % 2 === 0) ? chart[control].options.oddRowColor : value 
                  }
                  chart[control].options.cells.fill.color = [rowFillColors]
                  break
                case "oddRowColor":
                  // const rowFillColors = []
                  for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                    rowFillColors[j] = (j % 2 === 0) ? value : chart[control].options.evenRowColor
                  }
                  chart[control].options.cells.fill.color = [rowFillColors]
                  break
                case "header.align":
                  chart[control].options.header.align = [chart[control].options.firstColAlign, value]
                  break
                case "cells.align":
                  chart[control].options.cells.align = [chart[control].options.firstColAlign, value]
                  break
                default:
                  switch (keyParts.length ) {
                    case 1:
                      chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : value
                      break
                    case 2:
                      chart[control].options[keyParts[0]][keyParts[1]] = [chart[control].options.firstColAlign, value]
                      break
                    case 3:
                      chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
                      break
                    case 4:
                      chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
                      break
                  }
              }
              console.log("PLOT", plotlyTable)
              if (plotlyTable) Plotly.newPlot(plotlyTable, [chart[control].options], chart[control].options.layout, chart.chartConfig) 

          }
          break

      }

    })


  
 

  
}


export default drawChart
