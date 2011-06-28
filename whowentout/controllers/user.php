<?php

class User extends MY_Controller {
	
	function checkin() {
		$party_id = $this->input->post('party_id');
		$user_id = get_user_id();
    
    $this->user_model->checkin($user_id, $party_id);
		
		redirect("party/$party_id");
	}
	
}
