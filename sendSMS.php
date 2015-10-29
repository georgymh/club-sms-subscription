<?php
require_once('.../twilio-php-master/Services/Twilio.php'); // Loads twilio helper library

function sendSMS($listOfPhones){
 	//set API token vars
	$account_sid = 'ACCOUNT SID HERE';
	//$auth_token = 'AUTH TOKEN HERE';
	$client = new Services_Twilio($account_sid, $auth_token);

 	foreach ($listOfPhones as $member)
 	{
		$name = $member["name"];
		$phone = $member["phone"];
 		if (checkIfValidPhone($phone))
 		{
	 		//use helper library to send message
			// $client->account->messages->create(array(
			 	//'To' => '+' . $phone,
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
$clubMemberList = array(
    array(
        'name' => 'Georgy',
        'phone' => '7140000000',
        'extraInfoInTheFuture' => 'bla bla'
    ),
    array(
        'name' => 'Bryan',
        'phone' => '7140000001',
        'extraInfoInTheFuture' => 'bla bla'
    )
	)
);

sendSMS($listOfPeople);
?>
