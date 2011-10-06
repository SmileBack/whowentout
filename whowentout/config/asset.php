<?php

if (ENVIRONMENT == 'development') {
    $config['css_version'] = 'refresh';
    $config['js_version'] = 41;
}
else {
    $config['css_version'] = 2;
    $config['js_version'] = 2;
}
