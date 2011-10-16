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

    public function trigger($channel, $event_name, $event_data)
    {
        return $this->driver()->trigger($channel, $event_name, $event_data);
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
