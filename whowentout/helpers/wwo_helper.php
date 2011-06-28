<?php

function get_user_id() {
  return 1;
}

function get_college_id() {
  return 1;
}

function parties_dropdown($college_id, $date) {
  $parties = model('college_model')->get_parties($college_id, $date);
  $options = array();
  foreach ($parties as $party) {
    $options[$party->id] = $party->place_name;
  }
  return form_dropdown('party_id', $options);
}

function get_closing_time() {
  $closing_time_string = date('Y-m-d') . ' ' . '23:00:00';
  return strtotime($closing_time_string);
}

/**
 * Gives you the timestamp date for today.
 * @param bool $make_local
 *   Whether to give the time local to the user.
 * @return timestamp
 *   A Unix timestamp for todays date. This could be local or GMT based on $make_local. 
 */
function today($make_local = FALSE) {
  $current_time = now();
  if ($make_local) {
    $current_time = gmt_to_local($current_time, 'UM8', TRUE); //time in California. MUST CHANGE TO UM5 AT LAUNCH!!!
  }
  
  return strtotime(date('Y-m-d', $current_time)); //truncate hours, minutes, seconds	
}

function yesterday($make_local = FALSE) {
  return strtotime('-1 day', today($make_local));
}

function load_view($view_name, $data = array()) {
  $CI =& get_instance(); 
  return $CI->load->view($view_name, $data, TRUE);
}


function model($model_name) {
  $CI =& get_instance(); 
  return $CI->$model_name;
}


/* 
 * Loads the view of a section. The sections are located in views/sections
 * $section_name The name of the section. For example, 'my_info_view'.
 * $title The (optional) title of the section. If provided, a heading will
 * be shown for the section. If left out, nothing will be shown.
 * $data An array of any variables you would like to pass into the section.
 * This works just like when you pass data in with load_view.
 */
function load_section_view($section_name, $title = '', $data = array()) {
  return load_view('section_view', array(
	'section_name' => $section_name,
    'section_title' => $title,
    'section_content' => load_view('sections/' . $section_name, $data)
  ));
}

function get_age($dob) {
	if (is_string($dob)) {
	  $dob = strtotime($dob);
	}
	$now = time();
	
	$years_elapsed = date('Y', $now) - date('Y', $dob);
	$birthday_happened = date('z', $now) >= date('z', $dob);
	return $birthday_happened ? $years_elapsed : $years_elapsed - 1;
}
