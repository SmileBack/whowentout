<?php

class Homepage extends MY_Controller {
	
	function index() {
    
		$data= array(
		  'title'=> 'Home',
		  'parties_dropdown'=> parties_dropdown(get_college_id(), strtotime('2011-05-27')),
		);
		
		$this->load_view('homepage_view', $data);
	}
	
}
