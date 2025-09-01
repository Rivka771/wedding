
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
var creditCardCollected = false;
// Listen for a message from the iframe.
eventer(messageEvent, function (e) {
	if (isNaN(parseInt(event.data))) {
		form = jQuery('#pmpro_form, .pmpro_form');

		console.log(event.data + ' msg: ' + event.message);
		switch (event.data.message) {
			case 'paymentreplay':
				let resp = event.data.value;
				if (resp && !isNaN(resp.InternalCode)) {
					form.append('<input type="hidden" name="InternalCode" value="' + resp.InternalCode + '" />');
					if (resp.InternalCode != 0) {
						jQuery('#pmpro_message').text(resp.InternalDescription).addClass('pmpro_error').removeClass('pmpro_alert').removeClass('pmpro_success').show();
						jQuery('.pmpro_btn-submit-checkout,.pmpro_btn-submit').removeAttr('disabled');
						jQuery('#takbull_payment_popup').modal('hide');
					} else {
						creditCardCollected = true;
						form.append('<input type="hidden" name="payment_intent_id" value="' + resp.transactionInternalId + '" />');
						form.append('<input type="hidden" name="setup_intent_id" value="' + resp.transactionInternalId + '" />');
						form.append('<input type="hidden" name="subscription_id" value="' + resp.uniqId + '" />');
						form.append('<input type="hidden" name="takbull_token" value="' + resp.CreditCard.CardExternalToken + '" />');
						form.append('<input type="hidden" name="AccountNumber" value="XXXXXXXXXXXX' + resp.CreditCard.Last4Digits + '"/>');
						form.append('<input type="hidden" name="ExpirationMonth" value="' + ('0' + resp.CreditCard.CardTokenExpirationMonth).slice(-2) + '"/>');
						form.append('<input type="hidden" name="ExpirationYear" value="' + resp.CreditCard.CardTokenExpirationYear + '"/>');
						form.get(0).submit();
					}
				}
		}
		return;
	}
	document.getElementById('wc_takbull_iframe').style.height = e.data + 'px';
}, false);


jQuery(document).ready(function ($) {	
	$('#takbull_payment_popup').on('hidden.bs.modal', function () {
		$('.pmpro_btn-submit-checkout,.pmpro_btn-submit').removeAttr('disabled');
	});
	var readyToprocess = $('#readyToProcessByTakbull').val();
	var orderCode = $('#orderCode').val();
	if (readyToprocess && orderCode) {
		$('#pmpro_message').text('Processing......').removeClass('pmpro_error').removeClass('pmpro_alert').addClass('pmpro_success');
		$('.pmpro_btn-submit-checkout,.pmpro_btn-submit').attr('disabled', 'disabled');
		if (redirectAddress) {
			$('#wc_takbull_iframe').attr('src', redirectAddress)
			$('#takbull_payment_popup').modal('show');
		} else {
			$('#pmpro_message').text("ERROR").addClass('pmpro_error').removeClass('pmpro_alert').removeClass('pmpro_success').show();
			$('.pmpro_btn-submit-checkout,.pmpro_btn-submit').removeAttr('disabled');
		}
	}

	$('.pmpro_form').submit(function (event) {		
		if (creditCardCollected == false) {
			// alert('Please fill in the billing information.');
			processTakbull();
			event.preventDefault();
		}
	});
	function processTakbull() {
		var name = $('#bfirstname').val();
		if ($('#bfirstname').length && $('#blastname').length) {
			name = jQuery.trim($('#bfirstname').val() + ' ' + $('#blastname').val());
		}
		var customer = {
			'CustomerFullName': name,
			'FirstName': $.trim($('#bfirstname').val()),
			'LastName': $.trim($('#blastname').val()),
			'Email': $('#bemail').val(),
			'PhoneNumber': $('#bphone').val()
		};
		// delatype = 6;
		const formData = $(this).serialize();
		var paymentRequest = {
			// "DealType": delatype,
			"CustomerFullName": name,
			"City": $('#bcity').val(),
			"Country": $('#bcountry').val(),
			"Customer": customer,
			"DisplayType": "iframe",
			"PostProcessMethod": 1,
			"FormData": $(".pmpro_form").serialize()
		};
		jQuery.ajax({
			url: pmproTakbullVars.data.url,
			type: "post",
			data: {
				contentType: "application/json; charset=utf-8",
				dataType: "JSON",
				action: pmproTakbullVars.data.action,
				nonce: pmproTakbullVars.data.nonce,
				req: paymentRequest,
				form_data: formData,
			},
			success: function (apiresponse) {
				if (apiresponse.success === false && apiresponse.data && apiresponse.data.message) {
					alert(apiresponse.data.message);
					$('#pmpro_message').text(apiresponse.data.message)
						.addClass('pmpro_error')
						.removeClass('pmpro_alert pmpro_success')
						.show();
					$('.pmpro_btn-submit-checkout,.pmpro_btn-submit').removeAttr('disabled');
					return;
				} else {
					if (apiresponse.data.responseCode != 0) {
						// alert(apiresponse.description);
						$('#pmpro_message').text(apiresponse.data.description).addClass('pmpro_error').removeClass('pmpro_alert').removeClass('pmpro_success').show();
						$('.pmpro_btn-submit-checkout,.pmpro_btn-submit').removeAttr('disabled');
					}
					else {
						$('#wc_takbull_iframe').attr('src', pmproTakbullVars.data.redirect_url + apiresponse.data.uniqId)
						$('#takbull_payment_popup').modal('show');
					}
				}
			},
			error: function (request, status, error) {
				$('#pmpro_message').text(request.responseText).addClass('pmpro_error').removeClass('pmpro_alert').removeClass('pmpro_success').show();
				$('.pmpro_btn-submit-checkout,.pmpro_btn-submit').removeAttr('disabled');
			}
		})
	}
});
