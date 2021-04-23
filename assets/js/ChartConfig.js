// import ChartDefault from "./ChartDefault"


class ChartLayout{

  constructor(config, iwpgvObj) {

    // super(chartTypes, colors,pointShapes, lineWidth, markerSize, fontNames);

    this.config = config
    this.prefix = iwpgvObj.prefix
   
  }

  options() {

    return {

      "responsive" : ( typeof( this.config.responsive ) !== "undefined" ) ? this.config.responsive: true,
      "displayModeBar" : ( typeof( this.config.displayModeBar ) !== "undefined" ) ? this.config.displayModeBar : true,
    }

  }


  panel() {

    return {

      "id" : `${this.prefix}__chartConfigPanel`,
      'cssClasses' : ['chartConfig', 'chart'],
      "title" : "Configuration",
      "intro" : "uiyoyuoiyuioyuioyuyuyuoyuoyuo",
      "sections" : {
        "general" : {
          'intro' : "lorem udfujdfsjsdj",
          "id" : `${this.prefix}__chartConfigPanel__general`,
          "title" : "hhhhh",
          "fields" : [
            [
              {
                "id": "chartConfig[responsive]",
                "title": "Responsive ?",
                "type": "checkbox",
                "value": this.options()['responsive']
              },
              {
                "id": "chartConfig[displayModeBar]",
                "title": "Display Mode Bar ?",
                "type": "checkbox",
                "value": this.options()['displayModeBar']
              },
            ],
          ]  
        }
      }
    }
    
  }
  

}

export default ChartLayout