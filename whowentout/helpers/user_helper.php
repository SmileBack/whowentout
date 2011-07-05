<?php

require_once APPPATH . 'libraries/fb/facebook.php';

function require_login($data = array()) {
  $data['destination'] = uri_string();
  $data['post'] = post();
  
  ci()->session->set_userdata('login_action', $data);
  redirect('login');
}

function login_destination() {
  $destination = login_action('destination');
  return $destination ? $destination : 'dashboard';
}

function login_message() {
  return login_action('message');
}

function login_post($key = NULL) {
  $data = login_action('post');
  
  if ($data == NULL)
    return NULL;
  
  return $key ? $data[$key] : $data;
}

function login_action($key = NULL) {
  $data = ci()->session->userdata('login_action');
  
  if ($data == NULL)
    return NULL;
  
  return $key ? $data[$key] : $data;
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

function login() {
  return XUser::login();
}

function fake_login($user_id) {
  return XUser::fake_login($user_id);
}

function logout() {
  return XUser::logout();
}

function logged_in() {
  return XUser::logged_in();
}

function anchor_facebook_login($title = 'Facebook Login', $attributes = array()) {
  $permissions = array(
    'user_birthday',
    'user_education_history',
    'user_hometown',
    'email',
    'user_events',
  );
  $link = fb()->getLoginUrl(array(
    'scope' => implode(',', $permissions),
  ));
  return anchor($link, $title, $attributes);
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
