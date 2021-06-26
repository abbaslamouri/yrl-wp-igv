import Plotly from 'plotly.js-dist'
import swal from 'sweetalert'
import { hideOptions } from "./utilities"


const cancelChartBtnHandler = ( chart, prefix  ) => {

  if ( ! chart.traces.length ) {
    document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
    return
  }

  swal({
    title: "Are you sure?",
    text: "You will not be able to recover this chart",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
      Plotly.purge(`${prefix}__plotlyChart`)
      Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
      document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
      hideOptions(prefix)
      chart = {}
    } 
  })

}

export default cancelChartBtnHandler
  

