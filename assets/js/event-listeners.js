const fileUploadFields = ( event, chart, spreadsheet, prefix ) => {

  event.preventDefault()

    // Update chart params options
    chart.fileUpload.fileName = document.getElementById( `${prefix}__fileUpload[fileName]` ).value
    chart.fileUpload.fileId = document.getElementById( `${prefix}__fileUpload[fileId]` ).value
    chart.fileUpload.sheetId = document.getElementById( `${prefix}__fileUpload[sheetId]` ).value
    chart.fileUpload.chartType = document.getElementById( `${prefix}__fileUpload[chartType]` ).value

    // Bail if no file, sheet Id or chart type
    if( ! event.target.classList.contains(`fileUpload`) || ! chart.fileUpload.fileName ||  ! chart.fileUpload.fileId || ! chart.fileUpload.sheetId || ! chart.fileUpload.chartType   ) return

    // Hide warning and unhide loading
    document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")
    document.querySelector( `#${prefix}__admin .loading` ).classList.remove("hidden")

    // Remove extra traces if new spreasheet contains less columns than old spreasheet
    if (chart.traces) {
      const count = Object.values({...chart.traces}).length
        if ( spreadsheet[chart.fileUpload.sheetId].data.length < count +1 ) {
        for ( let i = spreadsheet[chart.fileUpload.sheetId].data.length-1; i <= count; i++ ) {
          delete chart.traces[i]
        }
      }
    }

}



module.exports = {
  fileUploadFields,
  
};
