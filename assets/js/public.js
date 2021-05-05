import Plotly from 'plotly.js-dist'
import "../sass/public.scss"


console.log("PUBLIC");

let iwpgvPublic = typeof yrl_wp_igv__front_end_chart !== "undefined" ?  yrl_wp_igv__front_end_chart : {}


console.log("iwpgvPublic", iwpgvPublic, `${iwpgvPublic}__plotly-${iwpgvPublic.payload.chart.chartParams.options.chartId}`)
console.log(`${iwpgvPublic.payload.pluginObj}__plotly-${iwpgvPublic.payload.chart.chartParams.options.chartId}`)

Plotly.newPlot(`${iwpgvPublic.payload.pluginObj}__plotly-${iwpgvPublic.payload.chart.chartParams.options.chartId}`, Object.values(iwpgvPublic.payload.chart.chartTraces.options), iwpgvPublic.payload.chart.chartLayout.options, iwpgvPublic.payload.chart.chartLayout.options.config)
