<?php

class Admin extends MY_Controller
{
  
  function fake_time() {
    $fake_time = post('fake_time');
    $fake_time_point = get_option('fake_time_point');
    
    if ($fake_time != NULL) {
      $fake_time_point = array(
        'fake_time' => new DateTime($fake_time),
        'real_time' => new DateTime(),
      );
      set_option('fake_time_point', $fake_time_point);
    }
    
    $delta = date_diff($fake_time_point['real_time'], $fake_time_point['fake_time']);
    
    $data = array();
    $data['fake_time'] = date_format($fake_time_point['fake_time'], 'Y-m-d H:i:s');
    $data['real_time'] = date_format($fake_time_point['real_time'], 'Y-m-d H:i:s');
    $data['delta'] = $delta->format('%d d, %h h, %m m, %s s');
    
    $this->load_view('admin/fake_time_view', $data);
  }
  
  function parties() {
    $this->load_view('admin/edit_parties_view');
  }
  
  function add_party() {
    $date = new DateTime(post('date'), get_college_timezone());
    $place_id = post('place_id');
    
    $place = XPlace::get($place_id);
    $formatted_date = $date->format('Y-m-d');
    
    current_college()->add_party($formatted_date, $place_id);
    set_message("Created party on $formatted_date at $place->name.");
    redirect('admin/parties');
  }
  
  function random_checkin($party_id) {
    $party = XParty::get($party_id);
    $user = get_random_user($party->id);
    
    $user->checkin($party->id);
    
    set_message("Randomly checked in $user->full_name to {$party->place->name} on $party->date.");
    redirect('admin/parties');
  }
  
}
