import Plotly from 'plotly.js-dist'
// import cloneDeep from 'lodash.clonedeep'
import chartOptions from './options'
import panels from './panels'


const paramsHandler = async ( chart, spreadsheet, mainAccordion, prefix  ) => {

  // Update chart params options
  chart.fileUpload.fileName = document.getElementById( `${prefix}__fileUpload[fileName]` ).value
  chart.fileUpload.fileId = document.getElementById( `${prefix}__fileUpload[fileId]` ).value
  chart.fileUpload.sheetId = document.getElementById( `${prefix}__fileUpload[sheetId]` ).value
  chart.fileUpload.chartType = document.getElementById( `${prefix}__fileUpload[chartType]` ).value

  // return  if no file name or file ID or chart type 
  if ( ! chart.fileUpload.fileName || !  chart.fileUpload.fileId || ! chart.fileUpload.sheetId || ! chart.fileUpload.chartType ) return

    // mainAccordion.close(0)
    Plotly.purge(`${prefix}__plotlyChart`)
    Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
    // chart = cloneDeep(emptyChart)

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

      console.log(chart)

   })

}

export default paramsHandler
