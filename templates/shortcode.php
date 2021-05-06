

 <!-- Admin messages -->
 <div class="public-messages"></div>
 
 <div id="<?php echo "{$this->prefix}__plotlyChart-{$payload["chart"]["chartParams"]["options"]["chartId"]}"; ?>" ></div>
 <div id="<?php echo "{$this->prefix}__plotlyMinMaxAvgTable-{$payload["chart"]["chartParams"]["options"]["chartId"]}"; ?>" ></div>

  <!-- X-axis Min and Max inputs go here -->
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


