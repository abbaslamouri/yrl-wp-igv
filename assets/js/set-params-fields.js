import Plotly from 'plotly.js-dist'
import fetchData from "./fetch-data";
import { setSheetIdOptions, displayAdminMessage, hideOptions } from "./utilities";

const setParamsFields = async function ( fileName, fileId, sheetId, chartType, chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix ) {

   

  // Set filename, file ID and chart type
  // const fileName = attachment.filename
  // const fileId = attachment.id
  // const chartType = ""

  // Fetch spreadsheet
  const response = await fetchData(`${wpRestUrl}/${fileId}`, "GET", wpRestNonce )
  if (response.status !== 200 ) throw new Error(  response.json().message )
  const spreadsheet = await response.json()

  if ( ! sheetId ) {
      sheetId = spreadsheet.length == 1 ? Object.keys(spreadsheet)[0]: ""
  }


  mainAccordion.close(0)
  Plotly.purge(`${prefix}__plotlyChart`)
  Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
  document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")
  document.querySelector( `#${prefix}__admin .loading` ).classList.remove("hidden")
  hideOptions(prefix)
  displayAdminMessage (null, null, prefix)
  setSheetIdOptions (spreadsheet, document.getElementById( `${prefix}__fileUpload[sheetId]` ) )
  // setFileUploadFields( fileName, fileId, spreadsheet.length == 1 ? Object.keys(spreadsheet)[0]: "", chartType, null, prefix )
  document.getElementById(`${prefix}__fileUpload[fileName]`).value = fileName
  document.getElementById(`${prefix}__fileUpload[fileId]`).value = fileId
  document.getElementById(`${prefix}__fileUpload[chartId]`).value = chartId
  document.getElementById( `${prefix}__fileUpload[sheetId]` ).value = sheetId
  document.getElementById( `${prefix}__fileUpload[chartType]` ).value = chartType
  document.getElementById( `${prefix}__fileUpload[fileName]` ).closest( ".field-group" ).classList.remove( "hidden" )
  document.getElementById( `${prefix}__fileUpload[sheetId]` ).closest( ".field-group" ).classList.remove( "hidden" )
  document.getElementById( `${prefix}__fileUpload[chartType]` ).closest( ".field-group" ).classList.remove( "hidden" )
  document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
  document.querySelector( `#${prefix}__admin .loading` ).classList.add("hidden")
  if ( ! chartId ) mainAccordion.open(0)

  return await spreadsheet




};

export default setParamsFields;
