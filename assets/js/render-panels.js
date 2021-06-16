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
import Annotation from "./Annotation"
import TableChart from "./TableChart"
import { displayAdminMessage, createPanel, createPanelSections } from "./utilities"

const renderPanels = ( chart, spreadsheet, prefix ) => {

  console.log("PPPPP", chart)

  try {

    // Bail if there are no chart traces, a file or a sheet id
    if (! spreadsheet ) throw new Error(  `S[preadsheet missing]` )

    const tracesAccordionDiv = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion`)
    tracesAccordionDiv.innerHTML = ""

    for (let i = 0;  i < chart.traces.length; i++) {

       // Create a trace panel and add it to traces accordion
      tracesAccordionDiv.appendChild( createPanel(  `traces${i}Ac`, chart.traces[i].name, `` ) )

      // Create level3 accordion inside new trace panel
      const level3AccordionDiv = document.createElement("div")
      level3AccordionDiv.classList.add("accordion", "accordion__level-3", `traces${i}__Accordion`)
      document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .traces${i}Ac .ac-panel `).appendChild( level3AccordionDiv )

    
      const sectionsContainer = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .ac-panel .traces${i}__Accordion`)

      // chart.traces[index-1] = ( chart.traces[index-1] !== undefined )? chart.traces[index-1] : {}
      const traceInstance = new Trace( chart.traces[i], i )
      // chart.traces[index-1] = traceInstance.options()
      createPanelSections( traceInstance.sections(), sectionsContainer, `traces${i}`, prefix )
      new Accordion( `.${prefix}__admin .traces${i}__Accordion`, { duration: 400 })

    }

     // Load accordion
    new Accordion( tracesAccordionDiv, { duration: 400 } )
    

   

  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } finally {
    
     // Hide warning and show spinner
    //  toggleElementById( `${iwpgvObj.prefix}__spinner` );
    //  showElementById( `${iwpgvObj.prefix}__warning`);

  }

  // // Render layout basic options panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .layoutAc .ac-panel` ).innerHTML = ""
  // const layoutInstance = new BasicOptions( chart.layout)
  // chart.layout = {...chart.layout, ...layoutInstance.options()}
  // createPanelSections( layoutInstance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .layoutAc .ac-panel` ), "layout", prefix )

  // // Render layout title panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .titleAc .ac-panel` ).innerHTML = ""
  // const titleInstance = new Title( chart.layout)
  // chart.layout.title = titleInstance.options().title
  // createPanelSections( titleInstance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .titleAc .ac-panel` ), "title", prefix )

  // // Render layout legend panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .legendAc .ac-panel` ).innerHTML = ""
  // const legendInstance = new Legend( chart.layout)
  // chart.layout.showlegend = legendInstance.options().showlegend
  // chart.layout.legend = legendInstance.options().legend
  // createPanelSections( legendInstance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .legendAc .ac-panel` ), "legend", prefix )

  // // Render layout hoverlabel panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .hoverlabelAc .ac-panel` ).innerHTML = ""
  // const hoverlabelInstance = new Hoverlabel( chart.layout)
  // chart.layout.hovermode = hoverlabelInstance.options().hovermode
  // chart.layout.hoverlabel = hoverlabelInstance.options().hoverlabel
  // createPanelSections( hoverlabelInstance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .hoverlabelAc .ac-panel` ), "hoverlabel", prefix )

  // // Render modebar panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .modebarAc .ac-panel` ).innerHTML = ""
  // const modebarInstance = new Modebar( chart.layout, chart.config)
  // chart.config.displayModeBar = modebarInstance.options().displayModeBar
  // chart.config.displaylogo = modebarInstance.options().displaylogo
  // chart.layout.modebar = modebarInstance.options().modebar
  // createPanelSections( modebarInstance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .modebarAc .ac-panel` ), "modebar", prefix )


  // // Render layout xaxis panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .xaxisAc .ac-panel .xaxis__Accordion` ).innerHTML = ""
  // const xAxisInstance = new ChartAxis( chart.layout, chart.fileUpload.chartType, "xaxis", "bottom", null, null, false )
  // chart.layout.xaxis = xAxisInstance.options()
  // createPanelSections( xAxisInstance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .xaxisAc .ac-panel .xaxis__Accordion` ), "xaxis", prefix )
  // new Accordion( `.${prefix}__admin .xaxis__Accordion`, { duration: 400 })

  // // Render layout xaxise panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .xaxis2Ac .ac-panel .xaxis2__Accordion` ).innerHTML = ""
  // const xAxis2Instance = new ChartAxis( chart.layout, chart.fileUpload.chartType, "xaxis2", "top", "x", "x", false )
  // chart.layout.xaxis2 = xAxis2Instance.options()
  // createPanelSections( xAxis2Instance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .xaxis2Ac .ac-panel .xaxis2__Accordion` ), "xaxis2", prefix )
  // new Accordion( `.${prefix}__admin .xaxis2__Accordion`, { duration: 400 })

  // // Render layout yaxis panel
  // document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .yaxisAc .ac-panel .yaxis__Accordion` ).innerHTML = ""
  // const yAxisInstance = new ChartAxis( chart.layout, chart.fileUpload.chartType, "yaxis", "left", null, null, false )
  // chart.layout.yaxis = yAxisInstance.options()
  // createPanelSections( yAxisInstance.sections(), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .yaxisAc .ac-panel .yaxis__Accordion` ), "yaxis", prefix )
  // new Accordion( `.${prefix}__admin .yaxis__Accordion`, { duration: 400 })
  



  // Render layout bottom axis options panel
  // const bottomAxisInstance = new ChartAxis( ( chart.bottomAxis !== undefined ) ? chart.bottomAxis : {}, chart.fileUpload.chartType, "bottomAxis", prefix )
  // chart.bottomAxis = bottomAxisInstance.options()
  // panel( bottomAxisInstance.sections(), "bottomAxisPanel", prefix )


  



  

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

  // Render layout xaxis panel
  // optionId = "xaxis"
  // sectionsContainer = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .${optionId}Ac .ac-panel .${optionId}__Accordion` )
  // sectionsContainer.innerHTML = ""
  // const xAxisInstance = new ChartAxis( chart[optionId], chart.fileUpload.chartType, optionId, prefix )
  // const xaxisOptions = xAxisInstance.options()
  // createPanelSections( xAxisInstance.sections(), sectionsContainer, optionId, prefix )
  // new Accordion( `.${prefix}__admin .${optionId}__Accordion`, { duration: 400 })

  console.log("MMMMMM", chart)
  return

