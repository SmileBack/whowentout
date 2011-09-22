<?php

if (ENVIRONMENT == 'development') {
    $config['css_version'] = 'refresh';
    $config['js_version'] = 'refresh';
}
else {
    $config['css_version'] = 1;
    $config['js_version'] = 1;
}
