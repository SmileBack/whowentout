<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['serverchannel']['active_group'] = 'pusher';

if (getenv('serverchannel_active_group') != NULL)
    $config['serverchannel']['active_group'] = getenv('serverchannel_active_group');

$config['serverchannel']['development'] = array(
    'driver' => 'filesystem',
    'folder' => 'events',
);

$config['serverchannel']['whowentout'] = array(
    'driver' => 's3',
    'bucket' => 'whowentoutevents',
    'amazon_public_key' => '0N83TDC3E416BETER2R2',
    'amazon_secret_key' => 'sKpMFrppw9X2KtuvUgJRyZo+O7yvYPluC4ttAwWK',
);

$config['serverchannel']['whowasout'] = array(
    'driver' => 's3',
    'bucket' => 'whowasoutevents',
    'amazon_public_key' => '0N83TDC3E416BETER2R2',
    'amazon_secret_key' => 'sKpMFrppw9X2KtuvUgJRyZo+O7yvYPluC4ttAwWK',
);

