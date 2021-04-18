
<div class = '<?php echo $this->class_module(); ?> list'>

  <?php if ( empty (get_option($this->class_module()."_add_cpt"))) :?>

    There are no custom post types to display

  <?php else : ?>

  <?php //if (!empty(get_option($this->class_module()."_add_cpt"))) { ?>
    <table id = "list">
      <col>
      <tr><th>ID</th><th>cpt_Singular</th><th>cpt_Plural</th><th>Supports</th><th>cpt_Private</th><th>Has Archives</th><th>Actions</th></tr>
      <tbody>

        <?php 
        foreach (get_option($this->class_module()."_add_cpt") as $key => $cpt) { 
          $supports = array();
            foreach ($cpt as $cpt_key => $cpt_value) {
              if (strpos($cpt_key, 'cpt_supports_') !== false) {
                $supports[] = substr($cpt_key,strpos($cpt_key, 'supports_') + strlen('supports_'));
              }
            }

          ?>
          <tr>
            <td><?php echo (isset($cpt['cpt_slug']))?  $cpt['cpt_slug'] : ""; ?></td>
            <td><?php echo (isset($cpt['cpt_singular']))? $cpt['cpt_singular'] : "" ?></td>
            <td><?php echo (isset($cpt['cpt_plural']))? $cpt['cpt_plural'] : "" ?></td>
            <td><?php echo (empty($supports))? "NONE" : implode(",  ", $supports); ?></td>
            <td><?php echo (isset($cpt['cpt_private']))? "TRUE" : "FALSE" ?></td>
            <td><?php echo (isset($cpt['cpt_has_archive']))? "TRUE" : "FALSE" ?></td>
            <td>
              <form action='' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $cpt['cpt_slug']; ?>" />
                <input type="hidden" name = "module" value = "<?php echo $this->class_module(); ?>" />
                <?php submit_button('Edit', 'primary small', "{$this->plugin}-edit-record", false);?>
              </form>
              <form action='options.php' method='post'>
                <input type="hidden" name = "record_id" value = "<?php echo $cpt['cpt_slug']; ?>" />
                <?php 
                settings_fields($this->class_module()."_add_cpt");   // usees the settings collect (section id in this case) 
                submit_button('Delete', 'delete small', "{$this->plugin}-delete-record", false, array('onclick' => "return confirm ('Are you sure?');"));
                ?>
              </form>

            </td>
          </tr>
        <?php } ?>

      </tbody>
    </table>

    <?php


    ?>
  <?php endif; ?>
</div>


        