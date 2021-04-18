<?php

/**
* AXL-WP-Ultimate
*
* @package AXL-WP-Ultimate
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace Ultimate\Includes;

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

// Declare main class if it does not exist
if (!class_exists('Dashboard')) {

  class Dashboard {

    public $name = AXL_WP_Ultimate_NAME;  // title of the plugin
    public $title = AXL_WP_Ultimate_NAME;  // title of the plugin
    public $path = AXL_WP_Ultimate_PATH;  // Path to the plugin directory
    public $url = AXL_WP_Ultimate_URL;    // URL to the plugin directory
    public $base = AXL_WP_Ultimate_BASE;   // represents plugin-dir/plugin-file 
    public $version = "1.0.0";           // module version
    public $prefix = AXL_WP_Ultimate_PREFIX;   // prefix (axl_wp_ultimate)
    public $plugin = AXL_WP_Ultimate_PLUGIN;   // plugin (axl-wp-ultimate)


    /**
    *Magic constructor.  Gets called when an object is instantiated
    */
    public function __construct() {

      // Save plugin options and do other activation tasks
      register_activation_hook($this->base, array($this, 'activate' ) );
      register_deactivation_hook($this->base, array($this, 'deactivate' ) );

      // Register styles and scripts
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
      add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts' ) );

       // Add admin menus, Submenus, section and fields
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

       // Add plugin settings link
      add_filter( "plugin_action_links_".$this->base, array($this, 'add_plugin_action_links'));

      // Display admin ntices
      add_action( 'admin_notices', array($this, 'add_admin_notices'));

      // Filter admin bar menu
      add_action( 'wp_before_admin_bar_render', array($this, 'my_admin_bar'));

      // Filters the “Thank you” text displayed in the Wordpress admin dashboard footer text
      add_filter( 'admin_footer_text', array($this, 'admin_footer_text'));

      // Intialize other initialization tidbits
      add_action('plugins_loaded', array($this, 'plugins_loaded'));

       // Set initialization tidbits
      add_action('admin_init', array($this, 'admin_init'));

       // List table reorder ajax handler
      add_action( "wp_ajax_". $this->prefix."_list_reorder", array($this, 'list_reorder' ));

     
    }  // END public function __construct()






    /**
    * Plgin options
    * @return array plugin options
    */
    public function plugin_options() {

      return array( 'version' => $this->version );

    }  // END  public function plugin_options() {




    /**
    * Class module name
    * @return string module name
    */
    public function class_module() {

      return $this->prefix."_dashboard";

    } // END public function class_module()






    /**
    * Settings API option
    * @return array settings_option
    */
    public function tab_settings($tab) {

      if (! get_option($tab)) return $this->defaults($tab); 

      // return options if at leat one option is not empty, otherwise return default
      foreach(get_option($tab) as $key => $val) {
        if (! empty($val)) return get_option($tab) ;
      }
      
      return $this->defaults($tab);

      //  return array(
      //   $tab => array(
      //     'title'   => 'Dashboard Settings',
      //     'record'  =>  $this->defaults($tab)
      //   )
      // );
      

    }  // END public function tab_settings($tab) {








     /**
    *Called when the plugin is activated using register_activation_hook
    **/
    public function activate() { 

      // Store plugin options
      if( false === get_option($this->prefix)){ //Options does not exists (first activation of plugin) add option
        
        add_option( $this->prefix, $this->plugin_options());

      } elseif ( get_option($this->prefix) != $this->plugin_options()) { // Plugin version different then update

        update_option( $this->prefix, $this->plugin_options());

      }

      // register post types
       $cpt = new CPT;
       $cpt->post_types_add();
        flush_rewrite_rules();
      

      //Create member default pages
      $member = new Member;
      extract( $member->tab_settings($member->class_module()."_settings") );

      if (isset($create_default_pages) && $create_default_pages ) $member->create_required_pages();

    } // END public function activate() {







    /**
    *Called when the plugin is deactivated using register_deactivation_hook
    **/
    public function deactivate() {

      //Flush rewrite rules on plugin activation. (woocoommerce endpoint permalink)
      flush_rewrite_rules();

    }  //END public function plugin_deactivate()







    /**
    * Register and Enque stylesheet and scripts.  Hooks into WP's wp_enqueue_scripts
    **/
    public function enqueue_scripts() {

      // Enqueue Javascript 
      wp_register_script( "{$this->plugin}-public-js", $this->url."assets/js/public.js", array( "jquery" ), null, true );
      //wp_enqueue_script( "{$this->plugin}-public-js");

      // Enqueue Stylesheet
      wp_register_style( "{$this->plugin}-public-css", $this->url."assets/css/public.css", array(), null, 'screen' );
      wp_enqueue_style( "{$this->plugin}-public-css");

    }  // END public function styles()





      /**
    * Enqueue scripts
    */
    public function admin_enqueue_scripts() {

      // Add the color picker css file       
      wp_enqueue_style( 'wp-color-picker' );
      //wp_enqueue_style( 'jquery-ui-sortable' ); 
      //wp_enqueue_style( 'jquery-ui-dialog' ); 


      wp_enqueue_script( 'common' );
      wp_enqueue_script( 'wp-lists' );
      wp_enqueue_script( 'postbox' );


           // Media uploader
      wp_enqueue_media();

       //Enqueue Javascript 
      wp_register_script( "{$this->plugin}-wp-media-uploader", $this->url."assets/js/wp-media-uploader.js", array( "jquery" ), null, true );
      wp_enqueue_script( "{$this->plugin}-wp-media-uploader");


      // Register and Enqueue Javascript 
      wp_register_script( "{$this->plugin}-admin-js", $this->url."assets/js/admin.js", array( "jquery", "wp-color-picker", "jquery-ui-accordion", "jquery-ui-sortable", "jquery-ui-dialog"), null, true );
      wp_enqueue_script( "{$this->plugin}-admin-js");


      wp_enqueue_style("{$this->plugin}-admin-ui-css",'http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/cupertino/jquery-ui.css',false,"1.9.0",false);

       // Enqueue Stylesheet
      wp_register_style( "{$this->plugin}-admin-css", $this->url."assets/css/admin.css", array(), null, 'screen' );
      wp_enqueue_style( "{$this->plugin}-admin-css");


    } // END public function enqueue_scripts()







    /* 
    * Register admin menus, submenus, sections and fields
    */
    public function admin_menu_register () {

       // If no admin or empty admin_menusreturn
      if ( ! is_admin() || ! current_user_can('manage_options') ) return;
      
      //add menu page 
      if ( get_class($this) === "Ultimate\Includes\Dashboard" && ! empty($this->admin_menus()) ) {
        foreach ($this->admin_menus() as $menu) {
          add_menu_page(
            $menu['page_title'],   // page Title displayed in browser bar
            $menu['menu_title'],   // menu title, which is displayed under admin menus
            $menu['caps'],         // The capability required for this menu to be displayed to the user.
            $menu['id'],           // menu id
            $menu['callback'],     //array($this, $menu['callback']), // Callback function used to render the settings 
            $menu['dashicon'],     // icon url
            $menu['position']      // menu position
          );
        }
      }

      // If submenu pages, add submenu pages
      if ( ! empty($this->admin_submenus())) {
        foreach ($this->admin_submenus() as $submenu) {
          $this->page_hook = add_submenu_page(
            $submenu['parent_id'],   // Parent id
            $submenu['page_title'],     // page title, which is displayed in the browser title bar
            $submenu['menu_title'],     // menu title, which is displayed in the browser title bar
            $submenu['caps'],          // The capability required for this page to be displayed to the user.
            $submenu['id'],            // submenu id
            array($this, 'settings_page_render')
          );
        }
      }

      // If dmin sections, Register sections, section settings snd section options
      if ( ! empty($this->admin_tabs())) {
        foreach ($this->admin_tabs() as $tab_id => $tab) {
          if( false == get_option($tab_id) ) {    // Create an option for the tab
            add_option( $tab_id);
          }
          register_setting(   // Register tab settings (tab settings have the same id as the tab and will be used to display and save tab fields)
            $tab_id,  
            $tab_id,
            array($this, 'sanitize')
          );
        }
      }

      // Adds my_help_tab when my_admin_page loads
      add_action("load-{$this->page_hook}", array($this, 'my_admin_add_help_tab'));

      // Add footer script
      add_action( "admin_footer-{$this->page_hook}", array($this, 'footer_scripts' ) );


    }  // END  public function admin_menu_register () {








    public function settings_page_render() {

      // Set active tab
      $this->active_tab =  (isset($_GET['tab']))?  $_GET['tab'] : array_keys($this->admin_tabs())[0];  

      //Seelect tab
      foreach ($this->admin_tabs() as $tab_id => $tab) {

        if ($this->active_tab != $tab_id ) continue;

        // Bail if there are no admin fields
        if ( empty($this->admin_fields())) return;

        // Start buffer
        ob_start();
        
        ?>
        <div id="settings-options">
          <div class="<?php echo $this->plugin; ?> wrap">
            <div id="<?php echo $this->class_module(); ?>">
              <div class="icon32" id="icon-tools"></div> <!-- Add the icon to the page -->
              <h1>
                <?php if ($this->prefix."_dashboard" == $this->class_module()){
                  echo AXL_WP_Ultimate_NAME." - Dashboard"; 
                } else {
                  echo AXL_WP_Ultimate_NAME." - ".$this->name; 
                }
                ?>
              </h1>

              <?php settings_errors($this->class_module()); ?> <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->

              <!-- Display the tabs -->
              <div class="nav-tab-wrapper">
                <?php foreach ($this->admin_tabs() as $tab_id => $tab) { ?>
                <a href="?page=<?php echo $this->class_module(); ?>&tab=<?php echo $tab_id ?>" class="nav-tab <?php echo ($this->active_tab == $tab_id)? 'nav-tab-active' : ''; ?>"><?php echo $tab['tab_title'] ?></a>
                <?php } ?>
              </div>

              <!-- Content wrapper -->
              <div class = "content-wrapper">

                <!-- Display reset settings button -->
                <div class="reset-settings">
                  <form action='options.php' method='post'>
                    <?php settings_fields($this->active_tab); ?>
                    <input type="hidden" name="active_tab" value="<?php echo $this->active_tab ?>">
                    <?php submit_button('Reset Settings', 'delete', 'reset_settings', false, array('onclick' => "return confirm ('Are you sure?');")); ?>
                  </form>
                </div>

                <form action='options.php' method='post'> 
                  <?php settings_fields($this->active_tab); ?>
                  <?php if ( empty($this->tab_settings($this->active_tab))) :?>
                    There are no records to display to display
                  <?php else : ?>
                    <?php if (count($this->tab_settings($this->active_tab), COUNT_RECURSIVE) === count($this->tab_settings($this->active_tab))) : ?>
                    <?php  //var_dump($this->tab_settings($this->active_tab));echo "llll<br>";die; ?>

                     <table class="wp-list-table widefat fixed striped">
                        <col width="20%"><col widht="80%">
                        <tbody>
                          <?php
                          foreach ($this->admin_fields() as $field_id => $field) {
                            // bail if field tab is not the same as the active tab
                            if ($field['tab'] != $this->active_tab ) continue;

                            // Set field value
                            $field['value'] = (isset($this->tab_settings($this->active_tab)[$field_id]))? $this->tab_settings($this->active_tab)[$field_id] : "";                          
      
                            // Set remaining fields
                            foreach ($this->possible_field_options() as $option_key => $option_value) {
                              if (! isset($field[$option_key])) $field[$option_key] = $option_value;
                            }

                            // render field
                            ?> <tr><?php
                            if(file_exists($this->path."templates/".$field['field_type'].".php")) {
                              ?>
                              <td><?php echo $field['title'] ?></td>
                              <td><?php require ( $this->path.'templates/'.$field['field_type'].".php"); ?></td>
                              <?php 
                            } else {
                              ?>
                              <td><?php _e("<div class = 'admin-errors'> Template ".$this->path."templates/".$field['field_type'].".php does not exist</div>", $this->plugin); ?></td>
                              <?Php
                            }
                            ?> </tr><?php
                          }
                          
                          ?>
                        </tbody>
                      </table>
                    <?php endif; ?>
                  <?php endif; ?>
                  <?php submit_button("Save Changes", 'primary', 'submit', true); ?><br style = "clear:both;">
                </form>

              </div> <!-- .content-wrapper -->
            </div>  <!-- #id=$this->class_module() -->
          </div> <!-- .wrap -->
          <div id = "sidebar">
            Enter content here for this tab <?php echo "{$this->active_tab}"; ?>
          </div> <!-- #id=sidebar -->
        </div>  <!-- #id="settings-options" -->
       

        <?php

      }

      $html = ob_get_contents();
      ob_end_clean();
      echo $html;

      // Render template
      //echo $this->get_template_html("settings");

    }




    /**
   * Renders tab meta box
   * @return string $htmkl     html text
   */
    public function render_tab_meta_box() {




      // retreive record by active tab
      //$records = $this->tab_settings($this->active_tab);

      //echo "<pre>";var_dump($records);die;

        
      // if ("axl_wp_ultimate_cpt_add_cpt" == $this->active_tab) {
      //   $this->button_label = (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Post Type' : "Add Post Type"; 
      // } elseif ("axl_wp_ultimate_woo_tidbits_single_product_tabs" == $this->active_tab) {
      //   $this->button_label = (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Product Tab' : "Add Product Tab";
      // } else {
      //   $this->button_label = "Save Changes";
      // }



      // If record is being edited and is multidimentional array, retreive record by recird ID 
      // if (isset($_POST["{$this->plugin}-edit-record"]) && count($records, COUNT_RECURSIVE) !== count($records) ) {
      //   $record = (isset($_POST['record_id']) && isset($records) && $records[$_POST['record_id']])? $records[$_POST['record_id']] : array();
      // } else {
      //   $record = $records;
      // }

      // If no admin fields
      // 
        // // Set Localization.
        // wp_localize_script( 
        //   "{$this->plugin}-admin-js",  //handle for the script
        //   "{$this->prefix}_admin_object",     //  The name of the variable which will contain the data (used in the ajax url)
        //   array(  // Data to be passed 
        //     'ajax_url'      => admin_url( 'admin-ajax.php' ),
        //     'ajax_action'   => $this->prefix."_list_reorder",
        //     'ajax_tab'      => $this->class_module()."_fields"
        //   )
        // );  

       



      // ob_start();

      // var_dump($this->tab_settings($this->active_tab));echo "<br>";
      
  

    }






    /**
    * Renders templates
    * @param string $template The name of the template to render (without .php)
    * @param array  $attributes    The PHP variables for the template
    * @return string               The contents of the template.
    */
    public function get_template_html($template, $atts = array() ) {

      ob_start();

      do_action( $this->prefix.'_before_' . $template );

      require( $this->path."templates/".$template.".php" );

      do_action( $this->prefix.'_after_' . $template );

      $html = ob_get_contents();
      ob_end_clean();

      return  $html;

    }  // END public function get_template_html($template, $atts = array() ) {






    /*
    * Perform additional validation on input fields before ther are stored in the DB
    */
    public function sanitize( $input ) {

      echo "<pre>";var_dump($_POST);die;

      // Intialize error
      $errors =  new \WP_Error;

      // get option page
      if(!isset($_POST['option_page'])) return;

      //Deelete option if post reset is set
      if(isset($_POST['reset_settings']) && isset($_POST['option_page'])) {
        delete_option($_POST['option_page']);
        return;
      }

      // Retreive add_feild settings options
      $output = (isset($_POST['option_page']) && get_option($_POST['option_page']))? get_option($_POST['option_page']) : array();
     

      // Set record ID based on the settings page (this is required for multi dimentional arrays like cpt)
      if ("{$this->prefix}_woo_tidbits_single_product_tabs" == $_POST['option_page']) {
        $record_id = 'tab_id';
      } elseif ("{$this->prefix}_woo_tidbits_order_statuses" == $_POST['option_page']) {
        $record_id = 'status_id';
      } elseif ("{$this->prefix}_woo_tidbits_checkout_fields" == $_POST['option_page']) {
        $record_id = 'checkout_field_id';
      } elseif ("{$this->prefix}_cpt_add_cpt" == $_POST['option_page']) {
        $record_id = 'cpt_slug';
      } elseif ("{$this->prefix}_contact_form_fields" == $_POST['option_page']) {
        $record_id = 'field_id';
      }

         // Deelete setting if post delete is set
        if(isset($_POST["{$this->plugin}-delete-record"])) {
          unset($output[$_POST['record_id']]);
          return $output;
        }

    
     
      // This portion of the code is run if amult dimentional array  (like cpt) is being  saved/edited)
      // if (count($output, COUNT_RECURSIVE) !== count($output)) {
       if (isset($record_id) && in_array($record_id, array_keys($input))) {
         //var_dump($input);die;
   

       


         // Add required fields error
        foreach ($this->admin_fields() as $key => $field) {
          if (in_array($key, array_keys($input))){

            if (isset($field['required']) && $field['required'] &&  ! $input[$key])
              $errors->add( "{$key}_empty", __("{$field['title']} is required", $this->plugin));
          }
        }

               // Check if an existing multi array record is being addaed

        if ( (! isset($_POST["{$this->plugin}-update-record"]) || ! $_POST["{$this->plugin}-update-record"]) && isset($input[$record_id]) && in_array($input[$record_id], array_keys($output))) 
          $errors->add( "{$input[$record_id]}_exists", __("A record with this ID = {$input[$record_id]} exists.  Please edit this tab to modify it", $this->plugin));


      }


      if (!empty($errors->errors)) {
        foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
          add_settings_error( $this->class_module(), $code, $message );
        }
     
      } else {
  
      
        // Save settings depending on whether it is an array or array or arrays as in the case of add_field tab
        //if (in_array($record_id, array_keys($input))) {
        if (isset($record_id) && in_array($record_id, array_keys($input)) ){   
          $output[$input[$record_id]] = $input;
        } else {
          $output = $input;
        }
      }

       //echo "<pre>";var_dump($output);die;
   
      return $output;

      //var_dump($output);die;
   
      //return $output;


      // // Intialize error
      // $errors =  new \WP_Error;

      // // get option page
      // if(!isset($_POST['option_page']) || empty($input)) return;

      // //Deelete option if post reset is set
      // if(isset($_POST['reset_settings']) && isset($_POST['option_page'])) {
      //   delete_option($_POST['option_page']);
      //   //var_dump($this);die;
      //   return;
      // }

      // // Retreive add_feild settings options
      // $output = (isset($_POST['option_page']) && get_option($_POST['option_page']))? get_option($_POST['option_page']) : array();
     

      // // Set record ID based on the settings page (this is required for multi dimentional arrays like cpt)
      // if ("{$this->prefix}_woo_tidbits_single_product_tabs" == $_POST['option_page']) {
      //   //$record_id = 'tab_id';
      // } elseif ("{$this->prefix}_woo_tidbits_order_statuses" == $_POST['option_page']) {
      //   //$record_id = 'status_id';
      // } elseif ("{$this->prefix}_woo_tidbits_checkout_fields" == $_POST['option_page']) {
      //   //$record_id = 'checkout_field_id';
      //   $record_title = 'checkout_field_label';
      // } elseif ("{$this->prefix}_cpt_add_cpt" == $_POST['option_page']) {
      //   //$record_id = 'cpt_slug';
      // } elseif ("{$this->prefix}_contact_form_fields" == $_POST['option_page']) {
      //   //$record_id = 'field_id';
      // } else {
      //   //$record_id = $_POST['option_page'];
      //   $record_title = 'Settings';
      // }

      //    // Deelete setting if post delete is set
      //   if(isset($_POST["{$this->plugin}-delete-record"])) {
      //     unset($output[$_POST['record_id']]);
      //     return $output;
      //   }

      //   $output= array();
      //   foreach ($input as $record_id => $record) {
      //     foreach ($this->admin_fields() as $field_id => $field) {
      //       if ($field['tab'] != $_POST['option_page'] ) continue;
      //         //foreach($record as $key => $value) {
      //         //if ($field_id == $key) {
              
      //       if ($field['field_type'] == "checkbox") {
      //           $record[$field_id] = (isset($record[$field_id]))? true : false;
      //       } else {
      //           $record[$key] = $value;
      //         }

      //         //}
            

      //     }
      //     $output[$record_id]['title'] = (isset($record[$record_title]))? $record[$record_title] : $record_title ;
      //     $output[$record_id]['record'] = $record;
      //   }

              //echo "<pre>";var_dump($output);die;



    
     
      // // This portion of the code is run if amult dimentional array  (like cpt) is being  saved/edited)
      // // if (count($output, COUNT_RECURSIVE) !== count($output)) {
      //  if (isset($record_id) && in_array($record_id, array_keys($input))) {
      //    //var_dump($input);die;
   

       


      //    // Add required fields error
      //   foreach ($this->admin_fields() as $key => $field) {
      //     if (in_array($key, array_keys($input))){

      //       if (isset($field['required']) && $field['required'] &&  ! $input[$key])
      //         $errors->add( "{$key}_empty", __("{$field['title']} is required", $this->plugin));
      //     }
      //   }

      //          // Check if an existing multi array record is being addaed

      //   if ( (! isset($_POST["{$this->plugin}-update-record"]) || ! $_POST["{$this->plugin}-update-record"]) && isset($input[$record_id]) && in_array($input[$record_id], array_keys($output))) 
      //     $errors->add( "{$input[$record_id]}_exists", __("A record with this ID = {$input[$record_id]} exists.  Please edit this tab to modify it", $this->plugin));


      // }


      // if (!empty($errors->errors)) {
      //   foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
      //     add_settings_error( $this->class_module(), $code, $message );
      //   }
     
      // } else {
  
      
      //   // Save settings depending on whether it is an array or array or arrays as in the case of add_field tab
      //   //if (in_array($record_id, array_keys($input))) {
      //   if (isset($record_id) && in_array($record_id, array_keys($input)) ){   
      //     $output[$input[$record_id]] = $input;
      //   } else {
      //     $output = $input;
      //   }
      // }

      //echo "<pre>";var_dump($output);die;
   
      return $output;

    }  // END static function sanitize( $input ) {









    /**
     * Footer Script Needed for Meta Box:
     * - Meta Box Toggle.
     * - Spinner for Saving Option.
     * - Reset Settings Confirmation
     * @since 0.1.0
     */
    public function footer_scripts(){



      //$page_hook_id = fx_smb_setings_page_id();
    ?>
    <script type="text/javascript">
      //<![CDATA[
      jQuery(document).ready( function($) {
        // toggle
        $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
        postboxes.add_postbox_toggles( '<?php echo $this->page_hook; ?>' );
      });
      //]]>
    </script>
    <?php

    }




    


    public function my_admin_add_help_tab () {
      $screen = get_current_screen();
   
      if ( $screen->id != $this->page_hook )
          return;

      // Add my_help_tab if current screen is My Admin Page
      if ("{$this->plugin}_page_{$this->prefix}_woo_tidbits" == $this->page_hook) {
        $screen->add_help_tab( array(
          'id'  => 'my_help_tab',
          'title' => __('My Help Tab'),
          'content' => '<p>' . __( 'Descriptive content  for woo tidbit '.$this->class_module().' that will show in My Help Tab-body goes here.' ) . '</p>',
        ) );
      }
    }







      /**
    * Adds plugin setting link
    * @param array $links links array
    * @return arra $links links array
    */
    public function add_plugin_action_links ( $links ) {

      $settings_link = "<a href='admin.php?page=".$this->class_module()."'>" . __( "Settings" ) . "</a>";
      array_push( $links, $settings_link );
      return $links;

    }  // END private function add_plugin_action_links ( $links ) { 






     /*
    * Adds admin notices
    */
    public function add_admin_notices() {
      
      //Return if not WP Ultimate plugin page 
      if (! strpos(get_current_screen()->id, $this->class_module())) return;

      extract ($this->tab_settings($this->class_module()."_settings"));

      // Initialize error/success objects
      $errors =  new \WP_Error;

      //Check if WooCommerce is active enable plugin functionality
      if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        foreach ( $this->tab_settings($this->class_module()."_settings") as $class_name => $flag)  {
          if (false !== strpos($class_name, "Woo") && isset($flag) && $flag) 
             $errors->add( "{$class_name}_requires_woocommerce", __("{$class_name} requires <a href ='https://wordpress.org/plugins/woocommerce/'>woocommerce</a> to be installed and activated. PLease activate Woocommerce or disable this module.", $this->plugin));
        }
  
      }

      // Display admin notice
      echo $this->display_admin_notices($errors);
      
    }  // END public function display_admin_notices() {










    /**
     * Displays admin notices
     * @param  object $error WP Error
     * @return string $html  List of admin errors
     */
    public function display_admin_notices($errors) {

      // Loop through all the errors/success
      if (empty($errors->errors) ) return;

      ob_start();

      foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
        ?><div class="notice notice-error is-dismissible"><p><?php  echo "$message"; ?> </p></div><?php
      }

      $html = ob_get_contents();
      ob_end_clean();
      return  $html;
      
    }  // END public function display_admin_notices() {







          /**
    * Add plugin links to admin bar
    */
    function my_admin_bar() {
      global $wp_admin_bar;

      $options = $this->tab_settings($this->class_module()."_settings");

      //extract($this->tab_settings($this->class_module()."_settings"));

      //Add a link called for the plugin
      $wp_admin_bar->add_menu( array(
        'id'    => $this->plugin,
        'title' => 'AXL WP Ultimate',
        'href'  => '#',
      ));

      //THEN add  sub-links
      $wp_admin_bar->add_menu( array(
        'id'    => $this->class_module(),
        'title' => 'Dashboard',
        'href'  => add_query_arg( array('page' => $this->class_module()), admin_url('admin.php')),
        'parent'=>$this->plugin
      ));

      foreach ( $this->tab_settings($this->class_module()."_settings") as $class_name => $flag)  {
        if ( isset($flag) && $flag && class_exists("Ultimate\\Includes\\{$class_name}")) {
          $class='Ultimate\\Includes\\'.$class_name;
          $instance = new $class;

          $wp_admin_bar->add_menu( array(
          'id'    => $instance->class_module(),
          'title' => $instance->name,
          'href'  => add_query_arg( array('page' => $instance->class_module()), admin_url('admin.php')),
          'parent'=>$this->plugin
        ));
        }
      }

    } // END  function my_admin_bar() {








    /**
     * Displays admin footer text
     * @return string footer text
     */
    public function admin_footer_text() {
      
      // Get current screen
      $screen = get_current_screen();

      // Return if not WP Ultimate plugin page 
      if (! strpos($screen->id, $this->prefix)) return;

      return  sprintf( __( 'Let us know if you like this site.   Want the real thing? <a href="%s" title="Click here to purchase a license!" target="_blank">Click here to purchase a license!</a>', $this->plugin ), 'http://abbaslamouri.com' );

    }  // end  public function admin_footer_text() {







    /**
    * Loads all enabled modules
    */
    public function plugins_loaded () {

      //var_dump($this->tab_settings($this->class_module()."_settings"));die;
      $options = $this->tab_settings($this->class_module()."_settings");
      //var_dump($options[$this->class_module()."_settings"]['record']);die;

      //extract ($this->tab_settings($this->class_module()."_settings"));
      // instantiate all the class modules
      foreach ( $this->tab_settings($this->class_module()."_settings") as $class_name => $flag)  {
        if ( isset($flag) && $flag && class_exists("Ultimate\\Includes\\{$class_name}")) {
          $class='Ultimate\\Includes\\'.$class_name; 

          $instance = new $class;
        }
      }


    }  // END public function plugins_loaded () {







    /**
    * Initialization tidbits
    */
    public function admin_init (){

      // start a session if none exists
      if(!session_id()) session_start();
         
    } // END  public function init (){






    /**
    * Uses Jquery UI to reorder lists using Ajax
    */
    public function list_reorder() {

      //print_r($_POST);wp_die();  

      // Fetch existing fields
      $old_fields = $this->tab_settings($_POST['tab']);
      
      //get new order
      $new_order = $_POST['rows'];
      
      // Intialize new fields 
      $new_keys = array();
      $new_fields= array();

      //Retreive the keys, reorder them , then reorder the fields)
      foreach($new_order as $key => $value) {
        $new_keys[$key] = array_keys($old_fields)[$value-1];
      }

      foreach($new_keys as $key => $value) {
        $new_fields[$value] = $old_fields[$value];
      }

      // Update fields
      update_option($_POST['tab'], $new_fields);

    }  // END list_reorder() {




    

    /**
     * Renders front end form fields by type
     * @param  array $field input field
     * @param  string $value input value if any
     * @return strin        htnl input field
     */
    public function render_field_type ($field, $value) {

      ob_start();

      if ($field['field_type'] == "text" || $field['field_type'] == "email" || $field['field_type'] == "password" || $field['field_type'] == "hidden" ) {
        ?><input type="<?php echo $field['field_type'] ?>" id="<?php echo $field['field_id']; ?>" name="<?php echo $field['field_id'] ?>" <?php echo ($value)? "value='".$value."'" : "" ?> >
        <?php echo $field['field_description'];
      } elseif ( $field['field_type'] == "textarea") {
        ?><textarea name="<?php echo $field['field_id'] ?>" id="<?php echo $field['field_id'] ?>" rows=10 ><?php echo $value ?></textarea><?php
      } elseif ( $field['field_type'] == "checkbox") {
        ?><input type="<?php echo $field['field_type'] ?>" id="<?php echo $field['field_id'] ?>" name="<?php echo $field['field_id'] ?>" value="1" <?php checked( 1, $value, true) ?> >
        <?php echo $field['field_description'] ?><?php
      } elseif ( $field['field_type'] == "select") {
        ?>
        <select id="<?php echo $field['field_id'] ?>" name="<?php echo $field['field_id'] ?>" >
          <option value="0">Select Option</option>
          <?php 
          $options = explode(", ", $field['field_options']);
          foreach ($options as $key =>  $option) {
            ?>
            <option value="<?php echo $option ?>" <?php selected( $value, $option, true); ?> ><?php echo $option ?></option>
          <?php } ?>
        </select>
        <?php
      } elseif ($field['field_type'] == "radio") {
        $options = explode(", ", $field['field_options']);
        foreach ($options as $key =>  $option) {
          ?>
          <input type="<?php echo $field['field_type'] ?>" id="<?php echo $field['field_id'] ?>" name="<?php echo $field['field_id'] ?>" value="<?php echo $option ?>" <?php checked( $option, $value, true); ?> >
          <?php echo $option;
        }
      } else {
        _e("<div class = 'admin-errors'> Template ".$this->path."templates/".$field['field_type'].".php does not exist</div>", $this->plugin);
      }

      return ob_get_clean();

    } // END public function render_field_type ($field, $value) {









      /*
    * Checks if required pages are set
    */
    public function  fetch_page ($tab, $page_slug){

      $pages = $this->tab_settings($tab);

      if ( isset($pages[$page_slug]) && $pages[$page_slug] && get_post($pages[$page_slug])) {
        return get_post($pages[$page_slug])->post_name;
      } else {
        return "";
      }
 
    } // END public static function  admin_menus (){ 









    /**
     * display_user_messages on the front end
     * @return string html text
     */
    public function display_user_messages() {

      // Retreive user errors if any
      $errors = (isset($_SESSION[$this->plugin.'-errors']))? $_SESSION[$this->plugin.'-errors'] : array(); 

      // Retreive user errors sucess if any
      $success = (isset($_SESSION[$this->plugin.'-success']))? $_SESSION[$this->plugin.'-success'] : array(); ;

      if (!empty($errors)) {
        $messages = $errors;
        $css_class = "user-errors"; 
        unset($_SESSION[$this->plugin.'-errors']);  
      } elseif (!empty ( $success)) {
        $messages = $success;
        $css_class = "user-success";
        unset ($_SESSION[$this->plugin.'-success']);
      } else {
        $messages = array();
        $css_class = "";
      }

       $html = "";

      if(!empty($messages)) {
        foreach ($messages as $code => $message) {
          $output = ($this->fetch_error_from_code($code) !== "unknown")? $this->fetch_error_from_code($code): $message;
          $html .= "<div class = '".$css_class."' >".wpautop($output, true)."</div>";
        }
      } 

      return $html;

    }





   
   /**
    * Finds and returns a matching error message for the given error code.      
    * @param  string $error_code error code
    * @return string            error message
    */
    public function fetch_error_from_code( $error_code ) {

      extract( $this->tab_settings($this->class_module()."_dialogs") );
      
      switch ( $error_code ) {

        case 'incorrect_password':
          $err = __( "{$incorrect_password} <a href='%s'>Did you forget your password?</a>", $this->plugin );
          return sprintf( $err, wp_lostpassword_url() );

        case 'invalid_key':
          return __( 'The password reset key has expired.  Please try again', $this->plugin );

        default:
          return __( 'unknown', $this->plugin );

        break;
      }


     }  // END public function fetch_error_from_code( $error_code ) {





    /**
     * Registers post types
     * @param  string $slug Post type slug
     * @param  array $args arguments array
     */
    public function register_post_types($slug, $args) {

       $result = register_post_type( $slug, $args ); 
      
        if (is_wp_error($result)) {
          add_action ('admin_notices', function () {
            echo $this->display_admin_notices($result);
          });
       }
    }








    /**
    * Relaces brackets with options
    * @param aray $options bracketed options
    * @param string $text text to be modofied
    * @return string $text with bracketed items replaced by actual option values
    */
    public function replace_bracketed_values ($options, $text) {

      foreach ($options as $key => $value) {
        $text = str_replace($key, $value, $text);
      }

      return $text;

    } // END public function replace_bracketed_values ($text) {









    /**
    * Adds errors/messages to the debug log
    * @param object/array/string $message
    */
    public function log_message($message) {
      if ( WP_DEBUG === true ) {
        if ( is_array($message) || is_object($message) ) {
            error_log( print_r($message, true) );
        } else {
            error_log( $message );
        }
      }
    }











    /*
    * Admin menus
    */
    public function  admin_menus (){

      return array(
        
        array(
          'page_title'    => __($this->name, $this->plugin),    // Text to be displayed in the browser window.                                        
          'menu_title'    => __($this->name, $this->plugin),    // Text to be displayed for the menu           
          'caps'          => 'administrator',               // The capability required for this page to be displayed to the user.         
          'id'            => $this->class_module(),                  // Unique id for this menu         
          'callback'      => function() {},                  // Callback to output the menu (Handled by the first submenu in this case
          'dashicon'      => 'dashicons-editor-customchar',  // icon url
          'position'      => '5', // menu position
        ),

      ) ;

    } // END public static function  admin_menus (){ 


      

    /*
    * Admin submenus
    */
    public  function  admin_submenus (){

      return array(

        array(
          'parent_id'    => "{$this->prefix}_dashboard",    // The id name for the parent menu (or the file name of a standard WordPress admin page).  
          'page_title'   => __($this->name, $this->plugin),    // Text to be displayed in the browser window.   
          'menu_title'   => __($this->name, $this->plugin),      // Text to be displayed for the menu   
          'caps'         => 'administrator', // The capability required for this page to be displayed to the user.
          'id'           => $this->class_module(),    //Unique id for this menu  
        ),

      );

    }




    /*
    * Admin sections 
    */
    public function  admin_tabs (){

      return array(  

        $this->class_module()."_settings"  => array(
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'      => __('Settings', $this->plugin),   // Formatted title of the tab. Shown as the heading for the section.
          'sections'   => array( 
            'general' => array('title' => 'General Settings'),
            //'other'   => array('title' => 'Other')
          ),

        ),



        $this->class_module()."_other"  => array(
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'      => __('Other', $this->plugin),   // Formatted title of the tab. Shown as the heading for the section.
          'sections'   => array( 
            'general' => array('title' => 'General Settings'),
            //'other'   => array('title' => 'Other')
          ),

        ),
   
      );

    }




    /**
    * All possible field options
    * @return array of possible field options
    */
    public function possible_field_options() {

      return array(

        'id'             => '',
        'title'          => '',
        'css_class'      => '',
        'tab'            => '',
        'field_type'     => '',
        'field_size'     => '',
        'field_min'      => '',
        'field_max'      => '',
        'required'       => false,
        'readonly'       => false,
        'unique'         => false,
        'disabled'       => false,
        'pattern'        => '',
        'placeholder'    => '',
        'modal'          => false,
        'description'    => '',
        'option_none'    => 'Select Option',
        'default'        => '',
        'field_options'  => '',
        'textarea_cols'  => '',
        'textarea_rows'  => '',

      );

    } // END  public function possible_field_options() {








    /**
    * Default settings
    * @return array of default settings
    */  
    public function defaults($tab) {

      switch ( $tab ) { 

        case $this->class_module().'_settings' :

          return array(
       
            'CPT'             => false,
            'WooMenuCart'        => false,
            'Members'         => false,
            'WooPDF'          => false,
            'ContactForm'     => false,
            'EmailSMTP'       => false,
            'WooTidbits'      => false,
              
            
          );

          default :
          
          return array();

          break;

      }


    }  // END public function settings_defaults($tab) {





    /*
    * Admin Fields
    */
     public function  admin_fields (){

      $options = $this->tab_settings($this->class_module()."_settings");
      $woocommerce_inactive =! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
      $notice = ($woocommerce_inactive)? "<span class='notice notice-warning'>Please install and activate Woocommerce if you wish to use this module.  <a href ='https://wordpress.org/plugins/woocommerce/'>woocommerce</a></span>" : "";


      return array(


        'CPT'    => array(
          'title'       => __('Custom Post Types', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'checkbox',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'description' => 'Enable Custom Post Types',  // Custom field description(supplied in the $args)
        ),

        'WooMenuCart' => array(
          'title'       => __('Menu Cart', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'section'  => 'general',
          'field_type'  => 'checkbox',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          //'disabled'  =>  ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )? true : false,
          'description' => (isset($options['WooPDF']) && $options['WooPDF'])? "Enable Woocommerce Menu Cart.  {$notice}" : "Enable Woocommerce Menu Cart.",  // Custom field description(supplied in the $args)
        ),

        'Members' => array(
          'title'       => __('Members', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'section'  => 'general',
          'field_type'  => 'checkbox',
          'description' => 'Enable Member',  
        ),

        'WooPDF' => array(
          'title'       => __('Woocommerce PDF', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'section'  => 'general',
          'field_type'  => 'checkbox',
         // 'disabled'  =>  ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )? true : false,
          'description' => (isset($options['WooPDF']) && $options['WooPDF'])? "Enable Woocommerce invoice and packing slip pdf.  {$notice}" : "Enable Woocommerce invoice and packing slip pdf.",
        ),  

        'ContactForm' => array(
          'title'       => __('Contact Form', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'section'  => 'general',
          'field_type'  => 'checkbox',
          'description' => 'Enable Contact Form',
        ),

        'EmailSMTP' => array(
          'title'       => __('SMTP Email', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'section'  => 'general',
          'field_type'  => 'checkbox',
          'description' => 'Enable SMTP Email', 
        ),

         'WooTidbits' => array(
          'title'       => __('Woocommerce Tidbits', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'section'  => 'general',
          'field_type'  => 'checkbox',
          //'disabled'  =>  ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )? true : false,
          'description' => (isset($options['WooTidbits']) && $options['WooTidbits'])?  "Enable various woocommerce options. {$notice}" : "Enable various woocommerce options." ,
        ),

          'otherfield' => array(
          'title'       => __('Woocommerce Tidbits', $this->plugin),
          'tab'     => $this->class_module()."_other",  // Section to which this field belongs
          'section'  => 'general',
          'field_type'  => 'checkbox',
          //'disabled'  =>  ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )? true : false,
          'description' => (isset($options['WooTidbits']) && $options['WooTidbits'])?  "Enable various woocommerce options. {$notice}" : "Enable various woocommerce options." ,
        ),

      );

    } // END  private  function register_fields (){



  } // END class Dashboard {

} // END if (!class_exists('Dashboard')) 
