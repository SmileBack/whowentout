<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['storage']['default'] = array(
    'driver' => 'local',
    'path' => '../tmp',
);

$config['storage']['pics'] = array(
    'driver' => 's3',
    'bucket' => 'whowentout_pics',
);

$config['storage']['js'] = array(
    'driver' => 's3',
    'bucket' => 'whowentout_js',
);
