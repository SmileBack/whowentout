<?php

class Dashboard extends MY_Controller {
		
	function index() {
		$this->load->model('college_model');
		$this->load->model('user_model');
		$this->load->model('party_model');
		
		$user= $this->user_model->get_user();
		
		$data= array(
			'title'=> 'Dashboard',
			'timer'=> get_timer(),
			'places'=> $this->college_model->get_places(),
			'user'=> $user,
			'parties_attended'=> $this->party_model->get_parties_attended($user->id),
		);
		
		$this->load_view('dashboard_view', $data);
	}
}