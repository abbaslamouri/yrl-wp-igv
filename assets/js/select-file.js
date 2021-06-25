import { setSheetIdOptions, displayAdminMessage } from "./utilities";

const selectFile = async function ( attachment, wpRestUrl, wpRestNonce, prefix ) {

  try {

     // Bail if attachment can't be found
     if ( ! attachment || ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

     document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")
     document.querySelector( `#${prefix}__admin .loading` ).classList.remove("hidden")

     // Update selected file and file Id
     document.getElementById(`${prefix}__fileUpload[fileName]`).value = attachment.filename
     document.getElementById(`${prefix}__fileUpload[fileId]`).value = attachment.id

     // Fetch response
     const response = await fetch(`${wpRestUrl}/${attachment.id}`, {
       method: "GET",
       headers: {'X-WP-Nonce': wpRestNonce }
     })
     const spreadsheet = await response.json();
     console.log("JSONRES-UPLOAD", spreadsheet)

     if ( spreadsheet.message ) displayAdminMessage(spreadsheet.message, "error",  prefix)

     // Set sheet Id select field options, update sheet Id select field values
     setSheetIdOptions (spreadsheet, document.getElementById( `${prefix}__fileUpload[sheetId]` ) )
     document.getElementById( `${prefix}__fileUpload[sheetId]` ).selectedIndex = document.getElementById( `${prefix}__fileUpload[sheetId]` ).options.length == 2 ? 1 : ""

     // Set chart type value
     document.getElementById( `${prefix}__fileUpload[chartType]` ).value = ""

     // Unhide sheet Id and chart type select fields
     document.getElementById( `${prefix}__fileUpload[fileName]` ).closest( ".field-group" ).classList.remove( "hidden" )
     document.getElementById( `${prefix}__fileUpload[sheetId]` ).closest( ".field-group" ).classList.remove( "hidden" )
     document.getElementById( `${prefix}__fileUpload[chartType]` ).closest( ".field-group" ).classList.remove( "hidden" )

    //  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.remove( "hidden" )

    // toggleElementByClass( `#${prefix}__admin .spinner` )
    document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
    document.querySelector( `#${prefix}__admin .loading` ).classList.add("hidden")

     
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .basicOptionsAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .configAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .tracesAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .titleAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .legendAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .hoverlabelAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .modebarAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxisAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxis2Ac`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxisAc`).classList.add( "hidden" )
     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxis2Ac`).classList.add( "hidden" )

     // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .fileUploadAc .ac-panel`).classList.add( "hidden" )


     // Hide min/max inputs if visible
     // hideElementById( `${prefix}__plotMinMax` )

     document.getElementById(`${prefix}__saveChart`).disabled = true

     return spreadsheet

    
  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } 

};

export default selectFile;
