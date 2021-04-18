jQuery(document).ready(function($) {

  "use strict";

  $.wpMediaUploader({
    target : '.image-uploader', // The class wrapping the textbox
    uploaderTitle : 'Select or upload image', // The title of the media upload popup
    uploaderButton : 'Set image', // the text of the button in the media upload popup
    multiple : false, // Allow the user to select multiple images
    buttonText : 'Upload image', // The text of the upload button
    buttonClass : '.image-upload', // the class of the upload button
    previewSize : '150px', // The preview image size
    modal : false, // is the upload button within a bootstrap modal ?
    buttonStyle : { // style the button
        // color : '#fff',
        // background : '#3bafda',
        // fontSize : '16px',                
        // padding : '10px 15px',
        // decoration : 'none',                
    },
  });

  // Load the color picker functionality
  $( '.axl-wp-ultimate-color-picker' ).wpColorPicker();

  // Add image under media uploader field Add button classes to the upload button
  $(".axl-wp-ultimate .image-upload").addClass('button button-primary');
  var imgSrc = $(".axl-wp-ultimate input.default-image").val();
  if (imgSrc ) {
    $(".axl-wp-ultimate img").attr('src', imgSrc).css('display', 'block'); 
  }


  // When the add-new-field button is clicked, hide button and show settings fields meta box
  //$(".axl_wp_ultimate-0").toggle();
  var buttonText = $(".axl-wp-ultimate #add-new-field").val();
  $(document).on('click', '.axl-wp-ultimate #add-new-field', function(event) {
    if ($(this).val() == "Close Form") {
      $(this).val(buttonText);
    } else {
      $(this).val("Close Form");
    }
    $(".axl_wp_ultimate-0").toggle();
  });




  //  $(document).on('click', '.axl-wp-ultimate #start-new-field', function(event) {
  //   $(this).hide();
  //   // if ($(this).val() == "Close Form") {
  //   //   $(this).val(buttonText);
  //   // } else {
  //   //   $(this).val("Close Form");
  //   // }
  //   $(".axl_wp_ultimate-1").removeClass("axl_wp_ultimate-1").addClass("axl_wp_ultimate-0").show();
  //   $(".axl_wp_ultimate-0").find("input, radio, select, textarea, checkbox").each(function (index) {
  //     $(this).show().attr("readonly", false);

  //     alert($(this).attr("type"));


  //    // .val(null).attr("readonly", false);

  //   });
  // });




  // Set childfield based on the value of the parent field
  var parentField = $(".axl_wp_ultimate-0 input.parent-field");
  $(".axl_wp_ultimate-0").on('change', parentField, function(event) {
    var parentFieldVal = parentField.val();
    $(".axl_wp_ultimate-0 input.child-field").val(parentFieldVal.split(' ').join('-').toLowerCase());
  });



  // Display a selecct/radio options fields when a select or radio button nearby is selected)
  var SelectRadioType = $('.axl-wp-ultimate select.select-radio-type');  
  var fieldOptions = $('.axl-wp-ultimate input.field-options');
  fieldOptions.parents("tr").hide();
  $(SelectRadioType).change(function(event) {
    if ( $(this).val() == 'select' || $(this).val() == 'radio' ) {
      $(fieldOptions).parents("tr").show();
      $(fieldOptions).attr('required', true);
    } else {
      $(fieldOptions).parents("tr").hide();
      $(fieldOptions).attr('required', false);
    }
  });


  //  // Show or hide field options depending on field type in admin form fields
  // var SelectRadioType = $('.axl-wp-ultimate select#field_type');  
  // var fieldOptions = $('.axl-wp-ultimate tr#field_options');
  // if (fieldType.val() == 'select'  || fieldType.val() == 'radio') {
  //   fieldOptions.show();
  // } else {
  //   fieldOptions.hide();
  // }
  // $(fieldType).change(function(event) { 
  //   if ( $(this).val() == 'select' || $(this).val() == 'radio' ) {
  //     $(fieldOptions).show();
  //     $(fieldOptions).find('input').attr('required', true);
  //   } else {
  //     $(fieldOptions).hide();
  //     $(fieldOptions).find('input').attr('required', false);
  //   }
  // });



  // Perform field reordering 
  $(".axl-wp-ultimate table.list").sortable({
    items: ".list-row",  // table row class (make sure the rows have unique ID's such as rows_{$position})
    opacity: 0.6,
    cursor: "move",
    axis: "y",
    update: function (){

      var listTable = $(this);
      // Change table opacity for visual effect
      listTable.css("opacity", ".2");

      // Retreive data form the rows (the serialixed data is returnes as an array of new list orders )
      var data = $(this).sortable('serialize') + "&action=" + axl_wp_ultimate_admin_object.ajax_action + "&tab=" + axl_wp_ultimate_admin_object.ajax_tab ;
      $.post(
        axl_wp_ultimate_admin_object.ajax_url, 
        data, 
        function( response ) {
          //alert(response);
        
          // reset opacity to noraml on success
          listTable.css("opacity", "1");   
          }
      );


    }

  });



  // Dialog box display for list tables
  $(".dialog-opener").each ( function (index) {
   
    var i = index + 1;
    var dialog_id = $("#dialog-" + i);
    var opener_id = $("#opener-" + i);

     $(dialog_id).dialog({
      position: { my: "center top", at: "center bottom", of: $(opener_id)},
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
    

    $(opener_id ).on( "click", function(event) {
      event.preventDefault();
      $(dialog_id).dialog( "open" );
    });

  });
        // var i;
        // for (i = 1; i < 2; i++) { 
        //   var dialog_id = $("#dialog-" + i);
        //   var opener_id = $("#opener-" + i) ;
        // $(dialog_id).dialog({
        //   autoOpen: false,
        //   show: {
        //     effect: "blind",
        //     duration: 1000
        //   },
        //   hide: {
        //     effect: "explode",
        //     duration: 1000
        //   }
        // });
        // }
 
        // $(opener_id ).on( "click", function() {
        //   $(dialog_id).dialog( "open" );
        // });

  

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





  $( "#accordion" )
      .accordion({
        header: "> div > h3"
      })
      .sortable({
        axis: "y",
        handle: "h3",
        stop: function( event, ui ) {
          // IE doesn't register the blur when sorting
          // so trigger focusout handlers to remove .ui-state-focus
          ui.item.children( "h3" ).triggerHandler( "focusout" );
 
          // Refresh accordion to handle new order
          $( this ).accordion( "refresh" );
        }
      });



      


});  // END jQuery(document).ready(function($) {
