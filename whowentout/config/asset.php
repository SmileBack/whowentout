<?php

if (ENVIRONMENT == 'development') {
    $config['css_version'] = 12;
    $config['js_version'] = 12;
}
else {
    $config['css_version'] = 1;
    $config['js_version'] = 1;
}
