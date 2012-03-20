<?php

class TestAction extends Action
{

    function execute()
    {
        //development
//        $application_key = 'cJjEXUGkRnyl8dFo2NMueA';
//        $master_secret = 'uFbpIvQNSq27QFoFBUN1cg';
//        $token = '7c3b21784425aa1a778a2cbcb21763599e7c767f5476489eb8a15b00a9627766'; // development token

        // production
        $application_key = 'JRutzFNcQVi-VUglIadqYQ';
        $master_secret = '4y08w0umTeCAt4c2rRy0Hg';
        $token = '13AE193396A8BD529267B8E8C4C49A5D784C6277EBCD88FCCC79B0C8D9EDE1C4'; // production token

        $sender = new ApplePushNotificationSender($application_key, $master_secret);
        $sender->send($token, "Venkat invited you to Shadowroom on Saturday");
    }

}
