<?php

require 'vendor/autoload.php';

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;


if ( isset($_POST['action']) && !empty($_POST['action']) ) {

	if ( !isset($_POST['name']) || empty($_POST['name']) ||
		 !isset($_POST['email']) || empty($_POST['email']) ||
		 !isset($_POST['phone']) || empty($_POST['phone']) ) {

		echo "error_headers";
		exit();

	} else {
		$actionType = $_POST['action'];
	}

} else {
	echo "error_headers";
	exit();
}

/**
*	SET UP
*/

$accessToken = "ACCESS_TOKEN_HERE";
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
		// Add them to the SMS list.
		addPhoneToEntry($sms_listFeed, $name, $email, $phone);
		echo 'activated';
	} else {
		echo 'not a member';
	}
} else {
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
	$userExisted = false;
	$entryToEdit = null;
	foreach ($listFeed->getEntries() as $entry) {
	    $values = $entry->getValues();

	    if ($values["email"] == $email) {
	    	$entryToEdit = $entry;

	    	if (! $values["activated"]) {
	    		$userExisted = true;
	    		break;
	    	}
	    }
	}

	// Subscribe the user.
	if ($userExisted) {
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
	$entryToEdit = null;
	foreach ($listFeed->getEntries() as $entry) {
	    $values = $entry->getValues();

	    if ($values["email"] == $email && $values["phone"] == $phone) {
	    	$entryToEdit = $entry;
	    	break;
	    }
	}

	if ($entryToEdit) {
		// Deactivate the SMS Service.
	    $values = $entryToEdit->getValues();
	    $values["activated"] = '0';
	    $entryToEdit->update($values);
	}
}

?>