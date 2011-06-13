<?php

function get_timer() {
	return '2hrs 52 mins';
}

function load_view($view_name, $data = array()) {
  $CI =& get_instance(); 
  return $CI->load->view($view_name, $data, TRUE);
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
