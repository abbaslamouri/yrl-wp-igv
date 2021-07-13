import { fontFamily, colors } from "./utilities"

const chartDefaultOptions = function ( index, name, chartType, x, y  ) {

  switch ( chartType ) {

    case 'scatter':
    case null:
      return {
        type: 'scatter',
        name: name,
        visible: true,
        showlegend: true,
        opacity: 1,
        text: ["pppppp", "TTTTTTTT"],
        textposition: "top center",
        hovertext: ["1111", "222222"],
        hoverinfo: "all",
        textfont: {
          family: "Raleway",
          size: 12,
          color: colors()[index],
        },
        hoverlabel: {
          bgcolor: colors()[index],
          bordercolor : "#000000",
          font: {
            family: "Raleway",
            color: "#FFFFFF",
            size: 12,
          },
          align: "auto",
          namelength: 15,
        },
        mode: "lines+markers+text",
        x: x,
        y: y,
        xaxis: "x",
        yaxis: "y",
        marker: {
          symbol: 0,
          opacity:1,
          size: 6,
          masdisplayed: 0,
          line: {
            width: 0,
            color: "#444444",
          },
          gradient: {
            type: "none",
            color: "#444444"
          },
          color: colors()[index],
          // colorbar:{
          //   x: 1.02
          // }
          colors: [],
        },
      
        line: {
          color: colors()[index],
          width: 2,
          shape: "linear",
          smoothing: 1,
          dash: "solid",
          simplify: true
        },
        error_y: {
          visible: false,
          type: "percent",
          symmetric: false,
          array: [],
          arrayminus: [],
          value: 20,
          valueminus: 10,
          color: colors()[index],
          thickness: 2,
          width: 4
        },
        connectgaps: false,
      }
      break

    case 'pie':
      return {
        type: 'pie',
        name: name,
        title: {
          text: 'Pie title text',
          family: Object.keys(fontFamily())[12],
          size: 12,
          color: colors()[index],
          position:"middle center"
        },
        visible: true,
        showlegend: true,
        opacity: 1,
        values: y,
        labels: x,
        pull: 0,
        text: [],
        textposition: "auto",
        hovertext: "",
        hoverinfo: "all",
        domain: {
          x: [.65,1],
          y: [0,1],
          row: null,
          column: null
        },
        automargin: true,
        marker: {
          color: colors()[index],
          line: {
            width: 0,
            color: "#444444",
          },
        },
        textfont: {
          family: "Raleway",
          color: colors()[index],
          size: 12,
        },
        textinfo: null,
        direction: "counterclockwise",
        hole: 0,
        hoverlabel: {
          bgcolor: colors()[index],
          bordercolor : "#000000",
          align: "auto",
          namelength: 15,
          font: {
            family: "Raleway",
            color: "#FFFFFF",
            size: 12,
          },
        },
        insidetextfont: {
          family: Object.keys(fontFamily())[12],
          size: 16,
          color: "#263238",
        },
        insidetextorientation: "auto",
        outsidetextfont: {
          family: Object.keys(fontFamily())[12],
          size: 16,
          color: "#263238",
        },
        rotation: 0,
        sort:true,
      }
      break

    case 'table':
      return {

        // rounding: ( this.tableConfig.rounding  === undefined ) ? 3 : this.tableConfig.rounding,
        // firstColAlign: ( this.tableConfig.firstColAlign  === undefined ) ? "center" : this.tableConfig.firstColAlign,
        // evenRowColor: ( this.tableConfig.evenRowColor  === undefined ) ? "#b0bec5" : this.tableConfig.evenRowColor,
        // oddRowColor: ( this.tableConfig.oddRowColor  === undefined ) ? "#e2f1f8" : this.tableConfig.oddRowColor,
        type: 'table',
        name: name,
        visible: true,
        hoverinfo: "all",
        domain: {
          x: [.65,1],
          y: [0,1],
          row: null,
          column: null
        },
        cells:{
          values: y,
          prefix: null,
          suffix: null,
          height: 20,
          align : "center",
          line: {
            width: 1,
            color: "#263238",
          },
          fill: {
            color: "#000a12",
          },
          font : {
            family :Object.keys(this.fontFamily())[12],
            size : 10,
            color : "#eeeeee",
          },
        },
        header:{
          values: x,
          prefix: null,
          suffix: null,
          height: 28,
          align : "center",
          line: {
            width: 1,
            color: "#263238",
          },
          fill: {
            color: "#000a12",
          },
          font : {
            family :Object.keys(this.fontFamily())[12],
            size : 10,
            color : "#eeeeee",
          }
        },
        hoverlabel: {
          bgcolor: colors()[index],
          bordercolor : "#000000",
          font: {
            family: "Raleway",
            color: "#FFFFFF",
            size: 12,
          },
          align: "auto",
          namelength: 15,
        },
      }
      break
  }

}

