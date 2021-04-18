
<div id = "" class="<?php echo $this->plugin; ?> user-logout widecolumn">

  <?php $page = $this->fetch_page( $this->class_module().'_pages', 'redirect-after-logout'); ?>

  <div> <?php echo __( "You are signed in", $this->plugin); ?></div>
  <a href='<?php echo wp_logout_url( home_url($page )); ?>'>Logout</a>

</div>
