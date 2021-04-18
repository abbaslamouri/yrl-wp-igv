jQuery(document).ready(function($) {

	"use strict";

	// When the form is submitted
	$(document).on('submit', 'form#iwpgv-file-uplod', function(event) {

		event.preventDefault();

		// Show animation
		$(".axl-wp-ultimate.contact-form .ajax-loading").show();
		
		// Remove user-notice html text
		$('.axl-wp-ultimate.contact-form .user-notice').html( "" );

		//Initiate a new form
		var formData = [];
		//var formFiles;
		var fieldValue;
		var fieldName;
		var response;

		var formFiles =  $('#upload-files');	// uploaded files
			// Loop through data and create an array file[] containing all the files to be uploaded.
		//var fileCount = 0;
		var files = [];
		$.each($(formFiles), function(i, obj) {
			//console.log(obj.files);
			$.each(obj.files, function(j,file){
				files.push(file);
				//fileCount += fileCount + 1;
			});
		});

		console.log(files);

		// Set form action
		formData.action =  iwpgv_file_upload_variable.ajax_method;
		formData["hello"] = "world";
		formData["nonce"] = iwpgv_file_upload_variable.upload_nonce;
		formData.files = files;
		console.log(formData);



		//Process all radio buttons
		$.each($(".axl-wp-ultimate.contact-form input:radio"), function(key, value) {
			if($(this).is(':checked')) {
				formData[$(this).attr('name')] = $(this).val();
				return false;
			}
		});

		//Process all checkboxes
		$.each($(".axl-wp-ultimate.contact-form input:checkbox"), function(key, value) {
			if( $(this).is(':checked') ) formData[$(this).attr('name')] = $(this).val();

		});

		// Process all other input fields
		$.each($(".axl-wp-ultimate.contact-form input, .axl-wp-ultimate.contact-form select, .axl-wp-ultimate.contact-form textarea"), function(key, value) {
			if( $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio' ) 
				formData[$(this).attr('name')] = $(this).val();

		});


		// Formulate the Ajax post
		$.post(
			iwpgv_file_upload_variable.ajax_url, 
			formData, 
			function( response ) {
				console.log(response);
				$('#response').html(response) ;
				$(".axl-wp-ultimate.contact-form .ajax-loading").hide();
				}
		);

	});

	

});  // END jQuery(document).ready(function($) {
