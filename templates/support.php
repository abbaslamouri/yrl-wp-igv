<!-- Create a header in the default WordPress 'wrap' container -->
<div class='<?php echo $this->plugin; ?>'>

Support

  <!-- Select active tav (fiest key if GET is not set-->
  <?php $active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : array_keys($this->admin_tabs())[0];?>

  <!-- Show module title -->
  <h1>
    <?php echo esc_html(get_admin_page_title()) ?>
  </h1>

  <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
  <?php settings_errors($this->prefix);?>



  <!-- Set tabs -->
  <h2 class="nav-tab-wrapper">
    <?php foreach ($this->admin_tabs() as $tab_id => $tab) {?>
    <a href="?page=<?php echo $this->prefix; ?>&tab=<?php echo $tab_id ?>"
      class="nav-tab <?php echo ($active_tab == $tab_id) ? 'nav-tab-active' : ''; ?>"><?php echo $tab['tab_title'] ?></a>
    <?php }?>
  </h2>

  <!-- Content wrapper -->
  <div class="content-wrapper">

    <!-- Display other tabs -->
    <?php foreach ($this->admin_tabs() as $tab_id => $tab) {?>
    <?php if ($active_tab == $tab_id) {?>
    <div class="options">
      <?php if ($active_tab === "{$this->prefix}_settings"): ?>
        <a href="?page=<?php echo $this->prefix; ?>&tab=<?php echo $tab_id ?>&action=create-default-pages">
        <?php submit_button('Create Default Pages', 'primary', 'create-default-pages');?>
        </a>
      <?php endif; ?>
    
      <!-- Create the form that will be used to render our options -->
      <form action='options.php' method='post'>

        <!-- Menu fields -->
        <?php settings_fields($tab_id);?>

        <!-- Menu sections -->
        <?php do_settings_sections($tab_id);?>

        <!-- Customize submit button -->
        <?php submit_button('', 'primary', 'submit');?>


      </form>
    </div>
    <?php }}?>

  </div> <!-- // END <div class = "content-wrapper"> -->

</div> <!-- // END <div class='<?php //echo $this->plugin; ?> wrap'> -->