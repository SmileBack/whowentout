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
    'amazon_public_key' => '0N83TDC3E416BETER2R2',
    'amazon_secret_key' => 'sKpMFrppw9X2KtuvUgJRyZo+O7yvYPluC4ttAwWK',
);
