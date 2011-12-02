<?php

define('COREPATH', FIREPATH . 'core/');

/**
 * @return Factory
 */
function factory()
{
    global $_factory;

    if (!defined('FIREPATH'))
        throw new Exception('You must define FIREPATH in your index.php file');

    if (!defined('APPPATH'))
        throw new Exception('You must define APPPATH in your index.php file');

    if (!$_factory) {
        require_once COREPATH . 'filesystemcache.class.php';
        require_once COREPATH . 'index.class.php';
        require_once COREPATH . 'classloader.class.php';

        $index_cache = new FilesystemCache(APPPATH . 'cache');
        $index = new Index(APPPATH, $index_cache);
        $class_loader = new ClassLoader($index);
        $class_loader->enable_autoload();

        $config_source = new ConfigSource($index);
        $_factory = new Factory($config_source, $class_loader, 'app');
    }

    return $_factory;
}

/**
 * @return FireApp
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

function html_element($tag, $attributes = array(), $content = '')
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
    $html[] = $content;
    $html[] = "</$tag>";

    return implode('', $html);
}

function a($url, $title, $attributes = array())
{
    $attributes['href'] = '/' . $url;
    return html_element('a', $attributes, $title);
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
        if ( ! isset($options_to_check[$key]) ) {
            $missing[] = $key;
        }
    }

    if (count($missing) > 0) {
        throw new Exception("You are missing " . conjunct($missing) . '.');
    }
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
