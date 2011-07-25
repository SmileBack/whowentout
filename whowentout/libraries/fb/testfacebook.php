<?php

require_once 'facebook.php';

class TestFacebook extends Facebook
{
  
  function api($options) {
    $data = NULL;
    if (is_array($options)) {
      if ($options['method'] == 'fql.query') {
        if ($options['query'] == 'SELECT affiliations FROM user WHERE uid = 776200121') {
          $data = $this->get_776200121_affiliations();
        }
      }
    }
    elseif (is_string($options)) {
      if ($options == "/776200121") {
        $data = parent::api($options);
        $this->modify_776200121_api($data);
      }
    }
    
    //default behavior
    $args = func_get_args();
    return $data ? $data : call_user_func_array(array('parent', 'api'), $args);
  }
  
  function modify_776200121_api(&$data) {
    $data['education'][] =   array (
      'school' => 
      array (
        'id' => '108727889151725',
        'name' => 'George Washington University',
      ),
      'year' => 
      array (
        'id' => '201638419856163',
        'name' => '2011',
      ),
      'type' => 'College',
    );
  }
  
  function get_776200121_affiliations() {
    return array(
      0 => 
      array (
        'affiliations' => 
        array (
          0 => 
          array (
            'nid' => '16777274',
            'name' => 'Maryland',
            'type' => 'college',
          ),
          array (
            'nid' => '16777270',
            'name' => 'GWU',
            'type' => 'college',
          ),
          2 => 
          array (
            'nid' => '16777219',
            'name' => 'Stanford',
            'type' => 'college',
          ),
        ),
      ),
    );
  }
  
}
