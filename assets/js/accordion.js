/*
div structure

<div class='accordion'
  <div class-'accordion__toggle>
    <h>Heading</h>
    <svg><svg>
  </div>
  <div class='accordion__content>Conetnt</div>
</div>

Note:  the div with .accordion__content must directly follow the div with .accodion__toggle
*/

// import "../sass/accordion.scss";

 // const iwpgvCharts = yrl_wp_igv_charts
//  const iwpgvObj = yrl_wp_igv_obj
//  const iwpgvObj.prefix = iwpgvObj.iwpgvObj.prefix

class Accordion {

  constructor(options, iwpgvObj) {

    this.options = options
    this.iwpgvObj = iwpgvObj

    const accordions = document.querySelectorAll(`.${this.iwpgvObj.prefix}__admin .accordion`)

    accordions.forEach((accordion) => {
      const toggles = [];
      const contents = [];
      const children = accordion.children
      for (const prop in children) {
        if ( children[prop].classList && children[prop].classList.contains("accordion__toggle" ) ) toggles[prop] = children[prop]
        if ( children[prop].classList && children[prop].classList.contains("accordion__content") ) contents[prop] = children[prop]
      }

      // Bail if the element itsel or its ancestors don't have a .accordion__toggle or the direct parent does not have .accorion class
      if (!toggles) return

      // Get the next sibling to the .accordion__toggle class
      // const contents = accordion.querySelectorAll(".accordion__content");
      contents.forEach((content, i) => {
        // Close all acordions
        this.closeAccordion(content)

        // Open accrodions with no toggle
        if (
          !content.previousElementSibling ||
          (content.previousElementSibling &&
            !content.previousElementSibling.classList.contains("accordion__toggle"))
        )
          this.openAccordion(content)

        // open accordion with openOnLoad class
        if (content.classList.contains("openOnLoad"))
          this.openAccordion(content);
      });

      toggles.forEach((toggle) => {
        toggle.addEventListener("click", (event) => {
          contents.forEach((content, i) => {
            this.closeAccordion(content)
          });
          const useElem = event.target
            .closest(".accordion__toggle")
            .querySelector(".accordion__svg use")
          const nextSibling = event.target.closest(".accordion__toggle")
            .nextElementSibling;
          this.toggleAccordion(nextSibling, useElem)
          // }
        })
      })
    })

  }

  toggleAccordion(content, useElem) {
    if (getComputedStyle(content).height == "0px") {
      useElem.href.baseVal = `${this.iwpgvObj.url}assets/img/icons.svg#icon-keyboard_arrow_down`
      this.openAccordion(content)
    } else {
      useElem.href.baseVal = `${this.iwpgvObj.url}assets/img/icons.svg#icon-keyboard_arrow_right`
      this.closeAccordion(content)
    }
  }

  openAccordion(content) {
    content.style.removeProperty("height")
    content.style.removeProperty("margin-top")
    content.style.removeProperty("margin-bottom")
    content.style.removeProperty("padding-top")
    content.style.removeProperty("padding-bottom")
    content.style.removeProperty("border")
    content.style.removeProperty("overflow")
  }

  closeAccordion(content) {
    content.style["height"] = 0
    content.style["margin-top"] = 0
    content.style["margin-bottom"] = 0
    content.style["padding-top"] = 0
    content.style["padding-bottom"] = 0
    content.style.border = "none"
    content.style.overflow = "hidden"
  }
}

// new Accordion( { collapsed: false } );

export default Accordion;
