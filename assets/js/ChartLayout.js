// import ChartDefault from "./ChartDefault"


class ChartLayout{

  constructor(layout, iwpgvObj) {

    // super(chartTypes, colors,pointShapes, lineWidth, markerSize, fontNames);

    this.layout = layout
    this.prefix = iwpgvObj.prefix

    this.fontFamily = {	
      "" : "Select Font Family",
      arial : "Arial",
      balto: "Balto",
      TimesNewRoman: "Times New Roman",
      courierNew: "Courier New",
      droidSans: "Droid Sans",
      droidSerif: "Droid Serif",
      droidSansMono: "Droid Sans Mono",
      gravitasOne: "Gravitas One",
      oldStandardTT: " Old Standard TT",
      openSans: "Open Sans",
      overpass: "Overpass",
      ptSansNarrow: "PT Sans Narrow",
      raleway: "Raleway"
    }

   
  }

  options() {

    return {

      paper_bgcolor : ( typeof( this.layout.paper_bgcolor ) !== "undefined" ) ? this.layout.paper_bgcolor: "#CCCCCC",
      plot_bgcolor : ( typeof( this.layout.plot_bgcolor ) !== "undefined" ) ? this.layout.plot_bgcolor : "#FFFFFF",
      height : ( typeof( this.layout.height ) !== "undefined" ) ? this.layout['height'] : 400,
      autosize : ( typeof ( this.layout['autosize']) !== "undefined" ) ? this.layout['autosize'] : true,
      title: {
        text: ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.text) !== "undefined" ) ? this.layout.title.text : 'Hi There',
        x: ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.x) !== "undefined" ) ? this.layout.title.x : 0.5,
        y: ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.y) !== "undefined" ) ? this.layout.title.y : 0.9,
        font : {
          family : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.family ) !== "undefined" ) ? this.layout.title.font.family : Object.keys(this.fontFamily)[13],
          size : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.size ) !== "undefined" ) ? this.layout.title.font.size : 40,
          color : ( typeof( this.layout.title ) !== "undefined" && typeof( this.layout.title.font ) !== "undefined" && typeof( this.layout.title.font.color ) !== "undefined" ) ? this.layout.title.font.color : "#008080",
        }
      },
      showlegend : ( typeof( this.layout.showlegend ) !== "undefined" ) ? this.layout.showlegend : true,
      legend : {
        bgcolor : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.bgcolor ) !== "undefined" ) ? this.layout.legend.bgcolor : '#ffc246',
        bordercolor : ( typeof( this.layout.legend) !== "undefined" && typeof( this.layout.legend.bordercolor ) !== "undefined" ) ? this.layout.legend.bordercolor : '#67daff',
        borderwidth : ( typeof( this.layout.legend ) !== "undefined"  && typeof( this.layout.legend.borderwidth ) !== "undefined" ) ? this.layout.legend.borderwidth : 2,
        font : {
          family: ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.family ) !== "undefined"  ) ? this.layout.legend.font.family : Object.keys(this.fontFamily)[13],
          size: ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.size ) !== "undefined" ) ? this.layout.legend.font.size : 20,
          color : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.font ) !== "undefined" && typeof( this.layout.legend.font.color ) !== "undefined" ) ? this.layout.legend.font.color : "#39796b",
        },
        orientation : ( typeof( this.layout.legend ) !== "undefined" && typeof( this.layout.legend.orientation ) !== "undefined" ) ? this.layout.legend.orientation : 'h',
      },
      xaxis : {
        automargin : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.automargin ) !== "undefined" ) ? this.layout.xaxis.automargin : true,
        rangeslider : {
          visible : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.visible ) !== "undefined" ) ? this.layout.xaxis.rangeslider.visible : false,
          bgcolor : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.bgcolor ) !== "undefined" ) ? this.layout.xaxis.rangeslider.bgcolor : "teal",
          thickness : ( typeof( this.layout.xaxis ) !== "undefined" && typeof( this.layout.xaxis.rangeslider ) !== "undefined" && typeof( this.layout.xaxis.rangeslider.thickness ) !== "undefined" ) ? this.layout.xaxis.rangeslider.thickness : 0.2,
        }
      },
      yaxis: {
        fixedrange : ( typeof ( this.layout.yaxis ) !== "undefined" && typeof ( this.layout.yaxis.fixedrange ) !== "undefined" ) ? this.layout.yaxis.fixedrange : true,
      },


    }

  }


  panel() {

    return {

      id : `${this.prefix}__chartLayoutPanel`,
      cssClasses : ['chartLayout', 'chart'],
      title : "Chart Layout",
      intro : "This is the layout pnel",
      sections : {
        title : {
          intro : "Here you can modify the plot title",
          id : `${this.prefix}__chartLayoutPanel__title`,
          title : "Title",
          fields : [
            [
              {
                id : "chartLayout[title][text]",
                title : "Chart Title",
                type : "text", 
                value : this.options().title.text,
              },
            ],
            [
              {
                id : "chartLayout[title][font][family]",
                title : "Font Family",	
                type : "select",
                options : this.fontFamily,
                value : this.options().title.font.family,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : "chartLayout[title][font][size]", 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().title.font.size,
                hint : "number greater than or equal to 1"
              },
              {
                id : "chartLayout[title][font][color]",
                title : "Font Color",
                type : "color", 
                value : this.options().title.font.color,
              },
            ],
            [
              {
                id : "chartLayout[title][x]",
                title : "Title Horizontal Position",	
                type : "number",
                min : 0,
                max : 1,
                step : 0.1,
                value : this.options().title.x,
                hint: "Sets the x position from '0' (left) to '1' (right)."
              },
              {
                id : "chartLayout[title][y]",
                title : "Title Vertical Position",	
                type : "number",
                min : 0,
                max : 1,
                step : 0.1,
                value : this.options().title.y,
                hint: "Sets the y position from '0' (bottom) to '1' (top)."
              },
            ],
          ]  
        },
        legend : {
          intro : "Here you can modify the plot legend",
          id : `${this.prefix}__chartLayoutPanel__legend`,
          title : "Legend",
          fields : [
            [
              {
                id: "chartLayout[showlegend]",
                title:"Show Legend",
                type: "checkbox",
                value: this.options().showlegend,
                hint: "Determines whether or not a legend is drawn. Default is `true` if there is a trace to show and any of these: a) Two or more traces would by default be shown in the legend. b) One pie trace is shown in the legend. c) One trace is explicitly given with `showlegend: true`"
              },
              {
                id : "chartLayout[legend][bgcolor]",
                title : "Background Color",
                type : "color", 
                value : this.options().legend.bgcolor,
                hint: "Sets the legend background color. Defaults to `layout.paper_bgcolor`."
              },
              {
                id : "chartLayout[legend][bordercolor]",
                title : "Border Color",
                type : "color", 
                value : this.options().legend.bordercolor,
                hint: "Sets the color of the border enclosing the legend."
              },
            ],
            [
              {
                id : "chartLayout[legend][font][family]",
                title : "Font Family",	
                type : "select",
                options : this.fontFamily,
                value : this.options().legend.font.family,
                hint: "HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. These include 'Arial', 'Balto', 'Courier New', 'Droid Sans',, 'Droid Serif', 'Droid Sans Mono', 'Gravitas One', 'Old Standard TT', 'Open Sans', 'Overpass', 'PT Sans Narrow', 'Raleway', 'Times New Roman'."
              },
              {
                id : "chartLayout[legend][font][size]", 
                title : "Font Size", 
                type : "number",
                min : 1,
                max : 100,
                step : 0.5,
                value : this.options().legend.font.size,
                hint : "number greater than or equal to 1"
              },
              {
                id : "chartLayout[legend][font][color]",
                title : "Font Color",
                type : "color", 
                value : this.options().legend.font.color,
              },
            ],
            [
              {
                id : "chartLayout[legend][borderwidth]",
                title : "Border Width",	
                type : "number",
                min : 0,
                max : 100,
                step : 1,
                value : this.options().legend.borderwidth,
                hint: "Sets the width (in px) of the border enclosing the legend."
              },
              {
                id : "chartLayout[legend][orientation]",
                title : "Orientation",	
                type : "select",
                options : {
                  h: "Horizontal",
                  v: "Vertical"
                },
                value : this.options().legend.orientation,
                hint: "Sets the orientation of the legend."
              },
            ],
          ]  
        },
        Other : {
          intro : "Here you can modify the plot Other",
          id : `${this.prefix}__chartLayoutPanel__other`,
          title : "Other",
          fields : [
            [
              {
                id : "chartLayout[paper_bgcolor]",
                title : "Paper Color",
                type : "color", 
                cssClasses : ["color-picker"],
                value : this.options().paper_bgcolor,
              },
              {
                id : "chartLayout[plot_bgcolor]",
                title : "Background Color",
                type : "color", 
                // "cssClasses" : ["color-picker"],
                value : this.options().plot_bgcolor,
              },
            ],
          ]  
        }
      }
    }
    
  }
  

}

export default ChartLayout;


