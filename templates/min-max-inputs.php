<!-- X-axis Min and Max inputs go here -->
<form id="<?php echo "{$this->prefix}__plotMinMaxAvgForm" ?>" class="inputs hidden" action="#">
  <h4>Select a range or use the sliders above</h4>  
  <div class="min-max-form-group">
    <input type="number" class="min-max-form-group__input" step="0.1" id="<?php echo "{$this->prefix}__rangeMinInput" ?>" placeholder="Range Min" required>
    <label class="min-max-form-group__label" for="<?php echo "{$this->prefix}__rangeMinInput" ?>">Range Min</label>
  </div>
  <div class="min-max-form-group">
    <input type="number" class="min-max-form-group__input" step="0.1" id="<?php echo "{$this->prefix}__rangeMaxInput" ?>" placeholder="Range Max" required>
    <label class="min-max-form-group__label" for="<?php echo "{$this->prefix}__rangeMaxInput" ?>">Range Max</label>
  </div>
</form>