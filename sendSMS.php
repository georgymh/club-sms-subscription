<?php
require_once('vendors/twilio-php-master/Services/Twilio.php'); // Loads twilio helper library

function sendSMS($listOfPhones){
 	//set API token vars
	$account_sid = "12345";
	$auth_token = "12345";
	$client = new Services_Twilio($account_sid, $auth_token);

 	foreach ($listOfPhones as $member)
 	{
		$name = $member["name"];
		$phone = $member["phone"];
 		if (checkIfValidPhone($phone))
 		{
	 		//use helper library to send message
			$client->account->messages->create(array(
			 	"To" => '+' . $phone,
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
	return $name . ", this is a reminder that pogramming club begins in 15 minutes.";
}
?>