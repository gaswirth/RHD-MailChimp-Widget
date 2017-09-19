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

		var listID = $("#rhd-mc-list-id-"+instance).val();
		var fname = ( $("#rhd-mc-fname-"+instance).val().length ) ? $("#rhd-mc-fname-"+instance).val() : null;
		var lname = ( $("#rhd-mc-lname-"+instance).val().length ) ? $("#rhd-mc-lname-"+instance).val() : null;
		var email = ( $("#rhd-mc-email-"+instance).val().length ) ? $("#rhd-mc-email-"+instance).val() : null;

		var data = {
			"email" : email,
			"fname" : fname,
			"lname" : lname,
			"list_id" : listID
		};

		if ( isEmail( email ) ) {
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
					$("#rhd-mc-email-"+instance).val("").animate({backgroundColor: $("#rhd-mc-email-"+instance).data("bg")});
					//$("#rhd-mc-thanks-"+instance).animate({'opacity': 1}, 'fast');
				}
			});
		} else {
			$("#rhd-mc-error-"+instance).animate({opacity: 1});
			$("#rhd-mc-email-"+instance)
				.data("bg", $("#rhd-mc-email-"+instance).css("backgroundColor"))
				.animate({backgroundColor: "rgba(255, 255, 178, 1)"});
		}
	}

	function isEmail( email ) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
})(jQuery);