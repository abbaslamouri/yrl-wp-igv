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

if (!class_exists('EmailSMTP')) {

	class EmailSMTP extends Dashboard {

    public $name = "SMTP Email";  // this module name

		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct() {

      // Add subadmin Menus
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

      // Initialize php mailer
      add_action( 'phpmailer_init', array($this, 'smtp_email'));

		}  // END public function __construct()



     /**
    * Class module name
    * @return string module name
    */
    public function class_module() {

      return $this->prefix."_smtp";

    }




   
   /**
    * Connect wp_mail to your authenticated
    * @param  object $phpmailer smtp mailer
    */
    public function smtp_email ( $phpmailer) {

      extract( $this->tab_settings($this->class_module()."_settings") );

      $phpmailer->isSMTP();
      $phpmailer->Host       = $smtp_host;
      $phpmailer->isHTML((isset($smtp_html) && $smtp_html)? true : false);
      $phpmailer->SMTPAuth   = (isset($smtp_auth) && $smtp_auth )? true : false;
      $phpmailer->Port       = $smtp_port;
      $phpmailer->Username   = $smtp_user;
      $phpmailer->Password   = $smtp_pass;
      $phpmailer->SMTPSecure = $smtp_secure;
      $phpmailer->From       = $smtp_from;
      $phpmailer->FromName   = $smtp_name;

    }  // END static function admin_submenus_register () {



 

    // /*
    // * Admin submenus
    // */
    // public function  admin_submenus (){

    //   $dashboard = new dashboard;

    //   // Set admin submenu pages
    //   return array(

    //       array(
    //       'parent_id'    => $dashboard->class_module(),    // The id name for the parent menu (or the file name of a standard WordPress admin page).  
    //       'page_title'   => __($dashboard->name, $this->plugin),    // Text to be displayed in the browser window.   
    //       'menu_title'   => __($this->name, $this->plugin),      // Text to be displayed for the menu   
    //       'caps'         => 'manage_options', // The capability required for this page to be displayed to the user.
    //       'id'         => $this->class_module(),    //Unique id for this menu  
    //     ),

    //   );

    // }  // END  public function  admin_submenus (){




    /*
    * Admin sections 
    */
    public function  admin_tabs (){

      return array(  

        $this->class_module()."_settings" => array(
          'title'      => __('SMTP Email Settings', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Settings', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

        $this->class_module()."_other"  => array( 
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Other', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

      );

    }  // END  public function  admin_tabs (){




       /**
    * Default settings
    * @return array of default settings
    */  
    public function defaults($tab) {

      switch ( $tab ) { 

        case $this->class_module().'_settings' :

          return array(

            'smtp_user'   => 'info@yrlus.com',
            'smtp_pass'   => 'adrar0714',
            'smtp_host'   => 'secure185.inmotionhosting.com',
            'smtp_html'   =>  true,
            'smtp_from'   => 'info@yrlus.com',
            'smtp_name'   => 'Sandbox',
            'smtp_port'   =>  '587',
            'smtp_secure' => 'tls',
            'smtp_auth'  => true,
            'smtp_debug' => 2,

          ); 

          default :
          
          return array();

          break;
      }

    } // END public function defaults($tab) {

      




    /*
    * Admin Fields
    */
    public function  admin_fields (){

      return array(

        'smtp_user' => array(
          'title'       => __('User', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'text',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_user'],
          'field_size'  => '40',
          'description' => "Username to use for SMTP authentication",
        ),

        'smtp_pass' => array(
          'title'       => __('Password', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'text',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_pass'],
          'field_size'  => '40',
          'description' => "Password to use for SMTP authentication",
        ),

        'smtp_host' => array(
          'title'       => __('Host', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'text',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_host'],
          'field_size'  => '40',
          'description' => "The hostname of the mail server",
        ),

        'smtp_html' => array(
          'title'       => __('HTML', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'default'     => $this->defaults($this->class_module()."_settings")['smtp_html'],
          'description' => "Check this box to send HTML email",
        ),

        'smtp_from' => array(
          'title'       => __('From Email', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'text',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_from'],
          'field_size'  => '40',
          'description' => "SMTP From email address",
        ),

        'smtp_name' => array(
          'title'       => __('From Name', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'text',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_name'],
          'field_size'  => '40',
          'description' => "SMTP From name",
        ),

        'smtp_port' => array(
          'title'       => __('Port', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'text',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_port'],
          'field_size'  => '40',
          'description' => "SMTP port number - likely to be 25, 465 or 587",
        ),

        'smtp_secure' => array(
          'title'       => __('Encryption System', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'radio',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_secure'],
          'field_options'     =>array('' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL'),
          'description' => "Encryption system to use - ssl or tls",
        ),

        'smtp_auth' => array(
          'title'       => __('Authentication', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_auth'],
          'description' => "Use SMTP authentication (true|false)",
        ),

        'smtp_debug' => array(
          'title'       => __('Debug', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'field_type'  => 'select',
          'default'       => $this->defaults($this->class_module()."_settings")['smtp_debug'],
          'field_options'     =>array('1' => '1', '2' => '2'),
          'description' => "for debugging purposes only set to 1 or 2",
        ),


      );

    } // END  private static function register_fields (){



	} // END class EmailSMTP {
		
} // END if (!class_exists('EmailSMTP')) 











