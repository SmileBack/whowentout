<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['serverchannel']['default'] = array(
    'driver' => 'pusher',
    'app_id' => '10140',
    'app_key' => 'cc920ca581a4b74b17dd',
    'app_secret' => 'b9c27ae2c8d15615336c',
);

$config['serverchannel']['s3polling'] = array(
    'driver' => 'polling',
    'storage' => array(
        'driver' => 's3',
        'bucket' => 'whowasout_events',
    ),
);
