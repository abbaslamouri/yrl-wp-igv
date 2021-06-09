
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="text" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[title][text]"; ?>" name="<?php echo "{$this->prefix}__layout[title][text]"; ?>" value="<?php echo  $payload["chart"]["layout"]["title"]["text"]; ?>" min="200" max="3000" step="10" >
    <label for="<?php echo "{$this->prefix}__layout[title][text]"; ?>" class="form-group__label">Title Text</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the plot's title. Note that before the existence of `title.text`, the title's contents used to be defined as the `title` attribute itself. This behavior has been deprecated.  
      </div>
    </div>
  </div>
  <div class="form-group">
    <select class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[title][font][family]"; ?>" name="<?php echo "{$this->prefix}__layout[title][font][family]"; ?>" value="<?php echo $payload["chart"]["layout"]["title"]["font"]["family"]; ?>" >
    <?php foreach ($payload["fontFamily"] as $key => $value) :?>
      <option value="<?php echo $key?>"<?php echo $payload["chart"]["layout"]["title"]["font"]["family"] === $key ? "selected" : "" ; ?> ><?php echo $value?></option>
      <?php endforeach; ?>
    </select>
    <label for="<?php echo "{$this->prefix}__layout[title][font][family]"; ?>" class="form-group__label">Font Family</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        HTML font family - the typeface that will be applied by the web browser. The web browser will only be able to apply a font if it is available on the system which it operates. Provide multiple font families, separated by commas, to indicate the preference in which to apply fonts if they aren't available on the system. The Chart Studio Cloud (at https://chart-studio.plotly.com or on-premise) generates images on a server, where only a select number of fonts are installed and supported. These include "Arial", "Balto", "Courier New", "Droid Sans",, "Droid Serif", "Droid Sans Mono", "Gravitas One", "Old Standard TT", "Open Sans", "Overpass", "PT Sans Narrow", "Raleway", "Times New Roman".  
      </div>
    </div>
  </div>
  
</div>

<div class="field-group fifty-fifty">
<div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[title][font][size]"; ?>" name="<?php echo "{$this->prefix}__layout[title][font][size]"; ?>" value="<?php echo  $payload["chart"]["layout"]["title"]["font"]["size"]; ?>" min="0" max="1000" step="1" >
    <label for="<?php echo "{$this->prefix}__layout[title][font][size]"; ?>" class="form-group__label">Font Size</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        number greater than or equal to 1
      </div>
    </div>
  </div>    
  <div class="form-group">
    <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$this->prefix}__layout[title][font][color]"; ?>" name="<?php echo "{$this->prefix}__layout[title][font][color]"; ?>" value="<?php echo  $payload["chart"]["layout"]["title"]["font"]["color"]; ?>" >
    <label for="<?php echo "{$this->prefix}__layout[title][font][color]"; ?>" class="form-group__label">Font color</label>
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
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[title][x]"; ?>" name="<?php echo "{$this->prefix}__layout[title][x]"; ?>" value="<?php echo  $payload["chart"]["layout"]["title"]["x"]; ?>" min="0" max="1" step=".01" >
    <label for="<?php echo "{$this->prefix}__layout[title][x]"; ?>" class="form-group__label">Title Horizontal Position</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the x position with respect to `xref` in normalized coordinates from "0" (left) to "1" (right).
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[title][y]"; ?>" name="<?php echo "{$this->prefix}__layout[title][y]"; ?>" value="<?php echo $payload["chart"]["layout"]["title"]["y"] === "auto" ? null : $payload["chart"]["layout"]["title"]["y"]; ?>" min="0" max="1" step=".01" >
    <label for="<?php echo "{$this->prefix}__layout[title][y]"; ?>" class="form-group__label">Title Vertical Position</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the y position with respect to `yref` in normalized coordinates from "0" (bottom) to "1" (top). "auto" places the baseline of the title onto the vertical center of the top margin.
      </div>
    </div>
  </div>  
</div>



