<?php

class JsObject
{
    /* @var $parent JsObject */
    public $parent = null;

    public $name;

    /* @var $commands JsCommand[] */
    private $commands = array();

    /* @var $objects JsObject[] */
    private $objects = array();

    function __construct($name)
    {
        $this->name = $name;
    }

    function __call($function, $args)
    {
        $command = new JsFunctionCall($this, $function, $args);
        $this->_add_command($command);
    }

    function &__get($name)
    {
        if (!isset($this->objects[$name])) {
            $this->objects[$name] = new JsObject($name);
            $this->objects[$name]->parent = $this;
        }
        return $this->objects[$name];
    }

    function __set($name, $value)
    {
        $this->objects[$name] = $value;

        $command = new JsAssignment($this, $name, $value);
        $this->_add_command($command);
    }

    function __unset($name)
    {
        unset($this->objects[$name]);
    }

    function to_js()
    {
        $js = array();
        foreach ($this->commands as $command) {
            $js[] = $command->to_js() . ';';
        }
        return implode("\n", $js);
    }

    function __toString()
    {
        return $this->script();
    }

    function script()
    {
        $script = array(
            '<script type="text/javascript">',
            $this->to_js(),
            '</script>',
            '',
        );
        return implode("\n", $script);
    }

    function _add_command(JsCommand $command)
    {
        if ($this->parent == null) {
            $this->commands[] = $command;
        }
        else {
            $this->parent->_add_command($command);
        }
    }

    function _get_path()
    {
        $scopes = array();
        for ($cur = $this; $cur != null; $cur = $cur->parent)
            $scopes[] = $cur->name;

        $scopes = array_reverse($scopes);
        return implode('.', $scopes);
    }

}