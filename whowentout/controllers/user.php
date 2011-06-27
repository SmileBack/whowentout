<?php

class User extends MY_Controller {
	
	function checkin() {
		$this->load->model('college_model');
		$this->load->library('input');
		$this->load->helper('date');
		
		$place_id = $this->input->post('place_id');
		
		$party = $this->college_model->get_party($place_id, yesterday());
		$this->db->insert('party_attendees', array(
		  'user_id' => 1,
		  'party_id' => $party->id,
		  'attendee_time' => date('Y-m-d H:i:s'),
		));
		
		redirect("party/{$party->id}");
	}
	
}
