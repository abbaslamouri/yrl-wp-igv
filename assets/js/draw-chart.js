import Plotly from 'plotly.js-dist'
import Accordion from 'accordion-js'
import 'accordion-js/dist/accordion.min.css'
import renderPanels from "./render-panels"
import renderChart from "./render-chart"
import { chartOptionKey } from "./utilities"

const drawChart = async( chart, spreadsheet, prefix ) => {

  // Hide chart and table charts
  Plotly.purge(`${prefix}__plotlyChart`)
  Plotly.purge(`${prefix}__plotlyMinMaxAvgTable`)


  // Hide min/max inputs if visible
  // hideElementById( `${prefix}__plotMinMaxAvg` )

  // // remove layout panel toggle and panel
  // document.querySelector(`#${prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${prefix}__chartTracesPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${prefix}__tableChartPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // // Hide Min/Max/Avg accordion toggle and content
  // document.querySelector( `.accordion__toggle.minMaxAvgTableChart.panel` ).classList.add("hidden")
  // document.querySelector( `.accordion__content.minMaxAvgTableChart.panel` ).classList.add("hidden")

  // Render panels
  renderPanels( chart, spreadsheet, prefix )

  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .basicOptionsPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .configPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .tracesPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .titlePanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .legendPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .hoverlabelPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .modebarPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxisPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .xaxis2Panel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxisPanel`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Acc .yaxis2Panel`).classList.remove( "hidden" )

  console.log(typeof mainAccordion == undefined)


  if ( mainAccordion ) {
    console.log("LLLLLLLLLLAXX")
    mainAccordion.destroy()
  }

  // Create mainaccordion and open first panel
  mainAccordion = new Accordion( `.${prefix}__admin .main__Acc`, { duration: 400 })
  // Close all accordion panels
  mainAccordion.closeAll()

  console.log("ACC1", mainAccordion)


  // new Accordion( `.${prefix}__admin .xaxis__Acc`, { duration: 400 })
  // new Accordion( `.${prefix}__admin .xaxis2__Acc`, { duration: 400 })
  // new Accordion( `.${prefix}__admin .yaxis__Acc`, { duration: 400 })
  // new Accordion( `.${prefix}__admin .yaxis2__Acc`, { duration: 400 })

  

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${prefix}__saveChart` ).disabled = false
  document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
  
  // Render chart
  await renderChart( chart, spreadsheet, prefix )

  document.querySelector( `.${prefix}__admin .loading` ).classList.add("hidden")


  

  // // Add range slider event handler
  // eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

  //   // Bail if the event is other that range slider
  //   if ( ! eventData['xaxis.range'] || ! chart.chartParams.options.enableMinMaxTableChart ) return

  //   console.log("PPPPPP",eventData)
  //   //
  //   // const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
  //   // const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
  //   document.getElementById(`${prefix}__rangeMinInput`).value = eventData['xaxis.range'][0] //parseFloat(xAxisMin).toFixed(chart.minMaxAvgTableChart.options.rounding)
  //   document.getElementById(`${prefix}__rangeMaxInput`).value = eventData['xaxis.range'][1] //parseFloat(xAxisMax).toFixed(chart.minMaxAvgTableChart.options.rounding)
    
  //   // Update Min/Max/Avg table data
  //   // chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])

  //   Plotly.restyle( `${prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])] } )

  // })


  document.addEventListener("input", async function (event) {
  
    event.preventDefault()

    // console.log ("EVENT",event.target.name)
    if (  event.target.name.includes("__fileUpload") ) return

    if ( event.target.name === `${prefix}__rangeMinInput` || event.target.name === `${prefix}__rangeMinInput` ){

    } else {

      const control = chartOptionKey(event.target.name).control
      const key = chartOptionKey(event.target.name).key
      const keyParts = key.split(".")
      let value =  event.target.type === 'checkbox' ? event.target.checked : event.target.value

      console.log("Control", control)
      console.log("key", key)
      console.log("keyParts", keyParts)
      console.log("value", value)

      switch ( control ) {

        case "layout":

          switch(key) {

            case "xaxis.autorange":
              if ( value ){
                chart.layout.xaxis.range = []
                document.getElementsByName(`${prefix}__layout[xaxis][range]`)[0].value = ""
              }
              Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
              break

            case "xaxis.range":
              // value = "" === value ? null : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
              if (value) {
                value = value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
                Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value } )
                document.getElementsByName(`${prefix}__layout[xaxis][autorange]`)[0].checked = true
              } else {
                const update = { "xaxis.range": null, "xaxis.autorange": true}
                Plotly.relayout( `${prefix}__plotlyChart`, update)
              }
              break

            default:
              Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
 
          }

          // Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
          console.log("Layout", chart)
          break

          
        case "traces":
          const keyArr = key.split(".")
          const traceNumber = keyArr.shift()
          const optionKey = keyArr.join(".")
          console.log("OPT", optionKey, traceNumber)

          switch (optionKey) {
            case "visible":
              value = "true" === value ? true : "false" === value ? false : value
              break

            default:

              break
          }

          Plotly.restyle(`${prefix}__plotlyChart`, { [optionKey]: value}, traceNumber)
          console.log("TRACES",chart.traces[traceNumber])
          break
      }

    }

    // xaxis
    document.getElementsByName(`${prefix}__layout[xaxis][type]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
    document.getElementsByName(`${prefix}__layout[xaxis][range]`)[0].disabled = ! chart.layout.xaxis.visible || chart.layout.xaxis.autorange  ? true : false
    document.getElementsByName(`${prefix}__layout[xaxis][autorange]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false


    return false

  })
  
}


export default drawChart
