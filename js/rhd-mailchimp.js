/**
 * RHD MailChimp JS Helper
 **/

(function($){
	$(document).on("click", ".rhd-mc-submit", function(e){
		e.preventDefault();
		mailChimpProcess( $(this) );
	});


	function mailChimpProcess( button ) {
		var instance = $(button).siblings(".rhd-mc-form-id").val();

		var fname = $("#rhd-mc-fname-"+instance).val();
		var lname = $("#rhd-mc-lname-"+instance).val();
		var email = $("#rhd-mc-email-"+instance).val();
		var listID = $("#rhd-mc-list-id-"+instance).val();

		var data = {
			"email" : email,
			"fname" : fname,
			"lname" : lname,
			"list_id" : listID
		};

		$.ajax({
			type: "POST",
			url: rhd_mc_ajax.url,
			data: {
				data: data,
				action: 'rhd_mc_submit'
			},
			error: function() {
				$("#rhd-mc-error-"+instance).animate({opacity: 1});
			},
			success: function() {
				$("#rhd-mc-error-"+instance).animate({opacity: 0});
				$("#rhd-mc-email-"+instance).val("");
				$("#rhd-mc-thanks-"+instance).animate({'opacity': 1}, 'fast');
			}
		});
	}
})(jQuery);