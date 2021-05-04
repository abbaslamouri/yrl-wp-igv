import Plotly from 'plotly.js-dist'
import swal from 'sweetalert';
import deleteChart from "./delete-chart"


const listCharts = async function ( charts, iwpgvObj) {

  
  Object.values(charts).forEach(async (el) => {
    console.log(el)

    for ( let i=0; i < el.chartTraces.options.length; i++) {

      el.chartTraces.options[i].x = el.sheet.data[0]
      el.chartTraces.options[i].y = el.sheet.data[i+1]
      el.chartTraces.options[i].showlegend = false
      el.chartLayout.options.hovermode = false
      el.chartLayout.options.height = 200
      el.chartLayout.options.margin ={
        autoexpand: true,
        t:10,
        b:30,
        l:60,
        r:60,
        pad:0
      }
      el.chartLayout.options.title = {}
      el.chartLayout.options.config.displayModeBar = false
    }
    Plotly.newPlot(`${iwpgvObj.prefix}__chart__${el.chartParams.options.chartId}`, el.chartTraces.options, el.chartLayout.options, el.chartLayout.options.config).then( function() {

      document.getElementById(`${iwpgvObj.prefix}__deleteChart[${el.chartParams.options.chartId}]`).addEventListener("click", function (event) {  
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
            const jsonRes = deleteChart(el.chartParams.options.chartId, iwpgvObj)
            // if (jsonRes.status && jsonRes.status === "success") displayAdminMessage(jsonRes.message, "success",  iwpgvObj)
            const card = document.getElementById(`${iwpgvObj.prefix}__chart__${el.chartParams.options.chartId}__card`)
            document.getElementById(`${iwpgvObj.prefix}__listChartsForm`).removeChild(card)
            swal(`Chart (ID=${el.chartParams.options.chartId}) has been deleted!`, {
              icon: "success",
            });
          } 
          // else {
          //   swal("Your imaginary file is safe!");
          // }
        });

       

      } )

    })
  
  })

}

export default listCharts;
