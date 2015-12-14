<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Messaging {

    /**
     *  Constructor.
     *  @param string account_sid: Twilio's Account SID
     *  @param string auth_token: Twilio's Authorization Token
     *  @param string fromPhone: Phone number that sends the text (+11234567890)
     */
    function __construct($account_sid, $auth_token, $fromPhone)
    {
        $this->twilioClient = new Services_Twilio($account_sid, $auth_token);
        $this->fromPhone = $fromPhone;
    }

    /**
     *  Sends a text message to each phone number in the list specified.
     *  @param array['name' => x, 'phone' => y]
     */
    public function send($listOfPhones)
    {
        foreach ($listOfPhones as $member) {
            $name = $member["name"];
            $phone = $member["phone"];
            if ($this->checkIfValidPhone($phone)) {
                //use helper library to send message
                $this->twilioClient->account->messages->create(array(
                    "To" => "+" . $phone,
                    "From" => $this->fromPhone,
                    "Body" => $this->composeMessage($name)
                ));
            }
        }
    }

    /**
     *  Twilio Client.
     *  Used to send the text messages.
     *  @var Services_Twilio
     */
    private $twilioClient;

    /**
     *  Phone to send text from.
     *  @var string
     */
    private $fromPhone;

    /**
     *  Returns true if the phone number is valid.
     *  Used to prevent errors from the SMS Client.
     *  @param string phone
     *
     *  @return boolean
     */
    private function checkIfValidPhone($phone)
    {
        return (preg_match("/^[0-9]{11}$/", $phone));
    }

    /**
     *  Composes and returns a custom message with the name of a subscriber.
     *  @param string name
     *
     *  @return string
     */
    private function composeMessage($name)
    {
        return "Hey $name! This is a reminder that the OCC Programming Club meeting will start in 60 minutes.";
    }

}
