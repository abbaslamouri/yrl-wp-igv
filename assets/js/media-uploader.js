import Plotly from 'plotly.js-basic-dist'
import fetchData from "./fetch-data";
import drawChart from "./draw-chart";
import { 
  displayAdminMessage,
  setSheetIdOptions,
  showInputField,
  toggleElementById,
  showElementById,
  hideElementById,
  removePanel
} from "./utilities"


let jsonRes = {} // Server response

const mediaUploader = function (chart, iwpgvObj) {

  // Initialize the media uploader
  let mediaUploader;
  if (mediaUploader) {
    mediaUploader.open();
    return;
  }
  mediaUploader = wp.media.frames.file_frame = wp.media({
    title: "Select File",
    button: {
      text: "Select File",
    },
    multiple: false,
  });

  // Add media uploader event handler
  mediaUploader.on("select", async function (event) {

    // Hide warning and show spinner
    toggleElementById( `${iwpgvObj.prefix}__spinner` );

    try {

      // Get attachment
      const attachment = mediaUploader.state().get("selection").first().toJSON()

      // Bail if attachment can't be found
      if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

      // Set file upload input field value
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value = attachment.filename

      // Purge chart
      Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
      Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
      Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

      const oldSpreadsheet = {...jsonRes.spreadsheet}
      // chart.chartParams.options.sheetId = null

      // document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
      // document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .panelIntro`).innerHTML = ""

      // Hide min/max inputs if visible
      hideElementById( `${iwpgvObj.prefix}__plotMinMax` )
  
      // Get from node
      const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

      // Bail if no form is found
      if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

      // Create form object and append action and nonce
      const formData = new FormData( form )
      formData.append("action", iwpgvObj.file_select_action);
      formData.append("nonce", iwpgvObj.file_select_nonce);

      //send ajax resquest
      jsonRes = await fetchData(formData);

      console.log("JSONRESPONSE", jsonRes)
      
      // Bail is server response status = error
      if (jsonRes.status && jsonRes.status === "error ") throw new Error(  jsonRes.message )

      const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
      const chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)
      const fileUploadInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`)
      const enableRangeSliderInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`)
      const enableMinMaxTableChartInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`)
      const enableTableChartInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`)
          
      // Set sheet Id select field options, update sheet Id select field values and unhide it
      setSheetIdOptions(jsonRes.spreadsheet, sheetIdInput)
      sheetIdInput.value = ""
      showInputField( sheetIdInput )

      // Update chart type select field values and unhide it
      chartTypeInput.value = ""
      showInputField( chartTypeInput )

      // Unhide remaining checkboxes
      showInputField( enableRangeSliderInput )
      showInputField( enableMinMaxTableChartInput )
      showInputField( enableTableChartInput )

      // Add change event listener to all chart Params inputs
      document.addEventListener("change", function (event) {

        // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
        if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return

        Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
        Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
        Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

        // Bail if no file, sheet Id or chart type
        if( ! fileUploadInput.value ||  ! sheetIdInput.value || ! chartTypeInput.value   ) return
        console.log("old", oldSpreadsheet)
        console.log("new", jsonRes.spreadsheet)
        console.log("=======",oldSpreadsheet == jsonRes.spreadsheet)

        if (chart.chartParams.options.sheetId && oldSpreadsheet[chart.chartParams.options.sheetId] && jsonRes.spreadsheet[sheetIdInput.value].data.length < oldSpreadsheet[chart.chartParams.options.sheetId].data.length) {
          console.log("HERE")
          for (let i = jsonRes.spreadsheet[sheetIdInput.value].data.length-1; i < oldSpreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
            delete chart.chartTraces.options[i]
            delete chart.chartTraces.panel.sections[i]
          }
        }
        
        // Delete extra rows if the length of the existing data object is greater thatn the new datat object
        if (chart.chartParams.options.sheetId && jsonRes.spreadsheet[chart.chartParams.options.sheetId] && (jsonRes.spreadsheet[chart.chartParams.options.sheetId].data.length > jsonRes.spreadsheet[sheetIdInput.value].data.length )) {
          for (let i = jsonRes.spreadsheet[sheetIdInput.value].data.length-1; i < jsonRes.spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
            delete chart.chartTraces.options[i]
            delete chart.chartTraces.panel.sections[i]
          }
        }

        // remove layout panel toggle and panel
        document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
        document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
        document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
        document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""
        // removePanel( document.getElementById(`${iwpgvObj.prefix}__chartConfigPanel`) )
        // removePanel( document.getElementById(`${iwpgvObj.prefix}__chartTracesPanel`) )
        // removePanel( document.getElementById(`${iwpgvObj.prefix}__tableChartConfigPanel`) )
        // removePanel( document.getElementById(`${iwpgvObj.prefix}__minMaxTableChartConfigPanel`) )

        // Update chart
        chart.chartParams.options.fileUpload = fileUploadInput.value
        chart.chartParams.options.sheetId = sheetIdInput.value
        chart.chartParams.options.chartType = chartTypeInput.value
        chart.chartParams.options.enableRangeSlider = enableRangeSliderInput.checked
        chart.chartParams.options.enableMinMaxTableChart = enableMinMaxTableChartInput.checked
        chart.chartParams.options.enableTableChart = enableTableChartInput.checked
        // chart.chartLayout = {},
        // chart.chartConfig = {},
        // chart.chartTraces = [],
        // chart.tableChartConfig = {}
        // chart.minMaxTableChartConfig = {}
       


       

        // chart.chartTraces.options.x = []
        // chart.chartTraces.options.y =[]

        // Draw chart
        drawChart(jsonRes.spreadsheet, chart, iwpgvObj)

      });

      
    } catch (error) {

      displayAdminMessage(error.message, "error",  iwpgvObj)
      console.log("CAUGHT ERROR", error)

    } finally {

      // Hide warning and show spinner
      toggleElementById( `${iwpgvObj.prefix}__spinner` );
      showElementById( `${iwpgvObj.prefix}__warning`);

    }

   
  })

  mediaUploader.open()

}

export default mediaUploader;
