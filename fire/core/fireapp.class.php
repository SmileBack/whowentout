<?php

require_once 'core.functions.php';
require_once 'event.class.php';

class FireApp
{

    public $window_settings = array();

    private $plugins = array();

    /**
     * @var ClassLoader
     */
    protected $class_loader;

    /**
     * @var Database
     */
    protected $database;
    
    function __construct(ClassLoader $class_loader, Database $database)
    {
        $this->class_loader = $class_loader;
        $this->database = $database;
    }

    function database()
    {
        return $this->database;
    }

    function environment()
    {
        if (getenv('environment')) {
            return getenv('environment');
        }
        else {
            return $_SERVER['HTTP_HOST'];
        }
    }

    function load_window_settings()
    {
        $js = '<script type="text/javascript">'
              . 'window.settings = ' . json_encode($this->window_settings) . ';'
              . '</script>';
        return $js;
    }
        
    function trigger($event_name, $event_data)
    {
        $this->load_plugins_if_not_loaded();
        $e = $this->cast_event($event_name, $event_data);
        foreach ($this->plugins as $plugin_name => $plugin_instance) {
            $handler = "on_$event_name";
            if (method_exists($plugin_instance, $handler)) {
                $plugin_instance->$handler($e);
            }
        }
        
        return $e;
    }

    /**
     * @return ClassLoader
     */
    function class_loader()
    {
        return $this->class_loader;
    }

    /**
     * @return Index
     */
    function index()
    {
        return $this->class_loader->get_index();
    }

    function enable_autoload()
    {
        $this->class_loader->enable_autoload();
    }

    private function cast_event($event_name, $event_data = array())
    {
        $e = is_object($event_data) ? $event_data : (object)$event_data;
        $e->type = $event_name;
        
        $event_object = $this->class_loader()->init_subclass('Event', $e->type);
        if (!$event_object)
            $event_object = $this->class_loader()->init('Event');
        
        foreach ($e as $prop => $val)
            $event_object->$prop = $val;

        return $event_object;
    }

    private $plugins_loaded = false;

    private function load_plugins_if_not_loaded()
    {
        if ($this->plugins_loaded)
            return;

        $plugin_class_names = app()->class_loader()->get_subclass_names('Plugin');
        foreach ($plugin_class_names as $class_name) {
            $plugin_name = strtolower($class_name);
            $this->plugins[$plugin_name] = app()->class_loader()->init($class_name);
        }

        $this->plugins_loaded = true;
    }

}
