import Accordion from "./accordion";
import mediaUploader from "./media-uploader";
import renderPanels from "./panels";
import drawChart from "./draw-chart";
import saveChart from "./save-chart";
import listCharts from "./list-charts";
import "../sass/admin.scss";

console.log("here")

// http://sandbox/wp-content/uploads/annotations.csv


  Plotly.d3.csv("http://sandbox/wp-content/uploads/Ge-8000-12000.csv", function(data){ processData(data) } );



function processData(allRows) {

  let cols = []
  let labels = []

  let k = 0
  for (const property in allRows[0]) {
      cols[k] = []
      labels[k] = property
      k++
   }

   console.log(cols)
   console.log(labels)

  // for (var j=0; j<Object.keys(allRows[0]).length; j++) {
  //   cols[j] = []
  // }

  

  for (var j=0; j<Object.keys(allRows[0]).length; j++) {
  
  
    

    for (var i=0; i<allRows.length; i++) {
      var row = allRows[i];
      let k = 0
      for (const property in row) {
        if (k==j) {
          cols[k].push(row[property])
          break
        }
        k++
        // console.log(`${property}: ${row[property]}`);
      }
      
     
    }


  }
  console.log("COL", cols)
  let  traces = []

  for (var j=1; j<Object.keys(allRows[0]).length; j++) {
    traces.push({  x : cols[0], y : cols[j], mode: 'lines', name: labels[j]})
  
  }
  console.log("Trc", traces)

  Plotly.newPlot('plotly', traces,
  {title: 'Plotting CSV data from AJAX call'});
  



  // console.log( 'X',x, 'Y',y, 'SD',standard_deviation );
  // makePlotly( x, y, standard_deviation );
}








const iwpgvCharts = typeof yrl_wp_igv_charts !== "undefined" ?  yrl_wp_igv_charts : {}

const iwpgvObj = typeof yrl_wp_igv_obj !== "undefined" ? yrl_wp_igv_obj : {}
const prefix = iwpgvObj.prefix ? iwpgvObj.prefix : ""


console.log("OBJ", iwpgvObj)
console.log("CHART", iwpgvCharts)



// Load the Visualization API and the controls package.
google.charts.load("current", { packages: ["corechart", "controls"] });

// Render accordion panels
if (typeof iwpgvCharts !== "undefined") {
  
  // Add new or edit an existing chart
  if (iwpgvCharts.action && iwpgvCharts.action === "editChart") {
    renderPanels(iwpgvCharts.panels);
  }

  // List all charts
  if (iwpgvCharts.action && iwpgvCharts.action === "listCharts") {
    listCharts(iwpgvCharts.payload)
  }
}

// Add click event listener
document.addEventListener("click", function (event) {
  
  // file uploader event listener
  if (event.target.id === `${prefix}__chartParams[mediaUploadBtn]`) {
    event.preventDefault();
    mediaUploader();
  }

  // save chart
  if (event.target.id === `${prefix}__saveChart`) {
    event.preventDefault();
    saveChart();
  }


});

// Add change event listener to all inputs with class containg prefix__chartParams
document.addEventListener("change", async function (event) {
  // Reset admin messages
  const adminMessages = document.querySelector(`.${prefix} .admin-messages`);
  if (adminMessages) adminMessages.innerHTML = "";

  if (event.target.classList.contains(`${prefix}__chartParams`)) {
    event.preventDefault();
    drawChart(event.target);
  }
});

// Load accordion
// const accordions = document.querySelectorAll(".accordion");
new Accordion({ collapsed: false });
// console.log(accordion);
// accordion.accordion();
