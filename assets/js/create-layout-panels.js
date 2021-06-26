import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import BasicOptions from './BasicOptions'
import Title from "./Title"
import Legend from "./Legend"
import Hoverlabel from "./Hoverlabel"
import Modebar from "./Modebar"
import ChartAxis from "./ChartAxis"
import { createPanelSections } from "./utilities"


const layoutPanels = async function (chart, prefix) {


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

  document.querySelector(`#${prefix}__admin .modebarAc .ac-panel `).innerHTML = ""
  createPanelSections( Modebar.sections( chart.layout, chart.config ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .modebarAc .ac-panel` ), "modebar", prefix  )
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .modebarAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .xaxisAc .ac-panel .accordion`).innerHTML = ""
  createPanelSections( ChartAxis.sections( chart.layout, "xaxis", "bottom", null, "Wavelength ( &#181;m )", null), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .xaxisAc .ac-panel .xaxis__Accordion` ), "xaxis", prefix )
  document.getElementById(`${prefix}__layout[xaxis][type]`).value = chart.layout.xaxis.type
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxisAc`).classList.remove( "hidden" )
  new Accordion( `#${prefix}__admin .xaxis__Accordion`, { duration: 400 })

  document.querySelector(`#${prefix}__admin .xaxis2Ac .ac-panel .accordion`).innerHTML = ""
  createPanelSections( ChartAxis.sections( chart.layout, "xaxis2", "top", "x", "Wavelength ( &#181;m )", "x"  ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .xaxis2Ac .ac-panel .xaxis2__Accordion` ), "xaxis2", prefix )
  document.getElementById(`${prefix}__layout[xaxis2][type]`).value = chart.layout.xaxis2.type
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxis2Ac`).classList.remove( "hidden" )
  new Accordion( `#${prefix}__admin .xaxis2__Accordion`, { duration: 400 })

  document.querySelector(`#${prefix}__admin .yaxisAc .ac-panel .accordion`).innerHTML = ""
  createPanelSections( ChartAxis.sections( chart.layout, "yaxis", "left", null, "Transmittance ( % )", null ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .yaxisAc .ac-panel .yaxis__Accordion` ), "yaxis", prefix )
  document.getElementById(`${prefix}__layout[yaxis][type]`).value = chart.layout.yaxis.type
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxisAc`).classList.remove( "hidden" )
  new Accordion( `#${prefix}__admin .yaxis__Accordion`, { duration: 400 })

  document.querySelector(`#${prefix}__admin .yaxis2Ac .ac-panel .accordion`).innerHTML = ""
  createPanelSections( ChartAxis.sections( chart.layout, "yaxis2", "right", "y", "Reflectance ( % )", "y"  ), document.querySelector( `#${prefix}__admin #${prefix}__chartOptionsForm .yaxis2Ac .ac-panel .yaxis2__Accordion` ), "yaxis2", prefix )
  document.getElementById(`${prefix}__layout[yaxis2][type]`).value = chart.layout.yaxis2.type
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxis2Ac`).classList.remove( "hidden" )
  new Accordion( `#${prefix}__admin .yaxis2__Accordion`, { duration: 400 })

  document.querySelector(`#${prefix}__admin .annotationsAc .ac-panel .accordion`).innerHTML = ""
  document.querySelector(`#${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .annotationsAc`).classList.remove( "hidden" )

  document.querySelector(`#${prefix}__admin .minMaxAvgTableAc .ac-panel`).innerHTML = ""


}

export default layoutPanels;
