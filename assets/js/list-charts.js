import Plotly from 'plotly.js-dist'
import swal from 'sweetalert';
import deleteChart from "./delete-chart"


const listCharts = async function ( charts, prefix) {

  Object.values(charts).forEach( async(el) => {
    
    const config = undefined !== el.config ? el.config : {}

    if (el.sheet) {

      for ( let i=0; i < el.traces.length; i++) {

        el.traces[i].x = el.sheet.data[0]
        el.traces[i].y = el.sheet.data[i+1]
        
        el.traces[i].showlegend = false
        el.layout.hovermode = false
        el.layout.height = 200
        el.layout.margin = { autoexpand: true, t:40, b:30, l:60, r:60, pad:0 }
        // el.layout.title.font.size = undefined !== el.layout.title && undefined !== el.layout.title.font && undefined !== el.layout.title.font.size ? 12 : null
        // el.layout.title.x = .15
        // el.layout.title.y = .95
        // el.layout.font.size = 12
        // el.layout.xaxis = { rangeslider: { visible: false} }
        config.displayModeBar = false 
      }

      await Plotly.newPlot(`${prefix}__chart__${el.fileUpload.chartId}`, el.traces, el.layout, config)

    } else {
      document.getElementById( `${prefix}__chart__${el.fileUpload.chartId}` ).innerHTML = `<div class='file-missing'>${el.fileUpload.fileUpload} cannot be found</div>`
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
