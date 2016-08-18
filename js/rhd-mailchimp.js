/**
 * RHD MailChimp JS Helper
 **/
var mcAction;

jQuery(document).ready(function(){
	mcAction = jQuery(".rhd-mc-subscribe").first().attr("action");
	jQuery(".rhd-mc-subscribe").attr("action", "");
});


jQuery(document).on("click", ".rhd-mc-submit", function(e){
	e.preventDefault();
	mailChimpProcess(jQuery(this));
});


function mailChimpProcess( button ) {
	var instance = jQuery(button).siblings(".rhd-mc-form-id").val();

	console.log(instance);

	var fname = jQuery("#rhd-mc-fname-"+instance).val();
	var lname = jQuery("#rhd-mc-lname-"+instance).val();
	var email = jQuery("#rhd-mc-email-"+instance).val();
	var dataString = "fname="+fname+"&lname="+lname+"&email="+email;

	jQuery.ajax({
		type: "POST",
		url:  mcAction,
		data: dataString,
		error: function() {
			jQuery("#rhd-mc-error-"+instance).animate({opacity: 1});
		},
		success: function() {
			jQuery("#rhd-mc-error-"+instance).animate({opacity: 0});
			jQuery("#rhd-mc-email-"+instance).val("");
			jQuery("#rhd-mc-thanks-"+instance).animate({'opacity': 1}, 'fast');
		}
	});
}
