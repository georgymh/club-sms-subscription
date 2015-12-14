<?php

require '../../vendor/autoload.php';
use Respect\Validation\Validator as v;

require '../../lib/Subscription.php';

// 1. Check the HTTP headers and assign data variables.
if (
    validate_parameter("action") &&
    validate_parameter("name") &&
    validate_parameter("email") &&
    validate_parameter("phone")
) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $action = $_POST['action'];
} else {
    die("error_headers");
}

// 2. Validate and clean phone number.
if ( v::phone()->validate($phone) ) {
	$phone = str_replace(' ', '', $phone);
	$phone = preg_replace('/[^\p{L}\p{N}\s]/u', '', $phone);
	$phone = "1" . $phone; // append 1 before phone
} else {
	die("error_phone");
}

// 3. Run the necessary subscription routines and return their status.
$subscription = new Subscription();

if (!$subscription->memberExists($email)) {
    echo "not a member";
}

if ($action == "activate") {
    $subscription->activateSubscriber($name, $email, $phone);
    echo "activated";
} elseif ($action == "deactivate") {
    if ($subscription->deactivateSubscriber($email, $phone)) {
        echo "deactivated";
    } else {
        echo "not a subscriber";
    }
}

/**
 *  Checks if POST request header is set and non-empty.
 *  @return boolean
 */
function validate_parameter($param_name)
{
  return (isset($_POST[$param_name]) && !empty($_POST[$param_name]));
}

?>
