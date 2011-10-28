<?php

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        f()->class_loader()->load('View');
        $this->config->load('pusher');

        f()->window_settings['pusher']['app_key'] = $this->config->item('pusher_app_key');
        f()->window_settings['environment'] = ENVIRONMENT;
    }

    protected function require_login($redirect = FALSE)
    {
        if (!logged_in()) {
            if ($redirect) {
                redirect('/');
            }
            else {
                show_error("You must be logged in.");
            }
        }
    }

    protected function load_view($name, $vars = array())
    {
        $this->benchmark->mark('page_content_start');
        $vars['page_content'] = r($name, $vars);
        $this->benchmark->mark('page_content_end');
        print r('page', $vars);
    }

    protected function json($response)
    {
        $this->response->json($response);
    }

    protected function json_for_ajax_file_upload($response)
    {
        $this->response->json_for_ajax_file_upload($response);
    }

    protected function json_success($message = '')
    {
        $this->json(array('success' => TRUE, 'message' => $message));
    }

    protected function json_failure($error = '')
    {
        $this->json(array('success' => FALSE, 'error' => $error));
    }

    function is_ajax()
    {
        return $this->input->is_ajax_request();
    }

}
