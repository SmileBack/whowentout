<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['storage']['default'] = array(
    'driver' => 'filesystem',
    'bucket' => 'events',
);

$config['storage']['images'] = array(
    'driver' => 's3',
    'bucket' => 'whowentouttemp',
);

$config['storage']['js'] = array(
    'driver' => 's3',
    'bucket' => 'whowentout_development_js',
);
