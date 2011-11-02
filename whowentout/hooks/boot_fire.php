<?php

define('FIREAPPPATH', APPPATH . 'modules/');

/**
 * @return FireApp
 */
function f()
{
    global $_fire_app;
    return $_fire_app;
}

function boot_fire()
{
    global $_fire_app;

    require_once APPPATH . '../fire/filesystemcache.class.php';
    require_once APPPATH . '../fire/fireapp.class.php';
    require_once APPPATH . '../fire/index.class.php';
    require_once APPPATH . '../fire/classloader.class.php';

    $cache = new FilesystemCache(APPPATH . '../cache');
    $index = new Index(FIREAPPPATH, $cache);
    
    $class_loader = new ClassLoader($index);
    
    $_fire_app = new FireApp($class_loader);
    
    f()->enable_autoload();

    f()->trigger('fire_boot', array(
                           'f' => f(),
                         ));
    
}
