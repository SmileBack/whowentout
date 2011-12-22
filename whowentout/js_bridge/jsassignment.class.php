<?php

class JsAssignment extends JsCommand
{

    /* @var $object JsObject */
    private $object;
    private $key;
    private $value;

    function __construct(JsObject $object, $key, $value)
    {
        $this->object = $object;
        $this->key = $key;
        $this->value = $value;
    }

    function to_js()
    {
        return $this->object->_get_path() . ".$this->key = " . json_encode($this->value);
    }

}
