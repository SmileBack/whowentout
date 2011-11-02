<?php

class ServerChannel extends FireComponent
{

    function __construct($config)
    {
        parent::__construct($config);
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
