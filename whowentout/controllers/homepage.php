<?php

class Homepage extends MY_Controller {
	
	function index() {
		$this->load->model('college_model');
		
		$data= array(
		  'title'=> 'Home',
		  'places'=> $this->college_model->get_places(),
		);
		
		$this->load_view('homepage_view', $data);
	}
	
}
