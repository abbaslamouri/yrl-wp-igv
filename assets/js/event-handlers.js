import swal from 'sweetalert'

const addNewChartBtnHandler = ( chart, BasicOptions, Title, Legend, Hoverlabel, Modebar, ChartAxis, mainAccordion, prefix  ) => {

  // Unhide chart  and open first accordion panel 
  document.querySelector(`#${prefix}__admin .edit-chart`).classList.remove("hidden")
  mainAccordion.open(0)

  
  
  // Retreive new chart options
  //   if ( null !== iwpgvCharts.chart ) {
  //     chart = iwpgvCharts.chart
  //     chart.config.responsive = chart.layout.responsive !== undefined ? chart.layout.responsive : false 
  //     chart.config.staticPlot = chart.layout.staticPlot !== undefined ? chart.layout.staticPlot : false
  
  //   }  else {
  chart.layout = { ...chart.layout, ...BasicOptions.defaultOptions( ) }
  chart.config.responsive = chart.layout.responsive
  chart.config.staticPlot = chart.layout.staticPlot

  chart.layout = { ...chart.layout, ...Title.defaultOptions( ) }
  chart.layout = { ...chart.layout, ...Legend.defaultOptions( ) }
  chart.layout = { ...chart.layout, ...Hoverlabel.defaultOptions( ) }

  chart.layout = { ...chart.layout, ...Modebar.defaultOptions( ) }
  chart.config.displayModeBar = chart.layout.displayModeBar
  chart.config.displaylogo = chart.layout.displaylogo
  
  chart.layout.xaxis = ChartAxis.defaultOptions( "xaxis", "bottom", null, "Wavelength ( &#181;m )", null ) 
  chart.layout.xaxis2 = ChartAxis.defaultOptions( "xaxis2", "top", "x", "Wavelength ( &#181;m )", "x" )
  chart.layout.yaxis = ChartAxis.defaultOptions( "yaxis", "left", null, "Transmittance ( % )", null )
  chart.layout.yaxis2 = ChartAxis.defaultOptions( "yaxis2", "right", "y", "Reflectance ( % )", "y" )
// }

}


const cancelChartBtnHandler = ( chart, prefix  ) => {

  if ( !chart.traces.length ) {
    document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
    return
  }

  swal({
    title: "Are you sure?",
    text: "You will not be able to recover this chart",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      document.querySelector(`#${prefix}__admin .edit-chart`).classList.add("hidden")
      Plotly.purge(`${prefix}__plotlyChart`)
      Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)
      document.querySelector( `#${prefix}__admin .warning` ).classList.remove("hidden")
      hideOptions(prefix)
      chart = cloneDeep(emptyChart)
    } 
  })

}



module.exports = {
  addNewChartBtnHandler,
  cancelChartBtnHandler
  
};
