import Plotly from 'plotly.js-dist'
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
// import renderPanel from "./render-panel"
import Accordion from "./Accordion"
import saveChart from "./save-chart"
import { toggleElementById, showElementById, hideElementById, fetchTableChartOptions, fetchMinMaxAvgTableChartOptions } from "./utilities"



const fixChart =  ( iwpgvCharts, iwpgvObj, spreadsheet ) => {

  const chart = iwpgvCharts.chart
  
  // Update chartLayout options
  chart.chartLayout.options.hovermode = ( chart.chartLayout.hovermode ) ? chart.chartLayout.hovermode : false

  // Set range slider min and max input fields if enableChartRangeSlider is true
  if ( chart.chartParams.options.enableRangeSlider ) {
    showElementById( `${iwpgvObj.prefix}__plotMinMax` )
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  Math.min(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.remove("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0]).toFixed(3)
    chart.chartLayout.options.xaxis.rangeslider = true
  } else {
    hideElementById( `${iwpgvObj.prefix}__plotMinMax` )
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value =  null
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput` ).closest(".form__group").classList.add("hidden")
    document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = null
    chart.chartLayout.options.xaxis.rangeslider =false
  }

  // document.getElementById(`${iwpgvObj.prefix}__plotlyChart`).style.width = `${chart.chartLayout.width}%`
  Plotly.newPlot(`${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config).
  then (function() {
   toggleElementById( `${iwpgvObj.prefix}__spinner` )
   console.log("Done plotting Chart")
  })


  // Render table chart if enableTableChart is true
  if ( chart.chartParams.options.enableTableChart ) {
    const tableChartOptions = fetchTableChartOptions( chart, spreadsheet ) 
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [tableChartOptions], tableChartOptions.layout, chart.chartLayout.options.config).
    then (function() {
      console.log("Done plotting Table")
    })
  
  }


  // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
  if ( chart.chartParams.options.enableMinMaxTableChart ) {
    const minMaxAvgTableChartOptions = fetchMinMaxAvgTableChartOptions( chart, spreadsheet )
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [minMaxAvgTableChartOptions]).
    then (function() {
      console.log("Done plotting MIn Max Table")
    })

  }






}





export default fixChart
