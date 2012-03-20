<?php

class ApplePushNotificationSender
{

    private $application_key;
    private

    function __construct($application_key, $master_secret)
    {

    }

}

class TestAction extends Action
{

    function execute()
    {
        $application_key = 'cJjEXUGkRnyl8dFo2NMueA';
        $master_secret = 'uFbpIvQNSq27QFoFBUN1cg';
        $token = '7c3b21784425aa1a778a2cbcb21763599e7c767f5476489eb8a15b00a9627766';

        $request = array(
            'device_tokens' => array($token),
            'aps' => array('alert' => 'woooooo'),
        );

        $command = array();
        $command[] = sprintf('curl -X POST -u "%s:%s"', $application_key, $master_secret);
        $command[] = '-H "Content-Type: application/json"';
        $command[] = sprintf("--data '%s'", json_encode($request));
        $command[] = '"https://go.urbanairship.com/api/push/"';

        print '<pre>';
        print implode(" ", $command);
        print '</pre>';
    }

}
