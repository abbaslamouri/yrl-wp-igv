<div class="accordion accordion__level-2 xaxis__Acc">
  <div class="ac">
    <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
    <div class="ac-panel">
      <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
      <div class="field-group fifty-fifty">
        
        <div class="form-group">
          <input type="checkbox" class="form-group__input form-group__input-checkbox layout" id="<?php echo "{$this->prefix}__layout[xaxis][autorange]"; ?>" name="<?php echo "{$this->prefix}__layout[xaxis][autorange]"; ?>" >
          <label for="<?php echo "{$this->prefix}__layout[xaxis][autorange]"; ?>" class="form-group__label">Auto Range</label>
          <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes.
            </div>
          </div>
        </div>

        <div class="form-group">
          <input type="text" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[xaxis][range]"; ?>" name="<?php echo "{$this->prefix}__layout[xaxis][range]"; ?>" >
          <label for="<?php echo "{$this->prefix}__layout[xaxis][range]"; ?>" class="form-group__label">Range</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the range of this axis. If the axis `type` is 'log', then you must take the log of your desired range (e.g. to set the range from 1 to 100, set the range from 0 to 2). If the axis `type` is 'date', it should be date strings, like date data, though Date objects and unix milliseconds will be accepted and converted to strings. If the axis `type` is 'category', it should be numbers, using the scale where each category is assigned a serial number from zero in the order it appears.
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

      <div class="field-group seventyFive-twentyFive">
        <div class="form-group">
          <input type="text" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[title][text]"; ?>" name="<?php echo "{$this->prefix}__layout[title][text]"; ?>" >
          <label for="<?php echo "{$this->prefix}__layout[title][text]"; ?>" class="form-group__label">Title</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes.
            </div>
          </div>
        </div>

        <div class="form-group">
          <input type="number" class="form-group__input layout" id="<?php echo "{$this->prefix}__layout[title][font][size]"; ?>" name="<?php echo "{$this->prefix}__layout[title][font][size]"; ?>" >
          <label for="<?php echo "{$this->prefix}__layout[title][font][size]"; ?>" class="form-group__label">Font Size</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the range of this axis. If the axis `type` is 'log', then you must take the log of your desired range (e.g. to set the range from 1 to 100, set the range from 0 to 2). If the axis `type` is 'date', it should be date strings, like date data, though Date objects and unix milliseconds will be accepted and converted to strings. If the axis `type` is 'category', it should be numbers, using the scale where each category is assigned a serial number from zero in the order it appears.
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
