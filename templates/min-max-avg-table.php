

<div class="<?php echo "accordion accordion__level-2 {$chart_axis}__Accordion"; ?>">
  
  <div class="ac">
    <h2 class="ac-header"><div class="ac-trigger">Basic Options</div></h2>
    <div class="ac-panel">
      <p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

      <div class="field-group twentyFive-seventyFive"> 
        <div class="form-group">
          <?php $value = ( isset( $min_max_avg_table["visible"] ) ) ? $min_max_avg_table["visible"] : false; ?>
          <input type="checkbox" class="form-group__input form-group__input-checkbox layout" id="<?php echo "{$__min_max_avg_table}[visible]"; ?>" name="<?php echo "{$__min_max_avg_table}[visible]"; ?>" <?php echo $value ? "checked" : ""; ?> >
          <label for="<?php echo "{$__min_max_avg_table}[autorange]"; ?>" class="form-group__label">Visible</label>
          <div class="form-group__tooltip form-group__tooltip-ttCheckbox">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              If 'normal', the range is computed in relation to the extrema of the input data. If 'tozero'`, the range extends to 0, regardless of the input data If 'nonnegative', the range is non-negative, regardless of the input data. Applies only to linear axes.
            </div>
          </div>
        </div>
      </div>

      <div class="field-group twentyFive-seventyFive"> 
        <div class="form-group">
          <?php $value = ( isset( $min_max_avg_table["evenRowColor"] ) ) ? $min_max_avg_table["evenRowColor"] : "#ffffff"; ?>
          <input type="color" class="form-group__input form-group__input-color min_max_avg_table" id="<?php echo "{$__min_max_avg_table}[evenRowColor]"; ?>" name="<?php echo "{$__min_max_avg_table}[evenRowColor]"; ?>" value="<?php echo $value; ?>" >
          <label for="<?php echo "{$__min_max_avg_table}[evenRowColor]"; ?>" class="form-group__label">Even Row Color</label>
          <div class="form-group__tooltip form-group__tooltip-ttColor">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the background color of the paper where the graph is drawn. Default: "#fff" 
            </div>
          </div>
        </div>
        <div class="form-group">
          <?php $value = ( isset( $min_max_avg_table["oddRowColor"] ) ) ? $min_max_avg_table["oddRowColor"] : "#ffffff"; ?>
          <input type="color" class="form-group__input form-group__input-color min_max_avg_table" id="<?php echo "{$__min_max_avg_table}[oddRowColor]"; ?>" name="<?php echo "{$__min_max_avg_table}[oddRowColor]"; ?>" value="<?php echo $value; ?>" >
          <label for="<?php echo "{$__min_max_avg_table}[oddRowColor]"; ?>" class="form-group__label">Odd Row Color</label>
          <div class="form-group__tooltip form-group__tooltip-ttColor">
            <div class="form-group__tooltip-question-mark">?</div>
            <div class="form-group__tooltip-hint">
              Sets the background color of the paper where the graph is drawn. Default: "#fff" 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div

</div>
