import Plotly from 'plotly.js-dist'


const configHandler = async ( key, value, prefix  ) => {

  
  try {

    let chart = JSON.parse( localStorage.getItem( 'chart') ) ? JSON.parse( localStorage.getItem( 'chart') ) : {}
    if ( ! Object.keys(chart).length ) throw new Error( `Chart missing` )

    chart.config[key] = value    
    Plotly.purge(`${prefix}__plotlyChart`)
    Plotly.plot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

    localStorage.setItem("chart", JSON.stringify(chart))
          
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
