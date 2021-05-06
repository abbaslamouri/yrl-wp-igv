import Plotly from 'plotly.js-dist'
import ChartTrace from "./ChartTrace"
import { fetchminMaxAvgTableChartData } from "./utilities"
import "../sass/public.scss"

let iwpgvPublic = typeof yrl_wp_igv__front_end_chart !== "undefined" ?  yrl_wp_igv__front_end_chart : {}

const payload = iwpgvPublic.payload


console.log("iwpgvPublic", iwpgvPublic)
console.log(`${payload.prefix}__plotlyChart-${payload.chart.chartParams.options.chartId}`)

// Assemble chart traces chart and and render chart traces panel
let index = 1;
while (index < payload.spreadsheet[payload.chart.chartParams.options.sheetId].labels.length) {
  payload.chart.chartTraces.options[index-1] = ( payload.chart.chartTraces.options[index-1] !== undefined )? payload.chart.chartTraces.options[index-1] : {}
  const chartTraceInstance =  new ChartTrace( payload.chart.chartTraces.options[index-1], payload.spreadsheet, index, payload.chart.chartParams.options.sheetId, payload.chart.chartParams.options.chartType, payload.prefix ) 
  payload.chart.chartTraces.options[index-1] = chartTraceInstance.options()
  // payload.chart.chartTraces.panel.sections[index-1] = chartTraceInstance.sections()
  index++
}

Plotly.newPlot(`${payload.prefix}__plotlyChart-${payload.chart.chartParams.options.chartId}`, Object.values(payload.chart.chartTraces.options), payload.chart.chartLayout.options, payload.chart.chartLayout.options.config)

// Render Min/Max?Avg table chart if enableMinMaxTableChart is true
if ( payload.chart.chartParams.options.enableMinMaxTableChart ) {
  payload.chart.minMaxAvgTableChart.options = fetchminMaxAvgTableChartData(payload.chart, payload.spreadsheet)
  Plotly.newPlot(`${payload.prefix}__plotlyMinMaxTable`, [payload.chart.minMaxAvgTableChart.options], payload.chart.minMaxAvgTableChart.options.layout, payload.chart.chartLayout.options.config)
}
