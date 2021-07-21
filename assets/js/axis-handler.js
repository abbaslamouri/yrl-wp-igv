import Plotly from 'plotly.js-dist'
import localForage from 'localforage'
import capitalize from 'lodash.capitalize'
import ChartAxis from "./ChartAxis"
import axesPanel from "./axes-panels"
import { displayAdminMessage, setSelectFieldOptions, fetchAxisOptions } from "./utilities"


const chartAxis =  async( axisType, prefix ) => {
 
  try {

    let chart = await localForage.getItem( 'chart')
    if ( ! Object.keys(chart).length  ) throw new Error( `Either chart or spreadsheet missing` )

    const axes = Object.keys(chart.layout).filter( ( prop ) => prop.includes(axisType))
    let lastIndex =  0
          
    for ( const prop in axes.length ) {
      lastIndex = parseInt(axes[prop].split(axisType)[1]) > lastIndex ? parseInt(axes[prop].split(axisType)[1]) : lastIndex
    }

    const index = lastIndex === 0 ? 2 : lastIndex+1
    const axisId = `${axisType}${index}`
    const axisSide = axisType === "xaxis" ? "top" : axisType === "yaxis" ? "right" : null
    const axisOverlaying = axisType === "xaxis" ? null : axisType === "yaxis" ? "y" : null


    if ( ! axisSide ) throw new Error( " Invalid axis Side" )

    chart.layout[axisId] = ChartAxis.defaultOptions( axisId, axisSide, axisOverlaying, "Wavelength-1 ( &#181;m )", null )

    for (const prop in chart.traces) {
      if ( chart.traces[prop].xaxis ) setSelectFieldOptions ( document.getElementById ( `${prefix}__traces[${prop}][xaxis]` ), fetchAxisOptions ( chart.layout, "xaxis", capitalize ) )
      if ( chart.traces[prop].yaxis ) setSelectFieldOptions ( document.getElementById (`${prefix}__traces[${prop}][yaxis]` ), fetchAxisOptions (chart.layout, "yaxis", capitalize) )
    }
    
    const plotlyPlot = await Plotly.react( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )//.then( ( ) => {
    chart.traces = plotlyPlot.data
    chart.layout = plotlyPlot.layout
    await localForage.setItem( 'chart', chart )

    axesPanel ( chart, axisType, prefix )

    console.log('chart', chart)

  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } 

}

export default chartAxis
