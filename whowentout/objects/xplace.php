<?php

class XPlace extends XObject
{
  protected static $table = 'places';
  
  function get_admin() {
    if ($this->admin_id == NULL)
      return NULL;
    
    return XUser::get($this->admin_id);
  }
  
}