// Assemble chart traces chart and and render chart traces panel
if ( spreadsheet ) {

  const tracesAccordionDiv = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion`)
  tracesAccordionDiv.innerHTML = ""

  let index = 1
  while ( index < spreadsheet[chart.fileUpload.sheetId].labels.length ) {

    // optionId = `traces${index-1}`

    // Create a trace panel and add it to traces accordion
    tracesAccordionDiv.appendChild( createPanel(  `traces${index-1}Ac`, Object.values( spreadsheet[chart.fileUpload.sheetId].labels)[index], `Here you can modify the options for ${Object.values( spreadsheet[chart.fileUpload.sheetId].labels)[index]} ` ) )

    // Create level3 accordion inside new trace panel
    const level3AccordionDiv = document.createElement("div")
    level3AccordionDiv.classList.add("accordion", "accordion__level-3", `traces${index-1}__Accordion`)
    document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .traces${index-1}Ac .ac-panel `).appendChild( level3AccordionDiv )

  
    const sectionsContainer = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .ac-panel .traces${index-1}__Accordion`)

    chart.traces[index-1] = ( chart.traces[index-1] !== undefined )? chart.traces[index-1] : {}
    const traceInstance = new Trace( chart.traces[index-1], spreadsheet, index, chart.fileUpload.sheetId, chart.fileUpload.chartType )
    chart.traces[index-1] = traceInstance.options()
    createPanelSections( traceInstance.sections(), sectionsContainer, `traces${index-1}`, prefix )
    new Accordion( `.${prefix}__admin .traces${index-1}__Accordion`, { duration: 400 })

    index++


  }


  // if (chart.layout.annotations.length) {
  //   console.log(chart.layout.annotations)
  // }

  


  


  // Load accordion
  new Accordion( tracesAccordionDiv, { duration: 400 } )

  // Add hover event to tooltips
  document.querySelectorAll(`.${prefix}__admin #${prefix}__chartOptionsForm .form-group__tooltip`).forEach( (el) => {
    
    el.addEventListener("mouseenter", function (event) {

      // Reset popup div
      document.querySelector(`.${prefix}__admin .hint-popup`).innerHTML = ""


      // const hint = el.querySelector( ".form-group__tooltip-hint")
      // console.log("NEW", el.getBoundingClientRect())
      
      // Create tooltip popup and add it to hint popup
      const hintDiv = document.createElement("div")
      hintDiv.classList.add ("form-group__tooltip-popup")
      hintDiv.innerHTML = el.querySelector( ".form-group__tooltip-hint").innerHTML
      document.querySelector(`.${prefix}__admin .hint-popup`).appendChild(hintDiv)

      // const questionMark = el.querySelector(".form-group__tooltip-question-mark")
      // Get question mark coordinates and set hint popup position and visibility style
      document.querySelector(`.${prefix}__admin .hint-popup`).style.bottom = `${window.innerHeight - el.querySelector(".form-group__tooltip-question-mark").getBoundingClientRect().y - 40}px`
      document.querySelector(`.${prefix}__admin .hint-popup`).style.right = `${window.innerWidth - el.querySelector(".form-group__tooltip-question-mark").getBoundingClientRect().x - 20}px`
      document.querySelector(`.${prefix}__admin .hint-popup`).style.opacity = "1"
      document.querySelector(`.${prefix}__admin .hint-popup`).style.visibility = "visible"
      console.log(window.innerWidth, window.innerHeight )

      // Add event listener to hide hint popup on mouseleave
      el.addEventListener("mouseleave", async function (event) {
        // document.querySelector(`.${prefix}__admin .hint-popup`).style.bottom = "0"
        document.querySelector(`.${prefix}__admin .hint-popup`).style.opacity = "0"
        document.querySelector(`.${prefix}__admin .hint-popup`).style.visibility = "hidden"
        document.querySelector(`.${prefix}__admin .hint-popup`).innerHTML = ""
      })

    })

  })


 

  
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
