import Plotly from 'plotly.js-dist'
import cloneDeep from 'lodash.clonedeep'
import { displayAdminMessage, resetChart } from "./utilities"

const addNewChart = ( chart, emptyChart, mainAccordion, prefix ) => {

  try {

    resetChart ( Plotly, prefix )
  
    // Unhide chart  and open first accordion panel 
    document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")

    // clone empty chart
    chart = cloneDeep(emptyChart)

    // hideOptions(prefix)
    mainAccordion.open(0)

    return chart

  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default addNewChart
