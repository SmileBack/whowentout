<?php

require_once APPPATH . 'libraries/fb/facebook.php';

function get_facebook_id($user_name) {
  $data = fb()->api("/$user_name");
  return $data['id'];
}

/**
 * @param int $facebook_id
 * @param array $data
 * @return XUser
 */
function create_user($facebook_id, $data = array()) {
   //we were given a username
  if ( ! preg_match('/^\d+$/', $facebook_id) ) {
    $facebook_id = get_facebook_id($facebook_id);
  }
  
  $data['facebook_id'] = $facebook_id;
  $data['registration_time'] = current_time()->format('Y-m-d H:i:s');
  $user = XUser::create($data);
  $user->update_facebook_data();
  return $user;
}

function create_college($name, $facebook_network_id, $facebook_school_id = NULL) {
  $data = array(
    'name' => $name,
    'facebook_network_id' => $facebook_network_id,
  );
  
  if ($facebook_school_id)
    $data['facebook_school_id'] = $facebook_school_id;
  
  $college = XCollege::get(array('facebook_network_id' => $facebook_network_id));
  
  if ($college == NULL) {
    $college = XCollege::create($data);
  }
  else {
    foreach ($data as $k => $v) {
      $college->$k = $v;
    }
    $college->save();
  }
  
  return $college;
}

function preserve_login_action() {
  ci()->session->keep_flashdata('login_action');
}

function unpack_login_action() {
  $data = login_action();
  foreach ($data['post'] as $key => $value) {
    $_POST[$key] = $value;
  }
}

function require_login($data = array()) {
  if ( ! logged_in() ) {
    $data['destination'] = uri_string();
    $data['post'] = post();

    ci()->session->set_flashdata('login_action', $data);
    
    redirect('login');
  }
  elseif ( logged_in() && login_action() ) {
    unpack_login_action();
  }
}

function login_destination() {
  $destination = login_action('destination');
  return $destination ? $destination : 'dashboard';
}

function login_action($key = NULL) {
  $data = ci()->session->flashdata('login_action');
  
  if ($data == NULL)
    return NULL;
  
  return $key ? $data[$key] : $data;
}

/**
 * @return XUser
 */
function current_user() {
  return XUser::current();
}

/**
 * @return int
 *   The id of the current user.
 */
function get_user_id() {
  return ci()->session->userdata('user_id');
  uri_string();
}

function set_user_id($user_id) {
  ci()->session->set_userdata('user_id', $user_id);
}

function login() {
  $facebook_id = fb()->getUser();
  $new_user = FALSE;
  if ($facebook_id) {
    $current_user = XUser::get(array(
      'facebook_id' => $facebook_id
    ));
    
    if ($current_user == NULL) {
      $current_user = create_user($facebook_id);
      $new_user = TRUE;
    }
    
    set_user_id($current_user->id);
    
    return TRUE;
  }
  else {
    return FALSE;
  }
}

function fake_login($user_id) {
  $current_user = XUser::get($user_id);
  set_user_id($current_user->id);
}

function logout() {
  return XUser::logout();
}

function logged_in() {
  return XUser::logged_in();
}

function facebook_login_url() {
  $permissions = array(
    'user_birthday',
    'user_education_history',
    'user_hometown',
    'email',
    'user_events',
  );
  return fb()->getLoginUrl(array(
    'scope' => implode(',', $permissions),
  ));
}

function anchor_facebook_login($title = 'Facebook Login', $attributes = array()) {
  return anchor(facebook_login_url(), $title, $attributes);
}

function deny_anonymous() {
  if ( ! logged_in() )
    show_404();
}

function fb() {
  static $facebook = NULL;
  if ($facebook == NULL) {
    $facebook = new Facebook(array(
      'appId' => ci()->config->item('facebook_app_id'),
      'secret' => ci()->config->item('facebook_secret_key'),
    ));
  }
  return $facebook;
}
