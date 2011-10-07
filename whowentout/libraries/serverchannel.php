<?php

class ServerChannel
{

    function __construct()
    {
        $this->ci =& get_instance();
        $this->load_config();
        $this->load_driver();

        $this->ci->load->helper('serverchannel');
    }

    function type()
    {
        return $this->driver->channel_type();
    }

    public function push($channel, $data)
    {
        return $this->driver->push($channel, $data);
    }

    public function delete($channel)
    {
        return $this->driver->delete($channel);
    }

    public function url($channel)
    {
        return $this->driver->url($channel);
    }

    function driver_config()
    {
        return $this->driver_config;
    }

    function load_config()
    {
        $this->ci->load->config('serverchannel');
        $this->config = $this->ci->config->item('serverchannel');
        $this->driver_config = $this->config[$this->config['active_group']];
    }

    function load_driver()
    {
        $driver_name = $this->driver_config['driver'];
        $base_driver_path = APPPATH . "libraries/serverchannel/serverchanneldriver.php";
        $driver_path = APPPATH . "libraries/serverchannel/drivers/{$driver_name}serverchanneldriver.php";
        require_once $base_driver_path;
        require_once $driver_path;

        $driver_class_name = "{$driver_name}serverchanneldriver";
        $this->driver = new $driver_class_name($this->driver_config);
    }

}
