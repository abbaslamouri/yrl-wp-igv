import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'
import TableTrace from "./TableTrace"
import fetchSpreadsheet from './fetch-spreadsheet'
import tracesPanel from "./traces-panel"
import { displayAdminMessage, addMinMaxAvgTable, addRangeMinMaxInputs, minMaxRangesliderHandler } from "./utilities"

const minMaxAvgHandler = async ( chart, value, spreadsheet, wpRestUrl, wpRestNonce, prefix ) => {

  if ( ! Object.keys(spreadsheet).length ) spreadsheet = await fetchSpreadsheet ( chart, wpRestUrl, wpRestNonce, prefix )

  try {

    if ( value ) {

      const update = {'xaxis.domain': [0,.5]}
      Plotly.relayout( `${prefix}__plotlyChart`, update)

      addMinMaxAvgTable( chart, TableTrace, spreadsheet, arrayMin, arrayMax, arrayMean, floatRound )
      await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {
      tracesPanel( chart, spreadsheet, prefix )

      addRangeMinMaxInputs( chart, Plotly, floatRound, `${prefix}__plotlyChart`, prefix )
      // Add range slider event handler
      eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

        minMaxRangesliderHandler( chart, eventData, spreadsheet, Plotly, arrayMin, arrayMax, arrayMean, floatRound, prefix  )

      })

    } else {

      const update = {'xaxis.domain': [0,1]}
      Plotly.relayout( `${prefix}__plotlyChart`, update)
      Plotly.deleteTraces( `${prefix}__plotlyChart`, chart.traces.length-1 )
      tracesPanel( chart, spreadsheet, prefix )

      document.getElementById( `${prefix}__plotMinMaxAvgForm` ).classList.add( 'hidden')

    }


  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default minMaxAvgHandler
