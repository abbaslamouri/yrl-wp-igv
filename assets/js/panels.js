import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import BasicOptions from './BasicOptions'
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Grid from "./Grid"
import Modebar from "./Modebar"
import ChartAxis from "./ChartAxis"
import ScatterTrace from './ScatterTrace'
import PieTrace from './PieTrace'
import capitalize from 'lodash.capitalize'
import { createPanel, createPanelSections } from "./utilities"


const panels = async function (chart, spreadsheet, prefix) {

  let deleteBtn = null


  document.querySelector(`#${prefix}__admin .tracesAc .ac-panel .accordion`).innerHTML = ""

  const tracesAccordionDiv = document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion`)
  tracesAccordionDiv.innerHTML = ""

  for (let i = 0;  i < chart.traces.length; i++) {

    // Create a trace panel and add it to traces accordion
    tracesAccordionDiv.appendChild( createPanel(  `traces${i}Ac`, chart.traces[i].name, "" ) )

    // Create level3 accordion inside new trace panel
    const level3AccordionDiv = document.createElement("div")
    level3AccordionDiv.classList.add("accordion", "accordion__level-3", `traces${i}__Accordion`)
    document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .traces${i}Ac .ac-panel `).appendChild( level3AccordionDiv )

    // Create a section container
    const sectionsContainer = document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .ac-panel .traces${i}__Accordion`)

    // Create panel sections
    switch ( chart.params.chartType ) {
      
      case "scatter":
        createPanelSections( ScatterTrace.sections( chart.traces[i], i, Object.values(spreadsheet[chart.params.sheetId]["labels"])[i], chart.params.chartType ), sectionsContainer, `traces${i}`, prefix )
        break

      case "pie":
        console.log("PIE")
        createPanelSections( PieTrace.sections( chart.traces[i], i, Object.values(spreadsheet[chart.params.sheetId]["labels"])[i], chart.params.chartType ), sectionsContainer, `traces${i}`, prefix )
        break
    }

    // Create section accordion
    new Accordion( `#${prefix}__admin .traces${i}__Accordion`, { duration: 400 })
    
  }

  // Create traces accordion
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .tracesAc`).classList.remove( "hidden" )
  
  new Accordion( tracesAccordionDiv, { duration: 400 } )


  document.querySelector(`#${prefix}__admin .basicOptionsAc .ac-panel`).innerHTML = ""
  createPanelSections( BasicOptions.sections( chart.layout, chart.config ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .basicOptionsAc .ac-panel` ), "basicOptions", prefix  )
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .basicOptionsAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .titleAc .ac-panel`).innerHTML = ""
  createPanelSections( Title.sections( chart.layout ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .titleAc .ac-panel` ), "title", prefix  )
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .titleAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .legendAc .ac-panel`).innerHTML = ""
  createPanelSections( Legend.sections( chart.layout ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .legendAc .ac-panel` ), "legend", prefix  )
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .legendAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .hoverlabelAc .ac-panel`).innerHTML = ""
  createPanelSections( Hoverlabel.sections( chart.layout ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .hoverlabelAc .ac-panel` ), "hoverlabel", prefix  )
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .hoverlabelAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .gridAc .ac-panel`).innerHTML = ""
  createPanelSections( Grid.sections( chart.layout ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .gridAc .ac-panel` ), "grid", prefix  )
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .gridAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .modebarAc .ac-panel `).innerHTML = ""
  createPanelSections( Modebar.sections( chart.layout, chart.config ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .modebarAc .ac-panel` ), "modebar", prefix  )
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .modebarAc`).classList.remove( "hidden" )





  const xaxesAccordionDiv = document.querySelector( `#${prefix}__admin .xaxes__Accordion`)

  // Reset xaxes panel accordion
  xaxesAccordionDiv.innerHTML = ""

// Fetch an array of all xaxes
  const xaxes = Object.keys(chart.layout).filter( ( prop ) => prop.includes("xaxis"))

  const axisType = "xaxis"

  for (let i = 0;  i < xaxes.length; i++) {

    const axisId = ! i ? "xaxis" : xaxes[i]




   
    // Create a annotation panel and add it to xaxes accordion
    xaxesAccordionDiv.appendChild( createPanel(  `${axisId}Ac`, capitalize(axisId), `Here you can modify the options for a new ${axisId}` ) )

    // Create level3 accordion inside new annotation panel
    const level3AccordionDiv = document.createElement("div")
    level3AccordionDiv.classList.add("accordion", "accordion__level-3", `${axisId}__Accordion`)
    document.querySelector( `#${prefix}__admin .${axisId}Ac .ac-panel `).appendChild( level3AccordionDiv )

    const sectionsContainer = document.querySelector( `#${prefix}__admin .${axisId}__Accordion`)
  
    createPanelSections( ChartAxis.sections( chart.layout, axisId, chart.layout[axisId].side, chart.layout[axisId].overlaying, chart.layout[axisId].title.text, chart.layout[axisId].matches  ), sectionsContainer, axisId, prefix )
  
    if (axisId !== "xaxis" && axisId !== "yaxis") {

      // Create Delete annotation button
      deleteBtn = document.createElement("div")
      deleteBtn.classList.add(`.${prefix}__deleteAxis`, "button", "btn", "btn-danger")
      deleteBtn.id = `.${prefix}__layout[${axisType}${i}]`
      const buttonText = document.createTextNode( "Delete Axis" )
      deleteBtn.appendChild(buttonText)
      document.querySelector( `#${prefix}__admin .${axisId}Ac .ac-panel `).appendChild( deleteBtn )

    }
    
    
    
    new Accordion( `#${prefix}__admin .${axisId}__Accordion`, { duration: 400 })

  }

  // Create traces accordion
  document.querySelector(`#${prefix}__admin .main__Accordion .xaxesAc`).classList.remove( "hidden" )

  
  // new Accordion( xaxesAccordionDiv, { duration: 400 } )
  


  // document.querySelector(`#${prefix}__admin .xaxisAc .ac-panel .accordion`).innerHTML = ""
  // createPanelSections( ChartAxis.sections( chart.layout, "xaxis", "bottom", null, "Wavelength ( &#181;m )", null), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .xaxisAc .ac-panel .xaxis__Accordion` ), "xaxis", prefix )
  // document.getElementById(`${prefix}__layout[xaxis][type]`).value = chart.layout.xaxis.type
  // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxisAc`).classList.remove( "hidden" )
  // new Accordion( `#${prefix}__admin .xaxis__Accordion`, { duration: 400 })

  // document.querySelector(`#${prefix}__admin .xaxis2Ac .ac-panel .accordion`).innerHTML = ""
  // createPanelSections( ChartAxis.sections( chart.layout, "xaxis2", "top", "x", "Wavelength ( &#181;m )", "x"  ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .xaxis2Ac .ac-panel .xaxis2__Accordion` ), "xaxis2", prefix )
  // document.getElementById(`${prefix}__layout[xaxis2][type]`).value = chart.layout.xaxis2.type
  // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxis2Ac`).classList.remove( "hidden" )
  // new Accordion( `#${prefix}__admin .xaxis2__Accordion`, { duration: 400 })

  // document.querySelector(`#${prefix}__admin .yaxisAc .ac-panel .accordion`).innerHTML = ""
  // createPanelSections( ChartAxis.sections( chart.layout, "yaxis", "left", null, "Transmittance ( % )", null ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .yaxisAc .ac-panel .yaxis__Accordion` ), "yaxis", prefix )
  // document.getElementById(`${prefix}__layout[yaxis][type]`).value = chart.layout.yaxis.type
  // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxisAc`).classList.remove( "hidden" )
  // new Accordion( `#${prefix}__admin .yaxis__Accordion`, { duration: 400 })

  // document.querySelector(`#${prefix}__admin .yaxis2Ac .ac-panel .accordion`).innerHTML = ""
  // createPanelSections( ChartAxis.sections( chart.layout, "yaxis2", "right", "y", "Reflectance ( % )", "y"  ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .yaxis2Ac .ac-panel .yaxis2__Accordion` ), "yaxis2", prefix )
  // document.getElementById(`${prefix}__layout[yaxis2][type]`).value = chart.layout.yaxis2.type
  // document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxis2Ac`).classList.remove( "hidden" )
  // new Accordion( `#${prefix}__admin .yaxis2__Accordion`, { duration: 400 })

  document.querySelector(`#${prefix}__admin .annotationsAc .ac-panel .accordion`).innerHTML = ""
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .annotationsAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .minMaxAvgTableAc .ac-panel`).innerHTML = ""


}

export default panels;
