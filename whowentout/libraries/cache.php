<?php

class Cache extends Component
{

    private $cache = array();

    function get($key)
    {
        if ( ! isset($this->cache[$key]) ) {
            $this->cache[$key] = $this->driver()->get($key);
        }
        return $this->cache[$key];
    }

    function set($key, $data)
    {
        $this->cache[$key] = $data;
        $this->driver()->set($key, $data);
    }

    function exists($key)
    {
        if (isset($this->cache[$key]))
            return TRUE;
        
        return $this->driver()->exists($key);
    }

    function delete($key)
    {
        unset($this->cache[$key]);
        $this->driver()->delete($key);
    }

}
