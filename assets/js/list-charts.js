import Plotly from 'plotly.js-dist'
import {
  toggleElement,
  hideElement,
  showElement,
  setSheetId,
  setChartTypeId,
  displayAdminMessage
} from "./utilities";
import fetchData from "./fetch-data";
import renderChart from "./render-chart";



const listCharts = async function ( charts, iwpgvObj) {

  
  Object.values(charts).forEach(async (el) => {
    console.log("EL", el)

    console.log(Object.values(el.chartTraces.options))

    console.log(Object.values(el.chartTraces.options).length === el.sheet.data.length-1)

    for ( let i=0; i < el.chartTraces.options.length; i++) {
      console.log(i)
      el.chartTraces.options[i].x = el.sheet.data[0]
      el.chartTraces.options[i].y = el.sheet.data[i+1]
      el.chartTraces.options[i].showlegend = false
      el.chartLayout.hovermode = false
      el.chartLayout.height = 200
      el.chartLayout.margin ={
        autoexpand: true,
        t:10,
        b:30,
        l:60,
        r:60,
        pad:0
      }
      el.chartLayout.title = null
      el.chartLayout.options.config.displayModeBar = false
    }
    // el.chartTraces.options.x =
    // el.chartTraces.options.y =
    Plotly.newPlot(`${iwpgvObj.prefix}__chart__${el.chartParams.options.chartId}`, el.chartTraces.options, el.chartLayout, el.chartLayout.options.config)

   
    // renderChart(el, el.chartParams.sheet, {chart:`${iwpgvObj.prefix}__chart__${el.chartParams.chartId}`}, "list")
  
  });

};

export default listCharts;
