<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['xemail']['default'] = array(
    'driver' => 'swift',
    'server' => 'smtp.gmail.com',
    'port' => 465,
    'encryption' => 'ssl',
    'username' => 'notifications@whowentout.com',
    'password' => 'WWO12345',
    'from' => 'WhoWentOut',
);

$config['xemail']['development'] = array(
    'driver' => 'empty',
    'from' => 'WhoWentOut',
);
