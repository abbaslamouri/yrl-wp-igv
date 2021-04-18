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

if (!class_exists('UserRegister')) {

	class UserRegister extends Member {

    public $name = "User Registration";  // The title for the form used in this class
    public $class_id = "user_registration";  // The ID for the form in this class

		/**
		*Magic constructor
		*/
		public function __construct() {

      // Set custom custom url for wp_registration_url()
      add_filter ('register_url', array($this, 'custom_registration_url'));

      // Registers user (handles the redirect in case of GET request)
      add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
      
      // Add registration form shortcode handler
      add_shortcode( $this->shortcodes()['register'], array( $this, 'shortcode' ) );

      // Perform user registration (handles the redirect in case of a POST request)
      add_action( 'login_form_register', array( $this, 'register_user' ) );

      // Modify registration email to user
      add_filter( 'wp_new_user_notification_email' , array($this, 'user_notification'), 10, 3 );

      // Modify registration email to admin
      add_filter( 'wp_new_user_notification_email_admin' , array($this, 'admin_notification'), 10, 3 );

      // An action function used to include the reCAPTCHA JavaScript fil at the end of the page.
      add_action( 'wp_print_footer_scripts', array($this, 'add_captcha_js_to_footer') ); 

      // Notify user when they are approved
      add_action( 'profile_update', array($this, 'activation_approval_notice'), 10, 2 );

      // add activate action link to the users list
      add_filter('user_row_actions', array($this, 'add_activate_action'), 10, 2);

      // Activate user through user action links
      add_action( 'admin_init', array( $this, 'admin_init' ) );

		}  // END public function __construct()




    /**
    * Sets this class shortcode default attributes
    * @return array of default attributes
    */
    public function default_attributes () {

      return array(
        'submit-title'    => "Register",
        'show-title'      => true,
        'form-action'     => site_url('wp-login.php?action=register'),
        'class-page'      => $this->fetch_page($this->class_module().'_pages', 'user-register'),
        'continue-page'   => $this->fetch_page($this->class_module().'_pages', 'user-login'),
        'form-success'    => (isset($_GET['registration_succesfull']) && $_GET['registration_succesfull'])? true : false,
      );

    }  // End public function form_fields () {






    // /**
    // * A shortcode for rendering the new user registration form.
    // * @param  array   $attributes  Shortcode attributes.
    // * @param  string  $content     The text content for shortcode. Not used.
    // * @return string  The shortcode output
    // */
    // public function shortcode( $attributes, $content = null ) {

    //   $this->atts = shortcode_atts(
    //     $this->default_attributes(),  // default array values
    //     $attributes // array of values passed to the shortcode if any
    //   );

    //    // Retreive form values from the session variable in case of errors and delete the session valiable
    //   if(isset($_SESSION["{$this->prefix}_{$this->class_id}"]) && $_SESSION["{$this->prefix}_{$this->class_id}"] ) {
    //     $this->atts[$this->class_id] = $_SESSION["{$this->prefix}_{$this->class_id}"];
    //     unset($_SESSION["{$this->prefix}_{$this->class_id}"]);
    //   }

    //   return $this->shortcode_render($this->atts);


    // }  // END public function register_form_shortcode( $attributes, $content = null ) {



 

    /**
    * Set custom url for wp_registration_url() 
    * @return custom registration url
    */
    function custom_registration_url( $regsitration_url) {

      return home_url($this->default_attributes()['class-page']);

    }




    /**
    * Redirects the user to the custom registration page  handles the GET request 
    * of wp-login.php?action=register.
    */
    public function redirect_to_custom_register() {

      if ( 'GET' != $_SERVER['REQUEST_METHOD'] ) return;
        
      // otherwise get redirect_to url if it is provided
      $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

      // get page to reditect to (fetch_pages returns page or "/" if no page is found)
      $url = (!empty( $redirect_to ))? add_query_arg( array('redirect_to' => $redirect_to), home_url( $this->default_attributes()['class-page'] )) : home_url($this->default_attributes()['class-page']);
      
      // Redirect
      wp_safe_redirect( $url );
      exit;          

    } // END public function redirect_to_custom_register() {





    /**
    * Handles new user validation and registration (POST Request).  Used through the action hook "login_form_register" activated on wp-login.php
    */
    public function register_user() {


      // If request is not POST return
      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) return;

      // Initialize error array
      $this->errors =  new \WP_Error;

      extract( $this->tab_settings($this->class_module()."_dialogs") );
      extract( $this->tab_settings($this->class_module()."_email") );

      $fields = (get_option($this->class_module()."_registration_fields"))? get_option($this->class_module()."_registration_fields") : array();

      // Check nonce
      if ( ! $this->nonce_check($_POST[$this->class_id], $_POST[$this->class_id], "{$this->prefix}_{$this->class_id}")) 
        $this->errors->add( 'invalid_nonce', __( wp_kses_post( "{$invalid_nonce}" ), $this->plugin));
      
      // Check required fields and email validity
      foreach($fields as $field_id => $field ) {
        if ( ! $this->required_field_check($field)) $this->errors->add( "{$field_id}_empty", __( wp_kses_post( "{$field['field_error']}"), $this->plugin ) );
        if (! $this->email_check($field)) $this->errors->add( 'not_an_email', __( wp_kses_post( "{$not_an_email}" ), $this->plugin ) );
      }

      // Check if passwords match
      if (! $this->do_passwords_match($_POST['reg-password'], $_POST['reg-verify-password']))  $this->errors->add( 'passwords_dont_match',  __( wp_kses_post( "$passwords_dont_match" ), $this->plugin ) );

      // Check if email is unique
      if (! $this->is_email_unique( $_POST['reg-email'] ) )   $this->errors->add( 'email_taken', __( wp_kses_post( "{$email_taken}")."  Forgot your password?  <a href = '".wp_lostpassword_url()."' >Click here to reset</a>", $this->plugin ) );

      // Check captcha
      if (! $this->captcha_check())  $this->errors->add( 'captcha_missing', __( wp_kses_post( "{$captcha_missing}" ), $this->plugin ) );

      if (! empty ($this->errors->errors)){

        // store the username/email in a session variable
        $_SESSION["{$this->prefix}_{$this->class_id}"] = array();

        foreach ($fields  as $field_id => $field) {
          if ($field_id != "reg-password" && $field_id != "reg-verify-password") $_SESSION["{$this->prefix}_{$this->class_id}"][$field_id] =  (isset($_POST[$field_id]))? $_POST[$field_id] : "";     
        }

        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        wp_redirect( home_url( $this->default_attributes()['class-page'] )) ;
        exit;
      }

      // If no errors Insert new user
      $user_data = array(
        'user_login'       => $_POST['reg-email'],
        'user_email'       => $_POST['reg-email'],
        'user_pass'        => $_POST['reg-password'],
        'first_name'       => $_POST['reg-first-name'],
        'last_name'        => $_POST['reg-last-name'],
        'nickname'         => $_POST['reg-first-name'],
      );
      $user_id = wp_insert_user( $user_data );

      if (is_wp_error($user_id)) {  //if errors
        $_SESSION[$this->plugin.'-errors'] = array_combine($user_id->get_error_codes(), $user_id->get_error_messages());
        wp_redirect( home_url($this->default_attributes()['class-page'] )) ;
        exit;
      }

      // add user meta
      foreach ($fields as $field_id => $field) {
        if (! in_array($field_id, array_keys($this->mandatory_registration_fields()))) update_user_meta( $user_id, "{$this->prefix}_{$field_id}", $_POST[$field_id], true);
      }

      // add activation slug to user meta and set to false (until user is activated)
      update_user_meta( $user_id, "{$this->prefix}_activation", false, true);

      // Send admin and user email notifications
      wp_new_user_notification( $user_id, null, $notify_who );
      $success =  new \WP_Error ( 'registered', __( wp_kses_post( "{$this->tab_settings($this->class_module()."_dialogs")['registration_succesful']}"), $this->plugin ) );
      $_SESSION[$this->plugin.'-success'] = array_combine($success->get_error_codes(), $success->get_error_messages());

      // Redirect to login page
      $url = add_query_arg( array('registration_succesfull' => true), home_url($this->default_attributes()['class-page'] ));
      wp_redirect( $url ) ;
      exit;
 
    }  // END  public function register_user() {




    
    /**
    * Modifies new user email notification
    * @return string $wp_new_user_notification_email    New user email notofication
    */
    public function user_notification ( $email, $user, $blogname ) {

      extract( $this->tab_settings($this->class_module()."_email") );

      $email['subject'] = $this->replace_bracketed_values( $this->bracketed_email_options ($user), $user_registration_subject);         // user notification email subject     
      $email['message'] = $this->replace_bracketed_values( $this->bracketed_email_options($user), $user_registration_body);        // user notification email body
      $signature = $this->replace_bracketed_values( $this->bracketed_email_options($user), $email_signature);      // email signature
      $email['message'] .="{$signature}";

      return $email;

    }  // END  public function new_user_email_notification ( $wp_new_user_notification_email, $user, $blogname ) {






    /**
    * Sends admin new user email notification
    * @param string $wp_new_user_notification_email_admin      The new user's email  to admin
    * @param string $user                                      new userser
    * @param string $blogname                                   blogname
    * @return string $wp_new_user_notification_email    New user email notofication
    */
    public function admin_notification ( $email, $user, $blogname ) {

      extract( $this->tab_settings($this->class_module()."_email") );

      $email['to'] = $admin_email_recipients;
      $email['subject'] = $this->replace_bracketed_values( $this->bracketed_email_options ($user), $admin_registration_subject);       // user notification email subject     
      $email['message'] = $this->replace_bracketed_values( $this->bracketed_email_options($user), $admin_registration_body);      // user notification email body
      $signature = $this->replace_bracketed_values( $this->bracketed_email_options($user), $email_signature);      // email signature
      $email['message'] .="{$signature}";

      return $email;
    
    }  // END  public function new_user_email_notification ( $wp_new_user_notification_email, $user, $blogname ) {





    /**
    * An action function used to include the reCAPTCHA JavaScript file
    * at the end of the page.
    */
    public function add_captcha_js_to_footer() {

      if ( !empty(get_option($this->class_module()."_captcha")['recaptcha_enable']) && !empty(get_option($this->class_module()."_captcha")['recaptcha_site_key'] )) {
        echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
      }
    }





    /**
    * Activate user through user action links
    * @param object $user
    */
    public function admin_init() {

      // Bail if current user is not admin or activate user and user id were not set
      if ( ! current_user_can('administrator') || ! isset ($_GET['activate_user']) || ! isset($_GET['user_id']) || ! $_GET['user_id']) return;

      // Retreive user
      $user = get_user_by ("ID",$_GET['user_id']);

      $activate = (isset($_GET['activate_user']))? $_GET['activate_user'] : false;

      // Update user meta
      if ($activate) {
        update_user_meta($user->ID, $this->prefix."_activation", true);
      } else {
        update_user_meta($user->ID, $this->prefix."_activation", false);
      }

      $this->send_message($user, $activate);


    }  // END  public function init() {




  

    /**
    * Notify user when they are approved fired through profile update
    * @param string $user_id
    * @param Object $old_user_data                                      new userser
    */
    public function activation_approval_notice($user_id, $old_user_data) {

      // nothing happens when we deactivate
      if ( ! isset($_POST[$this->prefix."_activation"]) )   return;

      // Retreive user
      $user = get_user_by ("ID", $user_id);
      $activate =  $_POST[$this->prefix."_activation"];

     $this->send_message($user, $activate);

    }







    /**
    * Sends activation approval message
    * @param object $user
    * @param boolean $activate
    */
    function send_message($user, $activate) {

      extract( $this->tab_settings($this->class_module()."_email") );

      // retreive user activation meta
      $activated = get_user_meta($user->ID, $this->prefix."_activated", true); // Set to true the first time the user is activated

      // member is being activated (// the the user has not already been activated set activated and send email)
      if ( $activate  && (! isset($activated) || ! $activated) )  { 

        // add user meta to the database
        update_user_meta( $user->ID, $this->prefix."_activated", true);

        $subject = $this->replace_bracketed_values( $this->bracketed_email_options ($user), $registration_approved_subject);         // user notification email subject    
        $message = $this->replace_bracketed_values( $this->bracketed_email_options($user), $registration_approved_body);          // user notification email body
        $signature = $this->replace_bracketed_values( $this->bracketed_email_options($user), $email_signature);        // email signature

        // send notificationcompose email
        wp_mail($user->user_email, "{$subject}", "{$message} {$signature}");

      }

    }



	} // END class UserRegister {

		
} // END if (!class_exists('UserRegister')) 



