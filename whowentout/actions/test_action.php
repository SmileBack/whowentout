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

        $curl = new Curl;

        $curl->setOptions(array(
            CURLOPT_USERPWD => $this->application_key . ':' . $this->master_secret,
            CURLOPT_HEADER => false,
        ));
        $curl->setHeader('Content-Type', 'application/json');
        $response = $curl->post($this->url, $json);

        var_dump($response);
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
        $sender->send($token, 'wowowowowowo');
    }

}
