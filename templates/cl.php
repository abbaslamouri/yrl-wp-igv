<?php
if (!empty($payload)) :
  foreach($payload as $chart_id => $chart):
    ?>
    sdasdadsasadas
    <div class="card">
      <h2 class="card__heading"><?php echo $chart['fileName']; ?></h2>
      <div class="card_content">
        <div class="chart-container">
          <div class="chart" id="iwpgv__chart__<?php echo $chart_id; ?>"></div>
          <img class="loading-spinner-<?php echo $chart_id;?> hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">
        </div>
      </div>
      <div class="card__footer">
        <div class="shortcode">[<?php echo "iwpgv-chart id={$chart_id}" ?>]</div>
        <div class="actions">
          <a  href="<?php echo add_query_arg(array("page" => $this->plugin, "action" => "edit-chart", 'chartId' =>$chart_id), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Edit' ); ?>">
            <svg class="edit" id='iwpgv__delete_chart'>
              <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-bin"; ?>"></use>
            </svg>
          </a>
          <a  href="<?php echo add_query_arg(array("page" => $this->plugin, "action" => "edit-chart", 'chartId' =>$chart_id), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Edit' ); ?>">
            <svg class="delete" id='iwpgv__delete_chart'>
              <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-pencil"; ?>"></use>
            </svg>
          </a>
        </div>
      </div>
    </div>
    <?php 
  endforeach;

else :
  ?>
<div> Ther no charts to display</div>
<?php
endif;
  