<?php

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

function html_element_open($tag, $attributes = array())
{
    $html = array();

    $html[] = "<$tag";

    if (!empty($attributes)) {
        $html[] = ' ';
        foreach ($attributes as $prop => $val) {
            $html[] = sprintf('%s="%s"', $prop, $val);
        }
    }

    $html[] = ">";

    return implode('', $html);
}

function html_element_close($tag)
{
    return "</$tag>";
}

function html_element($tag, $attributes = array(), $content = '')
{
    return html_element_open($tag, $attributes) . $content . html_element_close($tag);
}

function url($path)
{
    return '/' . $path;
}

function a($path, $title, $attributes = array())
{
    return a_open($path, $attributes) . $title . a_close();
}

function a_open($path, $attributes = array())
{
    $attributes['href'] = url($path);
    
    if (is_active($path)) {
        $attributes['class'] = isset($attributes['class'])
                ? $attributes['class'] . ' active'
                : 'active';
    }

    return html_element_open('a', $attributes);
}

function a_close()
{
    return html_element_close('a');
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
    $url = url($destination);
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
