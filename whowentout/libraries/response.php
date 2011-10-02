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
        foreach ($data as $k => $v) {
            $this->set($k, $v);
        }
        
        $json_response = json_encode($this->data);

        if ($this->ci->jsaction)
            $this->ci->jsaction->clear();

        if ($file_uploads)
            $json_response = "<textarea>$json_response</textarea>";

        print $json_response;
        exit;
    }
    
}
