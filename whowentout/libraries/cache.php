<?php

class Cache extends Component
{

    private $cache = array();

    function get($key)
    {
        return $this->driver()->get($key);
    }

    function set($key, $data)
    {
        $this->driver()->set($key, $data);
    }

    function exists($key)
    {
        return $this->driver()->exists($key);
    }

    function delete($key)
    {
        $this->driver()->delete($key);
    }

}
