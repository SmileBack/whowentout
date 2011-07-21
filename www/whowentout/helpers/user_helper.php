<?php

require_once APPPATH . 'libraries/fb/facebook.php';

function get_facebook_id($user_name) {
  $data = fb()->api("/$user_name");
  return $data['id'];
}

/**
 * @return XUser
 */
function user($user_id) {
  return XUser::get($user_id);
}

/**
 * @param int $facebook_id
 * @param array $data
 * @return XUser
 */
function create_user($facebook_id, $data = array()) {
   //we were given a username
  if ($facebook_id && ! preg_match('/^\d+$/', $facebook_id) ) {
    $facebook_id = get_facebook_id($facebook_id);
  }
  
  $data['facebook_id'] = $facebook_id;
  $data['registration_time'] = current_time()->format('Y-m-d H:i:s');
  $user = XUser::create($data);
  $user->update_facebook_data();
  $user->refresh_image('facebook');
  
  return $user;
}

/**
 * 
 * DELETES A USER AND ALL OF HIS INFORMATION!
 * THIS INCLUDES ALL SMILES AND ATTENDED PARTIES.
 * 
 * @param XUser $user
 *   The ID of the user.
 */
function destroy_user($user) {
  $user = user($user);
  
  if ($user == NULL)
    return;
  
  if (current_user() == $user)
    logout();
  
  ci()->db->delete('party_attendees', array('user_id' => $user->id));
  ci()->db->delete('smiles', array('sender_id' => $user->id));
  ci()->db->delete('smiles', array('receiver_id' => $user->id));
  
  $user->delete();
}

function user_exists($user_id) {
  return user($user_id) != NULL;
}

function require_profile_edit() {
  if (current_user()->never_edited_profile())
    redirect('user/edit');
}

/**
 * @return XCollege
 */
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

function require_login($action = array()) {
  if ( ! logged_in() ) {
    ci()->session->set_userdata('login_action', $action);
    redirect('login');
  }
}

function login_action() {
  return ci()->session->userdata('login_action');
}

function clear_login_action() {
  ci()->session->unset_userdata('login_action');
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

/**
 * @return XUser
 */
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
    
    return current_user();
  }
  else {
    return FALSE;
  }
}

function fake_login($user_id) {
  $current_user = user($user_id);
  set_user_id($current_user->id);
}

function logout() {
  return XUser::logout();
}

function logged_in() {
  return XUser::logged_in();
}

function connected_to_facebook() {
  return fb()->getUser() != 0;
}

function facebook_login_url() {
  $permissions = array(
    'user_birthday',
    'user_education_history',
    'user_hometown',
    'email',
    'user_events',
    'offline_access',
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
