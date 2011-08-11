<?php

class XParty extends XObject
{
  protected static $table = 'parties';
  
  function get_place() {
    return place($this->place_id);
  }
  
  function get_college() {
    return $this->place->college;
  }
  
  function get_admin() {
    if ($this->admin_id == NULL)
      return NULL;
    
    return user($this->admin_id);
  }
  
  function attendees($sort = 'checkin_time') {
    $query = $this->attendees_query($sort);
    return $this->load_objects('XUser', $query);
  }
  
  function attendees_query($sort = 'checkin_time') {
    $query = $this->db()->select('user_id AS id')
                       ->from('party_attendees')
                       ->join('users', 'users.id = party_attendees.user_id')
                       ->where('party_id', $this->id);
    
    if ($sort == 'checkin_time') {
      $query = $query->order_by('party_attendees.id', 'desc');
    }
    elseif ($sort == 'gender') {
      $order = $this->_attendees_query_gender_sort_order();
      $query = $query->order_by('gender', $order);
      $query = $query->order_by('checkin_time', 'desc');
    }
    elseif ($sort == 'name') {
      $query = $query->order_by('first_name', 'asc');
      $query = $query->order_by('last_name', 'asc');
    }
    
    return $query;
  }
  
  private function _attendees_query_gender_sort_order() {
    if ( ! logged_in() )
      return 'desc';
    if (current_user()->gender == 'M')
      return 'desc';
    if (current_user()->gender == 'F')
      return 'asc';
  }
  
  function get_count() {
    return $this->attendees_query()->count_all_results();
  }
  
  function get_female_count() {
    return $this->attendees_query()->where('gender', 'F')->count_all_results();
  }
  
  function get_male_count() {
    return $this->attendees_query()->where('gender', 'M')->count_all_results();
  }
  
  function recent_attendees() {
    $attendees = array();
    
    $rows = $this->db()->select('user_id AS id')
                       ->from('party_attendees')
                       ->join('users', 'users.id = party_attendees.user_id')
                       ->where('party_id', $this->id)
                       ->order_by('checkin_time', 'desc')
                       ->limit(5)
                       ->get()->result();
    
    foreach ($rows as $row) {
      $attendees[] = user($row->id);
    }
    
    return $attendees;
  }
  
}
