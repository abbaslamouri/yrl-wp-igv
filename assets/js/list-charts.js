import Plotly from 'plotly.js-dist'
import cloneDeep from 'lodash.clonedeep'
import editChart from "./edit-chart"
import deleteChart from "./delete-chart"

import { createChartCard } from "./utilities"

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
      newChart.layout.height = newChart.params.chartType !== "pie" ? 300 : null
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

      // Get chart Id
      const chartId = event.target.closest(".card__delete-chart").dataset.chartId
      const chart = charts.filter(element => element.params.chartId == chartId)[0]
      deleteChart(charts, chartId, wpRestUrl, wpRestNonce, prefix)

    })

  })

}

export default listCharts;
