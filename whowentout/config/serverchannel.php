<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['serverchannel']['active_group'] = 'pusher';

$config['serverchannel']['pusher'] = array(
    'driver' => 'pusher',
    'app_id' => '8602',
    'app_key' => '23a32666914116c9b891',
    'app_secret' => '746d66aa6309ef7768ef'
);

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
