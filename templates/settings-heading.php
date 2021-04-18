<!-- Create a header in the default WordPress 'wrap' container -->
<div class='<?php echo $this->plugin; ?> wrap'>

  <div id="<?php echo $this->class_module(); ?>">

    <div class="icon32" id="icon-tools"></div> <!-- Add the icon to the page -->

    <h1><?php echo esc_html(get_admin_page_title()).(($this->name !== AXL_WP_Ultimate_NAME)? " - ".$this->name : " - Dashboard"); ?></h1> <!-- Show module title -->

    <?php settings_errors($this->class_module()); ?> <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->

     
    <!-- Set tabs -->
    <h2 class="nav-tab-wrapper">
      <?php foreach ($this->admin_tabs() as $tab_id => $tab) { ?>
      <a href="?page=<?php echo $this->class_module(); ?>&tab=<?php echo $tab_id ?>" class="nav-tab <?php echo ($this->active_tab == $tab_id)? 'nav-tab-active' : ''; ?>"><?php echo $tab['tab_title'] ?></a>
      <?php } ?>
    </h2>

    <!-- Content wrapper -->
    <div class = "content-wrapper">