import Plotly from 'plotly.js-dist'
import swal from 'sweetalert';
import deleteChart from "./delete-chart"


const listCharts = async function ( charts, iwpgvObj) {

  Object.values(charts).forEach( async(el) => {

    if (el.sheet) {

      for ( let i=0; i < el.chartTraces.options.length; i++) {

        el.chartTraces.options[i].x = el.sheet.data[0]
        el.chartTraces.options[i].y = el.sheet.data[i+1]
        
        el.chartTraces.options[i].showlegend = false
        el.chartLayout.options.hovermode = false
        el.chartLayout.options.height = 200
        el.chartLayout.options.margin = { autoexpand: true, t:40, b:30, l:60, r:60, pad:0 }
        el.chartLayout.options.title.font.size = 12
        el.chartLayout.options.title.x = .15
        el.chartLayout.options.title.y = .95
        el.chartLayout.options.font.size = 12
        el.chartLayout.options.xaxis = { rangeslider: { visible: false} }
        el.chartLayout.options.config = { displayModeBar: false }
      }

      await Plotly.newPlot(`${iwpgvObj.prefix}__chart__${el.chartParams.options.chartId}`, el.chartTraces.options, el.chartLayout.options, el.chartLayout.options.config)

    } else {
      document.getElementById( `${iwpgvObj.prefix}__chart__${el.chartParams.options.chartId}` ).innerHTML = `<div class='file-missing'>${el.chartParams.options.fileUpload} cannot be found</div>`
    }
  
  })


  document.querySelectorAll( `.${iwpgvObj.prefix}__admin .delete-chart`).forEach( ( element ) => {

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
          const jsonRes = deleteChart(event.target.closest(".delete-chart").dataset.chartId, iwpgvObj)
          console.log("RESPONSE-DELETE", jsonRes)
          if (jsonRes.status && jsonRes.status === "success") displayAdminMessage(jsonRes.message, "success",  iwpgvObj)
          const card = document.getElementById(`${iwpgvObj.prefix}__chart__${event.target.closest(".delete-chart").dataset.chartId}__card`)
          document.querySelector(`.${iwpgvObj.prefix}__admin .chart-library`).removeChild(card)
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
