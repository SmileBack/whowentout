<?php

if (ENVIRONMENT == 'development') {
    $config['css_version'] = 'refresh';//13;
    $config['js_version'] = 13;
}
else {
    $config['css_version'] = 1;
    $config['js_version'] = 1;
}
