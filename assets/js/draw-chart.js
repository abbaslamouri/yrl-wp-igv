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
  

  // // Create mainaccordion and open first panel
  // const mainAccordion = new Accordion( `.${prefix}__admin .main__Acc`, { duration: 400 })
  // // Close all accordion panels
  // // mainAccordion.closeAll()

  // console.log("ACC", mainAccordion)


  // new Accordion( `.${prefix}__admin .xaxis__Acc`, { duration: 400 })
  // new Accordion( `.${prefix}__admin .xaxis2__Acc`, { duration: 400 })
  // new Accordion( `.${prefix}__admin .yaxis__Acc`, { duration: 400 })
  // new Accordion( `.${prefix}__admin .yaxis2__Acc`, { duration: 400 })

  

  // Enable save button  // Add click event listener to the chart params panel inoput fields
  document.getElementById( `${prefix}__saveChart` ).disabled = false
  document.getElementById( `${prefix}__saveChart` ).classList.remove("hidden")
  
  // Render chart
  await renderChart( chart, spreadsheet, prefix )
  

  // Render panels
  // renderPanels( chart, spreadsheet, prefix )



  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .layoutAc`).classList.remove( "hidden" )
  // document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .configAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .tracesAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .titleAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .legendAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .hoverlabelAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .modebarAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxisAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .xaxis2Ac`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxisAc`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .yaxis2Ac`).classList.remove( "hidden" )
  document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .main__Accordion .minMaxAvgTableAc`).classList.remove( "hidden" )

  document.querySelector( `.${prefix}__admin .loading` ).classList.add("hidden")



  document.addEventListener("input", async function (event) {
  
    event.preventDefault()

    // console.log ("EVENT",event.target.name)
    if (  event.target.name.includes("__fileUpload") ) return

    console.log(event.target.name)

    if ( event.target.name === `${prefix}__rangeMinInput` || event.target.name === `${prefix}__rangeMaxInput` ){
      const xaxisMin = document.getElementsByName(`${prefix}__rangeMinInput`)[0].value ? document.getElementsByName(`${prefix}__rangeMinInput`)[0].value : chart.layout.xaxis.range[0]
      const xaxisMax = document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value ? document.getElementsByName(`${prefix}__rangeMaxInput`)[0].value : chart.layout.xaxis.range[1]
      console.log(xaxisMax, xaxisMin)
      console.log("JJJJJJJJ")
      // if ( parseFloat( xaxisMin ) >= parseFloat( xaxisMax ) ) return
      Plotly.relayout(`${prefix}__plotlyChart`, { 'xaxis.range': [ parseFloat( xaxisMin ), parseFloat( xaxisMax )] })
      console.log("JJJJJJJJxxxxxxx")

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

          let update = {}

          switch(key) {

            case "xaxis.visible":
              if ( value ){
                update = {["xaxis.visible"]: true}
              } else {
                update = {["xaxis.visible"]: false}
              }
              Plotly.relayout( `${prefix}__plotlyChart`, update)
              document.getElementById(`${prefix}__layout[xaxis2][visible]`).checked = value
              break

            case "xaxis2.visible":
              if ( value ){
                update = {["xaxis2.visible"]: true}
              } else {
                update = {["xaxis2.visible"]: false}
              }
              Plotly.relayout( `${prefix}__plotlyChart`, update)
              document.getElementById(`${prefix}__layout[xaxis][visible]`).checked = value
              break

            case "xaxis.autorange":
              value = "false" === value ? false : "true" === value ? true : value
              if ( false !== value ){
                update = { ["xaxis.autorange"]: value, ["xaxis.range"]: null }
                chart.layout.xaxis.range = []
                Plotly.relayout( `${prefix}__plotlyChart`, update)
              } else {
                update = { ["xaxis.autorange"]: false }
                Plotly.relayout( `${prefix}__plotlyChart`, update)
                // document.getElementById(`${prefix}__layout[xaxis][range]`).value = null
              }
              document.getElementById(`${prefix}__layout[xaxis][range]`).value = chart.layout.xaxis.range.join()

              break

            case "xaxis.range":
              // value = value.split(",").map( ( item ) => { return parseFloat( item ) } )
              // update = { ["xaxis.range"]: value}
              // Plotly.relayout( `${prefix}__plotlyChart`, update)

              console.log("MMMMMM",chart.layout.xaxis.autorange, chart.layout.xaxis.range)
             
              if (value) {
                value = value.split(",").map( ( item ) => { return parseFloat( item ) } )
                update = { ["xaxis.range"]: value}
              } else {
                update = { ["xaxis.range"]: null, "xaxis.autorange": true}
              }
              Plotly.relayout( `${prefix}__plotlyChart`, update)
              document.getElementById(`${prefix}__layout[xaxis][autorange]`).value = true === chart.layout.xaxis.autorange ? "true" : false === chart.layout.xaxis.autorange ? "false" : chart.layout.xaxis.autorange 
              break

            // case "title.y":
              // value = "" === value ? "auto" : value
              // update = { [key]: value }
              // // Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
              // break

            // case "legend.itemclick":
            // case "legend.itemdoubleclick":
            // // case "hovermode":
            //   value = "false" === value ? false : value
            //   update = { [key]: value }
              // break

            default:
              update = { [key]: value }
              Plotly.relayout( `${prefix}__plotlyChart`, { [key]: value })
 
          }

          // Plotly.relayout( `${prefix}__plotlyChart`, update)
          console.log("Layout", chart)

          // aaxis
          document.getElementById(`${prefix}__layout[xaxis][type]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          document.getElementById(`${prefix}__layout[xaxis][side]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          document.getElementById(`${prefix}__layout[xaxis][autotypenumbers]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          document.getElementById(`${prefix}__layout[xaxis][autorange]`).disabled = ( ! chart.layout.xaxis.visible )  ? true : false
          document.getElementById(`${prefix}__layout[xaxis][fixedrange]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          document.getElementById(`${prefix}__layout[xaxis][rangemode]`).disabled = ( ! chart.layout.xaxis.visible || false === chart.layout.xaxis.autorange || chart.layout.xaxis.type !== "linear" )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][range]`).disabled = ( ! chart.layout.xaxis.visible || true === chart.layout.xaxis.autorange ) ? true : false
          document.getElementById(`${prefix}__layout[xaxis][mirror]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          document.getElementById(`${prefix}__layout[xaxis][automargin]`).disabled = ! chart.layout.xaxis.visible  ? true : false

          // // document.getElementById(`${prefix}__layout[xaxis][scaleanchor]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][ticks]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickmode]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" ) ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][nticks]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" || chart.layout.xaxis.tickmode !== "auto" )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tick0]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === ""|| chart.layout.xaxis.tickmode !== "linear"  )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][dtick]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === ""|| chart.layout.xaxis.tickmode !== "linear"  )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickvals]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" || chart.layout.xaxis.tickmode !== "array"  )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][ticktext]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" || chart.layout.xaxis.tickmode !== "array"  )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][ticklabelposition]`).disabled = ( ! chart.layout.xaxis.visible|| ! chart.layout.xaxis.showticklabels )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][ticklen]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickwidth]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickcolor]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.ticks === "" )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][showticklabels]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // // document.getElementById(`${prefix}__layout[xaxis][showspikes]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][spikecolor]`).disabled = ! chart.layout.xaxis.showspikes  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][spikethickness]`).disabled = ! chart.layout.xaxis.showspikes  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][spikedash]`).disabled = ! chart.layout.xaxis.showspikes  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][spikemode]`).disabled = ! chart.layout.xaxis.showspikes  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickangle]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickprefix]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels || chart.layout.xaxis.showtickprefix === "none" )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][showtickprefix]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][ticksuffix]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels || chart.layout.xaxis.showticksuffix === "none" )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][showticksuffix]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels ) ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][showexponent]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][exponentformat]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.showexponent === "none" )   ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][minexponent]`).disabled = ( ! chart.layout.xaxis.visible || chart.layout.xaxis.showexponent === "none" )   ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][separatethousands]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][showline]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][linecolor]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showline ) ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][linewidth]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showline ) ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][showgrid]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][gridcolor]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showgrid )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][gridwidth]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showgrid )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][zeroline]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][zerolinecolor]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.zeroline )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][zerolinewidth]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.zeroline )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickfont][family]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickfont][size]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][tickfont][color]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.showticklabels )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][title][text]`).disabled = ! chart.layout.xaxis.visible  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][title][font][family]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text ) ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][title][font][size]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][title][font][color]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text )  ? true : false
          // document.getElementById(`${prefix}__layout[xaxis][title][standoff]`).disabled = ( ! chart.layout.xaxis.visible || ! chart.layout.xaxis.title.text )  ? true : false

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

            case "text":
            case "hovertext":
              if (value.includes(",")) {
                value = [value.toString().split(",").map( ( item ) => { return item } )]
              }
            break
            case "error_y.array":
            case "error_y.arrayminus":
              if (value.includes(",")) {
                value = [value.toString().split(",").map( ( item ) => { return parseFloat( item ) } )]
                console.log(value)
              }
            break

            default:

              break
          }

          Plotly.restyle(`${prefix}__plotlyChart`, { [optionKey]: value}, traceNumber)

          const trace =chart.traces[traceNumber]

          // Basic Options
          document.getElementById(`${prefix}__traces[${traceNumber}][showlegend]`).disabled = false === trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][mode]`).disabled =  false === trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][name]`).disabled =  false === trace.visible || ! trace.showlegend  ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][xaxis]`).disabled =  false === trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][yaxis]`).disabled =  false === trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][opacity]`).disabled =  false === trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][connectgaps]`).disabled =  false === trace.visible ? true : false

          // Markers
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][symbol]`).disabled = false === trace.visible || ! trace.mode.includes( "markers" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][size]`).disabled = false === trace.visible || ! trace.mode.includes( "markers" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][opacity]`).disabled = false === trace.visible || ! trace.mode.includes( "markers" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][color]`).disabled = false === trace.visible || ! trace.mode.includes( "markers" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][line][width]`).disabled = false === trace.visible || ! trace.mode.includes( "markers" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][line][color]`).disabled = false === trace.visible || ! trace.mode.includes( "markers" ) ||  parseInt(trace.marker.line.width) === 0 ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][gradient][type]`).disabled = false === trace.visible || ! trace.mode.includes( "markers" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][gradient][color]`).disabled = false === chart.traces.visible || ! trace.mode.includes( "markers" ) || trace.marker.gradient.type === "none" ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][marker][maxdisplayed]`).disabled = true !== trace.visible || ! trace.mode.includes( "markers" ) ? true : false

          // Lines
          document.getElementById(`${prefix}__traces[${traceNumber}][line][shape]`).disabled = true !== trace.visible || ! trace.mode.includes( "lines" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][line][width]`).disabled = false === trace.visible || ! trace.mode.includes( "lines" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][line][color]`).disabled = false === trace.visible || ! trace.mode.includes( "lines" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][line][dash]`).disabled = false === trace.visible || ! trace.mode.includes( "lines" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][line][smoothing]`).disabled = true !== trace.visible || ! trace.mode.includes( "lines" ) ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][line][simplify]`).disabled = true !== trace.visible || ! trace.mode.includes( "lines" ) ? true : false

          // Text
          document.getElementById(`${prefix}__traces[${traceNumber}][text]`).disabled = true !== trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][textposition]`).disabled = true !== trace.visible || ! trace.text ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][textfont][family]`).disabled = true !== trace.visible|| ! trace.text ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][textfont][color]`).disabled = true !== trace.visible|| ! trace.text ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][textfont][size]`).disabled = true !== trace.visible|| ! trace.text ? true : false

          // Hoverlabel
          document.getElementById(`${prefix}__traces[${traceNumber}][hovertext]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverinfo]`).disabled = true !== trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverlabel][bgcolor]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverlabel][bordercolor]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverlabel][align]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverlabel][namelength]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverlabel][font][family]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverlabel][font][size]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][hoverlabel][font][color]`).disabled = true !== trace.visible || "skip" === trace.hoverinfo || "none" === trace.hoverinfo ? true : false

          // Error_y
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][visible]`).disabled = true !== trace.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][type]`).disabled = true !== trace.visible  || ! trace.error_y.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][symmetric]`).disabled = true !== trace.visible  || ! trace.error_y.visible  ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][value]`).disabled = true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type === "data" ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][valueminus]`).disabled = true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type === "data" || trace.error_y.symmetric ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][array]`).disabled = true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type !== "data" ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][arrayminus]`).disabled = true !== trace.visible  || ! trace.error_y.visible || trace.error_y.type !== "data"  || trace.error_y.symmetric ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][color]`).disabled = true !== trace.visible  || ! trace.error_y.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][thickness]`).disabled = true !== trace.visible  || ! trace.error_y.visible ? true : false
          document.getElementById(`${prefix}__traces[${traceNumber}][error_y][width]`).disabled = true !== trace.visible  || ! trace.error_y.visible ? true : false

          break

          // case "config":
          //   Plotly.purge(`${prefix}__plotlyChart`)
          //   chart.config[key] = value
          //   Plotly.plot( `${prefix}__plotlyChart`, Object.values(chart.traces), chart.layout, chart.config )
          //   break
      }

    }

    // Legend
    // document.getElementsByName(`${prefix}__layout[legend][bgcolor]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][bordercolor]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][borderwidth]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][font][family]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][font][size]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][font][color]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][title][text]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][title][font][family]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][title][font][size]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][title][font][color]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][title][side]`)[0].disabled = ( ! chart.layout.showlegend || ! chart.layout.legend.title.text ) ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][orientation]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][itemsizing]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][itemwidth]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][itemclick]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][itemdoubleclick]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][x]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][y]`)[0].disabled = ! chart.layout.showlegend ? true : false
    // document.getElementsByName(`${prefix}__layout[legend][valign]`)[0].disabled = ! chart.layout.showlegend ? true : false

    // xaxis
    // document.getElementsByName(`${prefix}__layout[xaxis][type]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false
    // document.getElementsByName(`${prefix}__layout[xaxis][range]`)[0].disabled = ! chart.layout.xaxis.visible || chart.layout.xaxis.autorange  ? true : false
    // document.getElementsByName(`${prefix}__layout[xaxis][autorange]`)[0].disabled = ! chart.layout.xaxis.visible  ? true : false

    // modebar
    // document.getElementsByName(`${prefix}__config[displaylogo]`)[0].disabled = ! chart.config.displayModeBar  ? true : false
    // document.getElementsByName(`${prefix}__layout[modebar][bgcolor]`)[0].disabled = ! chart.config.displayModeBar   ? true : false
    // document.getElementsByName(`${prefix}__layout[modebar][orientation]`)[0].disabled = ! chart.config.displayModeBar   ? true : false
    // document.getElementsByName(`${prefix}__layout[modebar][color]`)[0].disabled = ! chart.config.displayModeBar   ? true : false
    // document.getElementsByName(`${prefix}__layout[modebar][activecolor]`)[0].disabled = ! chart.config.displayModeBar   ? true : false

    console.log(chart)


    return false

  })
  
}


export default drawChart
