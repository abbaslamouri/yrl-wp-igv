<!-- Create a plugin wraper in the default WordPress 'wrap' container -->
<div class='<?php echo $this->prefix; ?>'>

  <!-- Show plugin title -->
  <h1>
    <?php echo esc_html(get_admin_page_title()) ?>
  </h1>

  <?php // var_dump($payload);die; ?>


  <div class="content-wrapper">

    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "edit-chart"), admin_url('admin.php')); ?>" >
      <?php esc_attr_e( 'Add New Chart' ); ?>
    </a>

    <div class="chart-library">
      <?php
      if (!empty($payload)) :
        foreach($payload as $chart_id => $chart):
          ?>
          <div class="card">
            <h2 class="card__heading"><?php echo $chart['chartParams']['options']['fileUpload']; ?></h2>
            <div class="card_content">
              <div class="chart-container">
                <div class="chart" id="<?php echo "{$this->prefix}__chart__{$chart_id}"; ?>"></div>
                <img class="loading-spinner-<?php echo $chart_id;?> hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">
              </div>
            </div>
            <div class="card__footer">
              <div class="shortcode">[<?php echo "{$this->prefix}_chart id={$chart_id}" ?>]</div>
              <div class="actions">
                <a  href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "edit-chart", 'chartId' =>$chart_id), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Edit' ); ?>">
                  <svg class="edit" id='<?php echo "{$this->prefix}__delete_chart" ?>'>
                    <use xlink:href="<?php echo "{$this->url}assets/img/icons.svg#icon-bin"; ?>"></use>
                  </svg>
                </a>
                <a  href="<?php echo add_query_arg(array("page" => $this->prefix, "action" => "edit-chart", 'chartId' =>$chart_id), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Edit' ); ?>">
                  <svg class="delete" id='<?php echo "{$this->prefix}__delete_chart" ?>'>
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
      <?php endif; ?>
    </div>
  </div>

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->