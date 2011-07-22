<?php

class Party extends MY_Controller {
	
  function page($party_id) {
    $user = current_user();
    $party = party($party_id);
    $sort = $this->_get_sort();
    
    require_profile_edit();
    
    if ( ! $user->has_attended_party($party->id) ) {
      show_404();
    }
    
    $data = array(
      'title' => 'Party',
      'party' => $party,
      'user' => $user,
      'sort' => $sort,
      'party_attendees' => $party->attendees($sort),
      'profile_pic_size' => $this->config->item('profile_pic_size'),
      'smiles_left' => $user->smiles_left($party->id),
    );
    
    $this->load_view('party_view', $data);
  }
  
  function _get_sort() {
    $possible_sorts = array('checkin_time', 'name', 'gender');
    $sort = $this->input->get('sort');
    return in_array($sort, $possible_sorts)
           ? $sort
           : $possible_sorts[0];
  }
  
  function recent($party_id) {
    $user = current_user();
    if ( ! $user->has_attended_party($party_id) )
      show_404();
    
    $party = party($party_id);
    $recent_attendee_images = array();
    foreach ($party->recent_attendees() as $attendee) {
      $recent_attendee_images[] = array(
        'id' => $attendee->id,
        'path' => $attendee->thumb_url,
      );
    }
    
    print json_encode($recent_attendee_images);exit;
  }
  
}
