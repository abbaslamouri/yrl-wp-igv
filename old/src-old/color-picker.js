import { chartOptionKey } from "./utilities";

/********************************************************
 ********************* WP Color Picker ******************
 ********************************************************/
const colorPicker = function (iwpgvChart) {
  // Wp color picker (must remain in index file)
  jQuery(document).ready(function ($) {
    const colorPickerOptions = {
      // you can declare a default color here,
      // or in the data-default-color attribute on the input
      defaultColor: false,

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
      console.log(
        "TTTTTT",
        chartOptionKey(event.target.id),
        ui.color.toString()
      );
      const optionParts = chartOptionKey(event.target.id);
      switch (optionParts[0]) {
        case "chartOptions":
        case "horAxisOptions":
        case "leftAxisOptions":
        case "rightAxisOptions":
        case "seriesOptions":
        case "trendlinesOptions":
          iwpgvChart.setOption(optionParts[1], ui.color.toString());
          iwpgvChart.draw();
          break;
      }
      // const optionId = chartOptionKey(event.target.id);
      // iwpgvChart.setOption(optionId, ui.color.toString());
      // iwpgvChart.draw();
    }

    function colorPickerClear(event) {
      console.log(chartOptionKey(event.target.id), "transparent");
      const inputId = event.target.closest(".form-group").querySelector("label")
        .htmlFor;
      const optionId = chartOptionKey(inputId);
      iwpgvChart.setOption(optionId, "transparent");
      iwpgvChart.draw();
    }
  });
};

export default colorPicker;
