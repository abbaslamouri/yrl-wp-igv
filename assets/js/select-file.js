import fetchData from "./fetch-data";
import { displayAdminMessage } from "./utilities";

const selectFile = async function ( attachment, iwpgvObj ) {


  try {

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
    return await fetchData(formData)
    
  } catch (error) {

    displayAdminMessage(error.message, "error",  iwpgvObj)
    console.log("CAUGHT ERROR", error)

  } 

};

export default selectFile;
