import Plotly from 'plotly.js-dist'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import Trace from "./Trace"
import chartOptions from "./options"
import panels from "./panels"
import { indexOfAll, displayAdminMessage, hideOptions, chartOptionKey, trimArray, setSelectFieldOptions, resetChart, showToolTip, cancelChart } from "./utilities"


const drawChart = async( chart, spreadsheet, prefix ) => {

  console.log(spreadsheet)

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

  const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
  const cellValues = []
  const names = []
  const min = []
  const mean = []
  const max = []
  for (const prop in originalData) {
    if (prop == 0 ) continue
    names.push(spreadsheet[chart.params.sheetId].labels[prop])
    min.push(Math.round((arrayMin(originalData[prop]) + Number.EPSILON) * 1000) / 1000)
    max.push(Math.round((arrayMax(originalData[prop]) + Number.EPSILON) * 1000) / 1000)
    mean.push(Math.round((arrayMean(originalData[prop]) + Number.EPSILON) * 1000) / 1000)
  }
  cellValues.push( names, min, mean, max )

  const newTrace = Trace.defaultOptions( chart.traces.length, "Min/Max/Average", headerValues, cellValues, headerValues, cellValues  )
  newTrace.type = 'table'

// }

  
  
   
  // Draw charts
  await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {

  Plotly.addTraces( `${prefix}__plotlyChart`, {y: cellValues} )
  Plotly.restyle(`${prefix}__plotlyChart`, newTrace, chart.traces.length-1)

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
  // const cellValues = []
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
  // cellValues.push( names, min, mean, max )

  // console.log("MMMM", cellValues)


// Add range slider event handler
  eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){
    

    // const control = chartOptionKey(Object.keys(eventData)[0]).control
    // const key = chartOptionKey(Object.keys(eventData)[0]).key
    const keyParts = Object.keys(eventData)[0].split(".")

    const range = chart.layout[keyParts[0]][keyParts[1]]
    const selectedAxis = keyParts[0].split("xaxis")[1] !== undefined ? `x${keyParts[0].split("xaxis")[1]}` : 'x'

    // const originalData = spreadsheet[chart.params.sheetId].data



    // let xaxisData = []
    // if ( typeof originalData[0][0] === 'string' || originalData[0][0] instanceof String ) {
    //   xaxisData = [0]
    //   let i = 1
    //   while (i < originalData[0].length ) {
    //     xaxisData.push( i)
    //     i++
    //   }
    // } else {
    //   xaxisData = originalData[0]
    // } 

    console.log("Range", range)
    console.log("xaxisData", xaxisData)
    console.log(spreadsheet[chart.params.sheetId].data)
    console.log("indeces", indexOfAll(xaxisData, range[0], range[1]))

    const indeces = indexOfAll(xaxisData, range[0], range[1])

    console.log('test', indeces.includes( 1 ))

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

    // const xnewData = newData.filter(el => el !== null)
    console.log("new", newData)


    

    // const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
  const cellValues = []
  const names = []
  const min = []
  const mean = []
  const max = []
  for (const prop in newData) {
    if (prop == 0 ) continue
    names.push(spreadsheet[chart.params.sheetId].labels[prop])

    if ( ! isNaN( Math.round((arrayMin(newData[prop]) + Number.EPSILON ) * 1000) / 1000) ) {
      min.push(Math.round((arrayMin(newData[prop]) + Number.EPSILON) * 1000) / 1000)
    } else {
      min.push(null)
    }

    if ( ! isNaN( Math.round((arrayMax(newData[prop]) + Number.EPSILON ) * 1000) / 1000) ) {
      max.push(Math.round((arrayMax(newData[prop]) + Number.EPSILON) * 1000) / 1000)
    } else {
      max.push(null)
    }
    
    if ( ! isNaN( Math.round((arrayMean(newData[prop]) + Number.EPSILON ) * 1000) / 1000) ) {
      mean.push(Math.round((arrayMean(newData[prop]) + Number.EPSILON) * 1000) / 1000)
    } else {
      mean.push(null)
    }

  }
  cellValues.push( names, min, mean, max )

  console.log("NEWCELLVALUES", cellValues)

  Plotly.restyle(`${prefix}__plotlyChart`, {'cells.values' : [cellValues]}, chart.traces.length-1)

  console.log(chart)

   
    


    // console.log(Object.keys(eventData))
    // Bail if the event is other that range slider
    // if ( ! eventData['xaxis.range'] ) return
    
    // document.getElementsByName(`${prefix}__rangeMinInput`)[0].value =  chart.minMaxAvgTable.rounding ? parseFloat(eventData['xaxis.range'][0]).toFixed( chart.minMaxAvgTable.rounding ) : eventData['xaxis.range'][0]
    // document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value =  chart.minMaxAvgTable.rounding ? parseFloat(eventData['xaxis.range'][1]).toFixed( chart.minMaxAvgTable.rounding ) : eventData['xaxis.range'][1]
    
    // Plotly.restyle( `${prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getcellValues( chart, spreadsheet )] } )

  })


  
}


export default drawChart


 