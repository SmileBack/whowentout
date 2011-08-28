<?php

class XPlace extends XObject
{
  protected static $table = 'places';
  
  function get_college() {
    return XCollege::get($this->college_id);
  }
  
  function get_admin() {
    if ($this->admin_id == NULL)
      return NULL;
    
    return user($this->admin_id);
  }
  
  function get_num_parties() {
    return $this->db()->from('parties')
                ->where('place_id', $this->id)
                ->count_all_results();
  }
  
}
