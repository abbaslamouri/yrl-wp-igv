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

if (!class_exists('UserLogin')) {

	class UserLogin extends Member{


    public $name = "User Login";  // The title for the form used in this class
    public $class_id = "user_login";  // The ID for the form in this class


		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {

      // redirect woocommerce login account page
      add_action('template_redirect', array($this, 'woo_login_redirect'));

      // Set custom url for wp_login_url()
      add_filter ('login_url', array($this, 'custom_login_url'), 10, 2);

      // Rediredt all login to custom login page 
      add_action( 'login_form_login', array( $this, 'custom_login_page' ) );

      // Add login form shortcode handler
      add_shortcode( $this->shortcodes()['login'], array( $this, 'shortcode' ) );

     
      // Redirect fires after user is logged in but before user is authenticated
      add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );

      // Redirect user after logout
      add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );

      // wp_authenticate hook fires before the Wordpress authentication process
      add_action( 'wp_authenticate', array($this, 'authenticate'));

      // wp_authenticate_user fires after WordPress's basic validation, but before a user is logged in
      add_filter( 'wp_authenticate_user', array($this, 'authenticate_user'), 10, 2 );

      
		}  // END public function __construct()





    /**
    * Sets this class shortcode default attributes
    * @return array of default attributes
    */
    public function default_attributes () {

      return array(
        'submit-title'    => "Login",
        'show-title'      => true,
        'form-action'     => site_url('wp-login.php'),
        'class-page'      => $this->fetch_page($this->class_module().'_pages', 'user-login'),
        'continue-page'   => $this->fetch_page($this->class_module().'_pages', 'user-login'),
        'redirect_to'     =>  ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        'form-success'    => (isset($_GET['login_succesfull']) && $_GET['login_succesfull'])? true : false,
      );

    }  // End public function form_fields () {





    // /**
    // * A shortcode for rendering the new user registration form.
    // * @param  array   $attributes  Shortcode attributes.
    // * @param  string  $content     The text content for shortcode. Not used.
    // * @return string  The shortcode output
    // */
    // public function shortcode( $attributes, $content = null ) {

    //     $this->atts = shortcode_atts(
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
    * redirect woocommerce account page to custom login if user is not logged in
    */
    public function woo_login_redirect (){

      if (class_exists('\Woocommerce') && is_account_page() && ! is_user_logged_in()) {
        $this->custom_login_page();
      }

    }  // END  public function woo_login_redirect (){





    
    /**
    * Set custom url for wp_login_url() 
    * @return custom loginurl
    */
    function custom_login_url( $login_url, $redirect) {

      return ($redirect)? add_query_arg( 'redirect_to', esc_url($redirect), home_url($this->default_attributes()['class-page']) ) : home_url($this->default_attributes()['class-page']) ;

    } // END function custom_login_url( $login_url, $redirect) {





    /**
    * Redirect the user to the custom login page instead of wp-login.php.
    */
    public function custom_login_page() { 

      if ( 'GET' != $_SERVER['REQUEST_METHOD'] ) return;

      $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : site_url( $this->default_attributes()['class-page']);

      // Add query arg to url
      $url = (!empty( $redirect_to ))? add_query_arg( array('redirect_to' => $redirect_to), home_url( $this->default_attributes()['class-page'] )) : home_url($this->default_attributes()['class-page']);

      // Redirect
      wp_safe_redirect( $url );
      exit;          

    }  // END function redirect_to_custom_login() {



   



     /**
    * Returns the URL to which the user should be redirected after the (successful) login.
    *
    * @param string           $redirect_to           The redirect destination URL.
    * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
    * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
    *
    * @return string Redirect URL
    */
    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

      if (is_wp_error($user)) {  // is user a wp_error

        $errors = $user;

         // store the username/email in a session variable
        $_SESSION[$this->prefix.'_'.$this->class_id] = array(
          'log' => (isset($_POST['log']))? $_POST['log'] : "",
        );
        
        $_SESSION[$this->plugin.'-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());
        //$page = $this->fetch_page($this->class_module().'_pages', 'user-login');
        wp_redirect( home_url( $this->default_attributes()['class-page'] )) ;
        exit;

      } else {  // If no error set the redirect to page

        return wp_validate_redirect( home_url( $this->fetch_page($this->class_module().'_pages', 'redirect-after-login') ), home_url() );

      }

    }  // END public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {







    /**
    * Redirect to custom login page after the user has been logged out.
    */
    public function redirect_after_logout() {

      wp_safe_redirect( home_url($this->fetch_page($this->class_module().'_pages', 'redirect-after-logout').'?login=logout' ) );
      exit;

    }  // END public function redirect_after_logout() {







    /**
    * Checks the user credentials before the WordPress authentication process
    * @param string            $username   The user name used to log in.
    */
    public function authenticate ( $username) {

      // If request is not POST return
      if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) return;

      // Initialize error array
      $this->errors =  new \WP_Error;

      extract( $this->tab_settings($this->class_module()."_dialogs") );

      $fields = $this->mandatory_login_fields();

       // Check nonce
      if ( ! $this->nonce_check($_POST[$this->class_id], $_POST[$this->class_id], "{$this->prefix}_{$this->class_id}")) 
        $this->errors->add( 'invalid_nonce', __( wp_kses_post( "{$invalid_nonce}" ), $this->plugin));

       // Check required fields and email validity
      foreach($fields as $field_id => $field ) {
        if ( ! $this->required_field_check($field)) $this->errors->add( "{$field_id}_empty", __( wp_kses_post( "{$field['field_error']}"), $this->plugin ) );
        if (! $this->email_check($field)) $this->errors->add( 'not_an_email', __( wp_kses_post( "{$not_an_email}" ), $this->plugin ) );
      }

      if ( ! empty ($this->errors->errors) ) {

         // store the username/email in a session variable
        $_SESSION["{$this->prefix}_{$this->class_id}"] = array();

        foreach ($fields  as $field_id => $field) {
          if ($field_id != "pwd" ) $_SESSION["{$this->prefix}_{$this->class_id}"][$field_id] =  (isset($_POST[$field_id]))? $_POST[$field_id] : "";     
        }

        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        wp_redirect( home_url( $this->default_attributes()['class-page'] )) ;
        exit;

      }

    }  // END function wo_authenticate( $user, $username, $password ) {






    /**
    * Redirect the user after authentication if there were any errors.
    * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
    * @param string            $username   The user name used to log in.
    * @param string            $password   The password used to log in.
    * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
    */
    public function authenticate_user( $user, $password ) {

      // If request is not POST return
      if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) return;

      // Initialize error array
      $this->errors =  new \WP_Error;

      extract( $this->tab_settings($this->class_module()."_dialogs") );
      extract( $this->tab_settings($this->class_module()."_settings") );

      if (! wp_check_password( $password, $user->user_pass, $user->ID )) {
         
         $this->errors->add( 'incorrect_password', __( wp_kses_post( "{$incorrect_password}" ), $this->plugin ) );
       
       } else {

        // If a user is found then check if such user require activation (admins maybe excempt) and is activated
        if ( ! get_user_meta($user->ID, $this->prefix."_activation", true) ) { // Is user activated
          if( ! user_can($user, 'administrator') || (user_can($user, 'administrator') &&  isset($force_admin_activation) && $force_admin_activation && $user->ID !== 1)) 
            $this->errors->add( 'pending_activation', __( wp_kses_post( "{$pending_activation}" ), $this->plugin ) );
        }
      
      }

      // If errors redirect to register page
      if(!empty($this->errors->errors)) {
        
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());

         // store the username/email in a session variable
        $_SESSION[$this->prefix.'_'.$this->class_id] = array(
          'log' => (isset($_POST['log']))? $_POST['log'] : "",
        );

        wp_redirect( home_url( $this->default_attributes()['class-page'] )) ;
        exit;
      
      }
    
      return $user;

    }  // END function maybe_redirect_at_authenticate( $user, $username, $password ) {

	} // END class Userlogin {
		
} // END if (!class_exists('Userlogin')) 



