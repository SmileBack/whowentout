<?php

class JsWindowObject extends JsObject
{

    function __construct()
    {
        parent::__construct('window');
        $this->load_from_session();
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
            '$(function() {',
            $this->to_js(),
            '});',
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

    function __destruct()
    {
        $this->persist_to_session();
    }

    private function load_from_session()
    {
        if (isset($_SESSION['js_window_object'])) {
            $this->commands = $_SESSION['js_window_object']['commands'];
            $this->objects = $_SESSION['js_window_object']['objects'];
        }
    }

    private function persist_to_session()
    {
        $_SESSION['js_window_object'] = array(
            'commands' => $this->commands,
            'objects' => $this->objects,
        );
    }

}
