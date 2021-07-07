import Plotly from 'plotly.js-dist'
// import cloneDeep from 'lodash.clonedeep'
import chartOptions from './options'
import panels from './panels'


const paramsHandler = async ( chart, spreadsheet, mainAccordion, prefix  ) => {

  return new Promise((resolve, reject) =>{
    if (true) {
       resolve(Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config ));   
       console.log("LLLLLL")  
    } else {
       reject()
    }
 });

  // Update chart params options
  chart.params.fileName = document.getElementById( `${prefix}__params[fileName]` ).value
  chart.params.fileId = document.getElementById( `${prefix}__params[fileId]` ).value
  chart.params.sheetId = document.getElementById( `${prefix}__params[sheetId]` ).value
  chart.params.chartType = document.getElementById( `${prefix}__params[chartType]` ).value

  // return  if no file name or file ID or chart type 
  if ( ! chart.params.fileName || !  chart.params.fileId || ! chart.params.sheetId || ! chart.params.chartType ) return

    // mainAccordion.close(0)
    Plotly.purge(`${prefix}__plotlyChart`)
    Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
    // chart = cloneDeep(emptyChart)
    console.log("HERE1")


    // Hide warning and unhide loading
    document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )
    document.querySelector( `#${prefix}__admin .loading` ).classList.remove( `hidden` )

    chart = chartOptions(chart, spreadsheet)
    
    Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config ).then( ( ) => {
      panels( chart, spreadsheet, prefix )

      mainAccordion.closeAll()
  
      // Enable save button  // Add click event listener to the chart params panel inoput fields
      document.getElementById( `${prefix}__saveChart` ).disabled = false
      document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
      // Hide warning and unhide loading
      // document.querySelector( `#${prefix}__admin .warning` ).classList.add( `${prefix}__hidden` )
      document.querySelector( `#${prefix}__admin .loading` ).classList.add(`hidden`)

      console.log("HERE")
      return "PPPPPPP"

   })

   console.log("HERE2")


}

export default paramsHandler
