import Plotly from 'plotly.js-dist'
import renderPanels from "./render-panels"
import renderChart from "./render-chart"
import { setSheetIdOptions, showInputField, showElementById, hideElementById, getMinMaxAvgData, fetchMinMaxAvgTableChartData, chartOptionKey } from "./utilities"

const drawChart = async( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  // toggleElementById( `${iwpgvObj.prefix}__spinner` )

  const chart = iwpgvCharts.chart

  // Hide chart and table charts
  Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxAvgTable`)

  // Hide min/max inputs if visible
  hideElementById( `${iwpgvObj.prefix}__plotMinMaxAvg` )

  // remove layout panel toggle and panel
  document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // Hide Min/Max/Avg accordion toggle and content
  document.querySelector( `.accordion__toggle.minMaxAvgTableChart.panel` ).classList.add("hidden")
  document.querySelector( `.accordion__content.minMaxAvgTableChart.panel` ).classList.add("hidden")

  // Render panels
  renderPanels( iwpgvCharts, iwpgvObj, spreadsheet )

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = false
  showElementById( `${iwpgvObj.prefix}__saveChart` )
  
  // Render chart
  await renderChart( iwpgvCharts, iwpgvObj, spreadsheet)

  // // Add range slider event handler
  // eval(`${iwpgvObj.prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

  //   // Bail if the event is other that range slider
  //   if ( ! eventData['xaxis.range'] || ! chart.chartParams.options.enableMinMaxTableChart ) return

  //   console.log("PPPPPP",eventData)
  //   //
  //   // const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
  //   // const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
  //   document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = eventData['xaxis.range'][0] //parseFloat(xAxisMin).toFixed(chart.minMaxAvgTableChart.options.rounding)
  //   document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = eventData['xaxis.range'][1] //parseFloat(xAxisMax).toFixed(chart.minMaxAvgTableChart.options.rounding)
    
  //   // Update Min/Max/Avg table data
  //   // chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])

  //   Plotly.restyle( `${iwpgvObj.prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])] } )

  // })


  document.addEventListener("change", async function (event) {
  
    event.preventDefault()

    switch( event.target.id ){

      case `${iwpgvObj.prefix}__rangeMinInput`:
      case `${iwpgvObj.prefix}__rangeMaxInput`:
        const xAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value
        const xAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value
        if (parseFloat(xAxisMin) < parseFloat(xAxisMax) ) {
          Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [xAxisMin, xAxisMax] })
        }
        break

      default:
        const control = chartOptionKey(event.target.id).control
        const key = chartOptionKey(event.target.id).key
        const keyParts = key.split(".")
        let value =  event.target.type === 'checkbox' ? event.target.checked : event.target.value

        console.log("Control", control)
        console.log("key", key)
        console.log("keyParts", keyParts)
        console.log("value", value)

        switch ( control ) {

          case "chartLayout":
            if ( key.includes( "config" ) ) {
              chart.chartLayout.options.config[key.split(".")[1]] = value
              if ( chart.chartLayout.options.config.displayModeBar ){
                document.getElementById(`${iwpgvObj.prefix}__chartLayout[config][displaylogo]`).disabled = false
              }else {
                document.getElementById(`${iwpgvObj.prefix}__chartLayout[config][displaylogo]`).disabled = true
              }
              Plotly.newPlot( `${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config )
            } else if ( key === "xaxis.range" ) {
              Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: value.toString().split(",").map( ( item ) => { return parseFloat( item ) } ) }, chart.chartLayout.options.config )
            } else if ( key === "xaxis.autorange" ) {
              Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]:  value === "true" ? true : value === "false" ? false : value }, chart.chartLayout.options.config )
            } else if ( key === "xaxis.mirror" ) {
              Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]:  value === "true" ? true : value === "false" ? false : value }, chart.chartLayout.options.config )
            }
            else if ( key === "xaxis.tickmode" ) {
              if ( value === "array" ) {
                document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][tickvals]`).readOnly = false
                document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][ticktext]`).readOnly = false
              } else {
                document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][tickvals]`).readOnly = true
                document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][ticktext]`).readOnly = true
              }
              Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]:  value }, chart.chartLayout.options.config )
            }else if ( key === "xaxis.tickvals" || key === "xaxis.ticktext" ) {
              console.log(value.split(",").map( ( item ) => { return  item } ))
              Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: value.split(",").map( ( item ) => { return  item } ) }, chart.chartLayout.options.config )
            } else if (key === "hovermode" || key === "legend.itemclick" ) {
              Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]:  ( event.target.value !== "disabled" ) ? event.target.value : false }, chart.chartLayout.options.config )
            } else {
              switch(keyParts.length){
                case 1:
                  chart.chartLayout.options[keyParts[0]] = value
                  break
                case 2:
                  chart.chartLayout.options[keyParts[0]][keyParts[1]] = value
                  console.log(chart.chartLayout.options[keyParts[0]][keyParts[1]])
                  break
                case 3:
                  chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = value
                  // if (key === "xaxis.rangeslider.visible" ) {
                  //   if ( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`).checked ) break
                  //   if (value) {
                  //     showElementById( `${iwpgvObj.prefix}__plotMinMaxAvg` )
                  //     showElementById( `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` )
                  //     document.querySelector(`.accordion__toggle.minMaxAvgTableChart.panel`).classList.remove("hidden")
                  //     document.querySelector(`.accordion__content.minMaxAvgTableChart.panel`).classList.remove("hidden")
                  //     const xAxisMin = ( chart.chartLayout.options.xaxis.range[0] ) ? chart.chartLayout.options.xaxis.range[0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
                  //     const xAxisMax = ( chart.chartLayout.options.xaxis.range[1] ) ? chart.chartLayout.options.xaxis.range[1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
                  //     chart.minMaxAvgTableChart.options = fetchMinMaxAvgTableChartData( chart, spreadsheet, xAxisMin, xAxisMax )
                  //     Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxAvgTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, chart.chartLayout.options.config) 
                  //   } else {
                  //     hideElementById( `${iwpgvObj.prefix}__plotMinMaxAvg` )
                  //     hideElementById( `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` )
                  //     document.querySelector(`.accordion__toggle.minMaxAvgTableChart.panel`).classList.add("hidden")
                  //     document.querySelector(`.accordion__content.minMaxAvgTableChart.panel`).classList.add("hidden")
                  //   }
                  // }
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
            if ( optionKey === "visible"  ) {
              if ( value === "true" ) value = true //Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: true }, traceNumber)
              if ( value === "false" ) value = false //Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: false }, traceNumber)
            } else if (optionKey === "error_y.symmetric") {
              // document.getElementById(`${iwpgvObj.prefix}__chartTraces[${traceNumber}][error_y][valueminus]`).readOnly = true
              document.getElementById(`${iwpgvObj.prefix}__chartTraces[${traceNumber}][error_y][valueminus]`).readOnly = value ? true : false
            } else if (optionKey === "error_y.type") {
              document.getElementById(`${iwpgvObj.prefix}__chartTraces[${traceNumber}][error_y][array]`).readOnly = value !== "data" ? true : false
              document.getElementById(`${iwpgvObj.prefix}__chartTraces[${traceNumber}][error_y][arrayminus]`).readOnly = value !== "data" ? true : false
            } else if ( optionKey === "error_y.array" || optionKey === "error_y.arrayminus" ){
             
              value = value.split(",").map( ( item ) => { return  parseFloat( item ) } )
              console.log(optionKey, traceNumber, value)
            } else  {
              // Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: value }, traceNumber)
            }
            Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: value }, traceNumber)
            
            break
            

            

          case "tableChart":
          case "minMaxAvgTableChart":
            const plotlyTable = ( control === "tableChart" ) ? `${iwpgvObj.prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` : null
            const rowFillColors = []
            switch( key ) {
              case "firstColAlign":
                chart[control].options.firstColAlign = value
                chart[control].options.cells.align = [value, chart[control].options.cells.align[1]]
                chart[control].options.header.align = [value, chart[control].options.header.align[1]]
                break
              case "rounding":
                chart[control].options.rounding = value
                const cellValues = []
                for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
                  cellValues[i] =[]
                  for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
                    cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( value ) ) 
                  }  
                }
                chart[control].options.cells.values = cellValues
                if ( plotlyTable === `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` ) {
                  const xAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value
                  const xAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value
                  chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
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
                    chart[control].options[keyParts[0]][keyParts[1]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 3:
                    chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 4:
                    chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                }
            }
            if (plotlyTable) Plotly.newPlot(plotlyTable, [chart[control].options], chart[control].options.layout) 

        }
        break

    }

    return false

  })


  
 

  
}


export default drawChart
