

<div class="<?php echo "accordion accordion__level-2 {$chart_axis}__Acc"; ?>">
  
  <div class="ac">
    <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
    <div class="ac-panel">
      <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
      <div class="field-group twentyFive-seventyFive"> 
        <div class="form-group">
          <?php $value = ( isset( $layout ) && isset( $layout[$chart_axis] ) && isset( $layout[$chart_axis]["visible"] ) ) ? $layout[$chart_axis]["visible"] : false; ?>
          <input type="checkbox" class="form-group__input form-group__input-checkbox layout" id="<?php echo "{$__layout}[{$chart_axis}][visible]"; ?>" name="<?php echo "{$__layout}[{$chart_axis}][visible]"; ?>" <?php echo $value ? "checked" : "" ?> >
          <label for="<?php echo "{$__layout}[{$chart_axis}][autorange]"; ?>" class="form-group__label">Visible</label>
          <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes.
            </div>
          </div>
        </div>
        <div class="form-group">
          <?php $value = ( isset( $layout ) && isset( $layout[$chart_axis] ) && isset( $layout[$chart_axis]["type"] ) ) ? $layout[$chart_axis]["type"] : ""; ?>
          <select type="text" class="form-group__input form-group__input-select layout" id="<?php echo "{$__layout}[{$chart_axis}][type]"; ?>" name="<?php echo "{$__layout}[{$chart_axis}][type]"; ?>" value="<?php echo $value ?>" <?php echo ! $layout[$chart_axis]["visible"] ? "disabled" : ""; ?> >
            <option value="-" selected>Default</option>
            <option value="linear">Linear</option>
            <option value="log">Log</option>
          </select>
          <label for="<?php echo "{$__layout}[{$chart_axis}][type]"; ?>" class="form-group__label">Type</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the range of this axis. If the axis `type` is 'log', then you must take the log of your desired range (e.g. to set the range from 1 to 100, set the range from 0 to 2). If the axis `type` is 'date', it should be date strings, like date data, though Date objects and unix milliseconds will be accepted and converted to strings. If the axis `type` is 'category', it should be numbers, using the scale where each category is assigned a serial number from zero in the order it appears.
            </div>
          </div>
        </div>
      </div>
      <div class="field-group twentyFive-seventyFive"> 
        <div class="form-group">
          <?php $value = ( isset( $layout ) && isset( $layout[$chart_axis] ) && isset( $layout[$chart_axis]["autorange"] ) ) ? $layout[$chart_axis]["autorange"] : false; ?>
          <input type="checkbox" class="form-group__input form-group__input-checkbox layout" id="<?php echo "{$__layout}[{$chart_axis}][autorange]"; ?>" name="<?php echo "{$__layout}[{$chart_axis}][autorange]"; ?>" <?php echo $value ? "checked" : "" ?> <?php echo ! $layout[$chart_axis]["visible"] ? "disabled" : ""; ?> >
          <label for="<?php echo "{$__layout}[{$chart_axis}][autorange]"; ?>" class="form-group__label">Auto Range</label>
          <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes.
            </div>
          </div>
        </div>
        <div class="form-group">
          <?php $value = ( isset( $layout ) && isset( $layout[$chart_axis] ) && isset( $layout[$chart_axis]["range"] ) ) ? $layout[$chart_axis]["range"] : ""; ?>
          <input type="text" class="form-group__input layout" id="<?php echo "{$__layout}[{$chart_axis}][range]"; ?>" name="<?php echo "{$__layout}[{$chart_axis}][range]"; ?>" value="<?php echo $value ?>" <?php echo ( ! $layout[$chart_axis]["visible"]  || $layout[$chart_axis]["autorange"] ) ? "disabled" : ""; ?> >
          <label for="<?php echo "{$__layout}[{$chart_axis}][range]"; ?>" class="form-group__label">Range</label>
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
      <div class="field-group sixty-forty">
        <div class="form-group">
          <?php $value = ( isset( $layout ) && isset( $layout[$chart_axis] ) && isset( $layout[$chart_axis]["title"] ) && isset( $layout[$chart_axis]["title"]["text"] ) ) ? $layout[$chart_axis]["title"]["text"] : ""; ?>
          <input type="text" class="form-group__input layout" id="<?php echo "{$__layout}[{$chart_axis}][title][text]"; ?>" name="<?php echo "{$__layout}[{$chart_axis}][title][text]"; ?>" value="<?php echo $value ?>" >
          <label for="<?php echo "{$__layout}[{$chart_axis}][title][text]"; ?>" class="form-group__label">Title</label>
          <div class="form-group__tooltip">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes.
            </div>
          </div>
        </div>
        <div class="form-group">
          <?php $value = ( isset( $layout ) && isset( $layout[$chart_axis] ) && isset( $layout[$chart_axis]["title"] ) && isset( $layout[$chart_axis]["title"]["font"] ) && isset($layout[$chart_axis]["title"]["font"]["size"] ) ) ? $layout[$chart_axis]["title"]["font"]["size"] : null; ?>
          <input type="number" class="form-group__input layout" id="<?php echo "{$__layout}[{$chart_axis}][title][font][size]"; ?>" name="<?php echo "{$__layout}[{$chart_axis}][title][font][size]"; ?>" value="<?php echo $value ?>" min="0" max="100" step="1" >
          <label for="<?php echo "{$__layout}[{$chart_axis}][title][font][size]"; ?>" class="form-group__label">Font Size</label>
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
