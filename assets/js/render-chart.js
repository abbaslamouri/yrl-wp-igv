import Plotly from 'plotly.js-dist'
import { showElementById, fetchMinMaxAvgTableChartData, getMinMaxAvgData } from "./utilities"


const renderChart =  async( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  const rangeMinInput = document.getElementById( `${iwpgvObj.prefix}__rangeMinInput` )
  const rangeMaxInput = document.getElementById( `${iwpgvObj.prefix}__rangeMaxInput` )

  const chart = iwpgvCharts.chart

  // console.log(chart)
  
  // Update chartLayout options
  // chart.chartLayout.options.hovermode = ( chart.chartLayout.hovermode ) ? chart.chartLayout.hovermode : false
  
  // Render chart
  if ( spreadsheet ) {

    
    await Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config)

    // document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][range]`).value = chart.chartLayout.options.xaxis.range.join()
    // document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][autorange]`).value = "false"
    // document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][autorange]`).disabled = true
    // chart.chartLayout.options.xaxis.autorange = false;




    console.log(chart.chartLayout.options)


    // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
    // if ( chart.chartLayout.options.xaxis.rangeslider.visible && chart.chartLayout.options.showMinMaxAvgTable ) {
      return

    const xAxisMin = chart.chartLayout.options.xaxis.range[0]
    const xAxisMax = chart.chartLayout.options.xaxis.range[1]
    
    // Set range min and max input values
    rangeMinInput.value =  chart.minMaxAvgTableChart.options.rounding ? xAxisMin.toFixed(chart.minMaxAvgTableChart.options.rounding) : xAxisMin
    rangeMaxInput.value = chart.minMaxAvgTableChart.options.rounding ? xAxisMax.toFixed(chart.minMaxAvgTableChart.options.rounding) : xAxisMax

    // Fetch Min?Max/Avg table data
    chart.minMaxAvgTableChart.options = fetchMinMaxAvgTableChartData( chart, spreadsheet, xAxisMin, xAxisMax )
    console.log(chart.minMaxAvgTableChart.options.cells.values)

    // Show Min/Max/Avg table (must show the div before plaotting)
    showElementById( `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` )

    // Plot table
    await Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxAvgTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, {displayModeBar: false})

    // Show range min and max input fields
    showElementById( `${iwpgvObj.prefix}__plotMinMaxAvg` )

    // Add range slider event handler
    eval(`${iwpgvObj.prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

      // Bail if the event is other that range slider
      if ( ! eventData['xaxis.range'] ) return

      console.log("KKKKKK", eventData )

      //
      // const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
      // const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat( eventData['xaxis.range'][0] ).toFixed(chart.minMaxAvgTableChart.options.rounding)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat( eventData['xaxis.range'][1] ).toFixed(chart.minMaxAvgTableChart.options.rounding)
      
      // Update Min/Max/Avg table data
      // chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])

      Plotly.restyle( `${iwpgvObj.prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])] } )

    })

  }



  // }

  // Render table chart if enableTableChart is true
  // if ( chart.chartParams.options.enableTableChart ) {
  //   chart.tableChart.options = fetchTableChartData( chart, spreadsheet )
  //   await Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartLayout.options.config )

  // }



  

    // // Add range slider event handler
    // eval(`${iwpgvObj.prefix}__plotlyChart`).on('plotly_relayout',function(eventData){  
    //   const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(xAxisMin).toFixed(3)
    //   document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(xAxisMax).toFixed(3)
    //   chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
    //   Plotly.restyle( `${iwpgvObj.prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData( chart, spreadsheet, xAxisMin, xAxisMax)] } )
    // })

    // // Add range min and range max change event listener
    // document.addEventListener( "change", function ( event ) {
    //   event.preventDefault()
    //   if ( ! event.target.closest("form")  || event.target.closest("form").id !== `${iwpgvObj.prefix}__plotMinMaxAvg` || ( event.target.id !== `${iwpgvObj.prefix}__rangeMinInput` && event.target.id !== `${iwpgvObj.prefix}__rangeMaxInput`) ) return
    //     const newXAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value ?  document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value : xAxisMin
    //     const newXAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value ? document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value : xAxisMax
    //     Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [newXAxisMin, newXAxisMax] })      
    // })



    // document.addEventListener("change", function (event) {
      
    //   event.preventDefault()

    //   // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
    //   if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ) return

    //   const control = chartOptionKey(event.target.id).control
    //   const key = chartOptionKey(event.target.id).key
    //   const keyParts = key.split(".")
    //   let value = event.target.value

    //   console.log(control, key, value)
    
    //   // Chart layout event handler
    //   if( control === "chartLayout" )  {
    //     if ( key.includes( "config" ) ) {
    //       chart.chartLayout.options.config[key.split(".")[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
    //       Plotly.newPlot( `${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config )
    //     } else if (key === "hovermode" || key === "legend.itemclick" ) {
    //       value = ( event.target.value !== "disabled" ) ? event.target.value : false
    //       Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.chartLayout.options.config )
    //     } else {
    //       switch(keyParts.length){
    //         case 1:
    //           chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 2:
    //           chart[control].options[keyParts[0]][keyParts[1]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 3:
    //           chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 4:
    //             chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 5:
    //             chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]][keyParts[4]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //       }
    //       Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.chartLayout.options.config )
    //     }
        
    //   }

      
    //   // Chart traces event handler
    //   if ( event.target.classList.contains( `${iwpgvObj.prefix}__chartTraces` ) ) {
    //     const keyArr = key.split(".")
    //     const traceNumber = keyArr.shift()
    //     const optionKey = keyArr.join(".")
    //     if ( optionKey === "visible" && event.target.value === "disabled" ) value = false
    //     if ( optionKey === "visible" && event.target.value === "enabled" ) value = true
    //     Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: event.target.type === 'checkbox' ? event.target.checked : value }, traceNumber);
    //   }


    // })

  


  
  //   then (function() {

  //      // table charts event handler
  //      if( control === "tableChart" || control ==="minMaxAvgTableChart" )  {

  //       const plotlyTable = ( control === "tableChart" ) ? `${iwpgvObj.prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` : null

  //       const rowFillColors = []

  //       switch( key ) {
  //         case "firstColAlign":
  //           chart[control].options.cells.align = [value, chart[control].options.cells.align[1]]
  //           chart[control].options.header.align = [value, chart[control].options.header.align[1]]
  //           break
  //         case "rounding":
  //           const cellValues = []
  //           for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
  //             cellValues[i] =[]
  //             for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
  //               cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( value ) ) 
  //             }  
  //           }
  //           chart[control].options.cells.values = cellValues
  //           if ( plotlyTable === `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` ) {
  //             console.log("HEHTRD")
  //             const xAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value
  //             const xAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value
  //             chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
  //             console.log(chart)
  //           }
  //           break
  //         case "evenRowColor":
  //           // const rowFillColors = []
  //           for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
  //             rowFillColors[j] = (j % 2 === 0) ? chart[control].options.oddRowColor : value 
  //           }
  //           chart[control].options.cells.fill.color = [rowFillColors]
  //           break
  //         case "oddRowColor":
  //           // const rowFillColors = []
  //           for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
  //             rowFillColors[j] = (j % 2 === 0) ? value : chart[control].options.evenRowColor
  //           }
  //           chart[control].options.cells.fill.color = [rowFillColors]
  //           break
  //         case "header.align":
  //           chart[control].options.header.align = [chart[control].options.firstColAlign, value]
  //           break
  //         case "cells.align":
  //           chart[control].options.cells.align = [chart[control].options.firstColAlign, value]
  //           break
  //         default:
  //           switch (keyParts.length ) {
  //             case 1:
  //               chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : value
  //               break
  //             case 2:
  //               chart[control].options[keyParts[0]][keyParts[1]] = [chart[control].options.firstColAlign, value]
  //               break
  //             case 3:
  //               chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
  //               break
  //             case 4:
  //               chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
  //               break
  //           }
          
  //       }
  //       console.log("PLOT", plotlyTable)
  //       if (plotlyTable) Plotly.newPlot(plotlyTable, [chart[control].options], chart[control].options.layout, chart.chartConfig)  
  //     }

  //   })

  // }


  


}





export default renderChart
