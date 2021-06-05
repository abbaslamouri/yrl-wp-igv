import Plotly from 'plotly.js-dist'
import renderPanels from "./render-panels"
import renderChart from "./render-chart"
import { setSheetIdOptions, showInputField, showElementById, hideElementById, getMinMaxAvgData, fetchMinMaxAvgTableChartData, chartOptionKey } from "./utilities"

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

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${prefix}__saveChart` ).disabled = false
  document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
  
  // Render chart
  await renderChart( chart, spreadsheet, prefix )

  

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

    console.log(event.target.id)

    switch( event.target.id ){

      case `${prefix}__rangeMinInput`:
      case `${prefix}__rangeMaxInput`:
        const xAxisMin = document.getElementById(`${prefix}__rangeMinInput`).value
        const xAxisMax = document.getElementById(`${prefix}__rangeMaxInput`).value
        if (parseFloat(xAxisMin) < parseFloat(xAxisMax) ) {
          Plotly.relayout(`${prefix}__plotlyChart`, { 'xaxis.range': [xAxisMin, xAxisMax] })
        }
        break

      default:
        const control = chartOptionKey(event.target.id).control
        const key = chartOptionKey(event.target.id).key
        const keyParts = key.split(".")
        let value =  event.target.type === 'checkbox' ? event.target.checked : event.target.value

        console.log("Control", control)
        console.log("key", key)
        console.log("keyParts", keyParts)
        console.log("value", value)

        switch ( control ) {

          case "layout":

            if ( key.includes( "config" ) ) {
              Plotly.purge(`${prefix}__plotlyChart`)
            
              chart.layout.config[key.split(".")[1]] = value
              Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.layout.config )
            } else {
              switch(key) {
                case "xaxis.range":
                  value = "" === value ? [] : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
                  break
                case "xaxis.autorange":
                  value = "false" === value ? false : "true" === value ? true : value
                  if ( true === value ){
                    chart.layout.xaxis.range = []
                    document.getElementById(`${prefix}__chartLayout[xaxis][range]`).value = ""
                  }
                  break
                case "xaxis.tickvals":
                  value = "" === value ? [] : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
                  break
                case "xaxis.ticktext":
                  value = "" === value ? [] : value.split(",").map( ( item ) => { return item } )
                  console.log(value)
                  break
                case "legend.itemclick":
                case "legend.itemdoubleclick":
                case "hovermode":
                    value = "false" === value ? false : value
                    console.log("LEG")
                    break
                default:
                  break
              }
              Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })

            }

            Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
            console.log("CHART", chart)



            // const layout = chart.layout
            // const layoutInputIdPrefix = `${prefix}__chartLayout`

           

            


            

            
           
           


            // // Range Slider
            // document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][thickness]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            // document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][bgcolor]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            // document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][borderwidth]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            // document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][bordercolor]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            // document.getElementById(`${layoutInputIdPrefix}[showMinMaxAvgTable]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false

            break


          case "basicOptions":
            if ( key === "responsive" ) {
              // if response is checked then set width to null (100%)
              if ( value ) {
                chart.layout.width = null
                document.getElementsByName( `${prefix}__basicOptions[width]`)[0].value = null
              }
              chart.config[key] = value
              Plotly.purge(`${prefix}__plotlyChart`)
              Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )
            } else  if ( key === "staticPlot" ) {
              chart.config[key] = value
              Plotly.purge(`${prefix}__plotlyChart`)
              Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )
            } else {
              switch (key) {
                case "width":
                  if (value) {
                    document.getElementsByName( `${prefix}__basicOptions[responsive]`)[0].checked = false
                  } else {
                    value = null
                    document.getElementsByName( `${prefix}__basicOptions[responsive]`)[0].checked = true
                  }
                  break
                
                default:
                  break
              }
              console.log("basicOptions",chart)
              Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
            }
            break

          case "chartTitle":
              Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
              break

          case "chartLegend":
                Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
              break

          case "hoverlabel":
            value = "false" === value ? false : value
            Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
            break

          case "modebar":
            if ( key === "displayModeBar" ) {
              chart.config[key] = value
              Plotly.purge(`${prefix}__plotlyChart`)
              Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )
            } else if ( key === "displaylogo" ) {
              chart.config[key] = value
              Plotly.purge(`${prefix}__plotlyChart`)
              Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )
            } else {
              Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
            }
            break
 
          case "bottomAxis":
            switch(key) {
              case "range":
                value = "" === value ? [] : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
                break

              case "autorange":
                value = "false" === value ? false : "true" === value ? true : value
                console.log("JJJJJJJJ", value)
                if ( true === value ){
                  chart.layout.xaxis.range = []
                  // document.getElementById(`${prefix}__bottomAxis[range]`).value = ""
                }
                break

              case "mirror":
                value = "false" === value ? false : "true" === value ? true : value
                break
            }
            Plotly.relayout( `${prefix}__plotlyChart`, { [`xaxis.${key}`]: value })
            console.log("BOTTOM", chart.layout)
            break

          case "topAxis":
            switch(key) {
              case "range":
                value = "" === value ? [] : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
                break
            }
            Plotly.relayout( `${prefix}__plotlyChart`, { [`xaxis2.${key}`]: value })
            console.log("TOP", chart)
            break

          case "leftAxis":
            switch(key) {
              case "range":
                value = "" === value ? [] : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
                break
            }
            Plotly.relayout( `${prefix}__plotlyChart`, { [`yaxis.${key}`]: value })
            console.log("LEFTY", chart)
            break

            case "rightAxis":
          switch(key) {
            case "range":
              value = "" === value ? [] : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
              break
          }
          Plotly.relayout( `${prefix}__plotlyChart`, { [`yaxis2.${key}`]: value })
          console.log("Right", chart)
          break

          
          case "traces":

            const keyArr = key.split(".")
            const traceNumber = keyArr.shift()
            const optionKey = keyArr.join(".")
            console.log("OPT", optionKey, traceNumber)

            if ( optionKey === "visible"  ) {
              value = value === "true" ? true : value === "false" ? false : value
            } else if ( optionKey === "error_y.array" || optionKey === "error_y.arrayminus" ){
              value = value === "" ? value : [value.split(",").map( ( item ) => { return parseFloat( item )} )]
              chart.traces[traceNumber][optionKey] = value
            }

            Plotly.restyle(`${prefix}__plotlyChart`, { [optionKey]: value}, traceNumber)
            console.log("TRACES",chart.traces[traceNumber])

            // const trace = chart.traces[traceNumber]
            // const traceInputIdPrefix = `${prefix}__chartTraces[${traceNumber}]`           

            // document.getElementById(`${traceInputIdPrefix}[xaxis]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[yaxis]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[mode]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[name]`).disabled = ( true !== trace.visible || trace.showlegend ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[opacity]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[showlegend]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][symbol]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][size]`).disabled = (true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][opacity]`).disabled = (true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][color]`).disabled = (true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][maxdisplayed]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][line][width]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][line][color]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][gradient][type]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[marker][gradient][color]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[line][shape]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[line][width]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[line][color]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[line][dash]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[line][smoothing]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[line][simplify]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[text]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[textposition]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[textfont][family]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[textfont][color]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[textfont][size]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hovertext]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverinfo]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverlabel][bgcolor]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverlabel][bordercolor]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverlabel][align]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverlabel][namelength]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverlabel][font][family]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverlabel][font][size]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[hoverlabel][font][color]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][visible]`).disabled = ( true !== trace.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][type]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][symmetric]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible  ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][value]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type === "data" ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][valueminus]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type === "data" || trace.error_y.symmetric ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][array]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type !== "data" ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][arrayminus]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type !== "data"  || trace.error_y.symmetric ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][color]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][thickness]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[error_y][width]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            // document.getElementById(`${traceInputIdPrefix}[connectgaps]`).disabled = ( true !== trace.visible  ) ? true : false

            break
                   

          case "tableChart":
          case "minMaxAvgTableChart":
            const plotlyTable = ( control === "tableChart" ) ? `${prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${prefix}__plotlyMinMaxAvgTable` : null
            const rowFillColors = []
            switch( key ) {
              case "firstColAlign":
                chart[control].options.firstColAlign = value
                chart[control].options.cells.align = [value, chart[control].options.cells.align[1]]
                chart[control].options.header.align = [value, chart[control].options.header.align[1]]
                break
              case "rounding":
                chart[control].options.rounding = value
                const cellValues = []
                for ( let  i = 0; i < spreadsheet[chart.chartParams.options.sheetId].data.length; i++ ) {
                  cellValues[i] =[]
                  for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[i].length; j++ ) {
                    cellValues[i][j] = ( spreadsheet[chart.chartParams.options.sheetId].data[i][j].toFixed( value ) ) 
                  }  
                }
                chart[control].options.cells.values = cellValues
                if ( plotlyTable === `${prefix}__plotlyMinMaxAvgTable` ) {
                  const xAxisMin = document.getElementById(`${prefix}__rangeMinInput`).value
                  const xAxisMax = document.getElementById(`${prefix}__rangeMaxInput`).value
                  chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, xAxisMin, xAxisMax)
                }
                break
              case "evenRowColor":
                // const rowFillColors = []
                for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                  rowFillColors[j] = (j % 2 === 0) ? chart[control].options.oddRowColor : value 
                }
                chart[control].options.cells.fill.color = [rowFillColors]
                break
              case "oddRowColor":
                // const rowFillColors = []
                for ( let  j = 0; j < spreadsheet[chart.chartParams.options.sheetId].data[0].length; j++ ) {
                  rowFillColors[j] = (j % 2 === 0) ? value : chart[control].options.evenRowColor
                }
                chart[control].options.cells.fill.color = [rowFillColors]
                break
              case "header.align":
                chart[control].options.header.align = [chart[control].options.firstColAlign, value]
                break
              case "cells.align":
                chart[control].options.cells.align = [chart[control].options.firstColAlign, value]
                break
              default:
                switch (keyParts.length ) {
                  case 1:
                    chart[control].options[keyParts[0]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 2:
                    chart[control].options[keyParts[0]][keyParts[1]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 3:
                    chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                  case 4:
                    chart[control].options[keyParts[0]][keyParts[1]][keyParts[2]][keyParts[3]] = event.target.type === 'checkbox' ? event.target.checked : value
                    break
                }
            }
            if (plotlyTable) Plotly.newPlot(plotlyTable, [chart[control].options], chart[control].options.layout) 

        }

        // // Title
        // document.getElementsByName(`${prefix}__chartTitle[title][font][family]`)[0].disabled = ! chart.layout.title.text  ? true : false
        // document.getElementsByName(`${prefix}__chartTitle[title][font][size]`)[0].disabled = ! chart.layout.title.text  ? true : false
        // document.getElementsByName(`${prefix}__chartTitle[title][font][color]`)[0].disabled = ! chart.layout.title.text  ? true : false
        // document.getElementsByName(`${prefix}__chartTitle[title][x]`)[0].disabled = ! chart.layout.title.text  ? true : false
        // document.getElementsByName(`${prefix}__chartTitle[title][y]`)[0].disabled = ! chart.layout.title.text  ? true : false

        // // Legend
        // document.getElementsByName(`${prefix}__chartLegend[legend][bgcolor]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][bordercolor]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][borderwidth]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][font][family]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][font][size]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][font][color]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][title][text]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][title][font][family]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][title][font][size]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][title][font][color]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][title][side]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][orientation]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][itemsizing]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][itemwidth]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][itemclick]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][itemdoubleclick]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][x]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][y]`)[0].disabled = ! chart.layout.showlegend ? true : false
        // document.getElementsByName(`${prefix}__chartLegend[legend][valign]`)[0].disabled = ! chart.layout.showlegend ? true : false

        // // Hoverlabel
        // document.getElementsByName(`${prefix}__hoverlabel[hoverlabel][bgcolor]`)[0].disabled = ! chart.layout.hovermode ? true : false
        // document.getElementsByName(`${prefix}__hoverlabel[hoverlabel][bordercolor]`)[0].disabled = ! chart.layout.hovermode ? true : false
        // document.getElementsByName(`${prefix}__hoverlabel[hoverlabel][align]`)[0].disabled = ! chart.layout.hovermode ? true : false
        // document.getElementsByName(`${prefix}__hoverlabel[hoverlabel][namelength]`)[0].disabled = ! chart.layout.hovermode ? true : false
        // document.getElementsByName(`${prefix}__hoverlabel[hoverlabel][font][family]`)[0].disabled = ! chart.layout.hovermode ? true : false
        // document.getElementsByName(`${prefix}__hoverlabel[hoverlabel][font][size]`)[0].disabled = ! chart.layout.hovermode ? true : false
        // document.getElementsByName(`${prefix}__hoverlabel[hoverlabel][font][color]`)[0].disabled = ! chart.layout.hovermode ? true : false

        // // Modebar
        // document.getElementsByName(`${prefix}__modebar[modebar][orientation]`)[0].disabled = ! chart.config.displayModeBar ? true : false
        // document.getElementsByName(`${prefix}__modebar[modebar][bgcolor]`)[0].disabled = ! chart.config.displayModeBar ? true : false
        // document.getElementsByName(`${prefix}__modebar[modebar][color]`)[0].disabled = ! chart.config.displayModeBar ? true : false
        // document.getElementsByName(`${prefix}__modebar[modebar][activecolor]`)[0].disabled = ! chart.config.displayModeBar ? true : false
        // document.getElementsByName(`${prefix}__modebar[displaylogo]`)[0].disabled = ! chart.config.displayModeBar ? true : false

        // // X-Axis
        // document.getElementsByName(`${prefix}__bottomAxis[type]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[autotypenumbers]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[autorange]`)[0].disabled = ( ! chart.layout.xaxis.visible )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[rangemode]`)[0].disabled = ( ! chart.layout.xaxis.visible || false === chart.layout.xaxis.autorange || chart.layout.xaxis.type !== "linear" )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[range]`)[0].disabled = ( ! chart.layout.xaxis.visible || true === chart.layout.xaxis.autorange ) ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[fixedrange]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // // document.getElementsByName(`${prefix}__bottomAxis[scaleanchor]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[ticks]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickmode]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" ) ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[nticks]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" || chart.layout.xaxis.tickmode !== "auto" )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tick0]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === ""|| chart.layout.xaxis.tickmode !== "linear"  )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[dtick]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === ""|| chart.layout.xaxis.tickmode !== "linear"  )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickvals]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" || chart.layout.xaxis.tickmode !== "array"  )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[ticktext]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" || chart.layout.xaxis.tickmode !== "array"  )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[ticklabelposition]`)[0].disabled = ( ! chart.layout.xaxis.visible|| ! chart.layout.xaxis.showticklabels )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[mirror]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[ticklen]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickwidth]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickcolor]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[showticklabels]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[automargin]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // // document.getElementsByName(`${prefix}__bottomAxis[showspikes]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[spikecolor]`)[0].disabled = ! chart.layout.xaxis.showspikes  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[spikethickness]`)[0].disabled = ! chart.layout.xaxis.showspikes  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[spikedash]`)[0].disabled = ! chart.layout.xaxis.showspikes  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[spikemode]`)[0].disabled = ! chart.layout.xaxis.showspikes  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickangle]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickprefix]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels || chart.layout.xaxis.showtickprefix === "none" )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[showtickprefix]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[ticksuffix]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels || chart.layout.xaxis.showticksuffix === "none" )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[showticksuffix]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels ) ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[showexponent]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[exponentformat]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.showexponent === "none" )   ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[minexponent]`)[0].disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.showexponent === "none" )   ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[separatethousands]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[showline]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[linecolor]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showline ) ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[linewidth]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showline ) ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[showgrid]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[gridcolor]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showgrid )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[gridwidth]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showgrid )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[zeroline]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[zerolinecolor]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.zeroline )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[zerolinewidth]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.zeroline )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[side]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickfont][family]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickfont][size]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[tickfont][color]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[title][text]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[title][font][family]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text ) ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[title][font][size]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[title][font][color]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text )  ? true : false
        // document.getElementsByName(`${prefix}__bottomAxis[title][standoff]`)[0].disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text )  ? true : false

        break

    }

    

    return false

  })


  
 

  
}


export default drawChart
