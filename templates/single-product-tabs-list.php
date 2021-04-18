
<div class = '<?php echo $this->class_module(); ?> list'>

  <?php if ( empty(get_option($this->class_module()."_single_product_tabs"))) : ?>

     There are no single product tabs to display

  <?php else : ?>
    <table  class="wp-list-table widefat striped list">
      <thead><tr><th></th><th>ID</th><th>Title</th><th>Visiblity</th><th>Callback</th><th>Actions</th></tr></thead>
      <tfoot><tr><th></th><th>ID</th><th>Title</th><th>Visiblity</th><th>Callback</th><th>Actions</th></tr></thead>
     
      <tbody>
        
        <?php  $position = 1; ?> 
        <?php foreach (get_option($this->class_module()."_single_product_tabs") as $key => $product_tab) { ?>
          <tr id= "<?php echo "rows_{$position}" ?>" class="list-row ui-state-default">
            <td class ="move"><span class="dashicons dashicons-move"></span></td>
            <td><?php echo (isset($product_tab['tab_id']))? $product_tab['tab_id'] : "" ?></td>
            <td><?php echo (isset($product_tab['title']))? $product_tab['title'] : "" ?></td>
            <td><?php echo (isset($product_tab['hidden']) && $product_tab['hidden'])? "Hidden" : "Visible";  ?></td>
            <td><?php echo (isset($product_tab['callback']))?  $product_tab['callback'] : ""; ?></td>
      
            <td  class="actions">
              <form action='' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $product_tab['tab_id']; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', "{$this->plugin}-edit-record", false);?>
              </form>

             <?php if (! in_array($product_tab['tab_id'], array('description', 'additional_information', 'reviews'))) : ?>
              <form action='options.php' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $product_tab['tab_id']; ?>" />
                <?php 
                // Output nonce, action, and option_page fields for a settings page
                settings_fields($this->class_module()."_single_product_tabs");   // usees the settings collect (section id in this case) 
                submit_button('Delete', 'delete small', "{$this->plugin}-delete-record", false, array('onclick' => "return confirm ('Are you sure?');"));
                ?>
              </form>
            <?php endif; ?>
            </td>
          </tr>
          <?php $position++; ?>
        <?php } ?>

      </tbody>
    </table>

    <?php


    ?>
  <?php endif; ?>
</div>



        