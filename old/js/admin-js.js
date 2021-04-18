jQuery(document).ready(function($) {

  "use strict";

$(".axl-wp-ultimate table.list").sortable({
  items: ".list-row",
  opacity: 0.6,
  cursor: "move",
  axis: "y",
  containment: "parent",
  update: function (){
    // Show animation

    var data = $(this).sortable('serialize') + "&action=" + axl_wp_ultimate_admin_object.ajax_action + "&tab=" + axl_wp_ultimate_admin_object.ajax_tab ;
    //var data = {};

    // Set form action
    //data.action =  axl_wp_ultimate_admin_contact_form_object.ajax_action;
    //data.order = order;

      // Formulate the Ajax post
    $.post(
      axl_wp_ultimate_admin_object.ajax_url, 
      data, 
      function( response ) {
        //alert(response);
        $(".admin-header").html(response);
        
        }
    );


  }

});

  

  // // set cpt slug based on plural label
  // var cptPlural = $(".axl-wp-ultimate input#cpt_plural");
  // // When the form is submitted
  // $(".axl-wp-ultimate").on('change', cptPlural, function(event) {
  //   console.log($(this).find("input#cpt_plural").val());
  //   var cptPluralVal = $(this).find("input#cpt_plural").val();
  //    if (cptPluralVal) {
  //     $(".axl-wp-ultimate input#cpt_slug").val(cptPluralVal.split(' ').join('-').toLowerCase());
  //   }
  // });

  // // set cpt slug based on plural label for contact form cpt
  // var cfcptPlural = $(".axl-wp-ultimate input#cf_cpt_plural");
  // // When the form is submitted
  // $(".axl-wp-ultimate").on('change', cfcptPlural, function(event) {
  //   console.log($(this).find("input#cpt_plural").val());
  //   var cfcptPluralVal = $(this).find("input#cf_cpt_plural").val();
  //    if (cfcptPluralVal) {
  //     $(".axl-wp-ultimate input#cf_cpt_slug").val(cfcptPluralVal.split(' ').join('-').toLowerCase());
  //   }
  // });


});  // END jQuery(document).ready(function($) {
