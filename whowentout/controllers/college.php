<?php

class College extends MY_Controller
{
  
  function students() {
    $q = $this->input->get('q');
    $college_id = college()->id;
    $results = ci()->db->from('college_students')
                   ->where('college_id', $college_id)
                   ->like('student_full_name', $q, 'both')
                   ->limit(10)
                   ->get()->result();
    
    $matches = array();
    foreach ($results as $result) {
      $matches[] = array('id' => $result->id, 'title' => $result->student_full_name);
    }
    print json_encode($matches);exit;
  }
  
}