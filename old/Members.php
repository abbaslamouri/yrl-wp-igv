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

if (!class_exists('Members')) {

  class Members extends Dashboard {

    public $name = "Members";  // this module name
    public $form_list = "registration-fields-list";  // The ID for the form in this class

    /**
    *Magic constructor.  Gets called when an object is instantiated
    */
    public function __construct() {

            // Enqueue scripts
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ));
      add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts' ));

      // Add admin Submenus, section and fields
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

      // add admin init action
      add_action('admin_init', array($this, 'admin_init'));

       // Display admin ntices
      add_action( 'admin_notices', array($this, 'add_admin_notices'));
      
      //allows custom meta fields to be added, edited and displayed in backend
      add_action( 'user_new_form', array($this, 'display_user_profile_fields' ));
      add_action( 'show_user_profile', array($this, 'display_user_profile_fields'),10,1);
      add_action( 'edit_user_profile', array($this, 'display_user_profile_fields'),10,1);


      // Add user list column to show activation status
      add_filter('manage_users_columns', array($this, 'add_user_id_column'));
      add_action('manage_users_custom_column',  array($this, 'show_user_id_column_content'), 10, 3);

      // save and update custom meta fields in backend
      add_action('user_register', array($this, 'save_custom_profile_fields'));
      add_action('profile_update', array($this, 'save_custom_profile_fields'));

         //Limit access to the dashboard for everybody but administrators
      add_action( 'admin_init', array($this, 'limit_dashboard_access'));

      // Hide admin bar for non admins
      add_filter( 'show_admin_bar' , array($this, 'hide_admin_bar'));

       // Add content before reset button
     // add_action ("{$this->prefix}_before_reset_settings_button", array($this, 'before_reset_settings_button'), 10, 2);

      // // Filter save settings button label
      // add_filter('save_settings_button_label', function ($button_label, $class_module, $active_tab) {
      //   if($active_tab == $this->class_module()."_registration_fields" ) return (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Field' : "Add Field"; 
      // }, 10, 3);

      // Instantiate the User_Registerclass
      if (class_exists('Ultimate\Includes\\UserRegister')) new UserRegister();

      // Instantiate the User_Login class
      if (class_exists('Ultimate\Includes\\UserLogin')) new UserLogin();

      // Instantiate the User_Reset_PWD class
      if (class_exists('Ultimate\Includes\\UserLostPWD')) new UserLostPWD();

      // Instantiate the User_Reset_PWD class
      if (class_exists('Ultimate\Includes\\UserResetPWD')) new UserResetPWD();

      // Instantiate the Security class
      if (class_exists('Ultimate\Includes\\Security')) new Security();


    }  // END public function __construct()




     /**
    * Limits access to dashboard to all but admins.  Redirects to user profile page or home_url() if user profile page doea not exists.
    */
    public function limit_dashboard_access( ) {

      // Check if the current page is an admin page
      // && check if the current user has admin capabilities
      // && and ensure that this is not an ajax call
      if ( is_admin() && !current_user_can( 'manage_options' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )) {
        wp_redirect( home_url() );
        exit;
      }
  
    }  // END public function limit_dsahborad_access( ) {



    /**
    * Limits access to dashboard to all but admins.  Redirects to user profile page or home_url() if user profile page doea not exists.
    */
    public function hide_admin_bar( ) {
     
      return (!current_user_can('manage_options'))?  false : true;
      
    }  // END public function limit_dsahborad_access( ) {
      



    
    /**
    * Class module name
    * @return string module name
    */
    public function class_module() {

      return $this->prefix."_member";

    }



     /**
    * Class page names and shortcodes
    * @return string shortocodes
    */
    public function shortcodes () {

      return  array(
        'login'       => $this->plugin."-login",
        'register'    => $this->plugin."-register",
        'profile'     => $this->plugin."-profile",
        'lost-pwd'    => $this->plugin."-lost-pwd",
        'reset-pwd'   => $this->plugin."-reset-pwd",
        'security'    => $this->plugin."-security",
      );

    }






    /**
    * A shortcode for rendering the new user registration form.
    * @param  array   $attributes  Shortcode attributes.
    * @param  string  $content     The text content for shortcode. Not used.
    * @return string  The shortcode output
    */
    public function shortcode( $attributes, $content = null ) {

        $this->atts = shortcode_atts(
        $this->default_attributes(),  // default array values
        $attributes // array of values passed to the shortcode if any
      );

       // Retreive form values from the session variable in case of errors and delete the session valiable
      if(isset($_SESSION["{$this->prefix}_{$this->class_id}"]) && $_SESSION["{$this->prefix}_{$this->class_id}"] ) {
        $this->atts[$this->class_id] = $_SESSION["{$this->prefix}_{$this->class_id}"];
        unset($_SESSION["{$this->prefix}_{$this->class_id}"]);
      }

      //return $this->shortcode_render($this->atts);
       // Render the logout template
      if ( is_user_logged_in() ) return $this->get_template_html('logout');
      
      // Render member form
      return $this->get_template_html('member-form', $this->atts);


    }  // END public function register_form_shortcode( $attributes, $content = null ) {





    // /**
    // * Populates shortcodes.
    // * @param  array   $atts  Shortcode attributes.
    // * @return string  The shortcode output
    // */
    // public function shortcode_render( $atts ) {

    //   // Render the logout template
    //   if ( is_user_logged_in() ) return $this->get_template_html('logout');
      
    //   // Render member form
    //   return $this->get_template_html('member-form', $atts);

    // } // RND  public function member_shortcode( $attributes, $content = null ) {




    /**
     * Admin init setup
     */
    public function admin_init() {

      // Save mandory fields if they do not exist
      if (! get_option($this->class_module()."_registration_fields") || 
        empty (get_option($this->class_module()."_registration_fields")) ) update_option($this->class_module()."_registration_fields", $this->mandatory_registration_fields());

    } // END public function admin_init() {



    /**
    * Enqueue scripts
    */
    public function enqueue_scripts() {

      // Register and Enqueue Javascript 
      wp_register_script( $this->prefix."_member", $this->url."assets/js/member.js", array( "jquery" ), null, true );
      wp_enqueue_script( $this->prefix."_member");


    } // END public function enqueue_scripts()







    // /**
    // * Admin init tidbits.
    // */
    // public function activate() {


    //   extract( $this->tab_settings($this->class_module()."_settings") );

    //   var_dump($create_default_pages);die;

    //   if (isset($create_default_pages) && $create_default_pages ) $this->create_default_pages();
     


    // }  // END static function init ()






    /**
    * Admin init tidbits.
    */
    public function add_admin_notices () {

      //Return if not WP Ultimate plugin page 
      if (! strpos(get_current_screen()->id, $this->class_module())) return;

      extract( $this->tab_settings($this->class_module()."_settings") );
      extract( $this->tab_settings($this->class_module()."_captcha") );

      // Initialize error/success objects
      $errors =  new \WP_Error;

      // Fetch stored option pages
      $pages = $this->tab_settings($this->class_module()."_pages") ;

      // Check if registration is open to all
      if ( get_option( 'users_can_register' ) )
        $errors->add( 'users_can_register', __("Anybody can register.  This is not recommended", $this->plugin));

      // Check if required pages are set
      foreach ( $this->admin_fields() as $id => $field ) {

        if (isset($field['field_type']) && $field['field_type'] == 'page-dropdown' &&  isset($field['required']) && $field['required']) {

          // Check if page is required
          if (isset($pages[$id]) && $pages[$id] && ! get_post($pages[$id])) {   
              $errors->add ( $id."-empty", __("Page ".$field['title']." is required", $this->plugin));
            } elseif ( get_post($pages[$id]) && get_post($pages[$id])->post_status ==='trash' ){

      
              $errors->add ( $id."-trashed", __("Page ".$field['title']." exists but it is in the trash", $this->plugin));
            }
          
        }
      }

      
      // check if recaptcha keys are set when captcha is enabled
      if(isset($recaptcha_enable) && $recaptcha_enable && (!isset($recaptcha_site_key) || !isset($recaptcha_secret_key))) {
        $errors->add( 'recptcha_site_secret_keys_empty', __("Captcha is enabled but site and secret keys are not set", $this->plugin));
      }


      // if ( ! $this->admin->check_required_pages($this) && isset($create_default_pages) && $create_default_pages  ) {
      //   $errors = new \WP_Error ( 'pages-enabled', __("Create default pages is enabled but the pages have not been created.  Please Check the 'Create Default Pages' and reactivate the plugin", $this->plugin));
      //   $_SESSION[$this->plugin.'-admin-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());
      // }



      // Display admin notice
      echo $this->display_admin_notices($errors);



    }  // END static function init ()




    /**
    * Create required admin pages and add page id's to the module section
    */
    public function create_required_pages() {

      // Initialize error array
      $errors =  new \WP_Error;
      $options = array(); 

      // Loop through all the pages
      foreach ( $this->admin_fields() as $id => $field ) {
     
        // Skip if the page is not required
        if (!isset($field['required']) || !$field['required'] || !isset($field['field_type']) || $field['field_type'] != 'page-dropdown' ) continue;
          
        // Check that the page doesn't exist already
        $query = new \WP_Query( 'pagename=' . $id );
        if (  ! $query->have_posts() ) {

          $args = array(
            'post_content'   => (isset($field['content']))? $field['content'] : '',
            'post_name'      => $id,
            'post_title'     => $field['title'],
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'ping_status'    => 'closed',
            'comment_status' => 'closed',
          );


          /*
          * Add the page using the data from the array above
          * (wp_insert_post() returns post ID on suceess, 0 if no post was inserted or WP Error or error)
          *If the $postarr parameter has ‘ID’ set to a value, then post will be updated.
          **/
          $page_id =  wp_insert_post(
            $args,  // post arguments
            true   // whether to return WP Error on failure
          );

          if (is_wp_error($page_id)) {  //if WP Error 
            $errors->add($page_id."_creation_error", __("Page {$field['title']} was not created<br />", $this->plugin));
          } else {   // If no errors add page id to the options array
            $options [$id] = (isset($page_id) && $page_id)? $page_id : 0 ;
            update_option($field['tab'], $options);
          }
          

        } // END if ( ! $query->have_posts() ) {


      }  // END foreach ( $default_pages as $id => $page ) {

    

      // Store admin errors 
      if (!empty($errors->errors)) $this->log_message(array_combine($errors->get_error_codes(), $errors->get_error_messages()));

    }  // END public static function create_required_admin_pages($this->class, $tab, $field_type) {






    /**
    * Checks if a valid nonce was submitted
    * @return Boolean
    */
    public function nonce_check($action, $name) {

      if( ! $action || !wp_verify_nonce($name) ) return $this->errors;
        return true;

    }



    /**
    * Checks if all required fields are submitted
    * @return Boolean
    */
    public function required_field_check($field) {
        
      if ( (isset( $field['field_required']) && $field['field_required']) && ( ! isset( $_POST[$field['field_id']]) || ! $_POST[$field['field_id']]) ) return false;
      return true;

    }



    /**
    * Checks if Emails are valid
    * @return Boolean/wp_errors
    */
    public function email_check($field) {
        
      if ( $field['field_type'] === "email" && isset($_POST[$field['field_id']]) && $_POST[$field['field_id']] && ! is_email( sanitize_email($_POST[$field['field_id']])) ) return false;
      return true;

    }




    /**
    * Checks if two passwords match
    * @return Boolean
    */
    public function do_passwords_match($pass_1, $pass_2) {

      if ( isset($pass_1) && isset($pass_2) && $pass_1 != $pass_2) return false;
      return true;

    }






    /**
    * Checks if email is unique
    * @return Boolean/
    */
    public function is_email_unique( $email) {
      if ( isset($email) && is_email( sanitize_email($email)) && (username_exists( sanitize_email($email)) || email_exists( sanitize_email($email)) )) return false;
      return true;
  
    }





    /**
    * Checks if a password is correctvalid nonce was submitted
    *
    * @return BBoolean/wp_errors
    *
    */
    public function password_check($user, $password) {

      if (isset($password) && $password && ! wp_check_password( $password, $user->user_pass, $user->ID )) return false;
        return true;
    
    }










    /**
    * Checks if an old password was used
    *
    * @return Boolean/wp_errors
    *
    */
    public function old_password_check($password, $old_pwds) {

      if (isset($password) && $password && in_array(wp_hash(trim($password)), $old_pwds))  return false;
        return true;

    }







    /**
    * Checks captcha
    * @return Boolean
    */
    public function captcha_check( ) {

      extract( $this->tab_settings($this->class_module()."_captcha") );

      if ( isset($recaptcha_enable) && $recaptcha_enable && ! $this->verify_recaptcha() ) return false;
      return true;

    }






     /**
    * Checks that the reCAPTCHA parameter sent with the registration
    * @return bool True if the CAPTCHA is OK, otherwise false.
    */
    private function verify_recaptcha() {

      // This field is set by the recaptcha widget if check is successful
      if ( ! isset ( $_POST['g-recaptcha-response'] ) ) return false;
        

      // Verify the captcha response from Google
      $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        array(
          'body' => array(
            'secret' => get_option($this->class_module()."_captcha")['recaptcha_secret_key'],
            'response' =>  $_POST['g-recaptcha-response']
          )
        )
      );

      if ( $response && is_array( $response ) ) {
        return json_decode( $response['body'] )->success;
      } else {
        return false;
      }
    }







  
    /**
    * Display custom user profile fields
    * @param  object $profile user A WP_User object
    */
    function display_user_profile_fields( $user ) {


      $activation = (is_object($user) && get_user_meta($user->ID, $this->prefix."_activation", true))?  true : false;
      $activated = (is_object($user) && get_user_meta($user->ID, $this->prefix."_activated", true))?  true : false;
     
      ?>
      <h3><?php esc_html_e( $this->name, $this->plugin  ); ?></h3>

      <!-- This input is required because woocommerce updates user profile on order status change -->
      <input type = "hidden" name = "<?php echo "{$this->plugin}-member-activation-flag" ?>" value = "1" >
      <table class="form-table">

        <tr>
          <th>
            <label for="<?php echo $this->prefix."_activation"; ?>" >
              <?php ( ! $activation )? esc_html_e( 'Activate User', $this->plugin ) : esc_html_e( 'Deactivate User', $this->plugin ) ; ?>
            </label>
          </th>
          <td>
            <input type="checkbox" name="<?php echo $this->prefix."_activation"; ?>" id="<?php echo $this->prefix."_activation"; ?>" value="1" <?php echo checked(1, $activation, true);?>  />
            <span class="description"><?php esc_html_e( 'Check this box to activate this user.', $this->plugin  ); ?></span>
          </td>
        </tr>

         <tr>
          <th>
            <label for="<?php echo $this->prefix."_activated"; ?>" >
              <?php _e( 'Activated', $this->plugin ); ?>
            </label>
          </th>
          <td>
            <input type="checkbox" name="<?php echo $this->prefix."_activated"; ?>" id="<?php echo $this->prefix."_activated"; ?>" value="1" <?php echo checked(1, $activated, true);?> onclick="return false" readonly />
            <span class="description"><?php esc_html_e( 'This is checked once the user has been activated.', $this->plugin  ); ?></span>
          </td>
        </tr>

        <?php 
        $fields = (get_option($this->class_module()."_registration_fields"))? get_option($this->class_module()."_registration_fields") : array();
        foreach ( $fields as $field_id => $field ) :
          if (! in_array($field_id, array_keys($this->mandatory_registration_fields()))) {
            $meta = (is_object($user) && get_user_meta($user->ID, "{$this->prefix}_{$field_id}", true))?  get_user_meta($user->ID, "{$this->prefix}_{$field_id}", true) : "";
            ?>
            <tr>
              <th><?php echo $field['field_label'] ?></th>
              <td>
                <input  class="regular-text" type="<?php echo $field['field_type'] ?>" name="<?php echo "{$this->prefix}_{$field_id}" ?>" id="<?php echo "{$this->prefix}_{$field_id}" ?>" value="<?php echo $meta; ?>" >
                <span class="description"><?php esc_html_e( "{$field['field_description']}", $this->plugin  ); ?></span>
              </td>
            </tr>
            <?php 
          } 
        endforeach; ?>

      </table>
      <?php
    }





    /**
    * Show custom user profile fields
    * 
    * @param  object $profile user A WP_User object
    * 
    */
    public function save_custom_profile_fields($user_id){

      // Bail out if  user is not being updated by an admin 
      if ( ! isset ($_POST["{$this->plugin}-member-activation-flag"]) ) return;

      $activation = (isset($_POST[$this->prefix."_activation"]))? true : false;
      $activated = (isset($_POST[$this->prefix."_activated"]))? true : false;

      // # again do this only if you can
      if(!current_user_can('manage_options')) return false;

      update_user_meta( $user_id, $this->prefix."_activation", $activation);
      update_user_meta( $user_id, $this->prefix."_activated", $activated);

     $fields = (get_option($this->class_module()."_registration_fields"))? get_option($this->class_module()."_registration_fields") : array();

      foreach ( $fields as $field_id => $field ) :
        if ( ! in_array($field_id, array_keys($this->mandatory_registration_fields())) && isset($_POST["{$this->prefix}_{$field_id}"]))
          update_user_meta( $user_id, "{$this->prefix}_{$field_id}", $_POST["{$this->prefix}_{$field_id}"]);
      endforeach;

    }








    /**
    * Add colum to the admin user list
    * 
    * @param array $columns user list columns
    * 
    * @return array $columns
    */
    public function add_user_id_column($columns) {

      $columns['activation'] = 'Activation Status';
      return $columns;

    }




    /**
    * Shows value associated with the column
    * 
    * @param string $value, string $column_name, integer $user_id
    * 
    * @return string $value 
    */
    public function show_user_id_column_content($value, $column_name, $user_id) {

      if(!current_user_can('manage_options')) return false;

      if ($column_name =='activation') {
        
        $user = get_user_by('ID', $user_id);
        $activation = (get_user_meta($user->ID, $this->prefix."_activation", true))?  true : false;
         
        return ($activation)? 'Active' : 'Inactive';
        
      } else {
        return $value;
      }

    }







    /**
    * Add activation link to user actions
    * @param object $user
    * @param array $actions
    */
    function add_activate_action($actions, $user) {

       // retrive user meta
      $activate = (get_user_meta( $user->ID, $this->prefix."_activation", true))? true : false;
      $action = (! $activate)? __( 'Activate', $this->plugin ) : __( 'Deactivate', $this->plugin ) ;

      $actions['activate-user'] = "<a class='' href='" . admin_url( "users.php?activate_user=".! $activate."&user_id=$user->ID") . "'>" . $action . "</a>";
      return $actions;

    }







  
    /**
    * Returns an array of all possible email options for the settings api
    * @return array of possible options
    */
    public function bracketed_email_options ( $user = null) {

      return array(

        '[blog-name]' => get_bloginfo('name'),
        '[first-name]' => (isset($user) && !empty($user))? $user->first_name : '',
        '[last-name]' => (isset($user) && !empty($user))? $user->last_name : '',
        '[user-name]' => (isset($user) && !empty($user))? $user->user_login : '',
        //'[password]' =>  (isset($user) && !empty($user))? $user->user_pass : 'User password',
        '[login-page]' =>  wp_login_url(),

      );

    }




      /**
    * Sanitizes settings fields
    * @param  array $input settings fields
    * @return array        settings fields
    */
    public function sanitize( $input ) {



      // Intialize error
      $errors =  new \WP_Error;

      //Deelete option if post reset is set
      if(isset($_POST['reset_settings']) && isset($_POST['active_tab'])) {
        delete_option($_POST['active_tab']);
        return false;
      }

      // Retreive add_feild settings options
      $output = (get_option($this->class_module()."_registration_fields"))? get_option($this->class_module()."_registration_fields") : array();


      // Deelete setting if post delete is set
      if(isset($_POST["{$this->plugin}-delete-record"])) {
        unset($output[$_POST["record_id"]]);
        return $output;
      }


      // Add required fields error
      foreach ($this->admin_fields() as $key => $field) {
        if (in_array($key, array_keys($input))){
          if (isset($field['required']) && $field['required'] &&  ! $input[$key])
            $errors->add( "{$key}_empty", __("{$field['title']} is required", $this->plugin));
        }
      }

       // Check if an existing cpt is being addaed
      if ( (! isset($_POST["{$this->plugin}-update-record"]) || ! $_POST["{$this->plugin}-update-record"]) && isset($input['field_id']) && in_array($input['field_id'], array_keys($output))) 
       $errors->add( "{$input['field_id']}_exists", __("A CPT with this id ({$input['field_id']}) exists.  Please edit this field to modify it", $this->plugin));

      if (!empty($errors->errors)) {
        foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
          add_settings_error( $this->class_module(), $code, $message );
        }
     
      } else {
  
      
        // Save settings depending on whether it is an array or array or arrays as in the case of add_field tab
        if (in_array('field_id', array_keys($input))) {   
          $output[$input['field_id']] = $input;
        } else {
          $output = $input;
        }
      }

           // var_dump($output);die;
   
      return $output;

    }  // END static function sanitize( $input ) {





   


    // /*
    // * Admin submenus
    // */
    // public  function  admin_submenus (){

    //   // Set admin submenu pages
    //   return array(

    //       array(
    //       'parent_id'    => parent::class_module(),    // The id name for the parent menu (or the file name of a standard WordPress admin page).  
    //       'page_title'   => __($this->title, $this->plugin),    // Text to be displayed in the browser window.   
    //       'menu_title'   => __('Members', $this->plugin),      // Text to be displayed for the menu   
    //       'caps'         => 'administrator', // The capability required for this page to be displayed to the user.
    //       'id'         => $this->class_module(),    //Unique id for this menu  
    //     ),

    //   );

    // }



    /**
    * Admin sections 
    */
    public function  admin_tabs (){

      //$admin_menu = new Admin_Menu ;  // Admin Menu class (handles sanitization and errors)

      return array(  

          $this->class_module()."_settings"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Settings', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )

        ), 

         
        $this->class_module()."_pages" => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Pages', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )         

        ), 


         $this->class_module()."_dialogs"  => array(
          
          'title'      => __('Dialogs & Error Messages', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Dialogs', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )

        ), 

          $this->class_module()."_email"  => array(
          
          'title'      => __('Email Headers & Messages', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Email', $this->plugin),
          'callback'   => function() { // Function that echos out any content at the top of the section (between heading and this->fields).
            echo "<div class = 'admin-header'>";
            echo   "<h4>In the following fields, you can use these mail-tags:</h4>";
            foreach ($this->possible_email_options() as $key =>$value) {
              echo "{$key}&nbsp; ";
            }
            echo "</div>";
          },
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )

        ),


        $this->class_module()."_registration_fields"  => array( 
          'title'      => __('Registration Fields', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Registration Fields', $this->plugin),
           'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ),

        // $this->class_module()."_list"  => array( 
        //   'title'      => __('Registration Fields', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
        //   'tab_title'  => __('Registration Fields', $this->plugin),
        //   'sections'   => array( 
        //     'general' => array('title'    => 'Field List'),
        //   )
        // ),


         $this->class_module()."_captcha"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('reCAPTCHA', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )

        ), 


        // $this->class_module()."_other"  => array(
          
        //   'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
        //   'tab_title'  => __('Other', $this->plugin),
        //   'sections'   => array( 
        //     'general' => array('title'    => 'General Settings'),
        //   )

        // ), 



      );

    }







   /**
    * Sets mandatory fields for this mosule
    * @return array Mandatory fields
    */
    public function mandatory_registration_fields() {

      return array(

        'reg-first-name'     =>  array(
          'field_id'         => 'reg-first-name',
          'field_label'      => 'First Name',
          'field_required'   => true,
          'field_type'       => 'text',
          'field_description'   =>'',
          'field_error'      =>'First Name is required.',
          'field_position'   => '1'
        ),

        'reg-last-name'      =>  array(
          'field_id'         => 'reg-last-name',
          'field_label'      => 'Last Name',
          'field_required'   => true,
          'field_type'       => 'text',
          'field_description'   =>'',
          'field_error'      =>'Last Name is required.',
          'field_position'   => '2'
        ),

        'reg-email'            =>  array(
          'field_id'         => 'reg-email',
          'field_label'      => 'Email',
          'field_required'   => true,
          'field_type'       => 'email',
          'field_description'   =>'',
          'field_error'      =>'You must provide a valid email address.',
          'field_position'   => '3'
        ),

        'reg-password'       =>  array(
          'field_id'         => 'reg-password',
          'field_label'      => 'Password',
          'field_required'   => true,
          'field_type'       => 'password',
          'field_description'   =>'',
          'field_error'      => 'Password is required.',
          'field_position'   => '4'
        ),

        'reg-verify-password'  =>  array(
          'field_id'         => 'reg-verify-password',
          'field_label'      => 'Verify Password',
          'field_required'   => true,
          'field_type'       => 'password',
          'field_description'   =>'',
          'field_error'      => 'Please verify password.',
          'field_position'   => '4'
        ),

        'reg-gdpr'     =>  array(
          'field_id'         => 'reg-gdpr',
          'field_label'      => 'GDPR Agreement',
          'field_required'   => true,
          'field_type'       => 'checkbox',
          'field_description'   => 'I consent to having this website store my submitted information so they can respond to my inquiry.  By using this form you agree with the storage of your data by Sandbox',
          'field_error'      =>'You must agree to the GDPR agreement.',
          'field_position'   => '5'
        )


      );

     
    } // END public function mandatory_registration_fields()







    /**
    * Sets the calss form fields if any
    * @return array form fields
    */
    public function mandatory_login_fields () {
      
      return array (

        'log' => array (
          'field_id'          => 'log',
          'field_label'       => 'Email',
          'field_required'    => true,
          'field_type'        => 'email',
          'field_description' =>'',
          'field_error'      => 'Email is required',
          'field_position'   => '1'
        ),

        'pwd' => array (
          'field_id'          => 'pwd',
          'field_label'       => 'Password',
          'field_type'        => 'password',
          'field_required'    => true,
          'field_description' =>'',
          'field_error'       => 'Please enter a password',
          'field_position'   => '2'
        ),

      );

    }  // End public function mandatory_login_fields () {






    /**
    * Sets the calss form fields if any
    * @return array form fields
    */
    public function mandatory_lost_pwd_fields () {
      
      return array (
        'user_login' => array (
          'field_id'          => 'user_login',
          'field_label'       => 'Email',
          'field_required'    => true,
          'field_type'        => 'email',
          'field_description' => '',
          'field_error'       => 'Please enter a valid email',
          'field_position'    => '1'
      ),

      );

    }  // End public function mandatory_lost_pwd_fields () {







       /**
    * Sets the calss form fields if any
    * @return array form fields
    */
    public function mandatory_reset_pwd_fields () {

      
      return array (

        'login' => array (
          'field_id'          => 'login',
          'field_label'       => '',
          'field_required'    => true,
          'field_type'        => 'hidden',
          'field_required'    => false,
          'field_description' => '',
          'field_error'       => 'We are unable to process you request, please contact the site administrator. We applogize for the inconvenience',
          'field_position'    => '1',
          'field_value'       => (isset($_GET['login']))? $_GET['login'] : ""
        ),

         'key' => array (
          'field_id'          => 'key',
          'field_label'       => '',
          'field_required'    => true,
          'field_type'        => 'hidden',
          'field_required'    => false,
          'field_description' => '',
          'field_error'       => 'We are unable to process you request, please contact the site administrator. We applogize for the inconvenience',
          'field_position'    => '1',
          'field_value'       => (isset($_GET['key']))? $_GET['key'] : ""
        ),

        'pass1' => array (
          'field_id'          => 'pass1',
          'field_label'       => 'New password',
          'field_required'    => true,
          'field_type'        => 'password',
          'field_required'    => true,
          'field_description' => '',
          'field_error'       => 'Password is required',
          'field_position'    => '1'
        ),

        'pass2' => array (
          'field_id'          => 'pass2',
          'field_label'       => 'Repeat new password',
          'field_required'    => true,
          'field_type'        => 'password',
          'field_required'    => true,
          'field_description' => '',
          'field_error'       => 'Verification password is required',
          'field_position'    => '1'
        ),

      );


    }  // End public function form_fields () {





 /**
    * Sets the calss form fields if any
    * @return array form fields
    */
    public function mandatory_security_fields () {

      
      return array (

        'login' => array (
          'field_id'          => 'login',
          'field_label' => '',
          'field_type' => 'hidden',
          'field_required' => false,
          'field_description' => '',
          'field_error'       => 'We are unable to process you request, please contact the site administrator. We applogize for the inconvenience',
          'field_position'    => '1',
          'field_value' => (isset($_GET['login']))? $_GET['login'] : "",
        ),

        'action' => array (
          'field_id'          => 'action',
          'field_label' => '',
          'field_type' => 'hidden',
          'field_required' => false,
          'field_description' => '',
          'field_error'       => 'We are unable to process you request, please contact the site administrator. We applogize for the inconvenience',
          'field_position'    => '2',
          'field_value' => (isset($_GET['action']))? $_GET['action'] : "",
        ),


        'current-password' => array (
          'field_id'          => 'current-password',
          'field_label' => 'Current password',
          'field_type' => 'password',
          'field_required' => true,
          'field_description' => '',
          'field_error'       => 'Current password is required',
          'field_position'    => '3',
          'field_value' => '',
        ),

        'password-1' => array (
          'field_id'          => 'password-1',
          'field_label' => 'New password',
          'field_type' => 'password',
          'field_required' => true,
          'field_description' => '',
          'field_error'       => 'New password is required',
          'field_position'    => '4',
          'field_value' => '',
        ),

        'password-2' => array (
          'field_id'          => 'password-2',
          'field_label' => 'Repeat new password',
          'field_type' => 'password',
          'field_required' => true,
          'field_description' => '',
          'field_error'       => 'Verification password is required',
          'field_position'    => '5',
          'field_value' => '',
        ),

      );


    }  // End public function form_fields () {






 
   /**
    * Default settings
    * @param  string $tab tab name
    * @return array      default settings
    */
    public function defaults($tab) {

      switch ( $tab ) { 

        case $this->class_module().'_settings' :

          return array(

            'create_default_pages'   => false,
            'force_admin_activation' => false,
            'pwd_reset_duration'     => 90,
            'force_admin_pwd_reset'  => false,
             
          );

        case $this->class_module().'_pages' :

          return array(

            'user-login' => '',
            'user-register' => '',
            'user-profile' => '', 
            'user-lost-pwd' => '',
            'user-reset-pwd' => '',
            'redirect-after-login' => '',
            'redirect-after-logout' => '',
            'user-expired-pwd'  => '',

          );


        case $this->class_module().'_dialogs' :

          return array(

            'invalid_nonce'     => 'An invalid nonce was submitted with this request.',
            'captcha_missing'    => 'This site uses reCAPTCHA.', 
           // 'email_empty'    => 'Email is required.',
            'not_an_email'    => 'The email address you entered is not valid, please try agin.', 
            'incorrect_password'    => "The password you entered wasn't quite right.", 
            //'password_empty'   => 'Password is required.', 
            'passwords_dont_match'    => "The password and verification password don't match.",
            'email_taken'     => 'Sorry, that email address already has an account, please try another.',
            'pending_activation'    => 'This account has not been activated.',
            'registration_succesful'    => <<<ENDHTML
Congratulations! Your registration was successful.
An email confirming your registration will be sent to you when your account is approved. If you do not find it in your inbox, please check your spam or junk folder in the event it found its way there in error.
ENDHTML
,
            'lostpassword_message'  => 'Lost your password? Please enter your email address. You will receive a link to create a new password via email.',  
            'lostpassword_email_sent'    => <<<ENDHTML
A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox.  If there is an account associated with '[user-name]', you’ll receive an email with a link to create a new password.  If you don’t see this email in your inbox within 15 minutes, look for it in your junk mail folder. If you find it there, please mark the email as Not Junk and add @[blog-name].com to your address book.
Please wait at least 10 minutes before attempting another reset.
ENDHTML
,
            'password_reset_succesful'    => 'Your password has been reset successfully.',
            'user_not_found'    => 'We are not able to find a username with this email address in our database.',
            'catch_all_error_message'    => 'We are unable to process your request at this time. Please try again later.',
            'no_user_found'    => 'We are not able to find a username with this email address in our database.',
            'expired_password_message'   => 'Your password has expired, please rest password.',
            'reused_password_message'    => 'Please reset your password.  For security reasons, we occasianlly ask our users to resset their password.  We applogize for the inconvenience.',
           // 'current_password_empty'     => 'Please enter current password.',
            'current_password_error'    => 'Current password is incorrect.',
           // 'new_password_empty'    => 'Please enter new password.',
            'new_passwords_dont_match'    => "New passwords don't match.",
            'old_password_reused'    => 'You are not allowed to re-use old passwords.',

          );


        case $this->class_module().'_email' :

          return array(

            'admin_email_recipients' => get_bloginfo('admin_email'),
            'disabale_password_change_notification'    => false,
            'notify_who'    => 'both',
            // 'from_email_address'    => '', 
            // 'from_email_name'     => '',
            'user_registration_subject'  => 'Your [blog-name] Registration',

            'user_registration_body' => <<<ENDHTML
<p>Thank you for registering for [blog-name].  Your registration has been received and is pending approval.</P>
<p>You will receive login instructions upon approval of your account.</p>
<br>
<p>Your email:  [user-name]</p>
<p>Login link:  [login-page]</p>
ENDHTML
,
        
            'admin_registration_subject'  => 'New user registration for [blog-name]',
            'admin_registration_body'    => <<<ENDHTML
New user registration on your site [blog-name].
Name:  [first-name] [last-name]
Username:  [user-name]
Log in to activate this user [login-page]
ENDHTML
,
        
            'registration_approved_subject'   => 'Your registration for [blog-name] has been approved',
        
            'registration_approved_body'     => <<<ENDHTML
<p>Your registration for [blog-name] has been approved.  Your registration information is below, you may wish to retain a copy for your records.</p>
<p>Email: [user-name]</p>
<p>You may log in and change your password here:</p>
<p>[login-page]</p>
ENDHTML
,
        
            'lostpassword_subject' => 'Your password reset instructions for [blog-name]',

            'lostpassword_body'     => <<<ENDHTML
<p>Password reset instructions:</p>

<p>Someone requested that the password be reset for account [user-name].  If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.</p>
<p>To reset your password, visit the link below:</p>


ENDHTML
,
    
            'password_reset_subject'      => '[blog-name] notice of password change',


            'password_reset_body'     => <<<ENDHTML
Hi [first-name],

Your password for [blog-name] has been reset.

If you did not change your password, please contact the Site [blog-name] Administrator 

Regards,
All at [blog-name]

ENDHTML
,

        
            'email_signature'  => <<<ENDHTML
<p>----------------------------------</p> 
This is an automated message from [blog-name]
Please do not reply to this email.
ENDHTML

        );


        case $this->class_module().'_registration_fields' :

          return array(

            'field_label'         => '',   
            'field_id'            => '',
            'field_type'          => '',
            'field_position'      => 10,
            'field_error'         => '',
            'field_required'      => false,
            'field_description'   => '',
            'field_options'       => ''


          );


        case $this->class_module().'_captcha' :

          return array(

            'recaptcha_enable' => false,
            'recaptcha_site_key' => '',
            'recaptcha_secret_key' => ''

          );

          default :
          
          return array();

          break;

      }


    }  // END public function settings_defaults($tab) {






    /**
    * Admin Fields
    */
     public function  admin_fields (){

      return array(

        // **********************************settings setion

        'create_default_pages' => array(
          'title'              => __('Create Default Pgaes', $this->plugin),
          'tab'                => $this->class_module()."_settings",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_settings")['create_default_pages'],
          'section'            => 'general',
          'field_type'         => 'checkbox',
          'description'        => "Check this box to create default pages (You must deactivate and reactivate the plugin",
        ),


        'force_admin_activation' => array(
          'title'               => __('Force Admin Activation', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_settings")['force_admin_activation'],
          'section'            => 'general',
          'field_type'  => 'checkbox',
          'description' => "Require Admins to be activated before they can sign in",
        ),


        'force_admin_pwd_reset'     => array(
          'title'       => __('Force Admin Reset', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_settings")['force_admin_pwd_reset'],
          'section'            => 'general',
          'field_type'  => 'checkbox',
          'description' => "Check if you want to force admins to reset password",
        ),

        
        'pwd_reset_duration'     => array(
          'title'       => __('Pasword Reset Duration', $this->plugin), 
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_settings")['pwd_reset_duration'],
          'section'            => 'general',
          'field_type'  => 'number',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'field_min'   => 1,  // Numeric field minimum
          'field_max'   => 365, // Numeric field maximim
          'description' => "Password reset duration in days",  // Custom field description(supplied in the $args)
        ),


        // ******************************************** pages section
        'user-login' => array(
          'title'       => __( 'User Login', $this->plugin), 
          'tab'         => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['user-login'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Login Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['login'].']',  // Page content (shortcode)
          'description' => "Create Login page and this shortcode to the page     <input value ='[".$this->shortcodes()['login']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
        ),

        'user-register' => array(
          'title'       => __( 'User Register', $this->plugin), 
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['user-register'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Registration Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['register'].']',  // Page content (shortcode)
          'description' => "Create Register page and this shortcode to the page     <input value ='[".$this->shortcodes()['register']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
        ),

        'user-profile' => array(
          
          'title'       => __( 'User Profile', $this->plugin), 
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['user-profile'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Profile Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['profile'].']',  // Page content (shortcode)
          'description' => "Create Profile page and this shortcode to the page     <input value ='[".$this->shortcodes()['profile']."]' size='50' readonly >",  // Custom field description(supplied in the $args)

        ),

        'user-lost-pwd' => array(
          'title'       => __( 'User Lost Password', $this->plugin), 
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['user-lost-pwd'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Lost Password Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['lost-pwd'].']',  // Page content (shortcode)
          'description' => "Create Lost Password page and this shortcode to the page     <input value ='[".$this->shortcodes()['lost-pwd']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
        ),

        'user-reset-pwd' => array(
          'title'       => __( 'User Reset Password', $this->plugin), 
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['user-reset-pwd'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Reset Password Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['reset-pwd'].']',  // Page content (shortcode)
          'description' => "Create Reset Password page and this shortcode to the page     <input value ='[".$this->shortcodes()['reset-pwd']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
        ),

         'user-expired-pwd' => array(
          'title'       => __( 'User Expired Password', $this->plugin), 
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['user-expired-pwd'],
          'section'            => 'general',
          'required'  => true,
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Expired Password Page',  // custom field (default value) (supplied in the $args)
          'content'    => '['.$this->shortcodes()['security'].']',  // Page content (shortcode)
          'description' => "reate Expired Password page and this shortcode to the page     <input value ='[".$this->shortcodes()['security']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
        ),

        'redirect-after-login' => array(   
          'title'       => __( 'Redirect After Login', $this->plugin), 
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['redirect-after-login'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Redirect After Login Page',  // custom field (default value) (supplied in the $args)
          'description' => "Page to redurect to after login",  // Custom field description(supplied in the $args)
        ),

        'redirect-after-logout' => array( 
          'title'       => __( 'Redirect After Logout', $this->plugin),
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['redirect-after-logout'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Redirect After Logout Page',  // custom field (default value) (supplied in the $args)
          'description' => "Page to redurect to after logout",  // Custom field description(supplied in the $args)
        ),


        // *****************************************Dialogs section

        'invalid_nonce'    => array(  
          'title'       => __('Invalid nonce', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['invalid_nonce'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

         'captcha_missing'    => array(  
          'title'       => __("Captcha missing", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['captcha_missing'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'not_an_email'    => array(  
          'title'       => __("Invalid email", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['not_an_email'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),


        'incorrect_password'    => array(  
          'title'       => __("Incorrect password", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['incorrect_password'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

         'passwords_dont_match'    => array(  
          'title'       => __("Passwords don't match", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['passwords_dont_match'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'email_taken'    => array(  
          'title'       => __('Email taken', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['email_taken'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'pending_activation'    => array(  
          'title'       => __('Pending activation', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['pending_activation'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'registration_succesful'    => array(  
          'title'       => __('Registration succesful', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['registration_succesful'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 3,
          'description' => '' ,
        ),

        'lostpassword_message'    => array(  
          'title'       => __('Lost your password message', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['lostpassword_message'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
        ),

        'lostpassword_email_sent'    => array(  
          'title'       => __('Lost password email sent message', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['lostpassword_email_sent'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 4,
          'description' => '' ,
        ),

        'password_reset_succesful'    => array(  
          'title'       => __('Password reset succesful', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['password_reset_succesful'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
        ),

        'user_not_found'    => array(  
          'title'       => __('User not found', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['user_not_found'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
        ),

         'catch_all_error_message'    => array(  
          'title'       => __('Catch all error message', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['catch_all_error_message'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
        ),

        'expired_password_message'    => array(  
          'title'       => __("Expired password message", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['expired_password_message'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'reused_password_message'    => array(  
          'title'       => __("Reused password message", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['reused_password_message'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

         'current_password_error'    => array(  
          'title'       => __("Current password error", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['current_password_error'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'new_passwords_dont_match'    => array(  
          'title'       => __("New passwords don't match", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['new_passwords_dont_match'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'old_password_reused'    => array(  
          'title'       => __("Old password re-used", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'            => $this->defaults($this->class_module()."_dialogs")['old_password_reused'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),


        // ********************************************Email section

        'disabale_password_change_notification'   => array(
          'title'        => __('Disable password change notification', $this->plugin),
          'tab'          => $this->class_module()."_email",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_email")['disabale_password_change_notification'],
          'section'            => 'general',
          'field_type'   => 'checkbox',
          'description'  => "check this box to disable sending password change notification email to admin",
        ),


        'notify_who' => array(
          'title'       => __('Send Registration Notification to', $this->plugin),
          'tab'         => $this->class_module()."_email",  // tab to which this field belongs
          'default'            => $this->defaults($this->class_module()."_email")['notify_who'],
          'section'            => 'general',
          'field_type'      => 'select',
          'field_options'  => array('user' => 'User', 'both' => 'Admin & User'),
          'description' => "Select whom to send the registration notification to",
        ),

         'admin_email_recipients'    => array(  
          'title'       => __('Admin email recipients', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['admin_email_recipients'],
          'section'            => 'general',
          'field_type'  => 'text',
          'field_size'  => '100',
          'description' => '(Comma separated admin email recipients' ,
        ),

        'user_registration_subject'    => array(  
          'title'       => __('Registration email subject', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['user_registration_subject'],
          'section'            => 'general',
          'field_type'  => 'text',
          'field_size'  => '100',
          'description' => '' ,
        ),

        'user_registration_body'    => array(  
          'title'       => __('Registration email body', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['user_registration_body'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 10,
          'description' => '' ,
        ),

        'admin_registration_subject'    => array(  
          'title'       => __('Admin Registration notification subject', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['admin_registration_subject'],
          'section'            => 'general',
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,
        ),

        'admin_registration_body'    => array(  
          'title'       => __('Admin registration message body', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['admin_registration_body'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 10,
          'description' => '' ,
        ),

        'registration_approved_subject'    => array(  
          'title'       => __('Registration approved email subject', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['registration_approved_subject'],
          'section'            => 'general',
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,
        ),

        'registration_approved_body'    => array(  
          'title'       => __('Registration approved email body', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['registration_approved_body'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 10,
          'description' => '' ,
        ),

        'lostpassword_subject'    => array(  
          'title'       => __('Lost password subject', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['lostpassword_subject'],
          'section'            => 'general',
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,

        ),

        'lostpassword_body'    => array(  
          'title'       => __('Lost password body', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['lostpassword_body'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 5,
          'description' => '' ,
        ),

         'password_reset_subject'    => array(  
          'title'       => __('Password reset subject', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['password_reset_subject'],
          'section'            => 'general',
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,
        ),

        'password_reset_body'    => array(  
          'title'       => __('Password reset body', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['password_reset_body'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 5,
          'description' => '' ,
        ),

        'email_signature'    => array(  
          'title'       => __('Email signature', $this->plugin),
          'tab' => $this->class_module()."_email",
          'default'            => $this->defaults($this->class_module()."_email")['email_signature'],
          'section'            => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 5,
          'description' => 'Optional' ,
        ),

        // ******************** Registration fields
        // 

        'field_label' => array(
          'title'       => __('Field Label', $this->plugin),
          'tab'     => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_label'],
          'section'     => 'general',
          'field_type'  => 'text',
          'required'    => true,
          'field_size'  => "50",
          'description' => "Field label",
        ),

         'field_id' => array(
          'title'       => __('Field ID', $this->plugin),
          'tab'     => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_id'],
          'section'     => 'general',
          'field_type'  => 'text',
          'required'    => true,
          'field_size'  => "50",
          'unique'     => true,
          'description' => "Field id attribute (also used as the name of the field)",
        ),

        'field_type' => array(
          'title'       => __('Field Type', $this->plugin),
          'tab'     => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_type'],
          'section'     => 'general',
          'field_type'  => 'select',
          'field_options' => array('text' => 'Text', 'checkbox' => 'Checkbox', 'email' => 'Email', 'select' => 'Select', 'radio' => ' Radio', 'textarea' => 'Textarea'),
          'required'    => true,
          'field_size'  => "50",
          'description' => "Choose a field type",
        ),

        'field_options' => array(
          'title'       => __('Field Options', $this->plugin),
          'tab'     => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_options'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => "50",
          'description' => "Options for select and radio button fields",
        ),


        'field_required' => array(
          'title'       => __('Field Required', $this->plugin),
          'tab'     => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_required'],
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'description' => "Check this box if you want this field to be required",
        ),

        'field_position' => array(
          'title'       => __('Field Position', $this->plugin),
          'tab'         => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_position'],
          'section'     => 'general',
          'field_type'  => 'number',
          'field_min'   => 1,
          'field_max'   => 100,
          'description' => 'Position of the field in the contact form (100 fields max)',
        ),

         'field_description' => array(
          'title'       => __('Field Description', $this->plugin),
          'tab'     => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_description'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'textarea_rows'  => 2,
          'description' => "Description for this field",
        ),

        'field_error' => array(
          'title'       => __('Field Error', $this->plugin),
          'tab'     => $this->class_module()."_registration_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_registration_fields")['field_error'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'textarea_rows'  => 2,
          'description' => "Error to show if this field is required and is empty",
        ),


        // **************************************************************Captcha section

        'recaptcha_enable' => array(
          'title'       => __('Enable reCAPTCHA', $this->plugin),
          'tab'     => $this->class_module()."_captcha",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_captcha")['recaptcha_enable'],
          'section'            => 'general',
          'field_type'  => 'checkbox',
          'description' => "check this box to enable recpatcha.  reCAPTCHA requires an API key, consisting of a 'Site' and a 'Secret' key. You can sign up for a free at <a href='https://www.google.com/recaptcha/admin#whyrecaptcha' target='_blank' >reCAPTCHA key</a>",
        ),
        
        'recaptcha_site_key' => array(
          'title'       => __('Site Key', $this->plugin),
          'tab'     => $this->class_module()."_captcha",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_captcha")['recaptcha_site_key'],
          'section'            => 'general',
          'field_type'  => 'text',
          'field_size'  =>'50',
          'field_enable' => (isset($captcha_settings['recaptcha_enable']) && $captcha_settings['recaptcha_enable'])? true : false,
          'description' => "",
        ),

        'recaptcha_secret_key' => array(
          'title'       => __('Secret Key', $this->plugin),
          'tab'     => $this->class_module()."_captcha",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_captcha")['recaptcha_secret_key'],
          'section'            => 'general',
          'field_type'  => 'text',
          'field_size'  =>'50',
          'field_enable' => (isset($captcha_settings['recaptcha_enable']) && $captcha_settings['recaptcha_enable'])? true : false,
          'description' => "",
        ),

      );


    } // END  private  function register_fields (){


  } // END class Members {
    
} // END if (!class_exists('Custom_Members')) 
