<!-- Create a header in the default WordPress 'wrap' container -->
<div class='<?php echo $this->plugin; ?> wrap'>
<?php //echo "<pre>"; var_dump($payload) ?>

  <?php $active_tab = (isset($_GET['tab'])) ? $_GET['tab'] : array_keys($this->admin_tabs())[0];?>

  <!-- Show module title -->
  <div class="admin-heading">
    <h2 ><?php echo esc_html(get_admin_page_title()); ?></h2>
  </div>

  <div class="admin-messages"></div>

  <!-- Set tabs -->
  <div class="nav-tabs-wrapper">
    <?php foreach ($this->admin_tabs() as $tab_id => $tab) {?>
    <a href="?page=<?php echo $this->prefix; ?>&tab=<?php echo $tab_id ?>"
      class="nav-tab <?php echo ($active_tab == $tab_id) ? 'nav-tab-active' : ''; ?>"><?php echo $tab['tab_title'] ?></a>
    <?php }?>
  </div>

  <div class="content-wrapper admin">

    <!-- <div class="content"> -->
      

       <!-- Display other tabs -->
      <?php foreach ($this->admin_tabs() as $tab_id => $tab) : ?>
        <?php if ($active_tab == $tab_id && $tab_id === "{$this->prefix}_settings") : ?>

          <a class="button-secondary btn" href="<?php echo add_query_arg(array("page" => $this->plugin, "action" => "add-new-chart"), admin_url('admin.php')); ?>" title="<?php esc_attr_e( 'Add New' ); ?>"><?php esc_attr_e( 'Add New Chart' ); ?></a>

          <div class="chart-list">
            <?php require ('chart-list.php'); ?>
          </div>

        <?php elseif ($active_tab == $tab_id && $tab_id === "{$this->prefix}_files"): ?>

          <div class="file-list">
          <?php require ('file-list.php'); ?>
          </div>

        <?php endif; ?>

      <?php endforeach; ?>

      
    <!-- </div> -->
  
   
  
  </div>


  
  
</div>