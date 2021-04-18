<div class="<?php echo $this->plugin; ?> wrap">
  <div id="<?php echo $this->class_module(); ?>">
    <div class="icon32" id="icon-tools"></div> <!-- Add the icon to the page -->
    <h1>
      <?php if ($this->prefix . "_dashboard" == $this->class_module()) {
    echo AXL_WP_Ultimate_NAME . " - Dashboard";
} else {
    echo AXL_WP_Ultimate_NAME . " - " . $this->name;
}
?>
    </h1>

    <?php settings_errors($this->class_module());?>
    <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->

    <!-- Display the tabs -->
    <div class="nav-tab-wrapper">
      <?php foreach ($this->admin_tabs() as $tab_id => $tab) {?>
      <a href="?page=<?php echo $this->class_module(); ?>&tab=<?php echo $tab_id ?>"
        class="nav-tab <?php echo ($this->active_tab == $tab_id) ? 'nav-tab-active' : ''; ?>"><?php echo $tab['tab_title'] ?></a>
      <?php }?>
    </div>

    <!-- Content wrapper -->
    <div class="content-wrapper">
      <?php

// Display Other tab if any
if ($this->active_tab == $this->class_module() . "_other") {?>
      <div class="other">
        Display general content here
      </div>
      <?php
}
?>




      <!-- Display for contact form -->
      <?php if ("{$this->prefix}_contact_form" == $this->class_module()) {?>
      <div class='admin-header'>
        Copy this text and past it into your post, page, or text widget content<br />
        <strong><?php echo "[{$this->plugin}-contact-form]"; ?></strong>
      </div>
      <?php }?>

      <?php if ($this->prefix . "_contact_form_email" == $this->active_tab) {?>
      <div class='admin-header'>
        In the following fields, you can use these mail-tags:<br />
        <?php foreach ($this->bracketed_email_options() as $key => $value) {?>
        <?php echo $key ?> &nbsp;
        <?php }?>
      </div>
      <?php }?>



      <!-- Display for Members -->
      <?php if ($this->prefix . "_member_email" == $this->active_tab) {?>
      <div class='admin-header'>
        In the following fields, you can use these mail-tags:<br />
        <?php foreach ($this->bracketed_email_options() as $key => $value) {?>
        <?php echo $key ?> &nbsp;
        <?php }?>
      </div>
      <?php }?>


      <!-- Display for Woo Tidbits pages -->
      <?php if ("{$this->prefix}_woo_tidbits_pages" == $this->active_tab) {?>
      <div class='admin-header'>
        Copy this text and past it into your post, page, or text widget content<br />
        <strong><?php echo "[{$this->plugin}-forbidden-page]"; ?></strong>
      </div>
      <?php }?>









      <!-- Display reset settings button -->
      <div class="reset-settings">
        <form action='options.php' method='post'>
          <?php settings_fields($this->active_tab);?>
          <input type="hidden" name="active_tab" value="<?php echo $this->active_tab ?>">
          <?php submit_button('Reset Settings', 'delete', 'reset_settings', false, array('onclick' => "return confirm ('Are you sure?');"));?>
        </form>
      </div>
      <br>


      <div class="admin-ajax-loading">
        <h2> Please wait while we process your request</h2>
        <img src="<?php echo AXL_WP_Ultimate_URL . "assets/img/ajax-scroll-bar.gif" ?>" alt="">
      </div>


      <div class="admin-messages"></div>


      <!-- Display settings fields -->
      <div class="settings-fields">
        <div id="poststuff">
          <div class="advanced-settings">
            <?php do_meta_boxes($this->page_hook, 'advanced', null);?>
          </div>
          <div id="post-body"
            class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
            <div id="postbox-container-1" class="postbox-container">
              <?php do_meta_boxes($this->page_hook, 'side', null);?></div>

            <?php
// Render list table
// if (
//   "{$this->prefix}_contact_form_fields" == $this->active_tab ||
//   "{$this->prefix}_member_registration_fields" == $this->active_tab ||
//   "{$this->prefix}_cpt_add_cpt" == $this->active_tab ||
//   "{$this->prefix}_woo_tidbits_single_product_tabs" == $this->active_tab ||
//   "{$this->prefix}_woo_tidbits_order_statuses" == $this->active_tab ||
//   "{$this->prefix}_woo_tidbits_checkout_fields" == $this->active_tab) {

$edit_record_flag = (isset($_POST["{$this->plugin}-edit-record"])) ? "1" : "0";
$css_class = "{$this->prefix}-{$edit_record_flag}";

// if ("{$this->prefix}_woo_tidbits_order_statuses" == $this->active_tab) {
//   require ( $this->path.'templates/order-statuses-list.php');  // Special case since I needed more than one list for the woo tidbits module.
// } else if ("{$this->prefix}_woo_tidbits_checkout_fields" == $this->active_tab) {
//   require ( $this->path.'templates/checkout-fields-list.php');
// } else {

//require ( $this->path.'templates/'.$this->form_list.'.php');
//}

if (count($this->tab_settings($this->active_tab), COUNT_RECURSIVE) !== count($this->tab_settings($this->active_tab))) {
    require $this->path . 'templates/settings-list.php';

}

if (isset($_POST["{$this->plugin}-edit-record"])) {
    ?><a href="<?php echo add_query_arg(array("page" => $this->class_module(), "tab" => $this->active_tab, "{$this->plugin}-edit-record" => "1"), admin_url("admin.php")); ?>"><?php
submit_button('Add New', 'primary', 'start-new-field');
    ?></a><?php
} else { //if ( ! isset($_GET["{$this->plugin}-edit-record"])) {
    submit_button('Add New', 'small', 'add-new-field');
}

// if ("axl_wp_ultimate_cpt_add_cpt" == $this->active_tab) {
//   //$button_label = (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Post Type' : "Add Post Type";
//   submit_button('Add New Post Type', 'small', 'add-new-field');
// } elseif ("axl_wp_ultimate_woo_tidbits_single_product_tabs" == $this->active_tab) {
//   //$button_label = (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Product Tab' : "Add Product Tab";
//   if (! isset($edit_record_flag) || ! $edit_record_flag) {
//     submit_button('Add New Tab', 'small', 'add-new-field');
//   } else {
//     ?>
            <!--  <br><br> --><?php
//   }
// } else {
//   //$button_label = (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Field' : "Add Field";
//   if (! isset($edit_record_flag) || ! $edit_record_flag) {
//     submit_button('Add New Field', 'small', 'add-new-field');
//   } else {
//     ?>
            <!-- <br>fdsfsdsfd<br> --><?php
//   }
// }
//} else {
// $button_label = "Save Changes";
// }
?>

            <!-- Create the form that will be used to render our options -->
            <form action='options.php' method='post'>
              <?php settings_fields($this->active_tab);?>
              <div id="postbox-container-2" class="<?php echo $css_class ?> postbox-container">
                <input type="hidden" name="<?php echo "{$this->plugin}-update-record" ?>"
                  value="<?php echo $edit_record_flag; ?>">
                <?php do_meta_boxes($this->page_hook, 'normal', null);?>
                <?php //submit_button($this->button_label, 'primary', 'submit'); ?>
              </div>
            </form>

            <br class="clear">
          </div> <!-- d#post-body -->
        </div> <!-- #poststuff -->
      </div> <!-- .settings-fields -->

    </div> <!-- .content-wrapper -->
  </div> <!-- #$this->class_module() -->
</div> <!-- .$this->plugin -->