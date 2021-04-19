import {
  toggleElement,
  hideElement,
  showElement,
  setSheetId,
  setChartTypeId,
  displayAdminMessage
} from "./utilities";
import fetchData from "./fetch-data";
import renderChart from "./render-chart";

// const iwpgvCharts = yrl_wp_igv_charts
// const iwpgvObj = yrl_wp_igv_obj
// const iwpgvObj.prefix = iwpgvObj.iwpgvObj.prefix

const listCharts = async function ( iwpgvCharts, iwpgvObj) {

  const payload = iwpgvCharts.charts
  
  Object.values(payload).forEach(async (el) => {

    delete el.chartOptions.annotations 
    delete el.chartOptions.crosshair 
    el.chartOptions.tooltip.trigger= "none"
    el.chartOptions.width = '100%'
    el.chartOptions.height = 200;
    el.chartOptions.legend.position = 'none'
    el.chartOptions.title= null
    el.chartOptions.backgroundColor.strokeWidth = 2;

    if (el.chartParams.chartType !== 'PieChart') {
      el.chartOptions.chartArea.top = 40;
      el.chartOptions.chartArea.left = 60;
      el.chartOptions.chartArea.width = null;
      el.chartOptions.chartArea.height = null;
      el.chartOptions.chartArea.backgroundColor.strokeWidth = 1;
    }

    // const containers = {...containers}
    // containers.chart = `iwpgv__chart__${el.chartParams.chartId}`
    renderChart(el, el.chartParams.sheet, {chart:`${iwpgvObj.prefix}__chart__${el.chartParams.chartId}`}, "list")
  
  });

  // Toggle Spinner, Wraning and Dasjbaord Div
  // toggleElement(`${iwpgvObj.prefix}__spinner`);
  // hideElement(`${iwpgvObj.prefix}__warning`);
  // hideElement(`${iwpgvObj.prefix}__dashboard`);


  // try {
  //   const formData = new FormData(
  //      document.getElementById(`${iwpgvObj.prefix}__chartOptionsForm`)
  //   );

    
  //   formData.append("action", iwpgvObj.file_select_action);
  //   formData.append("nonce", iwpgvObj.file_select_nonce);

  //   //send ajax resquest
  //   const jsonRes = await fetchData(formData);
    
  //   if (jsonRes.message) {
  //     displayAdminMessage(jsonRes.message);
  //   }

  //   // Success handler
  //   if (jsonRes.status && jsonRes.status === "success") {
  //     setSheetId(jsonRes.spreadsheet, "");
  //     setChartTypeId("");
  //   } else {
  //     throw new Error( jsonRes.message)
  //   }
  // } catch (error) {
  //   console.log("ERRROR", error)
  //   displayAdminMessage(error.message);
  // } finally {
  //   toggleElement(`${iwpgvObj.prefix}__spinner`);
  //   showElement(`${iwpgvObj.prefix}__warning`);
  // }
};

export default listCharts;
