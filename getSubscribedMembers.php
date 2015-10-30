<?php

require 'vendor/autoload.php';
require 'GoogleAutoToken.php';

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

// Set up.
$accessToken = GoogleAutoToken::getAccessToken();
$serviceRequest = new DefaultServiceRequest($accessToken);
ServiceRequestFactory::setInstance($serviceRequest);
$spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();

// SMS Subscribed Spreadsheets.
$sms_spreadsheet = $spreadsheetFeed->getByTitle('SMS-Subscribed');
$sms_worksheetFeed = $sms_spreadsheet->getWorksheets();
$sms_worksheet = $sms_worksheetFeed->getByTitle('Sheet1');
$sms_listFeed = $sms_worksheet->getListFeed();

$subscribersList = array();

foreach ($sms_listFeed->getEntries() as $entry) {
    $values = $entry->getValues();

    if ( $values['activated'] == 1 ) {
    	$subscribersList[] = array(
    		'name' => $values["name"],
    		'phone' => $values["phone"]
    	);
    }
   
}

// var_dump( $subscribersList );

// $subscribersList now containes all subscribers.
// could prevent duplicate phones here.

?>