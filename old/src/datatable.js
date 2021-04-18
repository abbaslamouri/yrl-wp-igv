const datatable = function (sheet) {
  // Create empty datatable
  const data = new google.visualization.DataTable();

  Object.values(sheet.labels).forEach((label, i) => {
    data.addColumn({
      type: Object.values(sheet.dataTypes)[i],
      label: label,
      role: sheet.roles ? Object.values(sheet.roles)[i] : null,
    });
  });

  // initial datatbale rows array
  var arr = [];

  // Loop through all rows
  Object.values(sheet.data).forEach((row, i) => {
    // Initialize column array
    var element = [];

    // loop through all column element and add to the column array
    Object.values(row).forEach((cell, j) => {
      element.push(cell);
    });

    // Add the column array to the rows arrays if the rowe and comun length match (cells with null values are not added)
    arr.push(element);
  });

  // add all rows to datatable
  data.addRows(arr);

  // Return datatable
  return data;
};

export default datatable;
