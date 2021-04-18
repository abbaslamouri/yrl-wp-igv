import renderChart from "./render-chart";
import datatable from "./datatable";

const listCharts = async function (containers) {
  console.log(iwpgv_charts);

  Object.values(iwpgv_charts.payload).forEach(async (el) => {
    console.log(el);

    const chartOptions = el.chartOptions;
    chartOptions.width = '100%'
    chartOptions.height = 200;
    chartOptions.legend.position = 'none'
    chartOptions.title= null
    chartOptions.backgroundColor.strokeWidth = 2;

    const chart = {};
    chart.chartOptions = el.chartOptions;
    chart.chartParams = el.chartParams;

    if (chart.chartParams.chartType !== 'PieChart') {
      chartOptions.chartArea.top = 40;
      chartOptions.chartArea.left = 60;
      chartOptions.chartArea.width = null;
      chartOptions.chartArea.height = null;
      chartOptions.chartArea.backgroundColor.strokeWidth = 1;
    }

    const containers = {...containers}
    containers.chart = `iwpgv__chart__${el.chartParams.chartId}`
      renderChart(chart, containers, {})
    // chart.chartOptions.series[0].visibleInLegend = false
    // Get the width and height of the dashboard container div and set chart width and height to 75% of the container div.
    // const chartList = document.querySelector(".iwpgv .chart-list");
    // const chartListBox = chartList.getBoundingClientRect();
    // const chartCount = Object.keys(iwpgv_charts.payload).length;
    // chartOptions.width = Math.round((0.6 * chartListBox.width) / 3);
    // chartOptions.height = chartOptions.width;


      // // Set a callback to run when the Google Visualization API is loaded.
      // google.charts.setOnLoadCallback(drawDashboard);
    
      // // instantiates a dashboard, a range slider and a pie chart,
      // function drawDashboard() {
      //   //Create our data table.
      //   const dataTable = datatable(chart.chartParams.sheet);
  
      //     // Draw chart
      //     const iwpgvChart = new google.visualization.ChartWrapper({
      //       dataTable: dataTable,
      //       chartType: chart.chartParams.chartType,
      //       containerId: ,
      //       options: chart.chartOptions,
      //     });
      //     iwpgvChart.draw();
        
      // };





   
    // Render chart
    // renderChart(chart, xx, {});

    // renderChart(
    //   el.sheet,
    //   el.chartParams.chartType,
    //   chartOptions,
    //   `iwpgv__chart__${el.chartParams.chartId}`
    // );
  });
};

export default listCharts;
