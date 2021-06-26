import Plotly from 'plotly.js-dist'
import { displayAdminMessage, hideOptions } from "./utilities"

const resetChart = ( chart, prefix  ) => {

    document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
    Plotly.purge(`${prefix}__plotlyChart`)
    Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
    document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
    displayAdminMessage (null, null, prefix)
    hideOptions(prefix)
    chart = {}
  
}

export default resetChart
  

