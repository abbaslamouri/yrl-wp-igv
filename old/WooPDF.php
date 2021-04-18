<?php

/**
* AXL-WP-Ultimate
*
* @package AXL-WP-Ultimate
* @author Abbas Lamouri
* @since 1.0.0
**/

namespace Ultimate\Includes;

use Dompdf\Dompdf;


// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if (!class_exists('WooPDF')) {

  class WooPDF extends Dashboard {

    public $name = "Woocommerce PDF";  // this module name

    
    /**
    *Magic constructor.  Gets called when an object is instantiated
    */
    public function __construct() {

       // Enqueue scripts
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ));
      add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts' ));

      // Add admin Submenus, section and fields
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

      // Add init action
      add_action ('admin_init', array($this, 'admin_init'));

      // Add packing slip metabox
      add_action('add_meta_boxes', array($this, 'add_woopdf_meta_box'));

      // Add action buttons to order list
      add_filter( 'woocommerce_admin_order_actions', array($this, 'order_actions'), 10, 2 );

    }  // END public function __construct()








     /**
   * Class module name
   * @return string module name
   */
    public function class_module() {

      return $this->prefix."_woopdf";

    } // END public function class_module()



     /**
    * Register and Enque stylesheet.  Hooks into WP's wp_enqueue_scripts
    **/
    public function enqueue_scripts() {
      
      // // Media uploader
      // wp_enqueue_media();

      // //Enqueue Javascript 
      // wp_register_script( $this->plugin."_wp_media_uploader", $this->url."assets/js/wp-media-uploader.js", array( "jquery" ), null, true );
      // wp_enqueue_script( $this->plugin."_wp_media_uploader");


      //Enqueue Javascript 
      wp_register_script( $this->plugin."_woo_pdf", $this->url."assets/js/woo-pdf.js", array( "jquery" ), null, true );
      wp_enqueue_script( $this->plugin."_woo_pdf");


    }  // END public function styles()



  



    /**
    * Outputs Woocommerce packing slip or invoice pdf (admin_init hook)
    * @param  $_GET, $dompdf, $canvas, $atts, $settings
    */
    public function admin_init () {

      // Print packing slip
      if ( ! isset($_GET['print-packing-slip']) && ! isset($_GET['print-invoice']))  return;

      // Extract settings
      extract( $this->tab_settings($this->class_module()."_settings") );

      // instantiate and use the dompdf class
      $dompdf = new Dompdf();

      // Get options
      $options =$dompdf->getOptions();
      
      // set default font
      $options->set ('defaultFont', 'arial');

      // get canvas
      $canvas = $dompdf->get_canvas();

      // ger woocommerce order details
      $this->atts['order-id'] = $_GET['order-id'];
      $this->atts['order'] = new \WC_Order($_GET['order-id']);
      $order_date_time = explode("T", $this->atts['order']->get_date_created())[0];
      $this->atts['order-date'] = explode("-", $order_date_time)[1]."/".explode("-", $order_date_time)[2]."/".explode("-", $order_date_time)[0];

      // get order products
      $this->atts['cart'] = $this->atts['order']->get_items();
      
      // retreive company logo
      if ($pos = strrpos($logo, 'uploads')) {  // postion of the 'uploads' string n the image url
        $base = substr($logo, $pos+ strlen('uploads')); // basename of the iamge in the iamge url
        $upload_dir = wp_upload_dir()['basedir']; // ulpoads dir
        $this->atts['src-path'] = $upload_dir.$base;  // path to the image
      } else {
        $this->atts['src-path'] = AXL_WP_Ultimate_PATH.'assets/img/'.basename($logo);  // default logo url
      }

      // Retreive company info
      $this->atts['company'] = $company; 

      // Output invoice/packing slip
      $html = $this->get_template_html('invoice-packing-slip', $this->atts);

       // Output invoice
      //if ( isset($_GET['print-invoice']) && $_GET['print-invoice'] ) $html = $this->get_template_html('invoice', $this->atts);

      // load template
      $dompdf->loadHtml($html);

      //Setup the paper size and orientation
      $dompdf->setPaper('letter', 'portrait');

      // Render the HTML as PDF
      $dompdf->render();

      // Retreive page dimensions
      $page_width = $canvas->get_width();  // page width in dpi 
      $page_height = $canvas->get_height();  // page height in dpi

      // Set page font
      $font = $dompdf->getFontMetrics()->get_font("helvetica", "regular");
      
      // Set and display page footer
      $font_size = 10;
      $canvas->page_text($page_width/2 -($font_size*(strlen($footer)) - $font_size*7)/4, 730, $footer, $font, $font_size, array(0,0,0));
      
      // Set page font
      $font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");

      // Set and display page numbering
      $page_number_text = "Page:";
      $font_size = 10;
      $canvas->page_text($page_width/2 -($font_size*(strlen($page_number_text)))/4, 760, $page_number_text." {PAGE_NUM} of {PAGE_COUNT}", $font, $font_size, array(0,0,0));


      //  Output pdf
      if (isset($_GET['print-invoice'])) $dompdf->stream("invoice.pdf", array("Attachment" => false));
      if (isset($_GET['print-packing-slip'])) $dompdf->stream("packing-slip.pdf", array("Attachment" => false));

      exit();

    }  // END public function init () {

    
    /**
    * Adds WOO PDF meta box to woocommerce orders
    */
    public function add_woopdf_meta_box () {

      add_meta_box($this->plugin.'-woopdf', __('WOO PDF', $this->plugin), array($this, 'woopdf_meta_box_render'), 'shop_order', 'side', 'high', null);

    }  // END public function add_woopdf_meta_box () {



    /**
    * Renders the WOO PDF meta box
    */
    public function woopdf_meta_box_render () {
      
      global $post;

      ?>

      <p><a href = "<?php echo admin_url().'?order-id='.$post->ID.'&print-packing-slip=1'; ?>" class='button button-primary' target = '_blank'>Print Packing Slip</a></p>

      <p><a href = "<?php echo admin_url().'?order-id='.$post->ID.'&print-invoice=1'; ?>" class='button save_order button-primary' target = '_blank'>Print Invoice</a></p>

      <?php

    }  // END public function woopdf_meta_box_render () {
      



   
    /**
     * Adds packing slip and invoice actiosn to woocommerce orders list
     * @param  array $actions array of actions
     * @param  object $order   Woocommerce order
     * @return array          array of actions
     */
    public function order_actions($actions, $order) {

       $actions['packing-slip'] = array(
        // adjust URL as needed
        'url'  => '/wp-admin/edit.php/?post_type=shop_order&order-id='.$order->get_order_number().'&print-packing-slip='.true,
        'name' => __( 'Print Packing Slip', $this->plugin ) ,
        'action' => 'axl-wp-ultimate-packing-slip',
      );


       $actions['invoice'] = array(
        // adjust URL as needed
        'url'  => '/wp-admin/edit.php/?post_type=shop_order&order-id='.$order->get_order_number().'&print-invoice='.true,
        'name' => __( 'Print Invoice', $this->plugin ) ,
        'action' => 'axl-wp-ultimate-invoice',
      );

      return $actions;

    }  // END public function order_actions($actions, $order) {





    // /*
    // * Admin subadmin
    // */
    // public  function  admin_submenus (){

    //   // Set admin submenu pages
    //   return array(

    //       array(
    //       'parent_id'    => parent::class_module(),    // The id name for the parent menu (or the file name of a standard WordPress admin page).  
    //       'page_title'   => __($this->title, $this->plugin),    // Text to be displayed in the browser window.   
    //       'menu_title'   => __('Woocommerce PDF', $this->plugin),      // Text to be displayed for the menu   
    //       'caps'         => 'administrator', // The capability required for this page to be displayed to the user.
    //       'id'         => $this->class_module(),    //Unique id for this menu  
    //      'callback'     => array($this, 'submenu_template'), // The function to be called to output the content for this page.
    //     ),

    //   );

    // }  // END public  function  admin_submenus (){



    /*
    * Admin sections 
    */
    public function  admin_tabs (){

      return array(  

          $this->class_module()."_settings"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
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

    }  // END public function  admin_tabs (){






      
    /**
    * Default settings
    * @param  string $tab tab name
    * @return array      default settings
    */
    public function defaults($tab) {

      switch ( $tab ) { 

        case $this->class_module().'_settings' :

          return array(
            'logo' => AXL_WP_Ultimate_URL.'assets/img/logo.png',
            'company' => "",
            'footer' => "",
          );


          default :
          
          return array();

          break;

      }


    }  // END public function settings_defaults($tab) {   
      




    /*
    * Admin fields
    */
    public function admin_fields (){

      return array(

        'logo'    => array(   
          'title'       => __("Logo", $this->plugin),
          'css_class'   => 'default-image',
          'tab'         => $this->class_module()."_settings",
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['logo'],
          'field_type'  => 'media',
          'field_size'  => "100",
        ),

        'company'    => array(  
          'title'       => __('Company', $this->plugin),
          'tab' => $this->class_module()."_settings",
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['company'],
          'field_type'  => 'textarea',
          'textarea_cols'  => 100,
          'textarea_rows'  => 10,
          'description' => '' ,
        ),

        'footer'    => array(  
          'title'       => __('Footer', $this->plugin),
          'tab' => $this->class_module()."_settings",
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_settings")['footer'],
          'field_type'  => 'textarea',
          'textarea_cols'  => 100,
          'textarea_rows'  => 5,
          'description' => '' ,
        ),

      );


    }  // END  public function admin_fields (){



  } // END class WooPDF {

} // END if (!class_exists('WooPDF')) 
