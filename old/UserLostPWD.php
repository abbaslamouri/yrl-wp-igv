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

if (!class_exists('UserLostPWD')) {

	class UserLostPWD extends Member {

    public $name = "Password Reset";  // The title for the form used in this class
    public $class_id = "user_lost_pwd";  // The ID for the form in this class

		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {

      // Set lost password URL
      add_filter( 'lostpassword_url', array($this, 'lost_password_url'), 10, 2 );

      // Redirect lost password request to custom page (member lost password) (triggers when the user clicks Forgot your password? link)
      add_action( 'login_form_lostpassword', array( $this, 'custom_lost_pwd' ) );

      // Add lost password and reset password forms shortcode handlers
      add_shortcode( $this->shortcodes()['lost-pwd'], array( $this, 'shortcode' ) );

      // finds the user with the lost password ( triggers when the user enters username/email to retreive password)
      add_action( 'login_form_lostpassword', array( $this, 'find_lost_pwd' ) );

      //Replace lost password message subject
      add_filter( 'retrieve_password_title', array($this, 'lost_pwd_message_subject'), 10, 3 );

      // Replace lost password message body
      add_filter( 'retrieve_password_message', array( $this, 'lost_pwd_message_body' ), 10, 4 );

		}  // END public function __construct()


    /**
    * Sets this class shortcode default attributes
    * @return array of default attributes
    */
    public function default_attributes () {

      return array(
        'submit-title'    => "Send me a reset link",
        'show-title'      => true,
        'form-action'     => site_url( 'wp-login.php?action=lostpassword' ),
        'class-page'      => $this->fetch_page($this->class_module().'_pages', 'user-lost-pwd'),
        'continue-page'   => $this->fetch_page($this->class_module().'_pages', 'user-login'),
        'form-success'    => (isset($_GET['lostpassword_email_sent']) && $_GET['lostpassword_email_sent'])? true : false,
      );

    }  // End public function form_fields () {






    // /**
    // * A shortcode for rendering the new user registration form.
    // *
    // * @param  array   $attributes  Shortcode attributes.
    // * @param  string  $content     The text content for shortcode. Not used.
    // *
    // * @return string  The shortcode output
    // */
    // public function shortcode( $attributes, $content = null ) {

    //  $this->atts = shortcode_atts(
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
    * Set lost password page
    */
    public function lost_password_url( $lostpassword_url, $redirect ) {

      $register_url = add_query_arg( 'redirect_to', esc_url($redirect), home_url($this->default_attributes()['class-page']) );
      return $register_url;
    
    }





    /**
    * Redirects the user to the custom "Forgot your password?" (GET request)
    * wp-login.php?action=lostpassword.
    */
    public function custom_lost_pwd() {

      if ( 'GET' != $_SERVER['REQUEST_METHOD'] ) return;

      wp_safe_redirect( home_url($this->default_attributes()['class-page']) );
      exit;          

    } // END  public function redirect_to_custom_lost_password() {





    /**
    * Redirects the user to the custom "Forgot your password? page " (POST request, finds user, semds email to reset password and redirect to login page with message to check email)
    * wp-login.php?action=lostpassword.
    */
    public function find_lost_pwd() {

      if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) return;

      $this->errors =  new \WP_Error;

      extract($this->tab_settings($this->class_module()."_dialogs"));
      extract($this->tab_settings($this->class_module()."_settings"));

      $fields = $this->mandatory_lost_pwd_fields();

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

      $user = get_user_by('email',$_POST['user_login'] );

      if ( ! $user ) {
        $this->errors->add( 'user_not_found', __( wp_kses_post( "{$user_not_found}" ), $this->plugin));

      } else {

        // Has the user been activated
        if ( isset($_POST['user_login']) ){
          
          if( get_user_meta($user->ID, $this->prefix."_activation", true) ) { // Is user activated
             // retreive_password() uses the name = user_login to find the user email (returns true if user_login is found otherwise WP error)
            // It also handles sending password retrieval email to user.
            if ( is_wp_error( retrieve_password()) ) $this->errors->add( 'user_not_found', __( wp_kses_post( "{$user_not_found}" ), $this->plugin));
          } else { 
            if( ! user_can($user, 'administrator') || (user_can($user, 'administrator') && isset($force_admin_activation) && $force_admin_activation && $user->ID !== 1)) {
              $this->errors->add( 'pending_activation', __( wp_kses_post( "{$pending_activation}" ), $this->plugin ) );
            } else {
              // retreive_password() uses the name = user_login to find the user email (returns true if user_login is found otherwise WP error)
              // It also handles sending password retrieval email to user.
              if ( is_wp_error( retrieve_password()) ) $this->errors->add( 'user_not_found', __( wp_kses_post( "{$user_not_found}" ), $this->plugin)); 
            }  
          }

        }

      }
      
      // If errors redirect to register page
      if(!empty($this->errors->errors)) {
        
         // store the username/email in a session variable
        $_SESSION["{$this->prefix}_{$this->class_id}"] = array();

        foreach ($fields  as $field_id => $field) {
          if ($field_id != "pwd" ) $_SESSION["{$this->prefix}_{$this->class_id}"][$field_id] =  (isset($_POST[$field_id]))? $_POST[$field_id] : "";     
        }

        // Store error in the session
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        wp_redirect( home_url( $this->default_attributes()['class-page'] )) ;
        exit;

      }

      $success =  new \WP_Error ( 'form-success', __( wp_kses_post ( $this->replace_bracketed_values( $this->bracketed_email_options ($user), $lostpassword_email_sent)), $this->plugin ) );
      $_SESSION[$this->plugin.'-success'] = array_combine($success->get_error_codes(), $success->get_error_messages());

      // Redirect to login page
       wp_safe_redirect(
        add_query_arg(
          array(
            'lostpassword_email_sent' => true
          ),
           home_url($this->default_attributes()['class-page'])
        )
      );

      exit;
      
    }





  /**
   * Returns the title for the password reset mail.
   * Called through the retrieve_password_title filter.
   * @param string  $title      Default mail title.
   * @param string  $user_login The username for the user.
   * @param WP_User $user_data  WP_User object.
   * @return string   The mail title to send.
   */
    public function lost_pwd_message_subject($subject, $user_login, $user) {

      extract($this->tab_settings($this->class_module()."_email"));

      return $this->replace_bracketed_values( $this->bracketed_email_options ($user), $lostpassword_subject);

    }





  /**
   * Returns the message body for the password reset mail.
   * Called through the retrieve_password_message filter.
   * @param string  $message    Default mail message.
   * @param string  $key        The activation key.
   * @param string  $user_login The username for the user.
   * @param WP_User $user_data  WP_User object.
   * @return string   The mail message to send.
   */
    public function lost_pwd_message_body( $messageg, $key, $user_login, $user ) {

      extract($this->tab_settings($this->class_module()."_email"));

      $signature = $this->replace_bracketed_values( $this->bracketed_email_options($user), $email_signature);        // email signature
      $message = $this->replace_bracketed_values( $this->bracketed_email_options($user), $lostpassword_body);          // user notification email body
      $message .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' );

      return "{$message} {$signature}";

    }

    
 
	} // END class UserLostPWD {
		
} // END if (!class_exists('UserLostPWD')) 











