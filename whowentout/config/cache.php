<?php

$config['cache']['default'] = array(
    'driver' => 'storage',
    'storage' => array(
        'driver' => 'filesystem',
        'bucket' => '../cache',
    ),
);
