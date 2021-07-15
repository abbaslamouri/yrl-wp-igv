import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import capitalize from 'lodash.capitalize'
import ScatterTrace from './ScatterTrace'
import TableTrace from './TableTrace'
import PieTrace from './PieTrace'
import { createPanel, createPanelSections, setSelectFieldOptions, fetchAxisOptions } from "./utilities"


const tracesPanel = function ( chart, spreadsheet, prefix ) {

  document.querySelector(`#${prefix}__admin .tracesAc .accordion`).innerHTML = ""

  const tracesAccordionDiv = document.querySelector( `#${prefix}__admin .traces__Accordion`)
  tracesAccordionDiv.innerHTML = ""

  for (let i = 0;  i < chart.traces.length; i++) {

    // Create a trace panel and add it to traces accordion
    tracesAccordionDiv.appendChild( createPanel(  `traces${i}Ac`, chart.traces[i].name, "" ) )

    // Create level3 accordion inside new trace panel
    const level3AccordionDiv = document.createElement("div")
    level3AccordionDiv.classList.add("accordion", "accordion__level-3", `traces${i}__Accordion`)
    document.querySelector( `#${prefix}__admin .traces${i}Ac .ac-panel `).appendChild( level3AccordionDiv )

    // Create a section container
    const sectionsContainer = document.querySelector( `#${prefix}__admin .traces${i}__Accordion`)

    // Create panel sections
    switch ( chart.traces[i].type ) {
      
      case "scatter":
        createPanelSections( ScatterTrace.sections( chart.traces[i], i, Object.values( spreadsheet[chart.params.sheetId]["labels"] )[i] ), sectionsContainer, `traces${i}`, prefix )
        setSelectFieldOptions ( document.getElementById ( `${prefix}__traces[${i}][xaxis]` ), fetchAxisOptions ( chart.layout, "xaxis", capitalize ) )
        setSelectFieldOptions ( document.getElementById (`${prefix}__traces[${i}][yaxis]` ), fetchAxisOptions (chart.layout, "yaxis", capitalize) )
        break

      case "pie":
        createPanelSections( PieTrace.sections( chart.traces[i], i, Object.values( spreadsheet[chart.params.sheetId]["labels"] )[i] ), sectionsContainer, `traces${i}`, prefix )
        break

      case "table":
        createPanelSections( TableTrace.sections( chart.traces[i], i, Object.values( spreadsheet[chart.params.sheetId]["labels"] )[i] ), sectionsContainer, `traces${i}`, prefix )
        break

    }

    // Create section accordion
    new Accordion( `#${prefix}__admin .traces${i}__Accordion`, { duration: 400 })
    
  }

  // Create traces accordion
  document.querySelector(`#${prefix}__admin .tracesAc`).classList.remove( "hidden" )
  
  new Accordion( tracesAccordionDiv, { duration: 400 } )


}

export default tracesPanel
