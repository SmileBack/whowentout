<?php

define('APPPATH', '../');

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

    require_once APPPATH . 'fire/firecore/filesystemcache.class.php';
    require_once APPPATH . 'fire/firecore/fireapp.class.php';
    require_once APPPATH . 'fire/firecore/index.class.php';
    require_once APPPATH . 'fire/firecore/classloader.class.php';

    $cache = new FilesystemCache(APPPATH . 'cache');
    $index = new Index(APPPATH, $cache);
    $class_loader = new ClassLoader($index);
    $_fire_app = new FireApp($class_loader);

    f()->enable_autoload();

    f()->trigger('fire_boot', array(
                           'f' => f(),
                         ));

}

boot_fire();

$router = new FireRouter();
$router->determine_route();

$controller_class = $router->get_class();
$controller_method = $router->get_method();

if ($router->is_valid_request()) {
    $controller = new $controller_class;
}

if ($router->is_valid_request() && is_callable(array($controller, $controller_method))) {
    $routed_segments = $router->get_routed_segments();
    f()->trigger('before_controller_request', array(
                                                   'url' => $router->get_url(),
                                              ));

    call_user_func_array(array(&$controller, $controller_method), array_slice($routed_segments, 2));

    f()->trigger('after_controller_request', array(
                                                  'url' => $router->get_url(),
                                             ));
}
else {
    print "<h1>404 page not found</h1>";
}
