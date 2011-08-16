<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
  
  function index() {
    $contents = file_get_contents('./students.txt');
    $lines = explode("\n", $contents);
    foreach ($lines as $line) {
      $segments = explode('%%', $line);
      $name = trim($segments[0]);
      $name_parts = preg_split('/\s*,\s*/', $name);
      $first_name = $name_parts[0];
      $last_name = $name_parts[1];
      $full_name = "$first_name $last_name";
      $email = trim($segments[1]);
      $this->db->insert('college_students', array('college_id' => 1, 'student_full_name' => $full_name, 'student_email' => $email));
    }
  }
  
}
