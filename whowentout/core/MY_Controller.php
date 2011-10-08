<?php

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        require_once APPPATH . 'libraries/component.php';
        require_once APPPATH . 'libraries/imagerepository.php';
        require_once APPPATH . 'libraries/fb/testfacebook.php';

        require_once APPPATH . 'objects/xobject.php';
        require_once APPPATH . 'objects/xuser.php';
        require_once APPPATH . 'objects/xcollege.php';
        require_once APPPATH . 'objects/xparty.php';
        require_once APPPATH . 'objects/xplace.php';
        require_once APPPATH . 'objects/xsmile.php';
        require_once APPPATH . 'objects/xsmilematch.php';
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
