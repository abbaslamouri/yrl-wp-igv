import Plotly from 'plotly.js-dist'
import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';
import Trace from "./Trace"
import ChartAxis from "./ChartAxis"


import Annotation from "./Annotation"

import { displayAdminMessage, chartOptionKey, createPanel, createPanelSections, colors } from "./utilities"


const renderChart =  async( chart, spreadsheet, prefix ) => {

  try {

    if ( ! spreadsheet ) throw new Error(  `Spreadsheet missing` )


    /************************************************************************
    ******************* Create traces options and panel *********************
    *************************************************************************/

    const tracesAccordionDiv = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion`)
    tracesAccordionDiv.innerHTML = ""
    
    for (let i = 0;  i < spreadsheet[chart.fileUpload.sheetId].data.length - 1; i++) {

      // Traces options
      chart.traces[i] = Trace.defaultOptions( i )
      chart.traces[i].name = Object.values(spreadsheet[chart.fileUpload.sheetId]["labels"])[i+1]
      chart.traces[i].x = spreadsheet[chart.fileUpload.sheetId].data[0]
      chart.traces[i].y = spreadsheet[chart.fileUpload.sheetId].data[i+1]

      // Create a trace panel and add it to traces accordion
      tracesAccordionDiv.appendChild( createPanel(  `traces${i}Ac`, chart.traces[i].name, "" ) )

      // Create level3 accordion inside new trace panel
      const level3AccordionDiv = document.createElement("div")
      level3AccordionDiv.classList.add("accordion", "accordion__level-3", `traces${i}__Accordion`)
      document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .traces${i}Ac .ac-panel `).appendChild( level3AccordionDiv )

      // Create a section container
      const sectionsContainer = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .tracesAc .ac-panel .traces__Accordion .ac-panel .traces${i}__Accordion`)

      // Create panel sections
      createPanelSections( Trace.sections(chart.traces[i], i, Object.values(spreadsheet[chart.fileUpload.sheetId]["labels"])[i]), sectionsContainer, `traces${i}`, prefix )

      // Create section accordion
      new Accordion( `.${prefix}__admin .traces${i}__Accordion`, { duration: 400 })
      
    }

    // Create traces accordion
    new Accordion( tracesAccordionDiv, { duration: 400 } )





    /************************************************************************
    ******************* Create xaxis options and panel *********************
    *************************************************************************/
    // Xaxis Options
    chart.layout.xaxis = ChartAxis.defaultOptions( "bottom", null )
    chart.layout.xaxis2 = ChartAxis.defaultOptions( "top", "x" )
    await Plotly.newPlot( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )

    console.log("Chart", chart)



    createPanelSections( ChartAxis.sections( chart.layout, "xaxis"), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .xaxisAc .ac-panel .xaxis__Accordion` ), "xaxis", prefix )
    createPanelSections( ChartAxis.sections( chart.layout, "xaxis2"), document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .xaxis2Ac .ac-panel .xaxis2__Accordion` ), "xaxis2", prefix )





    // chart.config =  {
    //   displayModeBar: false,
    //   displaylogo: false
    // }
    
   

    // const plotlyData = document.getElementById(`${prefix}__plotlyChart`)

    // chart.layout = plotlyData.layout

    

    // console.log("DATA", plotlyData.data)
    // console.log("LAYOUT", plotlyData.layout)
    // console.log("CONFIG", plotlyData)


  } catch (error) {

    displayAdminMessage(error.message, "error",  prefix)
    console.log("CAUGHT ERROR", error)

  } 




  // const rangeMinInput = document.getElementById( `${prefix}__rangeMinInput` )
  // const rangeMaxInput = document.getElementById( `${prefix}__rangeMaxInput` )

  // const chart = iwpgvCharts.chart

  // console.log(chart)
  
  // Update chartLayout options
  // chart.chartLayout.options.hovermode = ( chart.chartLayout.hovermode ) ? chart.chartLayout.hovermode : false

    // document.getElementsByName(`${prefix}__layout[xaxis][autorange]`)[0].checked = chart.layout.xaxis.autorange
    // document.getElementsByName(`${prefix}__layout[xaxis][range]`)[0].value = chart.layout.xaxis.range.join()


    // document.getElementById(`${prefix}__chartLayout[xaxis][range]`).value = chart.chartLayout.options.xaxis.range.join()
    // document.getElementById(`${prefix}__chartLayout[xaxis][autorange]`).value = "false"
    // document.getElementById(`${prefix}__chartLayout[xaxis][autorange]`).disabled = true
    // chart.chartLayout.options.xaxis.autorange = false;


    // Render Min/Max?Avg table chart if enableMinMaxTableChart is true
    // if ( chart.minMaxAvgTable.visible ) {

    //   document.getElementsByName(`${prefix}__rangeMinInput`)[0].value = chart.minMaxAvgTable.rounding ? parseFloat( chart.layout.xaxis.range[0] ).toFixed( chart.minMaxAvgTable.rounding ) : chart.layout.xaxis.range[0]
    //   document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value = chart.minMaxAvgTable.rounding ? parseFloat( chart.layout.xaxis.range[1] ).toFixed( chart.minMaxAvgTable.rounding ) : chart.layout.xaxis.range[1]
          
    //   // Set table header
    //   // const headerValues = [["Trace"], ["Min"], ["Average"], ["Max"]]
    //   chart.minMaxAvgTable.header.values = [["Trace"], ["Min"], ["Average"], ["Max"]]

    //   chart.minMaxAvgTable.cells.values = getMinMaxAvgData( chart, spreadsheet )
    //   console.log("MINMAX", chart.minMaxAvgTable)

    //   await Plotly.newPlot( `${prefix}__plotlyMinMaxAvgTable`, [chart.minMaxAvgTable], chart.minMaxAvgTable.layout, chart.minMaxAvgTable.config  )
    //   // await Plotly.newPlot( `${prefix}__plotlyMinMaxAvgTable`, [chart.minMaxAvgTable], chart.minMaxAvgTable.layout, {displayModeBar: false} )

    //   // Add range slider event handler
    //   eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){
    //     // Bail if the event is other that range slider
    //     if ( ! eventData['xaxis.range'] ) return
       
    //     document.getElementsByName(`${prefix}__rangeMinInput`)[0].value =  chart.minMaxAvgTable.rounding ? parseFloat(eventData['xaxis.range'][0]).toFixed( chart.minMaxAvgTable.rounding ) : eventData['xaxis.range'][0]
    //     document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value =  chart.minMaxAvgTable.rounding ? parseFloat(eventData['xaxis.range'][1]).toFixed( chart.minMaxAvgTable.rounding ) : eventData['xaxis.range'][1]
        
    //     Plotly.restyle( `${prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData( chart, spreadsheet )] } )

    //   })

    //   document.querySelector(`.${prefix}__admin #${prefix}__plotMinMaxAvgForm`).classList.remove( "hidden" )


    //   // Add range min and range max change event listener
    //   // document.addEventListener( "input", function ( event ) {
    //   //   event.preventDefault()
    //   //   console.log("JJJJJJJJ")

    //   //   if ( event.target.name !== `${prefix}__rangeMinInput` || event.target.name !== `${prefix}__rangeMaxInput`)  return
    //   //     // const xaxisMin = document.getElementsByName(`${prefix}__rangeMinInput`)[0].value ? document.getElementsByName(`${prefix}__rangeMinInput`)[0].value : chart.layout.xaxis.range[0]
    //   //     // const xaxisMax = document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value ? document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value : chart.layout.xaxis.range[1]
    //   //     // console.log(xaxisMax, xaxisMin)
    //   //     // console.log("JJJJJJJJ")
    //   //     // // if ( parseFloat( xaxisMin ) >= parseFloat( xaxisMax ) ) return
    //   //     // Plotly.relayout(`${prefix}__plotlyChart`, { 'xaxis.range': [ parseFloat( xaxisMin ), parseFloat( xaxisMax )] })
    //   //     // console.log("JJJJJJJJxxxxxxx")
      
    //   // })

    // }

    // const xAxisMin = chart.chartLayout.options.xaxis.range[0]
    // const xAxisMax = chart.chartLayout.options.xaxis.range[1]
    
    // // Set range min and max input values
    // rangeMinInput.value =  chart.minMaxAvgTableChart.options.rounding ? xAxisMin.toFixed(chart.minMaxAvgTableChart.options.rounding) : xAxisMin
    // rangeMaxInput.value = chart.minMaxAvgTableChart.options.rounding ? xAxisMax.toFixed(chart.minMaxAvgTableChart.options.rounding) : xAxisMax

    // // Fetch Min?Max/Avg table data
    // chart.minMaxAvgTableChart.options = fetchMinMaxAvgTableChartData( chart, spreadsheet, xAxisMin, xAxisMax )
    // console.log(chart.minMaxAvgTableChart.options.cells.values)

    // // Show Min/Max/Avg table (must show the div before plaotting)
    // showElementById( `${prefix}__plotlyMinMaxAvgTable` )

    // // Plot table
    // await Plotly.newPlot(`${prefix}__plotlyMinMaxAvgTable`, [chart.minMaxAvgTableChart.options], chart.minMaxAvgTableChart.options.layout, {displayModeBar: false})

    // // Show range min and max input fields
    // showElementById( `${prefix}__plotMinMaxAvg` )

    // // Add range slider event handler
    // eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

    //   // Bail if the event is other that range slider
    //   if ( ! eventData['xaxis.range'] ) return

    //   console.log("KKKKKK", eventData )

    //   //
    //   // const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   // const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   document.getElementById(`${prefix}__rangeMinInput`).value = parseFloat( eventData['xaxis.range'][0] ).toFixed(chart.minMaxAvgTableChart.options.rounding)
    //   document.getElementById(`${prefix}__rangeMaxInput`).value = parseFloat( eventData['xaxis.range'][1] ).toFixed(chart.minMaxAvgTableChart.options.rounding)
      
    //   // Update Min/Max/Avg table data
    //   // chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])

    //   Plotly.restyle( `${prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])] } )

    // })

  // }

  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm #${prefix}__addAnnotation`).addEventListener("click", function (event) {

    event.preventDefault()
    Plotly.purge(`${prefix}__plotlyChart`)


    const index = chart.layout.annotations.length

    const optionId = `annotations${index}`

    const annotationsAccordionDiv = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion`)
     // Create a annotation panel and add it to annotations accordion
     annotationsAccordionDiv.appendChild( createPanel(  `${optionId}Ac`, "New Annotation", `Here you can modify the options for New Annotation` ) )

    // Create Delete annotation button
    const deletebutton = document.createElement("button")
    deletebutton.classList.add(`.${prefix}__deleteAnnotation`, "button", "btn", "btn-danger")
    deletebutton.id = `.${prefix}__layout[annotations][${index}]`
    const buttonText = document.createTextNode( "Delete Annotation" )
    deletebutton.appendChild(buttonText)
    document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .${optionId}Ac .ac-panel `).appendChild( deletebutton )


    // Create level3 accordion inside new annotation panel
    const level3AccordionDiv = document.createElement("div")
    level3AccordionDiv.classList.add("accordion", "accordion__level-3", `${optionId}__Accordion`)
    document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .${optionId}Ac .ac-panel `).appendChild( level3AccordionDiv )

    const sectionsContainer = document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .ac-panel .${optionId}__Accordion`)

    chart.layout.annotations[index] = ( chart.layout.annotations[index] !== undefined )? chart.layout.annotations[index] : {}
    const annotationInstance = new Annotation( chart.layout.annotations[index], index )
    chart.layout.annotations[index] = annotationInstance.options()
    createPanelSections( annotationInstance.sections(), sectionsContainer, optionId, prefix )
    new Accordion( `.${prefix}__admin .${optionId}__Accordion`, { duration: 400 })

    console.log("XT", chart)


    Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )

    document.querySelector( `.${prefix}__admin #${prefix}__chartOptionsForm .annotationsAc .ac-panel .annotations__Accordion .${optionId}Ac .ac-trigger `).innerHTML = chart.layout.annotations[index].text
    console.log(document.querySelectorAll(`.${prefix}__admin #${prefix}__chartOptionsForm .${prefix}__deleteAnnotation`))

    // document.querySelectorAll(`.${prefix}__admin #${prefix}__chartOptionsForm .${prefix}__deleteAnnotation`).forEach( (el) => {
    
      deletebutton.addEventListener("click", function (event) {

        event.preventDefault()


        Plotly.purge(`${prefix}__plotlyChart`)


        const control = chartOptionKey(event.target.id).control
        const key = chartOptionKey(event.target.id).key
        const keyParts = key.split(".")

        console.log("Control", control)
        console.log("key", key)
        console.log("keyParts", keyParts)

        chart.layout.annotations[keyParts[1]] = null
        Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )

        console.log("CT", chart)

        swal({
          title: "Are you sure?",
          text: "You will not be able to recover this annotation",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            console.log("PPPPP", event.target.parentNode.parentNode)
            event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode)
            swal(`Annotation has been deleted!`, {
              icon: "success",
            });
          } 
          // else {
          //   swal("Your imaginary file is safe!");
          // }
        })
    
    
      })

    // })


  })

  // document.querySelectorAll(`.${prefix}__admin #${prefix}__chartOptionsForm .${prefix}__deleteAnnotation`).forEach( (el) => {
    
  //   el.addEventListener("click", function (event) {

  //     console.log("PPPPP", event.target)

  //   })

  // })



  // }

  // Render table chart if enableTableChart is true
  // if ( chart.chartParams.options.enableTableChart ) {
  //   chart.tableChart.options = fetchTableChartData( chart, spreadsheet )
  //   await Plotly.newPlot(`${prefix}__plotlyTable`, [chart.tableChart.options], chart.tableChart.options.layout, chart.chartLayout.options.config )

  // }



  

    // // Add range slider event handler
    // eval(`${prefix}__plotlyChart`).on('plotly_relayout',function(eventData){  
    //   const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
    //   document.getElementById(`${prefix}__rangeMinInput`).value = parseFloat(xAxisMin).toFixed(3)
    //   document.getElementById(`${prefix}__rangeMaxInput`).value = parseFloat(xAxisMax).toFixed(3)
    //   chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
    //   Plotly.restyle( `${prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData( chart, spreadsheet, xAxisMin, xAxisMax)] } )
    // })

    // // Add range min and range max change event listener
    // document.addEventListener( "change", function ( event ) {
    //   event.preventDefault()
    //   if ( ! event.target.closest("form")  || event.target.closest("form").id !== `${prefix}__plotMinMaxAvg` || ( event.target.id !== `${prefix}__rangeMinInput` && event.target.id !== `${prefix}__rangeMaxInput`) ) return
    //     const newXAxisMin = document.getElementById(`${prefix}__rangeMinInput`).value ?  document.getElementById(`${prefix}__rangeMinInput`).value : xAxisMin
    //     const newXAxisMax = document.getElementById(`${prefix}__rangeMaxInput`).value ? document.getElementById(`${prefix}__rangeMaxInput`).value : xAxisMax
    //     Plotly.relayout(`${prefix}__plotlyChart`, { 'xaxis.range': [newXAxisMin, newXAxisMax] })      
    // })



    // document.addEventListener("change", function (event) {
      
    //   event.preventDefault()

    //   // Bail if the clicked item is not inside the `${prefix}__chartOptionsForm` form 
    //   if (  ! event.target.closest("form") ||  event.target.closest("form").id !== `${prefix}__chartOptionsForm` ) return

    //   const control = chartOptionKey(event.target.id).control
    //   const key = chartOptionKey(event.target.id).key
    //   const keyParts = key.split(".")
    //   let value = event.target.value

    //   console.log(control, key, value)
    
    //   // Chart layout event handler
    //   if( control === "chartLayout" )  {
    //     if ( key.includes( "config" ) ) {
    //       chart.chartLayout.options.config[key.split(".")[1]] = event.target.type === 'checkbox' ? event.target.checked : event.target.value
    //       Plotly.newPlot( `${prefix}__plotlyChart`, Object.values(chart.traces.options), chart.chartLayout.options, chart.chartLayout.options.config )
    //     } else if (key === "hovermode" || key === "legend.itemclick" ) {
    //       value = ( event.target.value !== "disabled" ) ? event.target.value : false
    //       Plotly.relayout( `${prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.chartLayout.options.config )
    //     } else {
    //       switch(keyParts.length){
    //         case 1:
    //           chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 2:
    //           chart[control].options[keyParts[0]][keyParts[1]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 3:
    //           chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 4:
    //             chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //         case 5:
    //             chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]][keyParts[4]] = event.target.type === 'checkbox' ? event.target.checked : value
    //           break
    //       }
    //       Plotly.relayout( `${prefix}__plotlyChart`, { [key]: event.target.type === 'checkbox' ? event.target.checked : value}, chart.chartLayout.options.config )
    //     }
        
    //   }

      
    //   // Chart traces event handler
    //   if ( event.target.classList.contains( `${prefix}__chartTraces` ) ) {
    //     const keyArr = key.split(".")
    //     const traceNumber = keyArr.shift()
    //     const optionKey = keyArr.join(".")
    //     if ( optionKey === "visible" && event.target.value === "disabled" ) value = false
    //     if ( optionKey === "visible" && event.target.value === "enabled" ) value = true
    //     Plotly.restyle(`${prefix}__plotlyChart`, { [optionKey]: event.target.type === 'checkbox' ? event.target.checked : value }, traceNumber);
    //   }


    // })

  


  
  //   then (function() {

  //      // table charts event handler
  //      if( control === "tableChart" || control ==="minMaxAvgTableChart" )  {

  //       const plotlyTable = ( control === "tableChart" ) ? `${prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${prefix}__plotlyMinMaxAvgTable` : null

  //       const rowFillColors = []

  //       switch( key ) {
  //         case "firstColAlign":
  //           chart[control].options.cells.align = [value, chart[control].options.cells.align[1]]
  //           chart[control].options.header.align = [value, chart[control].options.header.align[1]]
  //           break
  //         case "rounding":
  //           const cellValues = []
  //           for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
  //             cellValues[i] =[]
  //             for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
  //               cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( value ) ) 
  //             }  
  //           }
  //           chart[control].options.cells.values = cellValues
  //           if ( plotlyTable === `${prefix}__plotlyMinMaxAvgTable` ) {
  //             console.log("HEHTRD")
  //             const xAxisMin = document.getElementById(`${prefix}__rangeMinInput`).value
  //             const xAxisMax = document.getElementById(`${prefix}__rangeMaxInput`).value
  //             chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
  //             console.log(chart)
  //           }
  //           break
  //         case "evenRowColor":
  //           // const rowFillColors = []
  //           for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
  //             rowFillColors[j] = (j % 2 === 0) ? chart[control].options.oddRowColor : value 
  //           }
  //           chart[control].options.cells.fill.color = [rowFillColors]
  //           break
  //         case "oddRowColor":
  //           // const rowFillColors = []
  //           for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
  //             rowFillColors[j] = (j % 2 === 0) ? value : chart[control].options.evenRowColor
  //           }
  //           chart[control].options.cells.fill.color = [rowFillColors]
  //           break
  //         case "header.align":
  //           chart[control].options.header.align = [chart[control].options.firstColAlign, value]
  //           break
  //         case "cells.align":
  //           chart[control].options.cells.align = [chart[control].options.firstColAlign, value]
  //           break
  //         default:
  //           switch (keyParts.length ) {
  //             case 1:
  //               chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : value
  //               break
  //             case 2:
  //               chart[control].options[keyParts[0]][keyParts[1]] = [chart[control].options.firstColAlign, value]
  //               break
  //             case 3:
  //               chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
  //               break
  //             case 4:
  //               chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
  //               break
  //           }
          
  //       }
  //       console.log("PLOT", plotlyTable)
  //       if (plotlyTable) Plotly.newPlot(plotlyTable, [chart[control].options], chart[control].options.layout, chart.chartConfig)  
  //     }

  //   })

  // }


  


}





export default renderChart
