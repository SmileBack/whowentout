<?php

require_once 'template.functions.php';

class Template
{

    private $template_file_resource;
    private $vars = array();

    function __construct($template_name)
    {
        $this->load($template_name);
    }

    private function load($template_name)
    {
        $this->template_file_resource = app()->index()->get_resource_metadata("$template_name.tpl.php");
        if (!$this->template_file_resource)
            throw new Exception("Template $template_name doesn't exist.");
    }

    function set($var, $value = null)
    {
        if (is_string($var)) {
            $this->vars[$var] = $value;
        }
        elseif (is_array($var)) {
            foreach ($var as $k => $v) {
                $this->vars[$k] = $v;
            }
        }
    }

    function __get($var_name)
    {
        return $this->vars[$var_name];
    }

    function __set($var_name, $var_value)
    {
        $this->set($var_name, $var_value);
    }

    function render()
    {
        extract($this->vars);

        ob_start();
        include( $this->template_file_resource['filepath'] );
        $rendered_template = ob_get_contents();
        @ob_end_clean();
        
        return $rendered_template;
    }

}
