<?php

require_once APPPATH . 'libraries/fb/facebook.php';

function current_user() {
  return XUser::current();
}

/**
 * @return int
 *   The id of the current user.
 */
function get_user_id() {
  return ci()->session->userdata('user_id');
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

function anchor_facebook_login($title = 'Facebook Login', $attributes = '') {
  $link = fb()->getLoginUrl();
  return anchor($link, $title, $attributes);
}

function deny_anonymous() {
  if ( ! logged_in() )
    show_404();
}

function require_login() {
  if ( ! logged_in() ) {
    redirect('login');
    exit;
  }
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

function get_facebook_data($user_id) {
  $user = get_user($user_id);
  $facebook_id = $user->facebook_id;
  $user_profile = fb()->api("/$facebook_id");
  return $user_profile;
}

