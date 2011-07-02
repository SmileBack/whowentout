<?php

require_once APPPATH . 'libraries/fb/facebook.php';

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

function logged_in() {
  return XUser::logged_in();
}

function deny_anonymous() {
  if ( ! logged_in() )
    show_404();
}

function current_user() {
  return XUser::current();
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

