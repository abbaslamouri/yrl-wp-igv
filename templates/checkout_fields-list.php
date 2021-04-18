
<div class = '<?php echo $this->class_module(); ?> list'>

  <?php if (!empty(get_option($this->class_module()."_checkout_fields"))) { ?>
    <table  class="wp-list-table widefat striped list">
      <thead><tr><th></th><th>ID</th><th>Label</th><th>Type</th><th>Required</th><th>Placeholder</th><th>CSS Class</th><th>Actions</th></tr></thead>
      <tfoot><tr><th></th><th>ID</th><th>Label</th><th>Type</th><th>Required</th><th>Placeholder</th><th>Css Class</th><th>Actions</th></tr></thead>
     
      <tbody>
        
        <?php  $position = 1; ?> 
        <?php foreach (get_option($this->class_module()."_checkout_fields") as $key => $fields) { ?>
          <tr id= "<?php echo "rows_{$position}" ?>" class="list-row ui-state-default">
            <td class ="move"><span class="dashicons dashicons-move"></span></td>
            <td><?php echo (isset($fields['checkout_field_id']))? $fields['checkout_field_id'] : "" ?></td>
            <td><?php echo (isset($fields['checkout_field_title']))? $fields['checkout_field_title'] : "" ?></td>
            <td><?php echo (isset($fields['checkout_field_type']))? $fields['checkout_field_type'] : "" ?></td>
            <td><?php echo (isset($fields['checkout_field_required']) && $fields['checkout_field_required'])? "TRUE" : "FALSE";  ?></td>
             <td><?php echo (isset($fields['checkout_field_placeholder']))? $fields['checkout_field_placeholder'] : "" ?></td>
            <td><?php echo (isset($fields['checkout_field_css_class']))? $fields['checkout_field_css_class'] : "" ?></td>
           
      
            <td class="actions">
              <form action='' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $fields['checkout_field_id']; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', "{$this->plugin}-edit-record", false);?>
              </form>

             <?php //if (! in_array($fields['checkout_field_id'], array('description', 'additional_information', 'reviews'))) : ?>
              <form action='options.php' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $fields['checkout_field_id']; ?>" />
                <?php 
                // Output nonce, action, and option_page fields for a settings page
                settings_fields($this->class_module()."_checkout_fields");   // usees the settings collect (section id in this case) 
                submit_button('Delete', 'delete small', "{$this->plugin}-delete-record", false, array('onclick' => "return confirm ('Are you sure?');"));
                ?>
              </form>
            <?php //endif; ?>
            </td>
          </tr>
          <?php $position++; ?>
        <?php } ?>

      </tbody>
    </table>

    <?php


    ?>
  <?php } ?>
</div>



        