export default chartDefaultOptions





//  // let marker = {}

//     // switch (chartType) {

//     //   case "scatter":
//     //     marker = {
//     //       symbol: 0,
//     //       opacity:1,
//     //       size: 6,
//     //       masdisplayed: 0,
//     //       line: {
//     //         width: 0,
//     //         color: "#444444",
//     //       },
//     //       gradient: {
//     //         type: "none",
//     //         color: "#444444"
//     //       },
//     //       color: colors()[index],
//     //       // colorbar:{
//     //       //   x: 1.02
//     //       // }
//     //       colors: [],

//     //     }
//     //     break

//     //   case "pie":
//     //     marker = {
//     //       colors: [],
//     //       line: {
//     //         color: "#444444",
//     //         width: 0
//     //       },
//     //     }
//     //     break
//     // }

    

//     const commonOptions = {
//       type: null,
//       name: name,
//       visible: true,
//       showlegend: true,
//       opacity: 1,
//       text: ["pppppp", "TTTTTTTT"],
//       textposition: "top center",
//       hovertext: ["1111", "222222"],
//       hoverinfo: "all",
//       textfont: {
//         family: "Raleway",
//         size: 12,
//         color: colors()[index],
//       },
//       hoverlabel: {
//         bgcolor: colors()[index],
//         bordercolor : "#000000",
//         font: {
//           family: "Raleway",
//           color: "#FFFFFF",
//           size: 12,
//         },
//         align: "auto",
//         namelength: 15,
//       },

//       // header: {
//       //   values: tableHeaderValue
//       // },

//       // cells: {
//       //   values: tableCellValue
//       // }

//     }

//     const scatterChartOptions = {
//       mode: "lines+markers+text",
//       x: x,
//       y: y,
//       xaxis: "x",
//       yaxis: "y",
//       marker: {
//         symbol: 0,
//         opacity:1,
//         size: 6,
//         masdisplayed: 0,
//         line: {
//           width: 0,
//           color: "#444444",
//         },
//         gradient: {
//           type: "none",
//           color: "#444444"
//         },
//         color: colors()[index],
//         // colorbar:{
//         //   x: 1.02
//         // }
//         colors: [],

//       },
//       // : {
//       //   symbol: 0,
//       //   opacity:1,
//       //   size: 6,
//       //   masdisplayed: 0,
//       //   line: {
//       //     width: 0,
//       //     color: "#444444",
//       //   },
//       //   gradient: {
//       //     type: "none",
//       //     color: "#444444"
//       //   },
//       //   color: colors()[index],
//       //   // colorbar:{
//       //   //   x: 1.02
//       //   // }
//       // },
//       line: {
//         color: colors()[index],
//         width: 2,
//         shape: "linear",
//         smoothing: 1,
//         dash: "solid",
//         simplify: true
//       },
//       error_y: {
//         visible: false,
//         type: "percent",
//         symmetric: false,
//         array: [],
//         arrayminus: [],
//         value: 20,
//         valueminus: 10,
//         color: colors()[index],
//         thickness: 2,
//         width: 4
//       },
//       connectgaps: false,

     
    
     
     
