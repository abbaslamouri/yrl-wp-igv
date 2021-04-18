 <?php foreach($tab['sections'] as $section_id => $section) {?>
  
  <h1><?php echo $section['title'] ?></h1>
  <table id = "<?php echo $record_id ?>" class="wp-list-table widefat fixed striped record-table">
    <col width="20%"><col widht="80%">
    <tbody>
      <?php
      foreach ($this->admin_fields() as $field_id => $field) {

        // bail if field tab is not the same as the active tab
        if ($field['tab'] != $this->active_tab || $field['section'] != $section_id ) continue;

        // Set value
        $field['value'] = (isset($options[$field_id]))? $options[$field_id] : "";     

        // Set remaining fields
        foreach ($this->possible_field_options() as $option_key => $option_value) {
          if (! isset($field[$option_key])) $field[$option_key] = $option_value;
        }

        // render field
        ?> <tr><?php
        if(file_exists($this->path."templates/".$field['field_type'].".php")) {
          ?><td><?php echo $field['title'] ?></td><td><?php require ( $this->path.'templates/'.$field['field_type'].".php"); ?></td><?php 
        } else {
          ?><td><?php _e("<div class = 'admin-errors'> Template ".$this->path."templates/".$field['field_type'].".php does not exist</div>", $this->plugin); ?></td><?php
        }
        ?> </tr><?php
      }
      ?>
    </tbody>
  </table>
  <?php submit_button("Save Changes", 'primary', 'submit', true); ?><br style = "clear:both;">

<?php } ?>