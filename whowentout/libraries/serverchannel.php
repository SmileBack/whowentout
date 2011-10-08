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
        return $this->driver()->channel_type();
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
