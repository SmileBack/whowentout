<?php

require_once 'third_party/facebook/facebook.php';

$fb = new Facebook(array(
                              'appId' =>'161054327279516',
                              'secret' => '8b1446580556993a34880a831ee36856',
                         ));
$fb->setAccessToken('AAACSemH3j5wBAOPnGG5W13KVKjBMqVKPJx3ybP4QXH8r2bYZCcZBEZCrFLsakn68dEPHKkFZCzK4zE7XuEZCvneVVgjy1GroZD');
print "querying";

$result = $fb->api('2614741/events');

print_r($result);

print "done!";
