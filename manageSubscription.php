<?php

require 'vendor/autoload.php';
require 'GoogleAutoToken.php';

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

if ( isset($_POST['action']) && !empty($_POST['action']) ) {

	if ( !isset($_POST['name']) || empty($_POST['name']) ||
		 !isset($_POST['email']) || empty($_POST['email']) ||
		 !isset($_POST['phone']) || empty($_POST['phone']) ) {

		echo "error_headers";
		exit();

	} else {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$action = $_POST['action'];
	}

} else {
	echo "error_headers";
	exit();
}

// Validate and Clean Phone.
use Respect\Validation\Validator as v;
if ( v::phone()->validate($phone) ) {
	$phone = str_replace(' ', '', $phone);
	$phone = preg_replace('/[^\p{L}\p{N}\s]/u', '', $phone);
	$phone = "+1" . $phone;
} else {
	echo "error_phone";
	exit();
}

/**
*	SET UP
*/

$accessToken = GoogleAutoToken::getAccessToken();

$serviceRequest = new DefaultServiceRequest($accessToken);
ServiceRequestFactory::setInstance($serviceRequest);
$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();

// Members Spreadsheets.
$member_spreadsheet = $spreadsheetFeed->getByTitle('Responses for New Club Member Registration Form ');
$member_worksheetFeed = $member_spreadsheet->getWorksheets();
$member_worksheet = $member_worksheetFeed->getByTitle('Form Responses 1');
$member_listFeed = $member_worksheet->getListFeed();

// SMS Subscribed Spreadsheets.
$sms_spreadsheet = $spreadsheetFeed->getByTitle('SMS-Subscribed');
$sms_worksheetFeed = $sms_spreadsheet->getWorksheets();
$sms_worksheet = $sms_worksheetFeed->getByTitle('Sheet1');
$sms_listFeed = $sms_worksheet->getListFeed();

/**
*	BODY
*/

if ($action == 'activate') {
	// ACTIVATE SMS SUBSCRIPTION.
	if ( checkMemberStatus($member_listFeed, $email) ) {
		// Activate the member's SMS Subscription.
		addPhoneToEntry($sms_listFeed, $name, $email, $phone);
	
		echo 'activated';
	} else {
		echo 'not a member';
	}
} elseif ($action == 'deactivate') {
	// DEACTIVATE SMS SUBSCRIPTION.
	deactivateSMS($sms_listFeed, $email, $phone);
	echo 'deactivated';
}

/**
*	HELPER METHODS
*/

function checkMemberStatus($listFeed, $email) {
	foreach ($listFeed->getEntries() as $entry) {
	    $values = $entry->getValues();

	    if ($values["emailaddress"] == $email) {
	    	return true;
	    }
	}

	return false;
}

function addPhoneToEntry($listFeed, $name, $email, $phone) {
	// To prevent duplicates, check if user already had subscribed.
	// NOTE: A duplicate currently means same email AND same phone.
	$entryToEdit = findMemberSubscription($listFeed, $email, $phone);

	// Subscribe the user.
	if ($entryToEdit) {
		// Activate the SMS Service, and that's it.
	    $values = $entryToEdit->getValues();
	    $values["activated"] = '1';
	    $entryToEdit->update($values);
	} else {
		// Create a new row.
		$row = array(
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'sendmessageat' => '9:30',
			'minutesuntilmeeting' => '60',
			'activated' => '1'
		);

		$listFeed->insert($row);
	}
}

function deactivateSMS($listFeed, $email, $phone) {
	// To prevent duplicates, check if user already had subscribed.
	$entryToEdit = findMemberSubscription($listFeed, $email, $phone);

	if ($entryToEdit) {
		// Deactivate the SMS Service.
	    $values = $entryToEdit->getValues();
	    $values["activated"] = '0';
	    $entryToEdit->update($values);
	}
}

function findMemberSubscription($listFeed, $email, $phone) {
	$entryToEdit = null;
	foreach ($listFeed->getEntries() as $entry) {
	    $values = $entry->getValues();

	    if ($values["email"] == $email && $values["phone"] == $phone) {
	    	$entryToEdit = $entry;
	    	break;
	    }
	}

	return $entryToEdit; // can be null.
}

?>
