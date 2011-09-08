<?php

class CI_Option
{
  
  private $ci;
  private $cache = array();
  
  function __construct() {
    $this->ci =& get_instance();
    $this->db = $this->ci->db;
  }
  
  function set($name, $value) {
    $value = serialize($value);
    
    if ($this->exists($name)) {
      $this->db->where('id', $name);
      $this->db->update('options', array('id' => $name, 'value' => $value));
    }
    else {
      $this->db->insert('options', array('id' => $name, 'value' => $value));
    }
    
    $this->cache[$name] = unserialize($value);
  }
  
  function get($name, $default = NULL) {
    if ( ! isset($this->cache[$name]) ) {
      $option = $this->db->select('value')
                         ->from('options')
                         ->where('id', $name)
                         ->get()->row();
      
      if ($option == NULL && $default !== NULL) {
        $this->set($name, $default);
      }
      else {
        $this->cache[$name] = unserialize($option->value);
      }
    }
    
    return $this->cache[$name];
  }
  
  function exists($name) {
    return $this->get($name) !== NULL;
  }
  
  function delete($name) {
    $this->db->delete('options', array('id' => $name));
    unset($this->cache[$name]);
  }
  
}
