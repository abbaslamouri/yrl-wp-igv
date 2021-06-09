
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[width]"; ?>" name="<?php echo "{$this->prefix}__layout[width]"; ?>" value="<?php echo  $payload["chart"]["layout"]["width"]; ?>" min="200" max="3000" step="10" >
    <label for="<?php echo "{$this->prefix}__layout[width]"; ?>" class="form-group__label">Plot Width</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the plot's width (in px). Number greater than or equal to 10. Default = 700  
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[height]"; ?>" name="<?php echo "{$this->prefix}__layout[height]"; ?>" value="<?php echo  $payload["chart"]["layout"]["height"]; ?>" min="200" max="3000" step="10" >
    <label for="<?php echo "{$this->prefix}__layout[height]"; ?>" class="form-group__label">Plot Height</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the plot's height (in px). Number greater than or equal to 10. Default = 450
      </div>
    </div>
  </div>  
</div>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$this->prefix}__layout[paper_bgcolor]"; ?>" name="<?php echo "{$this->prefix}__layout[paper_bgcolor]"; ?>" value="<?php echo  $payload["chart"]["layout"]["paper_bgcolor"]; ?>" >
    <label for="<?php echo "{$this->prefix}__layout[paper_bgcolor]"; ?>" class="form-group__label">Paper Background Color</label>
    <div class="form-group__tooltip form-group__tooltip-ttColor">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the background color of the paper where the graph is drawn. Default: "#fff" 
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$this->prefix}__layout[plot_bgcolor]"; ?>" name="<?php echo "{$this->prefix}__layout[plot_bgcolor]"; ?>" value="<?php echo  $payload["chart"]["layout"]["plot_bgcolor"]; ?>" >
    <label for="<?php echo "{$this->prefix}__layout[plot_bgcolor]"; ?>" class="form-group__label">Plot Background color</label>
    <div class="form-group__tooltip form-group__tooltip-ttColor">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the background color of the plotting area in-between x and y axes. Default: "#fff"
      </div>
    </div>
  </div>  
</div>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="checkbox" class="form-group__input form-group__input-checkbox config" id="<?php echo "{$this->prefix}__config[staticPlot]"; ?>" name="<?php echo "{$this->prefix}__config[staticPlot]"; ?>" <?php echo $payload["chart"]["config"]["staticPlot"] ? "checked" : ""; ?> >
    <label for="<?php echo "{$this->prefix}__config[staticPlot]"; ?>" class="form-group__label">Static Plot</label>
    <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the background color of the plotting area in-between x and y axes. Default: "#fff"
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[margin][pad]"; ?>" name="<?php echo "{$this->prefix}__layout[margin][pad]"; ?>" value="<?php echo  $payload["chart"]["layout"]["margin"]["pad"]; ?>" min="0" max="1000" step="1" >
    <label for="<?php echo "{$this->prefix}__layout[margin][pad]"; ?>" class="form-group__label">Plot Padding</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the amount of padding (in px) between the plotting area and the axis lines
      </div>
    </div>
  </div>    
</div>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[margin][l]"; ?>" name="<?php echo "{$this->prefix}__layout[margin][l]"; ?>" value="<?php echo  $payload["chart"]["layout"]["margin"]["l"]; ?>" min="0" max="1000" step="1" >
    <label for="<?php echo "{$this->prefix}__layout[margin][l]"; ?>" class="form-group__label">Margin Left</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the left margin (in px).
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[margin][r]"; ?>" name="<?php echo "{$this->prefix}__layout[margin][r]"; ?>" value="<?php echo  $payload["chart"]["layout"]["margin"]["r"]; ?>" min="0" max="1000" step="1" >
    <label for="<?php echo "{$this->prefix}__layout[margin][r]"; ?>" class="form-group__label">Margin Righ</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the right margin (in px).
      </div>
    </div>
  </div>  
</div>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[margin][b]"; ?>" name="<?php echo "{$this->prefix}__layout[margin][b]"; ?>" value="<?php echo  $payload["chart"]["layout"]["margin"]["b"]; ?>" min="0" max="1000" step="1" >
    <label for="<?php echo "{$this->prefix}__layout[margin][b]"; ?>" class="form-group__label">Margin Bottom</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the bottom margin (in px).
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[margin][t]"; ?>" name="<?php echo "{$this->prefix}__layout[margin][t]"; ?>" value="<?php echo  $payload["chart"]["layout"]["margin"]["t"]; ?>" min="0" max="1000" step="1" >
    <label for="<?php echo "{$this->prefix}__layout[margin][t]"; ?>" class="form-group__label">Margin Top</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the top margin (in px).
      </div>
    </div>
  </div>  
</div>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="checkbox" class="form-group__input form-group__input-checkbox layout" id="<?php echo "{$this->prefix}__layout[autosize]"; ?>" name="<?php echo "{$this->prefix}__layout[autosize]"; ?>" <?php echo $payload["chart"]["layout"]["autosize"] ? "checked" : ""; ?> >
    <label for="<?php echo "{$this->prefix}__layout[autosize]"; ?>" class="form-group__label">Auto Size</label>
    <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Determines whether or not a layout width or height that has been left undefined by the user is initialized on each relayout. Note that, regardless of this attribute, an undefined layout width or height is always initialized on the first call to plot.
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="checkbox" class="form-group__input form-group__input-checkbox layout" id="<?php echo "{$this->prefix}__layout[margin][autoexpand]"; ?>" name="<?php echo "{$this->prefix}__layout[margin][autoexpand]"; ?>" <?php echo $payload["chart"]["layout"]["margin"]["autoexpand"] ? "checked" : ""; ?> >
    <label for="<?php echo "{$this->prefix}__layout[margin][autoexpand]"; ?>" class="form-group__label">Auto Expand</label>
    <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Turns on/off margin expansion computations. Legends, colorbars, updatemenus, sliders, axis rangeselector and rangeslider are allowed to push the margins by defaults.
      </div>
    </div>
  </div>
</div>

