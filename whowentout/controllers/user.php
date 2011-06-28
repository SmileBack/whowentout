<?php

class User extends MY_Controller {
	
	function checkin() {
		$party_id = $this->input->post('party_id');
		
		$this->db->insert('party_attendees', array(
		  'user_id' => 1,
		  'party_id' => $party_id,
		  'attendee_time' => date('Y-m-d H:i:s'),
		));
		
		redirect("party/$party_id");
	}
	
}
