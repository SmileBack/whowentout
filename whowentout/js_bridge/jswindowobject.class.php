<?php

class JsWindowObject extends JsObject
{

    function __construct()
    {
        parent::__construct('window');
    }

    function _add_command(JsCommand $command)
    {
        $this->commands[] = $command;
    }

    function __toString()
    {
        $script = $this->script();

        // clear everything so the script is only run once
        $this->commands = array();
        $this->objects = array();

        return $script;
    }

    function script()
    {
        $script = array(
            '<script type="text/javascript">',
            '',
            $this->to_js(),
            '',
            '</script>',
            '',
        );
        return implode("\n", $script);
    }

    function to_js()
    {
        $js = array();
        foreach ($this->commands as $command) {
            $js[] = $command->to_js() . ';';
        }
        return implode("\n", $js);
    }

}
