import Plotly from 'plotly.js-dist'
import 'accordion-js/dist/accordion.min.css';
import Annotation from "./Annotation"
import annotationsPanels from "./annotations-panels"

const annotationsHandler =  async( chart, prefix ) => {
 
  const index = chart.layout.annotations === undefined ? 0 : chart.layout.annotations.length
  chart.layout.annotations[index] = Annotation.defaultOptions()
  chart.layout.annotations[index].x = chart.layout.xaxis.range[0] +  (chart.layout.annotations.length )*(( chart.layout.xaxis.range[1] - chart.layout.xaxis.range[0])/10)
  chart.layout.annotations[index].y = ( chart.layout.yaxis.range[1] + chart.layout.yaxis.range[0])/2
  await Plotly.react( `${prefix}__plotlyChart`, chart.traces, chart.layout, chart.config )       
  annotationsPanels( chart, prefix )

  return chart

}

export default annotationsHandler
