import {
  toggleElement,
  showElement,
  hideElement,
  togglePanel,
  displayAdminMessage,
  setChartWidth,
  setDependentFields,
} from "./utilities";
import fetchData from "./fetch-data";
import renderChart from "./render-chart";
import colorPicker from "./color-picker";

const drawChart = async function (target, containers) {

  // Bail if either value is null
  if (! document.getElementById("iwpgv__sheetId").value || ! document.getElementById("iwpgv__chartType").value) {
    return;
  }
  toggleElement('iwpgv__spinner');
  hideElement('iwpgv__dashboard');
  hideElement('iwpgv__warning');

  try {
    // Find form whose submit button was clicked
    const form = target.closest("form");
    if (!form) throw "Can't find a form";

    // Assemble form data
    const formData = new FormData(form);
    formData.append("action", iwpgv_obj.sheet_chart_type_select_action);
    formData.append("nonce", iwpgv_obj.sheet_chart_type_select_nonce);

    // Wait for response
    const jsonRes = await fetchData(formData);

    if (jsonRes && jsonRes.message) {
      displayAdminMessage(jsonRes.message);
    }

    // Render chart on success
    if (jsonRes.status && jsonRes.status === "success") {
      // Append panels
      document.querySelector(".iwpgv .chart-options-panels").innerHTML =
        jsonRes.adminFields;

      // Enable save button
      document.getElementById("iwpgv__save-chart").disabled = false;

      // Get chart options
      let chart = jsonRes.chart;

      // Get chart options
      let chartOptions = chart.chartOptions;
      
      // Set pie chart options 
      if (chart.pieChartOptions) {
        chartOptions = Object.assign(chartOptions, chart.pieChartOptions);
        delete chart.pieChartOptions;
      }

      // Set chart option axes
      chartOptions["hAxis"] = chart.horAxisOptions ? chart.horAxisOptions : {};
      chartOptions["vAxes"] = {};
      chartOptions["vAxes"][0] = chart.leftAxisOptions
        ? chart.leftAxisOptions
        : {};
      chartOptions["vAxes"][1] = chart.rightAxisOptions
        ? chart.rightAxisOptions
        : {};
      chartOptions["series"] = chart.seriesOptions ? {...chart.seriesOptions} : {};
      chartOptions["trendlines"] = chart.trendlinesOptions
        ? chart.trendlinesOptions
        : {};

      // Unset unnecassary options
      delete chart.horAxisOptions;
      delete chart.leftAxisOptions;
      delete chart.rightAxisOptions;
      delete chart.seriesOptions;
      delete chart.trendlinesOptions;

      // Get the width and height of the dashboard container div and set chart width
      chart.chartOptions.width = setChartWidth()
     

       // Set or disable chart area for pie chart
       const chartArea = chart.chartOptions.chartArea
       if ( chart.chartParams.chartType === 'PieChart') {
        const chartAreaBgColor = chart.chartOptions.chartArea.backgroundColor
         for (const option in chartArea) {
           const optionInput = document.getElementById(`iwpgv__chartOptions[chartArea][${option}]`)
           if (optionInput) optionInput.disabled = true
         }
 
         for (const option in chartAreaBgColor) {
           const optionInput = document.getElementById(`iwpgv__chartOptions[chartArea][backgroundColor][${option}]`)
           if (optionInput) optionInput.disabled = true
           if(optionInput.classList.contains('color-picker')) {
             optionInput.classList.remove('color-picker')
           }
         }
         chart.chartOptions.chartArea = null
       } else {
        // Set chart area heigth and width
        chart.chartOptions.chartArea.width = `${chartArea.width}%`;
        chart.chartOptions.chartArea.height = `${chartArea.height}%`;
       }
     
      // Set dependent fields
      const hasDependents = document.querySelectorAll(".hasDependents");
      if (hasDependents) {
        hasDependents.forEach((el) => {
          setDependentFields(el);
        });
      }

      // et chart option series and trendlines colors
      let i = 0;
      while (i < Object.values(chart.chartOptions.series).length) {
        // Series
        const serieColor = document.getElementById(`iwpgv__seriesOptions[series][${i}][color]`);
         if (serieColor) chart.chartOptions.series[i].color = serieColor.value
        
        const seriePointShapeType = document.getElementById( `iwpgv__seriesOptions[series][${i}][pointShape][type]`);
        if (seriePointShapeType) chart.chartOptions.series[i].pointShape.type = seriePointShapeType.value
        
        // Trendlines
        const trendlineColor = document.getElementById(`iwpgv__trendlinesOptions[trendlines][${i}][color]`);
        if (trendlineColor) chart.chartOptions.trendlines[i].color = trendlineColor.value
        
        const trendlinesPointShapeType = document.getElementById(`iwpgv__trendlinesOptions[series][${i}][pointShape][type]`);
        if (trendlinesPointShapeType) chart.chartOptions.trendlines[i].color = trendlinesPointShapeType.value
        
        i++;
      }

      // disable line and pont options when series are enablesd
      const optionsToDisable = [
        'iwpgv__chartOptions[lineWidth]',
        'iwpgv__chartOptions[lineDashStyle]',
        'iwpgv__chartOptions[pointsVisible]',
        'iwpgv__chartOptions[pointShape]',
        'iwpgv__chartOptions[pointSize]',
      ]
      if (chart.chartParams.enableSeries) {
        optionsToDisable.forEach( el => {
          const optionInput = document.getElementById(el)
          if (optionInput) {
            optionInput.disabled = true;
          }
        })
      }

      // Render chart
      let containers = {dashboard: 'iwpgv__dashboard', chart: 'iwpgv__chart', tableChart: "iwpgv__table-chart", numRange: 'iwpgv__num-range-filter', minMaxTableChart: 'iwpgv__min-max-table-chart'}
      renderChart(chart, containers);

      // Set color pickers
      colorPicker();

    }
  } catch (error) {
    const message = `<div class='notice notice-error is-dismissible'><p>${error.message}</p></div>`;
    displayAdminMessage(message);
    console.log(error);
  } finally {
    toggleElement('iwpgv__spinner');
    showElement('iwpgv__dashboard');
    togglePanel("iwpgv__fileUploadPanel");

  }
};

export default drawChart;
