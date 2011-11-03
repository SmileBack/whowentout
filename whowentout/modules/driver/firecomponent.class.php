<?php


class FireComponent
{
    protected $drivers = array();
    protected $mounted_preset;

    private $name;

    function __construct()
    {
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

        if ( ! isset($this->drivers[$this->mounted_preset]) ) {
            $config = $this->load_config($preset);

            $driver_name = $config['driver'];
            $component_name = strtolower(get_class($this));
            $driver_class_name = "{$driver_name}{$component_name}driver"; //todo: use init_subclass

            $this->drivers[$this->mounted_preset] = new $driver_class_name($config);
        }
    }

    private function load_config($preset = 'default')
    {
        $component_name = $this->name();

        $CFG =& load_class('Config', 'core');
        $CFG->load($component_name);
        $config = $CFG->item($component_name);

        return $config[$preset];
    }

}
