import drawChart from "./draw-chart"
import { displayAdminMessage } from "./utilities"

const sheetHandler = async ( chart, sheetId, spreadsheet, mainAccordion, prefix ) => {

  try {

   

    // set params sheet id
    chart.params.sheetId = sheetId

    // get min/max/avg/ checkbox
    chart.params.enableMinMaxAvgTable = document.getElementById(`${prefix}__params[enableMinMaxAvgTable]`).checked

    // Unhide file name, file id and chart id input fields
    document.getElementById( `${prefix}__params[fileName]` ).closest('.field-group' ).classList.remove ( 'hidden' )
    document.getElementById( `${prefix}__params[sheetId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
    document.getElementById( `${prefix}__params[chartId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
    document.getElementById( `${prefix}__params[enableMinMaxAvgTable]` ).closest('.field-group' ).classList.remove ( 'hidden' )

    // Toggle warning
    document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )

    // draw chart
    await drawChart ( chart, spreadsheet, 'new', prefix )

    // Close main accordion
    mainAccordion.closeAll()

    // et chart updated flag
    const chartUpdated = true

    return chartUpdated

  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default sheetHandler
