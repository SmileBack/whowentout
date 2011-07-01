<?php

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

function logout() {
  set_user_id(0);
}

function logged_in() {
  return get_user_id() != NULL;
}

function current_user() {
  if (!logged_in())
    return _anonymous_user();
  
  return ci()->db
              ->select('users.id AS id, first_name, last_name, college_name, grad_year, profile_pic,
                      email, gender, date_of_birth')
              ->from('users')
              ->where('users.id', get_user_id())
              ->join('colleges', 'users.college_id = colleges.id')
              ->get()->row();
}

function _anonymous_user() {
  return (object) array(
    'id' => 0,
    'first_name' => 'Anonymous',
  );
}
