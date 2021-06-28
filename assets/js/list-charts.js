import Plotly from 'plotly.js-dist'
import swal from 'sweetalert'
import cloneDeep from 'lodash.clonedeep'
import deleteChart from "./delete-chart"
import resetChart from './reset-chart'
import panels from "./panels"
import fetchData from "./fetch-data";
import cancelChartBtnHandler from "./cancel-chart-handler"




import addNewChartBtnHandler from "./add-new-chart-handler"

import { createChartCard, chartsListDefaultLayout } from "./utilities"

const listCharts = async function ( charts, sheets, pluginUrl, wpRestUrl, wpRestNonce, prefix) {

  if ( ! charts.length ) {
    document.querySelector( `#${prefix}__admin .chart-library__content` ).innerHTML = "<div class='no-charts'> There are no charts to display</div>"
    return
  }

  charts.forEach( async(chart) => {

    createChartCard(chart, pluginUrl, `#${prefix}__admin .chart-library__content`, prefix)

    if (sheets[chart.fileUpload.chartId]) {

      for ( let i=0; i < chart.traces.length; i++) {

        chart.traces[i].x = sheets[chart.fileUpload.chartId].data[0]
        chart.traces[i].y = sheets[chart.fileUpload.chartId].data[i+1]
        
      }

      // const oldHeight = chart.layout.height
      // const oldShowlegend = chart.layout.showlegend
      // const oldHovermode = chart.layout.hovermode
      // const oldDisplayModeBar = chart.config.displayModeBar

      const newChart = cloneDeep( chart )
      
      newChart.layout.showlegend = false
      newChart.layout.hovermode = false
      newChart.layout.height = 300
      newChart.config.displayModeBar = false

      // chartsListDefaultLayout(chart)

      await Plotly.newPlot(`${prefix}__chart__${chart.fileUpload.chartId}`, newChart.traces, newChart.layout, newChart.config)

      // chart.layout.height = oldHeight
      // chart.layout.showlegend = oldShowlegend
      // chart.layout.hovermode = oldHovermode
      // chart.config.displayModeBar = oldDisplayModeBar

    } else {
      document.getElementById( `${prefix}__chart__${chart.fileUpload.chartId}` ).innerHTML = `<div class='file-missing'>${chart.fileUpload.fileUpload} cannot be found</div>`
    }
  
  })


  document.querySelectorAll( `#${prefix}__admin .card__edit-chart`).forEach( ( element ) => {

    element.addEventListener("click", async function (event) {  
      event.preventDefault()

      try {

        // Get chart Id
        const chartId = event.target.closest(".card__edit-chart").dataset.chartId
        const chart = charts.filter(chart => chart.fileUpload.chartId == chartId)[0]

        // mainAccordion.close(0)
        resetChart(chart, prefix)
        // addNewChartBtnHandler( chart, prefix )
        document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")

        document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")

        await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

        const response = await fetchData(`${wpRestUrl}/${chart.fileUpload.fileId}`, "GET", wpRestNonce )

        const jsonRes = await response.json()

        if (response.status !== 200 ) throw new Error(  jsonRes.message )

        panels( chart, jsonRes, prefix )

        

        // Add click event listener to the Add New Chart button
        document.addEventListener("click", async function (event) {
          event.preventDefault()

          switch ( event.target.id ) {

            case `${prefix}__cancelChart`:
              console.log("NBBBBB", chart)
              cancelChartBtnHandler( chart, prefix )
              break

            }

        })

        console.log(chart)

      } catch (error) {

        displayAdminMessage(error.message, "error",  prefix)
        console.log("CAUGHT ERROR", error)
    
      } 

    })

  })


  document.querySelectorAll( `#${prefix}__admin .card__delete-chart`).forEach( ( element ) => {

    element.addEventListener("click", async function (event) {  
      event.preventDefault()

      // Get chart Id
      const chartId = event.target.closest(".card__delete-chart").dataset.chartId

      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this chart",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then(async (willDelete) => {
        if (willDelete) {
          charts = await deleteChart(chartId, charts, wpRestUrl, wpRestNonce, prefix)
          console.log("CH", charts)
          swal(`Chart with ( ID=${chartId} ) has been deleted!`, {
            icon: "success",
          });
        } 
      })

    })

  })


  

}

export default listCharts;
