<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Component
{

    protected $ci;

    protected $drivers = array();
    protected $mounted_preset;

    private $name;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->mount();
    }

    function name()
    {
        if (!$this->name) {
            $this->name = strtolower(get_class($this));
        }
        return $this->name;
    }

    /**
     * @param string $preset
     * @return Driver
     */
    function driver($preset = NULL)
    {
        if (!$preset)
            $preset = $this->mounted_preset;

        $this->mount($preset);
        return $this->drivers[$this->mounted_preset];
    }

    function mount($preset = 'default')
    {
        $this->mounted_preset = $preset;

        if (!isset($this->drivers[$this->mounted_preset])) {
            $config = $this->load_config($preset);
            $config['preset'] = $preset;
            
            $driver_name = $config['driver'];
            $component_name = strtolower(get_class($this));

            $base_driver_path = APPPATH . "libraries/{$component_name}/{$component_name}driver.php";
            $driver_path = APPPATH . "libraries/{$component_name}/drivers/{$driver_name}{$component_name}driver.php";
            require_once $base_driver_path;
            require_once $driver_path;

            $driver_class_name = "{$driver_name}{$component_name}driver";
            $this->drivers[$this->mounted_preset] = new $driver_class_name($config);
        }
    }

    function add_preset($preset_name, $preset_config)
    {
        $config = $this->ci->config->item( $this->name() );

        if (isset($config[$preset_name]))
            throw new Exception("Preset $preset_name already exists.");

        $config[$preset_name] = $preset_config;

        $this->ci->config->set_item($this->name(), $config);
    }

    private function load_config($preset = 'default')
    {
        $component_name = $this->name();
        $this->ci->load->config($component_name);
        $config = $this->ci->config->item($component_name);

        return $config[$preset];
    }

}

abstract class Driver
{

    private $name;
    protected $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    function name()
    {
        if (!$this->name) {
            $parent_class = strtolower(get_parent_class($this));
            $this_class = strtolower(get_class($this));
            return preg_replace("/$parent_class$/", '', $this_class);
        }
    }

}

