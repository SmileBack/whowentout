<?php

class XObject
{
  
  static $rows = array();
  /**
   * @param type $id
   * @return XUser
   */
  static function get($id) {
    $class = get_called_class();
    
    if ( ! isset(self::$rows[$class]) ) {
      self::$rows[$class] = array();
    }
    
    if ( isset(self::$rows[$class][$id]) ) {
      return self::$rows[$class][$id];
    }
    else {
      self::$rows[$class][$id] = new $class($id);
      return self::$rows[$class][$id];
    }
    
  }
  
  protected $prev_data = array();
  protected $data = array();
  
  function __construct($id = NULL) {
    $this->load($id);
  }
  
  protected function load($id = NULL) {
    if ($id) {
      $id = intval($id);
      
      $this->data = $this->db()
                  ->from($this->table)
                  ->where('id', $id)
                  ->get()->row();
      $this->data->id = $id;
      
      $this->data = (array) $this->data;
      $this->prev_data = $this->data;
    }
  }
  
  function is_new() {
    return $this->id == NULL;
  }
  
  function save() {
    if ($this->is_new()) {
      $this->insert();
    }
    else {
      $this->update();
    }
  }
  
  function changes() {
    $changes = array();
    foreach ($this->data as $k => $v) {
      if ($this->data[$k] != $this->prev_data[$k]) {
        $changes[$k] = $this->data[$k];
      }
    }
    unset($changes['id']);
    
    return $changes;
  }
  
  private function insert() {
    $data = $this->data;
    unset($data['id']);
    $this->db()->insert($this->table, $data);
    
    $this->load( $this->db()->insert_id() ); //load values
  }
  
  private function update() {
    $changes = $this->changes();
    
    if (empty($changes))
      return;
    
    $this->db()->update($this->table, $this->changes(), array('id' => $this->id) );
    
    $this->load($this->id); //refresh values
  }
  
  function __get($name) {
    $method = "get_$name";
    if (method_exists($this, $method)) {
      return $this->$method();
    }
    
    return isset($this->data[$name]) ? $this->data[$name] : NULL;
  }
  
  function __set($name, $value) {
    if ($name == 'id')
      throw new Exception("The id property is read-only.");
    
    $this->data[$name] = $value;
  }

  function __isset($name) {
    return isset($this->data[$name]);
  }
  
  function __unset($name) {
    unset($this->data[$name]);
  }
  
  function db() {
    return ci()->db;
  }
  
}
