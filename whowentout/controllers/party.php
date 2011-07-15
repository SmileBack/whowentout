<?php

class Party extends MY_Controller {
	
  function page($party_id) {
    $user = current_user();
    $party = party($party_id);
    
    require_profile_edit();
    
    if ( ! $user->has_attended_party($party->id) ) {
      show_404();
    }
    
    $data = array(
      'title' => 'Party',
      'party' => $party,
      'user' => $user,
      'party_attendees' => $party->attendees($user->other_gender),
      'profile_pic_size' => $this->config->item('profile_pic_size'),
      'smiles_left' => $user->smiles_left($party->id),
    );
    
    $this->load_view('party_view', $data);
  }
  
  function recent($party_id) {
    $user = current_user();
    if ( ! $user->has_attended_party($party_id))
      show_404();
    
    $party = party($party_id);
    $recent_attendee_ids = array();
    foreach ($party->recent_attendees($user->other_gender) as $attendee) {
      $recent_attendee_ids[] = $attendee->id;
    }
    
    print json_encode($recent_attendee_ids);exit;
  }
  
}
