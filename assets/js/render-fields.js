import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';


const renderFields = function (panelSections, panelId, iwpgvObj) {

  console.log("PanelSections", panelSections, Object.keys(panelSections))

  // Create new accordion
  
  const accordionDiv = document.createElement("div")
  accordionDiv.classList.add("accordion-2")


  // Loop through accordion panel sections
  for (const prop in panelSections) {

    // Create accordion panel
    const ac = document.createElement("div")
    ac.classList.add("ac")

     // Create ac header
     const acHeader = document.createElement("h2")
     acHeader.classList.add("ac-header")
   
    // Create trigger button
    const acTrigger = document.createElement("button")
    acTrigger.classList.add("ac-trigger")

    // Create heading title and add to trigger button
    const headingTitle = document.createTextNode(panelSections[prop].title)
    acTrigger.appendChild(headingTitle)

    // Add trigger button to header
    acHeader.appendChild(acTrigger)

    // Add header to ac
    ac.appendChild(acHeader)

    // Add ac to accordion
    accordionDiv.appendChild(ac)

    // Create accordion content
    const acPanel = document.createElement("div")
    acPanel.classList.add("ac-panel")

    // Create intro p
    const introP = document.createElement("p")
    introP.classList.add("ac-text", "intro")

     // Create intro and add to content
     const intro = document.createTextNode(panelSections[prop].intro)
     acPanel.appendChild(intro)

    // Add p tag to content
    acPanel.appendChild(introP)



    // Loop through field rows
    const fielGroups = panelSections[prop].fieldGroups
    for (const fieldRow in fielGroups) {
      const row = fielGroups[fieldRow];

      console.log("ROW",row)

      //Create field group and add apprpriate css classes
      const fieldGroup = document.createElement( "div" )
      if (row.cssClasses) {
        for (const cssClass in row.cssClasses) {
          fieldGroup.classList.add(row.cssClasses[cssClass])
        }
      }
      

      // Loop through fields
      for (const el in row.inputs) {
        const field = row.inputs[el]
        console.log("FIELD", field)


        // Create form group
        const formGroup = document.createElement( "div" )
        formGroup.classList.add("form-group");

        // Create label
        if (field.type !== "hidden") {
          const labelElem = document.createElement( "label" )
          labelElem.classList.add("form-group__label");
          labelElem.htmlFor = `${iwpgvObj.prefix}__${field.id}`
          const labelText = field.title
            ? document.createTextNode( field.title )
            : document.createTextNode( "\u00A0" );
          labelElem.appendChild( labelText );
          formGroup.appendChild( labelElem );
        }

        // Create input/select field and add css class
        let inputField = field.type === "select" ? document.createElement("select"): document.createElement("input")
        inputField.classList.add("form-group__input")

        // Add field type or options depending on whether input field is select or otherwise
        if (field.type === "select") {
          // const options = field.options
      
          // Remove all options
          inputField.options.length = 0
      
          // Add options
          for (const prop in field.options) {
            inputField.options[inputField.options.length] = new Option(
              field.options[prop],
              prop,
              false,
              false
            );
          }
        } else {
          inputField.type = field.type
        }

        // Set field id, name, classlist and value attributes
        inputField.id = `${iwpgvObj.prefix}__${field.id}`
        inputField.name = `${iwpgvObj.prefix}__${field.id}`
        if (field.type === "checkbox") {
          if (field.value) inputField.checked = true
        } else {
          inputField.value = field.value
        }
        if (field.cssClasses) {
          for (const cssClass in field.cssClasses) {
            inputField.classList.add(field.cssClasses[cssClass])
          }
        }

        // add min, number input max, max, step AND PLAVE HOLDERif any
        if (field.min) inputField.min = field.min
        if (field.max) inputField.max = field.max
        if (field.step) inputField.step = field.step
        if (field.title) inputField.placeholder = field.title
        if ( field.readOnly ) inputField.readOnly = true
        if ( field.disabled ) inputField.disabled = true
        if ( field.required ) inputField.required = true

        // Add form group box to form group
        formGroup.appendChild(inputField)

        // hide for group if input field has a hidden class
        if (field.cssClasses && field.cssClasses.includes("hidden")) {
          formGroup.classList.add("hidden")
        }

        



        // Add hint to form group
        if (field.hint) {

          // Create tooltip div
          const tooltip = document.createElement("div")
          tooltip.classList.add("form-group__tooltip")

          // Create tooltip question mark span and add to tooltip div
          const tooltipQuestionMarkDiv = document.createElement("div")
          tooltipQuestionMarkDiv.classList.add("form-group__tooltip-question-mark")
          const tooltipQuestionMark = document.createTextNode("?")
          tooltipQuestionMarkDiv.appendChild(tooltipQuestionMark)
          tooltip.appendChild(tooltipQuestionMarkDiv)

          // Create tooltip hint and ad to tooltip
          const tooltipHintDiv = document.createElement("div")
          tooltipHintDiv.classList.add("form-group__tooltip-hint")
          const hintText = document.createTextNode(field.hint)
          tooltipHintDiv.appendChild(hintText)
          tooltip.appendChild(tooltipHintDiv)

          // Add tooltip to form group
          formGroup.appendChild(tooltip)

        } 
        
        fieldGroup.appendChild( formGroup )

        console.log("FIELDGROUP", fieldGroup )

        // Add field group to content
        acPanel.appendChild( fieldGroup )

      }




      // switch (row.length) {
      //   case 3:
      //     fieldGroup.classList.add("triple-field")
      //     break;
      //   case 2:
      //     fieldGroup.classList.add("dual-field")
      //     break;
      //   case 1:
      //     fieldGroup.classList.add("single-field")
      //     break;
      //   default:
      //     break;
    }




    // Add content to ac
    ac.appendChild(acPanel)

    // Add accordion to panel
    const panelContent = document.querySelector(`.${iwpgvObj.prefix}__admin #${iwpgvObj.prefix}__chartOptionsForm .${panelId} .ac-panel`)
    panelContent.appendChild(accordionDiv)






    // // Add  subpanel accordion toggle to panel content if there is a heading title
    // if (panelSections[prop].title) {
    //   const subpanelToggle = accordionToggle(panelSections[prop].title)
    //   subpanelAccordion.appendChild(subpanelToggle);
    // }

    // // Add subpanel content  to panel content
    // const subpanelContent = accordionContent(
    //   panelSections[prop].id,
    //   ["subPanel"],
    //   panelSections[prop].intro
    // );
    // subpanelAccordion.appendChild(subpanelContent)

    // // Loop through field rows
    // const fieldRows = panelSections[prop].fields
    // for (const fieldRow in fieldRows) {
    //   const row = fieldRows[fieldRow];

    //   // Create field group and add apprpriate css classes
    //   const fieldGroup = document.createElement("div")
    //   fieldGroup.classList.add("field-group")
    //   switch (row.length) {
    //     case 3:
    //       fieldGroup.classList.add("triple-field")
    //       break;
    //     case 2:
    //       fieldGroup.classList.add("dual-field")
    //       break;
    //     case 1:
    //       fieldGroup.classList.add("single-field")
    //       break;
    //     default:
    //       break;
    //   }

    //   // Loop through fields
    //   for (const prop in row) {
    //     const field = row[prop]

    //     // Retreive form Group and add to field group
    //     const formGroup = setFormGroup(
    //       field,
    //       panel.cssClasses,
    //       iwpgvObj
    //     );
    //     fieldGroup.appendChild(formGroup)

    //   }

    //   // Add field group to subpanel content
    //   subpanelContent.appendChild(fieldGroup)
    //   newsubpanelAccordion.appendChild(fieldGroup)
    // }
  }

  // Load accordion
  const accordion2Options = { duration: 400 }
  const accordion2 = new Accordion( '.accordion-2', accordion2Options );
  // accordion1.openAll()

  console.log(accordionDiv)

}



