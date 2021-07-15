<!-- X-axis Min and Max inputs go here -->
<form id="<?php echo "{$this->prefix}__plotMinMaxAvgForm" ?>" class="plotMinMaxAvgForm inputs hidden" action="#">
  <h4>Enter a Range Min and a Range Max or use the Rangeslider above to select a range</h4>
  <div class="min-max-form-group">
    <label class="min-max-form-group__label" for="<?php echo "{$this->prefix}__rangeMinInput" ?>">Range Min</label>
    <input type="number" class="min-max-form-group__input" step="0.1" id="<?php echo "{$this->prefix}__rangeMinInput" ?>" placeholder="Range Min" required>
  </div>
  <div class="min-max-form-group">
    <label class="min-max-form-group__label" for="<?php echo "{$this->prefix}__rangeMaxInput" ?>">Range Max</label>
    <input type="number" class="min-max-form-group__input" step="0.1" id="<?php echo "{$this->prefix}__rangeMaxInput" ?>" placeholder="Range Max" required>
  </div>
</form>