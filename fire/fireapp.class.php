<?php

class FireApp
{

    /**
     * @var ClassLoader
     */
    private $class_loader;

    private $plugins = array();

    public $window_settings = array();

    function __construct($class_loader)
    {
        $this->class_loader = $class_loader;
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
        return $e;
    }

    private $plugins_loaded = FALSE;
    private function load_plugins_if_not_loaded()
    {
        if ($this->plugins_loaded)
            return;

        $plugin_class_names = f()->class_loader()->get_subclass_names('Plugin');
        foreach ($plugin_class_names as $class_name) {
            $plugin_name = strtolower($class_name);
            $this->plugins[$plugin_name] = f()->class_loader()->init($class_name);
        }

        $this->plugins_loaded = TRUE;
    }

}
