<?php

class FilesystemCache
{

    private $cache_directory;

    function __construct($cache_directory)
    {
        $this->cache_directory = $cache_directory;
    }

    private function check_path()
    {
        if ( ! is_writable($this->cache_directory)) {
            throw new Exception("The path " . $this->cache_directory . " is not writeable.");
        }
    }

    function get($key)
    {
        if (!$this->exists($key))
            return NULL;

        $serialized_data = file_get_contents( $this->filepath($key) );
        return @unserialize($serialized_data);
    }

    function exists($key)
    {
        return file_exists($this->filepath($key));
    }

    function set($key, $value)
    {
        $serialized_value = serialize($value);
        file_put_contents($this->filepath($key), $serialized_value, LOCK_EX);
    }

    function delete($key)
    {
        @unlink($this->filepath($key));
    }

    private function filepath($key)
    {
        $key = str_replace( array('/', '\\'), '', $key);
        return $this->cache_directory . '/' . $key;
    }

}
