import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'
import TableTrace from "./TableTrace"
import tracesPanel from "./traces-panel"
import { displayAdminMessage, addMinMaxAvgTable, addRangeMinMaxInputs, minMaxRangesliderHandler } from "./utilities"

const minMaxAvgHandler = async ( value, prefix ) => {

  try {

    let chart = JSON.parse( localStorage.getItem( 'chart') ) ? JSON.parse( localStorage.getItem( 'chart') ) : {}
    let spreadsheet = JSON.parse( localStorage.getItem( 'spreadsheet') ) ? JSON.parse( localStorage.getItem( 'spreadsheet') ) : {}
  
    if ( ! Object.keys(chart).length || ! Object.keys(spreadsheet).length ) throw new Error( `Either chart or spreadsheet missing` )

    if ( value ) {

      chart.layout.xaxis.domain =  [0,.5]

      chart = addMinMaxAvgTable( chart, TableTrace, spreadsheet, arrayMin, arrayMax, arrayMean, floatRound )
      await Plotly.react( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {
      tracesPanel( chart, spreadsheet, prefix )

      addRangeMinMaxInputs( chart, Plotly, floatRound, `${prefix}__plotlyChart`, prefix )

      // Add range slider event handler
      eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){
        minMaxRangesliderHandler( chart, eventData, spreadsheet, Plotly, arrayMin, arrayMax, arrayMean, floatRound, prefix  )
      })

    } else {

      Plotly.relayout( `${prefix}__plotlyChart`, {'xaxis.domain': [0,1]})
      Plotly.deleteTraces( `${prefix}__plotlyChart`, chart.traces.length-1 )
      console.log("CCxxxx", chart)
       chart.traces.splice( chart.traces.length-1, 1)
      // await Plotly.react( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {
      tracesPanel( chart, spreadsheet, prefix )

      document.getElementById( `${prefix}__plotMinMaxAvgForm` ).classList.add( 'hidden')

    }

    console.log("CC", chart)

    localStorage.setItem("chart", JSON.stringify(chart))



  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default minMaxAvgHandler
