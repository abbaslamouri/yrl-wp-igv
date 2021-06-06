// import Accordion from "./Accordion"
import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';
import panel from "./field-groups"
import BasicOptions from "./BasicOptions"
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Modebar from "./Modebar"
import ChartAxis from "./ChartAxis"
// import ChartLayout from "./ChartLayout"
import Trace from "./Trace"
import TableChart from "./TableChart"
import { displayAdminMessage, createAccordionPanel, fetchformGroup } from "./utilities"

const renderPanels = ( chart, spreadsheet, prefix ) => {

  // // Render layout basic options panel
  // const basicOptionsInstance = new BasicOptions( ( chart.basicOptions !== undefined ) ? chart.basicOptions : {}, prefix )
  // chart.basicOptions = basicOptionsInstance.options()
  // panel( basicOptionsInstance.sections(), "basicOptionsPanel", prefix )

  // // Render layout chart title panel
  // const titleInstance = new Title( ( chart.title !== undefined ) ? chart.title : {}, prefix )
  // chart.title = titleInstance.options()
  // panel( titleInstance.sections(), "titlePanel", prefix )

  // // Render layout chart legend panel
  // const legendInstance = new Legend( ( chart.legend !== undefined ) ? chart.legend : {}, prefix )
  // chart.legend = legendInstance.options()
  // panel( legendInstance.sections(), "legendPanel", prefix )

  // // Render layout chart hoverlabel panel
  // const hoverlabelInstance = new Hoverlabel( ( chart.hoverlabel !== undefined ) ? chart.hoverlabel : {}, prefix )
  // chart.hoverlabel = hoverlabelInstance.options()
  // panel( hoverlabelInstance.sections(), "hoverlabelPanel", prefix )

  // // Render layout chart modebar panel
  // const modebarInstance = new Modebar( ( chart.modebar !== undefined ) ? chart.modebar : {}, prefix )
  // chart.modebar = modebarInstance.options()
  // panel( modebarInstance.sections(), "modebarPanel", prefix )

  // // Render layout bottom axis options panel
  // const bottomAxisInstance = new ChartAxis( ( chart.bottomAxis !== undefined ) ? chart.bottomAxis : {}, chart.fileUpload.chartType, "bottomAxis", prefix )
  // chart.bottomAxis = bottomAxisInstance.options()
  // panel( bottomAxisInstance.sections(), "bottomAxisPanel", prefix )

  // // Render layout bottom axis options panel
  // const topAxisInstance = new ChartAxis( ( chart.topAxis !== undefined ) ? chart.topAxis : {}, chart.fileUpload.chartType, "topAxis", prefix )
  // chart.topAxis = topAxisInstance.options()
  // panel( topAxisInstance.sections(), "topAxisPanel", prefix )

  // // Render layout bottom axis options panel
  // const leftAxisInstance = new ChartAxis( ( chart.leftAxis !== undefined ) ? chart.leftAxis : {}, chart.fileUpload.chartType, "leftAxis", prefix )
  // chart.leftAxis = leftAxisInstance.options()
  // panel( leftAxisInstance.sections(), "leftAxisPanel", prefix )

  // // Render layout bottom axis options panel
  // const rightAxisInstance = new ChartAxis( ( chart.rightAxis !== undefined ) ? chart.rightAxis : {}, chart.fileUpload.chartType, "rightAxis", prefix )
  // chart.rightAxis = rightAxisInstance.options()
  // panel( rightAxisInstance.sections(), "rightAxisPanel", prefix )

  // chart.layout = { ...chart.basicOptions }
  // chart.layout.showlegend = chart.legend.showlegend
  // chart.layout.hovermode = chart.hoverlabel.hovermode
  // chart.layout.title = chart.title.title
  // chart.layout.legend = chart.legend.legend
  // chart.layout.hoverlabel = chart.hoverlabel.hoverlabel
  // chart.layout.modebar = chart.modebar.modebar
  // chart.layout.xaxis = chart.bottomAxis
  // chart.layout.xaxis2 = chart.topAxis
  // chart.layout.yaxis = chart.leftAxis
  // chart.layout.yaxis2 = chart.rightAxis
  // chart.config = {
  //   responsive: chart.basicOptions.responsive,
  //   staticPlot: chart.basicOptions.staticPlot,
  //   displayModeBar: chart.modebar.displayModeBar,
  //   displaylogo: chart.modebar.displaylogo,
  // }
  // chart.layout = {}

// Assemble chart traces chart and and render chart traces panel
if ( spreadsheet ) {
  console.log("XXXXX", chart.fileUpload)

  // Get chart traces panel content div
  const tracesAccordion = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesPanel .ac-panel .traces__Acc` )

  // chart.traces = ( chart.traces !== undefined )? chart.traces : {}
  let index = 1
  while ( index < spreadsheet[chart.fileUpload.sheetId].labels.length ) {  

    // Add trace panel to accordion
    tracesAccordion.appendChild( createAccordionPanel(  `traces${index-1}Panel`, Object.values( spreadsheet[chart.fileUpload.sheetId].labels)[index], `Here you can modify the options for ${Object.values( spreadsheet[chart.fileUpload.sheetId].labels)[index]} ` ) )


    
    chart.traces[index-1] = {
      x : spreadsheet[chart.fileUpload.sheetId].data[0],
      y : spreadsheet[chart.fileUpload.sheetId].data[index],
      name : Object.values(spreadsheet[chart.fileUpload.sheetId]["labels"])[index],
      visible : true,
    }

    chart.traces[index-1] = ( chart.traces[index-1] !== undefined )? chart.traces[index-1] : {}
    const chartTraceInstance =  new Trace( chart.traces[index-1], index )
    // chart.traces[index-1] = chartTraceInstance.options()
    // chart.traces.sections[index-1] = chartTraceInstance.sections()
    // panel(chartTraceInstance.sections(), `traces_${index-1}_Panel`, prefix)

    // Create trace sections acordion div
    const traceSectionsAccordionDiv = document.createElement("div")
    traceSectionsAccordionDiv.classList.add("accordion", "accordion__level-3", `trace${index-1}__Acc`)

    // Get chart traces panel content div
    const traceAccordion = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesPanel .ac-panel .traces__Acc .traces${index-1}Panel .ac-panel` )

    traceAccordion.appendChild( traceSectionsAccordionDiv )
    // Get chart traces panel content div
    // const traceSectionsPanel = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesPanel .ac-panel .traces__Acc .traces${index-1}Panel .ac-panel` )

    const sections = chartTraceInstance.sections()
    for ( const section in sections ) {
      // console.log("PROP",section, sections)
      // Add trace panel to accordion
      traceSectionsAccordionDiv.appendChild( createAccordionPanel(  `traces${index-1}${section}Panel`, sections[section].title, sections[section].intro ) )
      
      const acPanel = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesPanel .ac-panel .traces__Acc .traces${index-1}Panel .ac-panel .traces${index-1}${section}Panel .ac-panel` )

      for (const fieldRow in sections[section].fieldGroups) {
      
        const row = sections[section].fieldGroups[fieldRow];
  
        //Create field group and add apprpriate css classes
        const fieldGroup = document.createElement( "div" )
        if (row.cssClasses) {
          for (const cssClass in row.cssClasses) {
            fieldGroup.classList.add(row.cssClasses[cssClass])
          }
        }
        
        // Loop through fields
        for (const el in row.inputFields) {
  
          const formGroup = fetchformGroup( row.inputFields[el], prefix )
          
          fieldGroup.appendChild( formGroup )
          // console.log(fieldGroup)
  
          // Add field group to content
          // if (Object.keys(panelSections).length > 1) {
            acPanel.appendChild( fieldGroup )
          // } else {
            // panelContent.appendChild( fieldGroup )
          // }
  
        }
  
      }


    }

    new Accordion( `.${prefix}__admin .trace${index-1}__Acc`, { duration: 400 } )

    index++
  }

  // Load accordion
  new Accordion( `.${prefix}__admin .traces__Acc`, { duration: 400 } )

  
} else {
  
  displayAdminMessage( `${chart.fileUpload.options.fileUpload} is missing. Please upload a new file`, "error", prefix)
  
}


  // document.querySelector( `.accordion__toggle.basicOptions.panel` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.basicOptions.panel` ).classList.remove("hidden")

  // // Render chart title panel
  // const chartTitleInstance = new ChartTitle( chart.chartTitle.options, prefix )
  // chart.chartTitle.options = chartTitleInstance.options()
  // chart.chartTitle.panel.sections = chartTitleInstance.sections()
  // panel(chart.chartTitle.panel, prefix)
  // document.querySelector( `.accordion__toggle.chartTitle.panel` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.chartTitle.panel` ).classList.remove("hidden")

  // // Render chart legend panel
  // const chartLegendInstance = new ChartLegend( chart.chartLegend.options, prefix )
  // chart.chartLegend.options = chartLegendInstance.options()
  // chart.chartLegend.panel.sections = chartLegendInstance.sections()
  // panel(chart.chartLegend.panel, prefix)
  // document.querySelector( `.accordion__toggle.chartLegend.panel` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.chartLegend.panel` ).classList.remove("hidden")

  // // Render chart legend panel
  // const hoverLabelInstance = new HoverLabel( chart.hoverLabel.options, prefix )
  // chart.hoverLabel.options = hoverLabelInstance.options()
  // chart.hoverLabel.panel.sections = hoverLabelInstance.sections()
  // panel(chart.hoverLabel.panel, prefix)
  // document.querySelector( `.accordion__toggle.hoverLabel.panel` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.hoverLabel.panel` ).classList.remove("hidden")

  // // Render mode bar panel
  // const modeBarlInstance = new ModeBar( chart.modeBar.options, prefix )
  // chart.modeBar.options = modeBarlInstance.options()
  // chart.modeBar.panel.sections = modeBarlInstance.sections()
  // panel(chart.modeBar.panel, prefix)
  // document.querySelector( `.accordion__toggle.modeBar.panel` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.modeBar.panel` ).classList.remove("hidden")

  // // Render bottom axis
  // const bottomAxisInstance = new ChartAxis( chart.bottomAxis.options, chart.fileUpload.options.chartType, "bottomAxis", prefix )
  // chart.bottomAxis.options = bottomAxisInstance.options()
  // chart.bottomAxis.panel.sections = bottomAxisInstance.sections()
  // panel(chart.bottomAxis.panel, prefix)
  // document.querySelector( `.accordion__toggle.bottomAxis.panel` ).classList.remove("hidden")
  // document.querySelector( `.accordion__content.bottomAxis.panel` ).classList.remove("hidden")

  //  // Render top axis
  //  const topAxisInstance = new ChartAxis( chart.topAxis.options, chart.fileUpload.options.chartType, "topAxis", prefix )
  //  chart.topAxis.options = topAxisInstance.options()
  //  chart.topAxis.panel.sections = topAxisInstance.sections()
  //  panel(chart.topAxis.panel, prefix)
  //  document.querySelector( `.accordion__toggle.topAxis.panel` ).classList.remove("hidden")
  //  document.querySelector( `.accordion__content.topAxis.panel` ).classList.remove("hidden")

  //   // Render left axis
  //   const leftAxisInstance = new ChartAxis( chart.leftAxis.options, chart.fileUpload.options.chartType, "leftAxis", prefix )
  //   chart.leftAxis.options = leftAxisInstance.options()
  //   chart.leftAxis.panel.sections = leftAxisInstance.sections()
  //   panel(chart.leftAxis.panel, prefix)
  //   document.querySelector( `.accordion__toggle.leftAxis.panel` ).classList.remove("hidden")
  //   document.querySelector( `.accordion__content.leftAxis.panel` ).classList.remove("hidden")

  //    // Render right axis
  //  const rightAxisInstance = new ChartAxis( chart.rightAxis.options, chart.fileUpload.options.chartType, "rightAxis", prefix )
  //  chart.rightAxis.options = rightAxisInstance.options()
  //  chart.rightAxis.panel.sections = rightAxisInstance.sections()
  //  panel(chart.rightAxis.panel, prefix)
  //  document.querySelector( `.accordion__toggle.rightAxis.panel` ).classList.remove("hidden")
  //  document.querySelector( `.accordion__content.rightAxis.panel` ).classList.remove("hidden")


// // Assemble chart layout chart and and render chart layout panel
//   const chartLayoutInstance = new ChartLayout( iwpgvCharts.chart.chartLayout.options, spreadsheet[iwpgvCharts.chart.fileUpload.options.sheetId].data[0], iwpgvCharts.chart.fileUpload.options.chartType, prefix )
//   iwpgvCharts.chart.chartLayout.options = chartLayoutInstance.options()
//   iwpgvCharts.chart.chartLayout.panel.sections = chartLayoutInstance.sections()
//   panel(iwpgvCharts.chart.chartLayout.panel, prefix)
//   document.querySelector( `.accordion__toggle.chartLayout.panel` ).classList.remove("hidden")
//   document.querySelector( `.accordion__content.chartLayout.panel` ).classList.remove("hidden")
  

// Assemble chart traces chart and and render chart traces panel
// if (spreadsheet) {
//   let index = 1
//   while ( index < spreadsheet[iwpgvCharts.chart.fileUpload.options.sheetId].labels.length ) {
//     iwpgvCharts.chart.traces.options[index-1] = ( typeof iwpgvCharts.chart.traces.options[index-1] !== "undefined" )? iwpgvCharts.chart.traces.options[index-1] : {}
//     const chartTraceInstance =  new ChartTrace( iwpgvCharts.chart.traces.options[index-1], spreadsheet, index, iwpgvCharts.chart.fileUpload.options.sheetId, iwpgvCharts.chart.fileUpload.options.chartType, prefix )
//     iwpgvCharts.chart.traces.options[index-1] = chartTraceInstance.options()
//     iwpgvCharts.chart.traces.panel.sections[index-1] = chartTraceInstance.sections()
//     index++
//   }
//   panel(iwpgvCharts.chart.traces.panel, prefix)
//   document.querySelector( `.accordion__toggle.chartTraces.panel` ).classList.remove( "hidden" )
//   document.querySelector( `.accordion__content.chartTraces.panel` ).classList.remove( "hidden" )
// } else {
//   // console.log("PPPPPP", document.querySelector(`.${prefix}__admin .warning`))
//   // const node = document.createElement("div")
//   // node.classList.add("file-missing")
//   // var textNode = document.createTextNode(`${iwpgvCharts.chart.fileUpload.options.fileUpload} is missing. Please upload a new file`); 
//   // node.appendChild(textNode);  
//   displayAdminMessage( `${iwpgvCharts.chart.fileUpload.options.fileUpload} is missing. Please upload a new file`, "error", prefix)
  
//   // document.querySelector(`.${prefix}__admin .warning`).appendChild( node )
// }

  


  // Assemble table chart and panels layout if enableTableChart is true
  // if ( iwpgvCharts.chart.fileUpload.options.enableTableChart ) {
  //   const tableChartInstance = new TableChart( iwpgvCharts.chart.tableChart.options, prefix, "tableChart", "Table Chart" )
  //   iwpgvCharts.chart.tableChart.options = tableChartInstance.options()
  //   iwpgvCharts.chart.tableChart.panel.sections = tableChartInstance.sections()
  //   panel(iwpgvCharts.chart.tableChart.panel, prefix)
  //   document.querySelector( `.accordion__toggle.tableChart` ).classList.remove("hidden")
  //   document.querySelector( `.accordion__content.tableChart` ).classList.remove("hidden")
  // }


  // Assemble Min/Max table chart options and panel if enableMinMaxTableChart is true
    // const minMaxAvgTableChartInstance = new TableChart( iwpgvCharts.chart.minMaxAvgTableChart.options, prefix, "minMaxAvgTableChart", "" )
    // iwpgvCharts.chart.minMaxAvgTableChart.options = minMaxAvgTableChartInstance.options()
    // iwpgvCharts.chart.minMaxAvgTableChart.panel.sections = minMaxAvgTableChartInstance.sections()
    // panel(iwpgvCharts.chart.minMaxAvgTableChart.panel, prefix)

    // // if ( iwpgvCharts.chart.chartLayout.options.showMinMaxAvgTable ) {
    //   document.querySelector( `.accordion__toggle.minMaxAvgTableChart.panel` ).classList.remove("hidden")
    //   document.querySelector( `.accordion__content.minMaxAvgTableChart.panel` ).classList.remove("hidden")
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
