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

if (!class_exists('CPT')) {

  class CPT extends Dashboard {

    public $name = "Custom Post Type";  // this module name
    //public $form_list = "post-type-list";  // The ID for the form in this class

    
   /**
    *Magic constructor.  Gets called when an object is instantiated
    */
    public function __construct() {

      //die("PPJJJHJKKJJKJKKJJK");

     
     $this->module = "cpt";


     // API Settings
      add_action( 'rest_api_init', array($this, "register_routes"));


    

       // Register styles and scripts
     // add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ) );
      //add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts' ) );

      // Add admin Submenus, section and fields
      //add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

      // Register custom post type
      //add_action( 'init', array($this, 'post_types_add' ) );

    }  // END public function __construct()




   
    /**
     * Class module name
     * @return string module name
     */
    public function class_module() {

      return $this->prefix."_cpt";

    }




     /**
    *Create quote post type
    */
    public function post_types_add() {

      // Bail if there are no custom post type options
      if(! get_option($this->class_module()."_add_cpt")) return;

      // Loop through all the custom post types
      foreach(get_option($this->class_module()."_add_cpt") as $option) { 
        if (! isset($option)  || ! isset($option['cpt_slug']) || ! $option['cpt_singular'] || ! $option['cpt_plural'])  return;
        $this->register_post_types( $option['cpt_slug'], $this->get_args( $option['cpt_plural'], $option['cpt_singular']));
      }

    }

  



     /**
    * Enqueue scripts
    */
    public function enqueue_scripts() {

      wp_register_script( "{$this->prefix}-cpt", $this->url."assets/js/cpt.js", array( "jquery" ), null, true );
      wp_enqueue_script( "{$this->prefix}-cpt");

    } // END public function enqueue_scripts()













     /**
    *Create quote post type
    */
    public function get_args($plural, $singular) {

      $supports = array();
      foreach (get_option($this->class_module()."_add_cpt") as $cpt_key => $cpt_value) {
        if (strpos($cpt_key, 'supports_') !== false) $supports[] = substr($cpt_key,strpos($cpt_key, 'supports_') + strlen('supports_'));
      }
          
      $labels = array(
        'name'                  => _x( $plural, $this->plugin ),  //general name for the post type,  (max. 20 characters, cannot contain capital letters or spaces)
        'singular_name'         => _x( $singular, $this->plugin ),  //name for one object of this post type. Default is Pos
        'add_new'               => _x( 'Add New', $this->plugin ), //the add new text.  Example _x('Add New', 'product');
        'add_new_item'          => _x( 'Add New ' .$singular, $this->plugin ),    //Default is Add New Post/Add New Page.
        'edit_item'             => _x( 'Edit ' .$singular, $this->plugin ), //Default is Edit Post/Edit Page.
        'new_item'              => _x( 'New ' .$singular, $this->plugin ),    //Default is New Post/New Page.
        'view_item'             => _x( 'View ' .$singular, $this->plugin ), //Default is View Post/View Page.
        'search_items'          => _x( 'Search ' .$plural, $this->plugin ), //Default is Search Posts/Search Pages.
        'not_found'             => _x( 'Not found', $this->plugin ),   //Default is No posts found/No pages found.
        'not_found_in_trash'    => _x( 'Not found in Trash', $this->plugin ),  //Default is No posts found in Trash/No pages found in Trash.
        'parent_item_colon'     => _x( 'Parent Item:', $this->plugin ),    // Notused on non-hierarchical types. In hierarchical ones the default is 'Parent Page:'.
        'all_items'             => _x( 'All '.$plural, $this->plugin ),  //String for the submenu. Default is All Posts/All Pages.
        'archives'              => _x( 'Item Archives', $this->plugin ), //String for use with archives in nav menus. Default is Post Archives/Page Archives.
        'insert_into_item'      => _x( 'Insert into Collection', $this->plugin ),  //String for the media frame button. Default is Insert into post/Insert into page.
        'uploaded_to_this_item' => _x( 'Uploaded to this '.$singular, $this->plugin ), //String for the media frame filter. Default is Uploaded to this post/page
        'module'             => _x( $plural, $this->plugin ), //Default is the same as `name`.
        'filter_items_list'     => _x( 'Filter '.$plural.' list', $this->plugin ), //String for the table views hslugden heading.
        'items_list_navigation' => _x( $plural.' list navigation', $this->plugin ),  //String for the table pagination hidden heading.
        'items_list'            => _x( $plural.' list', $this->plugin ), //String for the table hidden heading.
        'name_admin_bar'        => _x( $singular, $this->plugin ), //String for use in New in Admin menu bar. Default is the same as `singular_name`.
        'update_item'           => _x( 'Update '.$singular, $this->plugin ),
          
      );

      return array(
        'label'                 => _x( $plural, $this->plugin ), //A plural descriptive name for the post type marked for translation.
        'description'           => _x( $plural.$singular, $this->plugin ),
        'supports'              => (!empty($supports))? $supports : false, //array('title', 'editor', 'page_attributes'),
        'taxonomies'            => array( true ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'menu_icon'             => 'dashicons-images-alt2',
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => true,    
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
         'labels'                => $labels,
        // ),
      );


    }  // END public function post_types_add() {









  } // END class CPT {
    
} // END if (!class_exists('CPT')) 
