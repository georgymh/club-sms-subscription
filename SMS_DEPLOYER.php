<?php

/*
    This is the file that the cron job should execute to send the text messages.
*/

/*
    Note: This file should prevent its arbitrary execution by only allowing its
    execution during certain time-range, or by passing and checking a pre-set
    parameter. Also by having a different hard-to-guess name.

    We use a combination of these methods on our servers.
*/

/******************************************************************************/

// Get the subscribed members.
require "lib/Subscription.php";
$subscription = new Subscription();
$subscribersList = $subscription->getAllSubscribers();

// Send text messages to all subscribed members.
require "lib/Messaging.php";
$account_sid = "TWILIO_ACCOUNT_SID_HERE";
$auth_token = "TWILIO_AUTH_TOKEN_HERE";
$fromPhone = "SMS_SENDER_PHONE_HERE";
$messaging = new Messaging($account_sid, $auth_token, $fromPhone);
$messaging->send($subscribersList);

?>
