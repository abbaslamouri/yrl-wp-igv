const renderPanels = function ($panels) {
  // Get plugin name
  const $plugin = yrl_wp_igv_obj.plugin;

  // Create accordion div
  const panelAccordion = document.createElement("div");
  panelAccordion.classList.add("accordion");

  // Loop through panels
  // const $panels = iwpgv_charts.panels;
  for (const $panel in $panels) {
    // Get panel toggle and content divs
    const panelToggle = accordionToggle($panels[$panel]["title"]);
    const panelContent = accordionContent(
      $panels[$panel]["id"],
      $panels[$panel]["cssClasses"],
      $panels[$panel]["intro"]
    );

    const subpanelAccordion = document.createElement("div");
    subpanelAccordion.classList.add("accordion");
    panelContent.appendChild(subpanelAccordion);

    // Loop through panel sections
    const $sections = $panels[$panel]["sections"];
    for (const $section in $sections) {
      // Add  subpanel accordion toggle to panel content if there is a heading title
      if ($sections[$section]["title"]) {
        const subpanelToggle = accordionToggle($sections[$section]["title"]);
        subpanelAccordion.appendChild(subpanelToggle);
      }

      // Add subpanel content  to panel content
      const subpanelContent = accordionContent(
        $sections[$section]["id"],
        "subpanel",
        $sections[$section]["intro"]
      );
      subpanelAccordion.appendChild(subpanelContent);

      // Loop through field rows
      const $fieldRows = $sections[$section]["fields"];
      for (const $fieldRow in $fieldRows) {
        const $row = $fieldRows[$fieldRow];

        // Create field group and add apprpriate css classes
        const fieldGroup = document.createElement("div");
        fieldGroup.classList.add("field-group");
        switch ($row.length) {
          case 3:
            fieldGroup.classList.add("triple-field");
            break;
          case 2:
            fieldGroup.classList.add("dual-field");
            break;
          case 1:
            fieldGroup.classList.add("single-field");
            break;
          default:
            break;
        }

        // Loop through fields
        for (const $prop in $row) {
          const $field = $row[$prop];

          // Retreive form Group and add to field group
          const formGroup = setFormGroup(
            $field,
            $panels[$panel]["cssClasses"],
            $plugin
          );
          fieldGroup.appendChild(formGroup);

          // Hide firld group if form group has a hidden class
          if (formGroup.classList.contains("hidden")) {
            fieldGroup.classList.add("hidden");
          }
        }

        // Add field group to subpanel content
        subpanelContent.appendChild(fieldGroup);
      }
    }

    // Append panel tooggle and content to accordion div
    panelAccordion.appendChild(panelToggle);
    panelAccordion.appendChild(panelContent);
  }

  // Add accordion div to foem
  const form = document.getElementById(`${$plugin}__chartOptionsForm`);
  form.insertAdjacentElement("beforeEnd", panelAccordion);

  // Add save button to form
  const saveChartBtn = document.createElement("button");
  saveChartBtn.classList.add("button");
  saveChartBtn.classList.add("button-primary");
  saveChartBtn.id = `${$plugin}__saveChart`;
  saveChartBtn.name = `${$plugin}__saveChart`;
  saveChartBtn.disabled = true;
  const saveChartBtnText = document.createTextNode("Save Chart");
  saveChartBtn.appendChild(saveChartBtnText);
  form.appendChild(saveChartBtn);
};

function accordionToggle($title) {
  // Create panel toggle div
  const panelToggle = document.createElement("div");
  panelToggle.classList.add("accordion__toggle");

  // Create toggle heading title div
  const panelHeadingTitle = document.createElement("div");
  panelHeadingTitle.classList.add("accordion__heading-title");

  // Create haeding title text
  const headingTitle = document.createTextNode($title);

  // Append title text to heading title div
  panelHeadingTitle.appendChild(headingTitle);

  // Append panel heading title to panel toggle
  panelToggle.appendChild(panelHeadingTitle);

  // Create svg element
  const svgElem = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  svgElem.classList.add("accordion__svg");
  const useElem = document.createElementNS("http://www.w3.org/2000/svg", "use");
  useElem.href.baseVal = `${iwpgv_obj.url}assets/img/icons.svg#icon-keyboard_arrow_right`;
  svgElem.appendChild(useElem);

  // Append svg to panel toggle
  panelToggle.appendChild(svgElem);

  return panelToggle;
}

