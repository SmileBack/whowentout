<?php

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        f()->class_loader()->load('Template');
        
        $this->config->load('pusher');
        f()->window_settings['pusher']['app_key'] = $this->config->item('pusher_app_key');
        f()->window_settings['environment'] = ENVIRONMENT;

        if (college()) {
            f()->window_settings['time']['current'] = college()->get_time()->getTimestamp();
            f()->window_settings['time']['yesterday'] = college()->get_time()->getDay(-1)->getTimestamp();
            f()->window_settings['time']['tomorrow'] = college()->get_time()->getDay(+1)->getTimestamp();
        }

        if (logged_in()) {
            f()->window_settings['current_user_id'] = current_user()->id;
            f()->window_settings['chatbar_state'] = current_user()->chatbar_state;
        }

        $this->output->enable_profiler(TRUE);
    }

    protected function require_admin()
    {
        $this->require_login();
        if (!current_user()->is_admin())
            show_error("You must be an admin.");
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
