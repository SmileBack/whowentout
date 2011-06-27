<?php

class Dashboard extends MY_Controller {
		
	function index() {
		$this->load->model('college_model');
		$this->load->model('user_model');
		$this->load->model('party_model');
		$this->load->helper('date');
		$this->load->helper('form');
		
		$user= $this->user_model->get_user();
		
		$data = array(
			'title'=> 'Dashboard',
			'closing_time' => get_closing_time(),
			'places'=> $this->college_model->get_places(),
			'user'=> $user,
			'parties_attended'=> $this->party_model->get_parties_attended($user->id),
		);
		
		$this->load_view('dashboard_view', $data);
	}
}
