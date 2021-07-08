import Trace from './Trace'
import PieTrace from './PieTrace'
import BasicOptions from './BasicOptions'
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Modebar from "./Modebar"
import Grid from "./Grid"
import ChartAxis from "./ChartAxis"


const chartOptions = ( chart, spreadsheet  ) => {

  // Remove extra traces if new spreasheet contains less columns than old spreasheet
  if ( chart.traces.length ) {
    const traceCount = [...chart.traces].length
    const spreadsheetLength = spreadsheet[chart.params.sheetId].data.length
    if ( spreadsheetLength < traceCount +1 ) chart.traces.splice( spreadsheetLength-1, traceCount - spreadsheetLength + 1 )
  }

  for (let i = 0;  i < spreadsheet[chart.params.sheetId].data.length - 1; i++) {

    // const result = spreadsheet[chart.params.sheetId].data[i].every( ( data ) => { data } )    
    if ( spreadsheet[chart.params.sheetId].data[i+1].every( ( data ) =>  ! data ) ) continue

    if ( chart.traces[i] === undefined ) {
      // switch ( chart.params.chartType ) {

        // case "scatter":
          chart.traces[i] = Trace.defaultOptions( i, chart.params.chartType, Object.values(spreadsheet[chart.params.sheetId]["labels"])[i+1], spreadsheet[chart.params.sheetId].data[0], spreadsheet[chart.params.sheetId].data[i+1] )
          // break

          // case "pie":
          // chart.traces[i] = PieTrace.defaultOptions( i, chart.params.chartType )
          // break

      // }
    }

    

    // chart.traces[i].name = Object.values(spreadsheet[chart.params.sheetId]["labels"])[i+1]
    // chart.traces[i].type = chart.params.chartType

    // if ( chart.params.chartType === "scatter" || chart.params.chartType === "bar" ) {
    //   // delete chart.traces[i].labels
    //   // delete chart.traces[i].values
    //   // chart.traces[i].x = new Date (parseInt(spreadsheet[chart.params.sheetId].data[0] )*1000)
    //   chart.traces[i].x = spreadsheet[chart.params.sheetId].data[0]
    //   chart.traces[i].y = spreadsheet[chart.params.sheetId].data[i+1]
    // } else if ( chart.params.chartType === "pie" ) {
    //   // delete chart.traces[i].x
    //   // delete chart.traces[i].y
    //   // delete chart.traces[i].xaxis
    //   // delete chart.traces[i].yaxis
    //   chart.traces[i].labels = spreadsheet[chart.params.sheetId].data[0]
    //   chart.traces[i].values = spreadsheet[chart.params.sheetId].data[i+1]
    // }

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
    chart.layout = { ...chart.layout, ...Grid.defaultOptions( ) }

    chart.layout = { ...chart.layout, ...Modebar.defaultOptions( ) }
    chart.config.displayModeBar = chart.layout.displayModeBar
    chart.config.displaylogo = chart.layout.displaylogo
    
    chart.layout.xaxis = ChartAxis.defaultOptions( "xaxis", "bottom", null, "Wavelength ( &#181;m )", null ) 
    // chart.layout.xaxis2 = ChartAxis.defaultOptions( "xaxis2", "top", "x", "Wavelength ( &#181;m )", "x" )
    chart.layout.yaxis = ChartAxis.defaultOptions( "yaxis", "left", null, "Transmittance ( % )", null )
    // chart.layout.yaxis2 = ChartAxis.defaultOptions( "yaxis2", "right", "y", "Reflectance ( % )", "y" )
  }

  return chart

}


export default chartOptions
