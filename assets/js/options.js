import Trace from './Trace'
import BasicOptions from './BasicOptions'
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Modebar from "./Modebar"
import ChartAxis from "./ChartAxis"


const newChartOptions = ( chart, spreadsheet  ) => {

  
  // Remove extra traces if new spreasheet contains less columns than old spreasheet
  if ( chart.traces.length ) {
    const traceCount = [...chart.traces].length
    const spreadsheetLength = spreadsheet[chart.fileUpload.sheetId].data.length
    if ( spreadsheetLength < traceCount +1 ) chart.traces.splice( spreadsheetLength-1, traceCount - spreadsheetLength + 1 )
  }

  for (let i = 0;  i < spreadsheet[chart.fileUpload.sheetId].data.length - 1; i++) {

    // Traces options
    if (chart.traces[i] === undefined) {
      chart.traces[i] = Trace.defaultOptions( i )
      chart.traces[i].name = Object.values(spreadsheet[chart.fileUpload.sheetId]["labels"])[i+1]
      chart.traces[i].x = spreadsheet[chart.fileUpload.sheetId].data[0]
      chart.traces[i].y = spreadsheet[chart.fileUpload.sheetId].data[i+1]
    } else {
      chart.traces[i].name = Object.values(spreadsheet[chart.fileUpload.sheetId]["labels"])[i+1]
      chart.traces[i].x = spreadsheet[chart.fileUpload.sheetId].data[0]
      chart.traces[i].y = spreadsheet[chart.fileUpload.sheetId].data[i+1]
    }
  }
    
    
  // Retreive new chart options
  if ( Object.values(chart.layout).length ) {
    chart.config.responsive = chart.layout.responsive !== undefined ? chart.layout.responsive : false 
    chart.config.staticPlot = chart.layout.staticPlot !== undefined ? chart.layout.staticPlot : false

  }  else {
    chart.layout = { ...chart.layout, ...BasicOptions.defaultOptions( ) }
    chart.config.responsive = chart.layout.responsive
    chart.config.staticPlot = chart.layout.staticPlot

    chart.layout = { ...chart.layout, ...Title.defaultOptions( ) }
    chart.layout = { ...chart.layout, ...Legend.defaultOptions( ) }
    chart.layout = { ...chart.layout, ...Hoverlabel.defaultOptions( ) }

    chart.layout = { ...chart.layout, ...Modebar.defaultOptions( ) }
    chart.config.displayModeBar = chart.layout.displayModeBar
    chart.config.displaylogo = chart.layout.displaylogo
    
    chart.layout.xaxis = ChartAxis.defaultOptions( "xaxis", "bottom", null, "Wavelength ( &#181;m )", null ) 
    chart.layout.xaxis2 = ChartAxis.defaultOptions( "xaxis2", "top", "x", "Wavelength ( &#181;m )", "x" )
    chart.layout.yaxis = ChartAxis.defaultOptions( "yaxis", "left", null, "Transmittance ( % )", null )
    chart.layout.yaxis2 = ChartAxis.defaultOptions( "yaxis2", "right", "y", "Reflectance ( % )", "y" )
  }

  return chart

}


export default newChartOptions
