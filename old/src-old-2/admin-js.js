
jQuery(document).ready(function($) {

	"use strict";

   // When the form is submitted
	$(document).on('submit', '#iwpgv-dashboard form#file-upload', function(event) {

      event.preventDefault();

      // Show animation
      $("#iwpgv-dashboard .admin-ajax-loading").show();
      
      // Remove user-notice html text
      $('#iwpgv-dashboard .admin-messages').html( "" );


      //Initiate a new form
      var formData = new FormData();
      //console.log (formData);
      //var formFiles;
      //var fieldValue;
      //var fieldName;
      //var response;

      //var file_upload_submit = $("#iwpgv-file-uplod-submit").val(); // File upload submit button
      //var chart_type = $("#iwpgv #chart-type").val(); // Cahrt type

      // Set form action
      formData.append("chart-type", $("#iwpgv-dashboard #chart-type").val()); 
      formData.append("chart-notes", $("#iwpgv-dashboard #chart-notes").val());
      formData.append("created-date", $("#iwpgv-dashboard #created-date").val()); 
      formData.append("updated-date", $("#iwpgv-dashboard #updated-date").val()); 

      //formData.hello = "Hello World";
      formData.append("action",  iwpgv_file_upload_variable.ajax_action);
      //formData.hello = "world";
      formData.append("nonce", iwpgv_file_upload_variable.upload_nonce);
      formData.append("iwpgv-file-upload-submit", $("#iwpgv-dashboard #file-uplod-submit").val());
      //formData.files = files;
      //
      //var fileData = formFiles.prop('files');
         // Loop through data and create an array file[] containing all the files to be uploaded.
      //var fileCount = 0;
      // var files = new FormData();
      // $.each($(formFiles), function(i, obj) {
      //    //console.log(obj.files);
      //    $.each(obj.files, function(j,file){
      //       files[j] = (file);
      //       //fileCount += fileCount + 1;
      //    });
      // });
      var formFiles =  $('#upload-files');   // uploaded files

      $.each($(formFiles), function(i, obj) {
         $.each(obj.files,function(j,file){
            formData.append('files[' + j + ']', file);
            //fileCount += fileCount + 1;
         });
      });

       formData.append('files', formFiles);

		


		// Initiate a new form
		//var data;
		//var response;
		//var action = iwpgv_file_upload_variable.ajax_method;
		//var nonce =  iwpgv_file_upload_variable.upload_nonce;
		// var name =   $(".axl-wp-ultimate.contact-form #cf-name").val();
		// var subject =   $(".axl-wp-ultimate.contact-form #cf-subject").val();
		// var email =   $(".axl-wp-ultimate.contact-form #cf-email").val();
		// var message =   $(".axl-wp-ultimate.contact-form #cf-message").val();
		// var submit =   $(".axl-wp-ultimate.contact-form #axl_wp_ultimate_contact_form_submit").val();

		//data = {
			//'action'        : action,
			//'nonce'  : nonce,
			// 'cf-name'          : name,
			// 'cf-email'         : email,
			// 'cf-subject'       : subject,
			// 'cf-message'       : message,
			// 'axl_wp_ultimate_contact_form_submit' :submit
		//};
   // Formulate the Ajax post
      // Formulate the Ajax post
      $.ajax ({
         type: "POST",
         url: iwpgv_file_upload_variable.ajax_url, 
         data: formData,
         contentType: false,
         processData: false,
         dataType: "json",
         success: function( data, status, jqXHR ) {
            console.log(status, data);
            $('.admin-messages').html(data) ;
            
            // hide animation
            $("#iwpgv-dashboard .admin-ajax-loading").hide();         }
         });



   });
 


});  // END jQuery(document).ready(function($) {


