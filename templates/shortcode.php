

 <!-- Admin messages -->
 <div class="public-messages"></div>
 <?php var_dump($payload["chart"]["chartParams"]["options"]["chartId"]); ?>
 
 <div id="<?php echo "{$this->prefix}__plotly-{$payload["chart"]["chartParams"]["options"]["chartId"]}"; ?>" ></div> 


