<?php

function ci() {
  return get_instance();
}

/**
 * @return XCollege
 */
function college() {
  return XCollege::current();
}

/**
 * @return XPlace
 */
function place($place_id) {
  return XPlace::get($place_id);
}

/**
 * @return XParty
 */
function party($party_id) {
  return XParty::get($party_id);
}

function option_exists($name) {
  return ci()->db->select('id')
                 ->from('options')
                 ->where('id', $name)
                 ->count_all_results() > 0;
}

function get_option($name, $default = NULL) {
  $option = ci()->db->select('value')
                    ->from('options')
                    ->where('id', $name)
                    ->get()->row();
  
  if ($option == NULL && $default !== NULL) {
    set_option($name, $default);
    return $default;
  }
  
  return $option ? unserialize($option->value) : NULL;
}

function unset_option($name) {
  ci()->db->delete('options', array('id' => $name));
}

function set_option($name, $value) {
  $option = get_option($name);
  $value = serialize($value);
  
  if (option_exists($name)) {
    ci()->db->where('id', $name);
    ci()->db->update('options', array('id' => $name, 'value' => $value));
  }
  else {
    ci()->db->insert('options', array('id' => $name, 'value' => $value));
  }
}

/**
 * @return ImageRepository 
 */
function images() {
  static $images = NULL;
  
  if (!$images) {
    ci()->config->load('imagerepository');
    $config = ci()->config->item('imagerepository');
    $config = $config[ $config['active_group'] ];
    
    if ($config['source'] == 'filesystem') {
      return new FilesystemImageRepository($config['path']);
    }
    elseif ($config['source'] == 's3') {
      return new S3ImageRepository($config['bucket']);
    }
    
  }
  
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

function parties_dropdown($parties) {
  if (empty($parties))
    return '';
  
  $options = array();
  foreach ($parties as $party) {
    $options[$party->id] = $party->place->name;
  }
  
  return form_dropdown('party_id', $options);
}

function places_dropdown($places) {
  $options = array();
  foreach ($places as $place) {
    $options[$place->id] = $place->name;
  }
  return form_dropdown('place_id', $options);
}

function grad_year_dropdown($selected_year = NULL) {
  $options = array();
  for ($i = 1; $i <= 4; $i++) {
    $year = college()->today()->modify("+$i year")->format('Y');
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

function where_friends_went_pie_chart_data() {
  if ( ! logged_in() )
    return NULL;
  
  $data = array();
  
  foreach (current_user()->where_friends_went() as $party_id => $friend_ids) {
    $party = party($party_id);
    $data[] = array($party->place->name, count($friend_ids), $party->id);
  }
  
  return $data;
}

function get_reason_message($reason) {
  $reasons = ci()->config->item('reasons');
  
  if (is_string($reason))
    return $reason;
  else
    return $reasons[$reason];
}

function update_facebook_friends($user, $force_update = FALSE) {
  $access_token = get_option('admin_facebook_access_token');
  fb()->setAccessToken($access_token);
  
  $user = user($user);
  $user->update_friends_from_facebook($force_update);
}

/**
 *
 * @param string$filepath
 *   The path to the file in question.
 * @return bool
 *   Whether the file located at $filepath is a valid image.
 */
function is_valid_image($filepath) {
  return getimagesize($filepath) !== FALSE;
}

function send_email($user, $subject, $body) {
  $user = user($user);
  
  if ($user == NULL || $user->email == NULL)
    return FALSE;          
  
  require_once APPPATH . 'libraries/swift/swift_required.php';
  
  ci()->load->config('email');
  $config = (object)ci()->config->item('email_server');
  
  $transport = Swift_SmtpTransport::newInstance($config->server, $config->port, $config->encryption)
                ->setUsername($config->username)
                ->setPassword($config->password);

  $mailer = Swift_Mailer::newInstance($transport);
  
  $message = Swift_Message::newInstance($subject)
              ->setBody($body, 'text/html')
              ->setFrom($config->username, 'WhoWentOut')
              ->setTo(array($user->email => $user->full_name));
              
  $result = $mailer->send($message);
}

function curl_file_get_contents($url) {
  $c = curl_init();
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($c, CURLOPT_URL, $url);
  $contents = curl_exec($c);
  curl_close($c);

  if ($contents)
    return $contents;
  else
    return FALSE;
}
