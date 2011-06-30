<?php

class Party_model extends CI_Model {
	
  function get_party($party_id, $user_id = NULL) {
    $party = $this->db
                  ->select('parties.id AS id, party_date, place_id, place_name, first_name AS admin_first_name, last_name AS admin_last_name')
                  ->where('parties.id', $party_id)
                  ->join('places', 'parties.place_id = places.id')
                  ->join('users', 'parties.admin = users.id', 'left')
                  ->get('parties')->row();

    if ($user_id != NULL) {
      $party->smile_info = $this->get_smile_info($party_id, $user_id);
    }
    
    return $party;
  }
	
  function get_smile_info($party_id, $user_id) {
    return array(
      'matches' => $this->get_matches($party_id, $user_id),
      'smiles_received' => $this->get_smiles_received($party_id, $user_id),
      'smiles_remaining' => $this->get_smiles_remaining($party_id, $user_id),
    );
  }
  
  /**
  *
  * @param int $party_id
  * @param int $user_id
  * @return array
  *   An array of user objects 
  */
  function get_party_attendees($party_id, $user_id) {
    $party_attendees = $this->db
                            ->select('user_id, party_attendees.party_id, first_name, last_name, college_name, grad_year, profile_pic, gender, date_of_birth')
                            ->where('party_attendees.party_id', $party_id)
                            ->where('gender', 'F')
                            ->join('users', 'party_attendees.user_id = users.id')
                            ->join('colleges', 'users.college_id = colleges.id')
                            ->get('party_attendees')->result();

    foreach ($party_attendees as &$attendee) {
      $attendee->was_smiled_at = $this->get_was_smiled_at($party_id, $user_id, $attendee->user_id);
    }

    return $party_attendees;
  }
	
  /**
  * Get an array of recent parties that $user_id attended.
  * @param int $user_id
  * @return array
  *   An array of party objects. Each party object also has smile info.
  */
  function get_recent_parties_attended($user_id) {
    $parties_attended = $this->db
                             ->select('user_id, parties.id, party_date, place_name')
                             ->from('party_attendees')
                             ->where('user_id', $user_id)
                             ->join('parties', 'party_attendees.party_id = parties.id')
                             ->join('places', 'parties.place_id = places.id')
                             ->order_by('party_date', 'desc')
                             ->get()->result();

    foreach ($parties_attended as &$party) {
      $party->smile_info = $this->get_smile_info($party->id, $user_id);
    }

    return $parties_attended;
  }
	
    function get_matches($party_id, $sender_id) { 
      return $this->db
                  ->query("SELECT first_name, last_name FROM users WHERE id
                          IN (SELECT receiver_id FROM smiles WHERE (sender_id = $sender_id) AND (party_id = $party_id))
                          AND id IN (SELECT sender_id FROM smiles WHERE receiver_id = 1)")
                  ->result();
    }
	
 /**
  * Tells you the number of smiles $user_id received at $party_id.
  * @param int $party_id
  * @param int $user_id
  * @return int 
  */
  function get_smiles_received($party_id, $user_id) {
    return $this->db
    ->from('smiles')
    ->where('receiver_id', $user_id)
    ->where('party_id', $party_id)
    ->count_all_results();
  }
	
 /**
  * Tells you the number of remaining smiles $user_id has for $party_id.
  * 
  * @param int $party_id
  * @param int $user_id
  * @return int 
  */
  function get_smiles_remaining($party_id, $user_id) {
    $smiles_used = $this->db
                        ->from('smiles')
                        ->where('sender_id', $user_id)
                        ->where('party_id', $party_id)
                        ->count_all_results();
    return (3 - $smiles_used);
  }
	
  /**
   * Tells you if the $reciever_id was smiled at by a $sender_id by $party_id.
   * 
   * @param int $party_id
   * @param int $receiver_id
   * @param int $sender_id
   * @return bool 
   */
  function get_was_smiled_at($party_id, $receiver_id, $sender_id) {
    return $this->db
                ->from('smiles')
                ->where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id)
                ->where('party_id', $party_id)
                ->count_all_results() == 1;
  }
  
}