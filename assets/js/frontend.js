import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'
import "../sass/frontend.scss"
import { addRangeMinMaxInputs, minMaxRangesliderHandler } from "./utilities"

console.log("JJJJJJJ")

if (  yrl_wp_plotly_charts__plotlyChart ) {

  console.log(yrl_wp_plotly_charts__plotlyChart)


  const yrlPlotlyChartsObj = yrl_wp_plotly_charts__plotlyChart
  const chart = yrlPlotlyChartsObj.chart
  const spreadsheet = yrlPlotlyChartsObj.spreadsheet
  const prefix = yrlPlotlyChartsObj.prefix

  // drawChart ( chart, spreadsheet, prefix )
  if ( document.getElementById( `${prefix}__plotlyChart__${chart.params.chartId}` ) ) {
    Plotly.newPlot( `${prefix}__plotlyChart__${chart.params.chartId}`, chart.traces, chart.layout, chart.config ) .then( ( ) => {

      if (chart.params.enableMinMaxAvgTable) addRangeMinMaxInputs( chart, Plotly, floatRound, `${prefix}__plotlyChart__${chart.params.chartId}`, prefix )

      // Add range slider event handler
      eval(`${prefix}__plotlyChart__${chart.params.chartId}`).on('plotly_relayout',function(eventData){

        if (chart.params.enableMinMaxAvgTable) minMaxRangesliderHandler( chart, eventData, spreadsheet, Plotly, arrayMin, arrayMax, arrayMean, floatRound, `${prefix}__plotlyChart__${chart.params.chartId}`, prefix  )

      })
    } )
  }

  
  
}


