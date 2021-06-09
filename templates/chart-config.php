
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <?php $value = ( isset( $config["displayModeBar"] ) ) ? $config["displayModeBar"] : false; ?>
    <input type="checkbox" class="form-group__input form-group__input-checkbox config" id="<?php echo "{$__config}[displayModeBar]"; ?>" name="<?php echo "{$__config}[displayModeBar]"; ?>" <?php echo $value ? "checked" : ""; ?> >
    <label for="<?php echo "{$__config}[displayModeBar]"; ?>" class="form-group__label">Display Mode Bar</label>
    <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Display Modebar 
      </div>
    </div>
  </div>
  <div class="form-group">
  <?php $value = ( isset( $config["displaylogo"] ) ) ? $config["displaylogo"] : false; ?>
    <input type="checkbox" class="form-group__input form-group__input-checkbox config" id="<?php echo "{$__config}[displaylogo]"; ?>" name="<?php echo "{$__config}[displaylogo]"; ?>" <?php echo $value ? "checked" : ""; ?> >
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
    <?php $value = ( isset( $config["staticPlot"] ) ) ? $config["staticPlot"] : false; ?>
    <input type="checkbox" class="form-group__input form-group__input-checkbox config" id="<?php echo "{$__config}[staticPlot]"; ?>" name="<?php echo "{$__config}[staticPlot]"; ?>" <?php echo $value ? "checked" : ""; ?> >
    <label for="<?php echo "{$__config}[staticPlot]"; ?>" class="form-group__label">Static Plot</label>
    <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
         Determines whether the plot is non-interractive
      </div>
    </div>
  </div>
   
</div>