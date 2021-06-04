import Accordion from 'accordion-js';
import 'accordion-js/dist/accordion.min.css';

let accordionDiv
let acPanel


const panel = function (panelSections, panelCssClass, prefix) {

  // panel content div
  const panelContent = document.querySelector(`.${prefix}__admin #${prefix}__chartOptionsForm .${panelCssClass} .ac-panel`)
  
  // Create new accordion if there are 2=two or more sections
  // if (Object.keys(panelSections).length > 1) {
    accordionDiv = document.createElement("div")
    accordionDiv.classList.add("accordion", "accordion__level-3", `${panelCssClass}__Acc`)

    // Add accordion to panel
    panelContent.appendChild(accordionDiv)
  // }

  
  // Loop through accordion panel sections
  for (const prop in panelSections) {

    // Create intro p
    const introDiv = document.createElement("div")
    introDiv.classList.add("ac-text", "intro")

    // Create intro and add to intro
    const introText = document.createTextNode(panelSections[prop].intro)
    introDiv.appendChild(introText)

    // if (Object.keys(panelSections).length > 1) {
      
      // Create accordion panel
      const ac = document.createElement("div")
      ac.classList.add("ac")

      // Create ac header
      const acHeader = document.createElement("h2")
      acHeader.classList.add("ac-header")
   
      // Create trigger button
      const acTrigger = document.createElement("div")
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
      acPanel = document.createElement("div")
      acPanel.classList.add("ac-panel")

      // Add content to ac
      ac.appendChild(acPanel)

    // }

    // Add p tag to content
    // if (Object.keys(panelSections).length > 1) {
      // acPanel.appendChild(introDiv)
    // } else {
      panelContent.appendChild(introDiv)
    // }

    

    // Loop through field rows
    for (const fieldRow in panelSections[prop].fieldGroups) {
      
      const row = panelSections[prop].fieldGroups[fieldRow];

      //Create field group and add apprpriate css classes
      const fieldGroup = document.createElement( "div" )
      if (row.cssClasses) {
        for (const cssClass in row.cssClasses) {
          fieldGroup.classList.add(row.cssClasses[cssClass])
        }
      }
      
      // Loop through fields
      for (const el in row.inputFields) {

        const formGroup = fetchformGroup( row.inputFields[el], prefix )
        
        fieldGroup.appendChild( formGroup )

        // Add field group to content
        // if (Object.keys(panelSections).length > 1) {
          acPanel.appendChild( fieldGroup )
        // } else {
          panelContent.appendChild( fieldGroup )
        // }

      }

    }

  }

  // Load accordion if there more than 2 sections
  // if (Object.keys(panelSections).length > 1) {
    // const accordion2Options = { duration: 400 }
    new Accordion( `.${prefix}__admin .${panelCssClass}__Acc`, { duration: 400 } );
  // }

  

}


const fetchformGroup = (field, prefix) => {

  // const field = row.inputFields[el]
  // console.log("FIELD", field)


  // Create form group
  const formGroup = document.createElement( "div" )
  formGroup.classList.add("form-group");

  // Create input/select field and add css class
  let inputField = field.type === "select" ? document.createElement("select"): document.createElement("input")
  inputField.classList.add("form-group__input")

  // Add field type or options depending on whether input field is select or otherwise
  if (field.type === "select") {

    inputField.classList.add("form-group__input-select")
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
    if (field.type === "checkbox") inputField.classList.add("form-group__input-checkbox")
    if (field.type === "color") inputField.classList.add("form-group__input-color")
  }

  // Set field id, name, classlist and value attributes
  inputField.id = `${prefix}__${field.id}`
  inputField.name = `${prefix}__${field.id}`
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

    // Create label
    if (field.type !== "hidden") {
    const labelElem = document.createElement( "label" )
    labelElem.classList.add("form-group__label");
    labelElem.htmlFor = `${prefix}__${field.id}`
    const labelText = field.title
      ? document.createTextNode( field.title )
      : document.createTextNode( "\u00A0" );
    labelElem.appendChild( labelText );
    formGroup.appendChild( labelElem );
  }

  // Add hint to form group
  if (field.hint) {

    // Create tooltip div
    const tooltip = document.createElement("div")
    tooltip.classList.add("form-group__tooltip")

    if (field.type === "checkbox") tooltip.classList.add("form-group__tooltip-ttCheckbox")
    if (field.type === "color") tooltip.classList.add("form-group__tooltip-ttColor")


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

  // hide for group if input field has a hidden class
  if (field.cssClasses && field.cssClasses.includes("hidden")) {
    formGroup.classList.add("hidden")
  }

  return formGroup
  
}



export default panel
