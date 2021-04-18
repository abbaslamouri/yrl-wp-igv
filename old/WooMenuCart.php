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

if (!class_exists('WooMenuCart')) {

  class WooMenuCart extends Dashboard {

    public $name = "Woocommerce Menu Cart";  // this module name

    /**
    *Magic constructor.  Gets called when an object is instantiated
    */
    public function __construct() {


      // Enqueue scripts
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ));
      add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts' ));
      
      // Add admin Submenus, section and fields
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

      // Display admin ntices
      add_action( 'admin_notices', array($this, 'add_admin_notices'));


      // Initial menu cart
      add_action('init', array($this, 'init'));


    }  // END public function __construct()





   /**
   * Class module name
   * @return string module name
   */
    public function class_module() {

      return $this->prefix."_menu_cart";

    } // END public function class_module()




    /**
    * Register and Enque stylesheet.  Hooks into WP's wp_enqueue_scripts
    **/
    public function enqueue_scripts() {

              //wp_enqueue_style( 'wp-color-picker' ); 

      

      //Enqueue Javascript 
      wp_register_script( $this->plugin."-menu-cart", $this->url."assets/js/menu-cart.js", array( "jquery" ), null, true );
      wp_enqueue_script( $this->plugin."-menu-cart");

      //Menu cart localization.  Though localization is the primary use, it can be used to make data available to your script that you can normally only get from the server side
      wp_localize_script( 
        $this->plugin."-menu-cart",  //handle for the script
        $this->prefix."_menu_cart_object",    //  The name of the variable which will contain the data (used in the ajax url)
        array(
          'ajax_url'     => admin_url( 'admin-ajax.php' ),
          'ajax_action' => $this->prefix."_menu_cart_update",
          'ajax_nonce'    => wp_create_nonce("_".$this->prefix."_menu_cart_update"),
        )
      );  //The data itself


    }  // END public function styles()




    /**
     * Adds all admin notices for this class
     */
    public function add_admin_notices() {

      //Return if not WP Ultimate plugin page 
      if (! strpos(get_current_screen()->id, $this->class_module())) return;

      // Initialize error/success objects
      $errors =  new \WP_Error;

      //Check if WooCommerce is active enable plugin functionality
      if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        // Display admin errors if any
        $errors->add( 'woocommerce_required', __("Menu Cart requires <a href ='https://wordpress.org/plugins/woocommerce/'>woocommerce</a> to be installed and activated", $this->plugin));
      }

      // Display admin notice
      echo $this->display_admin_notices($errors);

    } // END public function add_admin_notices() {







    /**
    * Initialize menu cart
    * @return array boolean woocommerce active
    */
    public function init() {

      // Extract settings
      extract( $this->tab_settings($this->class_module()."_settings") );

      //Check if WooCommerce is active enable plugin functionality
      if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        // Add ajax action
        add_action( "wp_ajax_".$this->prefix."_menu_cart_update", array($this, 'menu_cart_update' ));
        add_action( "wp_ajax_nopriv_".$this->prefix."_menu_cart_update", array($this, 'menu_cart_update' )); // need this to serve non logged in users

        //Add cart icon, item count and total to cart
        add_filter( 'wp_nav_menu_'.$nav_menu_slug.'_items', array($this, 'add_cart_to_nav_menu'), 10, 2 );
      }

    }





    /**
    * Update menu cart
    * @return string $html menu cart output
    */
    public function menu_cart_update( ) {

      global $woocommerce;

      // Extract seeings 
      extract( $this->tab_settings($this->class_module()."_settings") );

      $cart_url = wc_get_cart_url();  // woocommerce cart url
      $shop_page_url = wc_get_page_permalink( 'shop' );  //. woocommerce shop url
      $cart_contents_count = $woocommerce->cart->get_cart_contents_count();  // Number of items in the acrt
      $cart_total  = $woocommerce->cart->get_cart_total();  // Cart total
      $item_text = ($cart_contents_count > 1)? 'ITEMS' : 'ITEM'; // display text
      $cart_icon = $this->get_cart_icons()[$cart_icon];  //cart icon

      if ( (! $cart_contents_count || ! $cart_total) && ( isset($hide_empty_cart) && $hide_empty_cart ) ) return "";

      // construct output
      $html =  ($cart_contents_count && $cart_total)?  "$cart_contents_count $item_text - $cart_total" : "CART";

      $html = "<a class='elementor-item' style='color:".$menu_item_color.";' href = '$cart_url'><span class='".$this->class_module()." axlwpmenucart ".$cart_icon."'></span>&nbsp;&nbsp;&nbsp;<span>$html </span></a>";

      if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 

        //Check if the nonce is valid
        if (wp_verify_nonce($_REQUEST['security'], "_".$this->prefix."_menu_cart_update")) {
          echo $html;
          wp_die();
        }

      }

      return $html;
     

    }  // END static function sanitize( $input ) {




   
   /**
    * Add menu cart item to nav menu
    * @param string $items nav The HTML list content for the menu items
    * @param object $args  wp_nav_menus
    */
    public function add_cart_to_nav_menu( $items, $args  ) {

      if (empty($items)) return '';
     
      return  $items .= "<li class = '".$this->plugin."-menu-cart-ajax'>".$this->menu_cart_update()."</li>";

    }







    /*
    * Fetch nav menus (will be used as options for nav menu selection)
    */
    private function get_nav_menus( ) {

      $menus = array();

      $nav_menus = get_terms( array('taxonomy' => 'nav_menu', 'hide_empty' => false,) );

      foreach ($nav_menus as $menu) {

        $menus[$menu->slug] = $menu->name;
        
      }

      return $menus;

    }  // END static function sanitize( $input ) {



    /*
    * Fetch nav menus input fields
    */
    private function get_cart_icons( ) {

      return array (
        '1' => 'axlwpmenucart-cart',
        '2'  => 'axlwpmenucart-basket3',
        '3'  => 'axlwpmenucart-basket2',
        '4'  => 'axlwpmenucart-shopping-cart5',
        '5'  => 'axlwpmenucart-shopping_cart',
        '6'  => 'axlwpmenucart-shopping-cart3',
        '7'  => 'axlwpmenucart-shopping-basket2',
        '8'  => 'axlwpmenucart-shopping-cart4',
        '9'  => 'axlwpmenucart-shopping-basket',
        '10' => 'axlwpmenucart-shopping-cart2',
        '11' => 'axlwpmenucart-shopping-cart',
        '12' => 'axlwpmenucart-cart3',
        '13' => 'axlwpmenucart-cart2',
        '14' => 'axlwpmenucart-cart',
        '15' => 'axlwpmenucart-basket',
        '16' => 'axlwpmenucart-cart2',
        '17' => 'axlwpmenucart-cart'
      );

    }  // END static function sanitize( $input ) {





    // /*
    // * Admin submenus
    // */
    // public  function  admin_submenus (){

    //   // Set admin submenu pages
    //   return array(

    //       array(
    //       'parent_id'    => parent::class_module(),    // The id name for the parent menu (or the file name of a standard WordPress admin page).  
    //       'page_title'   => __($this->title, $this->plugin),    // Text to be displayed in the browser window.   
    //       'menu_title'   => __($this->name, $this->plugin),      // Text to be displayed for the menu   
    //       'caps'         => 'manage_options', // The capability required for this page to be displayed to the user.
    //       'id'         => $this->class_module(),    //Unique id for this menu  
    //      'callback'     => array($this, 'submenu_template'), // The function to be called to output the content for this page.
    //     ),

    //   );

    // }



    /*
    * Admin sections 
    */
    public function  admin_tabs (){

      //$admin_menu = new Admin_Menu ;  // Admin Menu class (handles sanitization and errors)

      return array(  

          $this->class_module()."_settings"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'      => __('Settings', $this->plugin),   // Formatted title of the tab. Shown as the heading for the section.
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 


        $this->class_module()."_other"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'      => __('Other', $this->plugin),   // Formatted title of the tab. Shown as the heading for the section.
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

      );

    }






   /**
    * Default settings
    * @param  string $tab tab name
    * @return array      default settings
    */
    public function defaults($tab) {

      switch ( $tab ) { 

        case $this->class_module().'_settings' :

          return array(
            'nav_menu_slug'      => '',
            'hide_empty_cart'    => false,
            'cart_icon'          => '1',
            'menu_item_color'    => '#FFF'
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

      return array(

        'nav_menu_slug' => array(
          'title'       => __('Menu to Display Cart On', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // tab to which this field belongs
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['nav_menu_slug'],
          'field_type'      => 'select',
          'field_options'  => $this->get_nav_menus(),
          'description' => "Select the menu where you want to display the cart",
        ),


        'hide_empty_cart' => array(
          'title'       => __('Hide Empty Cart', $this->plugin),
          'tab'         => $this->class_module()."_settings",  // tab to which this field belongs
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['hide_empty_cart'],
          'field_type'      => 'checkbox',
          'field_options'  => $this->get_nav_menus(),
          'description' => "Check this box to hide empty carts",
        ),


        'cart_icon' => array(
          'title'       => __('Cart Icon', $this->plugin),
          'tab'           => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['cart_icon'],
          'field_type'      => 'radio',
          'field_options'  => $this->get_cart_icons(),
          'description' => "Select cart icon",
        ),

        'menu_item_color' => array(
          'title'       => __('Cart Icon', $this->plugin),
          'css_class'       => 'axl-wp-ultimate-color-picker',
          'tab'           => $this->class_module()."_settings",  // Section to which this field belongs
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['menu_item_color'],
          'field_type'      => 'text',
          //'field_options'  => $this->get_cart_icons(),
          'description' => "Select cart icon",
        ),


      );

    } // END  private  function register_fields (){



  } // END class WooMenuCart {
    
} // END if (!class_exists('WooMenuCart')) 
