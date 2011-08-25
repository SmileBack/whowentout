<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Welcome extends MY_Controller
{
  
  
  public function index() {
    serverinbox_element('party', 23);
  }
  
  public function indexeyeaa() {
    $this->set_data(intval($this->input->get('version')));
  }
  
  private function set_data($data) {
    $amazon_public_key = ci()->config->item('amazon_public_key');
    $amazon_secret_key = ci()->config->item('amazon_secret_key');
    $bucket = 'whowentoutevents';
    
    $encoded_data = 'process(' . json_encode($data) . ')';
    
    $s3 = new AmazonS3($amazon_public_key, $amazon_secret_key);
    $s3->use_ssl = false;
    $s3->create_object($bucket, 'event.txt', array(
      'body' => $encoded_data,
      'contentType' => 'text/plain',
      'acl' => AmazonS3::ACL_PUBLIC,
    ));
    print $s3->get_object_url($bucket, 'event.txt');
  }
  
  public function chat() {
    $this->load_view('test_chart_view');
  }
  
  
  
  private function get_full_names_testtxt() {
    $content = file_get_contents('./test.txt');
    $lines = explode("\n", $content);
    return $lines;
  }
  
  private function get_full_names_dansgwnetwork() {
    $user = user(array('last_name' => 'Berenholtz'));
    $network_id = college()->facebook_network_id;
    $query = "SELECT uid, name, affiliations FROM user "
           . " WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=$user->facebook_id)"
           ;//. " AND $network_id IN affiliations.nid" ;
    $result = fb()->api(array(
      'method' => 'fql.query',
      'query' => $query,
    ));
    $groups = array();
    foreach ($result as $friend) {
      foreach ($friend['affiliations'] as $affiliation) {
        if ($affiliation['type'] == 'college') {
          $groups[ $affiliation['name'] ][] = $friend['name'];
        }
      }
    }
    uasort($groups, function($a, $b) {
      return count($a) > count($b) ? -1 : 1;
    });
    return $groups['GWU'];
  }
  
  private function get_full_names() {
    $content = file_get_contents('./class2014.txt');
    $lines = explode("\n", $content);
    return $lines;
  }
  
  private function count_matches($pattern) {
    return ci()->db->select('student_full_name')
              ->from('college_students')
              ->like('student_full_name', $pattern, 'both')
              ->count_all_results();
  }
  
  function index_yea_matches() {
    $counts = array();
    foreach (range('a', 'z') as $l) {
      $patt = 'gabr' . $l;
      $counts[$patt] = $this->count_matches($patt);
    }
    var_dump($counts);
  }
  
  function index3() {
    $matches = 0;
    $full_names = $this->get_full_names();
    foreach ($full_names as $full_name) {
      $output = $full_name;
      $student = college()->find_student($full_name);
      if ($student) {
        $matches++;
      }
      else {
        $output = "<b>$output</b>";
      }
      print $output;
        if ($student) print " ($student->student_full_name)";
        
      print '<br>';
    }
    print "$matches/" . count($full_names) . ' matches';
  }
  
  function index2() {
    $user = user(array('last_name' => 'Berenholtz'));
    $network_id = college()->facebook_network_id;
    $query = "SELECT uid, name, affiliations FROM user "
           . " WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=$user->facebook_id)"
           ;//. " AND $network_id IN affiliations.nid" ;
    $result = fb()->api(array(
      'method' => 'fql.query',
      'query' => $query,
    ));
    $groups = array();
    foreach ($result as $friend) {
      foreach ($friend['affiliations'] as $affiliation) {
        if ($affiliation['type'] == 'college') {
          $groups[ $affiliation['name'] ][] = $friend['name'];
        }
      }
    }
    uasort($groups, function($a, $b) {
      return count($a) > count($b) ? -1 : 1;
    });
    print '<pre>';
    print_r($groups['GWU']);
    print '</pre>';
//    $college_offset = college()->current_time(TRUE)->getOffset();
//    $browser_offset = college()->current_time()->setTimezone( new DateTimeZone('Etc/GMT-7'))->getOffset();
//    $diff = $college_offset - $browser_offset;
//    print $diff;
  }
  
}
