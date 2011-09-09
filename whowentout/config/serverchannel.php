<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['serverchannel']['active_group'] = 'development';

$config['serverchannel']['development'] = array(
    'driver' => 'filesystem',
    'folder' => 'events',
);

$config['serverchannel']['phpfog'] = array(
    'driver' => 's3',
    'bucket' => 'whowentoutevents',
    'amazon_public_key' => '0N83TDC3E416BETER2R2',
    'amazon_secret_key' => 'sKpMFrppw9X2KtuvUgJRyZo+O7yvYPluC4ttAwWK',
);
