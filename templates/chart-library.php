<!-- Create a plugin wraper in the default WordPress 'wrap' container -->
<div class='<?php echo $this->prefix; ?>'>

  <!-- Show plugin title -->
  <h1>
    <?php echo esc_html(get_admin_page_title()) ?>
  </h1>

  <?php // var_dump($payload);die; ?>

  <!-- Admin messages -->
  <div class="admin-messages"></div>
  
  <div class="content-wrapper">

    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "edit-chart"), admin_url('admin.php')); ?>" >
      <?php esc_attr_e( 'Add New Chart' ); ?>
    </a>

    <div class="chart-library">
      <form id="<?php echo "{$this->prefix}__listChartsForm" ?>" class="form" action="#">
        <?php
        if (!empty($payload)) :
          foreach($payload as $chart_id => $chart):
            ?>
            <div class="card" id="<?php echo "{$this->prefix}__chart__{$chart["chartParams"]["options"]["chartId"]}__card"; ?>">
              <h2 class="card__heading"><?php echo $chart['chartParams']['options']['fileUpload']; ?></h2>
              <div class="card_content">
                <div class="chart-container">
                  <div class="chart" id="<?php echo "{$this->prefix}__chart__{$chart["chartParams"]["options"]["chartId"]}"; ?>"></div>
                  <img class="loading-spinner-<?php echo $chart["chartParams"]["options"]["chartId"];?> hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">
                </div>
              </div>
              <div class="card__footer">
                <div class="shortcode">[<?php echo "{$this->prefix}_chart id={$chart["chartParams"]["options"]["chartId"]}" ?>]</div>
                <div class="actions">
                  <a  href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "edit-chart", 'chartId' =>$chart["chartParams"]["options"]["chartId"]), admin_url('admin.php')); ?>" title="Edit">
                    <svg class="edit" id='<?php echo "{$this->prefix}__editChart[{$chart["chartParams"]["options"]["chartId"]}]" ?>'>
                      <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-pencil"; ?>"></use>
                    </svg>
                  </a>
                  <a  href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "delete-chart", 'chartId' => $chart["chartParams"]["options"]["chartId"]), admin_url('admin.php')); ?>" title="Delete">
                    <svg class="delete" id='<?php echo "{$this->prefix}__deleteChart[{$chart["chartParams"]["options"]["chartId"]}]" ?>'>
                    <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-bin"; ?>"></use>
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
        <?php endif; ?>
      </form>
    </div>
  </div>

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->