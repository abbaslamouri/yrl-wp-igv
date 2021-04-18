
<div class = '<?php echo $this->class_module(); ?> list'>

  <?php if ( empty(get_option($this->active_tab))) :?>
     
     There are no checkout fields to display

  <?php else : ?>

     <table  class="wp-list-table widefat striped list">
      <thead>
        <tr>
          <th>Reorder</th>
          <?php 
          foreach (array_keys(array_values(get_option($this->active_tab))[0]) as $key) {
            foreach ( $this->admin_fields() as $field_id => $field ) {
              if ($field_id == $key) { 
                ?><th><?php echo $field['title'] ?></th><?php
              }
            }
          }
          ?>
          <th>Actions</th>
        </tr>
      </thead>

       <tfoot>
        <tr>
          <th>Reorder</th>
          <?php 
          foreach (array_keys(array_values(get_option($this->active_tab))[0]) as $key) {
            foreach ( $this->admin_fields() as $field_id => $field ) {
              if ($field_id == $key) { 
                ?><th><?php echo $field['title'] ?></th><?php
              }
            }
          }
          ?>
          <th>Actions</th>
        </tr>
      </tfoot>

      <?php $position = 1; ?>
      <tbody>

        <?php foreach (get_option($this->active_tab) as $key => $fields) { ?>
          
          <tr id= "<?php echo "rows_{$position}" ?>" class="list-row ui-state-default">
            
            <td class ="move"><span class="dashicons dashicons-move"></span></td>
           
            <?php 
            foreach ( $fields as $key => $field ) {
              ?> <td><?php
              foreach ( $this->admin_fields() as $field_id => $field_content ) {


                if ($field_id == $key) { 
                  if ( isset($field_content['modal']) && $field_content['modal']) {
                    ?>
                    <div id="<?php echo "dialog-{$position}" ?>"  class="dialog-container" title="Field Options">
                      <p><?php echo (isset($field) && $field)? $field : "" ?></p>
                    </div>
                    <button id="<?php echo "opener-{$position}" ?>" class="dialog-opener">View Details</button>
                    <?php
                  } elseif ($field_content['field_type'] == "checkbox" ) { 
                    ?><?php echo ( isset($field) && $field )? "TRUE" : "FALSE" ?><?php
                  } else {
                    ?><?php echo ( isset($field) && $field )? $field : "" ?><?php
                  }                
                }
              }
              ?> </td><?php
            }
            ?>
           

            <td  class="actions">
              <form action='' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $key; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', "{$this->plugin}-edit-record", false);?>
              </form>

             <?php //if (! in_array($key, array('description', 'additional_information', 'reviews'))) : ?>
              <form action='options.php' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $key; ?>" />
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




 
    <table  class="wp-list-table widefat striped list">
      <thead><tr><th></th><th>ID</th><th>Label</th><th>Type</th><th>Options</th><th>Required</th><th>Placeholder</th><th>CSS Class</th><th>Actions</th></tr></thead>
      <tfoot><tr><th></th><th>ID</th><th>Label</th><th>Type</th><th>Options</th><th>Required</th><th>Placeholder</th><th>Css Class</th><th>Actions</th></tr></thead>
     
      <tbody>
        
        <?php $position = 1; ?> 
        <?php foreach (get_option($this->active_tab) as $key => $fields) { ?>
          <tr id= "<?php echo "rows_{$position}" ?>" class="list-row ui-state-default">
            
            <td class ="move"><span class="dashicons dashicons-move"></span></td>
            <td><?php echo (isset($key))? $key : "" ?></td>
            <td><?php echo (isset($fields['checkout_field_label']))? $fields['checkout_field_label'] : "" ?></td>
            <td><?php echo (isset($fields['checkout_field_type']))? $fields['checkout_field_type'] : "" ?></td>
            <td>
              <div id="<?php echo "dialog-{$position}" ?>"  class="dialog-container" title="Field Options">
                <p><?php echo (isset($fields['checkout_field_options']))? $fields['checkout_field_options'] : "" ?></p>
              </div>
              <button id="<?php echo "opener-{$position}" ?>" class="dialog-opener">View Options</button>
            </td>
            <td><?php echo (isset($fields['checkout_field_required']) && $fields['checkout_field_required'])? "TRUE" : "FALSE";  ?></td>
             <td><?php echo (isset($fields['checkout_field_placeholder']))? $fields['checkout_field_placeholder'] : "" ?></p></td>
            <td><?php echo (isset($fields['checkout_field_css_class']))? $fields['checkout_field_css_class'] : "" ?></td>
           
            <td  class="actions">
              <form action='' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $key; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', "{$this->plugin}-edit-record", false);?>
              </form>

             <?php //if (! in_array($key, array('description', 'additional_information', 'reviews'))) : ?>
              <form action='options.php' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $key; ?>" />
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

  endif; ?>

</div>



        