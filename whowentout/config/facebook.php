<?php

$config = array();

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'test') {
    $config['facebook_app_id'] = '161054327279516';
    $config['facebook_secret_key'] = '8b1446580556993a34880a831ee36856';
}
elseif (ENVIRONMENT == 'whowentout') {
    $config['facebook_app_id'] = '238686466151268';
    $config['facebook_secret_key'] = '95a57df105552da2861b6f988bff82e0';
}
else if (ENVIRONMENT == 'whowasout') {
    $config['facebook_app_id'] = '183435348401103';
    $config['facebook_secret_key'] = '2a9ecc98d06840a80c21646cc185eca4';
}

$config['facebook_permissions'] = array(
    'user_birthday',
    'user_education_history',
    'user_hometown',
    'user_events',
    'email',
    'offline_access',
    'publish_stream',
    'friends_events',
    'friends_education_history',
);
