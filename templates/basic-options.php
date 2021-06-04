
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group fifty-fifty">
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[width]"; ?>" name="<?php echo "{$this->prefix}__layout[width]"; ?>" >
    <label for="<?php echo "{$this->prefix}__layout[width]"; ?>" class="form-group__label">Plot Width</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the plot's width (in px). Number greater than or equal to 10. Default = 700  
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[height]"; ?>" name="<?php echo "{$this->prefix}__layout[height]"; ?>" >
    <label for="<?php echo "{$this->prefix}__layout[height]"; ?>" class="form-group__label">Plot Height</label>
    <div class="form-group__tooltip">
      <div class="form-group__tooltip-question-mark">?</div>
      <div class="form-group__tooltip-hint">
        Sets the plot's height (in px). Number greater than or equal to 10. Default = 450
      </div>
    </div>
  </div>  
</div>