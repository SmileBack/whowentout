<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Component
{

    protected $ci;

    protected $drivers = array();
    protected $mounted_preset;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->mount();
    }

    /**
     * @param string $preset
     * @return StorageDriver
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

    private function load_config($preset = 'default')
    {
        $component_name = strtolower(get_class($this));
        $this->ci->load->config($component_name);
        $config = $this->ci->config->item($component_name);

        return $config[$preset];
    }

}