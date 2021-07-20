import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'
import panels from './panels'
import { addRangeMinMaxInputs, minMaxRangesliderHandler } from './utilities'

const drawChart = async( chart, spreadsheet, prefix ) => {
  
  // Draw charts
  await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {

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


 