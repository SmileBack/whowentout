<?php

class Party extends MY_Controller {
	
	function page($party_id) {
		$this->load->model('party_model');
		$this->load->model('user_model');
		$this->load->config('wwo');
		
		$user = $this->user_model->get_user();
    
		try {
			$data = array(
				'title'=> 'Party',
        'party'=> $this->party_model->get_party($party_id, $user->id),
				'party_attendees'=> $this->party_model->get_party_attendees($party_id, $user->id),
				'profile_pic_size'=> $this->config->item('profile_pic_size'),
			);
			
			$this->load_view('party_view', $data);
		} catch (Exception $e) {
			show_404();
		}
	}
}
