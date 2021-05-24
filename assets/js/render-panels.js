import Accordion from "./Accordion"
import panel from "./panel"
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
import { displayAdminMessage } from "./utilities"



const renderPanels = ( iwpgvCharts, iwpgvObj, spreadsheet) => {


// Assemble chart layout chart and and render chart layout panel
  const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout.options, spreadsheet[iwpgvCharts.chart.chartParams.options.sheetId].data[0], iwpgvCharts.chart.chartParams.options.chartType, iwpgvObj )
  iwpgvCharts.chart.chartLayout.options = chartLayoutInstance.options()
  iwpgvCharts.chart.chartLayout.panel.sections = chartLayoutInstance.sections()
  panel(iwpgvCharts.chart.chartLayout.panel, iwpgvObj)
  document.querySelector( `.accordion__toggle.chartLayout.panel` ).classList.remove("hidden")
  document.querySelector( `.accordion__content.chartLayout.panel` ).classList.remove("hidden")
  

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
  new Accordion( { collapsed: false } )

  
}




export default renderPanels
