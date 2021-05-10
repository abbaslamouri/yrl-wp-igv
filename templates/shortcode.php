
<div class="<?php echo "{$this->prefix}__public"; ?>">
  <!-- Admin messages -->
  <div class="messages"></div>

  <div class="plotly">
    <div class="chart" id="<?php echo "{$this->prefix}__plotlyChart__{$payload["chart"]["chartParams"]["options"]["chartId"]}"; ?>" ></div>
    <div class="min-max-avg-table">
      <?php require "{$this->path}templates/min-max-inputs.php"; ?>
      <div class="table" id="<?php echo "{$this->prefix}__plotlyMinMaxAvgTable__{$payload["chart"]["chartParams"]["options"]["chartId"]}"; ?>" ></div>
    </div>
  </div>

<div>



  


