<?php

class Party extends MY_Controller {
	
  function page($party_id) {
    $user = current_user();
    $party = XParty::get($party_id);
    
    if ( ! $user->has_attended_party($party->id) ) {
      show_404();
    }
    
    $data = array(
      'title' => 'Party',
      'party' => $party,
      'user' => $user,
      'party_attendees' => $party->attendees,
      'profile_pic_size' => $this->config->item('profile_pic_size'),
    );

    $this->load_view('party_view', $data);
  }
  
}
