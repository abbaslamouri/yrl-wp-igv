import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'

import { addRangeMinMaxInputs, minMaxRangesliderHandler } from "./utilities"

const renderFrontend = (chart, spreadsheet, prefix ) => {

  // drawChart ( chart, spreadsheet, prefix )
  Plotly.newPlot( `${prefix}__plotlyChart__${chart.params.chartId}`, chart.traces, chart.layout, chart.config ) //.then( ( ) => {

  // if (chart.params.enableMinMaxAvgTable) {
  //   document.getElementById( `${prefix}__rangeMinInput` ).value = floatRound( chart.layout.xaxis.range[0], chart.traces[chart.traces.length-1].rounding )
  //   document.getElementById( `${prefix}__rangeMaxInput` ).value = floatRound( chart.layout.xaxis.range[1], chart.traces[chart.traces.length-1].rounding )

  //   // Add change event listener to range min and range max input fields
  //   document.querySelector( `.${prefix}__public` ).addEventListener( "change", ( event ) => {
  //     event.preventDefault( )

  //     // Bail if not rangeMin or rangeMax inputs
  //     if ( event.target.id !== `${prefix}__rangeMinInput` && event.target.id !== `${prefix}__rangeMaxInput`) return
  //     const update = {'xaxis.range': [document.getElementById( `${prefix}__rangeMinInput` ).value, document.getElementById( `${prefix}__rangeMaxInput` ).value]}
  //     Plotly.relayout( `${prefix}__plotlyChart`, update)
    
  //   } )


  
  // }


  if (chart.params.enableMinMaxAvgTable) addRangeMinMaxInputs( chart, Plotly, floatRound, `${prefix}__plotlyChart__${chart.params.chartId}`, prefix )

  // Add range slider event handler
  eval(`${prefix}__plotlyChart__${chart.params.chartId}`).on('plotly_relayout',function(eventData){

    if (chart.params.enableMinMaxAvgTable) minMaxRangesliderHandler( chart, eventData, spreadsheet, Plotly, arrayMin, arrayMax, arrayMean, floatRound, `${prefix}__plotlyChart__${chart.params.chartId}`, prefix  )

  })


}

export default renderFrontend
