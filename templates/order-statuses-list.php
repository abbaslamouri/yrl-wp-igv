<div class = '<?php echo $this->class_module(); ?> list'>

  <?php if ( empty(get_option($this->class_module()."_order_statuses"))) : ?>

    There are no order statuses to display

  <?php else : ?>

 
    <table  class="wp-list-table widefat striped list">
      <thead><tr><th></th><th>ID</th><th>status_Title</th><th>Position</th><th>Actions</th></tr></thead>
      <tfoot><tr><th></th><th>ID</th><th>status_Title</th><th>Position</th><th>Actions</th></tr></thead>

      <tbody>
        
        <?php  $position = 1; ?> 
        <?php foreach (get_option($this->class_module()."_order_statuses") as $key => $status) { ?>
          <tr id= "<?php echo "rows_{$position}" ?>" class="list-row ui-state-default">
            <td class ="move"><span class="dashicons dashicons-move"></span></td>
            <td><?php echo (isset($status['status_id']))? $status['status_id'] : "" ?></td>
            <td><?php echo (isset($status['status_title']))? $status['status_title'] : "" ?></td>
            <td><?php echo (isset($status['status_priority']))? $status['status_priority'] : "" ?></td>
           
      
            <td  class="actions">
              <form action='' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $status['status_id']; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', "{$this->plugin}-edit-record", false);?>
              </form>

             <?php if (! in_array($status['status_id'], array_keys($this->core_order_statuses()))) : ?>
              <form action='options.php' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $status['status_id']; ?>" />
                <?php 
                // Output nonce, action, and option_page fields for a settings page
                settings_fields($this->class_module()."_order_statuses");   // usees the settings collect (section id in this case) 
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
  <?php endif;?>

</div>




        