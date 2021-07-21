import Plotly from 'plotly.js-dist'
import localForage from 'localforage'

const configHandler = async ( key, value, prefix  ) => {

  
  try {

    let chart = await localForage.getItem( 'chart')
    if ( ! Object.keys(chart).length ) throw new Error( `Chart missing` )

    chart.config[key] = value    
    // Plotly.purge(`${prefix}__plotlyChart`)
    const plotlyPlot = await Plotly.react( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

    chart.traces = plotlyPlot.data
    chart.layout = plotlyPlot.layout

    await localForage.setItem( "chart", chart )
    console.log('chart', chart)          
    // Modebar
    // document.getElementById(`${prefix}__layout[modebar][orientation]`).disabled = ! chart.config.displayModeBar ? true : false
    // document.getElementById(`${prefix}__layout[modebar][bgcolor]`).disabled = ! chart.config.displayModeBar ? true : false
    // document.getElementById(`${prefix}__layout[modebar][color]`).disabled = ! chart.config.displayModeBar ? true : false
    // document.getElementById(`${prefix}__layout[modebar][activecolor]`).disabled = ! chart.config.displayModeBar ? true : false
    // document.getElementById(`${prefix}__config[displaylogo]`).disabled = ! chart.config.displayModeBar ? true : false

  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default configHandler
