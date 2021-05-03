import Plotly from 'plotly.js-dist'
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
// import renderPanel from "./render-panel"
import Accordion from "./Accordion"
import saveChart from "./save-chart"
import { toggleElementById, showElementById, hideElementById, fetchTableChartOptions, getMinMaxAvgData, fetchMinMaxAvgTableChartOptions } from "./utilities"



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

    // Add change event listener on all the document
    document.addEventListener("change", function (event) {
    


      if (event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)) return

      // Range Min and Range Max handler
      if (event.target.closest("form") && event.target.closest("form").id == `${iwpgvObj.prefix}__plotMinMax` && ( event.target.id === `${iwpgvObj.prefix}__rangeMinInput` || event.target.id == `${iwpgvObj.prefix}__rangeMaxInput`) ) {

        event.preventDefault()


        const newXAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value ?  document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value : xAxisMin
        const newXAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value ? document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value : xAxisMax

        Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [newXAxisMin, newXAxisMax] })

        return
      }
    })

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

    // console.log("Done plotting Chart")

    // Add range slider event handler
    yrl_wp_igv__plotlyChart.on('plotly_relayout',function(eventData){  
      const x_start = (eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min(...spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data[0])
      const x_end = (eventData  && eventData['xaxis.range']) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data[0])
      document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = parseFloat(x_start).toFixed(3)
      document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = parseFloat(x_end).toFixed(3)
      iwpgvCharts.chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(iwpgvCharts.chart, spreadsheet, x_start, x_end)
      Plotly.restyle(`${iwpgvObj.prefix}__plotlyMinMaxTable`, iwpgvCharts.chart.minMaxAvgTableChart.options  )
    })

  })


  // Render table chart if enableTableChart is true
  if ( chart.chartParams.options.enableTableChart ) {

    // Set table header values
    const headerValues = []
    for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].labels.length; i++ ) {
      headerValues.push([`<b>${spreadsheet[chart.chartParams.options.sheetId].labels[i]}</b>`]);
    }
    chart.tableChart.options.header.values = headerValues

      // Set table header alignment
    chart.tableChart.options.header.align = [chart.tableChart.options.firstColAlign, chart.tableChart.options.header.align]


    // Round cells values if rounding is not 0
    if ( chart.tableChart.options.rounding) {
      const cellValues = []
      for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
        cellValues[i] =[]
        for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
          cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( chart.tableChart.options.rounding ) ) 
        }  
      }
      chart.tableChart.options.cells.values = cellValues  
    } else {
      chart.tableChart.options.cells.values = spreadsheet[chart.chartParams.options.sheetId].data
    }

      // Set table cells alignment
      chart.tableChart.options.cells.align = [chart.tableChart.options.firstColAlign, chart.tableChart.options.cells.align]

    // Set table even and odd row colors
    const rowFillColors = []
    for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
      rowFillColors[j] = (j % 2 === 0) ? chart.tableChart.options.oddRowColor : chart.tableChart.options.evenRowColor
    }
    chart.tableChart.options.cells.fill.color = [rowFillColors]
  
    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartLayout.options.config ).
    then (function() {

      // console.log("Done plotting Table")

    })
  
  }


  // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
  if ( chart.chartParams.options.enableMinMaxTableChart ) {

    // Set table header
    const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
    chart.minMaxAvgTableChart.options.header.values = headerValues

    chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet)

    // Set table cells alignment
    chart.minMaxAvgTableChart.options.cells.align = [chart.minMaxAvgTableChart.options.firstColAlign , chart.minMaxAvgTableChart.options.otherColsAlign]

    // Set table even and odd row colors
    const rowFillColors = []
    for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
      rowFillColors[j] = (j % 2 === 0) ? chart.minMaxAvgTableChart.options.evenRowColor : chart.minMaxAvgTableChart.options.oddRowColor
    }
    chart.minMaxAvgTableChart.options.cells.fill.color = [rowFillColors]

    Plotly.newPlot(`${iwpgvObj.prefix}__plotlyMinMaxTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, chart.chartLayout.options.config).
    then (function() {

      // console.log("Done plotting MIn Max Table")

    })

  }

  






}





export default fixChart