// Create accordion panel toggle
function accordionToggle(title) {
  // Create panel toggle div
  const panelToggle = document.createElement("div")
  panelToggle.classList.add("accordion__toggle")

  // Create toggle heading title div
  const panelHeadingTitle = document.createElement("div")
  panelHeadingTitle.classList.add("accordion__heading-title")

  // Create haeding title text
  const headingTitle = document.createTextNode(title)

  // Append title text to heading title div
  panelHeadingTitle.appendChild(headingTitle)

  // Append panel heading title to panel toggle
  panelToggle.appendChild(panelHeadingTitle)

  // Create svg element
  const svgElem = document.createElementNS("http://www.w3.org/2000/svg", "svg")
  svgElem.classList.add("accordion__svg")
  const useElem = document.createElementNS("http://www.w3.org/2000/svg", "use")
  useElem.href.baseVal = `${yrl_wp_igv_obj.url}assets/img/icons.svg#icon-keyboard_arrow_right`
  svgElem.appendChild(useElem)

  // Append svg to panel toggle
  panelToggle.appendChild(svgElem)

  return panelToggle;
}




// Create accordion panel content
function accordionContent(id, cssClasses, panelIntroText = "") {

  // Create panel/subpanel content div
  const panelContent = document.createElement("div")

  // panel content div ID
  panelContent.id = id;

  // Add accordion__content to panel/subpanel content div class list
  panelContent.classList.add("accordion__content")

  // Add panel css classes to panel/subpanel content
  panelContent.classList.add("panel");
  if (cssClasses) {
    for (const prop in cssClasses) {
      panelContent.classList.add(cssClasses[prop])
    }
  }
  
  // In intro then reate panel/subpanel intro div, add "panelIntro" to classlist and add intro text
  if (null !== panelIntroText) {   
   
    const panelIntroDiv = document.createElement("div")
    panelIntroDiv.classList.add("panelIntro");

    // Create panel/subpanel intro text
    const introText = document.createTextNode(panelIntroText)

    // Append panel/subpanel intro text nodeto panel intro div
    panelIntroDiv.appendChild(introText)

    // Append panel intro div to panel content
    panelContent.appendChild(panelIntroDiv)
  }

  return panelContent;
}


