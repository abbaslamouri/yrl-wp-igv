

<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="<?php echo "accordion accordion__level-2 legend__Accordion"; ?>">
  
  <div class="ac">
    <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
    <div class="ac-panel">
      <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

      <div class="field-group forty-sixty">
        <div class="form-group">
          <input type="checkbox" class="form-group__input form-group__input-checkbox config" id="<?php echo "{$this->prefix}__layout[showlegend]"; ?>" name="<?php echo "{$this->prefix}__layout[showlegend]"; ?>" <?php echo $payload["chart"]["layout"]["showlegend"] ? "checked" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[showlegend]"; ?>" class="form-group__label">Show Legend</label>
          <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Determines whether or not a legend is drawn. Default is `true` if there is a trace to show and any of these: a) Two or more traces would by default be shown in the legend. b) One pie trace is shown in the legend. c) One trace is explicitly given with `showlegend: true`
            </div>
          </div>
        </div>
        <div class="form-group">
          <select class="form-group__input form-group__input-select layout" id="<?php echo "{$this->prefix}__layout[legend][valign]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][valign]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
            <option value="top" <?php echo "top" === $payload["chart"]["layout"]["legend"]["valign"] ? "selected" : ""; ?> >Top</option>
            <option value="middle" <?php echo "middle" === $payload["chart"]["layout"]["legend"]["valign"] ? "selected" : ""; ?> >Middle</option>
            <option value="bottom" <?php echo "bottom" === $payload["chart"]["layout"]["legend"]["valign"] ? "selected" : ""; ?> >Bottom</option>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][valign]"; ?>" class="form-group__label">Text symbol Alignment</label>
          <div class="form-group__tooltip form-group__tooltip-ttSelect">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the vertical alignment of the symbols with respect to their associated text.
            </div>
          </div>
        </div> 
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][borderwidth]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][borderwidth]"; ?>" value="<?php echo $payload["chart"]["layout"]["legend"]["borderwidth"]; ?>" min="0" max="100" step="1" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][borderwidth]"; ?>" class="form-group__label">Border Width</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the width (in px) of the border enclosing the legend.
            </div>
          </div>
        </div>    
        <div class="form-group">
          <select class="form-group__input form-group__input-select layout" id="<?php echo "{$this->prefix}__layout[legend][orientation]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][orientation]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
            <option value="h" <?php echo "h" === $payload["chart"]["layout"]["legend"]["orientation"] ? "selected" : ""; ?> >Horizontal</option>
            <option value="v" <?php echo "v" === $payload["chart"]["layout"]["legend"]["orientation"] ? "selected" : ""; ?> >Vertical</option>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][orientation]"; ?>" class="form-group__label">Orientation</label>
          <div class="form-group__tooltip form-group__tooltip-ttSelect">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the orientation of the legend. Default: 'v'
            </div>
          </div>
        </div>  
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$this->prefix}__layout[legend][bgcolor]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][bgcolor]"; ?>" value="<?php echo $payload["chart"]["layout"]["legend"]["bgcolor"]; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][bgcolor]"; ?>" class="form-group__label">Background Color</label>
          <div class="form-group__tooltip form-group__tooltip-ttColor">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the legend background color. Defaults to `layout.paper_bgcolor`.
            </div>
          </div>
        </div>
        <div class="form-group">
          <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$this->prefix}__layout[legend][bordercolor]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][bordercolor]"; ?>" value="<?php echo $payload["chart"]["layout"]["legend"]["bordercolor"]; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][bordercolor]"; ?>" class="form-group__label">Border Color</label>
          <div class="form-group__tooltip form-group__tooltip-ttColor">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the color of the border enclosing the legend.
            </div>
          </div>
        </div>  
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <select class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][font][family]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][font][family]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?>  >
          <?php foreach ($payload["fontFamily"] as $key => $value) :?>
            <option value="<?php echo $key?>"<?php echo $payload["chart"]["layout"]["legend"]["font"]["family"] === $key ? "selected" : "" ; ?> ><?php echo $value?></option>
            <?php endforeach; ?>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][font][family]"; ?>" class="form-group__label">Font Family</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. Provide multiple font families, separated by commas, to indicate the preference in which to apply fonts if they aren't available on the system. The Chart Studio Cloud (at https://chart-studio.plotly.com or on-premise) generates images on a server, where only a select number of fonts are installed and supported.  
            </div>
          </div>
        </div>
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][font][size]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][font][size]"; ?>" value="<?php echo  $payload["chart"]["layout"]["legend"]["font"]["size"]; ?>" min="0" max="1000" step="1" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][font][size]"; ?>" class="form-group__label">Font Size</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              number greater than or equal to 1
            </div>
          </div>
        </div>    
        <div class="form-group">
          <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$this->prefix}__layout[legend][font][color]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][font][color]"; ?>" value="<?php echo  $payload["chart"]["layout"]["legend"]["font"]["color"]; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][font][color]"; ?>" class="form-group__label">Font color</label>
          <div class="form-group__tooltip form-group__tooltip-ttColor">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the background color of the plotting area in-between x and y axes. Default: "#ffffff"
            </div>
          </div>
        </div>  
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <select class="form-group__input form-group__input-select layout" id="<?php echo "{$this->prefix}__layout[legend][itemsizing]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][itemsizing]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
            <option value="trace" <?php echo "trace" === $payload["chart"]["layout"]["legend"]["itemsizing"] ? "selected" : ""; ?> >Trace</option>
            <option value="constant" <?php echo "constant" === $payload["chart"]["layout"]["legend"]["itemsizing"] ? "selected" : ""; ?> >Constant</option>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][itemsizing]"; ?>" class="form-group__label">Item Sizing</label>
          <div class="form-group__tooltip form-group__tooltip-ttSelect">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Determines if the legend items symbols scale with their corresponding "trace" attributes or remain "constant" independent of the symbol size on the graph. Default = Trace.
            </div>
          </div>
        </div> 
        <div class="form-group">
          <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][itemwidth]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][itemwidth]"; ?>" value="<?php echo  $payload["chart"]["layout"]["legend"]["itemwidth"]; ?>" min="0" max="1000" step="1" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][itemwidth]"; ?>" class="form-group__label">Item Width</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the width (in px) of the legend item symbols (the part other than the title.text). Default = 30.
            </div>
          </div>
        </div>  
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <select class="form-group__input form-group__input-select layout" id="<?php echo "{$this->prefix}__layout[legend][itemclick]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][itemclick]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
            <option value="toggle" <?php echo "toggle" === $payload["chart"]["layout"]["legend"]["itemclick"] ? "selected" : ""; ?> >Toggle</option>
            <option value="toggleothers" <?php echo "toggleOthers" === $payload["chart"]["layout"]["legend"]["itemclick"] ? "selected" : ""; ?> >Toggle Others</option>
            <option value=false <?php echo ! $payload["chart"]["layout"]["legend"]["itemclick"] ? "selected" : ""; ?> >Disabled</option>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][itemclick]"; ?>" class="form-group__label">Item Click</label>
          <div class="form-group__tooltip form-group__tooltip-ttSelect">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Determines the behavior on legend item click. "toggle" toggles the visibility of the item clicked on the graph. "toggleothers" makes the clicked item the sole visible item on the graph. "false" disable legend item click interactions. Defualt: Toggle
            </div>
          </div>
        </div> 
        <div class="form-group">
          <select class="form-group__input form-group__input-select layout" id="<?php echo "{$this->prefix}__layout[legend][itemdoubleclick]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][itemdoubleclick]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
            <option value="toggle" <?php echo "toggle" === $payload["chart"]["layout"]["legend"]["itemdoubleclick"] ? "selected" : ""; ?> >Toggle</option>
            <option value="toggleothers" <?php echo "toggleOthers" === $payload["chart"]["layout"]["legend"]["itemdoubleclick"] ? "selected" : ""; ?> >Toggle Others</option>
            <option value=false <?php echo ! $payload["chart"]["layout"]["legend"]["itemdoubleclick"] ? "selected" : ""; ?> >Disabled</option>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][itemdoubleclick]"; ?>" class="form-group__label">Item Double Click</label>
          <div class="form-group__tooltip form-group__tooltip-ttSelect">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Determines the behavior on legend item doubleclick. "toggle" toggles the visibility of the item clicked on the graph. "toggleothers" makes the clicked item the sole visible item on the graph. "false" disable legend item click interactions. Defualt: Toggle
            </div>
          </div>
        </div>
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][x]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][x]"; ?>" value="<?php echo  $payload["chart"]["layout"]["legend"]["x"]; ?>" min="-2" max="3" step=".01" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][x]"; ?>" class="form-group__label">Horizontal Position</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Number between or equal to -2 and 3.  Sets the x position (in normalized coordinates) of the legend. Defaults to "1.02" for vertical legends and defaults to "0" for horizontal legends.
            </div>
          </div>
        </div> 
        <div class="form-group">
          <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][y]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][y]"; ?>" value="<?php echo  $payload["chart"]["layout"]["legend"]["y"]; ?>" min="-2" max="3" step=".01" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][y]"; ?>" class="form-group__label">Vertical Position</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Number between or equal to -2 and 3.  Sets the y position (in normalized coordinates) of the legend. Defaults to "1" for vertical legends, defaults to "-0.1" for horizontal legends on graphs w/o range sliders and defaults to "1.1" for horizontal legends on graph with one or multiple range sliders.
            </div>
          </div>
        </div>  
      </div>
    </div>
  </div>

  <div class="ac">
    <h2 class="ac-header"><div class="ac-trigger">Title</div></h2>
    <div class="ac-panel">
      <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <input type="text" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][title][text]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][title][text]"; ?>" value="<?php echo $payload["chart"]["layout"]["legend"]["title"]["text"]; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][title][text]"; ?>" class="form-group__label">Title</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the title of the legend.
            </div>
          </div>
        </div>    
        <div class="form-group">
          <select class="form-group__input form-group__input-select layout" id="<?php echo "{$this->prefix}__layout[legend][title][side]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][title][side]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] || ! $payload["chart"]["layout"]["legend"]["title"]["text"] ? "disabled" : ""; ?> >
            <option value="top" <?php echo "top" === $payload["chart"]["layout"]["legend"]["title"]["side"] ? "selected" : ""; ?> >Top</option>
            <option value="left" <?php echo "left" === $payload["chart"]["layout"]["legend"]["title"]["side"] ? "selected" : ""; ?> >Left</option>
            <option value="top left" <?php echo "top left" === $payload["chart"]["layout"]["legend"]["title"]["side"] ? "selected" : ""; ?> >Top Left</option>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][title][side]"; ?>" class="form-group__label">Side</label>
          <div class="form-group__tooltip form-group__tooltip-ttSelect">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Determines the location of legend's title with respect to the legend items. Defaulted to "top" with `orientation` is "h". Defaulted to "left" with `orientation` is "v". The "top left" options could be used to expand legend area in both x and y sides.
            </div>
          </div>
        </div>  
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <select class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][title][font][family]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][title][font][family]"; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] || ! $payload["chart"]["layout"]["legend"]["title"]["text"] ? "disabled" : ""; ?> >
          <?php foreach ($payload["fontFamily"] as $key => $value) :?>
            <option value="<?php echo $key?>"<?php echo $payload["chart"]["layout"]["legend"]["title"]["font"]["family"] === $key ? "selected" : "" ; ?> ><?php echo $value?></option>
            <?php endforeach; ?>
          </select>
          <label for="<?php echo "{$this->prefix}__layout[legend][title][font][family]"; ?>" class="form-group__label">Font Family</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. Provide multiple font families, separated by commas, to indicate the preference in which to apply fonts if they aren't available on the system. The Chart Studio Cloud (at https://chart-studio.plotly.com or on-premise) generates images on a server, where only a select number of fonts are installed and supported.
            </div>
          </div>
        </div>
      </div>

      <div class="field-group fifty-fifty">
        <div class="form-group">
          <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[legend][title][font][size]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][title][font][size]"; ?>" value="<?php echo  $payload["chart"]["layout"]["legend"]["title"]["font"]["size"]; ?>" min="0" max="1000" step="1" <?php echo ! $payload["chart"]["layout"]["showlegend"] || ! $payload["chart"]["layout"]["legend"]["title"]["text"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][title][font][size]"; ?>" class="form-group__label">Font Size</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              number greater than or equal to 1
            </div>
          </div>
        </div>    
        <div class="form-group">
          <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$this->prefix}__layout[legend][title][font][color]"; ?>" name="<?php echo "{$this->prefix}__layout[legend][title][font][color]"; ?>" value="<?php echo  $payload["chart"]["layout"]["legend"]["title"]["font"]["color"]; ?>" <?php echo ! $payload["chart"]["layout"]["showlegend"] || ! $payload["chart"]["layout"]["legend"]["title"]["text"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$this->prefix}__layout[legend][title][font][color]"; ?>" class="form-group__label">Font color</label>
          <div class="form-group__tooltip form-group__tooltip-ttColor">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the background color of the plotting area in-between x and y axes. Default: "#ffffff"
            </div>
          </div>
        </div>  
      </div>

    </div>

  </div>

</div>            


   