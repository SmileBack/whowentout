<?php

class FireRouter
{

    private $class;
    private $method;
    private $routed_segments;

    function __construct()
    {
    }

    function get_url()
    {
        return isset($_SERVER['PATH_INFO'])
                ? substr($_SERVER['PATH_INFO'], 1)
                : '';
    }

    function get_url_segments()
    {
        return explode('/', $this->get_url());
    }

    function determine_route()
    {
        $url = $this->get_url();

        //route the uri if a route exists
        $matcher = new RouteMatcher();
        $routes = array(); //todo: allow for routes
        foreach ($routes as $k => $v) {
            $matcher->add($k, $v);
        }
        $routed_url = $matcher->route($url);

        $this->routed_segments = explode('/', $routed_url);

        $class = $this->routed_segments[0] . '_controller';
        $method = isset($this->routed_segments[1]) ? $this->routed_segments[1] : 'index';

        $this->class = $class;
        $this->method = $method;

        $this->validate_request();
    }

    function get_request()
    {
        return implode('/', $this->uri->segments);
    }

    private $valid_request = false;

    private function validate_request()
    {
        $this->valid_request = true;
        if (!class_exists($this->class))
            $this->valid_request = false;
        elseif (!is_subclass_of($this->class, 'Controller'))
            $this->valid_request = false;
        elseif (!method_exists($this->class, $this->method))
            $this->valid_request = false;
        elseif (substr($this->method, 0, 1) == '_')
            $this->valid_request = false;
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

    function get_routed_segments()
    {
        return $this->routed_segments;
    }

}
