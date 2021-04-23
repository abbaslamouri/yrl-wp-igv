import Plotly from 'plotly.js-basic-dist'
import fetchData from "./fetch-data";
import drawChart from "./draw-chart";
import { 
  displayAdminMessage,
  setSheetIdOptions,
  unhideInputField,
  toggleElement,
  showElement
 } from "./utilities"



const mediaUploader = function (iwpgvCharts, iwpgvObj) {

  // Initialize server response, form, sheet Id and chart type input fields
  let jsonRes = {}
  let form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
  let sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
  let chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)


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
    // showElement( `${iwpgvObj.prefix}__warning`);
    
    try {     
      
      // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).innerHTML =""
      Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);
      console.log(document.getElementById(`${iwpgvObj.prefix}__chartLayoutPanel`))

      // remove layout panel toggle and panel
      if (document.getElementById(`${iwpgvObj.prefix}__chartLayoutPanel`))  {
        document.getElementById(`${iwpgvObj.prefix}__chartLayoutPanel`).previousSibling.remove()
        document.getElementById(`${iwpgvObj.prefix}__chartLayoutPanel`).remove()
      }

      // remove chart traces panel toggle and panel
      if (document.getElementById(`${iwpgvObj.prefix}__chartConfigPanel`))  {
        document.getElementById(`${iwpgvObj.prefix}__chartConfigPanel`).previousSibling.remove()
        document.getElementById(`${iwpgvObj.prefix}__chartConfigPanel`).remove()
      }

      // remove chart traces panel toggle and panel
      if (document.getElementById(`${iwpgvObj.prefix}__chartTracesPanel`))  {
        document.getElementById(`${iwpgvObj.prefix}__chartTracesPanel`).previousSibling.remove()
        document.getElementById(`${iwpgvObj.prefix}__chartTracesPanel`).remove()
      }
           

      // Get attachment
      const attachment = mediaUploader.state().get("selection").first().toJSON()

      // Bail if attachment can't be found
      if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

      // Set file upload input field value
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value = attachment.filename


      
      // Bail if no foem is found
      if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

      // Create form object
      let formData = new FormData( form )

      // Add action and nonce to form
      formData.append("action", iwpgvObj.file_select_action);
      formData.append("nonce", iwpgvObj.file_select_nonce);
      // formData.append(`${iwpgvObj.prefix}__chartParams[chartType]`, chartTypeInput.value);

      //send ajax resquest
      jsonRes = await fetchData(formData);

      console.log("JSONRESPONSE", jsonRes)
      
      // Bail is server response status = error
      if (jsonRes.status && jsonRes.status === "error ") throw new Error(  jsonRes.message )
        
      // Set sheet Id select field options, update sheet Id and chart type select field values and unhide both fields
      setSheetIdOptions(jsonRes.spreadsheet, sheetIdInput)
      sheetIdInput.value = "" // (sheetIdInput.options.length === 2)  ? sheetIdInput.options[1].value : sheetIdInput.options[0].value
      chartTypeInput.value = "" // jsonRes.post[`${iwpgvObj.prefix}__chartParams`].chartType
      unhideInputField( sheetIdInput );
      unhideInputField( chartTypeInput );

      // Enable save button
      document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = true

      


      // Draw chart if file, sheet and chart type are selected
      // if ( attachment.filename && (sheetIdInput.value || sheetIdInput.value === 0 ) &&  chartTypeInput.value) {
      //   drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj);
      // }

      // Add change event listener to all inputs with class containg iwpgvObj.prefix__chartParams
      document.addEventListener("change", async function (event) {


        //   // Create form object
        // formData = new FormData( form )

        // // Add action and nonce to form
        // formData.append("action", iwpgvObj.file_select_action);
        // formData.append("nonce", iwpgvObj.file_select_nonce);

        // // chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)
        // formData.append(`${iwpgvObj.prefix}__chartParams[chartType]`, chartTypeInput.value);

        // //send ajax resquest
        // const jsonRes = await fetchData(formData);

        // console.log("JSONRESPONSE", jsonRes)

        // // // Initialize form, sheet Id and chart type input fields
        // // const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
        // // const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
        // // const chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)

        // console.log("FILE", document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value)

        // // document.getElementById(event.target.id).value = event.target.value


        
        // // Bail is server response status = error
        // if (jsonRes.status && jsonRes.status === "error ") throw new Error(  jsonRes.message )
          
        // // Set sheet Id select field options, update sheet Id and chart type select field values and unhide both fields
        // // setSheetIdOptions(jsonRes.spreadsheet, sheetIdInput)
        // // sheetIdInput.value = (sheetIdInput.options.length === 2)  ? sheetIdInput.options[1].value : sheetIdInput.options[0].value
        // // chartTypeInput.value = jsonRes.post[`${iwpgvObj.prefix}__chartParams`].chartType
        // unhideInputField( sheetIdInput );
        // unhideInputField( chartTypeInput );

        // // console.log("OOOOO",sheetIdInput.id, chartTypeInput.id)
        // //     console.log(sheetIdInput.value, chartTypeInput.value)
        // //   console.log("JJJJJJJJJ",jsonRes.spreadsheet)
      
        // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
        if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` || ( event.target.id !== sheetIdInput.id && event.target.id !== chartTypeInput.id ) ) return 

        sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
        chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)
        
        if( ! sheetIdInput.value || ! chartTypeInput.value  ) return

        

        console.log("VALUES", sheetIdInput.value,  chartTypeInput.value)

      

          
        drawChart(jsonRes.spreadsheet, iwpgvCharts, iwpgvObj)
        
      
      });



      
    } catch (error) {

      displayAdminMessage(error.message, "error",  iwpgvObj)
      console.log("CAUGHT ERROR", error)

    } finally {

      // Hide warning and show spinner
      toggleElement( `${iwpgvObj.prefix}__spinner` );
      showElement( `${iwpgvObj.prefix}__warning`);

    }

   
  })

  mediaUploader.open()

}

export default mediaUploader;