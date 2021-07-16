import Plotly from 'plotly.js-dist'
import Swal from 'sweetalert2'

import cloneDeep from 'lodash.clonedeep'
import editChart from "./edit-chart"
import fetchData from "./fetch-data"
import deleteChart from "./delete-chart"
import pickBy from 'lodash.pickby'
import { createChartCard, displayAdminMessage, fetchChartListDefaultOptions } from "./utilities"
import {  } from "./utilities"


const listCharts = async function ( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix) {

  if ( ! charts.length ) {
    document.querySelector( `#${prefix}__admin .chart-library__content` ).innerHTML = "<div class='no-charts'> There are no charts to display</div>"
    return
  }

  for ( const prop in charts ) {
    
    // const sheet = sheets[prop].sheet
    // const chartId = charts[prop].params.chartId

    createChartCard(charts[prop], pluginUrl, `#${prefix}__admin .chart-library__content`, prefix)

    // for ( let i=0; i < charts[prop].traces.length; i++) {
    //   charts[prop].traces[i].x = sheets[prop].sheet.data[0]
    //   charts[prop].traces[i].y = sheets[prop].sheet.data[i+1]
    // }
    charts[prop] = fetchChartListDefaultOptions( charts[prop], sheets[prop] )
    await Plotly.newPlot(`${prefix}__chart__${charts[prop].params.chartId}`, charts[prop].traces, charts[prop].layout, charts[prop].config)


    // } else {
    //   document.getElementById( `${prefix}__chart__${chartId}` ).innerHTML = `<div class='file-missing'>${charts[prop].params.fileName} cannot be found</div>`
    // }
  
  }


  document.querySelectorAll( `#${prefix}__admin .card__edit-chart`).forEach( ( element ) => {

    element.addEventListener("click", async function (event) {  
      event.preventDefault()

      // Get chart Id and edit chart
      const chartId = event.target.closest('.card__edit-chart').dataset[charts[prop].params.chartId]
      const chart = charts.filter(element => element.params.chartId == charts[prop].params.chartId)[0]
      editChart( chart, chartId, wpRestUrl, wpRestNonce, mainAccordion, prefix )

    })

  })


  document.querySelectorAll( `#${prefix}__admin .card__delete-chart`).forEach( ( element ) => {

    element.addEventListener("click", async function (event) {  
      event.preventDefault()

      try {

        Swal.fire({
          title: 'Are you sure?',
          text: "You will not be able to recover this axis!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#DD2C00',
          cancelButtonColor: '#4f5b62',
          confirmButtonText: 'Delete'
        }).then( async ( result ) => {
          if (result.isConfirmed) {

            console.log( "charts", cloneDeep(charts))
            console.log( "sheets", cloneDeep(sheets))
            // Get chart Id
            const chartId = event.target.closest(".card__delete-chart").dataset.chartId
            if ( ! chartId ) throw new Error(  `Chart ID is required` )
            charts = charts.filter( element => element.params.chartId != chartId)
            sheets = sheets.filter( element => element.chartId != chartId)
            // sheets = pickBy(sheets, (value, key) => key != chartId)
            

        
            const response = await fetchData( wpRestUrl, "POST", wpRestNonce, JSON.stringify(charts) ) 
            const jsonRes = await response.json();
            console.log("JSONRES-DELETE", jsonRes)

            // Bail is server response status = error
            if (response.status !== 200 ) throw new Error(  jsonRes.message )

            const card = document.getElementById(`${prefix}__chart__${chartId}__card`)
            document.querySelector(`#${prefix}__admin .chart-library__content`).removeChild( card ) 

            // Success handler
            displayAdminMessage("Chart deleted successfully", "success",  prefix)

            // const chart = charts.filter(element => element.params.chartId == chartId)[0]
            // await deleteChart(charts, chartId, sheets, wpRestUrl, wpRestNonce, prefix)
            console.log("CTS", charts)
            console.log( "SHT", cloneDeep(sheets))
          }
        })
 
    
      } catch (error) {
    
        displayAdminMessage(error.message, "error",  prefix)
        console.log("CAUGHT ERROR", error)
    
      } 



      

    })

  })

}

export default listCharts;
