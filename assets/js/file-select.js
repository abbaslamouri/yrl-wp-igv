import localForage from 'localforage'
import arrayMin from 'lodash.min'
import arrayMax from 'lodash.max'
import arrayMean from 'lodash.mean'
import floatRound from 'lodash.round'
import drawChart from "./draw-chart"
import ScatterTrace from './ScatterTrace'
import TableTrace from './TableTrace'
import fetchSpreadsheet from './fetch-spreadsheet'
import { displayAdminMessage, setSelectFieldOptions, setChartTraces } from "./utilities"

const fileSelect = async function ( wpRestUrl, wpRestNonce, mediaUploader, mainAccordion, prefix ) {

  try {

    // Toggle warning and loading
    document.querySelector( `#${prefix}__admin .warning` ).classList.add( `hidden` )
    document.querySelector( `#${prefix}__admin .loading` ).classList.remove( `hidden` )

    //fetch attachment
    const attachment = mediaUploader.state().get("selection").first().toJSON()

    // Bail if attachment can't be found
    if ( ! attachment || ! attachment.filename ) throw new Error(  `Something went terribly wrong, we cannot find the attachemnt` )

    let chart = await localForage.getItem( 'chart')
    // Bail if attachment can't be found
    if ( ! Object.keys(chart) ) throw new Error( `Chart missing` )


    // set chart filename and file id and params file name and file id
    chart.params.fileName = attachment.filename
    document.getElementById(`${prefix}__params[fileName]`).value = chart.params.fileName
    chart.params.fileId = attachment.id
    document.getElementById(`${prefix}__params[fileId]`).value = chart.params.fileId

    // get min/max/avg/ checkbox
    chart.params.enableMinMaxAvgTable = document.getElementById(`${prefix}__params[enableMinMaxAvgTable]`).checked

    // Set chart params chart Id
    if ( undefined === chart.params.chartId ) chart.params.chartId  = null
    document.getElementById(`${prefix}__params[chartId]`).value = chart.params.chartId

    const spreadsheet = await fetchSpreadsheet ( chart, wpRestUrl, wpRestNonce, prefix )
    await localForage.setItem( "spreadsheet", spreadsheet )


    // Set sheet select field options array
    setSelectFieldOptions( document.getElementById( `${prefix}__params[sheetId]` ), spreadsheet.map( el  => el.sheetName ) )

    // Set params sheet id and sheet select value
    chart.params.sheetId  = spreadsheet.length == 1 ? Object.keys(spreadsheet)[0]: ""
    document.getElementById( `${prefix}__params[sheetId]` ).value = chart.params.sheetId

    // Unhide file name, file id and chart id input fields
    document.getElementById( `${prefix}__params[fileName]` ).closest('.field-group' ).classList.remove ( 'hidden' )
    document.getElementById( `${prefix}__params[sheetId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
    document.getElementById( `${prefix}__params[chartId]` ).closest('.field-group' ).classList.remove ( 'hidden' )
    document.getElementById( `${prefix}__params[enableMinMaxAvgTable]` ).closest('.field-group' ).classList.remove ( 'hidden' )
  
    // draw chart immediatelly if spreadsheet contains a single sheet
    if ( spreadsheet.length == 1  ) {
      mainAccordion.closeAll()
      chart = setChartTraces(chart, ScatterTrace, TableTrace, spreadsheet, arrayMin, arrayMax, arrayMean, floatRound)
      // await localForage.setItem( "chart", chart )
      document.getElementById( `${prefix}__params[sheetId]` ).disabled = true
      await drawChart ( chart, spreadsheet, prefix )
      document.querySelector( `#${prefix}__admin .loading` ).classList.add( `hidden` )
      // await localForage.setItem("chartUpdated", true)

    } else {
      document.querySelector( `#${prefix}__admin .warning` ).classList.remove( `hidden` )
      document.querySelector( `#${prefix}__admin .loading` ).classList.add( `hidden` )
    }
    
  } catch (error) {
    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)
  }

}

export default fileSelect;
