import Plotly from 'plotly.js-dist'
import ChartTrace from "./ChartTrace"
import { showElementById, fetchminMaxAvgTableChartData, getMinMaxAvgData } from "./utilities"
import "../sass/public.scss"


if ( typeof yrl_wp_igv__plotlyChart !== "undefined") {

  let iwpgvPublic = typeof yrl_wp_igv__plotlyChart !== "undefined" ?  yrl_wp_igv__plotlyChart : {}


  const payload = iwpgvPublic.payload

  // Assemble chart traces chart and and render chart traces panel
  let index = 1;
  while (index < payload.spreadsheet[payload.chart.chartParams.options.sheetId].labels.length) {
    payload.chart.chartTraces.options[index-1] = ( payload.chart.chartTraces.options[index-1] !== undefined )? payload.chart.chartTraces.options[index-1] : {}
    const chartTraceInstance =  new ChartTrace( payload.chart.chartTraces.options[index-1], payload.spreadsheet, index, payload.chart.chartParams.options.sheetId, payload.chart.chartParams.options.chartType, payload.prefix ) 
    payload.chart.chartTraces.options[index-1] = chartTraceInstance.options()
    // payload.chart.chartTraces.panel.sections[index-1] = chartTraceInstance.sections()
    index++
  }

  // Set range slider min and max input fields if enableChartRangeSlider is true
  if ( payload.chart.chartParams.options.enableRangeSlider ) {
    showElementById( `${payload.prefix}__plotMinMax` )
    document.getElementById(`${payload.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${payload.prefix}__rangeMinInput`).value =  Math.min(...payload.spreadsheet[payload.chart.chartParams.options.sheetId].data[0]).toFixed(3)
    document.getElementById(`${payload.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${payload.prefix}__rangeMaxInput`).value = Math.max(...payload.spreadsheet[payload.chart.chartParams.options.sheetId].data[0]).toFixed(3)
    // payload.chart.chartLayout.options.xaxis.rangeslider = true
  } else {
    hideElementById( `${payload.prefix}__plotMinMax` )
    document.getElementById(`${payload.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${payload.prefix}__rangeMinInput`).value =  null
    document.getElementById(`${payload.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${payload.prefix}__rangeMaxInput`).value = null
    // payload.chart.chartLayout.options.xaxis.rangeslider =false
  }

  // Update chartLayout options
  payload.chart.chartLayout.options.hovermode = ( payload.chart.chartLayout.hovermode ) ? payload.chart.chartLayout.hovermode : false

  Plotly.newPlot(`${payload.prefix}__plotlyChart__${payload.chart.chartParams.options.chartId}`, Object.values(payload.chart.chartTraces.options), payload.chart.chartLayout.options, payload.chart.chartLayout.options.config). then(() => {

    // Add range slider event handler
    eval(`${payload.prefix}__plotlyChart__${payload.chart.chartParams.options.chartId}`).on('plotly_relayout',function(eventData){
      console.log("OOOOOOOO")

      const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...payload.spreadsheet[payload.chart.chartParams.options.sheetId].data[0])
      const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...payload.spreadsheet[payload.chart.chartParams.options.sheetId].data[0])
      document.getElementById(`${payload.prefix}__rangeMinInput`).value = parseFloat(xAxisMin).toFixed(3)
      document.getElementById(`${payload.prefix}__rangeMaxInput`).value = parseFloat(xAxisMax).toFixed(3)
      console.log("OOOOOOOOx")
      payload.chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(payload.chart, payload.spreadsheet, xAxisMin, xAxisMax)
      console.log(payload.chart.minMaxAvgTableChart.options.cells.values)
      Plotly.restyle( `${payload.prefix}__plotlyMinMaxAvgTable__${payload.chart.chartParams.options.chartId}`, { "cells.values": [getMinMaxAvgData( payload.chart, payload.spreadsheet, xAxisMin, xAxisMax)] } )
    })
  }) 

  // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
  if ( payload.chart.chartParams.options.enableMinMaxTableChart ) {
    payload.chart.minMaxAvgTableChart.options = fetchminMaxAvgTableChartData(payload.chart, payload.spreadsheet)
    console.log("P",payload.chart.minMaxAvgTableChart.options)
    Plotly.newPlot(`${payload.prefix}__plotlyMinMaxAvgTable__${payload.chart.chartParams.options.chartId}`, [payload.chart.minMaxAvgTableChart.options])
  }


}


