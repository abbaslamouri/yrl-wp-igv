<!-- Create a header in the default WordPress 'wrap' container -->
<div class='<?php echo $this->plugin; ?> wrap'>
<?php //echo "<pre>"; var_dump($payload) ?>

  <!-- Show module title -->
  <div class="admin-heading">
    <h2 ><?php echo esc_html(get_admin_page_title()); ?></h2>
  </div>

  <div class="content-wrapper admin">
    <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->plugin, "action" => "add-new-chart"), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Add New' ); ?>"><?php esc_attr_e( 'Add New Chart' ); ?></a>

    <div class="chart-library">
      <?php
      if (!empty($payload)) :
        foreach($payload as $chart_id => $chart):
          ?>
          <div class="card">
            <h2 class="card__heading"><?php echo $chart['fileName']; ?></h2>
            <div class="card_content">
              <div class="chart-container">
                <div class="chart" id="iwpgv__chart__<?php echo $chart_id; ?>"></div>
                <img class="loading-spinner-<?php echo $chart_id;?> hidden" src="<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>" alt="Loading Spinner">
              </div>
            </div>
            <div class="card__footer">
              <div class="shortcode">[<?php echo "iwpgv-{$chart_id}" ?>]</div>
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
        
      endif;
      ?>
    </div>
  </div> 
</div>