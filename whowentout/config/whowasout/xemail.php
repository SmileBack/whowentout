<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['email']['default'] = array(
    'driver' => 'swift',
    'server' => 'smtp.gmail.com',
    'port' => 465,
    'encryption' => 'ssl',
    'username' => 'notifications@whowentout.com',
    'password' => 'WWO12345',
    'from' => 'WhoWentOut',
);
