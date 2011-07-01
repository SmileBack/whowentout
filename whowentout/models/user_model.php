<?php

class User_model extends CI_Model {
  
  function checkin($user_id, $party_id) {
    if (!$this->can_checkin($user_id, $party_id)) {
      return FALSE;
    }
    
    $this->db->insert('party_attendees', array(
                        'user_id' => $user_id,
                        'party_id' => $party_id,
                        'checkin_time' => gmdate('Y-m-d H:i:s'),
                      ));
    
    return TRUE;
  }
  
  function smile_at($sender_id, $receiver_id, $party_id) {
    if (!$this->can_smile_at($sender_id, $receiver_id, $party_id))
      return FALSE;
    
    $this->db->insert('smiles',array(
                        'sender_id' => $sender_id,
                        'receiver_id' => $receiver_id,
                        'party_id' => $party_id,
                        'smile_time' => gmdate('Y-m-d H:i:s'),
                     ));
    
    return TRUE;
  }
  
  function can_smile_at($sender_id, $receiver_id, $party_id) {
    if ( ! $this->has_attended_party($sender_id, $party_id) ) {
      return FALSE;
    }
    
    if ( ci()->party_model->get_smiles_remaining($party_id, $sender_id) <= 0 ) {
      return FALSE;
    }
    
    if ( $this->has_smiled_at($sender_id, $receiver_id, $party_id) ) {
      return FALSE;
    }
    
    return TRUE;
  }
  
  /**
   * Tells you if $sender_id smiled at $receiver_id at a $party_id.
   * @param int $sender_id
   * @param int $receiver_id
   * @param int $party_id
   * @return bool
   */
  function has_smiled_at($sender_id, $receiver_id, $party_id) {
    return $this->db->from('smiles')
                    ->where('sender_id', $sender_id)
                    ->where('receiver_id', $receiver_id)
                    ->where('party_id', $party_id)
                    ->count_all_results() > 0;
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
    
    return ci()->party_model->get_party($row->party_id);
  }
  
  /**
   * Tells you if $user_id attended a party on $date.
   * 
   * @param type $user_id
   * @param type $date
   */
  function has_attended_party_on_date($user_id, $date) {
    return $this->get_attended_party($user_id, $date) != NULL;
  }
  
  /**
   * Tells you if $user_id attended $party_id.
   * @param type $user_id
   * @param type $party_id 
   * @return bool
   */
  function has_attended_party($user_id, $party_id) {
    return $this->db->from('party_attendees')
                    ->where('user_id', $user_id)
                    ->where('party_id', $party_id)
                    ->count_all_results() > 0;
  }
  
  function can_checkin($user_id, $party_id) {
    $party = ci()->party_model->get_party($party_id, $user_id);
    $party_date = new DateTime($party->party_date, get_college_timezone());
    
    // You've already attended a party
    if ( $this->has_attended_party_on_date($user_id, $party_date) ) {
      return FALSE;
    }
    
    // You are not within the bounds of the checkin time.
    if (doors_are_closed())
      return FALSE;
    
    return TRUE;
  }
  
}		
