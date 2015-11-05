<?php

// Load Twilio Library
require_once('vendors/twilio-php-master/Services/Twilio.php'); 

function sendSMS($listOfPhones){
 	// Set API token variables.
	$account_sid = "TWILIO_ACCOUNT_SID_HERE";
	$auth_token = "TWILIO_AUTH_TOKEN_HERE";
	$client = new Services_Twilio($account_sid, $auth_token);

	// Send SMS to each phone number.
 	foreach ($listOfPhones as $member)
 	{
		$name = $member["name"];
		$phone = $member["phone"];
 		if (checkIfValidPhone($phone))
 		{
	 		//use helper library to send message
			$client->account->messages->create(array(
			 	"To" => "+" . $phone,
			 	"From" => "+14243226078",
			 	"Body" => createMessage($name),
			));
		}
	}
}

function checkIfValidPhone($phone)
{
	return (preg_match("/^[0-9]{11}$/", $phone));
}

function createMessage($name)
{
	return "Hey $name! This is a reminder that the OCC Programming Club meeting will start in 60 minutes.";
}

?>