
jQuery(document).ready(function($) {

	"use strict";


      // add color picker functionality
      //$( '.axl-wp-ultimate-color-picker' ).wpColorPicker();

		var buttons = [
		".edd-add-to-cart",
		".wpsc_buy_button",
		".eshopbutton",
		"div.cartopt p label.update input#update",
		".add_to_cart_button",
		".woocommerce-cart input.minus",
		".cart_item a.remove",
		"#order_review .opc_cart_item a.remove",
		".woocommerce-cart input.plus",
		".single_add_to_cart_button",
		".fusion-update-cart"
	];

	var inputs = [
		"input.edd-item-quantity"
	];


	setTimeout( WPMenucart_Load_AJAX, 1000);


	jQuery(document.body).on('click', buttons.join(','), function(){
		WPMenucart_Timeout();
	});

	jQuery(document.body).on('change', inputs.join(','), function(){
		WPMenucart_Timeout();
	});
		
	function WPMenucart_Timeout() {
		setTimeout( WPMenucart_Load_AJAX, 1000);
	}


	function WPMenucart_Load_AJAX() {

		//Initiate a new form
		var data = {};

		// Set form action
		data.action =  axl_wp_ultimate_menu_cart_object.ajax_action;

		// Set form ajax nonce
		data.security =  axl_wp_ultimate_menu_cart_object.ajax_nonce;

		// Formulate the Ajax post
		$.post(
			axl_wp_ultimate_menu_cart_object.ajax_url, 
			data, 
			function( response ) {
				$('.axl-wp-ultimate-menu-cart-ajax').html( response );
			}
		);

	}
 


});  // END jQuery(document).ready(function($) {
