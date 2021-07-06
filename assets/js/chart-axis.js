import Plotly from 'plotly.js-dist'
import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';
import Annotation from "./Annotation"

import { chartOptionKey, createPanel, createPanelSections } from "./utilities"


const chartAxis =  async( chart, axisType, prefix ) => {


  const xaxes = Object.keys(chart.layout).filter( ( prop ) => prop.includes("xaxis"))
  console.log("Chart Axis", xaxes)
  let lastIndex = xaxes[0].split("xaxis")[1] ? parseInt(xaxes[0].split("xaxis")[1]) : 0

  for (let i=0; i<xaxes.length; i++) {
    lastIndex = parseInt(xaxes[i].split("xaxis")[1]) > lastIndex ? parseInt(xaxes[i].split("xaxis")[1]) : lastIndex
  }
  
  const axisId = `${axisType}${lastIndex+1}` 
  console.log(axisId)

 return

  let index;

  // Bail if no file, sheet Id or chart type
  // if( ! event.target.id === `${prefix}__addAnnotation` ) return
  Plotly.purge(`${prefix}__plotlyChart`)

  if ( chart.layout.annotations === undefined) {
    index = 0
    chart.layout.annotations = []
  } else {
    index = chart.layout.annotations ? chart.layout.annotations.length : 0
  }

  const optionId = `annotations${index}`

  const annotationsAccordionDiv = document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion`)
    // Create a annotation panel and add it to annotations accordion
    annotationsAccordionDiv.appendChild( createPanel(  `${optionId}Ac`, "New Annotation", `Here you can modify the options for New Annotation` ) )

  // Create Delete annotation button
  const deletebutton = document.createElement("div")
  deletebutton.classList.add(`.${prefix}__deleteAnnotation`, "button", "btn", "btn-danger")
  deletebutton.id = `.${prefix}__layout[annotations][${index}]`
  const buttonText = document.createTextNode( "Delete Annotation" )
  deletebutton.appendChild(buttonText)
  document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .${optionId}Ac .ac-panel `).appendChild( deletebutton )


  // Create level3 accordion inside new annotation panel
  const level3AccordionDiv = document.createElement("div")
  level3AccordionDiv.classList.add("accordion", "accordion__level-3", `${optionId}__Accordion`)
  document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .${optionId}Ac .ac-panel `).appendChild( level3AccordionDiv )

  const sectionsContainer = document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .ac-panel .${optionId}__Accordion`)

  chart.layout.annotations[index] = ( chart.layout.annotations[index] !== undefined )? chart.layout.annotations[index] : {}
  // const annotationInstance = new Annotation( chart.layout.annotations[index], index )
  chart.layout.annotations[index] = Annotation.defaultOptions()

  createPanelSections( Annotation.sections(chart.layout.annotations[index], index, chart.layout.annotations[index].text ), sectionsContainer, optionId, prefix )
  new Accordion( `#${prefix}__admin .${optionId}__Accordion`, { duration: 400 })

  Plotly.plot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

  document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .${optionId}Ac .ac-trigger `).innerHTML = chart.layout.annotations[index].text

  deletebutton.addEventListener("click", function (event) {

    event.preventDefault()

    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this annotation",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

        const key = chartOptionKey(event.target.id).key
        const keyParts = key.split(".")
        chart.layout.annotations[keyParts[1]] = null

        Plotly.purge(`${prefix}__plotlyChart`)
        Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )

        event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode)
        swal(`Annotation has been deleted!`, {
          icon: "success",
        });
      }
    })

  })

}





export default chartAxis
