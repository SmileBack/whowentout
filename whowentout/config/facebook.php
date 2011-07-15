<?php

$config = array();

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'test') {
  $config['facebook_app_id'] = '161054327279516';
  $config['facebook_secret_key'] = '8b1446580556993a34880a831ee36856';
}
elseif (ENVIRONMENT == 'hostgator') {
  $config['facebook_app_id'] = '238686466151268';
  $config['facebook_secret_key'] = '95a57df105552da2861b6f988bff82e0';
}
