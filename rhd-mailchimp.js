/**
 * RHD MailChimp JS Helper
 **/
var mcAction;

(function($) {
	mcAction = $(".rhd-mc-subscribe").first().attr("action");
	$(".rhd-mc-subscribe").attr("action", "");
	$(".rhd-mc-submit").click( function(e){
		e.preventDefault();
		mailChimpProcess($(this));
	});
})(jQuery);


function mailChimpProcess( button ) {
	var instance = "-" + jQuery(button).siblings(".rhd-mc-form-id").val();

	var fname = jQuery("#rhd-mc-fname"+instance ).val();
	var lname = jQuery("#rhd-mc-lname"+instance ).val();
	var email = jQuery("#rhd-mc-email"+instance ).val();
	var dataString = "fname="+fname+"&lname="+lname+"&email="+email;

	jQuery.ajax({
		type: "POST",
		url:  mcAction,
		data: dataString,
		error: function() {
			jQuery("#rhd-mc-error"+instance).fadeIn('fast');
		},
		success: function() {
			jQuery("#rhd-mc-subscribe"+instance+" .email").animate({'opacity': 0 });
			jQuery("#rhd-mc-thanks"+instance).animate({'opacity': 1}, 'fast');
		}
	});
}
