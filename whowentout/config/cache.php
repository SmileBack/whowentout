<?php

$config['cache']['default'] = array(
    'driver' => 'storage',
    'storage' => array(
        'driver' => 's3',
        'bucket' => 'whowentouttest',
    ),
);
