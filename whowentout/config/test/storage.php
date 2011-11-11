<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['storage']['default'] = array(
    'driver' => 'local',
    'path' => '../tmp',
);

$config['storage']['pics'] = array(
    'driver' => 'local',
    'path' => 'teststorage/pics',
);

$config['storage']['js'] = array(
    'driver' => 's3',
    'path' => 'teststorage/js',
);
