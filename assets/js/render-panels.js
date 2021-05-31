// import Accordion from "./Accordion"
import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';
import panel from "./panel"
import ChartBasicOptions from "./ChartBasicOptions"
import ChartTitle from "./ChartTitle"
import ChartLegend from "./ChartLegend"
import HoverLabel from "./HoverLabel"
import ModeBar from "./ModeBar"
import ChartAxis from "./ChartAxis"
import ChartLayout from "./ChartLayout"
import ChartTrace from "./ChartTrace"
import TableChart from "./TableChart"
import { displayAdminMessage } from "./utilities"

const renderPanels = ( chart, spreadsheet, iwpgvObj ) => {

  console.log("CHART", chart)

  // Render layout basic options panel
  const basicOptionsInstance = new ChartBasicOptions( ( chart.chartBasicOptions !== undefined ) ? chart.chartBasicOptions : {}, iwpgvObj )
  chart.chartBasicOptions = basicOptionsInstance.options()
  panel( basicOptionsInstance.sections(), "chartBasicOptionsPanel", iwpgvObj )


// Assemble chart traces chart and and render chart traces panel
if ( spreadsheet ) {

  // Get chart traces panel content div
  const chartTracesContentPanel = document.querySelector( `.${iwpgvObj.prefix}__admin #${iwpgvObj.prefix}__chartOptionsForm .charTracesPanel .ac-panel` )

  // Create new accordion and add accordion to panel
  const accordionDiv = document.createElement( "div" )
  accordionDiv.classList.add( "accordion","accordion__level-2", "chartTraces__Acc" )

  chartTracesContentPanel.appendChild(accordionDiv)

  chart.chartTracesOptions = ( chart.chartTracesOptions !== undefined )? chart.chartTracesOptions : {}
  let index = 1
  while ( index < spreadsheet[chart.chartParams.sheetId].labels.length ) {  
    
    // Create intro p
    const introDiv = document.createElement("div")
    introDiv.classList.add( "ac-text", "intro" )

    // Create accordion panel
    const ac = document.createElement( "div" )
    ac.classList.add( "ac", `chartTraces_${index-1}_Panel` )

    // Create ac header
    const acHeader = document.createElement( "h2" )
    acHeader.classList.add( "ac-header" )

    // Create trigger button
    const acTrigger = document.createElement( "div" )
    acTrigger.classList.add( "ac-trigger" )

    // Create heading title and add to trigger button
    const headingTitle = document.createTextNode( Object.values( spreadsheet[chart.chartParams.sheetId].labels)[index] )
    acTrigger.appendChild( headingTitle )

    // Add trigger button to header
    acHeader.appendChild( acTrigger )

    // Add header to ac
    ac.appendChild( acHeader )

    // Add ac to accordion
    accordionDiv.appendChild( ac )

    // Create accordion content
    const acPanel = document.createElement( "div" )
    acPanel.classList.add( "ac-panel" )

    // Add content to ac
    ac.appendChild(acPanel)

    // Add p tag to content
    acPanel.appendChild(introDiv)

    chart.chartTracesOptions[index-1] = ( chart.chartTracesOptions[index-1] !== undefined )? chart.chartTracesOptions[index-1] : {}
    const chartTraceInstance =  new ChartTrace( chart.chartTracesOptions[index-1], spreadsheet, index, chart.chartParams.sheetId, chart.chartParams.chartType, iwpgvObj )
    chart.chartTracesOptions[index-1] = chartTraceInstance.options()
    // chart.chartTraces.sections[index-1] = chartTraceInstance.sections()
    panel(chartTraceInstance.sections(), `chartTraces_${index-1}_Panel`, iwpgvObj)
    index++
  }

  // Load accordion
  new Accordion( `.${iwpgvObj.prefix}__admin .chartTraces__Acc`, { duration: 400 } )

  
} else {
  
  displayAdminMessage( `${chart.chartParams.options.fileUpload} is missing. Please upload a new file`, "error", iwpgvObj)
  
}

console.log("CHART1", {...chart})

return


  // document.querySelector( `.accordion__toggle.basicOptions.panel` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.basicOptions.panel` ).classList.remove("hidden")

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