function accordionContent($id, $cssClasses, $intro = null) {
  // Create panel content
  const panelContent = document.createElement("div");
  panelContent.id = $id;
  panelContent.classList.add("accordion__content");

  // Add panel css classes to panel content
  panelContent.classList.add("panel");
  if ($cssClasses) {
    for (const $prop in $cssClasses) {
      panelContent.classList.add($cssClasses[$prop]);
    }
  }
  // if (! panelContent.classList.contains('subpanel')) {
  //   panelContent.style["height"] = 0;
  //   panelContent.style["margin-top"] = 0;
  //   panelContent.style["margin-bottom"] = 0;
  //   panelContent.style["padding-top"] = 0;
  //   panelContent.style["padding-bottom"] = 0;
  //   panelContent.style.border = "none";
  //   panelContent.style.overflow = "hidden";
  // }

  // Create panel intro div
  if ($intro) {
    const intro = document.createElement("div");
    intro.classList.add("intro");

    // Create panel intro text
    const introText = document.createTextNode($intro);

    // Append panel intro text to panel intro div
    intro.appendChild(introText);

    // Append panel intro div to panel content
    panelContent.appendChild(intro);
  }

  return panelContent;
}

function setFormGroup($field, $panelCssClasses, $plugin) {
  // Create form group
  const formGroup = document.createElement("div");
  formGroup.classList.add("form-group");

  // Create label
  if ($field["type"] !== "hidden") {
    const labelElem = document.createElement("label");
    labelElem.classList.add("form-group__label");
    labelElem.htmlFor = $field["id"];
    const labelText = $field["title"]
      ? document.createTextNode($field["title"])
      : document.createTextNode("\u00A0");
    labelElem.appendChild(labelText);
    formGroup.appendChild(labelElem);
  }

  // Create form group input box (contains input/select field and suffix)
  const formGroupInputBox = document.createElement("div");
  formGroupInputBox.classList.add("form-group__input-box");

  // Create input/select field
  let inputField =
    $field["type"] === "select"
      ? document.createElement("select")
      : document.createElement("input");

  // Add field type or options depending on whether input field is select or otherwise
  if ($field["type"] === "select") {
    const $options = $field["options"];

    // Remove all options
    inputField.options.length = 0;

    // Add options
    for (const $prop in $options) {
      inputField.options[inputField.options.length] = new Option(
        $options[$prop],
        $prop,
        false,
        false
      );
    }
  } else {
    inputField.type = $field["type"];
  }

  // Set field id, name, classlist and value attributes
  inputField.id = `${$plugin}__${$field["id"]}`;
  inputField.name = `${$plugin}__${$field["id"]}`;
  if ($field["type"] === "checkbox") {
    if ($field["value"]) inputField.checked = true;
  } else {
    inputField.value = $field["value"];
  }
  if ($field["cssClasses"]) {
    for (const $prop in $field["cssClasses"]) {
      inputField.classList.add($field["cssClasses"][$prop]);
    }
  }
  for (const $prop in $panelCssClasses) {
    inputField.classList.add(`iwpgv__${$panelCssClasses[$prop]}`);
  }
  inputField.classList.add("form-group__input");
  // inputField.classList.add("iwpgv__chart");

  // add min, number input max, max and step if any
  if ($field["min"]) inputField.min = $field["min"];
  if ($field["max"]) inputField.max = $field["max"];
  if ($field["step"]) inputField.step = $field["step"];

  // Add dataset to field if any
  if (
    $field["cssClasses"] &&
    $field["cssClasses"].includes("hasDependents") &&
    $field["dependents"]
  ) {
    inputField.dataset.dependents = $field["dependents"].join(",");
  }

  // Set disabled fields
  if ($field['disabled']) inputField.disabled = true

  // Add input field to form froup box
  formGroupInputBox.appendChild(inputField);

  // Add suffix to form group box
  if ($field["suffix"]) {
    const suffix = document.createElement("div");
    suffix.classList.add("form-group__suffix");
    const suffixText = document.createTextNode($field["suffix"]);
    suffix.appendChild(suffixText);
    formGroupInputBox.appendChild(suffix);

    // Add hasSuffix class to form group if field hasSuffix class
    formGroupInputBox.classList.add("hasSuffix");
  }

  // Add form group box to form group
  formGroup.appendChild(formGroupInputBox);

  // Add hint to form group
  if ($field["hint"]) {
    const hint = document.createElement("div");
    hint.classList.add("form-group__hint");
    const hintText = document.createTextNode($field["hint"]);
    hint.appendChild(hintText);
    hint.classList.add("hidden");
    formGroup.appendChild(hint);
  }

  // hide for group if input field has a hidden class
  if ($field["cssClasses"] && $field["cssClasses"].includes("hidden")) {
    formGroup.classList.add("hidden");
  }

  return formGroup;
}

export default renderPanels;
