import Plotly from 'plotly.js-dist'
import Swal from 'sweetalert2'

import cloneDeep from 'lodash.clonedeep'
import editChart from "./edit-chart"
import fetchData from "./fetch-data"
import deleteChart from "./delete-chart"
import pickBy from 'lodash.pickby'
import { createChartCard, displayAdminMessage } from "./utilities"
import {  } from "./utilities"


const listCharts = async function ( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, mainAccordion, prefix) {

  if ( ! charts.length ) {
    document.querySelector( `#${prefix}__admin .chart-library__content` ).innerHTML = "<div class='no-charts'> There are no charts to display</div>"
    return
  }

  charts.forEach( async(chart) => {

    createChartCard(chart, pluginUrl, `#${prefix}__admin .chart-library__content`, prefix)

    if (sheets[chart.params.chartId]) {

      for ( let i=0; i < chart.traces.length; i++) {

        chart.traces[i].x = sheets[chart.params.chartId].data[0]
        chart.traces[i].y = sheets[chart.params.chartId].data[i+1]
        
      }

      const newChart = cloneDeep( chart )
      
      newChart.layout.showlegend = false
      newChart.layout.hovermode = false
      newChart.layout.xaxis.rangeslider.visible = false
      newChart.layout.xaxis.domain = [0,1]
      newChart.layout.title.text = ''
      newChart.layout.margin.t = 10
      newChart.layout.margin.b = 10
      newChart.layout.margin.l = 10
      newChart.layout.margin.r = 10
      newChart.layout.height = newChart.params.chartType !== "pie" ? 300 : null
      newChart.traces[newChart.traces.length-1].visible = false

      // newChart.layout.width = 400
      newChart.config.displayModeBar = false

      await Plotly.newPlot(`${prefix}__chart__${chart.params.chartId}`, newChart.traces, newChart.layout, newChart.config)


    } else {
      document.getElementById( `${prefix}__chart__${chart.params.chartId}` ).innerHTML = `<div class='file-missing'>${chart.params.fileName} cannot be found</div>`
    }
  
  })


  document.querySelectorAll( `#${prefix}__admin .card__edit-chart`).forEach( ( element ) => {

    element.addEventListener("click", async function (event) {  
      event.preventDefault()

      // Get chart Id and edit chart
      const chartId = event.target.closest('.card__edit-chart').dataset.chartId
      const chart = charts.filter(element => element.params.chartId == chartId)[0]
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
            // Get chart Id
            const chartId = event.target.closest(".card__delete-chart").dataset.chartId
            if ( ! chartId ) throw new Error(  `Chart ID is required` )
            charts = charts.filter( element => element.params.chartId != chartId)
            sheets = pickBy(sheets, (value, key) => key != chartId)
            console.log( "charts", charts)
            console.log( "sheets", sheets)

        
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
