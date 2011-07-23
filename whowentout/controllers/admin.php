<?php

class Admin extends MY_Controller
{
  
  function index() {
    $this->load_view('admin/admin_view');
  }
  
  function fake_time() {
    $fake_time = post('fake_time');
    $fake_time_point = get_option('fake_time_point');
    
    if ($fake_time != NULL) {
      set_fake_time(new DateTime($fake_time));
    }
    
    $data = array();
    $delta = time_delta_seconds();
    
    if ( ! time_is_faked() ) {
      $fake_time_point = array(
        'real_time' => actual_time(),
        'fake_time' => current_time(),
      );
    }
      
    $data['real_time'] = date_format($fake_time_point['real_time'], 'Y-m-d H:i:s');
    $data['fake_time'] = date_format($fake_time_point['fake_time'], 'Y-m-d H:i:s');
    $data['delta'] = "$delta seconds";
    
    $this->load_view('admin/fake_time_view', $data);
  }
  
  function parties() {
    $this->load_view('admin/edit_parties_view');
  }
  
  function users() {
    $this->load_view('admin/users_view');
  }
  
  function destroy_user($user_id) {
    $user = user($user_id);
    destroy_user($user->id);
    set_message("Destroyed $user->full_name.");
    redirect('admin/users');
  }
  
  function add_party() {
    $date = new DateTime( post('date'), college()->timezone );
    $place_id = post('place_id');
    
    $place = place($place_id);
    $formatted_date = $date->format('Y-m-d');
    
    college()->add_party($formatted_date, $place_id);
    set_message("Created party on $formatted_date at $place->name.");
    redirect('admin/parties');
  }
  
  function random_checkin($party_id) {
    $party = party($party_id);
    $user = get_random_user($party->id);
    
    if ( $user->can_checkin($party) ) {
      $user->checkin($party->id);
    
      set_message( "Randomly checked in $user->full_name to {$party->place->name} on $party->date." );
    }
    else {
      set_message( "Couldn't checkin $user->full_name. " . get_reason_message($user->reason()) );
    }
    
    redirect('admin/parties');
  }
  
}
