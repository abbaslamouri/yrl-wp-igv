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

if (!class_exists('Activate')) {


	class Activate extends Dashboard{

		/**
		*Magic constructor.  Gets called when an object is instantiated
		*/
		public function __construct () {




			//asdasdadsa
			

			register_activation_hook($this->base, array($this, 'activate' ) );
			register_deactivation_hook($this->base, array($this, 'deactivate' ) );

			// // Register styles and scripts
			// add_action( 'wp_enqueue_scripts', array($this, 'scripts_styles' ) );
			// add_action( 'admin_enqueue_scripts', array($this, 'scripts_styles' ) );

		}  // END public function __construct()
	

		/**
		*Called when the plugin is activated using register_activation_hook
		**/
		public function activate() {	

			// Store plugin options
			if( false === get_option($this->prefix)){ //Options does not exists (first activation of plugin) add option
				
				add_option( $this->prefix, $this->plugin_options());

			} elseif ( get_option($this->prefix) != $this->plugin_options()) { // Plugin version different then update

					update_option( $this->prefix, $this->plugin_options());

			}


			// Flush rewrite rules on plugin activation
			if ( class_exists('Ultimate\Includes\CPT')) {

				$cpt = new CPT;
      	$cpt->post_types_add();
     		flush_rewrite_rules();
     	}

    //   if ( class_exists('Ultimate\Includes\Member')) {
			
				// $member= new Member;
    //   	$settings = (get_option($member->class_module()."_settings"))? get_option($member->class_module()."_settings") : $member->settings_defaults();
    //   	if ( ! $member->admin->check_required_pages($member) && isset($settings['create_default_pages']) && $settings['create_default_pages']) $member->admin->create_required_admin_pages($member->class_module()."_pages"); 

    //   }
      

			 
		} // END public function plugin_activate() {



		/**
		*Called when the plugin is deactivated using register_deactivation_hook
		**/
		public function deactivate() {

			//Flush rewrite rules on plugin activation. (woocoommerce endpoint permalink)
			flush_rewrite_rules();

		}  //END public function plugin_deactivate()


	
	} // END class Activate {
		
} // END if (!class_exists('Activate')) 



