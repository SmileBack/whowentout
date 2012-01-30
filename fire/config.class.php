<?php

class Config implements ArrayAccess
{

    private $options;

    function __construct($options)
    {
        unset($options['type']);
        $this->options = $options;
    }

    function __get($property)
    {
        return isset($this->options[$property]) ? $this->options[$property] : null;
    }

    function __isset($property)
    {
        return isset($this->options[$property]);
    }

    public function offsetSet($offset, $value)
    {
        throw new Exception('Set unsupported for config.');
    }

    public function offsetExists($offset)
    {
        return isset($this->options[$offset]);
    }

    public function offsetUnset($offset)
    {
        throw new Exception('Unset unsupported for config.');
    }

    public function offsetGet($offset)
    {
        return isset($this->options[$offset]) ? $this->options[$offset] : null;
    }

}
