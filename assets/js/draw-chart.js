import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import Trace from "./Trace"
import chartOptions from "./options"
import panels from "./panels"
import { displayAdminMessage, hideOptions, chartOptionKey, trimArray, setSelectFieldOptions, resetChart, showToolTip, cancelChart } from "./utilities"


const drawChart = async( chart, spreadsheet, prefix ) => {

   // Fetch chart options
   chart = chartOptions(chart, spreadsheet)


  const originalData = spreadsheet[chart.params.sheetId].data

  let xaxisData = []



  if ( typeof originalData[0][0] === 'string' || originalData[0][0] instanceof String ) {
    xaxisData = [0]

    let i = 1
    while (i < originalData[0].length ) {
      xaxisData.push( i)
      i++
    }
   
  } else {
    xaxisData = originalData[0]

  }


  const minMaxAvgData = []
  const names = []
  const min = []
  const mean = []
  const max = []

  for (const prop in chart.traces) {
    const trace = chart.traces[prop]
    names.push(trace.name)
    min.push(arrayMin(trace.y))
    max.push(arrayMax(trace.y))
    mean.push(arrayMean(trace.y))
  }
  minMaxAvgData.push( names, min, mean, max )
  const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]

 

  chart.traces[chart.traces.length] = Trace.defaultOptions( chart.traces.length, "Min/Max/Average", headerValues, minMaxAvgData, headerValues, minMaxAvgData  )

  
  
   
  // Draw charts
  await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {


  // build chart options field panels
  panels( chart, spreadsheet, prefix )

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${prefix}__saveChart` ).disabled = false
  document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
  document.querySelector( `#${prefix}__admin .loading` ).classList.add(`hidden`)

  console.log("CHART", chart)

  


  // const keyParts = Object.keys(eventData)[0].split(".")

  // const range = chart.layout.xaxis.range
  // const selectedAxis = 'x'
  // const minMaxAvgData = []
  // const names = []
  // const min = []
  // const mean = []
  // const max = []

  // for (const prop in chart.traces) {
  //   const trace = chart.traces[prop]
  //   console.log(prop)
  //   console.log(selectedAxis)
  //   console.log(trace.xaxis)
  //   console.log(trace.y)
  //   if ( selectedAxis == trace.xaxis ) {
  //     names.push(trace.name)
  //     min.push(arrayMin(trace.y))
  //     max.push(arrayMax(trace.y))
  //     mean.push(arrayMean(trace.y))
  //   }
  // }
  // minMaxAvgData.push( names, min, mean, max )

  // console.log("MMMM", minMaxAvgData)


// Add range slider event handler
  eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){
    

    // const control = chartOptionKey(Object.keys(eventData)[0]).control
    // const key = chartOptionKey(Object.keys(eventData)[0]).key
    const keyParts = Object.keys(eventData)[0].split(".")

    const range = chart.layout[keyParts[0]][keyParts[1]]
    const selectedAxis = keyParts[0].split("xaxis")[1] !== undefined ? `x${keyParts[0].split("xaxis")[1]}` : 'x'

    const originalData = spreadsheet[chart.params.sheetId].data

    const xaxisData = [0]

    if ( typeof originalData[0][0] === 'string' || originalData[0][0] instanceof String ) {
      // const increment = 1/(originalData[0].length - 1)
      // console.log("INC", increment)

      let i = 1
      while (i < originalData[0].length ) {
        xaxisData.push( i)
        i++
      }
     
    }
        


    console.log("Range", range)
    console.log("xaxisData", xaxisData)
    return

    const minMaxAvgData = []
    const names = []
    const min = []
    const mean = []
    const max = []

    for (const prop in chart.traces) {
      const trace = chart.traces[prop]
      console.log(prop)
      console.log(selectedAxis)
      console.log(trace.xaxis)
      console.log(trace.y)
      if ( selectedAxis == trace.xaxis ) {
        names.push(trace.name)
        min.push(arrayMin(trace.y))
        max.push(arrayMax(trace.y))
        mean.push(arrayMean(trace.y))
      }
    }
    minMaxAvgData.push( names, min, mean, max )

    console.log("MMMM", minMaxAvgData)
    console.log("PLotly", document.getElementById(`${prefix}__plotlyChart`).data)
    console.log(originalData)

    console.log(typeof originalData[0][0] === 'string' || originalData[0][0] instanceof String)

    


    // console.log(Object.keys(eventData))
    // Bail if the event is other that range slider
    // if ( ! eventData['xaxis.range'] ) return
    
    // document.getElementsByName(`${prefix}__rangeMinInput`)[0].value =  chart.minMaxAvgTable.rounding ? parseFloat(eventData['xaxis.range'][0]).toFixed( chart.minMaxAvgTable.rounding ) : eventData['xaxis.range'][0]
    // document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value =  chart.minMaxAvgTable.rounding ? parseFloat(eventData['xaxis.range'][1]).toFixed( chart.minMaxAvgTable.rounding ) : eventData['xaxis.range'][1]
    
    // Plotly.restyle( `${prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData( chart, spreadsheet )] } )

  })


  
}


export default drawChart


 