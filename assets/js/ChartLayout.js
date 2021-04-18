// import ChartDefault from "./ChartDefault"


class ChartLayout{

  constructor(layout, prefix) {

    // super(chartTypes, colors,pointShapes, lineWidth, markerSize, fontNames);

    this.layout = layout
    this.prefix = prefix
   
  }

  options() {

    return {

      "paper_bgcolor" : ( typeof( this.layout.paper_bgcolor ) !== "undefined" ) ? this.layout.paper_bgcolor: "#CCC",
      "plot_bgcolor" : ( typeof( this.layout['plot_bgcolor'] ) !== "undefined" ) ? this.layout['plot_bgcolor'] : "#FFF",
      "height" : ( typeof( this.layout.height ) !== "undefined" ) ? this.layout['height'] : 400,
      "autosize" : ( typeof ( this.layout['autosize']) !== "undefined" ) ? this.layout['autosize'] : true,
      "title" : {
        "text" : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.text) !== "undefined" ) ? this.layout.title.text : 'Hi There',
        "x"    : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.x) !== "undefined" ) ? this.layout.title.x : 0.5,
        "y"    : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.y) !== "undefined" ) ? this.layout.title.y : 0.9,
        "font" : {
          "family" : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.family ) !== "undefined" ) ? this.layout.title.font.family : 'Balto',
          "size" : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.size ) !== "undefined" ) ? this.layout.title.font.size : 40,
          "color" : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.color ) !== "undefined" ) ? this.layout.title.font.color : "teal",
        }
      },
      "showlegend" : ( typeof( this.layout.showlegend ) !== "undefined" ) ? this.layout.showlegend : true,
      "legend" : {
        "bgcolor" : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.bgcolor ) !== "undefined" ) ? this.layout.legend.bgcolor : 'yellow',
        "bordercolor" : ( typeof( this.layout.legend) !== "undefined" && typeof( this.layout.legend.bordercolor ) !== "undefined" ) ? this.layout.legend.bordercolor : 'green',
        "borderwidth" : ( typeof( this.layout.legend ) !== "undefined"  && typeof( this.layout.legend.borderwidth ) !== "undefined" ) ? this.layout.legend.borderwidth : 2,
        "font" : {
          "family" : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.family ) !== "undefined"  ) ? this.layout.legend.font.family : 'Raleway',
          "size" : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.size ) !== "undefined" ) ? this.layout.legend.font.size : 20,
          "color" : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.color ) !== "undefined" ) ? this.layout.legend.font.color : "red",
        },
        "orientation" : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.orientation ) !== "undefined" ) ? this.layout.legend.orientation : 'h',
      },
      "xaxis" : {
        "automargin" : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.automargin ) !== "undefined" ) ? this.layout.xaxis.automargin : true,
        "rangeslider" : {
          "visible" : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.visible ) !== "undefined" ) ? this.layout.xaxis.rangeslider.visible : false,
          "bgcolor" : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.bgcolor ) !== "undefined" ) ? this.layout.xaxis.rangeslider.bgcolor : "teal",
          "thickness" : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.thickness ) !== "undefined" ) ? this.layout.xaxis.rangeslider.thickness : 0.2,
        }
      },
      "yaxis" : {
        "fixedrange" : ( typeof ( this.layout.yaxis ) !== "undefined" && typeof ( this.layout.yaxis.fixedrange ) !== "undefined" ) ? this.layout.yaxis.fixedrange : true,
      },


    }

  }


  panel() {

    return {

      "id" : `${this.prefix}__chartLayoutPanel`,
      'cssClasses' : ['chartLayout', 'chart'],
      "title" : "Chart Layout",
      "intro" : "uiyoyuoiyuioyuioyuyuyuoyuoyuo",
      "sections" : {
        "general" : {
          'intro' : "lorem udfujdfsjsdj",
          "id" : `${this.prefix}__chartLayoutPanel__general`,
          "title" : "hhhhh",
          "fields" : [
            [
              {
                "id" : "chartLayout[paper_bgcolor]",
                "title" : "Paper Color Color",
                "type" : "color-picker", 
                "cssClasses" : ["color-picker"],
                "value" : this.options()['paper_bgcolor'],
              },
              {
                "id" : "chartLayout[plot_bgcolor][fill]",
                "title" : "Background Color",
                "type" : "color-picker", 
                "cssClasses" : ["color-picker"],
                "value" : this.options()['plot_bgcolor'],
              },
            ],
          ]  
        }
      }
    }
    
  }
  

}

export default ChartLayout;