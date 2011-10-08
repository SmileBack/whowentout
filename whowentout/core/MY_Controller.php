<?php

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    protected function load_view($name, $data = array())
    {
        $data['page_content'] = $this->load->view($name, $data, TRUE);
        $this->load->view('page', $data);
    }

    protected function json($response, $file_uploads = FALSE)
    {
        $this->response->json($response, $file_uploads);
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
