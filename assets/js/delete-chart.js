import Swal from 'sweetalert2'
import fetchData from "./fetch-data"
import pickBy from 'lodash.pickby'
import { displayAdminMessage } from "./utilities"

const deleteChart = async function ( chartId, sheets, wpRestUrl, wpRestNonce, prefix ) {

  try {









    

  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } 

}

export default deleteChart;
