

 <!-- Admin messages -->
 <div class="public-messages"></div>
 
 <div class="public-plotly">
  <div id="<?php echo "{$this->prefix}__plotlyChart__{$payload["chart"]["chartParams"]["options"]["chartId"]}"; ?>" ></div>
  <div class="table">
  <form id="<?php echo "{$this->prefix}__plotMinMax" ?>" class="form" action="#">
      <div class="form__group hidden">
        <input type="number" id = "<?php echo "{$this->prefix}__rangeMinInput" ?>" class="form__input" placeholder="Range Min" required>
        <label class="form__label" for="<?php echo "{$this->prefix}__rangeMinInput" ?>">Range Min</label>
      </div>
      <div class="form__group hidden">
        <input type="number" id = "<?php echo "{$this->prefix}__rangeMaxInput" ?>" class="form__input" placeholder="Range Max" required>
        <label class="form__label" for="<?php echo "{$this->prefix}__rangeMaxInput" ?>">Range Max</label>
      </div>
    </form>
    <div id="<?php echo "{$this->prefix}__plotlyMinMaxAvgTable__{$payload["chart"]["chartParams"]["options"]["chartId"]}"; ?>" ></div>
    <!-- X-axis Min and Max inputs go here -->
    
    
  </div>
  
</div>



  


