<?php

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler(TRUE);

        $this->load->library('asset');
        $this->asset->load('whowentout.application.js');
    }

    protected function load_view($name, $data = array())
    {
        $this->benchmark->mark('page_content_start');
        $data['page_content'] = $this->load->view($name, $data, TRUE);
        $this->benchmark->mark('page_content_end');
        
        $this->load->view('page', $data);
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
