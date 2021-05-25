import Accordion from "./Accordion"
import panel from "./panel"
import BasicOptions from "./BasicOptions"
import ChartTitle from "./ChartTitle"
import ChartLegend from "./ChartLegend"
import HoverLabel from "./HoverLabel"
import ModeBar from "./ModeBar"
import ChartAxis from "./ChartAxis"
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
import { displayAdminMessage } from "./utilities"



const renderPanels = ( iwpgvCharts, iwpgvObj, spreadsheet) => {

  const chart = iwpgvCharts.chart

  // Render layout basic options panel
  const basicOptionsInstance = new BasicOptions( chart.basicOptions.options, iwpgvObj )
  chart.basicOptions.options = basicOptionsInstance.options()
  chart.basicOptions.panel.sections = basicOptionsInstance.sections()
  panel(chart.basicOptions.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.basicOptions.panel` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.basicOptions.panel` ).classList.remove("hidden")

  // Render chart title panel
  const chartTitleInstance = new ChartTitle( chart.chartTitle.options, iwpgvObj )
  chart.chartTitle.options = chartTitleInstance.options()
  chart.chartTitle.panel.sections = chartTitleInstance.sections()
  panel(chart.chartTitle.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.chartTitle.panel` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.chartTitle.panel` ).classList.remove("hidden")

  // Render chart legend panel
  const chartLegendInstance = new ChartLegend( chart.chartLegend.options, iwpgvObj )
  chart.chartLegend.options = chartLegendInstance.options()
  chart.chartLegend.panel.sections = chartLegendInstance.sections()
  panel(chart.chartLegend.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.chartLegend.panel` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.chartLegend.panel` ).classList.remove("hidden")

  // Render chart legend panel
  const hoverLabelInstance = new HoverLabel( chart.hoverLabel.options, iwpgvObj )
  chart.hoverLabel.options = hoverLabelInstance.options()
  chart.hoverLabel.panel.sections = hoverLabelInstance.sections()
  panel(chart.hoverLabel.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.hoverLabel.panel` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.hoverLabel.panel` ).classList.remove("hidden")

  // Render mode bar panel
  const modeBarlInstance = new ModeBar( chart.modeBar.options, iwpgvObj )
  chart.modeBar.options = modeBarlInstance.options()
  chart.modeBar.panel.sections = modeBarlInstance.sections()
  panel(chart.modeBar.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.modeBar.panel` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.modeBar.panel` ).classList.remove("hidden")

  // Render bottom axis
  const bottomAxisInstance = new ChartAxis( chart.bottomAxis.options, chart.chartParams.options.chartType, "bottomAxis", iwpgvObj )
  chart.bottomAxis.options = bottomAxisInstance.options()
  chart.bottomAxis.panel.sections = bottomAxisInstance.sections()
  panel(chart.bottomAxis.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.bottomAxis.panel` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.bottomAxis.panel` ).classList.remove("hidden")

   // Render top axis
   const topAxisInstance = new ChartAxis( chart.topAxis.options, chart.chartParams.options.chartType, "topAxis", iwpgvObj )
   chart.topAxis.options = topAxisInstance.options()
   chart.topAxis.panel.sections = topAxisInstance.sections()
   panel(chart.topAxis.panel, iwpgvObj)
   document.querySelector( `.accordion__toggle.topAxis.panel` ).classList.remove("hidden")
   document.querySelector( `.accordion__content.topAxis.panel` ).classList.remove("hidden")

    // Render left axis
    const leftAxisInstance = new ChartAxis( chart.leftAxis.options, chart.chartParams.options.chartType, "leftAxis", iwpgvObj )
    chart.leftAxis.options = leftAxisInstance.options()
    chart.leftAxis.panel.sections = leftAxisInstance.sections()
    panel(chart.leftAxis.panel, iwpgvObj)
    document.querySelector( `.accordion__toggle.leftAxis.panel` ).classList.remove("hidden")
    document.querySelector( `.accordion__content.leftAxis.panel` ).classList.remove("hidden")

     // Render right axis
   const rightAxisInstance = new ChartAxis( chart.rightAxis.options, chart.chartParams.options.chartType, "rightAxis", iwpgvObj )
   chart.rightAxis.options = rightAxisInstance.options()
   chart.rightAxis.panel.sections = rightAxisInstance.sections()
   panel(chart.rightAxis.panel, iwpgvObj)
   document.querySelector( `.accordion__toggle.rightAxis.panel` ).classList.remove("hidden")
   document.querySelector( `.accordion__content.rightAxis.panel` ).classList.remove("hidden")


// // Assemble chart layout chart and and render chart layout panel
//   const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout.options, spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data[0], iwpgvCharts.chart.chartParams.options.chartType, iwpgvObj )
//   iwpgvCharts.chart.chartLayout.options = chartLayoutInstance.options()
//   iwpgvCharts.chart.chartLayout.panel.sections = chartLayoutInstance.sections()
//   panel(iwpgvCharts.chart.chartLayout.panel, iwpgvObj)
//   document.querySelector( `.accordion__toggle.chartLayout.panel` ).classList.remove("hidden")
//   document.querySelector( `.accordion__content.chartLayout.panel` ).classList.remove("hidden")
  

// Assemble chart traces chart and and render chart traces panel
if (spreadsheet) {
  let index = 1
  while ( index < spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].labels.length ) {
    iwpgvCharts.chart.chartTraces.options[index-1] = ( typeof iwpgvCharts.chart.chartTraces.options[index-1] !== "undefined" )? iwpgvCharts.chart.chartTraces.options[index-1] : {}
    const chartTraceInstance =  new ChartTrace( iwpgvCharts.chart.chartTraces.options[index-1], spreadsheet, index, iwpgvCharts.chart.chartParams.options.sheetId, iwpgvCharts.chart.chartParams.options.chartType, iwpgvObj )
    iwpgvCharts.chart.chartTraces.options[index-1] = chartTraceInstance.options()
    iwpgvCharts.chart.chartTraces.panel.sections[index-1] = chartTraceInstance.sections()
    index++
  }
  panel(iwpgvCharts.chart.chartTraces.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.chartTraces.panel` ).classList.remove( "hidden" )
  document.querySelector( `.accordion__content.chartTraces.panel` ).classList.remove( "hidden" )
} else {
  // console.log("PPPPPP", document.querySelector(`.${iwpgvObj.prefix}__admin .warning`))
  // const node = document.createElement("div")
  // node.classList.add("file-missing")
  // var textNode = document.createTextNode(`${iwpgvCharts.chart.chartParams.options.fileUpload} is missing. Please upload a new file`); 
  // node.appendChild(textNode);  
  displayAdminMessage( `${iwpgvCharts.chart.chartParams.options.fileUpload} is missing. Please upload a new file`, "error", iwpgvObj)
  
  // document.querySelector(`.${iwpgvObj.prefix}__admin .warning`).appendChild( node )
}

  


  // Assemble table chart and panels layout if enableTableChart is true
  // if ( iwpgvCharts.chart.chartParams.options.enableTableChart ) {
  //   const tableChartInstance = new TableChart( iwpgvCharts.chart.tableChart.options, iwpgvObj, "tableChart", "Table Chart" )
  //   iwpgvCharts.chart.tableChart.options = tableChartInstance.options()
  //   iwpgvCharts.chart.tableChart.panel.sections = tableChartInstance.sections()
  //   panel(iwpgvCharts.chart.tableChart.panel, iwpgvObj)
  //   document.querySelector( `.accordion__toggle.tableChart` ).classList.remove("hidden")
  //   document.querySelector( `.accordion__content.tableChart` ).classList.remove("hidden")
  // }


  // Assemble Min/Max table chart options and panel if enableMinMaxTableChart is true
    const minMaxAvgTableChartInstance = new TableChart( iwpgvCharts.chart.minMaxAvgTableChart.options, iwpgvObj, "minMaxAvgTableChart", "" )
    iwpgvCharts.chart.minMaxAvgTableChart.options = minMaxAvgTableChartInstance.options()
    iwpgvCharts.chart.minMaxAvgTableChart.panel.sections = minMaxAvgTableChartInstance.sections()
    panel(iwpgvCharts.chart.minMaxAvgTableChart.panel, iwpgvObj)

    // if ( iwpgvCharts.chart.chartLayout.options.showMinMaxAvgTable ) {
      document.querySelector( `.accordion__toggle.minMaxAvgTableChart.panel` ).classList.remove("hidden")
      document.querySelector( `.accordion__content.minMaxAvgTableChart.panel` ).classList.remove("hidden")
    // }

  

  // document.querySelectorAll(`.accordion__toggle`).forEach (element=> {
  //   element.classList.remove("hidden")
  // })

  // document.querySelectorAll(`.accordion__content`).forEach (element=> {
  //   element.classList.remove("hidden")
  // })


  // Load accordion
  // new Accordion( { collapsed: false } )

  
}




export default renderPanels
