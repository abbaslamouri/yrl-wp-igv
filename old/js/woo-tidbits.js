jQuery(document).ready(function($) {

	"use strict";

// set field id and callback based on field label
var tabTitle = $(".axl-wp-ultimate input#title");
var statusTitle = $(".axl-wp-ultimate input#status_title");
var editRecord =  $("#postbox-container-2 input[name='axl-wp-ultimate-update-record']").val();

// Set single product tab id based on tab title
$(".axl-wp-ultimate").on('change', tabTitle, function(event) {
	var tabTitleVal = $(this).find("input#title").val();
	if (tabTitleVal && 0 == editRecord) {
		$(".axl-wp-ultimate input#tab_id").val(tabTitleVal.split(' ').join('-').toLowerCase());
		$(".axl-wp-ultimate input#callback").val(tabTitleVal.split(' ').join('_').toLowerCase());
	}
});

// Set order status id based on status title
$(".axl-wp-ultimate").on('change', statusTitle, function(event) {
	var statusTitleVal = $(this).find("input#status_title").val();
	if (statusTitleVal && 0 == editRecord) {
		$(".axl-wp-ultimate input#status_id").val(statusTitleVal.split(' ').join('-').toLowerCase());
	}
});

//  $( '.add_to_cart_button' ).on( 'click', function(){
//    $(this).parent("li").addClass('loading');
// });

});  // END jQuery(document).ready(function($) {
