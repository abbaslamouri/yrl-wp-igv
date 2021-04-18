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

if (!class_exists('ContactForm')) {

	class ContactForm extends Dashboard {

    public $name = "Contact Form";  // this module name
    public $class_id = "contact_form";  // The ID for the form in this class
    public $form_nonce = "contact_form_nonce";  // The ID for the form in this class
    public $form_list = "contact-form-list";  // The ID for the form in this class
    public $contact_form_cpt = 'axl_wp_ult_contacts'; // Contact form custom post type

	
    /**
    * Magic Constructor
    */
		public function __construct() {

      // Save mandory fields if they do not exist
      if (! get_option($this->class_module()."_fields") || 
        empty (get_option($this->class_module()."_fields")) ) update_option($this->class_module()."_fields", $this->mandatory_fields());

      // Enqueue scripts
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ));
      add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts' ));

      // Add admin Submenus, section and fields
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

      // Admin init setup
      add_action( 'admin_init', array($this, 'admin_init') );
 
      // Add registration form shortcode handler
      add_shortcode( $this->plugin."-contact-form", array( $this, 'contact_form_shortcode' ) );

      // Add ajax
      add_action( "wp_ajax_". $this->prefix."_contact_form_process", array($this, 'contact_form_process' ));
      add_action( "wp_ajax_nopriv_". $this->prefix."_contact_form_process", array($this, 'contact_form_process' )); // need this to serve non logged in users
 
      // Display admin ntices
      add_action( 'admin_notices', array($this, 'add_admin_notices'));

      // Reguster contacts custom post type
      add_action( 'init', array($this, 'register_contacts_form_post_type') );

      // Add meta boxes to sender custom post type
      add_action( 'add_meta_boxes', array($this, 'add_contacts_meta_boxes'), 10, 2 );

      // add sender name and sender email to contacts custom post type list columns
      add_filter("manage_{$this->contact_form_cpt}_posts_columns", array($this, 'add_columns'));
      add_action('manage_posts_custom_column', array($this, 'add_columns_content'), 10, 2);

      // Add content before reset button
      //add_action ("{$this->prefix}_before_reset_settings_button", array($this, 'before_reset_settings_button'), 10, 2);


      // // Filter save settings button label
      // add_filter('save_settings_button_label', function ($button_label, $class_module, $active_tab) {
      //   if($active_tab == $this->class_module()."_fields" ) return (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Field' : "Add Field"; 
      // }, 10, 3);
      



		}  // END public function __construct()





    /**
     * Class module name
     * @return string module name
     */
    public function class_module() {

      return $this->prefix."_contact_form";

    } // END public function class_module()




    /**
     * Admin init setup
     */
    public function admin_init() {

      

    } // END public function admin_init() {




    /**
     * Adds all admin notices for this class
     */
    public function add_admin_notices() {

      //Return if not WP Ultimate plugin page 
      if (! strpos(get_current_screen()->id, $this->class_module())) return;

      // Initialize error/success objects
      $errors =  new \WP_Error;

      extract( $this->tab_settings($this->class_module()."_email") );

      // Email recipients notice
      if (! isset($email_recipients) || ! $email_recipients) {
         $errors->add( 'email_recipient_empty', __("Contact form is enabled but the email recipient is missing.  <a href='".admin_url('admin.php')."?page=".$this->class_module()."&tab=".$this->class_module()."_email'>Click here to add email recipient</a>", $this->plugin));
       }

       // Display admin notice
       echo $this->display_admin_notices($errors);

    } // END public function add_admin_notices() {




   /**
    * Register contact form custom post type
    */
    public function register_contacts_form_post_type() {

      extract( $this->tab_settings($this->class_module()."_settings") );

        $args = array(
          'label'                 => _x( (isset($cf_cpt_plural) && $cf_cpt_plural)? $cf_cpt_plural: "Contacts", $this->plugin ), //A plural descriptive name for the post type marked for translation.
          'public'                => true,
          'menu_icon'             => $this->dash_icons()[$cf_cpt_dashicon],
        );

        $this->register_post_types((isset($cf_cpt_slug) && $cf_cpt_slug)?  $cf_cpt_slug : $this->contact_form_cpt, $args);

    } // END public function register_contacts_form_post_type()



     /**
    * Sets this class shortcode default attributes
    * @return array of default attributes
    */
    public function default_attributes () {

      return array(
        'submit-title'    => "Submit",
        'show-title'      => true,
        'form-success'    => (isset($_GET['registration_succesfull']) && $_GET['registration_succesfull'])? true : false,
      );

    }  // End public function form_fields () {




  
   /**
    * Renders the contact form shortcode
    * @param  array $attributes shortcode attributes
    * @param  string $content    shortcode content
    * @return string html templte
    */
    public function contact_form_shortcode( $attributes, $content = null ) {

      // If request is not POST return
      if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST[$this->prefix.'_contact_form_submit'])) {

        // Check if any errors, send message
        if ( $this->check_errors()) $this->send_message();
      
      }

     
      $this->atts = shortcode_atts(
        $this->default_attributes(),  // default array values
        $attributes // array of values passed to the shortcode if any
      );

      // Retreive form values from the session variable in case of errors and delete the session valiable
      if(isset($_SESSION["{$this->prefix}_{$this->class_id}"]) && $_SESSION["{$this->prefix}_{$this->class_id}"] ) {
        $this->atts[$this->class_id] = $_SESSION["{$this->prefix}_{$this->class_id}"];
        unset($_SESSION["{$this->prefix}_{$this->class_id}"]);
      }
      
      // Render registration form
      return $this->get_template_html('contact-form', $this->atts);


    }  // END  public function contact_form_shortcode( $attributes, $content = null ) {




   
   /**
    * Handles Ajax form submit (uses init hook)
    */
    public function contact_form_process() {

      // If request is not POST return
      if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST[$this->prefix.'_contact_form_submit'])) return;

      // Set redirect page
      $page = $_SERVER['HTTP_REFERER'];

      // If no errors, send message
      if ( $this->check_errors() ) $this->send_message();

      // Display error message 
      $html = "<div class = 'user-notice' >".$this->display_user_messages()."</div>";

      echo ($html);
      wp_die();  


    }  // END public function contact_form_process() {




   
   /**
    * handles contact form sumission errors
    * @return [boolean] 
    */
    public function check_errors() {

      // Initialize error array
      $errors =  new \WP_Error;

      extract( $this->tab_settings($this->class_module()."_dialogs") );
      extract( $this->tab_settings($this->class_module()."_settings") );

      //Retreive contact form fields
      $fields = $this->tab_settings($this->class_module()."_fields");

      // Check in nonce is valid
      if( ! isset($_POST[$this->form_nonce]) || ! $_POST[$this->form_nonce] || !wp_verify_nonce($_POST[$this->form_nonce], "{$this->prefix}_{$this->form_nonce}" ) ) {
        $errors->add( 'invalid_nonce', __("{$invalid_nonce}", $this->plugin));
      }

       foreach ($fields as $field_id => $field) {
        if (! isset($_POST[$field_id] )|| empty($_POST[$field_id])) {
          if ( true == $field['field_required'])  $errors->add( $field_id.'_empty', __(  wp_kses_post ( "{$field['field_error']}" ), $this->plugin ) ); 
        } elseif ( 'email' == $field['field_type'] && ! is_email( sanitize_email($_POST['cf-email'])) ) {
          $errors->add( 'not_an_email', __(  wp_kses_post ( "{$field['field_label']} is {$not_an_email}" ), $this->plugin ) );
        }
      }


      // Check in email recients exists
      if( ! isset($email_recipients) || ! $email_recipients  ) {
        $errors->add( 'email_recipient_empty', __("{$email_recipient_empty}", $this->plugin));
      }

      // If errors store form fields in a session variable
      if( !empty($errors->errors ) ) {
        
        $_SESSION["{$this->prefix}_{$this->class_id}"] = array();
        foreach ($fields as $field_id => $field) {
          if (isset($_POST[$field_id]) ) $_SESSION["{$this->prefix}_{$this->class_id}"][$field_id] = $_POST[$field_id];
        }

        // Save error to session
        $_SESSION[$this->plugin.'-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());

        return false;

      } else {

        return true;

      }
        
       
    }  // END  public function check_errors() {




   
   /**
    * Handles contact form submission
    * @return boolean
    */
    public function send_message ()  {

       // Initialize error array
      $errors =  new \WP_Error;

      extract( $this->tab_settings($this->class_module()."_settings") );
      extract( $this->tab_settings($this->class_module()."_dialogs") );

      // Add email header
      add_action( 'phpmailer_init', function ($phpmailer) {
        $phpmailer->AddReplyTo($_POST['cf-email'], $_POST['cf-name']);
      });


      //send email
      if (wp_mail ($email_recipients, $this->replace_bracketed_values($this->bracketed_email_options(), $email_subject), $this->replace_bracketed_values($this->bracketed_email_options(), $email_message))) {  // submission successful

        //create contact form custom post type
        $args = array(
          'post_content'   => $_POST['cf-message'],
          'post_title'     => date("Y-m-d_H-i-s")."_".$_POST['cf-name'],
          'post_status'    => 'publish',
          'post_type'      => $this->contact_form_cpt,
          'ping_status'    => 'closed',
          'comment_status' => 'closed',
        );
      
        $post_id =  wp_insert_post(
          $args,  // post arguments
          true   // whether to return WP Error on failure
        );

        if (is_wp_error($post_id)) {  // If WP Error log error
          $this->log_message($post_id);
        } else { // otherwise add post meta
       
          foreach($_POST as $key => $value) {        
            //if (! in_array($key, array_keys($this->mandatory_fields())) && isset($this->tab_settings($this->class_module()."_fields")[$key]) ) {
              update_post_meta($post_id, $key, $value, true );
            //}       
          }

        }

        $success =  new \WP_Error ( 'submission-succesfull', __(  wp_kses_post ( "{$submission_succesfull}" ), $this->plugin ));
        $_SESSION[$this->plugin.'-success'] = array_combine($success->get_error_codes(), $success->get_error_messages());
        return true;
        //$url = add_query_arg( array('submission-succesfull' => true),  $page );

      } else { //submission failed

        $errors =  new \WP_Error ( 'submission-failed', __(  wp_kses_post ( "{$submission_failed}" ), $this->plugin ));
        $_SESSION[$this->plugin.'-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());
        return false;

      }

    }  // END public function send_message ()  {





   /**
    * Adds columns to the contact form custom post type list
    * @param array $columns array of columns
    */
    function add_columns($columns) {

      $columns['sender_name'] = 'Sender Name';
      $columns['sender_email'] = 'Sender Email';
      $columns['email_subject'] = 'Email Subject';
      return $columns;

    } // END function add_columns($columns)





   /**
    * Adds column contents to contact form custom post type
    * @param strin $column_name column name
    */
    function add_columns_content($column_name, $post_id) {
      
      if ($column_name == 'sender_name'){
          echo get_post_meta($post_id, 'cf-name', true );
      } elseif ($column_name == 'sender_email'){
          echo get_post_meta($post_id, 'cf-email', true );
      }  else {
          echo get_post_meta($post_id, 'cf-subject', true );
      }
    } // END function add_columns_content($column_name, $post_id) {



 
   
   /**
    * Adds contacts custom post type meta boxes 
    * @param string $post_type Custom Post Type
    * @param Object $post      Post
    */
    public function add_contacts_meta_boxes( $post_type, $post ) {
      
      // Add meta box
      add_meta_box( 
        $this->class_module().'_meta_boxes',
        __( 'Message Info', $this->plugin ),
        function ($mb, $params) {
          $post = $params['args'];
          foreach($this->tab_settings($this->class_module()."_fields") as $key => $field) {
            if ($key != 'cf-message') echo '<p><strong>'.$field['field_label'].'</strong>: '.get_post_meta($post->ID, $key, true ).'</p>';
          }
        },
        'axl_wp_ult_contacts',
        'side',
        'high',
        $post
      );

    }  // END public function add_contacts_meta_boxes( $post_type, $post ) {








    /**
    * Returns an array of all possible email options for the settings api
    * @return array of possible options
    */
    public function bracketed_email_options () {

      return array(

        '[blog-name]' => get_bloginfo('name'),
        '[sender-name]'   =>  (isset($_POST['cf-name']) && !empty($_POST['cf-name']))? $_POST['cf-name'] : '',
        '[sender-email]'  =>  (isset($_POST['cf-email']) && !empty($_POST['cf-email']))? $_POST['cf-email'] : '',
        '[email-subject]' =>  (isset($_POST['cf-subject']) && !empty($_POST['cf-subject']))? $_POST['cf-subject']: '',
        '[message-body]'  =>  (isset($_POST['cf-message']) && !empty($_POST['cf-message']))? $_POST['cf-message'] : '',
     
      );

    } // END  public function bracketed_email_options () {






    /*
    * Fetch nav menus input fields
    */
    private function dash_icons( ) {

      return array (
        '\f100'  => 'dashicons-admin-appearance',
        '\f105'  => 'dashicons-admin-page',
        '\161'   => 'dashicons-format-gallery',
        '\233'   => 'dashicons-images-alt2'
      );

    }  // END static function sanitize( $input ) {





   // /**
   //  * Handles Ajax form submit (uses init hook)
   //  */
   //  public function sort_table() {

   //     //print_r(($_POST));
   //           //wp_die();  



   //    $old_fields = $this->tab_settings($this->class_module()."_fields");
   //    $new_order = $_POST['rows'];
   //    $new_fields= array();

   //    $new_keys = array();
   //    foreach($new_order as $key => $value) {
   //        $new_keys[$key] = array_keys($old_fields)[$value-1];
   //    }

   //    foreach($new_keys as $key => $value) {
   //       $new_fields[$value] = $old_fields[$value];
   //    }

   //    update_option($this->class_module()."_fields", $new_fields);

   //    // print_r(array_values($new_fields));

   //    //wp_die();  

   //    // If request is not POST return
   //    // if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset($_POST[$this->prefix.'_contact_form_submit'])) return;

   //    // // Set redirect page
   //    // $page = $_SERVER['HTTP_REFERER'];

   //    // // If no errors, send message
   //    // if ( $this->check_errors() ) $this->send_message();

   //    // // Display error message 
   //    // $html = "<div class = 'user-notice' >".$this->display_user_messages()."</div>";

   //    // echo ($html);
   //    // wp_die();  


   //  }  // END public function contact_form_process() {









    /**
    * Enqueue scripts
    */
    public function enqueue_scripts() {

      // Register and Enqueue Javascript 
      wp_register_script( $this->prefix."_contact_form", $this->url."assets/js/contact-form.js", array( "jquery" ), null, true );
      //wp_enqueue_script( $this->prefix."_contact_form");

      // Localization.
      wp_localize_script( 

        $this->prefix."_contact_form",  //handle for the script
        $this->prefix."_contact_form_object",     //  The name of the variable which will contain the data (used in the ajax url)
        array(  // Data to be passed 
          'ajax_url'      => admin_url( 'admin-ajax.php' ),
          'ajax_action'   => $this->prefix."_contact_form_process",
        )

      );  

    } // END public function enqueue_scripts()





    /**
    * Enqueue scripts
    */
    public function admin_enqueue_scripts() {

      // Register and Enqueue Javascript 
      wp_register_script( $this->prefix."_admin_contact_form", $this->url."assets/js/admin-contact-form.js", array( "jquery", "jquery-ui-sortable" ), null, true );
      wp_enqueue_script( $this->prefix."_admin_contact_form");

      // // Localization.
      // wp_localize_script( 

      //   $this->prefix."_admin_contact_form",  //handle for the script
      //   $this->prefix."_admin_contact_form_object",     //  The name of the variable which will contain the data (used in the ajax url)
      //   array(  // Data to be passed 
      //     'ajax_url'      => admin_url( 'admin-ajax.php' ),
      //     'ajax_action'   => $this->prefix."_sort_table",
      //   )

      // );  

    } // END public function enqueue_scripts()






   // /**
   //  * Sanitizes settings fields
   //  * @param  array $input settings fields
   //  * @return array        settings fields
   //  */
   //  public function sanitize( $input ) {

   //    // Intialize error
   //    $errors =  new \WP_Error;

   //    //Deelete option if post reset is set
   //    if(isset($_POST['reset_settings']) && isset($_POST['active_tab'])) {
   //      delete_option($_POST['active_tab']);
   //      return;
   //    }

   //    // Retreive add_feild settings options
   //    $output = (get_option($this->class_module()."_fields"))? get_option($this->class_module()."_fields") : array();


   //    // Deelete setting if post delete is set
   //    if(isset($_POST["{$this->plugin}-delete-record"])) {
   //      unset($output[$_POST["record_id"]]);
   //      return $output;
   //    }


   //    // Add required fields error
   //    foreach ($this->admin_fields() as $key => $field) {
   //      if (in_array($key, array_keys($input))){
   //        if (isset($field['required']) && $field['required'] &&  ! $input[$key])
   //          $errors->add( "{$key}_empty", __("{$field['title']} is required", $this->plugin));
   //      }
   //    }

   //     // Check if an existing cpt is being addaed
   //    if ( (! isset($_POST["{$this->plugin}-update-record"]) || ! $_POST["{$this->plugin}-update-record"]) && isset($input['field_id']) && in_array($input['field_id'], array_keys($output))) 
   //     $errors->add( "{$input['field_id']}_exists", __("A CPT with this id ({$input['field_id']}) exists.  Please edit this field to modify it", $this->plugin));

   //    if (!empty($errors->errors)) {
   //      foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
   //        add_settings_error( $this->class_module(), $code, $message );
   //      }
     
   //    } else {
  
      
   //      // Save settings depending on whether it is an array or array or arrays as in the case of add_field tab
   //      if (in_array('field_id', array_keys($input))) {   
   //        $output[$input['field_id']] = $input;
   //      } else {
   //        $output = $input;
   //      }
   //    }
   
   //    return $output;

   //  }  // END static function sanitize( $input ) {


      
      

    // /*
    // * Admin submenus
    // */
    // public  function  admin_submenus (){

    //   // Set admin submenu pages
    //   return array(

    //     array(
    //       'parent_id'    => parent::class_module(),    // The id name for the parent menu (or the file name of a standard WordPress admin page).  
    //       'page_title'   => __($this->title, $this->plugin),    // Text to be displayed in the browser window.   
    //       'menu_title'   => __('Contact Form', $this->plugin),      // Text to be displayed for the menu   
    //       'caps'         => 'administrator', // The capability required for this page to be displayed to the user.
    //       'id'           => $this->class_module(),    //Unique id for this menu  
    //     ),

    //   );

    // }  // END  public  function  admin_submenus (){







    /*
    * Admin tabss 
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

        $this->class_module()."_email"  => array(
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Email', $this->plugin),
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

        $this->class_module()."_fields"  => array( 
          'title'      => __('Contact Form Fields', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Contact Form Fields', $this->plugin),
           'sections'   => array( 
            'general' => array('title'    => 'Add New Form Fields'),
          )
        ),

        // $this->class_module()."_list"  => array( 
        //   'title'      => __('Form Fields', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
        //   'tab_title'  => __('Form Fields', $this->plugin),
        //   'sections'   => array( 
        //     'general' => array('title'    => 'Field List'),
        //   )
        // ),

        // $this->class_module()."_other"  => array(          
        //   'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
        //   'tab_title'  => __('Other', $this->plugin),
        //   'sections'   => array( 
        //     'general' => array('title'    => 'Other Settings'),
        //   )
        // ), 


      );

    }  // END public function  admin_tabs (){








   /**
    * Sets mandatory fields for this mosule
    * @return array Mandatory fields
    */
    public function mandatory_fields() {

      return array(

        'cf-name'            =>  array(
          'field_id'         => 'cf-name',
          'field_label'      => 'Name',
          'field_required'   => true,
          'field_type'       => 'text',
          'field_description'   =>'',
          'field_error'      =>'Name is required.',
          //'field_position'   => '1'
        ),

        'cf-subject'            =>  array(
          'field_id'         => 'cf-subject',
          'field_label'      => 'Subject',
          'field_required'   => true,
          'field_type'       => 'text',
          'field_description'   =>'',
          'field_error'      =>'Please enter your subject.',
          // 'field_position'   => '2'
        ),

        'cf-email'            =>  array(
          'field_id'         => 'cf-email',
          'field_label'      => 'Email',
          'field_required'   => true,
          'field_type'       => 'email',
          'field_description'   =>'',
          'field_error'      =>'You must provide a valid email address.',
          // 'field_position'   => '3'
        ),

        'cf-message'            =>  array(
          'field_id'         => 'cf-message',
          'field_label'      => 'Message',
          'field_required'   => true,
          'field_type'       => 'textarea',
          'field_description'   =>'',
          'field_error'      => 'You forgot to enter a message.',
          // 'field_position'   => '4'
        ),

        'cf-gdpr'     =>  array(
          'field_id'         => 'cf-gdpr',
          'field_label'      => 'GDPR Agreement',
          'field_required'   => true,
          'field_type'       => 'checkbox',
          'field_description'   => 'I consent to having this website store my submitted information so they can respond to my inquiry',
          'field_error'      =>'You must agree to the GDPR agreement.',
          // 'field_position'   => '5'
        )


      );

     
    } // END public function mandatory_fields()






   
   /**
    * Default settings
    * @param  string $tab tab name
    * @return array      default settings
    */
    public function defaults($tab) {

      switch ( $tab ) { 

        case $this->class_module().'_settings' :

          return array(

            'cf_cpt_plural'        => 'Contacts',
            'cf_cpt_singular'      => 'Contact',  
            'cf_cpt_slug'          => 'contacts',
            'cf_cpt_dashicon'      => '\233'

          );

        case $this->class_module().'_email' :

          return array(

          'email_recipients'               => "",
          'email_subject'                  => get_bloginfo('name').' [email-subject]',
          'email_headers'                  => 'Reply-To: [sender-email]',
          'email_message'                  => <<<ENDHTML
From: [sender-name] <[sender-email]>
Subject: [email-subject]

Message Body:
[message-body]

-- 
This e-mail was sent from a contact form on Sandbox (http://sandbox.yrlus.com)
ENDHTML

          );


          case $this->class_module().'_dialogs' :

          return array(

            'invalid_nonce'         => 'An invalid nonce nonce was submitted.',
            'email_recipient_empty' => 'There is no email recipient.  Please contact the website admin',  
            'not_an_email'          => 'Invalid email',
            'submission_succesfull' => 'Thank you for your message. It has been sent.',
            'submission_failed'     => 'There was an error trying to send your message. Please try again later.' ,

          );


          case $this->class_module().'_fields' :

          return array(

            'field_label'         => '',   
            'field_id'            => '',
            'field_type'          => '',
            //'field_position'      => 10,
            'field_error'         => '',
            'field_required'      => false,
            'field_description'   => '',
            'field_options'       => ''


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

      //var_dump($this->tab_settings($this->class_module()."_settings"));die;

      return array(

        'cf_cpt_plural' => array(
          'title'       => __('CPT Plural Name', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'default'     => $this->defaults($this->class_module()."_settings")['cf_cpt_plural'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => "50",
          'placeholder' => 'eg. Contacts',
          'description' => "Plural name of Contact Form CPT",
        ),

        'cf_cpt_singular' => array(
          'title'       => __('CPT Singular Name', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'default'     => $this->defaults($this->class_module()."_settings")['cf_cpt_singular'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => "50",
          'placeholder' => 'eg. Contact',
          'description' => "Singular name of Contact Form CPT",
        ),

        'cf_cpt_slug' => array(
          'title'       => __('CPT Slug', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // Section to which this field belongs
          'default'     => $this->defaults($this->class_module()."_settings")['cf_cpt_slug'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => "50",
          'placeholder' => 'eg. contacts',
          'description' => "Slug of Contact Form CPT",
        ),

        'cf_cpt_dashicon' => array(
          'title'       => __('Dashicon', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // tab to which this field belongs
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['cf_cpt_dashicon'],
          'field_type'    => 'radio',
          'field_options'  => $this->dash_icons(),
          'description' => "Select a dashicon",
        ),

        'field_label' => array(
          'title'       => __('Field Label', $this->plugin),
          'tab'     => $this->class_module()."_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_fields")['field_label'],
          'section'     => 'general',
          'css_class'   =>'parent-field',
          'field_type'  => 'text',
          'field_size'  => "50",
          'description' => "Field label",
        ),

         'field_id' => array(
          'title'       => __('Field ID', $this->plugin),
          'tab'     => $this->class_module()."_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_fields")['field_id'],
          'section'     => 'general',
          'css_class'   => 'child-field',
          'field_type'  => 'text',
          'required'    => true,
          'field_size'  => "50",
          'unique'     => true,
          'description' => "Field id attribute (also used as the name of the field)",
        ),

        'field_type' => array(
          'title'       => __('Field Type', $this->plugin),
          'tab'     => $this->class_module()."_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_fields")['field_type'],
          'section'     => 'general',
          'css_class'   => 'select-radio-type',
          'field_type'  => 'select',
          'field_options' => array('text' => 'Text', 'checkbox' => 'Checkbox', 'email' => 'Email', 'select' => 'Select', 'radio' => ' Radio', 'textarea' => 'Textarea'),
          'required'    => true,
          'field_size'  => "50",
          'description' => "Choose a field type",
        ),

        'field_options' => array(
          'title'       => __('Field Options', $this->plugin),
          'tab'     => $this->class_module()."_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_fields")['field_options'],
          'section'     => 'general',
          'css_class'   => 'field-options',
          'field_type'  => 'text',
          'field_size'  => "50",
          'description' => "Options for select and radio button fields",
        ),


        'field_required' => array(
          'title'       => __('Field Required', $this->plugin),
          'tab'     => $this->class_module()."_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_fields")['field_required'],
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'description' => "Check this box if you want this field to be required",
        ),

        // 'field_position' => array(
        //   'title'       => __('Field Position', $this->plugin),
        //   'tab'         => $this->class_module()."_fields",  // Section to which this field belongs
        //   'default'       => $this->defaults($this->class_module()."_fields")['field_position'],
        //   'section'     => 'general',
        //   'field_type'  => 'number',
        //   'field_min'   => 1,
        //   'field_max'   => 100,
        //   'description' => 'Position of the field in the contact form (100 fields max)',
        // ),

         'field_description' => array(
          'title'       => __('Field Description', $this->plugin),
          'tab'     => $this->class_module()."_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_fields")['field_description'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'textarea_rows'  => 4,
          'textarea_cols'  => 100,
          'description' => "Description for this field",
        ),

        'field_error' => array(
          'title'       => __('Field Error', $this->plugin),
          'tab'     => $this->class_module()."_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_fields")['field_error'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'textarea_rows'  => 4,
          'textarea_cols'  => 100,
          'description' => "Error to show if this field is required and is empty",
        ),


        'email_recipients' => array(
          'title'       => __('Email recipients', $this->plugin),
          'tab'     => $this->class_module()."_email",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_email")['email_recipients'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => "100",
          'description' => "Enter comma separated email recipients",
        ),

        'email_subject' => array(
          'title'       => __('Subject', $this->plugin),
          'tab'     => $this->class_module()."_email",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_email")['email_subject'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => "100",
          'description' => "Enter the from name here",
        ),

        'email_headers' => array(
          'title'       => __('Additional hearders', $this->plugin),
          'tab'     => $this->class_module()."_email",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_email")['email_headers'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => "100",
          'description' => "Enter coma seperated additional headers (key values seperated by :)",
        ),

        'email_message' => array(
          'title'       => __('Message', $this->plugin),
          'tab'     => $this->class_module()."_email",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_email")['email_message'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'textarea_rows' => 10,
          'description' => "",
        ),
      
        'invalid_nonce'    => array(  
          'title'       => __('Invalid nonce', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'       =>  $this->defaults($this->class_module()."_dialogs")['invalid_nonce'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'textarea_cols' => 100,
          'description' => '' ,
        ),

        'email_recipient_empty'    => array(  
          'title'       => __('Email recipient empty', $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'       =>  $this->defaults($this->class_module()."_dialogs")['email_recipient_empty'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'textarea_cols' => 100,
          'description' => '' ,
        ),

        'not_an_email'    => array(  
          'title'       => __("Invalid email", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'       => $this->defaults($this->class_module()."_dialogs")['not_an_email'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'submission_succesfull'    => array(  
          'title'       => __("Submission Succesfull", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'       => $this->defaults($this->class_module()."_dialogs")['submission_succesfull'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

        'submission_failed'    => array(  
          'title'       => __("Submission Failed", $this->plugin),
          'tab' => $this->class_module()."_dialogs",
          'default'       => $this->defaults($this->class_module()."_dialogs")['submission_failed'],
          'section'     => 'general',
          'field_type'  => 'textarea',
          'description' => '' ,
        ),

      );

    } // END  public function  admin_fields (){

  


	} // END class ContactForm {
		
} // END if (!class_exists('ContactForm')) 



