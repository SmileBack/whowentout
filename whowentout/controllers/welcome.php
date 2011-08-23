<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
  
  function index() {
    $college_offset = college()->current_time(TRUE)->getOffset();
    $browser_offset = college()->current_time()->setTimezone( new DateTimeZone('Etc/GMT-7'))->getOffset();
    $diff = $college_offset - $browser_offset;
    print $diff;
  }
  
}
