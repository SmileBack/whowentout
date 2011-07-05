<?php

class XUser extends XObject
{
  
  protected static $table = 'users';
  
  static function current() {
    if (logged_in()) {
      return self::get( get_user_id() );
    }
    else {
      return new XAnonymousUser();
    }
  }
  
  static function login() {
    $facebook_id = fb()->getUser();
    if ($facebook_id) {
      $current_user = XUser::get(array('facebook_id' => $facebook_id));
      if ($current_user == NULL) {
        $current_user = XUser::create(array(
          'facebook_id' => $facebook_id,
          'registration_time' => gmdate('Y-m-d H:i:s'),
        ));
        $current_user->update_facebook_data();
      }
      
      set_user_id($current_user->id);
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
  
  static function fake_login($user_id) {
    $current_user = XUser::get($user_id);
    set_user_id($current_user->id);
  }
  
  function logout() {
    //delete user id
    set_user_id(0);
    
    //destroy facebook session data
    $app_id = fb()->getAppId();
    unset($_SESSION["fb_{$app_id}_code"]);
    unset($_SESSION["fb_{$app_id}_access_token"]);
    unset($_SESSION["fb_{$app_id}_user_id"]);
    unset($_SESSION["fb_{$app_id}_state"]);
  }

  
  static function logged_in() {
    return get_user_id() != NULL;
  }
  
  function is_anonymous() {
    return FALSE;
  }
  
  function get_college() {
    return XCollege::get($this->college_id);
  }
  
  function checkin($party_id) {
    if (!$this->can_checkin($party_id)) {
      return FALSE;
    }
    
    $this->db()->insert('party_attendees', array(
                          'user_id' => $this->id,
                          'party_id' => $party_id,
                          'checkin_time' => gmdate('Y-m-d H:i:s'),
                        ));
    
    return TRUE;
  }
  
  function can_checkin($party_id) {
    $party = XParty::get($party_id);
    $party_date = new DateTime($party->date, get_college_timezone());
    
    // You've already attended a party
    if ( $this->has_attended_party_on_date($party_date) ) {
      return FALSE;
    }
    
    // You are not within the bounds of the checkin time.
    if (doors_are_closed())
      return FALSE;
    
    return TRUE;
  }
  
  function recent_parties() {
    $parties = array();
    $rows = $this->db()
                 ->select('party_id AS id')
                 ->from('party_attendees')
                 ->join('parties', 'party_attendees.party_id = parties.id')
                 ->where('user_id', $this->id)
                 ->order_by('date', 'desc')
                 ->get()->result();
    
    foreach ($rows as $row) {
      $parties[] = XParty::get( $row->id );
    }
    
    return $parties;
  }
  
  /**
   * Make this user smile at $receiver_id for $party_id.
   * @param int $receiver_id
   * @param int $party_id
   * @return bool
   *   Whether the smile actually was permitted.
   */
  function smile_at($receiver_id, $party_id) {
    if (!$this->can_smile_at($receiver_id, $party_id))
      return FALSE;
    
    $this->db()->insert('smiles', array(
                          'sender_id' => $this->id,
                          'receiver_id' => $receiver_id,
                          'party_id' => $party_id,
                          'smile_time' => gmdate('Y-m-d H:i:s'),
                       ));
    
    return TRUE;
  }
  
  function can_smile_at($receiver_id, $party_id) {
    if ( ! $this->has_attended_party($party_id) ) {
      return FALSE;
    }
    
    if ( $this->smiles_left($party_id) <= 0 ) {
      return FALSE;
    }
    
    if ( $this->has_smiled_at($receiver_id, $party_id) ) {
      return FALSE;
    }
    
    return TRUE;
  }
  
  /**
   * Tells you if this user smiled at $receiver_id at $party_id.
   * @param int $receiver_id
   * @param int $party_id
   * @return bool
   */
  function has_smiled_at($receiver_id, $party_id) {
    return $this->db()->from('smiles')
                      ->where('sender_id', $this->id)
                      ->where('receiver_id', $receiver_id)
                      ->where('party_id', $party_id)
                      ->count_all_results() > 0;
  }
  
  function smiles_left($party_id) {
    $smiles_used = $this->db()
                        ->from('smiles')
                        ->where('sender_id', $this->id)
                        ->where('party_id', $party_id)
                        ->count_all_results();
    return (3 - $smiles_used);
  }
  
  function smiles_received($party_id) {
    return $this->db()
                ->from('smiles')
                ->where('receiver_id', $this->id)
                ->where('party_id', $party_id)
                ->count_all_results();
  }
  
  /**
   * Tells you if this user was smiled at by $sender_id at $party_id.
   * 
   * @param int $sender_id
   * @param int $party_id
   * @return bool 
   */
  function was_smiled_at($sender_id, $party_id) {
    return $this->db()
                ->from('smiles')
                ->where('sender_id', $sender_id)
                ->where('receiver_id', $this->id)
                ->where('party_id', $party_id)
                ->count_all_results() == 1;
  }
  
  function matches($party_id) {
    $rows =  $this->db()
                  ->query("
                          SELECT users.id AS id FROM users
                            WHERE id IN 
                              (SELECT receiver_id FROM smiles WHERE sender_id = ? AND party_id = ?) #ids of people you smiled at
                            AND id IN
                              (SELECT sender_id FROM smiles WHERE receiver_id = ? AND party_id = ?)  #ids of people who smiled at you
                          ", array($this->id, $party_id, $this->id, $party_id))
                  ->result();
    $matches = array();
    foreach ($rows as $row) {
      $matches[] = XUser::get($row->id);
    }
    return $matches;
  }
  
    
  /**
   * Return the party that this user attended on $date.
   * 
   * @param int $user_id
   * @param timestamp $date
   * @return object
   *   A party object 
   */
  function get_attended_party($date) {
    $date = make_local($date);
    
    $row = $this->db()
                ->select('party_id AS id')
                ->from('party_attendees')
                ->join('parties', 'party_attendees.party_id = parties.id')
                ->where('user_id', $this->id)
                ->where('date', date_format($date, 'Y-m-d'))
                ->get()->row();
    
    if ($row == NULL)
      return NULL;
    
    return XParty::get($row->id);
  }
  
  function smiles_received_message($party_id) {
    $genders = array('M' => 'guy', 'F' => 'girl');
    $smiles = $this->smiles_received($party_id);
    $people = $genders[$this->other_gender];
    
    if ($smiles != 1)
      $people = $people . 's'; //pluralize
    
    $have = $smiles == 1 ? 'has' : 'have';
    
    return "$smiles $people $have smiled at you";
  }
  
  /**
   * Tells you if this user attended a party on $date.
   * 
   * @param type $user_id
   * @param type $date
   */
  function has_attended_party_on_date($date) {
    return $this->get_attended_party($date) != NULL;
  }
  
  /**
   * Tells you if this user attended $party_id.
   * @param type $user_id
   * @param type $party_id 
   * @return bool
   */
  function has_attended_party($party_id) {
    return $this->db()->from('party_attendees')
                      ->where('user_id', $this->id)
                      ->where('party_id', $party_id)
                      ->count_all_results() > 0;
  }
  
  function fetch_facebook_data() {
    if ($this->facebook_id == NULL)
      return NULL;
    else
      return fb()->api("/$this->facebook_id");
  }
  
  function get_other_gender() {
    return $this->gender == 'M' ? 'F' : 'M';
  }
  
  function get_pic() {
    return img($this->pic_url);
  }
  
  function get_thumb() {
    return img($this->thumb_url);
  }
  
  function get_pic_url() {
    if ($this->data['pic_url'] == NULL)
      $this->download_facebook_pic();
    
    return 'pictures/normal/' . $this->data['pic_url'];
  }
  
  function get_thumb_url() {
    if ($this->data['pic_url'] == NULL)
      $this->download_facebook_pic();
    
    return 'pictures/thumb/' . $this->data['pic_url'];
  }
  
  function update_facebook_data() {
    $genders = array('male' => 'M', 'female' => 'F');
    $fbdata = $this->fetch_facebook_data();
    
    $this->first_name = $fbdata['first_name'];
    $this->last_name = $fbdata['last_name'];
    
    $this->gender = $genders[ $fbdata['gender'] ];
    
    if ($this->email == NULL)
      $this->email = $fbdata['email'];
    
    $this->date_of_birth = date('Y-m-d', strtotime($fbdata['birthday']));
    
    if ($this->college_id == NULL)
      $this->update_college_from_facebook($fbdata);
    
    $this->hometown = $fbdata['hometown']['name'];
    
    if ($this->pic_url == NULL)
      $this->download_facebook_pic();
    
    $this->save();
  }
  
  private function update_college_from_facebook($fbdata) {
    $colleges = $this->_get_possible_colleges($fbdata);
    $college = $colleges[0];
    $this->college_id = $college->id;
    $this->grad_year = $this->_get_grad_year($college, $fbdata);
  }

  function download_facebook_pic() {
    $facebook_pic_url = "https://graph.facebook.com/$this->facebook_id/picture?type=large&access_token=" . fb()->getAccessToken();
    
    $img = WideImage::loadFromFile($facebook_pic_url);
    $img->saveToFile("pictures/raw_facebook/$this->id.jpg");
    
    $normal = $img->resize(150, 200);
    $normal = $normal->resizeCanvas(150, 200, 'center', 'center', '000000', 'up');
    $normal->saveToFile("pictures/normal/$this->id.jpg");
    
    $thumb = $img->resize(105, 140);
    $thumb = $thumb->resizeCanvas(105, 140, 'center', 'center', '000000', 'up');
    $thumb->saveToFile("pictures/thumb/$this->id.jpg");
    
    $this->pic_url = "$this->id.jpg";
    $this->save();
  }
  
  private function _get_possible_colleges($fbdata) {
    $colleges = array();
    $affiliations = $this->_get_affiliations();
    foreach ($affiliations as $affiliation) {
      $network_id = $affiliation['nid'];
      $college = XCollege::get(array(
        'facebook_network_id' => $network_id
      ));
      
      if ($college == NULL)
        continue;
      
      if ($college->enabled == '0')
        continue;
      
      foreach ($fbdata['education'] as $education) {
        if ($education['school']['id'] == $college->facebook_network_id) {
          $this->college_id = $college->id;
        }
      }
      
      $colleges[] = $college;
    }
    return $colleges;
  }
  
  private function _get_affiliations() {
    $result = fb()->api(array(
      'method' => 'fql.query',
      'query' => "SELECT affiliations FROM user WHERE uid = $this->facebook_id",
    ));
    return $result[0]['affiliations'];
  }
  
  private function _get_grad_year($college, $fbdata) {
    foreach ($fbdata['education'] as $education) {
      if ($education['school']['id'] == $college->facebook_school_id && isset($education['year'])) {
        return $education['year']['name'];
      }
    }
    return '';
  }
  
}

class XAnonymousUser extends XObject
{
  
  function __construct() {
    $this->data = array(
      'id' => 0,
      'first_name' => 'Anonymous',
    );
  }
  
  function is_anonymous() {
    return TRUE;
  }
  
}