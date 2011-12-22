<?php

require_once 'html.functions.php';

define('COREPATH', FIREPATH . 'core/');

function environment()
{
    if (getenv('environment')) {
        return getenv('environment');
    }
    else {
        $environment = $_SERVER['HTTP_HOST'];
        $parts = explode('.', $environment);
        $parts = array_diff($parts, array('www', 'com'));
        return array_shift($parts);
    }
}

function site_url($path = '')
{
    $protocol = 'http://';
    $host = $_SERVER['HTTP_HOST'];
    if ($path)
        return $protocol . $host . url($path);
    else
        return $protocol . $host;
}

/**
 * @return Factory
 */
function factory($key = null, $config = null)
{
    global $_factories;

    if ($key == null)
        $key = environment();

    if (!defined('FIREPATH'))
        throw new Exception('You must define FIREPATH in your index.php file');

    if (!defined('APPPATH'))
        throw new Exception('You must define APPPATH in your index.php file');

    if (!$_factories)
        $_factories = array();

    if (!isset($_factories[$key])) {
        require_once COREPATH . 'filesystemcache.class.php';
        require_once COREPATH . 'index.class.php';
        require_once COREPATH . 'classloader.class.php';

        $index_cache = new FilesystemCache(APPPATH . 'cache');
        $index = new Index(APPPATH, $index_cache);

        $class_loader = new ClassLoader($index);
        $class_loader->enable_autoload();

        $config_source = new ConfigSource($index);

        $config = is_array($config) ? $config : $key;
        $_factories[$key] = new Factory($config_source, $class_loader, $config);
    }

    return $_factories[$key];
}

/**
 * @return WhoWentOutApp
 */
function app()
{
    /* @var $_app FireApp */
    global $_app;

    if (!$_app) {
        $_app = factory()->build('app');
        $_app->enable_autoload();
    }

    return $_app;
}

/**
 * @return Database
 */
function db()
{
    return app()->database();
}

/**
 * @return FacebookAuth
 */
function auth()
{
    return factory()->build('auth');
}

/**
 * @return JsObject
 */
function js()
{
    static $js = null;
    if (!$js && class_exists('JsObject'))
        $js = new JsObject('window');

    return $js;
}

function current_url()
{
    return isset($_SERVER['PATH_INFO'])
            ? substr($_SERVER['PATH_INFO'], 1)
            : '';
}

function route_uri_request()
{
    /* @var $router Router */
    $router = factory()->build('router');
    $router->route_request();
}

function show_404()
{
    print "<h1>404 page not found</h1>";
    exit;
}

function url($path)
{
    return $path == '/' ? '/'
                        : '/' . $path;
}

function is_active($path)
{
    $current_url = current_url();
    return string_starts_with($path, $current_url);
}

function conjunct($words)
{
    $last_word = array_pop($words);
    return empty($words) ? $last_word
            : implode(', ', $words) . ", and $last_word";
}

function check_required_options($options_to_check, $required_options)
{
    $missing = array();

    foreach ($required_options as $key) {
        if (!isset($options_to_check[$key])) {
            $missing[] = $key;
        }
    }

    if (count($missing) > 0) {
        throw new Exception("You are missing " . conjunct($missing) . '.');
    }
}

function redirect($destination)
{
    $url = site_url($destination);
    header("Location: $url");
}

function run_command($args)
{
    $command_name = isset($args[1]) ? $args[1] : 'empty';
    $args = array_slice($args, 2);

    /* @var $command Command */
    $command = app()->class_loader()->init_subclass('Command', $command_name);
    if ($command)
        $command->run($args);
    else
        print "The command '$command_name' doesn't exist.";
}

function string_ends_with($end_of_string, $string)
{
    return substr($string, -strlen($end_of_string)) === $end_of_string;
}

function string_starts_with($start_of_string, $source)
{
    return strncmp($source, $start_of_string, strlen($start_of_string)) == 0;
}

function string_after_first($needle, $haystack)
{
    $pos = strpos($haystack, $needle);
    if ($pos === FALSE) {
        return FALSE;
    } else {
        return substr($haystack, $pos + strlen($needle));
    }
}

function string_before_first($needle, $haystack)
{
    $pos = strpos($haystack, $needle);
    if ($pos === FALSE) {
        return FALSE;
    } else {
        return substr($haystack, 0, $pos);
    }
}

function string_after_last($needle, $haystack)
{
    $pos = strrpos($haystack, $needle);
    if ($pos === FALSE) {
        return FALSE;
    } else {
        return substr($haystack, $pos + strlen($needle));
    }
}

function string_before_last($needle, $haystack)
{
    $pos = strrpos($haystack, $needle);
    if ($pos === FALSE) {
        return FALSE;
    } else {
        return substr($haystack, 0, $pos);
    }
}
