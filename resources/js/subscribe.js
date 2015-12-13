$(document).ready(function() {

$('#phone').mask('(000) 000-0000');

$('input#confirm').on('click', function() {
	$('.checkbox').css('color', 'black');
});

$('form').submit(function(e) {
	e.preventDefault();

	if (! $('input#confirm').is(':checked')) {
		$('.checkbox').css('color', '#A52F2D');
		return false;
	}

	var $btn = $(document.activeElement);

    if (
        /* there is an activeElement at all */
        $btn.length &&

        /* it's a child of the form */ 
        $(this).has($btn) &&

        /* it's really a submit element */
        $btn.is('button[type="submit"], input[type="submit"], input[type="image"]') &&

        /* it has a "name" attribute */
        $btn.is('[name]')
    ) {
        var sender_id = $btn.attr('id');

	    if (sender_id == 'subscribe') {
			action = 'activate';
		} else if (sender_id == 'unsubscribe') {
			action = 'deactivate';
		} else {
			return false;
		}
    } else {
    	return false;
    }

	$.ajax({
		url: "manageSubscription.php",
		method: "POST",
		data: {
			name: $('#name').val(),
			email: $('#email').val(),
			phone: $('#phone').val(),
			action: action
		},		
		beforeSend: function() {
			$('#spinner-' + sender_id).fadeIn();
			$('#subscribe').prop('disabled', true);
			$('#unsubscribe').prop('disabled', true);
			hideErrorAlert();
			hideSuccess();
		},
		success: function (response) {
			if (response == 'activated' || response == 'deactivated') {
				// Success.
				showSuccess(response);
			} else {
				// Error.
			
				// Manage the errors.
				if (response == 'not a member') {
					showErrorAlert('<b>We\'re sorry, but it seems that you\'re not a registered member.</b> You will need to first become one by completing <a href="https://docs.google.com/forms/d/1iQRCE1v-SPrRKDrL1E5oRHxQvKUm1gH-tdyB-19QXNU/viewform?c=0&w=1">this form</a>.');
				} else if (response == 'not a subscriber') {
					showErrorAlert('<b>We\'re sorry, but it seems that you haven\'t subscribed before.</b> Perhaps you used another phone?');
				} else if (response == 'error_headers') {
					showErrorAlert('<b>An error occurred.</b> Please verify that you are filling all the boxes and try again.');
				} else if (response == 'error_phone') {
					showErrorAlert('<b>Please verify your phone.</b>');
				} else {
					showErrorAlert('<b>We\'re sorry, an error occurred while subscribing you.</b> Please refresh and try again. If the problem persists, please <a href="mailto:occprogrammingclub@gmail.com">contact us</a>.');
				}
			}

			//console.log(response);
		},
		error: function() {
			showErrorAlert('An error occurred while performing your request. Please refresh and try again. If the problem persists, please <a href="mailto:occprogrammingclub@gmail.com">contact us</a>.');
		},
		complete: function() {
			$('#subscribe').prop('disabled', false);
			$('#unsubscribe').prop('disabled', false);
			$('#spinner-' + sender_id).hide();
		}
	});
});

});

function showSuccess(actionPerformed) {
	var msg = '';

	if (actionPerformed == 'activated') {
		msg = '<b>Hooray!</b> Your phone number has just been subscribed. You will receive a text message before the next meeting starts.';
	} else if (actionPerformed == 'deactivated') {
		msg = '<b>You have been successfully unsubscribed!</b>';
	} else {
		msg = 'Done.';
	}

	$('#success-box').html(msg);
	$('#success-box').show();
}

function hideSuccess() {
	$('#success-box').html('');
	$('#success-box').hide();
}

function showErrorAlert(msg) {
	$('#alert-box').show();
	$('#alert-box').html(msg);
}

function hideErrorAlert() {
	$('#alert-box').hide();
	$('#alert-box').html('');
}