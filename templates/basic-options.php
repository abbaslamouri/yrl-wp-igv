
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <?php $value = ( isset( $layout ) && isset( $layout["width"] ) ) ? $layout["width"] : null; ?>
    <input type="number" class="form-group__input layout" id="<?php echo "{$__layout}[width]"; ?>" name="<?php echo "{$__layout}[width]"; ?>" value="<?php echo $value; ?>" min="200" max="3000" step="10" >
    <label for="<?php echo "{$__layout}[width]"; ?>" class="form-group__label">Plot Width</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the plot's width (in px). Number greater than or equal to 10. Default = 700  
      </div>
    </div>
  </div>
  <div class="form-group">
  <?php $value = ( isset( $layout ) && isset( $layout["height"] ) ) ? $layout["height"] : null; ?>
    <input type="number" class="form-group__input layout" id="<?php echo "{$__layout}[height]"; ?>" name="<?php echo "{$__layout}[height]"; ?>" value="<?php echo $value; ?>" min="200" max="3000" step="10" >
    <label for="<?php echo "{$__layout}[height]"; ?>" class="form-group__label">Plot Height</label>
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
    <?php $value = ( isset( $layout ) && isset( $layout["paper_bgcolor"] ) ) ? $layout["paper_bgcolor"] : "#ffffff"; ?>
    <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$__layout}[paper_bgcolor]"; ?>" name="<?php echo "{$__layout}[paper_bgcolor]"; ?>" value="<?php echo $value; ?>" >
    <label for="<?php echo "{$__layout}[paper_bgcolor]"; ?>" class="form-group__label">Paper Background Color</label>
    <div class="form-group__tooltip form-group__tooltip-ttColor">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the background color of the paper where the graph is drawn. Default: "#fff" 
      </div>
    </div>
  </div>
  <div class="form-group">
  <?php $value = ( isset( $layout ) && isset( $layout["plot_bgcolor"] ) ) ? $layout["plot_bgcolor"] : "#ffffff"; ?>
    <input type="color" class="form-group__input form-group__input-color layout" id="<?php echo "{$__layout}[plot_bgcolor]"; ?>" name="<?php echo "{$__layout}[plot_bgcolor]"; ?>" value="<?php echo $value; ?>" >
    <label for="<?php echo "{$__layout}[plot_bgcolor]"; ?>" class="form-group__label">Plot Background color</label>
    <div class="form-group__tooltip form-group__tooltip-ttColor">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the background color of the plotting area in-between x and y axes. Default: "#fff"
      </div>
    </div>
  </div>  
</div>