import Plotly from 'plotly.js-dist'
import { toggleElementById, showElementById, hideElementById, getMinMaxAvgData, chartOptionKey } from "./utilities"


const renderChart =  ( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  const chart = iwpgvCharts.chart
  
  // Update chartLayout options
  chart.chartLayout.options.hovermode = ( chart.chartLayout.hovermode ) ? chart.chartLayout.hovermode : false

  // Set range slider min and max input fields if enableChartRangeSlider is true
  if ( chart.chartParams.options.enableRangeSlider ) {
    showElementById( `${iwpgvObj.prefix}__plotMinMax` )
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)
    chart.chartLayout.options.xaxis.rangeslider = true
  } else {
    hideElementById( `${iwpgvObj.prefix}__plotMinMax` )
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  null
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = null
    chart.chartLayout.options.xaxis.rangeslider =false
  }

  // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
  // toggleElementById( `${iwpgvObj.prefix}__spinner` )

  Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config).
  then (function() {

    // toggleElementById( `${iwpgvObj.prefix}__spinner` )

    // console.log("Done plotting Chart")

    // Add range slider event handler
    eval(`${iwpgvObj.prefix}__plotlyChart`).on('plotly_relayout',function(eventData){  
      const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
      const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(xAxisMin).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(xAxisMax).toFixed(3)
      chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
      Plotly.restyle( `${iwpgvObj.prefix}__plotlyMinMaxTable`, { "cells.values": [getMinMaxAvgData( chart, spreadsheet, xAxisMin, xAxisMax)] } )
    })

    // Add range min and range max change event listener
    document.addEventListener( "change", function ( event ) {
      event.preventDefault()
      if ( ! event.target.closest("form")  || event.target.closest("form").id !== `${iwpgvObj.prefix}__plotMinMax` || ( event.target.id !== `${iwpgvObj.prefix}__rangeMinInput` && event.target.id !== `${iwpgvObj.prefix}__rangeMaxInput`) ) return
        const newXAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value ?  document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value : xAxisMin
        const newXAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value ? document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value : xAxisMax
        Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [newXAxisMin, newXAxisMax] })      
    })


    // Render table chart if enableTableChart is true
    if ( chart.chartParams.options.enableTableChart ) {

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
    
      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartLayout.options.config ).
      then (function() {

        // console.log("Done plotting Table")

      })
  
    }


    // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
    if ( chart.chartParams.options.enableMinMaxTableChart ) {

      // Set table header
      const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
      chart.minMaxAvgTableChart.options.header.values = headerValues

      chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet)

      // Set table cells alignment
      chart.minMaxAvgTableChart.options.cells.align = [chart.minMaxAvgTableChart.options.firstColAlign , chart.minMaxAvgTableChart.options.otherColsAlign]

      // Set table even and odd row colors
      const rowFillColors = []
      for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
        rowFillColors[j] = (j % 2 === 0) ? chart.minMaxAvgTableChart.options.evenRowColor : chart.minMaxAvgTableChart.options.oddRowColor
      }
      chart.minMaxAvgTableChart.options.cells.fill.color = [rowFillColors]

      Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, chart.chartLayout.options.config).
      then (function() {

        // console.log("Done plotting MIn Max Table")

      })

    }


    document.addEventListener("change", function (event) {
      
      event.preventDefault()

      // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
      if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ) return

      const control = chartOptionKey(event.target.id).control
      const key = chartOptionKey(event.target.id).key
      const keyParts = key.split(".")
      let value = event.target.value

      console.log(control, key, value)
    
      // Chart layout event handler
      if( control === "chartLayout" )  {
        if ( key.includes( "config" ) ) {
          chart.chartLayout.options.config[key.split(".")[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
          Plotly.newPlot( `${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config )
        } else {
        if (key === "hovermode" || key === "legend.itemclick" ) value = ( event.target.value !== "disabled" ) ? event.target.value : false
        Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.chartLayout.options.config )
        }
      }

      
      // Chart traces event handler
      if ( event.target.classList.contains( `${iwpgvObj.prefix}__chartTraces` ) ) {
        const keyArr = key.split(".")
        const traceNumber = keyArr.shift()
        const optionKey = keyArr.join(".")
        if ( optionKey === "visible" && event.target.value === "disabled" ) value = false
        if ( optionKey === "visible" && event.target.value === "enabled" ) value = true
        Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: event.target.type === 'checkbox' ? event.target.checked : value }, traceNumber);
      }

      // table charts event handler
      if( control === "tableChart" || control ==="minMaxAvgTableChart" )  {

        const plotlyTable = ( control === "tableChart" ) ? `${iwpgvObj.prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${iwpgvObj.prefix}__plotlyMinMaxTable` : null
       
        switch(keyParts.length){
          case 1:
            if (keyParts[0] === "firstColAlign") {
              chart[control].options.cells.align = [event.target.value, chart[control].options.cells.align[1]]
              chart[control].options.header.align = [event.target.value, chart[control].options.header.align[1]]
            } else if (keyParts[0] === "rounding") {
              const cellValues = []
              for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
                cellValues[i] =[]
                for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
                  cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( event.target.value ) ) 
                }  
              }
              chart[control].options.cells.values = cellValues
            } else if (  keyParts[0] === "evenRowColor" ) {
               // Set table even and odd row colors
              const rowFillColors = []
              for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                rowFillColors[j] = (j % 2 === 0) ? chart[control].options.oddRowColor : event.target.value 
              }
              chart[control].options.cells.fill.color = [rowFillColors]
            } else if (  keyParts[0] === "oddRowColor" ) {
              // Set table even and odd row colors
              const rowFillColors = []
              for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                rowFillColors[j] = (j % 2 === 0) ? event.target.value : chart[control].options.evenRowColor
              }
              chart[control].options.cells.fill.color = [rowFillColors]
             } else {
              chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
            }
            break
          case 2:
            console.log(chart[control].options[keyParts[0]][keyParts[1]], chart[control].options.firstColAlign)
            if ( key === "header.align" || key === "cells.align") {
              chart[control].options[keyParts[0]][keyParts[1]] = [chart[control].options.firstColAlign, event.target.value]
            }
            // chart[control].options[keyParts[0]][keyParts[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
            break
          case 3:
            chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
            break
          case 4:
              chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
            break
        }

        if (plotlyTable) Plotly.newPlot(plotlyTable, [chart[control].options], chart[control].options.layout, chart.chartConfig)
  
      }

    })






  })




}





export default renderChart
