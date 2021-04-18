<!-- Create a header in the default WordPress 'wrap' container -->
<div class='<?php echo $class->plugin; ?> wrap'>

  <div id="<?php echo $class->class_module(); ?>">

    <!-- Add the icon to the page -->
    <div class="icon32" id="icon-tools"></div>

    <!-- Show module title -->
    <h1><?php echo esc_html(get_admin_page_title()).(($class->name !== AXL_WP_Ultimate_NAME)? " - ".$class->name : " - Dashboard"); ?></h1>

    <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
    <?php settings_errors($class->class_module()); ?> 


    <?php
    if (isset($_POST['edit']) || (isset($_GET['tab']) && $_GET[ 'tab' ] == $class->class_module()."_settings")) {
      $active_tab = $class->class_module()."_settings";
    } else {
      $active_tab = $class->class_module()."_list";
    }

    $button_label = (!isset($_POST['edit']))? 'Add' : 'Update';

    ?>
    
    <h2 class="nav-tab-wrapper">
      <?php foreach ($class->admin_tabs() as $tab_id => $tab) { ?>
      <a href="?page=<?php echo $class->class_module() ?>&tab=<?php echo $tab_id ?>" class="nav-tab <?php echo ($active_tab == $tab_id)? 'nav-tab-active' : ''; ?>"><?php echo $tab['tab_title'] ?></a>
      <?php } ?>
    </h2>

    <!-- Content wrapper -->
    <div class = "content-wrapper">

      <?php if ($active_tab == $class->class_module()."_list") { ?>
        <div class = '<?php echo $class->class_module(); ?> list' id = ''>
          <?php if (!empty(get_option($class->class_module()."_settings"))) { ?>
            <table>
              <tr><th>id</th><th>Singular</th><th>Plural</th><th>Supports</th><th>Private</th><th>Has Archives</th><th>Actions</th></tr>
              <tbody>
                <?php
                foreach (get_option($class->class_module()."_settings") as $key => $tab) {
                  $supports = array();
                  foreach ($tab as $cpt_key => $cpt_value) {
                    if (strpos($cpt_key, 'supports_') !== false) {
                      $supports[] = substr($cpt_key,strpos($cpt_key, 'supports_') + strlen('supports_'));
                    }
                  }
                  ?>
                  <tr>
                    <td><?php echo (isset($tab['cpt_slug']))?  $tab['cpt_slug'] : ""; ?></td>
                    <td><?php echo (isset($tab['singular']))? $tab['singular'] : "" ?></td>
                    <td><?php echo (isset($tab['plural']))? $tab['plural'] : "" ?></td>
                    <td><?php echo (empty($supports))? "NONE" : implode(",  ", $supports); ?></td>
                    <td><?php echo (isset($tab['public']))? "TRUE" : "FALSE" ?></td>
                    <td><?php echo (isset($tab['has_archive']))? "TRUE" : "FALSE" ?></td>
                    <td>
                      <form action='' method='post'>
                        <input type="hidden" name = "edit" value = "<?php echo $tab['cpt_slug']; ?>" />
                        <?php submit_button('Edit', 'primary small', 'submit', false);?>
                      </form>
                    
                      <form action='options.php' method='post'>
                        <input type="hidden" name = "delete" value = "<?php echo $tab['cpt_slug']; ?>" />
                        <?php 
                        // Output nonce, action, and option_page fields for a settings page
                        settings_fields($class->class_module()."_settings");   // usees the settings collect (section id in this case) 
                        submit_button('Delete', 'delete small', 'submit', false, array('onclick' => "return confirm ('Are you sure?');"));
                        ?>
                      </form>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } else { 
            echo "there are no saved custom post types.";
          }
          ?>
        </div>
        <?php 
      } 
      if ($active_tab == $class->class_module()."_settings") {
        ?>
        <!-- Display other tabs -->
        <div class = "<?php echo $class->class_module() ?> settings" id = "">
          <!-- Create the form that will be used to render our options -->        
          <form action='options.php' method='post'>
            <?php 
            settings_fields($tab_id);   // fields for this menu     
            do_settings_sections($tab_id); // Sections for this menu
             submit_button($button_label, 'primary', 'submit');  //Customize the submit button  submit_button('', 'primary', 'submit');  //Customize the submit button 
            ?>
          </form>
        </div>
      <?php
      }
      ?>

    </div>  <!-- // END <div class = "content-wrapper"> -->

  </div> <!-- // END <div id="<?php //echo $class->class_module(); ?>"> -->

</div>  <!-- // END <div class='<?php //echo $class->plugin; ?> wrap'> -->

