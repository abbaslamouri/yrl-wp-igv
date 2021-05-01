import Plotly from 'plotly.js-basic-dist'
import fetchData from "./fetch-data"
import Accordion from "./Accordion"
import ChartParams from "./ChartParams"
import ChartLayout from "./ChartLayout"
import mediaUploader from "./media-uploader"
import renderPanel from "./render-panel"
// import drawChart from "./draw-chart";
import listCharts from "./list-charts"
import { displayAdminMessage, showElementById, hideElementById, showInputField, setSheetIdOptions, charParamsChangeHandler } from "./utilities"
import "../sass/admin.scss"


let iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
let iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}
let jsonRes = {}
let spreadsheet = []




const drawChart = () => {

  // Hide chart and table charts
  Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // Hide min/max inputs if visible
  hideElementById( `${iwpgvObj.prefix}__plotMinMax` )

  // remove layout panel toggle and panel
  document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
  document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // Update chart params options
  iwpgvCharts.chart.chartParams.options.fileUpload = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value
  iwpgvCharts.chart.chartParams.options.sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value
  iwpgvCharts.chart.chartParams.options.chartType = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value
  iwpgvCharts.chart.chartParams.options.enableRangeSlider = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`).checked
  iwpgvCharts.chart.chartParams.options.enableTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`).checked
  iwpgvCharts.chart.chartParams.options.enableMinMaxTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`).checked

  // // Assemble chart layout chart and and ender chart layout panel
  // const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout.options, iwpgvObj )
  // iwpgvCharts.chart.chartLayout.options = chartLayoutInstance.options()
  // iwpgvCharts.chart.chartLayout.panel.sections = chartLayoutInstance.sections()
  // renderPanel(iwpgvCharts.chart.chartLayout.panel, iwpgvObj)
  // Show the panel toogle and content divs
  // document.querySelector( `.accordion__toggle.chartLayout` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.chartLayout` ).classList.remove("hidden")
  console.log("PLOTTING")

  // document.addEventListener( "change", chartParamsHandler )






  // console.log("======", jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId], iwpgvCharts.chart.chartTraces.options)

  // const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
  // // if (iwpgvCharts.chart.chartTraces.options.length && iwpgvCharts.chart.chartParams.options.sheetId && iwpgvCharts.jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId] && jsonResjsonRes..spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.jsonRes.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length) {
  //   if (jsonRes.spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.chart.chartTraces.options.length) {
  //   // console.log("HERE")
  //   for (let i = jsonRes.spreadsheet[sheetIdInput.value].data.length-1; i < iwpgvCharts.chart.chartTraces.options.length; i++ ) {
  //     delete iwpgvCharts.chart.chartTraces.options[i]
  //     delete iwpgvCharts.chart.chartTraces.panel.sections[i]
  //   }
  // }

  

  // charParamsChangeHandler(jsonRes.spreadsheet, iwpgvCharts.chart, iwpgvObj)
   
  // Draw chart
  // drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj)
  

  // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // Load accordion
  // new Accordion( { collapsed: false } )

  
}




const chartParamsHandler = (event) => {

  console.log("E", spreadsheet)


  // Bail if the clicked item is not inside the `${iwpgvCharts.prefix}__chartOptionsForm` form 
  if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return


  // Bail if no file, sheet Id or chart type
  if( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value ||  ! document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value || ! document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value   ) return

  // // HIde chart and table charts
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // // Hide min/max inputs if visible
  // hideElementById( `${iwpgvObj.prefix}__plotMinMax` )

  // // remove layout panel toggle and panel
  // document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // // Update chart params options
  // iwpgvCharts.chart.chartParams.options.fileUpload = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value
  // iwpgvCharts.chart.chartParams.options.sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value
  // iwpgvCharts.chart.chartParams.options.chartType = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value
  // iwpgvCharts.chart.chartParams.options.enableRangeSlider = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`).checked
  // iwpgvCharts.chart.chartParams.options.enableTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`).checked
  // iwpgvCharts.chart.chartParams.options.enableMinMaxTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`).checked

  // // Assemble chart layout chart and and ender chart layout panel
  // const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout.options, iwpgvObj )
  // iwpgvCharts.chart.chartLayout.options = chartLayoutInstance.options()
  // iwpgvCharts.chart.chartLayout.panel.sections = chartLayoutInstance.sections()
  // renderPanel(iwpgvCharts.chart.chartLayout.panel, iwpgvObj)
  // // Show the panel toogle and content divs
  // document.querySelector( `.accordion__toggle.chartLayout` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.chartLayout` ).classList.remove("hidden")



  console.log("======", spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId], iwpgvCharts.chart.chartTraces.options)

  const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
  // if (iwpgvCharts.chart.chartTraces.options.length && iwpgvCharts.chart.chartParams.options.sheetId && iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId] && jsonResjsonRes..spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length) {
    if (spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.chart.chartTraces.options.length) {
    // console.log("HERE")
    for (let i = spreadsheet[sheetIdInput.value].data.length-1; i < iwpgvCharts.chart.chartTraces.options.length; i++ ) {
      delete iwpgvCharts.chart.chartTraces.options[i]
      delete iwpgvCharts.chart.chartTraces.panel.sections[i]
    }
  }

  drawChart()

  

  // charParamsChangeHandler(spreadsheet, iwpgvCharts.chart, iwpgvObj)
   
  // Draw chart
  // drawChart(spreadsheet, iwpgvCharts, iwpgvObj)







  

  // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // Load accordion
  new Accordion( { collapsed: false } )

  


  // Remove change event listener to all chart Params inputs
  // document.removeEventListener( "change", chartParamsHandler );

}







console.log("iwpgvObj", {...iwpgvObj})
console.log("iwpgvCharts", {...iwpgvCharts})

