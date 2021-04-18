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

if (!class_exists('Security')) {

	class Security extends Member  {

    public $name = "Expired Password";  // The title for the form used in this class
    public $class_id = "security";  // The ID for the form in this class

		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {

      // Check if the user password has expired afetr login (wp_login fires after the user has succesfully loggeed in)
      add_action( 'wp_login', array( $this, 'check_expired_password' ), 10, 2 );

      // Process password reset
      add_action('init', array($this, 'process_password_reset'));

      // Expire password shortcode
      add_shortcode( $this->shortcodes()['security'], array( $this, 'shortcode' ) );

      //allows custom meta fields to be added, edited and displayed in backend
      add_action( 'user_new_form', array($this, 'display_user_profile_fields' ));
      add_action( 'show_user_profile', array($this, 'display_user_profile_fields'));
      add_action( 'edit_user_profile', array($this, 'display_user_profile_fields'));

      // Add user list column to show activation status
      add_filter('manage_users_columns', array($this, 'add_user_id_column'));
      add_action('manage_users_custom_column',  array($this, 'show_user_id_column_content'), 10, 3);   

		}  // END public function __construct()



    /**
    * Sets this class shortcode default attributes
    * @return array of default attributes
    */
    public function default_attributes () {

       // Parse shortcode attributes
      return array(
        'submit-title'    => "Reset password",
        'show-title'      => true,
        'form-action'     => null,
        'class-page'      => $this->fetch_page($this->class_module()."_pages", 'user-expired-pwd'),
        'continue-page'   => $this->fetch_page($this->class_module().'_pages', 'user-login'),
        'form-success'    => (isset($_GET['password_reset_succesful']) && $_GET['password_reset_succesful'])? true : false,
      );

    }  // End public function form_fields () {



   



    /**
    * A shortcode for rendering the form used to reset a user's password.
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

      return $this->shortcode_render($this->atts);

    } // RND  public function shortcode( $attributes, $content = null ) {



    /**
    * Redirects the user to the custom "Forgot your password?" (GET request)
    * wp-login.php?action=lostpassword.
    */
    public function check_expired_password($user_login, $user) {

      // Extract settings
      extract($this->tab_settings($this->class_module()."_settings"));
      extract($this->tab_settings($this->class_module()."_dialogs"));

      // If it is an administrator and forrce admin reset password is turned off return  (User with ID = 1 is excempt)
      if ( (user_can($user, 'administrator') && (! isset($force_admin_pwd_reset) || ! $force_admin_pwd_reset)) || $user->ID === 1) return;

      // Fetch security meta
      if (! $user || is_wp_error($user) || ! is_object($user)) {
        $this->errors->add( 'catch_all_error_message', __( wp_kses_post ( "{$catch_all_error_message}" ), get_option($this->plugin)));
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        $page = $this->admin->fetch_page($this->class_module().'_pages', 'user-login');
        wp_redirect(home_url( $page ) ) ;
        exit;
      } else {
        $user_meta = $this->fetch_security_meta($user->ID);
      }

      //If not continue to check if the password has expired
      $old_pwds = (!empty($user_meta) && isset($user_meta['old_pwds']))? $user_meta['old_pwds'] : array();
      $pwd_time = (!empty($user_meta) && isset($user_meta['pwd_time']))? $user_meta['pwd_time'] : time();

      // Check if the password has been saved previously saved in the old password meta data ($_POST['pwd'] comes from UserLogin class)
      // An existing password in the old password array is possible if the password was reset by say Woocommerce for example
      if (isset($_POST['pwd']) && !empty($_POST['pwd']) && in_array(wp_hash(trim($_POST['pwd'])), $old_pwds)) {

        $this->errors = new \WP_Error( 'reused_password_message', __( wp_kses_post( "{$reused_password_message}" ), $this->plugin ) );
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());

        //destroy session and redirect 
        $GLOBALS['current_user'] = $user; // Required to destroy sessions
        wp_destroy_all_sessions();

        wp_safe_redirect(
          add_query_arg(
            array(
              'action' => $this->prefix.'_security',
              'login'    => $user->user_login
            ),
             home_url($this->default_attributes()['class-page'])
          ),
          302
        );

        exit;

      }

      //calculate the pasword duration in days
      $duration = (time() - $pwd_time)/(3600*24);
      //$duration = time() - $pwd_time;

      //If password expired
      if ($duration > $pwd_reset_duration) {

        // Add the current  password to the old pasword array so it cannot be reused
        $old_pwds [] = wp_hash(trim($_POST['pwd']));
        update_user_meta($user->ID, $this->prefix."_security", array('pwd_time' => $pwd_time, 'old_pwds' => $old_pwds )); 

        $this->errors = new \WP_Error( 'expired_password_message', __( wp_kses_post( "{$expired_password_message}" ), $this->plugin ) );
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());

        //destroy session and redirect 
        $GLOBALS['current_user'] = $user; // Required to destroy sessions

        wp_destroy_all_sessions();

        wp_safe_redirect(
          add_query_arg(
            array(
              'action' => $this->prefix.'_security',
              'login'    => $user->user_login
            ),
             home_url($this->default_attributes()['class-page'])
          ),
          302
        );

        exit;
      }


    } // END  public function redirect_to_custom_lost_password() {



    /*
    * Processes expired password reset submission
    */
    public function process_password_reset() {

      //  Process expired password form submission
      if( ! isset($_POST['form-submit']) || ! isset($_POST['action']) || $_POST['action'] !== $this->prefix.'_security') return;

      // Instantiate error class
      $this->errors =  new \WP_Error;

      extract($this->tab_settings($this->class_module()."_dialogs"));
      //extract($this->tab_settings($this->class_module()."_settings"));

      // Retreive fields
      $fields = $this->mandatory_security_fields();

      // Find user
      $user = get_user_by('email', $_POST['login']);

      // Bail out if no user found
      if( ! $user ) {
        $this->errors->add( 'no_user_found', __(wp_kses_post( "{$no_user_found}" ), $this->plugin));
        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        $page = $this->admin->fetch_page($this->class_module().'_pages', 'user-login');
        wp_redirect(home_url( $page ) ) ;
        exit;
      }

      // Fetch suser ecurity meta data
      $user_meta = $this->fetch_security_meta($user->ID);
      $pwd_time = (! empty($user_meta) && isset($user_meta['pwd_time']))? $user_meta['pwd_time'] : time();
      $old_pwds = (! empty($user_meta) && isset($user_meta['old_pwds']))? $user_meta['old_pwds'] : array();

      // Check nonce
      if ( ! $this->nonce_check($_POST[$this->class_id], $_POST[$this->class_id], "{$this->prefix}_{$this->class_id}")) 
        $this->errors->add( 'invalid_nonce', __( wp_kses_post( "{$invalid_nonce}" ), $this->plugin));

       // Check required fields and email validity
      foreach($fields as $field_id => $field ) {
        if ( ! $this->required_field_check($field)) $this->errors->add( "{$field_id}_empty", __( wp_kses_post( "{$field['field_error']}"), $this->plugin ) );
        if (! $this->email_check($field)) $this->errors->add( 'not_an_email', __( wp_kses_post( "{$not_an_email}" ), $this->plugin ) );
      }

      if (! $this->password_check($user, $_POST['current-password']))
        $this->errors->add ( "current_password_error", __( wp_kses_post( "{$current_password_error}")." <a class='forgot-password' href = '".wp_lostpassword_url()."'>Did you forget your password? </a>", $this->plugin ));

      // Check if passwords match
      if (! $this->do_passwords_match($_POST['password-1'], $_POST['password-2']))  $this->errors->add( 'passwords_dont_match',  __( wp_kses_post( "$passwords_dont_match" ), $this->plugin ) ); 

      if (! $this->old_password_check($_POST['password-1'], $old_pwds))  
        $this->errors->add ( 'old_password_reused', __( wp_kses_post( "{$old_password_reused}" ), $this->plugin ) );

      if (! empty ($this->errors->errors)){

        $_SESSION[$this->plugin.'-errors'] = array_combine($this->errors->get_error_codes(), $this->errors->get_error_messages());
        wp_safe_redirect(
          add_query_arg(
            array(
              'action' => $this->prefix.'_security',
              'login'    => $user->user_login,
            ),
             home_url($this->default_attributes()['class-page'])
          )
        );
        exit;
      }

      // If no errors, reset password
      reset_password( $user, trim($_POST['password-1']) );
      
      // Update user security meta 
      if (! in_array(wp_hash(trim($_POST['current-password'])), $old_pwds)) $old_pwds [] = wp_hash(trim($_POST['current-password'])) ;    
      update_user_meta($user->ID, $this->prefix."_security", array('pwd_time' => time(), 'old_pwds' => $old_pwds ));
      $success=  new \WP_Error ( "password_reset_succesful", __( wp_kses_post( "{$password_reset_succesful}" ), $this->plugin ) );
      $_SESSION[$this->plugin.'-success'] = array_combine($success->get_error_codes(), $success->get_error_messages());

      wp_safe_redirect(
        add_query_arg(
          array(
            'password_reset_succesful' => true,
          ),
          home_url($this->default_attributes()['class-page'])
        )
      );
      exit;

    }  // END static function init () {



    /* 
    *  fetches security meta data (meta data is created if it does not exist)
    */
    public function fetch_security_meta ($user_id) {

      // If user meta is not set (first time after plugin installation) add user metya
      if ( empty(get_user_meta($user_id, $this->prefix."_security", true))) 
        add_user_meta($user_id, $this->prefix."_security", array('pwd_time' => time(), 'old_pwds' => array()) , true);

     // Fetch user meta 
      return get_user_meta($user_id, $this->prefix."_security", true);

    }  // END public function check_passsword_reset ($user, $new_pass)



  /**
  * Show custom user profile fields
  * 
  * @param  object $profileuser A WP_User object
  * @return void
  */
  public function display_user_profile_fields( $user ) {

    // Fetch security meta
    $user_meta = (is_object($user) && $this->fetch_security_meta($user->ID))?  $this->fetch_security_meta($user->ID) : array();
    $old_pwds = (isset($user_meta['old_pwds']))? $user_meta['old_pwds'] : array();
    $pwd_time = (isset($user_meta['pwd_time']))? $user_meta['pwd_time'] : time();


    ?>
    <h3><?php //esc_html_e( $this->name, $this->plugin  ); ?></h3>

    <table class="form-table">

      <tr>
        <th><label for="pwd_time"><?php esc_html_e( 'Password Elapsed Time', $this->plugin ); ?></label></th>
        <td>
          <input type="text" name="pwd_time" id="pwd_time" value="<?php  echo (time() - $pwd_time)/(3600*24); ?>" readonly />
          <br><span class="description"><?php esc_html_e( 'Time in days since password was reset.', $this->plugin  ); ?></span>
        </td>
      </tr>

       <tr>
        <th><label for="old_pwds"><?php esc_html_e( 'Old hashed passwords', $this->plugin ); ?></label></th>
        <td>
         <?php var_dump( $old_pwds ) ?>
        </td>
      </tr>

    </table>
    <?php
  }



  public function save_custom_profile_fields($user_id){

     // Fetch security meta
    $user_meta = ($this->fetch_security_meta($user_id))?  $this->fetch_security_meta($user_id) : array();
    $old_pwds = (isset($user_meta['old_pwds']))? $user_meta['old_pwds'] : array();
    $pwd_time = (isset($_POST['pwd_time']))? time() - $_POST['pwd_time']*(3600*24) : 0;

    # again do this only if you can
    if(!current_user_can('manage_options')) return false;

    update_user_meta($user->ID, $this->prefix."_security", array('pwd_time' => $pwd_time, 'old_pwds' => $old_pwds ));

  }



    // Add colum to the admin user list
    public function add_user_id_column($columns) {

      $columns['expired-password'] = 'Password Elapsed Time';
      return $columns;
    }




     // Show value associated with the column
    public function show_user_id_column_content($value, $column_name, $user_id) {

      if(!current_user_can('manage_options')) return false;

      if ($column_name =='expired-password') {

         // Fetch user meta
        $user = get_user_by('ID', $user_id);
        // Fetch security meta
        $user_meta = (is_object($user) && $this->fetch_security_meta($user_id))?  $this->fetch_security_meta($user_id) : array();
        $old_pwds = (isset($user_meta['old_pwds']))? $user_meta['old_pwds'] : array();
        $pwd_time = (isset($user_meta['pwd_time']))? $user_meta['pwd_time'] : time();
        $display_time = (time()- $pwd_time)/(3600*24);
        
        if ($display_time > 1) {
          return  round($display_time)." Days";
        } else {
          $display_time = $display_time*24;
          if($display_time > 1) {
            return round($display_time)." Hours";
          } else {
            $display_time = $display_time*60;
            return round($display_time). " Minutes";
          }
        }
      } else {
        return $value;
      }

    }



	} // END class Security {
		
} // END if (!class_exists('Security')) 











