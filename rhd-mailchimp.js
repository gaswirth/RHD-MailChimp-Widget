/**
 * RHD MailChimp JS Helper
 **/
(function($) {
	var mcAction = $("rhd_mc_subscribe-1").attr("action");
	$(".rhd_mc_subscribe").attr("action", "");
	$(".rhd_mc_submit").click( function(e){
		e.preventDefault();
		mailChimpProcess($(this));
	});
})(jQuery);


function mailChimpProcess( button ) {
	var instance = "-" + jQuery(button).siblings(".rhd_form_mc_id").val();

	var fname = jQuery("#rhd_mc_fname"+instance ).val();
	var lname = jQuery("#rhd_mc_lname"+instance ).val();
	var email = jQuery("#rhd_mc_email"+instance ).val();
	var dataString = "fname="+fname+"&lname="+lname+"&email="+email;

	jQuery.ajax({
		type: "POST",
		url:  mcAction,
		data: dataString,
		error: function() {
			jQuery("#rhd_mc_error"+instance).fadeIn('fast');
		},
		success: function() {
			jQuery("#rhd_mc_subscribe"+instance+" .email").animate({ opacity: 0 });
			jQuery("#rhd_mc_thanks"+instance).fadeIn();
		}
	});
}