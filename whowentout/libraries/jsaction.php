<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_JsAction
{

    function __construct()
    {
        $this->ci =& get_instance();
    }

    function clear()
    {
        $this->set_actions(array());
    }

    function add($action, $arg1 = NULL, $arg2 = NULL, $arg3 = NULL)
    {
        $args = func_get_args(); array_shift($args);

        $actions = $this->actions();
        $actions[] = array(
            'name' => $action,
            'args' => $args,
        );
        $this->set_actions($actions);
    }

    function __call($method, $args)
    {
        $add_arguments = $args;
        array_unshift($add_arguments, $method);
        call_user_func_array(array($this, 'add'), $add_arguments);
    }

    function actions() {
        $actions = $this->ci->session->userdata('_JsActionList');
        return $actions ? $actions : array();
    }

    function set_actions($actions) {
        $this->ci->session->set_userdata('_JsActionList', $actions);
        
        if ($this->ci->response)
            $this->ci->response->set('jsactionlist', $actions);
    }

    function run($clear_actions = TRUE) {
        $actions = $this->actions();

        if ($clear_actions)
            $this->clear();

        return '<script type="text/javascript">'
                 . 'window._JsActionList = ' . json_encode($actions) . ';'
                 . 'window.JsAction.RunActions(window._JsActionList);'
             . '</script>';
    }

}