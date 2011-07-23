<?php

class XUser extends XObject
{
  
  protected static $table = 'users';
  
  //The reason the user can't do something
  private $reason = NULL;
  
  static function current() {
    if (logged_in()) {
      return self::get( get_user_id() );
    }
    else {
      return new XAnonymousUser();
    }
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
  
  function reason() {
    return $this->reason;
  }
  
  function is_anonymous() {
    return FALSE;
  }
  
  function get_college() {
    return XCollege::get($this->college_id);
  }
  
  function get_full_name() {
    return "$this->first_name $this->last_name";
  }
  
  function never_edited_profile() {
    return $this->last_edit == NULL;
  }
  
  function checkin($party) {
    $party = party($party);
    
    if (!$this->can_checkin($party)) {
      return FALSE;
    }
    
    $this->db()->insert('party_attendees', array(
                          'user_id' => $this->id,
                          'party_id' => $party->id,
                          'checkin_time' => gmdate('Y-m-d H:i:s'),
                        ));
    
    return TRUE;
  }
  
  function can_checkin($party_id) {
    $party = party($party_id);
    
    if ($party == NULL) {
      $this->reason = REASON_PARTY_DOESNT_EXIST;
      return FALSE;
    }
    
    $party_date = new DateTime($party->date, $party->college->timezone);
    $yesterday = yesterday(TRUE);
    
    if ($party->college != $this->college) {
      $this->reason = REASON_NOT_IN_COLLEGE;
      return FALSE;
    }
    
    if ( $party_date != $yesterday) {
      $this->reason = REASON_PARTY_WASNT_YESTERDAY;
      return FALSE;
    }
    
    // You've already attended a party
    if ( $this->has_attended_party_on_date($party_date) ) {
      $this->reason = REASON_ALREADY_ATTENDED_PARTY;
      return FALSE;
    }
    
    // You are not within the bounds of the checkin time.
    if (doors_are_closed()) {
      $this->reason = REASON_DOORS_HAVE_CLOSED;
      return FALSE;
    }
    
    $this->reason = NULL;
    return TRUE;
  }
  
  function recent_parties() {
    $parties = array();
    $rows = $this->db()
                 ->select('party_id AS id')
                 ->from('party_attendees')
                 ->join('parties', 'party_attendees.party_id = parties.id')
                 ->where('user_id', $this->id)
                 ->order_by('date', 'desc');
    return $this->load_objects('XParty', $rows);
  }
  
  /**
   * Make this user smile at $receiver for $party.
   * @param XUser $receiver
   * @param XParty $party
   * @return bool
   *   Whether the smile actually was permitted.
   */
  function smile_at($receiver, $party) {
    $receiver = user($receiver);
    $party = party($party);
    
    if ( ! $this->can_smile_at($receiver, $party) )
      return FALSE;
    
    $this->db()->insert('smiles', array(
                          'sender_id' => $this->id,
                          'receiver_id' => $receiver->id,
                          'party_id' => $party->id,
                          'smile_time' => gmdate('Y-m-d H:i:s'),
                       ));
    
    raise_event('smile', $this, $receiver, $party);
    
    return TRUE;
  }
  
  function can_smile_at($receiver, $party) {
    $receiver = user($receiver);
    $party = party($party);
    
    if ( ! $receiver ) {
      $this->reason = REASON_USER_DOESNT_EXIST;
      return FALSE;
    }
    
    if ( ! $party ) {
      $this->reason = REASON_PARTY_DOESNT_EXIST;
      return FALSE;
    }
    
    if ( $receiver->gender == $this->gender ) {
      $this->reason = REASON_CANT_SMILE_AT_SAME_GENDER;
      return FALSE;
    }
    
    if ( ! $receiver->has_attended_party($party) ) {
      $this->reason = REASON_RECEIVER_NOT_IN_PARTY;
      return FALSE;
    }
    
    if ( ! $this->has_attended_party($party) ) {
      $this->reason = REASON_NOT_IN_PARTY;
      return FALSE;
    }
    
    if ( $this->smiles_left($party) <= 0 ) {
      $this->reason = REASON_OUT_OF_SMILES;
      return FALSE;
    }
    
    if ( $this->has_smiled_at($receiver, $party) ) {
      $this->reason = REASON_ALREADY_SMILED_AT;
      return FALSE;
    }
    
    $this->reason = NULL;
    return TRUE;
  }
  
  /**
   * Tells you if this user smiled at $receiver_id at $party_id.
   * @param XUser $receiver
   * @param XParty $party
   * @return bool
   */
  function has_smiled_at($receiver, $party) {
    return $this->db()->from('smiles')
                      ->where('sender_id', $this->id)
                      ->where('receiver_id', $receiver->id)
                      ->where('party_id', $party->id)
                      ->count_all_results() > 0;
  }
  
  function smiles_left($party) {
    $party = party($party);
    
    $smiles_used = $this->db()
                        ->from('smiles')
                        ->where('sender_id', $this->id)
                        ->where('party_id', $party->id)
                        ->count_all_results();
    return (3 - $smiles_used);
  }
  
  function smiles_received($party) {
    $party = party($party);
    
    return $this->db()
                ->from('smiles')
                ->where('receiver_id', $this->id)
                ->where('party_id', $party->id)
                ->count_all_results();
  }
  
  /**
   * Tells you if this user was smiled at by $sender_id at $party_id.
   * 
   * @param XUser $sender
   * @param XParty $party
   * @return bool 
   */
  function was_smiled_at($sender, $party) {
    $sender = user($sender);
    $party = party($party);
    
    return $this->db()
                ->from('smiles')
                ->where('sender_id', $sender->id)
                ->where('receiver_id', $this->id)
                ->where('party_id', $party->id)
                ->count_all_results() == 1;
  }
  
  function matches($party) {
    $party = party($party);
    
    $rows =  $this->db()
                  ->query("
                          SELECT users.id AS id FROM users
                            WHERE id IN 
                              (SELECT receiver_id FROM smiles WHERE sender_id = ? AND party_id = ?) #ids of people you smiled at
                            AND id IN
                              (SELECT sender_id FROM smiles WHERE receiver_id = ? AND party_id = ?)  #ids of people who smiled at you
                          ", array($this->id, $party->id, $this->id, $party->id));
    return $this->load_objects('XUser', $rows);
  }
  
  function mutual_friends($person) {
    $person = user($person);
    
    $mutual_friend_fb_ids = fb()->api(array(
      'method' => 'friends.getMutualFriends',
      'source_uid' => $this->facebook_id,
      'target_uid' => $person->facebook_id,
    ));
    
    $queries = array();
    foreach ($mutual_friend_fb_ids as $fb_id) {
      $queries[$fb_id] = "SELECT uid,name FROM user WHERE uid=$fb_id";
    }
    
    $mutual_friends_result = fb()->api(array(
      'method' => 'fql.multiquery',
      'queries' => $queries,
    ));
    
    $mutual_friends = array();
    foreach ($mutual_friends_result as $mutual_friend_result) {
      $friend = (object) array(
        'facebook_id' => $mutual_friend_result['fql_result_set'][0]['uid'],
        'full_name' => $mutual_friend_result['fql_result_set'][0]['name'],
      );
      $friend->thumb = "https://graph.facebook.com/$friend->facebook_id/picture";
      $mutual_friends[] = $friend;
    }
    return $mutual_friends;
  }
  
  function friends() {
    return array(
      user(array('first_name' => 'Dan')),
      user(array('first_name' => 'Erica')),
      user(array('first_name' => 'Alex')),
      user(array('first_name' => 'Jenny')),
      user(array('first_name' => 'Rebecca')),
      user(array('first_name' => 'Claire')),
      user(array('first_name' => 'Emily')),
      user(array('first_name' => 'Maggie')),
      user(array('first_name' => 'Jackie')),
    );
    
    $this->update_friends_from_facebook();
    
    $rows = $this->db()->select('friend_id AS id')
                       ->from('friends')
                       ->where('user_id', $this->id);
    
    return $this->load_objects('XUser', $rows);
  }
  
  function update_friends_from_facebook() {
    if ( ! connected_to_facebook() )
      return;
    
    $friends = $this->_fetch_friends_from_facebook();
        
    //delete old friends data
    $this->db()->delete('friends', array('user_id' => $this->id));
    
    $rows = array();
    foreach ($friends as $friend) {
      $rows[] = array(
        'user_id' => $this->id,
        'friend_id' => $friend->id,
      );
    }
    
    $this->db()->insert_batch('friends', $rows);
  }
  
  function _fetch_friends_from_facebook() {
    $friends = array();
    
    $results = fb()->api(array(
      'method' => 'fql.query',
      'query' => "SELECT uid FROM user
                  WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $this->facebook_id)
                  AND is_app_user = 1"
    ));
    
    foreach ($results as $result) {
      $friend = user(array('facebook_id' => $result['uid']));
      if ($friend)
        $friends[] = $friend;
    }
    
    return $friends;
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
    
    return party($row->id);
  }
  
  function smiles_received_message($party) {
    $party = party($party);
    
    $genders = array('M' => 'guy', 'F' => 'girl');
    $smiles = $this->smiles_received($party->id);
    $people = $genders[$this->other_gender];
    
    if ($smiles != 1)
      $people = $people . 's'; //pluralize
    
    $have = $smiles == 1 ? 'has' : 'have';
    
    return "$smiles $people $have smiled at you";
  }
  
  function smiles_left_message($party) {
    $party = party($party);
    
    $smiles_left = $this->smiles_left($party->id);
    $smiles = 'smile';
    
    if ($smiles_left != 1)
      $smiles = $smiles . 's';
    
    return "You have $smiles_left $smiles left to give";
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
   * @param XParty $party
   * @return bool
   */
  function has_attended_party($party) {
    $party = party($party);
    return $this->db()->from('party_attendees')
                      ->where('user_id', $this->id)
                      ->where('party_id', $party->id)
                      ->count_all_results() > 0;
  }
  
  function where_friends_went() {
    $breakdown = array();
    
    $party_ids = $this->_party_ids();
    $friend_ids = $this->_friend_ids();
    
    if (empty($party_ids) || empty($friend_ids)) {
      return array();
    }
    
    $party_ids = implode(',', $party_ids);
    $friend_ids = implode(',', $friend_ids);
    
    $query = "SELECT user_id, party_id from users
              INNER JOIN party_attendees
              ON users.id = party_attendees.user_id AND party_id IN ($party_ids) AND users.id IN ($friend_ids)";
    $rows = $this->db()->query($query)->result();
    foreach ($rows as $row) {
      $breakdown[$row->party_id][] = $row->user_id;
    }
    return $breakdown;
  }
  
  private function _party_ids() {
    $ids = array();
    foreach ($this->college->open_parties( today(true) ) as $party) {
      $ids[] = $party->id;
    }
    return $ids;
  }
  
  private function _friend_ids() {
    $ids = array();
    foreach ($this->friends() as $friend) {
      $ids[] = $friend->id;
    }
    return $ids;
  }
  
  function fetch_facebook_data() {
    if ($this->facebook_id == NULL)
      return NULL;
    else
      return fb()->api("/$this->facebook_id");
  }
  
  function upload_pic() {
    $this->refresh_image('upload');
  }
  
  function use_facebook_pic() {
    images()->delete($this->id, 'upload');
    images()->delete($this->id, 'source');
    images()->refresh($this->id, 'facebook');
  }
  
  //todo: validate
  function crop_pic($x, $y, $width, $height) {
    $this->pic_x = $x;
    $this->pic_y = $y;
    $this->pic_width = $width;
    $this->pic_height = $height;
    
    $this->refresh_image('normal');
    $this->refresh_image('thumb');
  }
  
  function anchor_facebook_message() {
    return anchor("http://www.facebook.com/messages/$this->facebook_id", 'send message', array('target' => '_blank'));
  }
  
  function get_other_gender() {
    return $this->gender == 'M' ? 'F' : 'M';
  }
  
  function get_raw_pic() {
    return img($this->raw_pic_url);
  }
  
  function get_raw_pic_url() {
    return $this->_get_image_path('source');
  }
  
  function get_pic() {
    return img($this->pic_url);
  }
  
  function get_pic_url() {
    return $this->_get_image_path('normal');
  }
  
  function get_thumb() {
    return img($this->thumb_url);
  }
  
  function get_thumb_url() {
    return $this->_get_image_path('thumb');
  }
  
  function refresh_image($preset) {
    images()->refresh($this->id, $preset);
  }
  
  /**
   * @return WideImage
   */
  function image($preset) {
    return images()->get($this->id, $preset);
  }
  
  function has_pic($preset) {
    return images()->exists($this->id, $preset);
  }
  
  private function _get_image_path($preset) {
    return images()->path($this->id, $preset);
  }
  
  function update_facebook_data() {
    if ( ! $this->facebook_id)
      return;
    
    $fbdata = $this->fetch_facebook_data();
    
    $this->_update_name_from_facebook($fbdata);
    $this->_update_gender_from_facebook($fbdata);
    
    $this->_update_email_from_facebook($fbdata);
    $this->_update_date_of_birth_from_facebook($fbdata);
    $this->_update_hometown_from_facebook($fbdata);
    $this->_update_college_from_facebook($fbdata);
    
    $this->save();
  }
  
  private function _update_name_from_facebook($fbdata) {
    $this->first_name = $fbdata['first_name'];
    $this->last_name = $fbdata['last_name'];
  }
  
  private function _update_gender_from_facebook($fbdata) {
    $genders = array('male' => 'M', 'female' => 'F');
    $this->gender = $genders[ $fbdata['gender'] ];
  }
  
  private function _update_email_from_facebook($fbdata) {
    if ( isset($fbdata['email']) )
      $this->email = $fbdata['email'];
  }
  
  private function _update_date_of_birth_from_facebook($fbdata) {
    if ( isset($fbdata['birthday']) )
      $this->date_of_birth = date('Y-m-d', strtotime($fbdata['birthday']));
  }
  
  private function _update_college_from_facebook($fbdata) {
    $colleges = $this->_get_possible_colleges($fbdata);
    if (empty($colleges)) {
      $this->college_id = NULL;
      $this->grad_year = NULL;
    }
    else {
      $college = $colleges[0];
      $this->college_id = $college->id;
      $this->grad_year = $this->_get_grad_year($college, $fbdata);
    }
  }
  
  private function _update_hometown_from_facebook($fbdata) {
    if ( isset($fbdata['hometown']['name']) ) {
      $hometown = $fbdata['hometown']['name'];

      list($city, $state) = preg_split('/\s*,\s*/', $hometown);
      $abbreviated_state = get_state_abbreviation($state);

      if ($abbreviated_state == NULL)
        $abbreviated_state = $state;

      $this->hometown = "$city, $abbreviated_state";
    }
  }
  
  private function _get_possible_colleges($fbdata) {
    $colleges = array();
    $affiliations = $this->_get_affiliations();
    foreach ($affiliations as $affiliation) {
      $network_id = $affiliation['nid'];
      $college = XCollege::get(array(
        'facebook_network_id' => $network_id
      ));
      
      if ($college == NULL) {
        $college = create_college($affiliation['name'], $network_id);
      }
      
      if ($college->enabled == '0')
        continue;
      
      if (isset($fbdata['education'])) {
        foreach ($fbdata['education'] as $education) {
          if ($education['school']['id'] == $college->facebook_network_id) {
            $this->college_id = $college->id;
          }
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
    if ( isset($fbdata['education']) ) {
      foreach ($fbdata['education'] as $education) {
        if ($education['school']['id'] == $college->facebook_school_id && isset($education['year'])) {
          return $education['year']['name'];
        }
      }
    }
    
    return '';
  }
  
}

class XAnonymousUser extends XObject
{
  
  private $reason = NULL;
  
  function reason() {
    return $this->reason;
  }
  
  function __construct() {
    $this->data = array(
      'id' => 0,
      'first_name' => 'Anonymous',
    );
  }
  
  function is_anonymous() {
    return TRUE;
  }
  
  function can_view_dashboard() {
    $this->reason = REASON_NOT_LOGGED_IN;
    return FALSE;
  }
  
}
