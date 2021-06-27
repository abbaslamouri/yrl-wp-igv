import Plotly from 'plotly.js-dist'
import swal from 'sweetalert'
import deleteChart from "./delete-chart"
import resetChart from './reset-chart'
import panels from "./panels"
import fetchData from "./fetch-data";



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

      chartsListDefaultLayout(chart)

      await Plotly.newPlot(`${prefix}__chart__${chart.fileUpload.chartId}`, chart.traces, chart.layout, chart.config)

    } else {
      document.getElementById( `${prefix}__chart__${chart.fileUpload.chartId}` ).innerHTML = `<div class='file-missing'>${chart.fileUpload.fileUpload} cannot be found</div>`
    }
  
  })


  document.querySelectorAll( `#${prefix}__admin .card__edit-chart`).forEach( ( element ) => {

    element.addEventListener("click", async function (event) {  
      event.preventDefault()

      // Get chart Id
      const chartId = event.target.closest(".card__edit-chart").dataset.chartId
      const chart = charts.filter(chart => chart.fileUpload.chartId == chartId)[0]

      // mainAccordion.close(0)
      resetChart(chart, prefix)
      // addNewChartBtnHandler( chart, prefix )
      document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")

      document.querySelector( `#${prefix}__admin .warning` ).classList.add("hidden")

      await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

      const spreadsheet = await fetchData(`${wpRestUrl}/${chart.fileUpload.fileId}`, "GET", wpRestNonce )

      panels( chart, spreadsheet, prefix )

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
