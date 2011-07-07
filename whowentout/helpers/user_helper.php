<?php

require_once APPPATH . 'libraries/fb/facebook.php';

function require_login($data = array()) {
  if ( ! logged_in() ) {
    $data['destination'] = uri_string();
    $data['post'] = post();

    ci()->session->set_flashdata('login_action', $data);
    
    redirect('login');
  }
  elseif ( logged_in() && login_action() ) {
    $data = login_action();
    foreach ($data['post'] as $key => $value) {
      $_POST[$key] = $value;
    }
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
