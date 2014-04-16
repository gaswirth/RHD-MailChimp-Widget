/**
 * Javascript/jQuery Scriptagementation for the custom theme for The Paper Mama. By Roundhouse Designs.
 **/
(function($) {
	
	//MailChimp process handler
	var mcAction;
	
	var mcProcess = function mailChimpProcess() {
		var email = $("#mc_subscribe #email").val();
		
		var dataString = "fname="+fname+"&lname="+lname+"&email="+email;
		
		$.ajax({
			type: "POST",
			url:  mcAction,
			data: dataString,
			beforeSend: function() {
				$("#mc_error").fadeOut();
				$("#mc_subscribe, p.subscribe").animate({
					opacity: 0
				}).delay(600);
				$("#mailchimp-widget .ajax-loader").fadeIn('fast');
			},
			complete: function() {
				$("#mailchimp-widget .ajax-loader").fadeOut('fast');
				$("#mc_subscribe #email").val('');
			},
			error: function() {
				$("#mc_subscribe").animate({
					opacity: 1
				});
				$("#mc_error").fadeIn('fast');
			},
			success: function() {
				$("#mc_thanks").fadeIn().delay(4000).fadeOut();
				$("#mc_subscribe, p.subscribe").hide().delay(4500).css('opacity',1).fadeIn();
			}
		});
		return false;
	}
	
	mcAction = $("#mc_subscribe").attr("action");
	$("#mc_subscribe").attr("action", "");
		
	$("#mc_subscribe #mc_submit").click( mcProcess );
	
})