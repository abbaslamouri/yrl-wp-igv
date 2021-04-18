jQuery(document).ready(function($) {

	"use strict";

   // retreive plugin page template id
   var dashboardTemplateId = iwpgv_file_upload_object.dashboard_template_id;

   // retreive plugin name
   var plugin = iwpgv_file_upload_object.plugin;

   // When the file upload form is submitted
	$(document).on("submit", "#" + dashboardTemplateId + " form#file-upload", function(event) {

      event.preventDefault();

      // Show animation
      $("#" + dashboardTemplateId + " .admin-ajax-loading").show();
      
      // Remove user-notice html text
      $("#" + dashboardTemplateId + " .admin-messages").html( "" );

      


      //Initiate a new form
      var formData = new FormData();

      // Retreive form inputs
      formData.append("chart-type", $("#" + dashboardTemplateId + " #chart-type").val()); 
      formData.append("chart-notes", $("#" + dashboardTemplateId + " #chart-notes").val());
      formData.append("created-date", $("#" + dashboardTemplateId + " #created-date").val()); 
      formData.append("updated-date", $("#" + dashboardTemplateId + " #updated-date").val()); 
      formData.append("file-upload-submit", $("#" + dashboardTemplateId + " #file-upload-submit").val()); 

      // Append action hook to the form
      formData.append("action",  iwpgv_file_upload_object.action);
      
      // Append nonce to the form
      formData.append("nonce", iwpgv_file_upload_object.nonce);

      //Retreive files
      var formFiles =  $("#" + dashboardTemplateId + " input#file-upload");   // uploaded files
      $.each($(formFiles), function(i, obj) {
         $.each(obj.files,function(j,file){
            formData.append('files[' + j + ']', file);
         });
      });
      formData.append('files', formFiles);

      $.ajax ({
         type: "POST",
         url: iwpgv_file_upload_object.url, 
         data: formData,
         contentType: false,
         processData: false,
         //dataType: "json",
         success: function(response, status, jqXHR) {
         console.log("successxxxxx");
         //console.log(response);
         //console.log(status);
         //console.log(jqXHR);

            if (jqXHR.readyState === 4 && status === "success" && jqXHR.status === 200){

               // Parse json
               var allData = JSON.parse(response);
               console.log(allData);

                // Display success message
               if (!! allData["wp-success"]){
                  console.log(allData["wp-success"]);
                  console.log("#" + dashboardTemplateId + " .admin-messages");
                  $("#" + dashboardTemplateId + " .admin-messages").append(allData["wp-success"]) ;
                  $("#" + dashboardTemplateId + " .admin-ajax-loading").hide();
               }

               // Display error message
               if (!! allData["wp-error"]){
                  console.log(allData["wp-error"]);
                  console.log("#" + dashboardTemplateId + " .admin-messages");
                  $("#" + dashboardTemplateId + " .admin-messages").append(allData["wp-error"]) ;
                  $("#" + dashboardTemplateId + " .admin-ajax-loading").hide();
               }

            }

         },
         error: function (jqXHR, response, error) {
            console.log("errorxxxxx");
            console.log(response);
            console.log(error);
            console.log(jqXHR);

            if (jqXHR.readyState === 4 && response === "error"){
               var output = "<div class='notice notice-error is-dismissible'>Status: " + jqXHR.status + ",   Error: PHP " + error + "</div>" ;
               $("#" + dashboardTemplateId + " .admin-messages").html(output);
            }
         }

      });

   });
 

});  // END jQuery(document).ready(function($) {


