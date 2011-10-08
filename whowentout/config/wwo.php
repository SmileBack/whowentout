<?php

define('VENKAT_FACEBOOK_ID', '776200121');
define('DAN_FACEBOOK_ID', '8100231');

$config['admin_facebook_ids'] = array(VENKAT_FACEBOOK_ID, DAN_FACEBOOK_ID);

if (ENVIRONMENT == 'whowentout')
    $config['selected_college_id'] = 1;
else
    $config['selected_college_id'] = 55;

$config['facebook_permissions'] = array(
    'user_birthday',
    'user_education_history',
    'user_hometown',
    'user_events',
    'email',
    'offline_access',
    'publish_stream',
    'user_location',
    'friends_events',
    'friends_education_history',
);

