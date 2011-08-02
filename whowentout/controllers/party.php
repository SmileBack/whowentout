<?php

class Party extends MY_Controller {
	
  function page($party_id) {
    $user = current_user();
    $party = party($party_id);
    $sort = $this->_get_sort();
    
    enforce_restrictions();
    
    if ( ! $user->has_attended_party($party) ) {
      show_404();
    }
    
    raise_event('page_load', uri_string());
    
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
  
  function count($party_id) {
    if ( ! current_user()->has_attended_party($party_id) )
      show_404();
    
    $response = array();
    
    $party = party($party_id);
    $response['client_count'] = $client_count = intval( post('count') ); // the number of attendees shown in the browser
    $response['server_count'] = $server_count = $party->count;
    $response['diff'] = $server_count - $client_count;
    $response['count'] = $party->count;
    
    $query = $party->attendees_query()
                   ->limit($server_count - $client_count);
    $new_attendees = $party->load_objects('XUser', $query);
    
    $response['new_attendees'] = array();
    foreach ($new_attendees as $attendee) {
      $response['new_attendees'][] = load_view('party_attendee_view', array(
        'party' => $party,
        'attendee' => $attendee,
        'smiles_left' => current_user()->smiles_left($party),
      ));
    }
    
    print json_encode($response);exit;
  }
  
}
