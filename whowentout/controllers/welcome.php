<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
  
  function index() {
//    $variations = college()->student_name_variations('Ron Webb');
//    var_dump($variations);
    $student = college()->find_student('Cassie Scheinman');
  }
  
  function index2() {
  }
  
}
