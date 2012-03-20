<?php

class ApplePushNotificationSender
{

    private $application_key;
    private $master_secret;

    private $url = 'https://go.urbanairship.com/api/push/';

    function __construct($application_key, $master_secret)
    {
        $this->application_key = $application_key;
        $this->master_secret = $master_secret;
    }

    function send($token, $message)
    {
        $contents = array(
            'badge' => '+1',
            'alert' => $message,
        );

        $push = array(
            'device_tokens' => array($token),
            'aps' => $contents,
        );

        $json = json_encode($push);

        $session = curl_init($this->url);
        curl_setopt($session, CURLOPT_USERPWD, $this->application_key . ':' . $this->master_secret);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $json);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $content = curl_exec($session);
        echo $content; // just for testing what was sent

        // Check if any error occured
        $response = curl_getinfo($session);
        if($response['http_code'] != 200) {
        echo "Got negative response from server, http code: ".
        $response['http_code'] . "\n";
        } else {

        echo "Wow, it worked!\n";
        }

        curl_close($session);
    }

}

class TestAction extends Action
{

    function execute()
    {
        $application_key = 'cJjEXUGkRnyl8dFo2NMueA';
        $master_secret = 'uFbpIvQNSq27QFoFBUN1cg';
        $token = '7c3b21784425aa1a778a2cbcb21763599e7c767f5476489eb8a15b00a9627766';

        $sender = new ApplePushNotificationSender($application_key, $master_secret);
        $sender->send($token, 'helloooo');
    }

}
