import fetchData from "./fetch-data";
import drawChart from "./draw-chart"
import {
  toggleElement,
  hideElement,
  showElement,
  setSheetIdOptions,
  setChartTypeId,
  displayAdminMessage,
  unhideInputField,
  chartOptionKey
} from "./utilities";

const selectFile = async function ( iwpgvObj, iwpgvCharts ) {



  toggleElement( `${iwpgvObj.prefix}__spinner` );
  toggleElement( `${iwpgvObj.prefix}__warning`);
  // hideElement( `${iwpgvObj.prefix}__plotlyChart`);


  try {

    



    // throw new Error( "Hello There")

    // console.log("OBJ", iwpgvObj)
    // console.log("CHART", iwpgvCharts)


    // Create a form data object
    const form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )

    // Bail if no foem is found
    if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

    // Create form object
    const formData = new FormData( form )

    // Add action and nonce to form
    formData.append("action", iwpgvObj.file_select_action);
    formData.append("nonce", iwpgvObj.file_select_nonce);
    formData.append(`${iwpgvObj.prefix}__chartParams[chartType]`, document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value);

    //send ajax resquest
   const jsonRes = await fetchData(formData);

    console.log("JSONRESPONSE", jsonRes)

    // Display message if any
    if (jsonRes.message) displayAdminMessage(jsonRes.message);
    
    // Success handler
    if (jsonRes.status && jsonRes.status === "success") {
      
      // Update sheetID select field options and value
      const sheetIdInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
      setSheetIdOptions(jsonRes.spreadsheet, sheetIdInput)
      sheetIdInput.value = (sheetIdInput.options.length === 2)  ? sheetIdInput.options[1].value : sheetIdInput.options[0].value

      // Update chart type select field value
      const chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)
      chartTypeInput.value = jsonRes.post[`${iwpgvObj.prefix}__chartParams`].chartType

      // Reset form data ( eliminate action and nonce ans add sheetId and chart type values)
      formData.delete("action");
      formData.delete("nonce");
      formData.append(`${iwpgvObj.prefix}__chartParams[sheetId]`, sheetIdInput.value );
      formData.append(`${iwpgvObj.prefix}__chartParams[chartType]`, chartTypeInput.value );

        // Loop throu the form data to to populate the chart and the panels
      for (const property of formData ) {
        const inputParams = chartOptionKey(property[0])
        const fieldId = inputParams.key
        const fieldValue = property[1]
        iwpgvCharts.chart[inputParams.control][fieldId] = fieldValue
     }

      console.log("KKKKKK",{...iwpgvCharts.chart})

      if ( iwpgvCharts.chart.chartParams.fileUpload && (iwpgvCharts.chart.chartParams.sheetId || iwpgvCharts.chart.chartParams.sheetId == 0 ) &&  iwpgvCharts.chart.chartParams.chartType) {
        drawChart(iwpgvObj, iwpgvCharts, jsonRes);
      }

      // Set sheet Id select field

      


      // Unhide sheed Id and chart type input fields
      unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`) );
      unhideInputField( document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`) );

      // Add change event listener to all inputs with class containg iwpgvObj.prefix__chartParams
      document.addEventListener("change", async function (event) {
        
        // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
        if (! event.target.closest("form") || ! event.target.closest("form").id === `${iwpgvObj.prefix}__chartOptionsForm`) return

        if ( event.target.id.includes(`${iwpgvObj.prefix}__chartParams`)) {
          selectFile(iwpgvObj, iwpgvCharts)
        } 
        
      });
      
    } 
  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } finally {
    toggleElement(`${iwpgvObj.prefix}__spinner`);
    toggleElement(`${iwpgvObj.prefix}__warning`);
  }

};

export default selectFile;
