
jQuery(document).ready(function($) {

	"use strict";

	// Hide recaptch site and secreet keys if recapcha is disabled
	if ( ! $("input#recaptcha_enable").is(":checked") ){
		$("tr#recaptcha_site_key").hide();
		$("tr#recaptcha_secret_key").hide();
	} 

	// Show or hide field options depending on the type of field type selected
	var fieldType = $('#axl_wp_ultimate_member select#field_type');  
	var fieldOptions = $('#axl_wp_ultimate_member tr#field_options');

	
	if (fieldType.val() == 'select'  || fieldType.val() == 'radio') {
		fieldOptions.show();
	} else {
		fieldOptions.hide();
	}

	// Show or hide field options when field type changes
	$(fieldType).change(function(event) { 
		if ( $(this).val() == 'select' || $(this).val() == 'radio' ) {
			$(fieldOptions).show();
			$(fieldOptions).find('input').attr('required', true);
		} else {
			$(fieldOptions).hide();
			$(fieldOptions).find('input').attr('required', false);
		}
	});




	

});  // END jQuery(document).ready(function($) {
