import { chartOptionKey } from "./utilities";

const colorPicker = function (iwpgvChart, chartRangeSlider) {
  // Wp color picker (must remain in index file)
  jQuery(document).ready(function ($) {
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

    $(".iwpgv .color-picker").each(function () {
      $(this).wpColorPicker(colorPickerOptions);
    });

    function colorPickerChange(event, ui) {
      console.log(chartOptionKey(event.target.id), ui.color.toString());
      // const optionParts = chartOptionKey(event.target.id);
      switch (chartOptionKey(event.target.id).control) {
        case "chartOptions":
        case "horAxisOptions":
        case "leftAxisOptions":
        case "rightAxisOptions":
        case "seriesOptions":
        case "trendlinesOptions":
        case "pieChartOptions":
          iwpgvChart.setOption(
            chartOptionKey(event.target.id).key,
            ui.color.toString()
          );
          iwpgvChart.draw();
          break;

        case "chartRangeOptions":
          console.log("PPPPPPP");
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
