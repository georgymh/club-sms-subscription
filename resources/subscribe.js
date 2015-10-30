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

	$.ajax({
		url: "addMember.php",
		method: "POST",
		data: {
			name: $('#name').val(),
			email: $('#email').val(),
			phone: $('#phone').val(),
			action: 'activate'
		},		
		beforeSend: function() {
			$('#spinner').fadeIn();
			$('#subscribe').prop('disabled', true);
			hideErrorAlert();
			hideSuccess();
		},
		success: function (response) {
			if (response == 'activated') {
				// Success.
				showSuccess();
				$('#subscribe').prop('disabled', false);
				$('#spinner').hide();
			} else {
				// Error.
				$('#subscribe').prop('disabled', false);
				$('#spinner').hide();

				// Manage the errors.
				if (response == 'not a member') {
					showErrorAlert('<b>We\'re sorry, but it seems that you\'re not a registered member.</b> You will need to first become one by completing <a href="https://docs.google.com/forms/d/1iQRCE1v-SPrRKDrL1E5oRHxQvKUm1gH-tdyB-19QXNU/viewform?c=0&w=1">this form</a>.');
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
			showErrorAlert('An error occurred while subscribing you. Please refresh and try again. If the problem persists, please <a href="mailto:occprogrammingclub@gmail.com">contact us</a>.');
			$('#subscribe').prop('disabled', false);
			$('#spinner').hide();
		}
	});
});

});

function showSuccess() {
	$('#success-box').html('<b>Hooray!</b> Your phone number has just been subscribed. You will receive a text message before the next meeting starts.')
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