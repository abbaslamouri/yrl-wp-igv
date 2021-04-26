import Plotly from 'plotly.js-basic-dist'
import fetchData from "./fetch-data";
import drawChart from "./draw-chart";
import { 
  displayAdminMessage,
  setSheetIdOptions,
  unhideInputField,
  toggleElementById,
  showElementById
} from "./utilities"


let jsonRes = {} // Server response
let chart = {} // chart with all the options
let form // THis is the only form
let sheetIdInput // Spreadsheet Sheet Id select field
let chartTypeInput // Chart type select field
let fileUploadInput // file (spreadsheet) name
let enableRangeSliderInput // file (spreadsheet) name
let enableMinMaxTableChartInput // file (spreadsheet) name
let enableTableChartInput // file (spreadsheet) name


const mediaUploader = function (iwpgvObj) {

  // Get form, sheet Id and chart type input fields
  form = document.getElementById( `${iwpgvObj.prefix}__chartOptionsForm` )
  sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
  chartTypeInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`)
  fileUploadInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`)
  enableRangeSliderInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`)
  enableMinMaxTableChartInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`)
  enableTableChartInput = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`)


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

      Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);

      
      // REset sheet Id and chart tpe
      // sheetIdInput.value = "" // (sheetIdInput.options.length === 2)  ? sheetIdInput.options[1].value : sheetIdInput.options[0].value
      // chartTypeInput.value = "" // jsonRes.post[`${iwpgvObj.prefix}__chartParams`].chartType
      
      // Get attachment
      const attachment = mediaUploader.state().get("selection").first().toJSON()

      // Bail if attachment can't be found
      if ( ! attachment ||  ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

      // Set file upload input field value
      document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value = attachment.filename
      
      // Bail if no foem is found
      if (typeof (form) === "undefined") throw new Error(  `Can't find form with ID = ${iwpgvObj.prefix}__chartOptionsForm` )

      // Create form object
      const formData = new FormData( form )

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
      unhideInputField( sheetIdInput )
      unhideInputField( chartTypeInput )
      unhideInputField( enableRangeSliderInput )
      unhideInputField( enableMinMaxTableChartInput )
      unhideInputField( enableTableChartInput )

      // Set sheet Id select field
      setSheetIdOptions(jsonRes.spreadsheet, sheetIdInput)
      // document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value = chart.chartParams.sheetId

      // Enable save button
      // document.getElementById(`${iwpgvObj.prefix}__saveChart`).disabled = true

      // Add change event listener to all inputs with class containg iwpgvObj.prefix__chartParams
      document.addEventListener("change", async function (event) {

        // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
        if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` || ( event.target.id !== sheetIdInput.id && event.target.id !== chartTypeInput.id ) ) return 

        // Bail if no sheet Id or chart type
        if( ! fileUploadInput.value ||  ! sheetIdInput.value || ! chartTypeInput.value  ) return

        const chart = {
          chartParams: {
            fileUpload: fileUploadInput.value,
            sheetId: sheetIdInput.value,
            chartType: chartTypeInput.value,

          },
          chartLayout: {},
          chartConfig: {},
          chartTraces: []
        }

        // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).innerHTML =""
      Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`);

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

        // iwpgvCharts.chart.chartParams.fileUpload = fileUploadInput.value
        // iwpgvCharts.chart.chartParams.sheetId = sheetIdInput.value
        // iwpgvCharts.chart.chartParams.chartType = chartTypeInput.value
          
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
