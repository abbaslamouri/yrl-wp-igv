
<div class = '<?php echo $this->class_module(); ?> list'>

  <?php if (!empty(get_option($this->class_module()."_add_field"))) { ?>
    <table id = "list">
      <col>
      <tr><th>ID</th><th>Label</th><th>Type</th><th>Options</th><th>Required</th><th>Position</th><th>Actions</th></tr>
      <tbody>

        <?php foreach (get_option($this->class_module()."_add_field") as $key => $field) { ?>
          <tr>
            <td><?php echo (isset($field['field_id']))? $field['field_id'] : "" ?></td>
            <td><?php echo (isset($field['field_label']))? $field['field_label'] : "" ?></td>
            <td><?php echo (isset($field['field_type']))? $field['field_type'] : "" ?></td>
            <td><?php echo (($field['field_type'] == 'select' || $field['field_type'] == 'radio') && isset($field['field_options']))? $field['field_options'] : "N/A" ?></td>
            <td><?php echo (isset($field['field_required']))? "TRUE" : "FALSE" ?></td>
            <td><?php echo (isset($field['field_position']))?  $field['field_position'] : ""; ?></td>
      
            <td>
              <form action='' method='post'>
                <input type="hidden" name = "edit" value = "<?php echo $field['field_id']; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', 'submit', false);?>
              </form>

              <?php if ($field['field_id'] != 'cf-name' && $field['field_id'] != 'cf-subject' && $field['field_id'] != 'cf-email' && $field['field_id'] != 'cf-message' && $field['field_id'] != 'cf-gdpr') : ?>
              <form action='options.php' method='post'>
                <input type="hidden" name = "delete" value = "<?php echo $field['field_id']; ?>" />
                <?php 
                // Output nonce, action, and option_page fields for a settings page
                settings_fields($this->class_module()."_add_field");   // usees the settings collect (section id in this case) 
                submit_button('Delete', 'delete small', 'submit', false, array('onclick' => "return confirm ('Are you sure?');"));
                ?>
              </form>
            <?php endif; ?>
            </td>
          </tr>
        <?php } ?>

      </tbody>
    </table>

    <?php


    ?>
  <?php } ?>
</div>


        