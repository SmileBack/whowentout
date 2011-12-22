<?php

class JsFunctionCall extends JsCommand
{

    /* @var $context JsObject */
    private $object;
    private $function;
    private $args = array();

    function __construct(JsObject $object, $function, $args)
    {
        $this->object = $object;
        $this->function = $function;
        $this->args = $args;
    }

    function to_js()
    {
        return $this->object->_get_path() . '.' . $this->function_to_js();
    }

    private function function_to_js()
    {
        $js = array();
        foreach ($this->args as $arg) {
            $js[] = json_encode($arg);
        }
        return $this->function . '(' . implode(', ', $js) . ')';
    }

}
