            
<p class="ac-text intro">Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>

<div class="field-group">
  <div class="form-group">
    <button type="button" class="form-group__btn btn button" id="<?php echo "{$this->prefix}__fileUpload[mediaUploadBtn]"; ?>" name="<?php echo "{$this->prefix}__fileUpload[mediaUploadBtn]"; ?>" >Upload New File </button>
    <label for="" class="form-group__label"></label>
  </div>
</div>

<div class="field-group">
  <div class="form-group">
    <input type="text" class="form-group__input fileUpload no-hint" id="<?php echo "{$this->prefix}__fileUpload[fileName]"; ?>" name="<?php echo "{$this->prefix}__fileUpload[fileName]"; ?>" placeholder="Selected File" readonly>
    <label for="" class="form-group__label">Selected File</label>
  </div>
</div>

<div class="field-group">
  <div class="form-group">
    <input type="text" class="form-group__input fileUpload no-hint" id="<?php echo "{$this->prefix}__fileUpload[chartId]"; ?>" name="<?php echo "{$this->prefix}__fileUpload[chartId]"; ?>" placeholder="Chart Id"  readonly>
    <label for="" class="form-group__label">Chart Id</label>
  </div>
</div>

<div class="field-group hidden">
  <div class="form-group">
    <select class="form-group__input form-group__input-select fileUpload no-hint" id="<?php echo "{$this->prefix}__fileUpload[sheetId]"; ?>" name="<?php echo "{$this->prefix}__fileUpload[sheetId]"; ?>" placeholder="Sheet">
    </select>
    <label for="" class="form-group__label">Sheet</label>
  </div>
</div>

<div class="field-group hidden">
  <div class="form-group">
    <select class="form-group__input form-group__input-select fileUpload no-hint" id="<?php echo "{$this->prefix}__fileUpload[chartType]"; ?>" name="<?php echo "{$this->prefix}__fileUpload[chartType]"; ?>" >
      <option value="" selected>Select Chart Type</option>
      <option value="LineChart">Line Chart</option>
      <option value="ScatterChart">Scatter Chart</option>
      <option value="PieChart">Pie Chart</option>
      <option value="BarChart">Bar Chart</option>
    </select>
    <label for="" class="form-group__label">Chart Type</label>
  </div>
</div>

<div class="field-group">
  <div class="form-group">
    <input type="text" id="<?php echo "{$this->prefix}__fileUpload[fileId]"; ?>" name="<?php echo "{$this->prefix}__fileUpload[fileId]"; ?>" >
    <label for="" class="form-group__label">File Id</label>
  </div>
</div>
