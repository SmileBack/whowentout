<?php

define('WWO_DEBUG', TRUE);

define('REASON_NOT_IN_COLLEGE', 1 << 1);
define('REASON_NOT_IN_PARTY', 1 << 2);
define('REASON_OUT_OF_SMILES', 1 << 3);
define('REASON_ALREADY_SMILED_AT', 1 << 4);
define('REASON_DOORS_HAVE_CLOSED', 1 << 5);
define('REASON_ALREADY_ATTENDED_PARTY', 1 << 6);
define('REASON_NOT_LOGGED_IN', 1 << 7);
define('REASON_PARTY_WASNT_YESTERDAY', 1 << 8);
define('REASON_MISSING_PROFILE_INFO', 1 << 9);
define('REASON_PARTY_DOESNT_EXIST', 1 << 10);
define('REASON_RECEIVER_NOT_IN_PARTY', 1 << 11);
define('REASON_USER_DOESNT_EXIST', 1 << 12);

$config['profile_pic_size'] = array(
  'width'=> 150,
  'height'=> 200,
);
