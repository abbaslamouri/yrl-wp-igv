import Plotly from 'plotly.js-basic-dist'
import fetchData from "./fetch-data";
import drawChart from "./draw-chart";
import { 
  displayAdminMessage,
  setSheetIdOptions,
  unhideInputField,
  toggleElement
 } from "./utilities"

const mediaUploader = function (iwpgvObj, iwpgvCharts) {

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
    toggleElement( `${iwpgvObj.prefix}__spinner` );
    toggleElement( `${iwpgvObj.prefix}__warning`);
    
    try {

      console.log(Plotly)
      

      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).innerHTML =""
      Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);

      // Get attachment
      const attachment = mediaUploader.state().get("selection").first().toJSON()

      // Bail if attachment can't be found
      if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

      // Set file upload input field value
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value = attachment.filename

      // Initialize form, sheet Id and chart type input fields
      const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
      const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
      const chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)

      // Bail if no foem is found
      if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

      // Create form object
      const formData = new FormData( form )

      // Add action and nonce to form
      formData.append("action", iwpgvObj.file_select_action);
      formData.append("nonce", iwpgvObj.file_select_nonce);
      formData.append(`${iwpgvObj.prefix}__chartParams[chartType]`, chartTypeInput.value);

      //send ajax resquest
      const jsonRes = await fetchData(formData);

      console.log("JSONRESPONSE", jsonRes)
      
      // Bail is server response status = error
      if (jsonRes.status && jsonRes.status === "error ") throw new Error(  jsonRes.message )
        
      // Set sheet Id select field options, update sheet Id and chart type select field values and unhide both fields
      setSheetIdOptions(jsonRes.spreadsheet, sheetIdInput)
      sheetIdInput.value = (sheetIdInput.options.length === 2)  ? sheetIdInput.options[1].value : sheetIdInput.options[0].value
      chartTypeInput.value = jsonRes.post[`${iwpgvObj.prefix}__chartParams`].chartType
      unhideInputField( sheetIdInput );
      unhideInputField( chartTypeInput );

      if ( attachment.filename && (sheetIdInput.value || sheetIdInput.value == 0 ) &&  chartTypeInput.value) {
        // console.log(sheetIdInput.value, chartTypeInput.value)

        drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj);
      }

      // Add change event listener to all inputs with class containg iwpgvObj.prefix__chartParams
      document.addEventListener("change", async function (event) {
      
      // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
      if (! event.target.closest("form") || ! event.target.closest("form").id === `${iwpgvObj.prefix}__chartOptionsForm`) return

      if ( event.target.id === sheetIdInput.id || event.target.id === chartTypeInput.id ) {
        // console.log(sheetIdInput.id, chartTypeInput.id)
        // console.log(sheetIdInput.value, chartTypeInput.value)
        drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj);
      } 
      
    });



      
    } catch (error) {

      displayAdminMessage(error.message, "error",  iwpgvObj)
      console.log("CAUGHT ERROR", error)

    } finally {

      // Hide warning and show spinner
      toggleElement( `${iwpgvObj.prefix}__spinner` );
      toggleElement( `${iwpgvObj.prefix}__warning`);

    }

   
  })

  mediaUploader.open()

}

export default mediaUploader;
