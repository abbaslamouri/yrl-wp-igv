<div class = "<?php echo "{$this->class_module()}"; ?> settings">
  <form action='options.php' method='post'>
    <?php settings_fields($this->active_tab); ?>
    <input type="hidden" name="active_tab" value="<?php echo $this->active_tab ?>">
    <?php submit_button('Reset Settings', 'primary', 'reset_settings', false, array('onclick' => "return confirm ('Are you sure?');")); ?>
  </form>
  
  <div id="poststuff"><?php do_meta_boxes( $this->page_hook, 'advanced', null ); ?></div>     
 <!--  <form action='options.php' method='post'> --> <!-- Create the form that will be used to render our options --> 
  
   
    <div id="poststuff">
      <div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
        <div id="postbox-container-1" class="postbox-container"><?php do_meta_boxes( $this->page_hook, 'side', null ); ?></div>
        <div id="postbox-container-2" class="postbox-container"><?php do_meta_boxes( $this->page_hook, 'normal', null ); ?></div>
      </div>
      <br class="clear">
    </div><!-- #poststuff -->
  <!-- </form> -->
</div>
