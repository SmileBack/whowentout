<?php

abstract class StorageDriver
{

    protected $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    function name()
    {
        return str_replace('storagedriver', '', strtolower(get_class($this)));
    }

    abstract function save($destFilename, $sourceFilepath);

    abstract function saveText($destFilename, $text);

    abstract function exists($filename);

    abstract function delete($filename);

    abstract function url($filename);

}
