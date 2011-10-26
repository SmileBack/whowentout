<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['serverchannel']['default'] = array(
    'driver' => 'pusher',
    'app_id' => '10138',
    'app_key' => '805af8a6919abc9fb047',
    'app_secret' => '66d4d08ba68b3da6a60f',
);

$config['serverchannel']['localpolling'] = array(
    'driver' => 'polling',
    'storage' => array(
        'driver' => 'filesystem',
        'bucket' => 'events',
    ),
);
