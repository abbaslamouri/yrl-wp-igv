<div class = '<?php echo $this->class_module(); ?> list'>

  <?php if ( empty(get_option($this->class_module()."_fields"))) : ?>

    There are no contact form fields to display

  <?php else : ?>
    
    <table class="wp-list-table widefat striped list">
      <thead><tr><th></th><th>ID</th><th>Label</th><th>Type</th><th>Options</th><th>Required</th><th>Actions</th></tr></thead>
      <tfoot><tr><th></th><th>ID</th><th>Label</th><th>Type</th><th>Options</th><th>Required</th><th>Actions</th></tr></thead> 
      <tbody>
        
        <?php $position = 1; ?>
        <?php foreach (get_option($this->class_module()."_fields") as $key => $field) { ?>
          <tr id= "<?php echo "rows_{$position}" ?>" class="list-row ui-state-default">
            <td class ="move"><span class="dashicons dashicons-move"></span></td>
            <td><?php echo (isset($field['field_id']))? $field['field_id'] : "" ?></td>
            <td><?php echo (isset($field['field_label']))? $field['field_label'] : "" ?></td>
            <td><?php echo (isset($field['field_type']))? $field['field_type'] : "" ?></td>
            <td><?php echo (($field['field_type'] == 'select' || $field['field_type'] == 'radio') && isset($field['field_options']))? $field['field_options'] : "N/A" ?></td>
            <td><?php echo (isset($field['field_required']))? "TRUE" : "FALSE" ?></td>
      
            <td class="actions">
              <form action='' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $field['field_id']; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', "{$this->plugin}-edit-record", false);?>
              </form>

              <?php if (! in_array($field['field_id'], array('cf-name', 'cf-subject', 'cf-email', 'cf-message', 'cf-gdpr'))) : ?>
              <form action='options.php' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $field['field_id']; ?>" />
                <?php 
                // Output nonce, action, and option_page fields for a settings page
                settings_fields($this->class_module()."_fields");   // usees the settings collect (section id in this case) 
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



        