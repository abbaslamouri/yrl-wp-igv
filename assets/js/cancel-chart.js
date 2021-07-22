import Swal from 'sweetalert2'
import { hideAdminMessage } from "./utilities"


const cancelChart = ( prefix  ) => {

  Swal.fire({
    title: 'Are you sure?',
    text: "You will not be able to recover this chart!",
    // icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#DD2C00',
    cancelButtonColor: '#4f5b62',
    confirmButtonText: 'Delete'
  }).then( async ( result ) => {
    if (result.isConfirmed) {
      hideAdminMessage( prefix )
      document.querySelector( `#${prefix}__admin .edit-chart` ).classList.add("hidden")
      document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
    }
  })

}

export default cancelChart
  

