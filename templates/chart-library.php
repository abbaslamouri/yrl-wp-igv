<!-- Create a plugin wraper in the default WordPress 'wrap' container -->
<div class="chart-library">

  <!-- Show plugin title -->
  <h2 class="page-title">
    <?php echo esc_html(get_admin_page_title()) ?>
    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "edit-chart"), admin_url('admin.php')); ?>" >
      <?php esc_attr_e( 'Add New Chart' ); ?>
    </a>
  </h2>

  <?php //var_dump($payload);die; ?>

  <!-- Admin messages -->
  <div class="admin-messages"></div>
  
  <div class="chart-library__content">
    <!-- <form id="<?php // echo "{$this->prefix}__listChartsForm" ?>" class="form" action="#"> -->
      <?php
      if (!empty($payload)) :
        foreach($payload as $chart_id => $chart):
          ?>
          <div class="card" id="<?php echo "{$this->prefix}__chart__{$chart["fileUpload"]["chartId"]}__card"; ?>">
            <h2 class="card__heading">File: <?php echo $chart['fileUpload']['fileName']; ?></h2>
            <div class="card_content">
              <div class="chart-container">
                <div class="chart" id="<?php echo "{$this->prefix}__chart__{$chart["fileUpload"]["chartId"]}"; ?>"></div>
                <img class="loading-spinner-<?php echo $chart["fileUpload"]["chartId"];?> hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">
              </div>
            </div>
            <div class="card__footer">
              <div class="shortcode">[<?php echo "{$this->prefix}_chart id={$chart["fileUpload"]["chartId"]}" ?>]</div>
              <div class="actions">
                <a href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "clone-chart", 'chartId' =>$chart["fileUpload"]["chartId"]), admin_url('admin.php')); ?>" >
                  <svg class="clone" id='<?php echo "{$this->prefix}__cloneChart[{$chart["fileUpload"]["chartId"]}]" ?>'>
                    <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-file_copy"; ?>"></use>
                  </svg>
                </a>
                <a href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "edit-chart", 'chartId' =>$chart["fileUpload"]["chartId"]), admin_url('admin.php')); ?>" >
                  <svg class="edit" id='<?php echo "{$this->prefix}__editChart[{$chart["fileUpload"]["chartId"]}]" ?>'>
                    <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-pencil"; ?>"></use>
                  </svg>
                </a>
                <a class="delete-chart" href="#" title="Delete" data-chart-id="<?php echo $chart["fileUpload"]["chartId"]; ?>">
                  <svg class="delete" id='<?php echo "{$this->prefix}__deleteChart[{$chart["fileUpload"]["chartId"]}]" ?>' >
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
        <div class="no-charts"> Ther no charts to display</div>
      <?php endif; ?>
    <!-- </form> -->
  </div>
  <!-- </div> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->