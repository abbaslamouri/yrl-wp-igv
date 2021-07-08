import Plotly from 'plotly.js-dist'


const configHandler = async ( chart, key, value, prefix  ) => {
  console.log(chart)
  console.log(key)

  chart.config[key] = value    
  Plotly.purge(`${prefix}__plotlyChart`)
  Plotly.plot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )
        
  // Modebar
  document.getElementById(`${prefix}__layout[modebar][orientation]`).disabled = ! chart.config.displayModeBar ? true : false
  document.getElementById(`${prefix}__layout[modebar][bgcolor]`).disabled = ! chart.config.displayModeBar ? true : false
  document.getElementById(`${prefix}__layout[modebar][color]`).disabled = ! chart.config.displayModeBar ? true : false
  document.getElementById(`${prefix}__layout[modebar][activecolor]`).disabled = ! chart.config.displayModeBar ? true : false
  document.getElementById(`${prefix}__config[displaylogo]`).disabled = ! chart.config.displayModeBar ? true : false

}

export default configHandler
