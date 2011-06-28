<?php

class Dashboard extends MY_Controller {
		
	function index() {
		
		$user = $this->user_model->get_user();
		
		$data = array(
			'title'=> 'Dashboard',
			'closing_time' => get_closing_time(),
			'parties_dropdown' => parties_dropdown(get_college_id(), strtotime('2011-05-27')),
			'user'=> $user,
			'parties_attended'=> $this->party_model->get_parties_attended($user->id),
		);
		
		$this->load_view('dashboard_view', $data);
	}
}
