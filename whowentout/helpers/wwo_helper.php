<?php

function ci() {
  return get_instance();
}

/**
 * @return ImageRepository 
 */
function images() {
  static $images = NULL;
  if (!$images)
    $images = new ImageRepository ('pics');
  
  return $images;
}

function post($key = NULL) {
  if ($key) {
    return ci()->input->post($key);
  }
  else {
    $post = array();
    foreach ($_POST as $k => $v) {
      $post[$k] = ci()->input->post($k);
    }
    return $post;
  }
}

function set_message($message) {
  ci()->session->set_userdata('message', $message);
}

function pull_message() {
  $message = get_message();
  ci()->session->unset_userdata('message');
  return $message;
}

function get_message() {
  return ci()->session->userdata('message');
}

/**
 * @return XCollege
 */
function current_college() {
  return XCollege::current();
}

function parties_dropdown($parties) {
  $options = array();
  foreach ($parties as $party) {
    $options[$party->id] = $party->place->name;
  }
  
  return form_dropdown('party_id', $options);
}

function grad_year_dropdown($selected_year = NULL) {
  $options = array();
  for ($i = 1; $i <= 4; $i++) {
    $year = today()->modify("+$i year")->format('Y');
    $options[$year] = $year;
  }
  return form_dropdown('grad_year', $options, $selected_year);
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

function get_state_abbreviation($full_state_name) {
  require_once 'state_data.php';
  $data = _get_state_data();
  return isset($data[$full_state_name]) ? $data[$full_state_name] : NULL;
}
