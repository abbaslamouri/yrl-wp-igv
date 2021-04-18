<?php

/**
* AXL-WP-Ultimate
*
* @package AXL-WP-Ultimate
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace YRL_WP_IGV\Includes;


// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if (!class_exists('Admin')) {

	class Admin {
		
		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct($class) {

      $this->class = $class;

		}  // END public function __construct()


   


       /* 
    *processes input fields based on type
    */
    // public function input_field_render ($tab, $args) {

    //   //var_dump($args);

    //   $options = get_option($tab);
    //   var_dump($options);

    //   // Instantiate Dashboard class
    //   $dashboard = new Dashboard;
    //   $prefix = $dashboard->prefix;
    //   $this->action = $action;

    //   // Populate all possible fields
    //   foreach ($this->possible_fields() as $possible_field) {
    //     if (isset($args[$possible_field])) {
    //       $this->field[$possible_field] = $args[$possible_field];
    //     }  elseif ($possible_field =='option_none' ) {
    //        $this->field[$possible_field] = 'Select Option';
    //     } elseif ($possible_field =='required' || $possible_field == 'readonly'  || $possible_field == 'unique') {
    //        $this->field[$possible_field] = false;
    //     } elseif ($possible_field =='field_options') {
    //        $this->field[$possible_field] = array();
    //     } else {
    //       $this->field[$possible_field] = null;
    //     }
    //   }


    //   // settings options
    //   $this->options = ($options)? $options : array();
      
    //   // field value
    //   $this->field['value'] = (isset($this->options[$this->field['id']]) && !empty(trim($this->options[$this->field['id']])))? $this->sanitize_field_type($this->options[$this->field['id']], $this->field['field_type']) : $this->field['default'];

    //   // show template if it exists
    //   if(file_exists($dashboard->path."templates/".$this->field['field_type'].".php")) {

    //     echo $this->get_template_html( $this->class, $this->field['field_type']);

    //   } else {

    //     _e("<div class = 'admin-errors'> Template ".$this->class->path."templates/".$this->field['field_type'].".php does not exist</div>", $this->class->plugin);

    //   }

    // }



    // /*
    // * Sanitizes input fields
    // */
    // public function sanitize_field_type( $value, $type) {
          
    //   if (strpos($type, 'text') ){   
    //     return sanitize_text_field($value);
    //   } elseif (strpos($type, 'email')) {   // check if text email
    //     return sanitize_text_email($value);
    //   } elseif (strpos($type, 'textarea')) {   // check if text textarea
    //     return wp_kses(stripslashes_deep($value, wp_kses_allowed_html( 'post' )));
    //       //sanitize_textarea($value);
    //   } elseif(strpos($type, 'number')) {
    //     return intval($value);
    //   } else {
    //     return $value;
    //   }
    // }   



    // /**
    // * Renders the contents of the given template to a string and returns it.
    // *
    // * @param string $template The name of the template to render (without .php)
    // * @param array  $attributes    The PHP variables for the template
    // *
    // * @return string               The contents of the template.
    // */
    // public function get_template_html( $class, $template, $atts = array() ) {

    //   $dashboard = new Dashboard;
    //   $prefix = $dashboard->prefix;
    //   $path = $dashboard->path;

    //   ob_start();

    //   do_action( $dashboard->prefix.'_before_' . $template );

    //   require( $dashboard->path."templates/".$template.".php" );

    //   do_action( $dashboard->prefix.'_after_' . $template );

    //   $html = ob_get_contents();
    //   ob_end_clean();

    //   return  $html;

    // }  // END private function get_template_html( $template, $attributes = null ) {





      /**
      * Create required admin pages and add page id's to the  module section
      *
      * @param class $this->class        The name of the class for which the admin pages are created
      * @param string $section     The admin menu section for these pages
      * @param string $field_type  the type of field representing these pages (default is page_dropdown)
      *
      * @return string               The contents of the template.
      */
      public function create_required_admin_pages($tab, $field_type = 'page-dropdown') {

        // Initialize error array
        $errors =  new \WP_Error;
  
        $temp_errors = "";
        $temp_success = "";
        $options = array(); 

        // Loop through all the pages
        foreach ( $this->class->admin_fields() as $id => $page ) {

       
          // Skip if the page is not required
          if (!isset($page['required']) || !$page['required'] || $page['field_type'] != $field_type ) continue;
            
          // Check that the page doesn't exist already
          $query = new \WP_Query( 'pagename=' . $id );
          if (  ! $query->have_posts() ) {

            $args = array(
              'post_content'   => (isset($page['content']))? $page['content'] : '',
              'post_name'      => $id,
              'post_title'     => $page['title'],
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

              $temp_errors .= "Page {$page_id} was not created<br />";
             
            } else {

              // If no errors add page id to the options array
              $options [$id] = (isset($page_id) && $page_id)? $page_id : 0 ;
              update_option($tab, $options);

              $temp_success .= "{$page_id} was created succesfully";
            }
            

          } // END if ( ! $query->have_posts() ) {


        }  // END foreach ( $default_pages as $id => $page ) {

        // Store admin errors 
        if (!empty($errors->errors)) {
          $errors = new \WP_Error( 'page-creation-error', __($errors, $this->class->plugin));
          $_SESSION[$this->class->plugin.'-admin-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());
        } else {
          $messages = new \WP_Error( 'success', __("Member pages created succesfully", $this->class->plugin));
          $_SESSION[$this->class->prefix.'-admin-success'] = array_combine($messages->get_error_codes(), $messages->get_error_messages());
        }
    

      }  // END public static function create_required_admin_pages($this->class, $tab, $field_type) {





    /*
    * Checks if required pages are set
    */
    public function  check_required_pages ($class){
      
      // Instantiate error class
      $errors = new \WP_Error;

      // Loop through all the pages
      foreach ( $class->admin_fields() as $id => $page ) {
        
        // Skip if the page is not required
        if (isset($page['required']) && $page['required'] && $page['field_type'] == 'page-dropdown' ) {
          $found_page=  $class->admin->fetch_page($class->class_module()."_pages", $id);

          if (!$found_page) {
            $errors->add ( $id."-empty", __("Page ".$page['title']." is required", $class->plugin));

          } elseif (get_post(get_option($class->class_module()."_pages")[$id])->post_status ==='trash' ){
            $errors->add ( $id."-trashed", __("Page ".$page['title']." exists but it is in the trash", $class->plugin));
            
          }
        }

      }

      if (!empty($errors->errors)) {
       $_SESSION[$this->class->plugin.'-admin-errors'] = array_combine($errors->get_error_codes(), $errors->get_error_messages());
       return false;
      } else {
        return true;
      }
       
    } // END public static function  admin_menus (){ 





    /**
    * returns an array of option pages
    *
    * @param class $this->member        The name of the class for which the admin pages are created
    * @param string $section     The admin menu section for these pages
    * @param string $field_type  the type of field representing these pages (default is page_dropdown)
    * @param string $page_slug     The slug of the page to be retreived
    *
    * @return string               The contents of the template.
    */
    public function fetch_page( $tab, $page_slug) {

     
      // Retreive tab options
      $options = get_option($tab);


      if(isset($options[$page_slug]) && $options[$page_slug] && get_post($options[$page_slug])) {
        $page = get_post($options[$page_slug])->post_name;
      } else {
        $page = "";
      }

      return $page;


    }




    // /*
    // * Display admin notices
    // */
    // public function display_user_messages() {

    //   // Retreive user errors if any
    //   $errors = (isset($_SESSION[$this->class->plugin.'-errors']))? $_SESSION[$this->class->plugin.'-errors'] : array(); 

    //   // Retreive user errors sucess if any
    //   $success = (isset($_SESSION[$this->class->plugin.'-success']))? $_SESSION[$this->class->plugin.'-success'] : array(); ;


    //   if (!empty($errors)) {
    //     $messages = $errors;
    //     $css_class = "user-errors"; 
    //     unset($_SESSION[$this->class->plugin.'-errors']);  
    //   } elseif (!empty ( $success)) {
    //     $messages = $success;
    //     $css_class = "user-success";
    //     unset ($_SESSION[$this->class->plugin.'-success']);
    //   } else {
    //     $messages = array();
    //     $css_class = "";
    //   }

    //    $html = "";



    //   if(!empty($messages)) {
    //     foreach ($messages as $code => $message) {
    //       $output = ($this->fetch_error_from_code($code) !== "unknown")? $this->fetch_error_from_code($code): $message;
    //       $html .= "<div class = '".$css_class."' >".wpautop($output, true)."</div>";
    //     }
    //   } 

    //   return $html;

    // }




    /*
    * Display admin notices
    */
    public function display_admin_notices() {
      
      //  // Get current screen
      // $screen = get_current_screen();

      // // Return if not WP Ultimate plugin page 
      // if (! strpos($screen->id, $this->class->prefix)) return;

      // if(is_admin() && isset($_SESSION[$this->class->plugin.'-admin-errors']) && $_SESSION[$this->class->plugin.'-admin-errors'] ) {  // if error
      //   $options = $_SESSION[$this->class->plugin.'-admin-errors'];
      //   unset($_SESSION[$this->class->plugin.'-admin-errors']);
      //   $css_class = "notice notice-error is-dismissible";
      // } elseif (isset($_SESSION[$this->class->plugin.'-admin-success']) && $_SESSION[$this->class->plugin.'-admin-success']) {
      //   $options = $_SESSION[$this->class->plugin.'-admin-success'];
      //   unset($_SESSION[$this->class->plugin.'-admin-success']);
      //   $css_class = "notice notice-success is-dismissible";
      // } 

      // // return if neither option (error or success) is set
      // if (!isset($options) || empty($options)) return;

      // // Loop through all the errors/success
      // foreach ($options as $code => $message) {
        ?>
       
      <!--   <div class="<?php echo $css_class; ?>">
          <p><?php  echo "$message"; ?> </p>
        </div> -->
        
        <?php
      }
              
      
    }  // END public function display_admin_notices() {





      
    // /**
    // * Finds and returns a matching error message for the given error code.
    // *
    // * @param string $error_code The error code to look up.
    // *
    // * @return string An error message.
    // */
    // public function fetch_error_from_code( $error_code ) {

    //   extract($this->class->class_dialogs());
      
    //   switch ( $error_code ) {

    //     case 'incorrect_password':
    //       $err = __( "{$incorrect_password} <a href='%s'>Did you forget your password?</a>", $this->class->plugin );
    //       return sprintf( $err, wp_lostpassword_url() );

    //     case 'invalid_key':
    //       return __( 'The password reset key has expired.  Please try again', $this->class->plugin );

    //     default:
    //       return __( 'unknown', $this->class->plugin );

    //     break;
    //   }


    //  }  // END private function get_login_error_message( $error_code ) {



	} // END class Admin {
		
} // END if (!class_exists('Admin')) 



