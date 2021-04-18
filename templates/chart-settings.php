<!-- Create a header in the default WordPress 'wrap' container -->
<div class='<?php echo $this->plugin; ?>'>

  <!-- Show module title -->
  <h1><?php echo esc_html(get_admin_page_title()) ?></h1>

  <!-- Select active tab
  <?php $active_tab = (isset($_GET['tab'])) ? $_GET['tab'] :"{$this->prefix}_settings";?>

  <!-- Set tabs -->
  <h2 class="nav-tab-wrapper">
    <?php foreach ($this->admin_sections() as $section_id => $section) : ?>
    <?php if ($section_id !== "{$this->prefix}_settings"):?>
        <a href="?page=<?php echo "{$this->prefix}_settings"; ?>&tab=<?php echo $section_id ?>"
          class="nav-tab <?php echo ($active_tab == $section_id) ? 'nav-tab-active' : ''; ?>">
            <?php echo $section['tabTitle'] ?>
        </a>
    <?php endif; ?>
    <?php endforeach; ?>
  </h2>


  <!-- Content wrapper -->
  <div class="content-wrapper">

    <?php foreach ($this->admin_sections() as $section_id => $section) : ?>
      <?php if ( $section_id === $active_tab) : ?>

     <!-- Make a call to the WP options error function -->
     <?php settings_errors($section_id);?>
    
      <form action='options.php' method='post'>

        <!-- Menu fields -->
        <?php settings_fields($section_id);?>

        <!-- Menu sections -->
        <?php do_settings_sections($section_id);?>

        <!-- Customize submit button -->
        <?php submit_button('', 'primary', 'submit');?>

      </form>

      <?php endif; ?>
    <?php endforeach; ?>
   
  </div> <!-- "content-wrapper"> -->

</div>