import Plotly from 'plotly.js-dist'
import chartOptions from "./options"
import panels from "./panels"

const drawChart = async( chart, spreadsheet, prefix ) => {

  // Fetch chart options
  chart = chartOptions(chart, spreadsheet)
   
  // Draw charts
  await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {

  // build chart options field panels
  panels( chart, spreadsheet, prefix )
  console.log(document.getElementById( `${prefix}__params[sheetId]` ).closest ('.field-group' ))

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${prefix}__saveChart` ).disabled = false
  document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
  document.querySelector( `#${prefix}__admin .loading` ).classList.add(`hidden`)

  console.log("CHART", chart)

  
}


export default drawChart
