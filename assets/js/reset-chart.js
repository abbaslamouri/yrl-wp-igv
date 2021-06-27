import Plotly from 'plotly.js-dist'
import { displayAdminMessage, hideOptions } from "./utilities"

const resetChart = ( chart, prefix  ) => {

    // document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")

    // Purge chart and table
    Plotly.purge(`${prefix}__plotlyChart`)
    Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)

    // document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")

    // Remove messages if any
    displayAdminMessage (null, null, prefix)

    //
    hideOptions(prefix)
    chart = {}
  
}

export default resetChart
  

