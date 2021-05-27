import Plotly from 'plotly.js-dist'
import renderPanels from "./render-panels"
import renderChart from "./render-chart"
import { setSheetIdOptions, showInputField, showElementById, hideElementById, getMinMaxAvgData, fetchMinMaxAvgTableChartData, chartOptionKey } from "./utilities"

const drawChart = async( chart, spreadsheet, iwpgvObj ) => {

  // Hide chart and table charts
  Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
  Plotly.purge(`${iwpgvObj.prefix}__plotlyMinMaxAvgTable`)

  // Hide min/max inputs if visible
  // hideElementById( `${iwpgvObj.prefix}__plotMinMaxAvg` )

  // // remove layout panel toggle and panel
  // document.querySelector(`#${iwpgvObj.prefix}__chartLayoutPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__chartTracesPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__tableChartPanel .accordion`).innerHTML = ""
  // document.querySelector(`#${iwpgvObj.prefix}__minMaxAvgTableChartPanel .accordion`).innerHTML = ""

  // // Hide Min/Max/Avg accordion toggle and content
  // document.querySelector( `.accordion__toggle.minMaxAvgTableChart.panel` ).classList.add("hidden")
  // document.querySelector( `.accordion__content.minMaxAvgTableChart.panel` ).classList.add("hidden")

  // Render panels
  renderPanels( chart, spreadsheet, iwpgvObj )

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${iwpgvObj.prefix}__saveChart` ).disabled = false
  document.getElementById( `${iwpgvObj.prefix}__saveChart` ).classList.remove("hidden")
  
  // Render chart
  await renderChart( iwpgvCharts, iwpgvObj, spreadsheet)

  // // Add range slider event handler
  // eval(`${iwpgvObj.prefix}__plotlyChart`).on('plotly_relayout',function(eventData){

  //   // Bail if the event is other that range slider
  //   if ( ! eventData['xaxis.range'] || ! chart.chartParams.options.enableMinMaxTableChart ) return

  //   console.log("PPPPPP",eventData)
  //   //
  //   // const xAxisMin = ( eventData && eventData['xaxis.range'] ) ? eventData['xaxis.range'][0] : Math.min( ...spreadsheet[chart.chartParams.options.sheetId].data[0])
  //   // const xAxisMax = ( eventData  && eventData['xaxis.range'] ) ? eventData['xaxis.range'][1] : Math.max(...spreadsheet[chart.chartParams.options.sheetId].data[0])
  //   document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value = eventData['xaxis.range'][0] //parseFloat(xAxisMin).toFixed(chart.minMaxAvgTableChart.options.rounding)
  //   document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value = eventData['xaxis.range'][1] //parseFloat(xAxisMax).toFixed(chart.minMaxAvgTableChart.options.rounding)
    
  //   // Update Min/Max/Avg table data
  //   // chart.minMaxAvgTableChart.options.cells.values = getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])

  //   Plotly.restyle( `${iwpgvObj.prefix}__plotlyMinMaxAvgTable`, { "cells.values": [getMinMaxAvgData(chart, spreadsheet, eventData['xaxis.range'][0], eventData['xaxis.range'][1])] } )

  // })


  document.addEventListener("change", async function (event) {
  
    event.preventDefault()

    switch( event.target.id ){

      case `${iwpgvObj.prefix}__rangeMinInput`:
      case `${iwpgvObj.prefix}__rangeMaxInput`:
        const xAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value
        const xAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value
        if (parseFloat(xAxisMin) < parseFloat(xAxisMax) ) {
          Plotly.relayout(`${iwpgvObj.prefix}__plotlyChart`, { 'xaxis.range': [xAxisMin, xAxisMax] })
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

          case "chartLayout":

            if ( key.includes( "config" ) ) {
              Plotly.purge(`${iwpgvObj.prefix}__plotlyChart`)
            
              chart.chartLayout.options.config[key.split(".")[1]] = value
              Plotly.plot( `${iwpgvObj.prefix}__plotlyChart`, Object.values(chart.chartTraces.options), chart.chartLayout.options, chart.chartLayout.options.config )
            } else {
              switch(key) {
                case "xaxis.range":
                  value = "" === value ? [] : value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )
                  break
                case "xaxis.autorange":
                  value = "false" === value ? false : "true" === value ? true : value
                  if ( true === value ){
                    chart.chartLayout.options.xaxis.range = []
                    document.getElementById(`${iwpgvObj.prefix}__chartLayout[xaxis][range]`).value = ""
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
              Plotly.relayout( `${iwpgvObj.prefix}__plotlyChart`, { [key]: value })

            }

            console.log("CRT", chart.chartLayout.options.hoverlabel)

            const layout = chart.chartLayout.options
            const layoutInputIdPrefix = `${iwpgvObj.prefix}__chartLayout`

            // Plot title
            document.getElementById(`${layoutInputIdPrefix}[title][font][family]`).disabled = ! layout.title.text  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[title][font][size]`).disabled = ! layout.title.text  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[title][font][color]`).disabled = ! layout.title.text  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[title][x]`).disabled = ! layout.title.text  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[title][y]`).disabled = ! layout.title.text  ? true : false

            // Legend
            document.getElementById(`${layoutInputIdPrefix}[legend][bgcolor]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][bordercolor]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][borderwidth]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][font][family]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][font][size]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][font][color]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][title][text]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][title][font][family]`).disabled = ( ! layout.showlegend || ! layout.legend.title.text ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][title][font][size]`).disabled = ( ! layout.showlegend || ! layout.legend.title.text ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][title][font][color]`).disabled = ( ! layout.showlegend || ! layout.legend.title.text ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][title][side]`).disabled = ( ! layout.showlegend || ! layout.legend.title.text ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][orientation]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][itemsizing]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][itemwidth]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][itemclick]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][itemdoubleclick]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][x]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][y]`).disabled = ! layout.showlegend ? true : false
            document.getElementById(`${layoutInputIdPrefix}[legend][valign]`).disabled = ! layout.showlegend ? true : false


            // Hover Label
            document.getElementById(`${layoutInputIdPrefix}[hoverlabel][bgcolor]`).disabled = ! layout.hovermode ? true : false
            document.getElementById(`${layoutInputIdPrefix}[hoverlabel][bordercolor]`).disabled = ! layout.hovermode ? true : false
            document.getElementById(`${layoutInputIdPrefix}[hoverlabel][align]`).disabled = ! layout.hovermode ? true : false
            document.getElementById(`${layoutInputIdPrefix}[hoverlabel][namelength]`).disabled = ! layout.hovermode ? true : false
            document.getElementById(`${layoutInputIdPrefix}[hoverlabel][font][family]`).disabled = ! layout.hovermode ? true : false
            document.getElementById(`${layoutInputIdPrefix}[hoverlabel][font][size]`).disabled = ! layout.hovermode ? true : false
            document.getElementById(`${layoutInputIdPrefix}[hoverlabel][font][color]`).disabled = ! layout.hovermode ? true : false

            // Modebar
            document.getElementById(`${layoutInputIdPrefix}[modebar][orientation]`).disabled = ! layout.config.displayModeBar ? true : false
            document.getElementById(`${layoutInputIdPrefix}[modebar][bgcolor]`).disabled = ! layout.config.displayModeBar ? true : false
            document.getElementById(`${layoutInputIdPrefix}[modebar][color]`).disabled = ! layout.config.displayModeBar ? true : false
            document.getElementById(`${layoutInputIdPrefix}[modebar][activecolor]`).disabled = ! layout.config.displayModeBar ? true : false
            document.getElementById(`${layoutInputIdPrefix}[config][displaylogo]`).disabled = ! layout.config.displayModeBar ? true : false
           
            // X-Axis
            document.getElementById(`${layoutInputIdPrefix}[xaxis][type]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][autotypenumbers]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][autorange]`).disabled = ( ! layout.xaxis.visible )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][rangemode]`).disabled = ( ! layout.xaxis.visible || false === layout.xaxis.autorange || layout.xaxis.type !== "linear" )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][range]`).disabled = ( ! layout.xaxis.visible || true === layout.xaxis.autorange ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][fixedrange]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][scaleanchor]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][ticks]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickmode]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === "" ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][nticks]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === "" || layout.xaxis.tickmode !== "auto" )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tick0]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === ""|| layout.xaxis.tickmode !== "linear"  )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][dtick]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === ""|| layout.xaxis.tickmode !== "linear"  )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickvals]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === "" || layout.xaxis.tickmode !== "array"  )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][ticktext]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === "" || layout.xaxis.tickmode !== "array"  )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][ticklabelposition]`).disabled = ( ! layout.xaxis.visible|| ! layout.xaxis.showticklabels )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][mirror]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][ticklen]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === "" )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickwidth]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === "" )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickcolor]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.ticks === "" )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][showticklabels]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][automargin]`).disabled = ! layout.xaxis.visible  ? true : false
            // document.getElementById(`${layoutInputIdPrefix}[xaxis][showspikes]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][spikecolor]`).disabled = ! layout.xaxis.showspikes  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][spikethickness]`).disabled = ! layout.xaxis.showspikes  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][spikedash]`).disabled = ! layout.xaxis.showspikes  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][spikemode]`).disabled = ! layout.xaxis.showspikes  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickangle]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickprefix]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels || layout.xaxis.showtickprefix === "none" )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][showtickprefix]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][ticksuffix]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels || layout.xaxis.showticksuffix === "none" )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][showticksuffix]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][showexponent]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][exponentformat]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.showexponent === "none" )   ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][minexponent]`).disabled = ( ! layout.xaxis.visible || layout.xaxis.showexponent === "none" )   ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][separatethousands]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][showline]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][linecolor]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showline ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][linewidth]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showline ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][showgrid]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][gridcolor]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showgrid )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][gridwidth]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showgrid )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][zeroline]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][zerolinecolor]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.zeroline )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][zerolinewidth]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.zeroline )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][side]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickfont][family]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickfont][size]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][tickfont][color]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.showticklabels )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][title][text]`).disabled = ! layout.xaxis.visible  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][title][font][family]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.title.text ) ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][title][font][size]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.title.text )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][title][font][color]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.title.text )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][title][standoff]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.title.text )  ? true : false


            // Range Slider
            document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][thickness]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][bgcolor]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][borderwidth]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[xaxis][rangeslider][bordercolor]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false
            document.getElementById(`${layoutInputIdPrefix}[showMinMaxAvgTable]`).disabled = ( ! layout.xaxis.visible || ! layout.xaxis.rangeslider.visible )  ? true : false

            break



          case "chartTraces":

            const keyArr = key.split(".")
            const traceNumber = keyArr.shift()
            const optionKey = keyArr.join(".")
            console.log("OPT", optionKey, traceNumber)

            if ( optionKey === "visible"  ) {
              value = value === "true" ? true : value === "false" ? false : value
            } else if ( optionKey === "error_y.array" || optionKey === "error_y.arrayminus" ){
              value = value === "" ? value : [value.split(",").map( ( item ) => { return parseFloat( item )} )]
              chart.chartTraces.options[traceNumber][optionKey] = value
            }

            Plotly.restyle(`${iwpgvObj.prefix}__plotlyChart`, { [optionKey]: value}, traceNumber)
            console.log("TRACES",chart.chartTraces.options[traceNumber])

            const trace = chart.chartTraces.options[traceNumber]
            const traceInputIdPrefix = `${iwpgvObj.prefix}__chartTraces[${traceNumber}]`           

            document.getElementById(`${traceInputIdPrefix}[xaxis]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[yaxis]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[mode]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[name]`).disabled = ( true !== trace.visible || trace.showlegend ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[opacity]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[showlegend]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][symbol]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][size]`).disabled = (true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][opacity]`).disabled = (true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][color]`).disabled = (true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][maxdisplayed]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][line][width]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][line][color]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][gradient][type]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[marker][gradient][color]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "markers" )  ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[line][shape]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[line][width]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[line][color]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[line][dash]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[line][smoothing]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[line][simplify]`).disabled = ( true !== trace.visible || ! trace.mode.includes( "lines" ) ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[text]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[textposition]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[textfont][family]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[textfont][color]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[textfont][size]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hovertext]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverinfo]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverlabel][bgcolor]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverlabel][bordercolor]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverlabel][align]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverlabel][namelength]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverlabel][font][family]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverlabel][font][size]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[hoverlabel][font][color]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][visible]`).disabled = ( true !== trace.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][type]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][symmetric]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible  ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][value]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type === "data" ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][valueminus]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type === "data" || trace.error_y.symmetric ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][array]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type !== "data" ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][arrayminus]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type !== "data"  || trace.error_y.symmetric ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][color]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][thickness]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[error_y][width]`).disabled = ( true !== trace.visible  || ! trace.error_y.visible ) ? true : false
            document.getElementById(`${traceInputIdPrefix}[connectgaps]`).disabled = ( true !== trace.visible  ) ? true : false

            break
            

            

          case "tableChart":
          case "minMaxAvgTableChart":
            const plotlyTable = ( control === "tableChart" ) ? `${iwpgvObj.prefix}__plotlyTable` : ( control === "minMaxAvgTableChart" ) ? `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` : null
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
                if ( plotlyTable === `${iwpgvObj.prefix}__plotlyMinMaxAvgTable` ) {
                  const xAxisMin = document.getElementById(`${iwpgvObj.prefix}__rangeMinInput`).value
                  const xAxisMax = document.getElementById(`${iwpgvObj.prefix}__rangeMaxInput`).value
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
        break

    }

    return false

  })


  
 

  
}


export default drawChart
