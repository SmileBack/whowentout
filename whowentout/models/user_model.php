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
   * Return the party that $user_id attended on $date.
   * 
   * @param int $user_id
   * @param timestamp $date
   * @return object
   *   A party object 
   */
  function get_attended_party($user_id, $date) {
    $row = $this->db
                ->select('party_id')
                ->from('party_attendees')
                ->join('parties', 'party_attendees.party_id = parties.id')
                ->where('user_id', $user_id)
                ->where('party_date', date('Y-m-d', $date))
                ->get()->row();
    if ($row == NULL)
      return NULL;
    
    return model('party_model')->get_party($row->party_id);
  }
  
  /**
   * Tells you if $user_id attended a party on $date.
   * 
   * @param type $user_id
   * @param type $date 
   */
  function has_attended_party($user_id, $date) {
    return $this->get_attended_party($user_id, $date) != NULL;
  }
  
  function can_checkin($user_id, $party_id) {
    $party = model('party_model')->get_party($user_id, $party_id);
    if ( $this->has_attended_party($user_id, $party->party_date) ) {
      return FALSE;
    }
    
    return TRUE;
  }
  
}		
