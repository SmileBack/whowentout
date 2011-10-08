<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['storage']['default'] = array(
    'driver' => 'filesystem',
    'bucket' => '../tmp',
);

$config['storage']['pics'] = array(
//    'driver' => 'filesystem',
//    'bucket' => 'pics',
    'driver' => 's3',
    'bucket' => 'whowentout_development_pics',
);

$config['storage']['js'] = array(
    'driver' => 's3',
    'bucket' => 'whowentout_development_js',
);
