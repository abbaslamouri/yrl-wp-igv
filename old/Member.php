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

if (!class_exists('Member')) {

  class Member extends Dashboard {

    public $name = "Member";  // this module name
    //public $template = "admin";   // admin submenu template

    /**
    *Magic constructor.  Gets called when an object is instantiated
    */
    public function __construct() {

      // Instantiate Custom post Type class if custom post type is enabled
      if (class_exists('Ultimate\\Includes\\Admin')) $this->admin = new Admin($this);
      
      // add admin init action
      add_action('admin_init', array($this, 'admin_init'));

      // Add admin Submenus, section and fields
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );
      
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

      // Instantiate the User_Registerclass
      if (class_exists('Ultimate\Includes\\UserRegister')) new UserRegister($this);

      // Instantiate the User_Login class
      if (class_exists('Ultimate\Includes\\UserLogin')) new UserLogin($this);

      // Instantiate the User_Reset_PWD class
      if (class_exists('Ultimate\Includes\\UserLostPWD')) new UserLostPWD($this);

       // Instantiate the User_Reset_PWD class
      if (class_exists('Ultimate\Includes\\UserResetPWD')) new UserResetPWD($this);

      // Instantiate the Security class
      if (class_exists('Ultimate\Includes\\Security')) new Security($this);
      

    }  // END public function __construct()

    
    /**
    * Class module name
    *
    * @return string module name
    *
    */
    public function class_module() {

      return $this->prefix."_member";

    }



     /**
    * Class page names and shortcodes
    *
    * @return string shortocodes
    *
    */
    public function shortcodes () {

      return  array(
        'login'       => $this->plugin."-login",
        'register'    => $this->plugin."-register",
        'profile'     => $this->plugin."-profile",
        'lost-pwd'    => $this->plugin."-lost-pwd",
        'reset-pwd'   => $this->plugin."-reset-pwd",
        'security'  => $this->plugin."-security",
      );

    }







    /**
    * Populates shortcodes.
    *
    * @param  array   $atts  Shortcode attributes.
    *
    * @return string  The shortcode output
    */
    public function member_shortcode( $class, $atts ) {

      // Render the logout template
      if ( is_user_logged_in() ) return $this->admin->get_template_html($this, 'logout');
      
      // Render lost password template
      return $this->admin->get_template_html($class, 'member-form', $atts);

    } // RND  public function member_shortcode( $attributes, $content = null ) {







    /**
    * Settings setting api option
    *
    * @return array settings_option
    *
    */
    public function class_settings() {

      return (get_option($this->class_module()."_settings")? get_option($this->class_module()."_settings") : $this->settings_defaults());

    }





    /**
    * Captcha setting api option
    *
    * @return array captcha_option
    *
    */
    public function class_captcha() {

      return (get_option($this->class_module()."_captcha")? get_option($this->class_module()."_captcha") : $this->captcha_defaults());

    }




    /**
    * Dialogs setting api option
    *
    * @return array disalogs_option
    *
    */
    public function class_dialogs() {

      return (get_option($this->class_module()."_dialogs")? get_option($this->class_module()."_dialogs") : $this->dialogs_defaults());

    }



    /**
    * Email setting api option
    *
    * @return array email_option
    *
    */
    public function class_email() {

      return (get_option($this->class_module()."_email")? get_option($this->class_module()."_email") : $this->email_defaults());

    }






    /**
    * Checks if a valid nonce was submitted
    *
    * @return Boolean/wp_errors
    *
    */
    public function nonce_check($class, $errors) {


       // Check if nonce is valid
      if( ! $_POST[$class->class_id] || !wp_verify_nonce($_POST[$class->class_id], '_'.$this->prefix.'_'.$class->class_id) ) {
        $errors->add( 'invalid_nonce', __( wp_kses_post( "{$this->class_dialogs()['invalid_nonce']}" ), $this->plugin));
        return $errors;
      } else {
        return true;
      }


    }







    /**
    * Checks if all required fields are submitted and that emails are valid
    *
    * @return Boolean/wp_errors
    *
    */
    public function required_fields_and_email_check($class, $errors) {


      //check required fields and email fields
      foreach($class->form_fields() as $id => $field ) {

        // Check required fields
        if ( $field['field_required'] && (!isset( $_POST[$id]) || ! $_POST[$id]) ) $errors->add( "{$id}_empty", __( wp_kses_post( "{$field['field_label']} is required."), $this->plugin ) );

        // Check emails
        if ( $field['field_type'] === "email" && isset($_POST[$id]) && $_POST[$id] && ! is_email( sanitize_email($_POST[$id])) ) {
          $errors->add( 'not_an_email', __( wp_kses_post( "{$this->class_dialogs()['not_an_email']}" ), $this->plugin ) );
        }

      }

      if (!empty($errors->errors)) return $errors;

      return true;

    }







    /**
    * Checks if a password is correctvalid nonce was submitted
    *
    * @return BBoolean/wp_errors
    *
    */
    public function password_check($user, $password, $error_code, $errors) {

      //check if the current password is correct
      if (isset($password) && $password && ! wp_check_password( $password, $user->user_pass, $user->ID )) {
        $errors->add ( $error_code, __( wp_kses_post( "{$this->class_dialogs()[ $error_code] }")." <a class='forgot-password' href = '".wp_lostpassword_url()."'>Did you forget your password? </a>", $this->plugin ));
        return $errors;
      } else {
        return true;
      }


    }




    /**
    * Checks if two passwords match
    *
    * @return Boolean/wp_errors
    *
    */
    public function do_passwords_match($pass_1, $pass_2, $error_code, $errors) {

     // Do new passwords match ?
      if ( isset($pass_1) && isset($pass_2) && $pass_1 != $pass_2) {
        $errors->add( $error_code, __( wp_kses_post( "{$this->class_dialogs()[$error_code]}" ), $this->plugin ) );
        return $errors;
      } else {
        return true;
      }


    }







    /**
    * Checks if an old password was used
    *
    * @return Boolean/wp_errors
    *
    */
    public function old_password_check($password, $old_pwds, $errors) {

     // Check if the password has been used before
      if (isset($password) && !empty($password) && in_array(wp_hash(trim($password)), $old_pwds)) {
        $errors->add ( 'old_password_reused', __( wp_kses_post( "{$this->class_dialogs()['old_password_reused']}" ), $this->plugin ) );
        return $errors;
      } else {
        return true;
      }


    }







    /**
    * Checks if email is unique
    *
    * @return Boolean/wp_errors
    *
    */
    public function is_email_unique( $errors ) {

      // Email is unique (Email is used as both username and email)
      if ( isset($_POST['user_email']) && is_email( sanitize_email($_POST['user_email'])) && (username_exists( sanitize_email($_POST['user_email'])) || email_exists( sanitize_email($_POST['user_email'])) )){ 
        $errors->add( 'email_taken', __( wp_kses_post( "{$this->class_dialogs()['email_taken']}")."  Forgot your password?  <a href = '".wp_lostpassword_url()."' >Click here to reset</a>", $this->plugin ) );
        return $errors;
      } else {
        return true;
      }


    }







    /**
    * Checks captcha
    *
    * @return Boolean/wp_errors
    *
    */
    public function captcha_check( $errors ) {

      // If captcha is enebled
      if ( $this->class_captcha()['recaptcha_enable'] && ! $this->verify_recaptcha() ) {
        $errors->add( 'captcha_missing', __( wp_kses_post( "{$this->class_dialogs()['captcha_missing']}" ), $this->plugin ) );
        return $errors;
      } else {
        return true;
      }

    }






     /**
    * Checks that the reCAPTCHA parameter sent with the registration
    * request is valid.
    *
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
    * Admin init tidbits.
    */
    public function admin_init () {

      extract($this->class_settings());
      extract($this->class_captcha());

       // If registration is open show notice 
      if ( get_option( 'users_can_register' ) ) {
        $errors = new \WP_Error( 'users_can_register', __("Any user can register.  This is not recommended", $this->plugin));
        $_SESSION[$this->plugin.'-admin-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());

      }

      // Check if required pages are selected and create Member class default pages if create_default_page option is true
      $this->admin->check_required_pages($this);

      
      // check if recaptcha keys are set when captcha is enabled
     //$this->check_captcha();
       if(isset($recaptcha_enable) && $recaptcha_enable && (!isset($recaptcha_site_key) || !isset($recaptcha_secret_key))) {
        // Display admin errors if any
        $errors = new \WP_Error( 'recptcha', __("Captcha is enabled but site and secret keys are not set", $this->plugin));
        $_SESSION[$this->plugin.'-admin-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());
      }


      if ( ! $this->admin->check_required_pages($this) && isset($create_default_pages) && $create_default_pages  ) {
        $errors = new \WP_Error ( 'pages-enabled', __("Create default pages is enabled but the pages have not been created.  Please Check the 'Create Default Pages' and reactivate the plugin", $this->plugin));
        $_SESSION[$this->plugin.'-admin-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());
      }


    }  // END static function init ()





    /**
    * Saves custom user profile fields
    * 
    * @param  object $profile user A WP_User object
    *
    */
    function display_user_profile_fields( $user ) {


      $activation = (is_object($user) && get_user_meta($user->ID, $this->prefix."_activation", true))?  true : false;
      $activated = (is_object($user) && get_user_meta($user->ID, $this->prefix."_activated", true))?  true : false;
     
      ?>
      <h3><?php esc_html_e( $this->name, $this->plugin  ); ?></h3>

      <table class="form-table">

        <tr>
          <th><label for="<?php echo $this->prefix."_activation"; ?>" ><?php ( ! $activation )? esc_html_e( 'Activate User', $this->plugin ) : esc_html_e( 'Deactivate User', $this->plugin ) ; ?></label>
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
        <tr><input type="hidden" name="<?php echo "{$this->plugin}-member-activation"; ?>" value="1" ></tr>

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

      //var_dump($_POST);die;
      
      // This is required becuase woocommerce updates the profile when order status changes
      if (! isset($_POST["{$this->plugin}-member-activation"])) return;

      $activation = (isset($_POST[$this->prefix."_activation"]))? true : false;
      $activated = (isset($_POST[$this->prefix."_activated"]))? true : false;

      // # again do this only if you can
      if(!current_user_can('manage_options')) return false;

      update_user_meta( $user_id, $this->prefix."_activation", $activation);
      update_user_meta( $user_id, $this->prefix."_activated", $activated);

    }




    /**
    * Add colum to the admin user list
    * 
    * @param array $columns user list columns
    * 
    * @return array $columns
    */
    public function add_user_id_column($columns) {

      $columns['phone'] = Phone;
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
      $user = get_user_by('ID', $user_id);

      if ($column_name =='activation') {
        $activation = (get_user_meta($user->ID, $this->prefix."_activation", true))?  true : false;
        return ($activation)? 'Active' : 'Inactive'; 
      } elseif ($column_name == 'phone') {
        return get_user_meta($user->ID, 'billing_phone', true);
      } else {
        return $value;
      }

    }




    /**
    * Composes email
    *
    * @param array $email 
    * @param Object $user                                
    * @param string $blogname                            
    * @param string $subject                            
    * @param string $body                             
    * @param string $signature                             
    *
    * @return araray  $email
    */
    public function compose_email ( $email, $user, $blogname, $subject, $body, $signature ) {

      // user notification email subject     
      $email['subject'] = $this->replace_bracketed_values($user, $subject);

      // user notification email body
      $email['message'] = $this->replace_bracketed_values($user, $body);

      // email signature
      $signature = $this->replace_bracketed_values($user, $signature);
  

      $email['message'] .="{$signature}";


      return $email;

    }  // END  public function new_user_email_notification ( $wp_new_user_notification_email, $user, $blogname ) {







     /**
    * Rlaces brackets with options
    *
    * @param Object $user, string $text
    *
    * @return string $text with bracketed items replaced by actual option values
    */
    public function replace_bracketed_values ($user, $text) {

      
      foreach ($this->possible_email_options($user) as $key => $value) {
        $text = str_replace($key, $value, $text);
      }

      return $text;

    } 



  
    /**
    * Returns an array of all possible email options for the settings api
    * @return array of possible options
    */
    public function possible_email_options ( $user = null) {

      return array(

        '[blog-name]' => get_bloginfo('name'),
        '[first-name]' => (isset($user) && !empty($user))? $user->first_name : '',
        '[last-name]' => (isset($user) && !empty($user))? $user->last_name : '',
        '[user-name]' => (isset($user) && !empty($user))? $user->user_login : '',
        '[company]' =>  get_user_meta($user->ID, 'billing_company', true),
        '[login-page]' =>  wp_login_url(),

      );

    }





    /**
    * Default settings
    * @return array of default settings
    */
    public function settings_defaults() {

      return array(

        //Settings tab
        'create_default_pages'   => false,
        'force_admin_activation' => false,
        'pwd_reset_duration'     => 90,
        'force_admin_pwd_reset'  => false,

      );

    }



    /**
    * Default pages
    * @return array of default pages
    */
    public function pages_defaults() {

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

    }




    /**
    * Default dialogs
    * @return array of default dialogs
    */
    public function dialogs_defaults() {

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
A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. 
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

    }



    /**
    * Default email
    * @return array of default email
    */
    public function email_defaults() {

      return array(

        'admin_email_recipients' => get_bloginfo('admin_email'),
        'disabale_password_change_notification'    => false,
        'notify_who'    => 'both',
        // 'from_email_address'    => '', 
        // 'from_email_name'     => '',
        'user_registration_subject'  => 'Your [blog-name] Registration',

        'user_registration_body' => <<<ENDHTML
Thank you for registering for [blog-name].
Your registration has been received and is pending approval.
You will receive login instructions upon approval of your account.

Your email:  [user-name]

Login link:  [login-page]
ENDHTML
,
        
        'admin_registration_subject'  => 'New user registration for [blog-name]',
        'admin_registration_body'    => <<<ENDHTML
New user registration on your site [blog-name].

Name:  [first-name] [last-name] 
Company: [company] 
Username:  [user-name]

Log in to activate this user [login-page]
ENDHTML
,
        
        'registration_approved_subject'   => 'Your registration for [blog-name] has been approved',
        
        'registration_approved_body'     => <<<ENDHTML
Your registration for [blog-name] has been approved.
Your registration information is below.
You may wish to retain a copy for your records.

Email: [user-name]

You may log in and change your password here:
[login-page]
ENDHTML
,
        
        'lostpassword_subject' => 'Your password reset instructions for [blog-name]',

        'lostpassword_body'     => <<<ENDHTML
Password reset instructions:

Someone requested that the password be reset for account [user-name].
If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.
To reset your password, visit the following address:
 
ENDHTML
,
    
         'password_reset_subject'      => '[blog-name] notice of password change',


        'password_reset_body'     => <<<ENDHTML
Hi [first-name],

Your password for [blog-name] has been reset.

If you did not change your password, please contact [blog-name] Administrator 

Regards,
All at [blog-name]

ENDHTML
,

        
        'email_signature'  => <<<ENDHTML
       

 ---------------------------------- 
This is an automated message from [blog-name]
Please do not reply to this email.
ENDHTML
,

      );

    }




    /**
    * Default captcha
    * @return array of default captcha
    */
    public function captcha_defaults() {

      return array(
      // Recaptcha tab
      'recaptcha_enable' => false,
      'recaptcha_site_key' => '',
      'recaptcha_secret_key' => ''
   
      ); 

    }  // END public function settings_defaults() {





    /**
    * All Default merged into a single array
    * @return array of default merged defaults
    */
    public function merged_settings_defaults() {

      return array_merge($this->settings_defaults(), $this->pages_defaults(), $this->dialogs_defaults(), $this->email_defaults(), $this->captcha_defaults() );

    }


      

    /**
    * Admin menus
    */
    public function  admin_menus (){

      return array() ;

    } // END public static function  admin_menus (){ 

      
      

    /*
    * Admin submenus
    */
    public  function  admin_submenus (){

      $dashboard = new dashboard;

      // Set admin submenu pages
      return array(

          array(
          'parent_id'    => $dashboard->class_module(),    // The id name for the parent menu (or the file name of a standard WordPress admin page).  
          'page_title'   => __($dashboard->name, $this->plugin),    // Text to be displayed in the browser window.   
          'menu_title'   => __('Members', $this->plugin),      // Text to be displayed for the menu   
          'caps'         => 'administrator', // The capability required for this page to be displayed to the user.
          'id'         => $this->class_module(),    //Unique id for this menu  
         'callback'     => array($this, 'submenu_template'), // The function to be called to output the content for this page.
        ),

      );

    }



    /**
    * Admin sections 
    */
    public function  admin_tabs (){

      //$admin_menu = new Admin_Menu ;  // Admin Menu class (handles sanitization and errors)

      return array(  

          $this->class_module()."_settings"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Settings', $this->plugin),
          'callback'   => function() { // Function that echos out any content at the top of the section (between heading and this->fields).
            echo '';
          },

        ), 

         
        $this->class_module()."_pages" => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Pages', $this->plugin),
          'callback'   => function() { // Function that echos out any content at the top of the section (between heading and this->fields).
            echo '';
          },

        ), 


         $this->class_module()."_dialogs"  => array(
          
          'title'      => __('Dialogs & Error Messages', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Dialogs', $this->plugin),
          'callback'   => function() { // Function that echos out any content at the top of the section (between heading and this->fields).
            echo '';
          },

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

        ),


         $this->class_module()."_captcha"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('reCAPTCHA', $this->plugin),
          'callback'   => function() { // Function that echos out any content at the top of the section (between heading and this->fields).
            echo '';
          },

        ), 


        $this->class_module()."_other"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Other', $this->plugin),
          'callback'   => function() { // Function that echos out any content at the top of the section (between heading and this->fields).
            echo '';
          },

        ), 



      );

    }




    /**
    * Admin Fields
    */
     public function  admin_fields (){

       
      // Get recaptcha settings. If enabled show other recpatcha fields
      $captcha_settings = (null !== get_option($this->class_module()."_captcha"))? get_option($this->class_module()."_captcha") : array();

      $main_fields = array(

        // settings setion
        'create_default_pages'   => array(
          'title'        => __('Create Default Pgaes', $this->plugin),
          'css_class'        => 'ui-toggle',
          'tab'          => $this->class_module()."_settings",  // Section to which this field belongs
          'callback'     => array($this, 'input_field'),
          'field_type'   => 'checkbox',
          'description'  => "Check this box to create default pages (You must deactivate and reactivate the plugin to create the pages",
          'default'    => $this->settings_defaults()['create_default_pages'],         
        ),


        'force_admin_activation' => array(
          'title'       => __('Force Admin Activation', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'checkbox',
          'disabled'    => true,
          'description' => "Require Admins to be activated before they can sign in",
          'default'    => $this->settings_defaults()['force_admin_activation'],          
        ),


        'force_admin_pwd_reset'     => array(
          'title'       => __('Force Admin Reset', $this->plugin),
          'css_class'       => $this->plugin.' ui-toggle',
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'checkbox',
          'disabled'   => true,
          'description' => "Check if you want to force admins to reset password",
          'default'    => $this->settings_defaults()['force_admin_pwd_reset'],
        ),

        
        'pwd_reset_duration'     => array(
          'title'       => __('Pasword Reset Duration', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => $this->plugin.' ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'number',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'field_min'   => 1,  // Numeric field minimum
          'field_max'   => 365, // Numeric field maximim
          'description' => "Password reset duration in days",  // Custom field description(supplied in the $args)
          'default'    => $this->settings_defaults()['pwd_reset_duration'],
        ),


        // pages section
        'user-login' => array(
          'title'       => __( 'User Login', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => 'ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'         => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Login Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['login'].']',  // Page content (shortcode)
          'description' => "Create Login page and this shortcode to the page     <input value ='[".$this->shortcodes()['login']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['user-login'],
        ),

        'user-register' => array(
          'title'       => __( 'User Register', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => 'ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Registration Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['register'].']',  // Page content (shortcode)
          'description' => "Create Register page and this shortcode to the page     <input value ='[".$this->shortcodes()['register']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['user-register'],
        ),

        'user-profile' => array(
          
          'title'       => __( 'User Profile', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => 'ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Profile Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['profile'].']',  // Page content (shortcode)
          'description' => "Create Profile page and this shortcode to the page     <input value ='[".$this->shortcodes()['profile']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['user-profile'],

        ),

        'user-lost-pwd' => array(
          'title'       => __( 'User Lost Password', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => 'ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Lost Password Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['lost-pwd'].']',  // Page content (shortcode)
          'description' => "Create Lost Password page and this shortcode to the page     <input value ='[".$this->shortcodes()['lost-pwd']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['user-lost-pwd'],
        ),

        'user-reset-pwd' => array(
          'title'       => __( 'User Reset Password', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => 'ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Reset Password Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'content'    => '['.$this->shortcodes()['reset-pwd'].']',  // Page content (shortcode)
          'description' => "Create Reset Password page and this shortcode to the page     <input value ='[".$this->shortcodes()['reset-pwd']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['user-reset-pwd'],
        ),

         'user-expired-pwd' => array(
          'title'       => __( 'User Expired Password', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => $this->plugin.' ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'required'  => true,
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Expired Password Page',  // custom field (default value) (supplied in the $args)
          'content'    => '['.$this->shortcodes()['security'].']',  // Page content (shortcode)
          'description' => "reate Expired Password page and this shortcode to the page     <input value ='[".$this->shortcodes()['security']."]' size='50' readonly >",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['user-expired-pwd'],
        ),

        'redirect-after-login' => array(   
          'title'       => __( 'Redirect After Login', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => 'ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Redirect After Login Page',  // custom field (default value) (supplied in the $args)
          'description' => "Page to redurect to after login",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['redirect-after-login'],
        ),

        'redirect-after-logout' => array( 
          'title'       => __( 'Redirect After Logout', $this->plugin), // Formatted title of the field. Shown as the label for the field during output.
          'css_class'       => 'ui-toggle',  //  CSS Class to be added to the <tr> element when the field is output. (supplied in the $args)
          'tab'     => $this->class_module()."_pages",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'), // function responsible for rendering this field
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Redirect After Logout Page',  // custom field (default value) (supplied in the $args)
          'description' => "Page to redurect to after logout",  // Custom field description(supplied in the $args)
          'default'    => $this->pages_defaults()['redirect-after-logout'],
        ),


        // Dialogs section
        'invalid_nonce'    => array(  
          'title'       => __('Invalid nonce', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['invalid_nonce'],
        ),

         'captcha_missing'    => array(  
          'title'       => __("Captcha missing", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['captcha_missing'],
        ),

        'not_an_email'    => array(  
          'title'       => __("Invalid email", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['not_an_email'],
        ),


        'incorrect_password'    => array(  
          'title'       => __("Incorrect password", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['incorrect_password'],
        ),

         'passwords_dont_match'    => array(  
          'title'       => __("Passwords don't match", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['passwords_dont_match'],
        ),

        'email_taken'    => array(  
          'title'       => __('Email taken', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['email_taken'],
        ),

        'pending_activation'    => array(  
          'title'       => __('Pending activation', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['pending_activation'],
        ),

        'registration_succesful'    => array(  
          'title'       => __('Registration succesful', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 3,
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['registration_succesful'],
        ),

        'lostpassword_message'    => array(  
          'title'       => __('Lost your password message', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['lostpassword_message'],
        ),

        'lostpassword_email_sent'    => array(  
          'title'       => __('Lost password email sent message', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 4,
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['lostpassword_email_sent'],
        ),

        'password_reset_succesful'    => array(  
          'title'       => __('Password reset succesful', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['password_reset_succesful'],
        ),

        'user_not_found'    => array(  
          'title'       => __('User not found', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['user_not_found'],
        ),

         'catch_all_error_message'    => array(  
          'title'       => __('Catch all error message', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 2,
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['catch_all_error_message'],
        ),

        'expired_password_message'    => array(  
          'title'       => __("Expired password message", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['expired_password_message'],
        ),

        'reused_password_message'    => array(  
          'title'       => __("Reused password message", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['reused_password_message'],
        ),

         'current_password_error'    => array(  
          'title'       => __("Current password error", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['current_password_error'],
        ),

        'new_passwords_dont_match'    => array(  
          'title'       => __("New passwords don't match", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['new_passwords_dont_match'],
        ),

        'old_password_reused'    => array(  
          'title'       => __("Old password re-used", $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_dialogs",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'description' => '' ,
          'default'    => $this->dialogs_defaults()['old_password_reused'],
        ),


        // Email section
        'admin_email_recipients'    => array(  
          'title'       => __('Admin Email Recipients', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
          'field_size'  => '100',
          'description' => 'Comma seperated admin email recipients' ,
          'default'    => $this->email_defaults()['admin_email_recipients'],
        ),

        'disabale_password_change_notification'   => array(
          'title'        => __('Disable password change notification', $this->plugin),
          'css_class'        => 'ui-toggle',
          'tab'          => $this->class_module()."_email",  // Section to which this field belongs
          'callback'     => array($this, 'input_field'),
          'field_type'   => 'checkbox',
          'description'  => "check this box to disable sending password change notification email to admin",
          'default'    => $this->email_defaults()['disabale_password_change_notification'],          
        ),


        'notify_who' => array(
          'title'       => __('Send Registration Notification to', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab'         => $this->class_module()."_email",  // tab to which this field belongs
          'field_type'      => 'select',
          'field_options'  => array('user' => 'User', 'both' => 'Admin & User'),
          'description' => "Select whom to send the registration notification to",
          'default'    => $this->email_defaults()['notify_who'],
        ),

        // 'from_email_address'    => array(  
        //   'title'       => __('Custom email address', $this->plugin),
        //   'css_class'       => 'ui-toggle',
        //   'tab' => $this->class_module()."_email",
        //   'callback'    => array($this, 'input_field'),
        //   'field_type'  => 'text',
        //   'field_size'  => '50',
        //   'description' => '(optional) email@yourdomain.com' ,
        //   'default'    => $this->email_defaults()['from_email_address'],
        // ),

        // 'from_email_name'    => array(  
        //   'title'       => __('Custom email name', $this->plugin),
        //   'css_class'       => 'ui-toggle',
        //   'tab' => $this->class_module()."_email",
        //   'callback'    => array($this, 'input_field'),
        //   'field_type'  => 'text',
        //   'field_size'  => '50',
        //   'description' => '(optional) John Smith',
        //   'default'    => $this->email_defaults()['from_email_name'],
        // ),


        'user_registration_subject'    => array(  
          'title'       => __('Registration email subject', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
          'field_size'  => '100',
          'description' => '' ,
          'default'    => $this->email_defaults()['user_registration_subject'],
        ),

        'user_registration_body'    => array(  
          'title'       => __('Registration email body', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 10,
          'description' => '' ,
          'default'    => $this->email_defaults()['user_registration_body'],
        ),

        'admin_registration_subject'    => array(  
          'title'       => __('Admin Registration notification subject', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,
          'default'    => $this->email_defaults()['admin_registration_subject'],
        ),

        'admin_registration_body'    => array(  
          'title'       => __('Admin registration message body', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 10,
          'description' => '' ,
          'default'    => $this->email_defaults()['admin_registration_body'],
        ),

        'registration_approved_subject'    => array(  
          'title'       => __('Registration approved email subject', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,
          'default'    => $this->email_defaults()['registration_approved_subject'],
        ),

        'registration_approved_body'    => array(  
          'title'       => __('Registration approved email body', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 10,
          'description' => '' ,
         'default'    => $this->email_defaults()['registration_approved_body'],
        ),

        'lostpassword_subject'    => array(  
          'title'       => __('Lost password subject', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,
         'default'    => $this->email_defaults()['lostpassword_subject'],

        ),

        'lostpassword_body'    => array(  
          'title'       => __('Lost password body', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 5,
          'description' => '' ,
          'default'    => $this->email_defaults()['lostpassword_body'],          
        ),

         'password_reset_subject'    => array(  
          'title'       => __('Password reset subject', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
           'field_size'  => '100',
          'description' => '' ,
         'default'    => $this->email_defaults()['password_reset_subject'],
        ),

        'password_reset_body'    => array(  
          'title'       => __('Password reset body', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 5,
          'description' => '' ,
         'default'    => $this->email_defaults()['password_reset_body'],
        ),

        'email_signature'    => array(  
          'title'       => __('Email signature', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab' => $this->class_module()."_email",
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'textarea',
          'textarea_rows' => 5,
          'description' => 'Optional' ,
         'default'    => $this->email_defaults()['email_signature'],
        ),


        // Captcha section
        'recaptcha_enable' => array(
          'title'       => __('Enable reCAPTCHA', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab'     => $this->class_module()."_captcha",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'checkbox',
          'description' => "check this box to enable recpatcha.  reCAPTCHA requires an API key, consisting of a 'Site' and a 'Secret' key. You can sign up for a free at <a href='https://www.google.com/recaptcha/admin#whyrecaptcha' target='_blank' >reCAPTCHA key</a>",
          'default'    => $this->captcha_defaults()['recaptcha_enable'],          
        ),

       

      );


      $enabled_fields = array(
        
        
        'recaptcha_site_key' => array(
          'title'       => __('Site Key', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab'     => $this->class_module()."_captcha",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
          'field_size'  =>'50',
          'field_enable' => (isset($captcha_settings['recaptcha_enable']) && $captcha_settings['recaptcha_enable'])? true : false,
          'description' => "",
          'default'    => $this->captcha_defaults()['recaptcha_site_key'],          
        ),

        'recaptcha_secret_key' => array(
          'title'       => __('Secret Key', $this->plugin),
          'css_class'       => 'ui-toggle',
          'tab'     => $this->class_module()."_captcha",  // Section to which this field belongs
          'callback'    => array($this, 'input_field'),
          'field_type'  => 'text',
          'field_size'  =>'50',
          'field_enable' => (isset($captcha_settings['recaptcha_enable']) && $captcha_settings['recaptcha_enable'])? true : false,
          'description' => "",
          'default'    => $this->captcha_defaults()['recaptcha_secret_key'],         
        ),


      );

      return (isset($captcha_settings['recaptcha_enable']) && $captcha_settings['recaptcha_enable'])? array_merge($main_fields, $enabled_fields) : $main_fields; 


    } // END  private  function register_fields (){


  } // END class Member {
    
} // END if (!class_exists('Custom_Member')) 
