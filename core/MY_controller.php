<?php

class MY_Controller extends CI_Controller {
	
	protected function load_view($name, $data) {
		//foreach (array('header', $name, 'footer') as $v) $this->load->view($v, $data); 
			$this->load->view('header', $data);
			$this->load->view($name, $data);
			$this->load->view('footer', $data);
	}
}
