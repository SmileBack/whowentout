<?php

class StorageCacheDriver extends CacheDriver
{

    private $storage_preset;

    function __construct($options)
    {
        parent::__construct($options);

        $this->ci =& get_instance();
        $this->ci->load->library('storage');

        $this->storage_preset = $this->options['preset'] . '_cache';
        $this->ci->storage->add_preset($this->storage_preset, $this->options['storage']);
    }

    function get($key)
    {
        if ($this->ci->storage->exists($this->storage_preset, $key))
        {
            $data = $this->ci->storage->getText($this->storage_preset, $key);
            return unserialize($data);
        }
    }

    function set($key, $data)
    {
        $data = serialize($data);
        $this->ci->storage->saveText($this->storage_preset, $key, $data);
    }

    function exists($key)
    {
        return $this->ci->storage->exists($this->storage_preset, $key);
    }

    function delete($key)
    {
        $this->ci->storage->delete($this->storage_preset, $key);
    }

}
