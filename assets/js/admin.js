import Accordion from "./Accordion"
import ChartParams from "./ChartParams"
// import ChartLayout from "./ChartLayout"
import mediaUploader from "./media-uploader"
import renderPanels from "./panels"
import renderSections from "./render-sections"
import saveChart from "./save-chart"
import listCharts from "./list-charts"
import { displayAdminMessage, showElementById } from "./utilities"
import "../sass/admin.scss"


let iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}
let iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}

console.log("iwpgvObj", iwpgvObj)
console.log("iwpgvCharts", {...iwpgvCharts})

try {

  // Check if server error
  if ( iwpgvCharts.status === "error" ) throw new Error( iwpgvCharts.message )

  // List all charts
  if (iwpgvCharts.action && iwpgvCharts.action === "listCharts") {

    listCharts( iwpgvCharts, iwpgvObj)

  } else {
  
    // Initialize chart and panels
    const chart = iwpgvCharts.chart
    for(const prop in chart) {
      chart[prop].options = {...chart[prop].options}
      chart[prop].panel.sections = {...chart[prop].panel.sections}
    }

    // Assemble chart Params chart and panels
    const chartParamsInstance = new ChartParams( chart.chartParams.options, iwpgvObj )
    chart.chartParams.panel.sections = chartParamsInstance.panel()
    chart.chartParams.options = chartParamsInstance.options()

    // Loop through the chart params sections
    for (const section in chart.chartParams.panel.sections) {

      // Add  subpanel accordion toggle to panel content if there is a heading title
      if (chart.chartParams.panel.sections[section]["title"]) {
        // Create panel toggle div
        const subPanelToggle = document.createElement("div")
        subPanelToggle.classList.add("accordion__toggle")

        // Create toggle heading title div
        const panelHeadingTitle = document.createElement("div")
        panelHeadingTitle.classList.add("accordion__heading-title")

        // Create haeding title text
        const headingTitle = document.createTextNode(chart.chartParams.panel.sections[section]["title"])

        // Append title text to heading title div
        panelHeadingTitle.appendChild(headingTitle)

        // Append panel heading title to panel toggle
        subPanelToggle.appendChild(panelHeadingTitle)

        // Create svg element
        const svgElem = document.createElementNS("http://www.w3.org/2000/svg", "svg")
        svgElem.classList.add("accordion__svg")
        const useElem = document.createElementNS("http://www.w3.org/2000/svg", "use")
        useElem.href.baseVal = `${yrl_wp_igv_obj.url}assets/img/icons.svg#icon-keyboard_arrow_right`
        svgElem.appendChild(useElem)

        // Append svg to panel toggle
        subPanelToggle.appendChild(svgElem)

        const subpanelAccordion = document.getElementById( chart.chartParams.panel.id).querySelector(".accordion")
        console.log(subpanelAccordion)

        subpanelAccordion.appendChild(subPanelToggle)


      }
    }

    
    // chart.chartParams = {}
    // const panels = { 
    //   chartParams:{
    //     // id : `${iwpgvObj.prefix}__chartParamsPanel`,
    //     // cssClasses : ['chartParams', 'openOnLoad'],
    //     // title : "Parameters",
    //     // intro : "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
    //     // sections : {}
    //   },
    //   // chartLayout: {
    //   //   id : `${iwpgvObj.prefix}__chartLayoutPanel`,
    //   //   cssClasses : ['chartLayout', 'chart'],
    //   //   title : "Layout",
    //   //   intro : "This is the layout pnel",
    //   //   sections : {}
    //   // }
    // }

    // const panels1 = { chartParams1:{}}
    
    // Assemble chart Params chart and panels
    // const chartParamsInstance = new ChartParams( chart.chartParams, iwpgvObj )
    // panels.chartParams = chartParamsInstance.panel()
    // chart.chartParams = chartParamsInstance.options()

    // const chartParamsInstance1 = new ChartParams( chart.chartParams, iwpgvObj )
    // panels1.chartParams1 = chartParamsInstance1.panel().sections
    // console.log("PANELS1",panels1)

    // for (const section in panels1) {

    //   // Add  subpanel accordion toggle to panel content if there is a heading title
    //   if (sections[section]["title"]) {
    //     const subpanelToggle = accordionToggle(sections[section]["title"])
    //     subpanelAccordion.appendChild(subpanelToggle);
    //   }

    // }


      // Add new or edit an existing chart
    if (iwpgvCharts.action && iwpgvCharts.action === "editChart") {

      // Render and display the accordion panels
      // renderPanels(panels, iwpgvObj);
      // renderSections(panels1, iwpgvObj);

      showElementById (`${iwpgvObj.prefix}__saveChart` )
      
      // Add click event listener to the chart params panel inoput fields
      document.addEventListener("click", function (event) {

        // Bail if the clicked item is not inside the `${iwpgvObj.prefix}__chartOptionsForm` form 
        if (! event.target.classList.contains( `${iwpgvObj.prefix}__chartParams`) || ! event.target.closest("form").id === `${iwpgvObj.prefix}__chartOptionsForm`) return
      
        // file uploader event listener
        if (event.target.id === `${iwpgvObj.prefix}__chartParams[mediaUploadBtn]`) {
          event.preventDefault()
          mediaUploader( chart, iwpgvObj )
        }
        
        // Save chart event handler
        if (event.target.id === `${iwpgvObj.prefix}__saveChart`) {
          event.preventDefault()
          saveChart()
        }
        
      })

    }

  }


} catch (error) {

  displayAdminMessage(error.message, "error",  iwpgvObj)
  console.log("CAUGHT ERROR", error)

} 

// Load accordion
new Accordion( { collapsed: false } )

