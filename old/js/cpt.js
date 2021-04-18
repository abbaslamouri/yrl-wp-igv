jQuery(document).ready(function($) {

	"use strict";

	// set field id and callback based on field label
	var cptPlural = $(".axl-wp-ultimate input#cpt_plural");
	var editRecord =  $("#postbox-container-2 input[name='axl-wp-ultimate-update-record']").val();
    $(".axl-wp-ultimate").on('change', cptPlural, function(event) {
		  var cptPluralVal = $(this).find("input#cpt_plural").val();
		    if (cptPluralVal && 0 == editRecord) {
          $(".axl-wp-ultimate input#cpt_slug").val(cptPluralVal.split(' ').join('-').toLowerCase());
        }
	 });

//  $( '.add_to_cart_button' ).on( 'click', function(){
//    $(this).parent("li").addClass('loading');
// });

});  // END jQuery(document).ready(function($) {
