
jQuery(document).ready(function($) {

	"use strict";



	// When the form is submitted
	$(document).on('click', '#axl_wp_ultimate_contact_form_submit', function(event) {

		event.preventDefault();

		// Show animation
		$(".axl-wp-ultimate.contact-form .ajax-loading").show();
		
		// Remove user-notice html text
		$('.axl-wp-ultimate.contact-form .user-notice').html( "" );

		//Initiate a new form
		var data = {};
		var fieldValue;
		var fieldName;
		var response;

		// Set form action
		data.action =  axl_wp_ultimate_contact_form_object.ajax_action;


		//Process all radio buttons
		$.each($(".axl-wp-ultimate.contact-form input:radio"), function(key, value) {
			if($(this).is(':checked')) {
				data[$(this).attr('name')] = $(this).val();
				return false;
			}
		});

		//Process all checkboxes
		$.each($(".axl-wp-ultimate.contact-form input:checkbox"), function(key, value) {
			if( $(this).is(':checked') ) data[$(this).attr('name')] = $(this).val();

		});

		// Process all other input fields
		$.each($(".axl-wp-ultimate.contact-form input, .axl-wp-ultimate.contact-form select, .axl-wp-ultimate.contact-form textarea"), function(key, value) {
			if( $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio' ) 
				data[$(this).attr('name')] = $(this).val();

		});


		// Formulate the Ajax post
		$.post(
			axl_wp_ultimate_contact_form_object.ajax_url, 
			data, 
			function( response ) {
				$('.axl-wp-ultimate.contact-form .user-notice').html(response) ;
				$(".axl-wp-ultimate.contact-form .ajax-loading").hide();
				}
		);

	});

	

});  // END jQuery(document).ready(function($) {
