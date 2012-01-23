<?php

class ActionRouter
{

    /* @var $app FireApp */
    private $app;

    /* @var $route_matcher RouteMatcher */
    private $route_matcher;

    function __construct(FireApp $app, $routes = array())
    {
        $this->app = $app;
        $this->route_matcher = new RouteMatcher();
        foreach ($routes as $url => $action) {
            $this->add($url, $action);
        }
    }

    function add($url, $action)
    {
        $this->route_matcher->add($url, $action);
    }

    function route_request()
    {
        $url = $this->get_url();
        return $this->execute($url);
    }

    function execute($url)
    {
        $target = $this->route_matcher->route($url);

        if ($target == $url)
            throw new Exception("Invalid route $url");

        $parts = explode('/', $target);

        $action_class = $parts[0];
        $args = array_slice($parts, 1);

        /* @var $action Action */
        $action = $this->app->class_loader()->init_subclass('Action', $action_class);

        $this->app->trigger('before_request', array('url' => $url));
        $result = call_user_func_array(array($action, 'execute'), $args);
        $this->app->trigger('after_request', array('url' => $url));

        return $result;
    }

    function get_url()
    {
        return isset($_SERVER['PATH_INFO'])
                ? substr($_SERVER['PATH_INFO'], 1)
                : '';
    }

}
