import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import TableTrace from './TableTrace'
import chartOptions from "./options"
import panels from "./panels"

import { indexOfAll, convertXAxisData, fetchMinMaxAvgCellValues, fetchTableCellsColors } from "./utilities"


const drawChart = async( chart, spreadsheet, prefix ) => {

  // Fetch chart options
  chart = chartOptions(chart, spreadsheet)



  // Add min/max/avg table cahrt
  const originalData =  spreadsheet[chart.params.sheetId].data
  const xaxisData = convertXAxisData( originalData )
  const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
  const cellValues = fetchMinMaxAvgCellValues( originalData, spreadsheet[chart.params.sheetId].labels, arrayMin, arrayMax, arrayMean )
  chart.traces[chart.traces.length]= TableTrace.defaultOptions( chart.traces.length, "Min/Max/Average", headerValues, cellValues  )
  chart.traces[chart.traces.length-1].cells.fill.color = [fetchTableCellsColors( chart.traces[chart.traces.length-1] )]

  
  
  // Draw charts
  await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {

  // build chart options field panels
  panels( chart, spreadsheet, prefix )

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${prefix}__saveChart` ).disabled = false
  document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
  document.querySelector( `#${prefix}__admin .loading` ).classList.add(`hidden`)

  console.log("CHART", chart)

  // Add range slider event handler
  eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

    const keyParts = Object.keys(eventData)[0].split(".")

    // Bail if ecentData does not include xaxis
    if ( ! keyParts.includes( 'xaxis' ) ) return

    const range = chart.layout[keyParts[0]][keyParts[1]]
    const indeces = indexOfAll(xaxisData, range[0], range[1])
    let newData = []
    for (const prop in originalData) {
      newData[prop] = []
      for (const index in originalData[prop]) {
        if ( indeces.includes( parseInt(index) ) ) {
          newData[prop][index] = originalData[prop][index]
        } else {
          newData[prop][index] = null
        }
      }
      newData[prop] = newData[prop].filter(el => el !== null)
    }

    const cellValues = fetchMinMaxAvgCellValues( newData, spreadsheet[chart.params.sheetId].labels, arrayMin, arrayMax, arrayMean )
    Plotly.restyle(`${prefix}__plotlyChart`, {'cells.values' : [cellValues]}, chart.traces.length-1)
    
  })

}


export default drawChart


 