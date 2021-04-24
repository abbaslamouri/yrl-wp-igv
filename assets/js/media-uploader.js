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
      Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);

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

        // Bail if no file, sheet Id or chart type
        if( ! fileUploadInput.value ||  ! sheetIdInput.value || ! chartTypeInput.value   ) return

        Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);

        // remove layout panel toggle and panel
        removePanel( document.getElementById(`${iwpgvObj.prefix}__chartLayoutPanel`) )
        removePanel( document.getElementById(`${iwpgvObj.prefix}__chartConfigPanel`) )
        removePanel( document.getElementById(`${iwpgvObj.prefix}__chartTracesPanel`) )

        // Update chart
        chart.chartParams.fileUpload = fileUploadInput.value,
        chart.chartParams.sheetId = sheetIdInput.value,
        chart.chartParams.chartType = chartTypeInput.value,
        chart.chartParams.enableRangeSlider = enableRangeSliderInput.checked,
        chart.chartParams.enableMinMaxTableChart = enableMinMaxTableChartInput.checked,
        chart.chartParams.enableTableChart = enableTableChartInput.checked,
        chart.chartLayout = {},
        chart.chartConfig = {},
        chart.chartTraces = [],
        chart.tableChartConfig = {}

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
