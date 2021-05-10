<!-- X-axis Min and Max inputs go here -->
<form id="<?php echo "{$this->prefix}__plotMinMaxAvg" ?>" class="inputs" action="#">
  <div class="form-group hidden">
    <input type="number" class="form-group__input hidden" step="0.1" id="<?php echo "{$this->prefix}__rangeMinInput" ?>" placeholder="Range Min" required>
    <label class="form-group__label" for="<?php echo "{$this->prefix}__rangeMinInput" ?>">Range Min</label>
  </div>
  <div class="form-group hidden">
    <input type="number" class="form-group__input hidden" step="0.1" id="<?php echo "{$this->prefix}__rangeMaxInput" ?>" placeholder="Range Max" required>
    <label class="form-group__label" for="<?php echo "{$this->prefix}__rangeMaxInput" ?>">Range Max</label>
  </div>
</form>