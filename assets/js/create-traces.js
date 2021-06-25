import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import Trace from './Trace'
import { createPanel, createPanelSections } from "./utilities"


const traces = async function (chart, spreadsheet, prefix) {

  

    const tracesAccordionDiv = document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion`)
    tracesAccordionDiv.innerHTML = ""

    
    for (let i = 0;  i < spreadsheet[chart.fileUpload.sheetId].data.length - 1; i++) {

      // Traces options
      if (chart.traces[i] === undefined) {
        chart.traces[i] = Trace.defaultOptions( i )
        chart.traces[i].name = Object.values(spreadsheet[chart.fileUpload.sheetId]["labels"])[i+1]
        chart.traces[i].x = spreadsheet[chart.fileUpload.sheetId].data[0]
        chart.traces[i].y = spreadsheet[chart.fileUpload.sheetId].data[i+1]
      }

      // Create a trace panel and add it to traces accordion
      tracesAccordionDiv.appendChild( createPanel(  `traces${i}Ac`, chart.traces[i].name, "" ) )

      // Create level3 accordion inside new trace panel
      const level3AccordionDiv = document.createElement("div")
      level3AccordionDiv.classList.add("accordion", "accordion__level-3", `traces${i}__Accordion`)
      document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .traces${i}Ac .ac-panel `).appendChild( level3AccordionDiv )

      // Create a section container
      const sectionsContainer = document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .ac-panel .traces${i}__Accordion`)

      // Create panel sections
      createPanelSections( Trace.sections(chart.traces[i], i, Object.values(spreadsheet[chart.fileUpload.sheetId]["labels"])[i]), sectionsContainer, `traces${i}`, prefix )

      // Create section accordion
      new Accordion( `#${prefix}__admin .traces${i}__Accordion`, { duration: 400 })
      
    }

    // Create traces accordion
    document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .tracesAc`).classList.remove( "hidden" )
    new Accordion( tracesAccordionDiv, { duration: 400 } )

  
 
}

export default traces;
