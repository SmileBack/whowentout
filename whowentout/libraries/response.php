<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Response
{

    private $ci;

    private $data = array();

    function __construct()
    {
        $this->ci =& get_instance();
    }

    function set($k, $v)
    {
        $this->data[$k] = $v;
    }

    function json($data = array(), $file_uploads = FALSE)
    {
        $json_response = $this->prepare_json_response($data);
        print $json_response;
        exit;
    }

    function json_for_ajax_file_upload($data = array())
    {
        $json_response = $this->prepare_json_response($data);
        print "<textarea>$json_response</textarea>";
        exit;
    }

    private function prepare_json_response($data = array())
    {
        foreach ($data as $k => $v) {
            $this->set($k, $v);
        }

        $json_response = json_encode($this->data);

        if ($this->ci->jsaction)
            $this->ci->jsaction->clear();

        return $json_response;
    }

}