function setFormGroup(field, panelCssClasses, iwpgvObj) {
  // Create form group
  const formGroup = document.createElement("div")
  formGroup.classList.add("form-group");

  // Create label
  if (field.type !== "hidden") {
    const labelElem = document.createElement("label")
    labelElem.classList.add("form-group__label");
    labelElem.htmlFor = `${iwpgvObj.prefix}__${field.id}`
    const labelText = field.title
      ? document.createTextNode(field.title)
      : document.createTextNode("\u00A0");
    labelElem.appendChild(labelText);
    formGroup.appendChild(labelElem);
  }

  // Create form group input box (contains input/select field and suffix)
  // const formGroupInputBox = document.createElement("div")
  // formGroupInputBox.classList.add("form-group__input-box")

  // Create input/select field
  let inputField = field.type === "select" ? document.createElement("select"): document.createElement("input")

  inputField.classList.add("form-group__input")

  // Add field type or options depending on whether input field is select or otherwise
  if (field.type === "select") {
    const options = field["options"]

    // Remove all options
    inputField.options.length = 0

    // Add options
    for (const prop in options) {
      inputField.options[inputField.options.length] = new Option(
        options[prop],
        prop,
        false,
        false
      );
    }
  } else {
    inputField.type = field.type
  }

  // Set field id, name, classlist and value attributes
  inputField.id = `${iwpgvObj.prefix}__${field.id}`
  inputField.name = `${iwpgvObj.prefix}__${field.id}`
  if (field.type === "checkbox") {
    if (field.value) inputField.checked = true
  } else {
    inputField.value = field.value
  }
  if (field.cssClasses) {
    for (const prop in field.cssClasses) {
      inputField.classList.add(field.cssClasses[prop])
    }
  }
  for (const prop in panelCssClasses) {
    inputField.classList.add(`yrl_wp_igv__${panelCssClasses[prop]}`)
  }
  
  // inputField.classList.add("yrl_wp_igv__chart");

  // add min, number input max, max and step if any
  if (field.min) inputField.min = field.min
  if (field.max) inputField.max = field.max
  if (field.step) inputField.step = field.step

  // Add filed title as placeholder
  if (field.title) inputField.placeholder = field.title

  

  // Add dataset to field if any
  // if (
  //   field.cssClasses &&
  //   field.cssClasses.includes("hasDependents") &&
  //   field.dependents
  // ) {
  //   inputField.dataset.dependents = field.dependents.join(",");
  // }

  // Set disabled fields
  if (field.disabled) inputField.disabled = true

  // Add input field to form froup box
  // formGroupInputBox.appendChild(inputField)

  // Add suffix to form group box
  // if (field.suffix) {
  //   const suffix = document.createElement("div")
  //   suffix.classList.add("form-group__suffix");
  //   const suffixText = document.createTextNode(field.suffix)
  //   suffix.appendChild(suffixText)
  //   formGroupInputBox.appendChild(suffix)

  //   // Add hasSuffix class to form group if field hasSuffix class
  //   formGroupInputBox.classList.add("hasSuffix")
  // }

  // Add form group box to form group
  formGroup.appendChild(inputField)

  // Add hint to form group
  if (field.hint) {

    // Create tooltip div
    const tooltip = document.createElement("div")
    tooltip.classList.add("form-group__tooltip")

    // Create tooltip question mark span and add to tooltip div
    const tooltipQuestionMarkDiv = document.createElement("div")
    tooltipQuestionMarkDiv.classList.add("form-group__tooltip-question-mark")
    const tooltipQuestionMark = document.createTextNode("?")
    tooltipQuestionMarkDiv.appendChild(tooltipQuestionMark)
    tooltip.appendChild(tooltipQuestionMarkDiv)

    // Create tooltip hint and ad to tooltip
    const tooltipHintDiv = document.createElement("div")
    tooltipHintDiv.classList.add("form-group__tooltip-hint")
    const hintText = document.createTextNode(field.hint)
    tooltipHintDiv.appendChild(hintText)
    tooltip.appendChild(tooltipHintDiv)

    // Add tooltip to form group
    formGroup.appendChild(tooltip)

  }

  if ( field.readOnly ) inputField.readOnly = true
  if ( field.disabled ) inputField.disabled = true
  if ( field.required ) inputField.required = true

  // hide for group if input field has a hidden class
  if (field.cssClasses && field.cssClasses.includes("hidden")) {
    formGroup.classList.add("hidden")
  }

  return formGroup
}

export default renderFields
