import { chartOptionKey } from "./utilities";

const colorPicker = function (layout={}, traces={}, config = {}) {
  // Wp color picker (must remain in index file)
  jQuery(document).ready(function ($) {

    // const iwpgvCharts = yrl_wp_igv_charts
    const iwpgvObj = yrl_wp_igv_obj
    const prefix = iwpgvObj.prefix


    const colorPickerOptions = {
      // you can declare a default color here,
      // or in the data-default-color attribute on the input
      defaultColor: false,
      width: 255,

      // a callback to fire whenever the color changes to a valid color
      change: function (event, ui) {
        // console.log(ui.color.toString());
        // console.log(chartObj);
        colorPickerChange(event, ui);
      },

      // a callback to fire when the input is emptied or an invalid color
      clear: function (event) {
        colorPickerClear(event);
      },

      // hide the color picker controls on load
      true: false,

      // show a group of common colors beneath the square
      // or, supply an array of colors to customize further
      palettes: true,
    };

    $(`.${prefix} .color-picker`).each(function () {
      $(this).wpColorPicker(colorPickerOptions);
    });

    function colorPickerChange(event, ui) {
      console.log(chartOptionKey(event.target.id), ui.color.toString());
      // const optionParts = chartOptionKey(event.target.id);
      switch (chartOptionKey(event.target.id).control) {
        case "chartLayout":
        case "horAxisOptions":
        case "leftAxisOptions":
        case "rightAxisOptions":
        case "seriesOptions":
        case "trendlinesOptions":
        case "pieChartOptions":

          let arr = chartOptionKey(event.target.id).key.split(".")
          console.log(arr)
          switch (arr.length) {
          //   case 6:
          //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
          //       parts[3].split("]")[0]
          //     }.${parts[4].split("]")[0]}.${parts[5].split("]")[0]}`;
          //     break;
        
          //   case 5:
          //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
          //       parts[3].split("]")[0]
          //     }.${parts[4].split("]")[0]}`;
          //     break;
        
          //   case 4:
          //     optionKey = `${parts[1].split("]")[0]}.${parts[2].split("]")[0]}.${
          //       parts[3].split("]")[0]
          //     }`;
          //     break;
        
            case 3:
            layout[arr[0]][arr[1]][arr[2]] = ui.color.toString()
            break;
        
            case 2:
              layout[arr[0]][arr[1]] = ui.color.toString()
              break;
        
            default:
              optionKey = null;
              break;
          }

          // layout[chartOptionKey(fieldId).key]
          console.log("LAYOUT", layout)
          Plotly.redraw('yrl_wp_igv__plotlyChart', traces, layout, config);
          // iwpgvChart.setOption(chartOptionKey(fieldId).key, fieldVal);
          // iwpgvChart.draw();
          // iwpgvChart.setOption(
          //   chartOptionKey(event.target.id).key,
          //   ui.color.toString()
          // );
          // iwpgvChart.draw();
          break;

        case "chartRangeOptions":
          chartRangeSlider.setOption(
            chartOptionKey(event.target.id).key,
            ui.color.toString()
          );
          chartRangeSlider.draw();
          break;

        default:
          break;

      }
    }

    function colorPickerClear(event) {
      const inputId = event.target.closest(".form-group").querySelector("label")
        .htmlFor;
      const optionId = chartOptionKey(inputId).key;
      iwpgvChart.setOption(optionId, "transparent");
      iwpgvChart.draw();
    }
  });
};

export default colorPicker;
