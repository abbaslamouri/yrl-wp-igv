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

if (!class_exists('WooTidbits')) {

	class WooTidbits extends Dashboard {

    public $name = "Woocommerce Tidbits";  // this module name
    public $class_id = "woo_tdbits";  // The ID for the form in this class
    //public $form_list = "single-product-tabs-list";  // The ID for the form in this class

	
    /**
    * Magic Constructor
    */
		public function __construct() {

      //Render  PO Number, Shiping Account Number, Carrier and Method input fields checkout page checkout before customer notes
      add_action( 'woocommerce_before_order_notes', array($this, 'checkout_custom_fields'));

      // Display admin ntices
      add_action( 'admin_notices', array($this, 'add_admin_notices'));

      // Enqueue scripts
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts' ));
      add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts' ));

       // Add admin Submenus, section and fields
      add_action( 'admin_menu', array($this, 'admin_menu_register' ) );

       // Admin init setup
      add_action( 'admin_init', array($this, 'admin_init') );

      // Bail if Woocommerce is not active       
      if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;   

       // Add forbidden page  shortcode handler
      add_shortcode( $this->plugin."-forbidden-page", array( $this, 'forbidden_page_shortcode' ) );

      //Add all the woocommerce tid bits
      add_action ('wp_loaded', array($this, 'tidbits'));
      add_action ('template_redirect', array($this, 'more_tidbits'));

     

      // Add ajax
      //add_action( "wp_ajax_". $this->prefix."_contact_form_process", array($this, 'contact_form_process' ));
      //add_action( "wp_ajax_nopriv_". $this->prefix."_contact_form_process", array($this, 'contact_form_process' )); // need this to serve non logged in users

     
      
      // Display admin ntices
     // add_action( 'admin_notices', array($this, 'add_admin_notices'));

      // Reguster contacts custom post type
      //add_action( 'init', array($this, 'register_contacts_post_type') );

      // Add meta boxes to sender custom post type
      //add_action( 'add_meta_boxes', array($this, 'add_contacts_meta_boxes'), 10, 2 );

      // add sender name and sender email to contacts custom post type list columns
     // add_filter("manage_{$this->contact_form_cpt}_posts_columns", array($this, 'add_columns'));
     // add_action('manage_posts_custom_column', array($this, 'add_columns_content'), 10, 2);

      // Add content before reset button
      //add_action ("{$this->prefix}_before_reset_settings_button", array($this, 'before_reset_settings_button'), 10, 2);


      // Filter save settings button label
      //add_filter('save_settings_button_label', function ($button_label, $class_module, $active_tab) {
       // if($active_tab == $this->class_module()."_fields" ) return (isset($_POST["{$this->plugin}-edit-record"]))? 'Update Field' : "Add Field"; 
    //  }, 10, 3);
      



		}  // END public function __construct()





    /**
    * Class module name
    * @return string module name
    */
    public function class_module() {

      return $this->prefix."_woo_tidbits";

    } // END public function class_module()



    /**
     * Admin init setup
     */
    public function admin_init() {

      // Save single product tabs if they do not exist
      if (! get_option($this->class_module()."_single_product_tabs") || 
        empty (get_option($this->class_module()."_single_product_tabs")) ) update_option($this->class_module()."_single_product_tabs", $this->single_product_tabs());


      // Save order statuses if they do not exist
      if (! get_option($this->class_module()."_order_statuses") || 
        empty (get_option($this->class_module()."_order_statuses")) ) update_option($this->class_module()."_order_statuses", $this->core_order_statuses());

    } // END public function admin_init() {



     /**
    * Renders the contact form shortcode
    * @param  array $attributes shortcode attributes
    * @param  string $content    shortcode content
    * @return string html templte
    */
    public function forbidden_page_shortcode( $attributes, $content = null ) {

      ob_start();
      ?>
      Please log in to access this page
      <a class = "button" href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login">Login</a>
      <?php

      $html = ob_get_contents();
      ob_end_clean();

      return  $html;

    }  // END  public function forbidden_page_shortcode( $attributes, $content = null ) {









    public function tidbits () {

      if (is_admin()) return;

      extract( $this->tab_settings($this->class_module()."_settings") );
      extract( $this->tab_settings($this->class_module()."_sale_flash") );

      //var_dump($this->tab_settings($this->class_module()."_settings"));die;

      // Remove product meta
      if ( isset($hide_product_meta) && $hide_product_meta )  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

      // Change number of related products per page and per row
      add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
        extract( $this->tab_settings($this->class_module()."_settings") );
        $args['posts_per_page'] = $related_products_count; // # of related products
        $args['columns'] = $related_products_columns; // # of columns per row
        return $args;
      }, 9999 );


      // Rename or disable tabs on single product page 
      add_filter( 'woocommerce_product_tabs', function( $tabs ) {

        $saved_tabs = $this->tab_settings($this->class_module()."_single_product_tabs");

        // array of call back functions (I do not know what to do with this for now)
        $this->callbacks =array();

        foreach ($saved_tabs as $id => $product_tab) {
          $tabs[$id] = $product_tab;
          if (isset($product_tab['hidden']) && $product_tab['hidden']) {
            unset($tabs[$id]);
          }

          if (!in_array($id, array_keys($this->single_product_tabs()))) {
            $this->callbacks[] = $product_tab['callback'];
          }
        }

        //if (isset($hide_description_tab) && $hide_description_tab) unset( $tabs['description'] );               // Remove the reviews tab
        // if (isset($hide_description_tab) && $hide_description_tab) unset( $tabs['reviews'] );                  // Remove the reviews tab
        //if (isset($hide_description_tab) && $hide_description_tab) unset( $tabs['additional_information'] );   // Remove the additional information tabinformation tab
        //$tabs['description']['title'] = "Reference Material";
        //$tabs['description']['callback'] = array($this, 'woo_custom_description_tab_content');
        return $tabs;
      }, 98 );


      // Rename statuses and create new ones based on saved statuses options
      $statuses = $this->tab_settings($this->class_module()."_order_statuses");
      foreach ($statuses as $key => $status) {
       if (! in_array($key, array_keys($this->core_order_statuses()))){
          // Register new statuses (other than core)
          register_post_status( "wc-{$key}", array(
            'label'                     => $status['status_title'],
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false,
            'label_count'               => _n_noop( "{$status['status_title']}  <span class='count'>(%s)</span>", "{$status['status_title']}  <span class='count'>(%s)</span>" )
          ));
        }
      }

      // Reorder and display statuses
      add_filter( 'wc_order_statuses', function($statuses) {

        $status_options = $this->tab_settings($this->class_module()."_order_statuses");

        usort($status_options, function($a, $b) {
          return strnatcmp($a['status_priority'], $b['status_priority']);
        });

        // Initialize new statuses
        $new_statuses = array();
        foreach ( $status_options as $key => $status ) {
          $new_statuses[ $key ] = $status['status_title'];
        }
       
        return $new_statuses;

      } );



      // Hide price
      if ( isset($hide_price) && $hide_price ) {
        if ($hide_price == 'all')   $this->hide_price();
        if ($hide_price == 'non_logged_in' && ! is_user_logged_in() ) $this->hide_price();
      }

      // Hide Add_to_cart button
      if ( isset($hide_add_to_cart) && $hide_add_to_cart ) {
        if ($hide_add_to_cart == 'all')   $this->hide_add_to_cart();
        if ($hide_add_to_cart == 'non_logged_in' && ! is_user_logged_in() ) {
          
          // Hide Add to cart button
          $this->hide_add_to_cart();

          // Add LOGIN link 
          if (isset($add_login_link) && $add_login_link) {
            add_action('woocommerce_single_product_summary', function () {
              extract( $this->tab_settings($this->class_module()."_settings") );
              ?><div class = "login-link"><?php echo $login_link_text; ?> <a class = "button" href="<?php echo wp_login_url(); ?>" title="Login">Login</a></div><?php
            }, 30);
          }
        }
      }


      // Move product next to quantity box
      if (isset($move_price_next_qty) && $move_price_next_qty) {
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        add_action( 'woocommerce_before_add_to_cart_form', 'woocommerce_template_single_price', 30 );
        
        add_action( 'wp_head', function () { 
          ?>
         <!--  <style type="text/css">
            .single-product .product form.cart{
              display:flex;
              align-items:center;
            }

            .single-product .product form.variations_form.cart .single_variation_wrap .woocommerce-variation-add-to-cart {
              display:flex;
              align-items:center;
            }

            .single-product .product form.cart .price {
              margin-right:30px;
            }
          </style> -->
          <?php
        });
      }


     


       


      //Show OEM Part Number Attribute after product title on products page
      //add_action('woocommerce_after_shop_loop_item_title', array($this, 'show_oem_part_number_after_title'),1);
    


              





        //add_filter( 'woocommerce_get_order_item_totals', function ( $totals ) {
          //unset($totals['cart_subtotal']  );
          //return $totals;
        //});




        // Add EMPTY CART button under prodcut table on cart page
        if (isset($add_empty_cart_button) && $add_empty_cart_button) {
          add_action( 'woocommerce_after_cart_table', function () {
            ?>
            <form action="" method = "post">
              <button type="submit" class="button" name="empty_cart" value="Empty cart" onclick="return confirm('are you sure?')">Empty cart</button>
            </form>
            <?php
          });
        }
       


        // Hide Sale flash 
        if (isset($hide_sale_flash) && $hide_sale_flash) add_action('woocommerce_sale_flash', '__return_false');

        // Set sale flash position
        if (isset($sale_flash_position) && $sale_flash_position ) {
          add_action( 'wp_head', function () { 
          extract( $this->tab_settings($this->class_module()."_sale_flash") );
            ?>
            
            <?php if ($sale_flash_position == 'top_left') {?>
              <style type="text/css">
                .onsale {
                  position:absolute;
                  top:5px;
                  left:5px;
                  border-radius:50%;
                  z-index: 1000;
                }
              </style>
            <?php } ?>

            
            <?php if ( $sale_flash_position == 'top_right') { ?>
              <style type="text/css">
                .onsale {
                  position:absolute;
                  top:5px;
                  right:5px;
                  border-radius:50%;
                  z-index: 1000;
                }
              </style>
            <?php } ?>
                  
            <?php
          });

        }  


        // Set sale flash text color
        if (isset($sale_flash_text_color) && $sale_flash_text_color) {
          add_action( 'wp_head', function () { 
          extract( $this->tab_settings($this->class_module()."_sale_flash") );
            ?>
            <style type="text/css">
              .onsale {
               color:<?php echo $sale_flash_text_color; ?>;
              }
            </style>
            <?php
          });
         }

         // Set sale flash background color color
        if (isset($sale_flash_bg_color) && $sale_flash_bg_color) {
          add_action( 'wp_head', function () { 
          extract( $this->tab_settings($this->class_module()."_sale_flash") );
            ?>
            <style type="text/css">
              .onsale {
               background-color:<?php echo $sale_flash_bg_color; ?>;
              }
            </style>
            <?php
          });
         }


        //Show OEM Part Number Attribute after product title on single product page
        add_action('woocommerce_single_product_summary', array($this, 'show_attribute_under_title'),7);

        //Show attribute after product title on products page
        add_action('woocommerce_after_shop_loop_item_title', array($this, 'show_attribute_under_title') , 5);



        // Set woocommerce product placeholder image
        add_filter('woocommerce_placeholder_img_src', function ( $src ) {
                    extract( $this->tab_settings($this->class_module()."_settings") );

          // $upload_dir = wp_upload_dir();
          // $uploads = untrailingslashit( $upload_dir['baseurl'] );
          // $src = $uploads . '/ACSNOIMAGE.jpg';
          return $image_placeholder;
        });






          
   

      

      

    


 

 }


    public function more_tidbits () {

      extract( $this->tab_settings($this->class_module()."_settings") );
      
      $page = get_post($this->tab_settings($this->class_module()."_pages")['forbidden-page']);

      // Hide price for non logged in users
      if ( isset($hide_price) && $hide_price  && ! is_user_logged_in()) {

        // Prevent user from reaching the cart and checkout pages if the hide_cart is enabled and user is not logged in
        if ( is_cart() || is_checkout() ) {
          wp_redirect( home_url( $page->post_name)) ;
          exit;
        }
      }



      //Emptry cart
      if( isset( $_POST['empty_cart'] ) && $_SERVER['REQUEST_METHOD'] == "POST" ) WC()->cart->empty_cart( true );
      

    }






    public function hide_price() {

      // Prevent price from being returned
      add_filter( 'woocommerce_get_price_html', function( $price ) { return ''; } );

      // Hide checkout prices  (this does not hide the cart total)
      add_filter( 'woocommerce_cart_item_price', '__return_false' );
      add_filter( 'woocommerce_cart_item_subtotal', '__return_false' );
      
      // Hide quantity and cart totals (this is redundant as the cart and checkout pages will not be loaded)
      add_action( 'wp_head', function () { 
        ?>
        <style type="text/css">
          .cart-collaterals{
           display:none;
          }
        </style>
        <?php
      });
     

    }






    public function hide_add_to_cart() {

      // Replace Add to cart button with Read more on archive page and hide it in single product page ((this will not replace the button from variable products)
      add_filter( 'woocommerce_is_purchasable', '__return_false');
      
      // Remove archives add to cart button
      //remove_action( "woocommerce_after_shop_loop_item", "woocommerce_template_loop_add_to_cart", 10);

      // Remove single product add to cart button on all types of products 
      remove_action( "woocommerce_single_product_summary", "woocommerce_template_single_add_to_cart", 30);
      

      // Rename read more button
      add_filter( 'woocommerce_product_add_to_cart_text' , function ($text) {
        switch ($text) {
          case "Read more" : return 'View details';
          default : return $text;
          break;
        }


      });


      //Change 'add to cart' text on archive product page (this will prevent read more when add_to_cart button is hidden)
     // add_filter( 'woocommerce_product_add_to_cart_text', '__return_false');

    }





    public function show_attribute_under_title() {

      global $product;

      extract( $this->tab_settings($this->class_module()."_settings") );

      $terms =  wp_get_post_terms( $product->get_id(), $display_attribute , 'all' );
      if ( ! is_wp_error( $terms ) && ! empty($terms) ) {
        if ( isset($terms[0]) && isset($terms[0]->name) ) {
          ?><div class="<?php echo $display_attribute ?>"><?php echo $terms[0]->name ?></div><?php
        } else {
          ?><div class="<?php echo $display_attribute ?>"><?php echo "&nbsp;" ?></div><?php
        }
      } else {
        ?><div class="<?php echo $display_attribute ?>"><?php echo "&nbsp;" ?></div><?php
      }     

    }



