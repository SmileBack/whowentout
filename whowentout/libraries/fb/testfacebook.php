<?php

require_once 'facebook.php';

class TestFacebook extends Facebook
{

    function api($options)
    {
        $data = NULL;

        $e = new stdClass();
        $e->options = $options;
        $e->response = NULL;
        $e->fb = $this;

        $args = func_get_args();
        $e->default_response = call_user_func_array(array('parent', 'api'), $args);

        raise_event('call_facebook_api', $e);

        return $e->response ? $e->response : $e->default_response;
    }

}
