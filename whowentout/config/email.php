<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email']['active_group'] = 'whowentoutgmail';//ENVIRONMENT;

$config['email']['whowentoutgmail'] = array(
  'driver' => 'swift',
  'server' => 'smtp.gmail.com',
  'port' => 465,
  'encryption' => 'ssl',
  'username' => 'notifications@whowentout.com',
  'password' => 'WWO12345',
);

$config['email']['development'] = array(
  'driver' => 'empty',
);

$config['email']['whowentout'] = $config['email']['whowentoutgmail'];
$config['email']['whowasout'] = $config['email']['whowentoutgmail'];