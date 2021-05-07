import Plotly from 'plotly.js-dist'
import { toggleElementById, showElementById, hideElementById, getMinMaxAvgData, chartOptionKey, fetchTableChartData, fetchminMaxAvgTableChartData } from "./utilities"


const renderChart =  async( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  const chart = iwpgvCharts.chart
  
  // Update chartLayout options
  chart.chartLayout.options.hovermode = ( chart.chartLayout.hovermode ) ? chart.chartLayout.hovermode : false

  // Set range slider min and max input fields if enableChartRangeSlider is true
  if ( chart.chartLayout.options.xaxis.rangeslider.visible ) {
    showElementById( `${iwpgvObj.prefix}__plotMinMax` )
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)
    // chart.chartLayout.options.xaxis.rangeslider = true
  } else {
    hideElementById( `${iwpgvObj.prefix}__plotMinMax` )
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  null
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = null
    // chart.chartLayout.options.xaxis.rangeslider =false
  }
  
  // Render chart
  await Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config)

  // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
  if ( chart.chartParams.options.enableMinMaxTableChart ) {
    chart.minMaxAvgTableChart.options = fetchminMaxAvgTableChartData( chart, spreadsheet )
    await Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, chart.chartLayout.options.config)
  }

  // Render table chart if enableTableChart is true
  if ( chart.chartParams.options.enableTableChart ) {
    chart.tableChart.options = fetchTableChartData( chart, spreadsheet )
    await Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartLayout.options.config )

  }



  

    // // Add range slider event handler
    // eval(`${iwpgvObj.prefix}__plotlyChart`).on('plotly_relayout',function(eventData){  
    //   const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(xAxisMin).toFixed(3)
    //   document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(xAxisMax).toFixed(3)
    //   chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
    //   Plotly.restyle( `${iwpgvObj.prefix}__plotlyMinMaxTable`, { "cells.values": [getMinMaxAvgData( chart, spreadsheet, xAxisMin, xAxisMax)] } )
    // })

    // // Add range min and range max change event listener
    // document.addEventListener( "change", function ( event ) {
    //   event.preventDefault()
    //   if ( ! event.target.closest("form")  || event.target.closest("form").id !== `${iwpgvObj.prefix}__plotMinMax` || ( event.target.id !== `${iwpgvObj.prefix}__rangeMinInput` && event.target.id !== `${iwpgvObj.prefix}__rangeMaxInput`) ) return
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

  //       const plotlyTable = ( control === "tableChart" ) ? `${iwpgvObj.prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${iwpgvObj.prefix}__plotlyMinMaxTable` : null

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
  //           if ( plotlyTable === `${iwpgvObj.prefix}__plotlyMinMaxTable` ) {
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
