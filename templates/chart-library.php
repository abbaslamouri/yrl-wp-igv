<div class="chart-library">

  <!-- Show plugin title -->
  <h2 class="page-title">
    <?php echo esc_html(get_admin_page_title()) ?>
    <a class="button-secondary btn" id="<?php echo"{$this->prefix}__addNewChart"; ?>" >
      <?php esc_attr_e( 'Add New Chart' ); ?>
    </a>
  </h2>
  
  <div class="chart-library__content">
    <div class='loading'>Loading...</div> 
    <img class='loading-spinner' src='<?php echo "{$this->url}assets/img/loading-spinner.svg" ?>' alt='Loading Spinner'>
  </div>

</div>
