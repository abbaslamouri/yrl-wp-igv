 <div id="poststuff">
  <form action='options.php' method='post'>
    <input type="hidden" name="active_tab" value="<?php echo $this->active_tab ?>">
    <?php settings_fields($atts['tab_id']); ?>
    <?php do_meta_boxes( $this->page_hook, 'advanced', null ); ?> 
  </form>
</div>
            