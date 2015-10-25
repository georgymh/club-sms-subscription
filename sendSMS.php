<?php
require_once('/home/occprogr/public_html/vendors/twilio-php-master/Services/Twilio.php'); // Loads twilio helper library

function sendSMS($listOfPhones){
 	//set API token vars
	$account_sid = 'ACc7ac0e71cf8da9cf5a53ad9af9866e96';
	//$auth_token = 'c6c816f5c5203e618205cbb0f25d5435';
	$client = new Services_Twilio($account_sid, $auth_token);

 	foreach ($listOfPhones as $name => $phone)
 	{
 		if (checkIfValidPhone($phone))
 		{
	 		//use helper library to send message
			// $client->account->messages->create(array(
			 	//'To' => + . $phone,
			 	//'From' => "+14243226078",
			 	//'Body' => createMessage($name),
			//));


			echo "TESTING: sending message: " . createMessage($name) . " to " . "+" . $phone;
		}
		else {
			echo "TESTING: Phone number invalid";
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



//test array and function call
$listOfPeople = array(
	'Bryan' => '17146558300'
	);

sendSMS($listOfPeople);
?>
