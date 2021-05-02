const chartParamsChangeHandler = (event) => {

  console.log("E", spreadsheet)


  // Bail if the clicked item is not inside the `${iwpgvCharts.prefix}__chartOptionsForm` form 
  if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${iwpgvObj.prefix}__chartOptionsForm` ||  ! event.target.classList.contains(`${iwpgvObj.prefix}__chartParams`)  ) return


  // Bail if no file, sheet Id or chart type
  if( ! document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value ||  ! document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value || ! document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value   ) return

  // // HIde chart and table charts
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // // Hide min/max inputs if visible
  // hideElementById( `${iwpgvObj.prefix}__plotMinMax` )

  // // remove layout panel toggle and panel
  // document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // // Update chart params options
  // iwpgvCharts.chart.chartParams.options.fileUpload = document.getElementById(`${iwpgvObj.prefix}__chartParams[fileUpload]`).value
  // iwpgvCharts.chart.chartParams.options.sheetId = document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`).value
  // iwpgvCharts.chart.chartParams.options.chartType = document.getElementById(`${iwpgvObj.prefix}__chartParams[chartType]`).value
  // iwpgvCharts.chart.chartParams.options.enableRangeSlider = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableRangeSlider]`).checked
  // iwpgvCharts.chart.chartParams.options.enableTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableTableChart]`).checked
  // iwpgvCharts.chart.chartParams.options.enableMinMaxTableChart = document.getElementById(`${iwpgvObj.prefix}__chartParams[enableMinMaxTableChart]`).checked

  // // Assemble chart layout chart and and ender chart layout panel
  // const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout.options, iwpgvObj )
  // iwpgvCharts.chart.chartLayout.options = chartLayoutInstance.options()
  // iwpgvCharts.chart.chartLayout.panel.sections = chartLayoutInstance.sections()
  // renderPanel(iwpgvCharts.chart.chartLayout.panel, iwpgvObj)
  // // Show the panel toogle and content divs
  // document.querySelector( `.accordion__toggle.chartLayout` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.chartLayout` ).classList.remove("hidden")



  console.log("======", spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId], iwpgvCharts.chart.chartTraces.options)

  const sheetIdInput =  document.getElementById(`${iwpgvObj.prefix}__chartParams[sheetId]`)
  // if (iwpgvCharts.chart.chartTraces.options.length && iwpgvCharts.chart.chartParams.options.sheetId && iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId] && jsonResjsonRes..spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data.length) {
    if (spreadsheet[sheetIdInput.value].data.length < iwpgvCharts.chart.chartTraces.options.length) {
    // console.log("HERE")
    for (let i = spreadsheet[sheetIdInput.value].data.length-1; i < iwpgvCharts.chart.chartTraces.options.length; i++ ) {
      delete iwpgvCharts.chart.chartTraces.options[i]
      delete iwpgvCharts.chart.chartTraces.panel.sections[i]
    }
  }

  renderChart()

  

  // charParamsChangeHandler(spreadsheet, iwpgvCharts.chart, iwpgvObj)
   
  // Draw chart
  // renderChart(spreadsheet, iwpgvCharts, iwpgvObj)







  

  // Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyTable`)
  // Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxTable`)

  // Load accordion
  new Accordion( { collapsed: false } )

  


  // Remove change event listener to all chart Params inputs
  // document.removeEventListener( "change", chartParamsHandler );

}


export default chartParamsChangeHandler
