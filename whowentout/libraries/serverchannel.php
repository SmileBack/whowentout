<?php

class ServerChannel extends Component
{
    
    function __construct($config)
    {
        parent::__construct($config);
        $this->ci->load->helper('serverchannel');
    }

    function type()
    {
        $driver_class = get_class($this->driver());
        return preg_replace('/ServerChannelDriver$/', 'Channel', $driver_class);
    }

    public function push($channel, $data)
    {
        return $this->driver()->push($channel, $data);
    }

    public function delete($channel)
    {
        return $this->driver()->delete($channel);
    }

    public function url($channel)
    {
        return $this->driver()->url($channel);
    }
    
}
