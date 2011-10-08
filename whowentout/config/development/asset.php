<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['css_version'] = 'refresh';
$config['js_version'] = 41;

$config['asset']['js'] = array(
    'version' => 41,
    'jquery' => array('lib/jquery.js'),
    'jquery.ext' => array(
        'lib/jquery.entwine.js',
        'lib/underscore.js',
        'lib/jquery.position.js',
        'lib/jquery.ext.js',
    ),
    'application' => array(
        'pack' => TRUE,
        
        'WhoWentOut.Application.js',
        'widgets/jquery.autocomplete.js',
        'widgets/jquery.dialog.js',
        'widgets/jquery.notifications.js',
        'widgets/chatbar.js',
        'core.js',
        'time.js',

        'pages',

        'script.js',

        'lib/jsaction.js',
        'actions.js',
    ),
    'pages' => array(
        'pages/editinfo.js',
        'pages/home.js',
        'pages/dashboard.js',
        'pages/gallery.js',
        'pages/editinfo.js',
        'pages/friends.js',
    ),
);

$config['asset']['css'] = array(
    'version' => 'refresh',
);
