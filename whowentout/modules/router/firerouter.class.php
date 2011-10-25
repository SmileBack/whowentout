<?php

class FireRouter
{

    private $class;
    private $method;
    private $directory;

    function __construct()
    {
        $this->uri =& load_class('URI', 'core');
    }

    function determine_route()
    {
        $this->uri->_fetch_uri_string();
        $this->uri->_remove_url_suffix();
        $this->uri->_explode_segments();
        $this->uri->_reindex_segments();

        $segments = $this->uri->segments;
        $uri = implode('/', $segments);

        //route the uri if a route exists
        $matcher = new RouteMatcher();
        include APPPATH . 'config/routes.php';
        foreach ($route as $k => $v) {
            $matcher->add($k, $v);
        }
        $matcher->add($k, $v);
        $routed_uri = $matcher->route($uri);

        $rsegments = explode('/', $routed_uri);

        $class = $rsegments[0];
        $method = isset($rsegments[1]) ? $rsegments[1] : 'index';

        $this->class = $class;
        $this->method = $method;

        $this->validate_request();

        $this->uri->rsegments = $rsegments;
    }

    function get_request()
    {
        return implode('/', $this->uri->segments);
    }

    private $valid_request = FALSE;
    private function validate_request()
    {
        $this->valid_request = TRUE;
        if ( ! class_exists($this->class) )
            $this->valid_request = FALSE;
        elseif ( ! is_subclass_of($this->class, 'MY_Controller') )
            $this->valid_request = FALSE;
        elseif ( ! method_exists($this->class, $this->method) )
            $this->valid_request = FALSE;
        elseif (substr($this->method, 0, 1) == '_')
            $this->valid_request = FALSE;
    }

    function is_valid_request()
    {
        return $this->valid_request;
    }

    function get_method()
    {
        return $this->method;
    }

    function get_class()
    {
        return $this->class;
    }

}
