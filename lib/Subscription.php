<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/GoogleAutoToken.php';

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

class Subscription {

    function __construct()
    {
        // Get Access Token.
        $accessToken = GoogleAutoToken::getAccessToken();

        // Set up SpreadSheets Service.
        $serviceRequest = new DefaultServiceRequest($accessToken);
        ServiceRequestFactory::setInstance($serviceRequest);
        $spreadsheetService = new Google\Spreadsheet\SpreadsheetService();
        $spreadsheetFeed = $spreadsheetService->getSpreadsheets();

        // Members Spreadsheets.
        $member_spreadsheet = $spreadsheetFeed->getByTitle('Responses for New Club Member Registration Form ');
        $member_worksheetFeed = $member_spreadsheet->getWorksheets();
        $member_worksheet = $member_worksheetFeed->getByTitle('Form Responses 1');
        $this->membersSheets = $member_worksheet->getListFeed();

        // SMS Subscribed Spreadsheets.
        $sms_spreadsheet = $spreadsheetFeed->getByTitle('SMS-Subscribed');
        $sms_worksheetFeed = $sms_spreadsheet->getWorksheets();
        $sms_worksheet = $sms_worksheetFeed->getByTitle('Sheet1');
        $this->subscribersSheets = $sms_worksheet->getListFeed();
    }

    /**
     *  Activates a previously-subscribed user, or creates an entry in the subscribers
     *  spreadsheets if it is a new subscriber (by marking the 'activated' column with 1
     *  on both cases).
     *
     *  @param string name
     *  @param string email
     *  @param string phone
     */
    public function activateSubscriber($name, $email, $phone)
    {
        // To prevent duplicates, check if member is already a subscriber.
        $entryToEdit = $this->searchSubscriberSheetsRow($email, $phone);

        // Subscribe the user.
        if ($entryToEdit) {
            // Activate the entry.
            $values = $entryToEdit->getValues();
            $values["activated"] = '1';
            $entryToEdit->update($values);
        } else {
            // Create a new entry.
            $row = array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'sendmessageat' => '9:30',
                'minutesuntilmeeting' => '60',
                'activated' => '1'
            );

            $this->subscribersSheets->insert($row);
        }
    }

    /**
     *  Deactivates a subscriber from the subscribers list, and returns true if successful,
     *  false if the subscriber did not exist.
     *
     *  @param string email
     *  @param string phone
     *
     *  @return boolean - true if success, false if member is not a subscriber
     */
    public function deactivateSubscriber($email, $phone)
    {
        // To prevent duplicates, check if member is already a subscriber.
        $entryToEdit = $this->searchSubscriberSheetsRow($email, $phone);

        if ($entryToEdit) {
            // Deactivate the Subscription by deleting the entry.
            //$entryToEdit->delete();

            // Deactivate the Subscription by marking 'activated' with 0.
            $values = $entryToEdit->getValues();
            $values["activated"] = '0';
            $entryToEdit->update($values);

            return true;
        } else {
            return false;
        }
    }

    /**
     *  Returns true if an email is part of the club members list, false otherwise.
     *  @param string email
     *
     *  @return boolean
     */
    public function memberExists($email)
    {
        foreach ($this->membersSheets->getEntries() as $entry) {
            $values = $entry->getValues();

            if ($values["emailaddress"] == $email) {
                return true;
            }
        }

        return false;
    }

    /**
     *  Returns a list with the name and phone of all subscribers.
     *  @return array['name' => x, 'phone' => y]
     */
    public function getAllSubscribers()
    {
        $subscribersList = array();

        foreach ($this->subscribersSheets->getEntries() as $entry) {
            $values = $entry->getValues();

            if ( $values['activated'] == 1 ) {
                $subscribersList[] = array(
                    'name' => $values["name"],
                    'phone' => $values["phone"]
                );
            }
        }

        return $subscribersList;
    }

    /**
     *  List feed object of the club members spreadsheet.
     *  @var ListFeed (GoogleSpreadsheet)
     */
    private $membersSheets;

    /**
     *  List feed object of the subscribers spreadsheet.
     *  @var ListFeed (GoogleSpreadsheet)
     */
    private $subscribersSheets;

    /**
     *  Returns the row (entry) in the spreadsheets where a subscriber was previously
     *  added, or null if the member has never been added to the subscribers list.
     *
     *  Note: Both email and phone have to match for the search to be successful.
     *
     *  @param string email
     *  @param string phone
     *
     *  @return Entry (GoogleSpreadsheet) or null
     */
    private function searchSubscriberSheetsRow($email, $phone)
    {
        foreach ($this->subscribersSheets->getEntries() as $entry) {
            $values = $entry->getValues();

            if ($values["email"] == $email && $values["phone"] == $phone) {
                return $entry;
            }
        }

        // If we get here, we did not find the subscriber in the spreadsheets.
        return null;
    }

}
