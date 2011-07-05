<?php

class XParty extends XObject
{
  protected static $table = 'parties';
  
  function get_place() {
    return XPlace::get($this->place_id);
  }
  
  function get_admin() {
    if ($this->admin_id == NULL)
      return NULL;
    
    return XUser::get($this->admin_id);
  }
  
  function attendees($gender = 'F') {
    $attendees = array();
    $rows = $this->db()->select('user_id AS id')
                       ->from('party_attendees')
                       ->join('users', 'users.id = party_attendees.user_id')
                       ->where('party_id', $this->id)
                       ->where('gender', $gender)
                       ->get()->result();
    
    foreach ($rows as $row) {
      $attendees[] = XUser::get($row->id);
    }
    
    return $attendees;
  }
  
  function recent_attendees($gender = 'F') {
    $attendees = array();
    
    $rows = $this->db()->select('user_id AS id')
                       ->from('party_attendees')
                       ->join('users', 'users.id = party_attendees.user_id')
                       ->where('party_id', $this->id)
                       ->where('gender', $gender)
                       ->order_by('checkin_time', 'desc')
                       ->limit(4)
                       ->get()->result();
    
    foreach ($rows as $row) {
      $attendees[] = XUser::get($row->id);
    }
    
    return $attendees;
  }
  
}