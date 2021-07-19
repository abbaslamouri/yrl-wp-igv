import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'
import TableTrace from './TableTrace'
import chartOptions from './options'
import panels from './panels'

import { addMinMaxAvgTable, addRangeMinMaxInputs, minMaxRangesliderHandler } from './utilities'


const drawChart = async( chart, spreadsheet, action, fileStatus, prefix ) => {

  
  chart = chartOptions (chart, spreadsheet, action, fileStatus )

  // Add min/max/avg table cahrt
  if (chart.params.enableMinMaxAvgTable) {
    addMinMaxAvgTable( chart, TableTrace, spreadsheet, arrayMin, arrayMax, arrayMean, floatRound )
  } else {
    chart.layout.xaxis.domain = [0,1]
  }


  // Draw charts
  await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {

  console.log('CHART', chart)

  // build chart options field panels
  panels( chart, spreadsheet, prefix )

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${prefix}__saveChart` ).disabled = false
  document.getElementById( `${prefix}__saveChart` ).classList.remove('hidden')
  document.querySelector( `#${prefix}__admin .loading` ).classList.add(`hidden`)



  if (chart.params.enableMinMaxAvgTable) addRangeMinMaxInputs( chart, Plotly, floatRound, `${prefix}__plotlyChart`, prefix )

  // Add range slider event handler
  eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

    if (chart.params.enableMinMaxAvgTable) minMaxRangesliderHandler( chart, eventData, spreadsheet, Plotly, arrayMin, arrayMax, arrayMean, floatRound, `${prefix}__plotlyChart`, prefix  )

  })


}


export default drawChart


 