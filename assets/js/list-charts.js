import Plotly from 'plotly.js-dist'
import swal from 'sweetalert';
import deleteChart from "./delete-chart"


const listCharts = async function ( charts, prefix) {

  Object.values(charts).forEach( async(chart) => {
    
    const config = undefined !== chart.config ? chart.config : {}

    if (chart.sheet) {

      for ( let i=0; i < chart.traces.length; i++) {

        chart.traces[i].x = chart.sheet.data[0]
        chart.traces[i].y = chart.sheet.data[i+1]
        
        chart.traces[i].showlegend = false
        chart.layout.hovermode = false
        chart.layout.height = 200
        chart.layout.margin = { autoexpand: true, t:40, b:30, l:60, r:60, pad:0 }
        // chart.layout.title.font.size = undefined !== chart.layout.title && undefined !== chart.layout.title.font && undefined !== chart.layout.title.font.size ? 12 : null
        // chart.layout.title.x = .15
        // el.layout.title.y = .95
        // el.layout.font.size = 12
        // el.layout.xaxis = { rangeslider: { visible: false} }
        config.displayModeBar = false 
      }

      await Plotly.newPlot(`${prefix}__chart__${chart.fileUpload.chartId}`, chart.traces, chart.layout, config)

    } else {
      document.getElementById( `${prefix}__chart__${chart.fileUpload.chartId}` ).innerHTML = `<div class='file-missing'>${chart.fileUpload.fileUpload} cannot be found</div>`
    }
  
  })


  document.querySelectorAll( `.${prefix}__admin .delete-chart`).forEach( ( element ) => {

    element.addEventListener("click", function (event) {  
      event.preventDefault()

      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this chart",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          const jsonRes = deleteChart(event.target.closest(".delete-chart").dataset.chartId, prefix)
          console.log("RESPONSE-DELETE", jsonRes)
          if (jsonRes.status && jsonRes.status === "success") displayAdminMessage(jsonRes.message, "success", prefix)
          const card = document.getElementById(`${prefix}__chart__${event.target.closest(".delete-chart").dataset.chartId}__card`)
          document.querySelector(`.${prefix}__admin .chart-library`).removeChild(card)
          swal(`Chart (ID=${event.target.closest(".delete-chart").dataset.chartId}) has been deleted!`, {
            icon: "success",
          });
        } 
        // else {
        //   swal("Your imaginary file is safe!");
        // }
      })

    })

  })

}

export default listCharts;
