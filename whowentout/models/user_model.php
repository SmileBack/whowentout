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
    if (!$this->can_checkin($user_id, $party_id)) {
      return FALSE;
    }
    
    $this->db->insert('party_attendees', 
                array(
		  'user_id' => $user_id,
		  'party_id' => $party_id,
		  'checkin_time' => gmdate('Y-m-d H:i:s'),
		));
    
    return TRUE;
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
    $date = make_local($date);
    
    $row = $this->db
                ->select('party_id')
                ->from('party_attendees')
                ->join('parties', 'party_attendees.party_id = parties.id')
                ->where('user_id', $user_id)
                ->where('party_date', date_format($date, 'Y-m-d'))
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
    $party = model('party_model')->get_party($party_id, $user_id);
    $party_date = new DateTime($party->party_date, get_college_timezone());
    
    // You've already attended a party
    if ( $this->has_attended_party($user_id, $party_date) ) {
      return FALSE;
    }
    
    // TODO: You are not within the bounds of the checkin time.
    
    
    return TRUE;
  }
  
}		
