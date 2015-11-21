<?php

// NOTE: This file should prevent its arbitrary execution by allowing its
// execution during certain time-range, or by passing and checking a specific
// parameter (GET/POST).

// We use a combination of these in our servers.

/***************************************************************************/

// Get the subscribed members.
// $subscribersList will contain an associative array with the members.
require_once('getSubscribedMembers.php');

// Send a customized SMS to the subscribed members.
require_once('sendSMS.php');
sendSMS($subscribersList);

?>