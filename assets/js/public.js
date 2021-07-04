import Plotly from 'plotly.js-dist'
import ScatterTrace from "./ScatterTrace"
import PieTrace from "./PieTrace"
import { hideElementById, showElementById, fetchminMaxAvgTableChartData, getMinMaxAvgData } from "./utilities"
import "../sass/public.scss"

console.log(yrl_wp_igv__plotlyChart)


if ( typeof yrl_wp_igv__plotlyChart === "undefined" ||  yrl_wp_igv__plotlyChart.status === "error") {

} else {

  let iwpgvPublic = typeof yrl_wp_igv__plotlyChart !== "undefined" ?  yrl_wp_igv__plotlyChart : {}



  const payload = iwpgvPublic.payload
  const paramsOptions = payload.chart.chartParams.options


  // Assemble chart traces chart and and render chart traces panel
  let index = 1;
  while (index < payload.spreadsheet[paramsOptions.sheetId].labels.length) {
    payload.chart.chartTraces.options[index-1] = ( payload.chart.chartTraces.options[index-1] !== undefined )? payload.chart.chartTraces.options[index-1] : {}
    const chartTraceInstance =  new Trace( payload.chart.chartTraces.options[index-1], payload.spreadsheet, index, paramsOptions.sheetId, paramsOptions.chartType, payload.prefix ) 
    payload.chart.chartTraces.options[index-1] = chartTraceInstance.options()
    // payload.chart.chartTraces.panel.sections[index-1] = chartTraceInstance.sections()
    index++
  }

  // Set range slider min and max input fields if enableChartRangeSlider is true
  if ( paramsOptions.enableRangeSlider ) {
    showElementById( `${payload.prefix}__plotMinMax` )
    showElementById( `${payload.prefix}__rangeMinInput` )
    showElementById( `${payload.prefix}__rangeMaxInput` )
    document.getElementById(`${payload.prefix}__rangeMinInput` ).closest(".form-group").classList.remove("hidden")
    document.getElementById(`${payload.prefix}__rangeMinInput`).value =  Math.min(...payload.spreadsheet[paramsOptions.sheetId].data[0]).toFixed(3)
    document.getElementById(`${payload.prefix}__rangeMaxInput` ).closest(".form-group").classList.remove("hidden")
    document.getElementById(`${payload.prefix}__rangeMaxInput`).value = Math.max(...payload.spreadsheet[paramsOptions.sheetId].data[0]).toFixed(3)
  } else {
    hideElementById( `${payload.prefix}__plotMinMax` )
    hideElementById( `${payload.prefix}__rangeMinInput` )
    hideElementById( `${payload.prefix}__rangeMaxInput` )
    document.getElementById(`${payload.prefix}__rangeMinInput` ).closest(".form-group").classList.add("hidden")
    document.getElementById(`${payload.prefix}__rangeMinInput`).value =  null
    document.getElementById(`${payload.prefix}__rangeMaxInput` ).closest(".form-group").classList.add("hidden")
    document.getElementById(`${payload.prefix}__rangeMaxInput`).value = null
  }

  // Update chartLayout options
  payload.chart.chartLayout.options.hovermode = ( payload.chart.chartLayout.hovermode ) ? payload.chart.chartLayout.hovermode : false

  Plotly.newPlot(`${payload.prefix}__plotlyChart__${paramsOptions.chartId}`, Object.values(payload.chart.chartTraces.options), payload.chart.chartLayout.options, payload.chart.chartLayout.options.config). then(() => {

    // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
    if ( paramsOptions.enableMinMaxTableChart ) {
      payload.chart.minMaxAvgTableChart.options = fetchminMaxAvgTableChartData(payload.chart, payload.spreadsheet)
      Plotly.newPlot(`${payload.prefix}__plotlyMinMaxAvgTable__${paramsOptions.chartId}`, [payload.chart.minMaxAvgTableChart.options], payload.chart.minMaxAvgTableChart.options.layout, payload.chart.chartLayout.options.config)
    }

    document.addEventListener("change", function (event) {
  
      event.preventDefault()

      switch( event.target.id ){

        case `${payload.prefix}__rangeMinInput`:
        case `${payload.prefix}__rangeMaxInput`:
          const xAxisMin = document.getElementById(`${payload.prefix}__rangeMinInput`).value
          const xAxisMax = document.getElementById(`${payload.prefix}__rangeMaxInput`).value
          if (parseFloat(xAxisMin) < parseFloat(xAxisMax) ) {
            Plotly.relayout( `${payload.prefix}__plotlyChart__${paramsOptions.chartId}`, { 'xaxis.range': [xAxisMin, xAxisMax] })
          }
          break
      }

    })

    // Add range slider event handler
    eval(`${payload.prefix}__plotlyChart__${paramsOptions.chartId}`).on('plotly_relayout',function(eventData){
      const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...payload.spreadsheet[paramsOptions.sheetId].data[0])
      const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...payload.spreadsheet[paramsOptions.sheetId].data[0])
      document.getElementById(`${payload.prefix}__rangeMinInput`).value = parseFloat(xAxisMin).toFixed(3)
      document.getElementById(`${payload.prefix}__rangeMaxInput`).value = parseFloat(xAxisMax).toFixed(3)
      payload.chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(payload.chart, payload.spreadsheet, xAxisMin, xAxisMax)
      console.log(payload.chart.minMaxAvgTableChart.options)
      Plotly.restyle( `${payload.prefix}__plotlyMinMaxAvgTable__${paramsOptions.chartId}`, { "cells.values": [getMinMaxAvgData( payload.chart, payload.spreadsheet, xAxisMin, xAxisMax)] } )
    })

  }) 
  
}


