<?php

class User_model extends CI_Model {
		
	function get_user() {
		return $this->db
			->select('users.id AS id, first_name, last_name, college_name, grad_year, profile_pic,
				email, gender, date_of_birth')
			->from('users')
			->where('users.id', get_user_id())
			->join('colleges', 'users.college_id = colleges.id')
			->get()->row();
	}
  
  function checkin($user_id, $party_id) {
    $this->db->insert('party_attendees', array(
		  'user_id' => $user_id,
		  'party_id' => $party_id,
		  'checkin_time' => date('Y-m-d H:i:s'),
		));
  }
  
  /**
   * Tells you if $user_id checked in at any time on $date
   * @param type $user_id
   * @param type $date 
   */
  function has_checked_in($user_id, $date) {
    
  }
  
  function can_checkin($user_id, $party_id) {
    $party = model('party')->get_party($user_id, $party_id);
    if ( $this->has_attended_party($user_id, today()) ) {
      return FALSE;
    }
    
    return TRUE;
  }
  
}		
