            
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group">
  <div class="form-group">
    <button type="button" class="form-group__btn btn button" id="<?php echo "{$this->prefix}__params[mediaUploadBtn]"; ?>" name="<?php echo "{$this->prefix}__params[mediaUploadBtn]"; ?>" >Upload New File </button>
    <label for="" class="form-group__label"></label>
  </div>
</div>

<div class="field-group hidden">
  <div class="form-group">
    <input type="text" class="form-group__input" id="<?php echo "{$this->prefix}__params[fileName]"; ?>" name="<?php echo "{$this->prefix}__params[fileName]"; ?>" placeholder="Selected File" readonly>
    <label for="" class="form-group__label">Selected File</label>
  </div>
</div>

<div class="field-group hidden">
  <div class="form-group">
    <select class="form-group__input form-group__input-select" id="<?php echo "{$this->prefix}__params[sheetId]"; ?>" name="<?php echo "{$this->prefix}__params[sheetId]"; ?>" placeholder="Sheet">
    </select>
    <label for="" class="form-group__label">Sheet</label>
  </div>
</div>

<div class="field-group hidden">
  <div class="form-group">
    <select class="form-group__input form-group__input-select" id="<?php echo "{$this->prefix}__params[chartType]"; ?>" name="<?php echo "{$this->prefix}__params[chartType]"; ?>" >
      <option value="" selected>Select Chart Type</option>
      <option value="scatter">Scatter Chart</option>
      <option value="pie">Pie Chart</option>
      <option value="bar">Bar Chart</option>
    </select>
    <label for="" class="form-group__label">Chart Type</label>
  </div>
</div>

<div class="field-group">
  <div class="form-group">
    <input type="hidden" id="<?php echo "{$this->prefix}__params[fileId]"; ?>" name="<?php echo "{$this->prefix}__params[fileId]"; ?>" >
    <!-- <label for="" class="form-group__label">File Id</label> -->
  </div>
</div>

<div class="field-group hidden">
  <div class="form-group">
    <input type="text" class="form-group__input" id="<?php echo "{$this->prefix}__params[chartId]"; ?>" name="<?php echo "{$this->prefix}__params[chartId]"; ?>" placeholder="Chart Id"  readonly>
    <label for="" class="form-group__label">Chart Id</label>
  </div>
</div>
