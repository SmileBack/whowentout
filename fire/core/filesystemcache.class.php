<?php

class FilesystemCache
{

    private $path;

    function __construct($path)
    {
        $this->path = $path;
        krumo::dump('yea');exit;
    }

    function get($key)
    {
        if (!$this->exists($key))
            return null;

        $serialized_data = file_get_contents($this->filepath($key));
        return @unserialize($serialized_data);
    }

    function exists($key)
    {
        return file_exists($this->filepath($key));
    }

    function set($key, $value)
    {
        $serialized_value = serialize($value);
        krumo::dump($this->filepath($key));exit;
        file_put_contents($this->filepath($key), $serialized_value, LOCK_EX);
    }

    function delete($key)
    {
        @unlink($this->filepath($key));
    }

    private function filepath($key)
    {
        $key = str_replace(array('/', '\\'), '', $key);
        return $this->path . '/' . $key;
    }

    private function check_path()
    {
        if (!is_writable($this->path)) {
            throw new Exception("The path " . $this->path . " is not writeable.");
        }
    }
    
}
