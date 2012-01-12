<?php

require_once 'template.functions.php';

class Display
{

    private $template_file_resource;
    private $vars = array();

    protected $defaults = array();

    function __construct($template_name, $options = array())
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
        return isset($this->vars[$var_name]) ? $this->vars[$var_name] : null;
    }

    function __set($var_name, $var_value)
    {
        $this->set($var_name, $var_value);
    }

    function __isset($var_name)
    {
        return isset($this->vars[$var_name]);
    }

    function __unset($var_name)
    {
        unset($this->vars[$var_name]);
    }

    // to be overridden
    function process()
    {
    }

    function render()
    {
        $this->apply_default_variables();
        $this->process();

        extract($this->vars);
        ob_start();
        include( $this->template_file_resource['filepath'] );
        $rendered_template = ob_get_contents();
        @ob_end_clean();
        
        return $rendered_template;
    }

    function __toString()
    {
        try {
            $rendered_html = $this->render();
        }
        catch (Exception $e) {
            trigger_error(strval($e));
        }

        return $rendered_html;
    }

    protected function apply_default_variables()
    {
        foreach ($this->defaults as $var_name => $var_value) {
            if (!isset($this->$var_name))
                $this->$var_name = $var_value;
        }
    }

}
