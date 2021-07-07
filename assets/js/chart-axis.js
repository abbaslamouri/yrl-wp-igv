import Plotly from 'plotly.js-dist'
import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';
import capitalize from 'lodash.capitalize'
import ChartAxis from "./ChartAxis"
import layoutHandler from "./layout-handler"
import { chartOptionKey, createPanel, createPanelSections } from "./utilities"


const chartAxis =  async( chart, axisType, prefix ) => {

  let deleteBtn = null


  const xaxes = Object.keys(chart.layout).filter( ( prop ) => prop.includes(axisType))
  let lastIndex =  0

  console.log("AXES", xaxes)
  // const axisOptions = []

  for (let i = 0; i < xaxes.length; i++) {
    lastIndex = parseInt(xaxes[i].split(axisType)[1]) > lastIndex ? parseInt(xaxes[i].split(axisType)[1]) : lastIndex
    // axisOptions.push ( ! xaxes[i].split(axisType)[1] ? `x` : `x${parseInt(xaxes[i].split(axisType)[1])}` )
  }

  const index = lastIndex === 0 ? 2 : lastIndex+1

  const axisOption = `x${index}`

  
  const axisId = `${axisType}${index}` 
  console.log("Option", axisOption)

  if ( chart.layout[axisId] === undefined ) {
    chart.layout[axisId] = ChartAxis.defaultOptions( axisId, "bottom", null, "Wavelength ( &#181;m )", null )
  }



  for (let i = 0; i < chart.traces.length; i++) {
   const tracesXaxis = document.getElementById(`${prefix}__traces[${i}][xaxis]`)
   tracesXaxis.options.add( new Option(capitalize( axisId ), axisOption, false) );
   console.log(tracesXaxis)
  }


  const xaxesAccordionDiv = document.querySelector( `#${prefix}__admin .xaxes__Accordion`)




  
  // Create a annotation panel and add it to xaxes accordion
  xaxesAccordionDiv.appendChild( createPanel(  `${axisId}Ac`, `X-Axis${index}`, `Here you can modify the options for a new x-axis` ) )

  // Create level3 accordion inside new annotation panel
  const level3AccordionDiv = document.createElement("div")
  level3AccordionDiv.classList.add("accordion", "accordion__level-3", `${axisId}__Accordion`)
  document.querySelector( `#${prefix}__admin .${axisId}Ac .ac-panel `).appendChild( level3AccordionDiv )

  const sectionsContainer = document.querySelector( `#${prefix}__admin .${axisId}__Accordion`)

  createPanelSections( ChartAxis.sections( chart.layout, axisId, "bottom", null, "Wavelength ( &#181;m )", null  ), sectionsContainer, axisId, prefix )


  if (axisId !== "xaxis" && axisId !== "yaxis") {

    // Create Delete annotation button
    deleteBtn = document.createElement("div")
    deleteBtn.classList.add(`.${prefix}__deleteAxis`, "button", "btn", "btn-danger")
    deleteBtn.id = `.${prefix}__layout[${axisType}${index}]`
    const buttonText = document.createTextNode( "Delete Axis" )
    deleteBtn.appendChild(buttonText)
    document.querySelector( `#${prefix}__admin .${axisId}Ac .ac-panel `).appendChild( deleteBtn )

  }






  new Accordion( `#${prefix}__admin .${axisId}__Accordion`, { duration: 400 })

  new Accordion( xaxesAccordionDiv, { duration: 400 } )


  document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .xaxesAc .ac-panel .xaxes__Accordion .${axisId}Ac .ac-trigger `).innerHTML = capitalize( axisId )

  console.log(chart.layout)

  // Add change event listener to all input fields
  // sectionsContainer.addEventListener( "input", async function ( event ) {
  //   event.preventDefault( )

  //     const control = chartOptionKey(event.target.name).control
  //     const key = chartOptionKey(event.target.name).key
  //     const keyParts = key.split(".")
  //     let value =  event.target.type === 'checkbox' ? event.target.checked : event.target.value
  //     console.group()
  //     console.log("Control", control)
  //     console.log("key", key)
  //     console.log("keyParts", keyParts)
  //     console.log("value", value)
  //     console.groupEnd()

  //     layoutHandler( chart, key, value, prefix )
  
  //   } )

  deleteBtn.addEventListener("click", function (event) {

    event.preventDefault()

    swal({
      title: "Are you sure?",
      text: "You will not be able to recover this xaxes",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

        const key = chartOptionKey(event.target.id).key
        const keyParts = key.split(".")
        console.log(keyParts)
        chart.layout[keyParts[1]] = null

        // Plotly.purge(`${prefix}__plotlyChart`)
        // Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )

        event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode)
        swal(`${keyParts[1]} has been deleted!`, {
          icon: "success",
        });
      }
    })

  })

}





export default chartAxis
