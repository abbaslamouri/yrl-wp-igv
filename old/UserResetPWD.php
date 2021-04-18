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

if (!class_exists('UserResetPWD')) {

	class UserResetPWD extends Member {

    public $name = "Password Reset";  // The title for the form used in this class
    public $class_id = "user_reset_pwd";  // The ID for the form in this class


		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {

      // Redirect reset password to custom page  (when user clicks link in the reset password email (GET)
      add_action( 'login_form_rp', array( $this, 'redirect_to_custom_reset_pwd' ) );
      add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_reset_pwd' ) );

      // Add password reset form shortcode handler
      add_shortcode( $this->shortcodes()['reset-pwd'], array( $this, 'shortcode' ) );

      // submit password reset form (POST)
      add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
      add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

		}  // END public function __construct()



     
    /**
    * Sets this class shortcode default attributes
    * @return array of default attributes
    */
    public function default_attributes () {

      return array(
        'submit-title'    => "Reset password",
        'show-title'      => true,
        'form-action'     => site_url( 'wp-login.php?action=resetpass'),
        'class-page'      => $this->fetch_page($this->class_module().'_pages', 'user-reset-pwd'),
        'continue-page'   => $this->fetch_page($this->class_module().'_pages', 'user-login'),
        'form-success'    => (isset($_GET['password_reset_succesful']) && $_GET['password_reset_succesful'])? true : false,
      );

    }



   


    // /**
    // * A shortcode for rendering the new user registration form.
    // *
    // * @param  array   $attributes  Shortcode attributes.
    // * @param  string  $content     The text content for shortcode. Not used.
    // *
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
    * Redirects to the custom password reset page, or the login page
    * if there are errors.
    * called when the user clicks on the reset password email
    */
    public function redirect_to_custom_reset_pwd() {

      if ( 'GET' != $_SERVER['REQUEST_METHOD'] )  return;

       // Instantiate error class
      $this->errors =  new \WP_Error;

    // Verify key / login combo
      $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );

    //If errors redirect to login page
      if ( is_wp_error( $user ) ) {
        $_SESSION[$this->plugin.'-errors'] = array_combine($user->get_error_codes(), $user->get_error_messages());
        wp_redirect( home_url( $this->fetch_page($this->class_module().'_pages', 'user-login') ) );
        exit;
      }

      //If no errors, redirect to password reset page
      //$page = $this->default_attributes()['class-page'];
      $url = home_url( $this->default_attributes()['class-page'] );
      $url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $url );
      $url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $url );
      wp_redirect($url);
      exit;


    }




    /**
    * Resets the user's password if the password reset form was submitted.
    */
    public function do_password_reset() {
 
      if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) return;

      $this->errors =  new \WP_Error;

      extract($this->tab_settings($this->class_module()."_dialogs"));
      extract($this->tab_settings($this->class_module()."_email"));

      $fields = $this->mandatory_reset_pwd_fields();

      // if no login or key then this form cannot be processed 
      if( ! isset($_POST['login']) || ! $_POST['login'] || ! isset($_POST['key']) || ! $_POST['key'] ) {
        $this->errors->add( 'catch_all_error_message', __( wp_kses_post ( "{$catch_all_error_message}" ), get_option($this->plugin)));
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        $this->log_message("Password reset login and/or key missing");
        wp_redirect(home_url( $this->fetch_page($this->class_module().'_pages', 'user-login') ) ) ;
        exit;
      }


       // Check nonce
      if ( ! $this->nonce_check($_POST[$this->class_id], $_POST[$this->class_id], "{$this->prefix}_{$this->class_id}")) 
        $this->errors->add( 'invalid_nonce', __( wp_kses_post( "{$invalid_nonce}" ), $this->plugin));

       // Check required fields and email validity
      foreach($fields as $field_id => $field ) {
        if ( ! $this->required_field_check($field)) $this->errors->add( "{$field_id}_empty", __( wp_kses_post( "{$field['field_error']}"), $this->plugin ) );
        if (! $this->email_check($field)) $this->errors->add( 'not_an_email', __( wp_kses_post( "{$not_an_email}" ), $this->plugin ) );
      }

      // Check if the passwords match
      if (! $this->do_passwords_match($_POST['pass1'], $_POST['pass2'])) $this->errors->add( 'passwords_dont_match', __( wp_kses_post( "{$passwords_dont_match}" ), $this->plugin ) );

      if ( ! empty ($this->errors->errors) ) {

        // store the username/email in a session variable
        $_SESSION["{$this->prefix}_{$this->class_id}"] = array();

        foreach ($fields  as $field_id => $field) {
          if ($field_id != "pwd" ) $_SESSION["{$this->prefix}_{$this->class_id}"][$field_id] =  (isset($_POST[$field_id]))? $_POST[$field_id] : "";     
        }

        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        
        wp_safe_redirect(
          add_query_arg(
            array(
              'login' => esc_attr( $_POST['login'] ),
              'key'   => esc_attr( $_POST['key'] )
            ),
            home_url($this->default_attributes()['class-page'])
          )
        );
        exit;
      }


      // if no errors so far retreive user 
      if( isset($_POST['login']) && ! empty ($_POST['login']) && isset($_POST['key']) && ! empty ($_POST['key']) )  
         $user = check_password_reset_key( $_POST['key'], $_POST['login'] );

      //If errors redirect to login page
      if ( is_wp_error( $user ) ) {
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        wp_redirect(home_url( $this->fetch_page($this->class_module().'_pages', 'user-login') ) ) ;
        exit;
      }
     
      // If no errors, reset password
      reset_password( $user, $_POST['pass1'] );

      $subject = $this->replace_bracketed_values( $this->bracketed_email_options ($user), $password_reset_subject);         // user notification email subject    
      $message = $this->replace_bracketed_values( $this->bracketed_email_options($user), $password_reset_body);          // user notification email body
      $signature = $this->replace_bracketed_values( $this->bracketed_email_options($user), $email_signature);        // email signature

      // send notificationcompose email
      wp_mail($user->user_email, "{$subject}", "{$message} {$signature}");

      // Redirect with a success messsage
      $success =  new \WP_Error ( 'password_reset_succesful', __( wp_kses_post ( "{$password_reset_succesful}" ), $this->plugin ) );
      $_SESSION[$this->plugin.'-success'] = array_combine($success->get_error_codes(), $success->get_error_messages());
      //$page = $this->fetch_page($this->class_module().'_pages', 'user-reset-pwd');

      wp_safe_redirect(
        add_query_arg(
          array(
            'password_reset_succesful' => true
          ),
           home_url($this->default_attributes()['class-page'])
        )
      );

      exit;

    }



  // /**
  //  * Returns the title for the password reset mail.
  //  * Called through the retrieve_password_title filter.
  //  * @param string  $title      Default mail title.
  //  * @param string  $user_login The username for the user.
  //  * @param WP_User $user_data  WP_User object.
  //  * @return string   The mail title to send.
  //  */
  //   public function lost_pwd_message_subject($subject, $user_login, $user) {

  //     $subject = (isset($this->class_email()['lostpassword_subject']))? $this->class_email()['lostpassword_subject'] : "Lost Password";

  //     return $this->replace_bracketed_values($user, $subject);

  //   }


  // /**
  //  * Returns the message body for the password reset mail.
  //  * Called through the retrieve_password_message filter.
  //  * @param string  $message    Default mail message.
  //  * @param string  $key        The activation key.
  //  * @param string  $user_login The username for the user.
  //  * @param WP_User $user_data  WP_User object.
  //  * @return string   The mail message to send.
  //  */
  //   public function lost_pwd_message_body( $msg, $key, $user_login, $user ) {

  //     $message['subject'] = "";
  //     $message['message'] = $msg;

  //     $output =  $this->compose_email ($this, $this->class_email(), $message, $user, 'lostpassword_subject', 'lostpassword_body')['message'];
  //     $output .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";

  //     // email signature
  //     $signature = (isset($this->class_email()['email_signature']))? $this->class_email()['email_signature'] : "";

  //     // Email Signature
  //     $signature = $this->replace_bracketed_values($user, $signature);
    
  //     return "{$output} {$signature}";

  //   }



	} // END class UserResetPWD {
		
} // END if (!class_exists('UserResetPWD')) 











