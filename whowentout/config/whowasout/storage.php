<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['storage']['default'] = array(
    'driver' => 'filesystem',
    'bucket' => '../tmp',
);

$config['storage']['pics'] = array(
    'driver' => 's3',
    'bucket' => 'whowasout_pics',
);

$config['storage']['js'] = array(
    'driver' => 's3',
    'bucket' => 'whowasout_js',
);
