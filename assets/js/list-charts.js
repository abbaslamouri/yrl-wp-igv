import Plotly from 'plotly.js-dist'

const listCharts = async function ( charts, iwpgvObj) {

  
  Object.values(charts).forEach(async (el) => {

    for ( let i=0; i < el.chartTraces.options.length; i++) {
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
    Plotly.newPlot(`${iwpgvObj.prefix}__chart__${el.chartParams.options.chartId}`, el.chartTraces.options, el.chartLayout, el.chartLayout.options.config)
  
  });

};

export default listCharts;