public function checkout_custom_fields () {

        //Render  PO Number, Shiping Account Number, Carrier and Method input fields checkout page checkout before customer notes
      add_action( 'woocommerce_before_order_notes', function ( $checkout ) {

        ?>
        <div id="additionl_info_checkout_field"><h2><?php //echo  __('Additional Information')  ?></h2>
        <?php
          
          // Add PO Number field 
          woocommerce_form_field( 'arq_po_number', array(
            'type'          => 'text',
            'class'         => array('arq-po-number form-row-wide'),
            'label'         => __('PO Number'),
            'placeholder'   => __('Please enter PO Number'),
            'required'      => true,
          ), $checkout->get_value( 'arq_po_number' ));

          // Add shipping account number field
           woocommerce_form_field( 'arq_shipping_account_number', array(
            'type'          => 'text',
            'class'         => array('arq-shipping-account-number form-row-wide'),
            'label'         => __('Please enter shipping account number'),
            'placeholder'   => __('Shipping Account Number'),
          ), $checkout->get_value( 'arq_shipping_account_number' ));

          // Add shipping carrier field
          woocommerce_form_field( 'arq_shipping_carrier', array(
            'type'          => 'select',
            'class'         => array('arq-shipping-carrier form-row-wide'),
            'label'         => __('Carrier'),
            'options'       => $this->shipping_carriers,
          ), $checkout->get_value( 'arq_shipping_carrier' ));

          // Add Shipping priority field
          woocommerce_form_field( 'arq_shipping_method', array(
            'type'          => 'select',
            'class'         => array('arq-shipping-method form-row-wide min-width50px'),
            'label'         => __('Method'),
            'options'       => $this->shipping_methods,
          ), $checkout->get_value( 'arq_shipping_method' ));

          ?>
        </div>
        <?php

      });

    }





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
    * Enqueue scripts
    */
    public function enqueue_scripts() {


      wp_register_script( "{$this->prefix}-woo-tidbits", $this->url."assets/js/woo-tidbits.js", array( "jquery" ), null, true );
      wp_enqueue_script( "{$this->prefix}-woo-tidbits");

      // // Localization.
      // wp_localize_script( 

      //   $this->prefix."_contact_form",  //handle for the script
      //   $this->prefix."_contact_form_object",     //  The name of the variable which will contain the data (used in the ajax url)
      //   array(  // Data to be passed 
      //     'ajax_url'      => admin_url( 'admin-ajax.php' ),
      //     'ajax_action'   => $this->prefix."_contact_form_process",
      //   )

      // );  

    } // END public function enqueue_scripts()






      /**
    * Sanitizes settings fields
    * @param  array $input settings fields
    * @return array        settings fields
    */
   // public function sanitize( $input ) {



    //  //echo "<pre>";
    // // var_dump($input);die;

    //   // Intialize error
    //   $errors =  new \WP_Error;

    //   //Deelete option if post reset is set
    //   if(isset($_POST['reset_settings']) && isset($_POST['option_page'])) {
    //     delete_option($_POST['option_page']);
    //     return;
    //   }

    //   // Retreive add_feild settings options
    //   //$output = (get_option($this->class_module()."_single_product_tabs"))? get_option($this->class_module()."_single_product_tabs") : array();
    //   $output = (isset($_POST['option_page']) && get_option($_POST['option_page']))? get_option($_POST['option_page']) : array();
    //    //var_dump($output);die;
    //  // var_dump(count($output, COUNT_RECURSIVE) !== count($output));die;


    //   // Deelete setting if post delete is set
    //   if(isset($_POST["{$this->plugin}-delete-record"])) {
    //     unset($output[$_POST["tab_id"]]);
    //     return $output;
    //   }


    //   // Add required fields error
    //   foreach ($this->admin_fields() as $key => $field) {
    //     if (in_array($key, array_keys($input))){
    //       if (isset($field['required']) && $field['required'] &&  ! $input[$key])
    //         $errors->add( "{$key}_empty", __("{$field['title']} is required", $this->plugin));
    //     }
    //   }

    //    // Check if an existing cpt is being addaed
    //   if ( (! isset($_POST["{$this->plugin}-update-record"]) || ! $_POST["{$this->plugin}-update-record"]) && isset($input['tab_id']) && in_array($input['tab_id'], array_keys($output))) 
    //    $errors->add( "{$input['tab_id']}_exists", __("A record with this id ({$input['tab_id']}) exists.  Please edit this tab to modify it", $this->plugin));

    //   if (!empty($errors->errors)) {
    //     foreach (array_combine($errors->get_error_codes(), $errors->get_error_messages()) as $code => $message) {
    //       add_settings_error( $this->class_module(), $code, $message );
    //     }
     
    //   } else {
  
      
    //     // Save settings depending on whether it is an array or array or arrays as in the case of add_field tab
    //     //if (in_array('tab_id', array_keys($input))) {
    //     if (count($output, COUNT_RECURSIVE) !== count($output) ){   
    //       $output[$input['tab_id']] = $input;
    //     } else {
    //       $output = $input;
    //     }
    //   }

    //   //var_dump($output);die;
   
    //   return $output;

   // }  // END static function sanitize( $input ) {








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
            'general' => array('title' => 'General Settingsxxx'),
            'other'   => array('title' => 'Other Settings'),
          )
        ), 

        $this->class_module()."_sale_flash"  => array(
          
          'title'      => __('', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Sale Flash', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

         $this->class_module()."_pages"  => array(  
          'title'      => __('Pages', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Pages', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

        $this->class_module()."_single_product_tabs"  => array(  
          'title'      => __('Single Product Tabs', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Single Product Tabs', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

        $this->class_module()."_order_statuses"  => array(  
          'title'      => __('Order Statuses', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Order Statuses', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

         $this->class_module()."_checkout_fields"  => array(  
          'title'      => __('Checkout Fields', $this->plugin),   // Formatted title of the section. Shown as the heading for the section.
          'tab_title'  => __('Checkout Fields', $this->plugin),
          'sections'   => array( 
            'general' => array('title'    => 'General Settings'),
          )
        ), 

       
      );

    }  // END public function  admin_tabs (){






       /**
    * Sets mandatory fields for this mosule
    * @return array Mandatory fields
    */
    public function single_product_tabs() {


      return array(

        'description'              =>  array(
          'tab_id'                 => 'description',
          'title'                  => 'Description',
         // 'priority'               => 10,
          'callback'               => 'woocommerce_product_description_tab',
          'hidden'                 =>false,
        ),

        'additional_information'   =>  array(
          'tab_id'                 => 'additional_information',
          'title'                  => 'Additional Information',
          //'priority'               => 20,
          'callback'               => 'woocommerce_product_additional_information_tab',
          'hidden'                 =>false,
        ),

        'reviews'                  =>  array(
          'tab_id'                 => 'reviews',
          'title'                  => 'Reviews',
          //'priority'               => 30,
          'callback'               => 'comments_template',
          'hidden'                 =>false,
        ),

      );

     
    } // END public function mandatory_fields()




    public function core_order_statuses() {

      return array(
        "wc-pending"          => array(
          "status_id"         => "wc-pending",
          "status_title"      =>"Pending payment",
         // "status_priority"   => 10,
        ),
        "wc-processing"       => array(
         "status_id"          => "wc-processing",
         "status_title"       => "Processing",
       // "status_priority"     => 20,
       ),
        "wc-on-hold"          => array(
         "status_id"          => "wc-on-hold",
         "status_title"       =>"On hold",
        // "status_priority"   => 30,
       ),
        "wc-completed"        => array(
          "status_id"         => "wc-completed",
          "status_title"      => "Completed",
         // "status_priority"   => 40,
        ),
        "wc-cancelled"        => array(
          "status_id"         => "wc-cancelled",
          "status_title"      => "Cancelled",
         // "status_priority"   => 50,
        ),
        "wc-refunded"         => array(
          "status_id"         => "wc-refunded",
          "status_title"      => "Refunded",
        //  "status_priority"   => 60,
        ),
        "wc-failed"           => array(
          "status_id"         => "wc-failed",
          "status_title"      => "Failed",
         // "status_priority"   => 70,
          ) 
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
            'hide_price'               => 'non_logged_in',
            'hide_add_to_cart'         => 'non_logged_in',
            'add_empty_cart_button'    => false,
            'image_placeholder'        => AXL_WP_Ultimate_URL.'assets/img/image-placeholder.png',
            'move_price_next_qty'      => false,
            'add_login_link'           => false,
            'login_link_text'          => 'Please login to access pricing and other information ',
            'hide_product_meta'        => false,
            'related_products_count'   => 4,
            'related_products_columns' => 2,
            'display_attribute'  => false,
            'forbidden-page'     => '',
          );

        case $this->class_module().'_sale_flash' :

          return array(
            'hide_sale_flash'          =>false,
            'sale_flash_position'      =>'top_left',
            'sale_flash_text_color'    => '#FFF',
            'sale_flash_bg_color'      => '#CCC',
          );


        case $this->class_module().'_pages' :

          return array(
            'forbidden-page'     => '', 
          );


        case $this->class_module().'_single_product_tabs' :

          return array(
            'tab_id'      =>'',
            'title'       => '',
            //'priority'    => '',
            'hidden'  => false,
            'callback'    => ''
          );

        case $this->class_module().'_order_statuses' :

          return array(
            'status_id'          =>'',
            'status_title'       => '',
           // 'status_priority'    =>0,
          );



        case $this->class_module().'_checkout_fields' :

          return array(
          'checkout_field_label'  => "",
          'checkout_field_id' => "",
          'checkout_field_type' => "",
          'checkout_field_options' => "",
          'checkout_field_required' => false,
          'checkout_field_css_class' => "",
          'checkout_field_placeholder' => "",
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

        'hide_price' => array(
          'title'       => __('Hide Price', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['hide_price'],
          'section'     => 'general',
          'field_type'  => 'radio',
          'field_options'     => array('none' => 'None', 'non_logged_in' => 'Non Logged in Users', 'all' => 'All'),
          'description' => "Check this field to hide price for non logged in users",
        ),

        'hide_add_to_cart'    => array(
          'title'             => __('Hide Add to cart button', $this->plugin),
          'tab'               => $this->class_module()."_settings",  // Section to which this field belongs
          'default'           => $this->defaults($this->class_module()."_settings")['hide_add_to_cart'],
          'section'           => 'general',
          'field_type'        => 'radio',
          'field_options'     => array('none' => 'None', 'non_logged_in' => 'Non Logged in Users', 'all' => 'All'),
          'description' => "Check this field to hide the Add to cart button",
        ),

        'move_price_next_qty' => array(
          'title'       => __('Move Price Next Qty.', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['move_price_next_qty'],
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'description' => "Move the price next to the quantity field",
        ),

        'add_login_link' => array(
          'title'       => __('Add Login in Link', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['add_login_link'],
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'description' => "Add login link when Add to cart button is hidden and users are not logged in",
        ),

        'login_link_text' => array(
          'title'       => __('Login Link Text', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['login_link_text'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => 100,
          'description' => "Login in link text",
        ),

        'display_attribute' => array(
          'title'       => __('Display attribute below title', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['display_attribute'],
          'section'     => 'general',
          'field_type'  => 'text',
          'field_size'  => 100,
          'description' => "Choose the attribute to display below the product title",
        ),

        'hide_product_meta' => array(
          'title'       => __('Hide Product Meta', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['hide_product_meta'],
          'section'     => 'other',
          'field_type'  => 'checkbox',
          'description' => "",
        ),

        'related_products_count' => array(
          'title'       => __('Related Products per Page', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['related_products_count'],
          'section'     => 'other',
          'field_type'  => 'number',
          'description' => "Enter the number of related products to display",
        ),

        'related_products_columns' => array(
          'title'       => __('Related Products Columns', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['related_products_columns'],
          'section'     => 'other',
          'field_type'  => 'number',
          'description' => "Enter the number of related products to display",
        ),





       
       




        'add_empty_cart_button'    => array(
          'title'             => __('Add "Empty Cart" Button', $this->plugin),
          'tab'               => $this->class_module()."_settings",  // Section to which this field belongs
          'default'           => $this->defaults($this->class_module()."_settings")['add_empty_cart_button'],
          'section'           => 'other',
          'field_type'        => 'checkbox',
          'field_options'     => array('none' => 'None', 'non_logged_in' => 'Non Logged in Users', 'all' => 'All'),
          'description'       => "Add 'Empty Cart' button under 'Update Cart' button" ,
        ),

        'image_placeholder'    => array(   
          'title'       => __("Product Image Placeholder", $this->plugin),
          'css_class'   => 'default-image',
          'tab'         => $this->class_module()."_settings",
          'section'     => 'other',
          'default'       => $this->defaults($this->class_module()."_settings")['image_placeholder'],
          'field_type'  => 'media',
          'field_size'  => "100",
        ),




       

       'hide_sale_flash'    => array(
          'title'             => __('Hide Sale flash', $this->plugin),
          'tab'               => $this->class_module()."_sale_flash",  // Section to which this field belongs
          'default'           => $this->defaults($this->class_module()."_sale_flash")['hide_sale_flash'],
          'section'           => 'general',
          'field_type'        => 'checkbox',
          'field_options'     => array('none' => 'None', 'non_logged_in' => 'Non Logged in Users', 'all' => 'All'),
          'description'       => "Hide Sale box" ,
        ),

        'sale_flash_position'    => array(
          'title'             => __('Sale flash top position', $this->plugin),
          'tab'               => $this->class_module()."_sale_flash",  // Section to which this field belongs
          'default'           => $this->defaults($this->class_module()."_sale_flash")['sale_flash_position'],
          'section'           => 'general',
          'field_type'        => 'radio',
          'field_options'     => array('top_left' => 'Top Left', 'top_right' => 'Top Right'),
          'description'       => "Sale flash position" ,
        ),

        'sale_flash_text_color' => array(
          'title'       => __('Text Color', $this->plugin),
          'css_class'       => 'axl-wp-ultimate-color-picker',
          'tab'           => $this->class_module()."_sale_flash",  // Section to which this field belongs
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_sale_flash")['sale_flash_text_color'],
          'field_type'      => 'text',
          //'field_options'  => $this->get_cart_icons(),
          'description' => "",
        ),

        'sale_flash_bg_color' => array(
          'title'       => __('Background color Color', $this->plugin),
          'css_class'       => 'axl-wp-ultimate-color-picker',
          'tab'           => $this->class_module()."_sale_flash",  // Section to which this field belongs
          'section'     => 'general',
          'default'       => $this->defaults($this->class_module()."_sale_flash")['sale_flash_bg_color'],
          'field_type'      => 'text',
          //'field_options'  => $this->get_cart_icons(),
          'description' => "",
        ),








        'display_attribute' => array(
          'title'       => __('Display attribute below title', $this->plugin),
          'tab'     => $this->class_module()."_settings",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_settings")['display_attribute'],
          'section'     => 'general',
          'field_type'  => 'text',
          'description' => "Choose the attribute to display below the product title",
        ),

        'forbidden-page' => array(
          'title'       => __( 'Forbidden Page', $this->plugin), 
          'tab'         => $this->class_module()."_pages",  // Section to which this field belongs
          'default'            => $this->defaults($this->class_module()."_pages")['forbidden-page'],
          'section'            => 'general',
          'field_type'  => 'page-dropdown',  // custom field (fieled\\d type:  text, email, checkbox, textarea....) (supplied in the $args)
          'option_none' => 'Select Page',  // custom field (default value) (supplied in the $args)
          'required'    => true,  // Whether the field is required or not (supplied in the $args)
          'description' => "Select a page to redirect to if hide cart is enabled and user is not logged in and the cart or checkout pages are called."
        ),



        'title' => array(
          'title'       => __('Title', $this->plugin),
          'tab'         => $this->class_module()."_single_product_tabs",  // Section to which this field belongs
          'default'      => $this->defaults($this->class_module()."_single_product_tabs")['title'],
          'section'     => 'general',
          'css_class'   =>'parent-field',
          'field_type'  => 'text',
          'field_size'  => 100,
          'description' => "New tab title",
        ),

        'tab_id' => array(
          'title'       => __('Tab ID', $this->plugin),
          'tab'         => $this->class_module()."_single_product_tabs",  // Section to which this field belongs
          'default'      => $this->defaults($this->class_module()."_single_product_tabs")['tab_id'],
          'section'     => 'general',
           'css_class'   => 'child-field',
          'field_type'  => 'text',
          'unique'     => true,
          'field_size'  => 100,
          'description' => "New tab ID",
        ),

        // 'priority' => array(
        //   'title'       => __('Priority', $this->plugin),
        //   'tab'         => $this->class_module()."_single_product_tabs",  // Section to which this field belongs
        //   'default'       => $this->defaults($this->class_module()."_single_product_tabs")['priority'],
        //   'section'     => 'general',
        //   'field_type'  => 'text',
        //   'required'    => true,
        //   'field_size'  => 100,
        //   'description' => "New tab priority",
        // ),

        'hidden' => array(
          'title'       => __('Visibility', $this->plugin),
          'tab'         => $this->class_module()."_single_product_tabs",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_single_product_tabs")['hidden'],
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'description' => "Check this box to hide this tab",
        ),

        'callback'  => array(
          'title'       => __('Callback', $this->plugin),
          'tab'        => $this->class_module()."_single_product_tabs",  // Section to which this field belongs
          'default'     => $this->defaults($this->class_module()."_single_product_tabs")['callback'],
          'section'     => 'general',
          'field_type'  => 'text',
          'required'    => true,
          'field_size'  => 100,
          'description' => "New tab callback fumction",
        ),



        'status_title'  => array(
          'title'       => __('Status Title', $this->plugin),
          'tab'        => $this->class_module()."_order_statuses",  // Section to which this field belongs
          'default'     => $this->defaults($this->class_module()."_order_statuses")['status_title'],
          'section'     => 'general',
          'css_class'   =>'parent-field',
          'field_type'  => 'text',
          'required'    => true,
          'field_size'  => 100,
          'description' => "Order Status title",
        ),
          
        'status_id' => array(
          'title'       => __('Status ID', $this->plugin),
          'tab'         => $this->class_module()."_order_statuses",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_order_statuses")['status_id'],
          'section'     => 'general',
          'css_class'   =>'child-field',
          'field_type'  => 'text',
          'required'    => true,
          'field_size'  => 100,
          'unique'     => true,
          'description' => "Order status ID",
        ),




        'checkout_field_label'  => array(
          'title'       => __(' Label', $this->plugin),
          'tab'        => $this->class_module()."_checkout_fields",  // Section to which this field belongs
          'default'     => $this->defaults($this->class_module()."_checkout_fields")['checkout_field_label'],
          'section'     => 'general',
          'css_class'   =>'parent-field',
          'field_type'  => 'text',
          'required'    => true,
         // 'field_size'  => 100,
          'description' => "",
        ),

        'checkout_field_id' => array(
          'title'       => __('ID', $this->plugin),
          'tab'         => $this->class_module()."_checkout_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_checkout_fields")['checkout_field_id'],
          'section'     => 'general',
          'css_class'   =>'child-field',
          'field_type'  => 'text',
          'required'    => true,
         // 'field_size'  => 100,
          'unique'     => true,
          'description' => "",
        ),

        'checkout_field_type' => array(
          'title'       => __('Type', $this->plugin),
          'tab'         => $this->class_module()."_checkout_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_checkout_fields")['checkout_field_type'],
          'section'     => 'general',
          'css_class'   => 'select-radio-type',
          'field_type'  => 'select',
          'required'    => true,
          'field_options' => array('text' => 'Text', 'checkbox' => 'Checkbox', 'email' => 'Email', 'select' => 'Select', 'radio' => ' Radio', 'textarea' => 'Textarea'),
         // 'field_size'  => 100,
          'description' => "",
        ),

         'checkout_field_options' => array(
          'title'       => __('Options', $this->plugin),
          'tab'     => $this->class_module()."_checkout_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_checkout_fields")['checkout_field_options'],
          'section'     => 'general',
          'css_class'   => 'field-options',
          'field_type'  => 'text',
          'field_size'  => "50",
          'modal'       => true,
          'description' => "Comma separated options for above field",
        ),

        'checkout_field_required' => array(
          'title'       => __('Required', $this->plugin),
          'tab'         => $this->class_module()."_checkout_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_checkout_fields")['checkout_field_required'],
          'section'     => 'general',
          'field_type'  => 'checkbox',
          'description' => "",
        ),

        'checkout_field_css_class' => array(
          'title'       => __('CSS Class', $this->plugin),
          'tab'         => $this->class_module()."_checkout_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_checkout_fields")['checkout_field_css_class'],
          'section'     => 'general',
          'field_type'  => 'text',
          //'field_size'  => 100,
          'description' => "",
        ),

        'checkout_field_placeholder' => array(
          'title'       => __('Placeholder', $this->plugin),
          'tab'         => $this->class_module()."_checkout_fields",  // Section to which this field belongs
          'default'       => $this->defaults($this->class_module()."_checkout_fields")['checkout_field_placeholder'],
          'section'     => 'general',
          'field_type'  => 'text',
          //'field_size'  => 100,
          'description' => "",
        ),

        

        // 'status_priority' => array(
        //   'title'       => __('Status ID', $this->plugin),
        //   'tab'         => $this->class_module()."_order_statuses",  // Section to which this field belongs
        //   'default'       => $this->defaults($this->class_module()."_order_statuses")['status_priority'],
        //   'section'     => 'general',
        //   'field_type'  => 'number',
        //   'description' => "Position of this status in the list",
        // ),

       
      );

    } // END  public function  admin_fields(){

  


	} // END class WooTidbits {
		
} // END if (!class_exists('WooTidbits')) 



