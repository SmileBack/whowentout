<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['storage']['default'] = array(
    'driver' => 'local',
    'path' => '../tmp',
);

$config['storage']['pics'] = array(
    'driver' => 'local',
    'path' => 'pics',
    'base_url' => 'http://localhost/',
);

$config['storage']['js'] = array(
    'driver' => 's3',
    'bucket' => 'js',
);
