import swal from 'sweetalert'
import fetchData from "./fetch-data"
import { displayAdminMessage } from "./utilities"

const deleteChart = async function (charts, chartId, wpRestUrl, wpRestNonce, prefix) {

  try {

    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this chart",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then(async (willDelete) => {
      if (willDelete) {

        // Bail if there are no chart tr
        if ( ! chartId ) throw new Error(  `Chart ID is required` )
            
        const newCharts = charts.filter(chart => chart.fileUpload.chartId != chartId)

        const response = await fetchData( wpRestUrl, "POST", wpRestNonce, JSON.stringify(newCharts) ) 

        // Convert response to json
        const jsonRes = await response.json();

        console.log("JSONRES-DELETE", jsonRes)

        // Bail is server response status = error
        if (response.status !== 200 ) throw new Error(  jsonRes.message )

        const card = document.getElementById(`${prefix}__chart__${chartId}__card`)
        document.querySelector(`#${prefix}__admin .chart-library__content`).removeChild( card ) 

        // Success handler
        displayAdminMessage("Chart deleted successfully", "success",  prefix)

        swal(`Chart with ( ID=${chartId} ) has been deleted!`, {
          icon: "success",
        })

      } 
      
    })

    

  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } 

}

export default deleteChart;
