
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <?php //$value = ( isset( $config["displayModeBar"] ) ) ? $config["displayModeBar"] : false; ?>
    <input type="checkbox" class="form-group__input form-group__input-checkbox config" id="<?php echo "{$__config}[displayModeBar]"; ?>" name="<?php echo "{$__config}[displayModeBar]"; ?>" <?php echo $config["displayModeBar"] ? "checked" : ""; ?> >
    <label for="<?php echo "{$__config}[displayModeBar]"; ?>" class="form-group__label">Display Mode Bar</label>
    <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Display Modebar 
      </div>
    </div>
  </div>
  <div class="form-group">
  <?php //$value = ( isset( $config["displaylogo"] ) ) ? $config["displaylogo"] : false; ?>
    <input type="checkbox" class="form-group__input form-group__input-checkbox config" id="<?php echo "{$__config}[displaylogo]"; ?>" name="<?php echo "{$__config}[displaylogo]"; ?>" <?php echo $config["displaylogo"] ? "checked" : ""; ?> <?php echo ! $config["displayModeBar"] ? "disabled" : ""; ?> >
    <label for="<?php echo "{$__config}[displaylogo]"; ?>" class="form-group__label">Display Logo</label>
    <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Display Plotly Logo
      </div>
    </div>
  </div>  
</div>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <?php //$value = ( isset( $layout["modebar"]["bgcolor"] ) ) ? $layout["modebar"]["bgcolor"] : "#ffffff"; ?>
    <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$__layout}[modebar][bgcolor]"; ?>" name="<?php echo "{$__layout}[modebar][bgcolor]"; ?>" value="<?php echo $layout["modebar"]["bgcolor"]; ?>" <?php echo $value ? "checked" : ""; ?> <?php echo ! $config["displayModeBar"] ? "disabled" : ""; ?> >
    <label for="<?php echo "{$__layout}[modebar][bgcolor]"; ?>" class="form-group__label">Background Color</label>
    <div class="form-group__tooltip form-group__tooltip-ttColor">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Display Modebar 
      </div>
    </div>
  </div>
  <div class="form-group">
  <?php //$value = ( isset( $layout["modebar"]["orientation"]) ) ? $layout["modebar"]["orientation"]: "h"; ?>
    <select class="form-group__input form-group__input-select layout" id="<?php echo "{$__layout}[modebar][orientation]"; ?>" name="<?php echo "{$__layout}[modebar][orientation]"; ?>" value="<?php echo $layout["modebar"]["orientation"]; ?>" <?php echo ! $config["displayModeBar"] ? "disabled" : ""; ?> >
      <option value="h">Horizontal</option>
      <option value="v">Verical</option>
    </select>
    <label for="<?php echo "{$__layout}[modebar][orientation]"; ?>" class="form-group__label">Orientation</label>
    <div class="form-group__tooltip form-group__tooltip-ttSelect">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Display Plotly Logo
      </div>
    </div>
  </div>  
</div>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <?php //$value = ( isset( $layout["modebar"]["color"] ) ) ? $layout["modebar"]["color"] : "#ffffff"; ?>
    <input type="color" class="form-group__input form-group__input-color config" id="<?php echo "{$__layout}[modebar][color]"; ?>" name="<?php echo "{$__layout}[modebar][color]"; ?>" value="<?php echo $layout["modebar"]["color"]; ?>" <?php echo $value ? "checked" : ""; ?> <?php echo ! $config["displayModeBar"] ? "disabled" : ""; ?> >
    <label for="<?php echo "{$__layout}[modebar][color]"; ?>" class="form-group__label">Color</label>
    <div class="form-group__tooltip form-group__tooltip-ttColor">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Display Modebar 
      </div>
    </div>
  </div>
  <div class="form-group">
  <?php //$value = ( isset( $layout["modebar"]["activecolor"] ) ) ? $layout["modebar"]["activecolor"] : "#ffffff"; ?>
    <input type="color" class="form-group__input form-group__input-color config" id="<?php echo "{$__layout}[modebar][activecolor]"; ?>" name="<?php echo "{$__layout}[modebar][activecolor]"; ?>" value="<?php echo $layout["modebar"]["activecolor"]; ?>" <?php echo $value ? "checked" : ""; ?> <?php echo ! $config["displayModeBar"] ? "disabled" : ""; ?> >
    <label for="<?php echo "{$__layout}[modebar][activecolor]"; ?>" class="form-group__label">Active Color</label>
    <div class="form-group__tooltip form-group__tooltip-ttColor">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Display Plotly Logo
      </div>
    </div>
  </div>  
</div>