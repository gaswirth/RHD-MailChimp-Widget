/**
 * RHD MailChimp JS Helper
 **/
(function($) {
	var mcAction = $("rhd_mc_subscribe-1").attr("action");
	$(".mc_subscribe").attr("action", "");
	$(".mc_submit").click( function(e){
		e.preventDefault();
		mailChimpProcess($(this));
	});
})(jQuery);


function mailChimpProcess( button ) {
	var widgetID = "-" + jQuery(button).siblings(".rhd_form_mc_id").val();

	var email = jQuery("#mc_subscribe"+widgetID+" .email" ).val();
	var dataString = "email="+email;

	jQuery.ajax({
		type: "POST",
		url:  mcAction,
		data: dataString,
		error: function() {
			jQuery("#mc_error"+widgetID).fadeIn('fast');
		},
		success: function() {
			jQuery("#mc_subscribe"+widgetID+" .email").animate({ opacity: 0 });
			jQuery("#mc_thanks"+widgetID).fadeIn();
		}
	});
}