//     }

//     const pieChartOptions = {
//        // type: chartType,
//       // visible: true,
//       // showlegend: true,
//       // opacity: 1,
//       // text: [],
//       // textfont: {
//       //   family: "Raleway",
//       //   color: colors()[index],
//       //   size: 12,
//       // },
//       // textposition: "auto",
//       // hovertext: "",
//       // hoverinfo: "all",
//       // hoverlabel: {
//       //   bgcolor: colors()[index],
//       //   bordercolor : "#000000",
//       //   align: "auto",
//       //   namelength: 15,
//       //   font: {
//       //     family: "Raleway",
//       //     color: "#FFFFFF",
//       //     size: 12,
//       //   },
//       // },
//       title: {
//         text: "",
//         font: {
//           family: Object.keys(fontFamily())[12],
//           size: 16,
//           color: "#263238",
//         },
//         position: "top center",
//       },
//       values: y,
//       labels: x,
//       pull: 0,
//       domain: {
//         x: [.65,1],
//         y: [0,1],
//         row: null,
//         column: null
//       },
//       automargin: true,
//       // marker: {
//       //   colors: [],
//       //   line: {
//       //     color: "#444444",
//       //     width: 0
//       //   },
//       // },
     
//       textinfo: null,
//       direction: "counterclockwise",
//       hole: 0,
     
//       insidetextfont: {
//         font: {
//           family: Object.keys(fontFamily())[12],
//           size: 16,
//           color: "#263238",
//         },
//       },
//       insidetextorientation: "auto",
//       outsidetextfont: {
//         font: {
//           family: Object.keys(fontFamily())[12],
//           size: 16,
//           color: "#263238",
//         },
//       },
//       rotation: 0,
//       sort:true,
//     }

//     const tableChartOptions = {

//       // rounding: ( this.tableConfig.rounding  === undefined ) ? 3 : this.tableConfig.rounding,
//       // firstColAlign: ( this.tableConfig.firstColAlign  === undefined ) ? "center" : this.tableConfig.firstColAlign,
//       // evenRowColor: ( this.tableConfig.evenRowColor  === undefined ) ? "#b0bec5" : this.tableConfig.evenRowColor,
//       // oddRowColor: ( this.tableConfig.oddRowColor  === undefined ) ? "#e2f1f8" : this.tableConfig.oddRowColor,
//       type: 'table',
//       name: name,
//       visible: true,
//       hoverinfo: "all",
//       domain: {
//         x: [.65,1],
//         y: [0,1],
//         row: null,
//         column: null
//       },
//       cells:{
//         values: tableCellValue,
//         prefix: null,
//         suffix: null,
//         height: 20,
//         align : "center",
//         line: {
//           width: 1,
//           color: "#263238",
//         },
//         fill: {
//           color: "#000a12",
//         },
//         font : {
//           family :Object.keys(this.fontFamily())[12],
//           size : 10,
//           color : "#eeeeee",
//         },
//       },
//       header:{
//         values: tableHeaderValue,
//         prefix: null,
//         suffix: null,
//         height: 28,
//         align : "center",
//         line: {
//           width: 1,
//           color: "#263238",
//         },
//         fill: {
//           color: "#000a12",
//         },
//         font : {
//           family :Object.keys(this.fontFamily())[12],
//           size : 10,
//           color : "#eeeeee",
//         }
//       },
//       hoverlabel: {
//         bgcolor: colors()[index],
//         bordercolor : "#000000",
//         font: {
//           family: "Raleway",
//           color: "#FFFFFF",
//           size: 12,
//         },
//         align: "auto",
//         namelength: 15,
//       },
      
//     }

//     // switch (chartType) {

