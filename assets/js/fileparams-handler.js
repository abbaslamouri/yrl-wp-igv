import Plotly from 'plotly.js-dist'
import fetchNewChartOptions from './options'
import panels from "./panels"


import Trace from './Trace'
import BasicOptions from './BasicOptions'
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Modebar from "./Modebar"
import ChartAxis from "./ChartAxis"


const fileParamsHandler = async ( chart, spreadsheet, mainAccordion, prefix  ) => {

  // Update chart params options
  chart.fileUpload.fileName = document.getElementById( `${prefix}__fileUpload[fileName]` ).value
  chart.fileUpload.fileId = document.getElementById( `${prefix}__fileUpload[fileId]` ).value
  chart.fileUpload.sheetId = document.getElementById( `${prefix}__fileUpload[sheetId]` ).value
  chart.fileUpload.chartType = document.getElementById( `${prefix}__fileUpload[chartType]` ).value

  // return  if no file name or file ID or chart type 
  if ( ! chart.fileUpload.fileName || !  chart.fileUpload.fileId || ! chart.fileUpload.sheetId || ! chart.fileUpload.chartType ) return

   // Hide warning and unhide loading
   document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")
   document.querySelector( `#${prefix}__admin .loading` ).classList.remove("hidden")

   chart = fetchNewChartOptions(chart, spreadsheet)

   document.querySelector( `#${prefix}__admin .loading` ).classList.add("hidden")

   await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

   panels( chart, spreadsheet, prefix )

   mainAccordion.closeAll()

   // Enable save button  // Add click event listener to the chart params panel inoput fields
   document.getElementById( `${prefix}__saveChart` ).disabled = false
   document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")

   // Add click event listener to the Add New Chart button
   document.addEventListener("click", async function (event) {
     event.preventDefault()

     switch ( event.target.id ) {

       case `${prefix}__cancelChart`:
         console.log("NBBBBB", chart)
         cancelChartBtnHandler( chart, prefix )
         break

       }

   })

   console.log("CHART",chart)


}



export default fileParamsHandler
