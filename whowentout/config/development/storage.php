<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['storage']['default'] = array(
    'driver' => 'filesystem',
    'bucket' => '../tmp',
);

$config['storage']['pics'] = array(
    'driver' => 'filesystem',
    'bucket' => 'pics',
);

$config['storage']['gallery_pics'] = array(
    'driver' => 'filesystem',
    'bucket' => 'gallery_pics',
);

$config['storage']['js'] = array(
    'driver' => 's3',
    'bucket' => 'js',
);