try {

  // throw new Error("Stop")

  // Check if server error
  if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message )

  // List all charts
  if (iwpgvCharts.action && iwpgvCharts.action === "listCharts") {

    listCharts( iwpgvCharts.charts, iwpgvObj)

  } 
  
  // Add new chart or edit an existing chart
  if ( iwpgvCharts.action && iwpgvCharts.action === "editChart" ) {

     // Initialize the media uploader
     let mediaUploader;
     if (mediaUploader) {
       mediaUploader.open();
       throw new Error("Stop")
       // return;
     }
     mediaUploader = wp.media.frames.file_frame = wp.media({
       // title: "Select File",
       // button: {
       //   text: "Select File",
       // },
       multiple: false,
     });
 
     // Add media uploader event handler
     mediaUploader.on("select", async function () {
 
       // Get attachment
       const attachment = mediaUploader.state().get("selection").first().toJSON()
 
       // Bail if attachment can't be found
       if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )
 
       // Update selected file and file Id
       document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value = attachment.filename
       document.getElementById(`${iwpgvObj.prefix}__chartParams[fileId]`).value = attachment.id
 
       // Get from 
       const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
 
       // Bail if no form is found
       if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )
 
       // Create form object and append action and nonce
       const formData = new FormData( form )
       formData.append( "action", iwpgvObj.file_select_action );
       formData.append( "nonce", iwpgvObj.file_select_nonce );
       formData.append( "fileId", attachment.id );
 
       //send ajax resquest
       jsonRes = await fetchData(formData)
       console.log("JSONRES-UPLOAD", jsonRes)
       
       // Bail is server response status = error
       if (jsonRes.status && jsonRes.status === "error") throw new Error(  jsonRes.message )
 
       spreadsheet = jsonRes.spreadsheet
 
       // Set sheet Id select field options, update sheet Id select field values
       setSheetIdOptions(jsonRes.spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
       document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = ""
 
       // Update chart type select field
       document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value = ""
       
       // Show all chart params panel input fields
       showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
       showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) )
       showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`) )
       showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`) )
       showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`) )
 
     })

    
    if ( ! iwpgvCharts.spreadsheet) { // New chart

      // Assemble chart Params chart and panels
      const chartParamsInstance = new ChartParams( iwpgvCharts.chart.chartParams.options, iwpgvObj )
      iwpgvCharts.chart.chartParams.options = chartParamsInstance.options()
      iwpgvCharts.chart.chartParams.panel.sections = chartParamsInstance.sections()

      // Render chart params panel
      renderPanel(iwpgvCharts.chart.chartParams.panel, iwpgvObj)

      // Show the panel toogle and content divs
      document.querySelector( `.accordion__toggle.chartParams` ).classList.remove("hidden")
      document.querySelector( `.accordion__content.chartParams` ).classList.remove("hidden")

      // document.addEventListener( "change", chartParamsHandler )


    } else { // // Edit an existing chart

      console.log("Chart")

      // Set sheet Id select field options, update sheet Id select field values

      // Assemble chart Params chart and panels
      const chartParamsInstance = new ChartParams( iwpgvCharts.chart.chartParams.options, iwpgvObj )
      iwpgvCharts.chart.chartParams.options = chartParamsInstance.options()
      iwpgvCharts.chart.chartParams.panel.sections = chartParamsInstance.sections()

      // Render chart params panel
      renderPanel(iwpgvCharts.chart.chartParams.panel, iwpgvObj)

      // Show the panel toogle and content divs
      document.querySelector( `.accordion__toggle.chartParams` ).classList.remove("hidden")
      document.querySelector( `.accordion__content.chartParams` ).classList.remove("hidden")
   
      setSheetIdOptions(iwpgvCharts.spreadsheet, document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`))
      document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = iwpgvCharts.chart.chartParams.options.sheetId

      // Show the remaining input fields in the chart params panel
      // showInputField( document.getElementById(`${iwpgvObj.prefix}__fileUpload]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`) )
      showInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`) )



      spreadsheet = iwpgvCharts.spreadsheet

      // document.addEventListener( "change", chartParamsHandler )

     

      // Call draw chart
      // drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj)

      // drawChart()
      

      // Add change event listener to all chart Params inputs
      // document.addEventListener( "change", chartParamsHandler )

    }

     // Add click event listener to the chart params panel inoput fields
     document.getElementById(`${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`).addEventListener("click", async function (event) {
    
      event.preventDefault()

      mediaUploader.open()

      
      // // HIde chart and table charts
      // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
      // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
      // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

      // // Hide min and max input fields
      // hideElementById (`${iwpgvObj.prefix}__plotMinMax` )

      // mediaUploader( iwpgvCharts, iwpgvObj )
      //   document.addEventListener( "change", chartParamsHandler )

      
          
    })

    document.addEventListener( "change", chartParamsHandler )

   

    


    

    // Show saveChart button
    // showElementById (`${iwpgvObj.prefix}__saveChart` )
      
    // // Add click event listener to the chart params panel inoput fields
    // document.getElementById(`${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`).addEventListener("click", async function (event) {
     
    //   event.preventDefault()
      
    //   // HIde chart and table charts
    //   Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
    //   Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
    //   Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

    //   // Hide min and max input fields
    //   hideElementById (`${iwpgvObj.prefix}__plotMinMax` )

    //   mediaUploader( iwpgvCharts, iwpgvObj )
    //     document.addEventListener( "change", chartParamsHandler )

      
          
    // })



  }


} catch (error) {

  displayAdminMessage(error.message, "error",  iwpgvObj)
  console.log("CAUGHT ERROR", error)

} 

// Load accordion
new Accordion( { collapsed: false } )





















