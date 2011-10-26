<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['serverchannel']['default'] = array(
    'driver' => 'pusher',
    'app_id' => '10139',
    'app_key' => '8d634f5c91dded3c5ba9',
    'app_secret' => '7089b317afc31e3d6d66',
);

$config['serverchannel']['s3polling'] = array(
    'driver' => 'polling',
    'storage' => array(
        'driver' => 's3',
        'bucket' => 'whowentout_events',
    ),
);
