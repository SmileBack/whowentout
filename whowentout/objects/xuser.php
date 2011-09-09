<?php

class XUser extends XObject
{

    protected static $table = 'users';

    //The reason the user can't do something
    private $reason = NULL;

    static function current()
    {
        if (logged_in()) {
            return self::get(get_user_id());
        }
        else {
            return new XAnonymousUser();
        }
    }

    function change_visibility($visibility)
    {
        $allowed_visibilities = array('everyone', 'friends', 'none');
        if (!in_array($visibility, $allowed_visibilities))
            return FALSE;

        $this->visible_to = $visibility;
        $this->ping_leaving_site();
        $this->ping_server();

        raise_event('user_changed_visibility', array(
                                                 'user' => $this,
                                                 'visibility' => $this->visible_to,
                                               ));

        return TRUE;
    }

    function logout()
    {
        //delete user id
        set_user_id(0);

        //destroy facebook session data
        $app_id = fb()->getAppId();
        $session_vars = array('code', 'access_token', 'user_id', 'state');
        foreach ($session_vars as $var) {
            ci()->session->unset_userdata("fb_{$app_id}_{$var}");
        }
    }

    function reason()
    {
        return $this->reason;
    }

    function is_anonymous()
    {
        return FALSE;
    }

    function is_admin()
    {
        return in_array($this->facebook_id, ci()->config->item('admin_facebook_ids'));
    }

    function is_current_user()
    {
        return $this == current_user();
    }

    function can_use_website()
    {
        return $this->college == college()
               && !$this->needs_to_edit_profile();
    }

    function get_college()
    {
        return XCollege::get($this->college_id);
    }

    function get_full_name()
    {
        return "$this->first_name $this->last_name";
    }

    function needs_to_edit_profile()
    {
        return $this->never_edited_profile()
               || $this->is_missing_info();
    }

    function never_edited_profile()
    {
        return $this->last_edit == NULL;
    }

    function is_missing_info()
    {
        return count($this->get_missing_info()) > 0;
    }

    function get_missing_info()
    {
        $missing_info = array();

        if ($this->hometown_city == '')
            $missing_info[] = 'hometown_city';

        if ($this->hometown_state == '')
            $missing_info[] = 'hometown_state';

        if ($this->grad_year == '' || $this->grad_year == 0)
            $missing_info[] = 'grad_year';

        return $missing_info;
    }

    function checkin($party)
    {
        $party = party($party);

        if (!$this->can_checkin($party)) {
            return FALSE;
        }

        $this->db()->insert('party_attendees', array(
                                                    'user_id' => $this->id,
                                                    'party_id' => $party->id,
                                                    'checkin_time' => current_time()->format('Y-m-d H:i:s'),
                                               ));

        raise_event('checkin', array(
                                    'source' => $party,
                                    'user' => $this,
                                    'party' => $party,
                               ));

        return TRUE;
    }

    function can_checkin($party_id)
    {
        $party = party($party_id);

        if ($party == NULL) {
            $this->reason = REASON_PARTY_DOESNT_EXIST;
            return FALSE;
        }

        $party_date = new DateTime($party->date, $party->college->timezone);

        if ($party->college != $this->college) {
            $this->reason = REASON_NOT_IN_COLLEGE;
            return FALSE;
        }

        $yesterday = $this->college->yesterday(TRUE);
        if ($party_date != $yesterday) {
            $this->reason = REASON_PARTY_WASNT_YESTERDAY;
            return FALSE;
        }

        // You've already attended a party
        if ($this->has_attended_party_on_date($party_date)) {
            $this->reason = REASON_ALREADY_ATTENDED_PARTY;
            return FALSE;
        }

        // You are not within the bounds of the checkin time.
        if ($this->college->doors_are_closed()) {
            $this->reason = REASON_DOORS_HAVE_CLOSED;
            return FALSE;
        }

        $this->reason = NULL;
        return TRUE;
    }

    function recent_parties()
    {
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
    function smile_at($receiver, $party)
    {
        $receiver = user($receiver);
        $party = party($party);

        if (!$this->can_smile_at($receiver, $party))
            return FALSE;

        $smile = XSmile::create(array(
                                     'sender_id' => $this->id,
                                     'receiver_id' => $receiver->id,
                                     'party_id' => $party->id,
                                     'smile_time' => current_time()->format('Y-m-d H:i:s'),
                                ));

        raise_event('smile_sent', array(
                                       'source' => $party,
                                       'smile' => $smile,
                                       'sender' => $this,
                                       'receiver' => $receiver,
                                       'party' => $party,
                                  ));

        return TRUE;
    }

    function can_smile_at($receiver, $party)
    {
        $receiver = user($receiver);
        $party = party($party);

        if (!$receiver) {
            $this->reason = REASON_USER_DOESNT_EXIST;
            return FALSE;
        }

        if (!$party) {
            $this->reason = REASON_PARTY_DOESNT_EXIST;
            return FALSE;
        }

        if ($receiver->gender == $this->gender) {
            $this->reason = REASON_CANT_SMILE_AT_SAME_GENDER;
            return FALSE;
        }

        if (!$receiver->has_attended_party($party)) {
            $this->reason = REASON_RECEIVER_NOT_IN_PARTY;
            return FALSE;
        }

        if (!$this->has_attended_party($party)) {
            $this->reason = REASON_NOT_IN_PARTY;
            return FALSE;
        }

        if ($this->smiles_left($party) <= 0) {
            $this->reason = REASON_OUT_OF_SMILES;
            return FALSE;
        }

        if ($this->has_smiled_at($receiver, $party)) {
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
    function has_smiled_at($receiver, $party)
    {
        return $this->db()->from('smiles')
                       ->where('sender_id', $this->id)
                       ->where('receiver_id', $receiver->id)
                       ->where('party_id', $party->id)
                       ->count_all_results() > 0;
    }

    function smiles_left($party)
    {
        $party = party($party);

        $smiles_used = $this->db()
                ->from('smiles')
                ->where('sender_id', $this->id)
                ->where('party_id', $party->id)
                ->count_all_results();
        return (3 - $smiles_used);
    }

    function smiles_received($party)
    {
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
    function was_smiled_at($sender, $party)
    {
        $sender = user($sender);
        $party = party($party);

        return $this->db()
                       ->from('smiles')
                       ->where('sender_id', $sender->id)
                       ->where('receiver_id', $this->id)
                       ->where('party_id', $party->id)
                       ->count_all_results() == 1;
    }


    function most_recent_smile_from($user)
    {
        $user = user($user);
        $row = $this->db()->from('smiles')
                ->where('sender_id', $user->id)
                ->where('receiver_id', $this->id)
                ->get()->row();
        return empty($row) ? FALSE : smile($row->id);
    }

    /**
     * @param  $user
     * @return XSmile
     */
    function most_recent_smile_to($user)
    {
        $user = user($user);
        $row = $this->db()->from('smiles')
                ->where('sender_id', $this->id)
                ->where('receiver_id', $user->id)
                ->get()->row();
        return empty($row) ? FALSE : smile($row->id);
    }

    function matches($party)
    {
        $party = party($party);
        $query = $this->db()->select('smile_matches.id AS id')
                ->from('smile_matches')
                ->join('smiles', 'second_smile_id = smiles.id')
                ->where('smiles.party_id', $party->id)
                ->where('first_user_id', $this->id)
                ->or_where('smiles.party_id', $party->id)
                ->where('second_user_id', $this->id);
        return $this->load_objects('XSmileMatch', $query);
    }

    function mutual_friends($person)
    {
        $person = user($person);
        update_facebook_friends($person); // update his friends if necessary

        $params = array($this->id, $person->id, $this->id);
        $rows = $this->db()
                ->query("
                          SELECT friend_facebook_id, friend_full_name FROM friends
                          WHERE
                            user_id = ?
                          AND
                            friend_facebook_id IN (SELECT friend_facebook_id FROM friends WHERE user_id = ?)
                          AND
                            friend_facebook_id IN (SELECT friend_facebook_id FROM friends WHERE user_id = ?)    
                         ", $params)->result();

        $mutual_friends = array();
        foreach ($rows as $row) {
            $friend = array(
                'facebook_id' => $row->friend_facebook_id,
                'full_name' => $row->friend_full_name,
            );
            $friend['thumb'] = "https://graph.facebook.com/$row->friend_facebook_id/picture&access_token=" . fb()->getAccessToken();
            $mutual_friends[] = (object)$friend;
        }

        return $mutual_friends;
    }

    function get_hometown()
    {
        return $this->hometown_city . ', ' . $this->hometown_state;
    }

    function friends()
    {
        $rows = $this->db()->select('friend_id AS id')
                ->from('friends')
                ->where('user_id', $this->id)
                ->where('friend_id IS NOT NULL');

        return $this->load_objects('XUser', $rows);
    }

    function update_friends_from_facebook($force_update = FALSE)
    {
        if (!$this->friends_need_update($force_update))
            return;

        try {
            $results = fb()->api(array(
                                      'method' => 'fql.query',
                                      'query' => "SELECT uid, name, is_app_user FROM user
                    WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $this->facebook_id)" // AND is_app_user = 1
                                 ));
        }
        catch (Exception $e) {
            return FALSE;
        }

        $rows = array();
        foreach ($results as $result) {
            $rows[] = array(
                'user_id' => $this->id,
                'user_facebook_id' => $this->facebook_id,
                'friend_facebook_id' => $result['uid'],
                'friend_full_name' => $result['name'],
            );
        }

        //delete old friends data
        $this->db()->trans_start();
        $this->db()->delete('friends', array('user_id' => $this->id));
        $this->db()->insert_batch('friends', $rows);
        //update friends
        $this->db()->query("UPDATE friends
                        SET friend_id = (SELECT users.id FROM users WHERE users.facebook_id = friend_facebook_id)
                        WHERE user_id = ?", array($this->id));
        $this->db()->trans_complete();
        $this->last_updated_friends = current_time()->format('Y-m-d H:i:s');
        $this->save();

        return TRUE;
    }

    function friends_need_update($force_update = FALSE)
    {
        if ($force_update)
            return TRUE;
        elseif ($this->friends_are_out_of_date())
            return TRUE;
        else
            return FALSE;
    }

    function friends_are_out_of_date()
    {
        if ($this->last_updated_friends == NULL)
            return TRUE;

        $last_updated = new DateTime($this->last_updated_friends, new DateTimeZone('UTC'));

        return current_time()->getTimestamp() - $last_updated->getTimestamp() > 600;
    }

    /**
     * Return the party that this user attended on $date.
     *
     * @param int $user_id
     * @param timestamp $date
     * @return object
     *   A party object
     */
    function get_attended_party($date)
    {
        $date = $this->college->make_local($date);

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

    function get_recently_attended_parties()
    {
        $cutoff = $this->college->day(-7, TRUE);
        $rows = $this->db()
                ->select('party_id AS id')
                ->from('party_attendees')
                ->join('parties', 'party_attendees.party_id = parties.id')
                ->where('user_id', $this->id)
                ->where('date >', date_format($cutoff, 'Y-m-d'));
        return $this->load_objects('XParty', $rows);
    }

    function smiles_received_message($party)
    {
        $party = party($party);

        $genders = array('M' => 'guy', 'F' => 'girl');
        $smiles = $this->smiles_received($party->id);
        $people = $genders[$this->other_gender];

        if ($smiles != 1)
            $people = $people . 's'; //pluralize

        $have = $smiles == 1 ? 'has' : 'have';

        return "$smiles $people $have smiled at you";
    }

    function smiles_left_message($party)
    {
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
    function has_attended_party_on_date($date)
    {
        return $this->get_attended_party($date) != NULL;
    }

    /**
     * Tells you if this user attended $party_id.
     * @param XParty $party
     * @return bool
     */
    function has_attended_party($party)
    {
        $party = party($party);
        return $this->db()->from('party_attendees')
                       ->where('user_id', $this->id)
                       ->where('party_id', $party->id)
                       ->count_all_results() > 0;
    }

    function where_friends_went(DateTime $date)
    {
        $breakdown = array();

        if (!$this->college)
            return array();

        $party_ids = $this->_party_ids($date);
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

    private function _party_ids(DateTime $date)
    {
        $ids = array();
        foreach ($this->college->open_parties($date) as $party) {
            $ids[] = $party->id;
        }
        return $ids;
    }

    private function _friend_ids()
    {
        $ids = array();
        foreach ($this->friends() as $friend) {
            if ($friend instanceof XUser)
                $ids[] = $friend->id;
        }
        return $ids;
    }

    function fetch_facebook_data()
    {
        if ($this->facebook_id == NULL)
            return NULL;
        else
            return fb()->api("/$this->facebook_id");
    }

    function upload_pic()
    {
        $this->refresh_image('upload');
    }

    function use_facebook_pic()
    {
        images()->delete($this->id, 'upload');
        images()->delete($this->id, 'source');
        images()->refresh($this->id, 'facebook');
    }

    //todo: validate
    function crop_pic($x, $y, $width, $height)
    {
        $this->pic_x = $x;
        $this->pic_y = $y;
        $this->pic_width = $width;
        $this->pic_height = $height;

        $this->refresh_image('normal');
        $this->refresh_image('thumb');
    }

    function anchor_facebook_message()
    {
        return anchor("http://www.facebook.com/messages/$this->facebook_id", 'send message', array('target' => '_blank'));
    }

    function get_gender_word()
    {
        return $this->gender == 'M' ? 'guy' : 'girl';
    }

    function get_other_gender_word()
    {
        return $this->gender == 'M' ? 'girl' : 'guy';
    }

    function get_other_gender()
    {
        return $this->gender == 'M' ? 'F' : 'M';
    }

    function get_raw_pic()
    {
        return img($this->raw_pic_url);
    }

    function get_raw_pic_url()
    {
        return $this->_get_image_path('source');
    }

    function get_pic()
    {
        return img($this->pic_url);
    }

    function get_pic_url()
    {
        return $this->_get_image_path('normal');
    }

    function get_thumb()
    {
        return img($this->thumb_url);
    }

    function get_thumb_url()
    {
        return $this->_get_image_path('thumb');
    }

    function refresh_image($preset)
    {
        images()->refresh($this->id, $preset);
    }

    /**
     * @return WideImage
     */
    function image($preset)
    {
        return images()->get($this->id, $preset);
    }

    function has_pic($preset)
    {
        return images()->exists($this->id, $preset);
    }

    function is_online()
    {
        if ($this->last_ping == NULL)
            return FALSE;

        $a_little_while_ago = current_time()->modify('-10 seconds')->getTimestamp();
        $last_ping = new DateTime($this->last_ping, new DateTimeZone('UTC'));
        $last_ping = $last_ping->getTimestamp();

        return $last_ping > $a_little_while_ago;
    }

    /**
     * @param  XUser $user
     * @return bool
     *      Whether $this user appears online to $user.
     */
    function is_online_to($user)
    {
        $user = user($user);

        if (!$this->is_online())
            return FALSE;

        else if ($this->visible_to == 'none')
            return FALSE;

            // not hiding anything, so your visible state is unaltered
        else if ($this->visible_to == 'everyone')
            return $this->is_online();

            // only tell them you're online if you're friends with them
        else if ($this->visible_to == 'friends')
            return $this->is_online() && $this->is_friend_of($user);
    }

    function is_friend_of($user)
    {
        $user = user($user);
        return $this->db()->from('friends')
                       ->where('user_id', $this->id)
                       ->where('friend_id', $user->id)
                       ->count_all_results() > 0;
    }

    function ping_server()
    {
        $was_online = $this->is_online();

        $this->last_ping = current_time()->format('Y-m-d H:i:s');
        $this->save();

        $just_came_online = !$was_online;
        if ($just_came_online) {
            raise_event('user_came_online', array(
                                                 'user' => $this,
                                            ));
        }
    }

    function ping_leaving_site($suspend_save = FALSE)
    {
        if ($this->last_ping == NULL) //already marked as offline so don't do anything
            return;

        $this->last_ping = NULL;
        $this->save();

        raise_event('user_went_offline', array(
                                              'user' => $this,
                                         ));
    }

    function get_chatbar_state()
    {
        if ($this->data['chatbar_state'] == NULL)
            return array();
        else
            return json_decode($this->data['chatbar_state']);
    }

    function to_array($show_private_fields = FALSE)
    {
        $array = array(
            'id' => $this->id,
            'facebook_id' => $this->facebook_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'other_gender' => $this->other_gender,
            'is_current_user' => $this->is_current_user(),
            'thumb_url' => $this->thumb_url,
        );

        if ($show_private_fields) {
            $array['visible_to'] = $this->visible_to;
        }
        
        return $array;
    }

    //this is underscored so it doesn't conflict with the XObject get_{property} methods.
    private function _get_image_path($preset)
    {
        return images()->url($this->id, $preset);
    }

    function update_facebook_data()
    {
        if (!$this->facebook_id)
            return;

        $fbdata = $this->fetch_facebook_data();

        $this->_update_name_from_facebook($fbdata);
        $this->_update_gender_from_facebook($fbdata);

        $this->_update_email_from_facebook($fbdata);
        $this->_update_date_of_birth_from_facebook($fbdata);
        $this->_update_hometown_from_facebook($fbdata);
        $this->_update_college_from_facebook($fbdata);

        if (intval($this->grad_year) < 2011) {
            $this->grad_year = '';
        }

        $this->save();
    }

    private function _update_name_from_facebook($fbdata)
    {
        $this->first_name = $fbdata['first_name'];
        $this->last_name = $fbdata['last_name'];
    }

    private function _update_gender_from_facebook($fbdata)
    {
        $genders = array('male' => 'M', 'female' => 'F');
        $this->gender = $genders[$fbdata['gender']];
    }

    private function _update_email_from_facebook($fbdata)
    {
        if (isset($fbdata['email']))
            $this->email = $fbdata['email'];
    }

    private function _update_date_of_birth_from_facebook($fbdata)
    {
        if (isset($fbdata['birthday']))
            $this->date_of_birth = date('Y-m-d', strtotime($fbdata['birthday']));
    }

    private function _update_college_from_facebook($fbdata)
    {
        $colleges = $this->_get_possible_colleges($fbdata);
        if (empty($colleges)) {
            $this->college_id = NULL;
            $this->grad_year = NULL;
        }
        else {
            $college = $colleges[0];
            $this->college_id = $college->id;

            if (!$this->grad_year) {
                $this->grad_year = $this->_get_grad_year($college, $fbdata);
            }
        }
    }

    private function _update_hometown_from_facebook($fbdata)
    {
        if (isset($fbdata['hometown']['name'])) {
            $hometown = $fbdata['hometown']['name'];

            $city = get_hometown_city($hometown);
            $state = get_hometown_state($hometown);
            $abbreviated_state = get_state_abbreviation($state);

            if ($abbreviated_state == NULL)
                $abbreviated_state = $state;

            if ($this->hometown_city == '') {
                $this->hometown_city = $city;
                $this->hometown_state = $abbreviated_state;
            }
        }
    }

    private function _get_possible_colleges($fbdata)
    {
        $enabled_colleges = array();
        $disabled_colleges = array();

        $affiliations = $this->_get_affiliations();
        foreach ($affiliations as $affiliation) {
            $network_id = $affiliation['nid'];
            $college = XCollege::get(array(
                                          'facebook_network_id' => $network_id
                                     ));

            if ($college == NULL) {
                $college = create_college($affiliation['name'], $network_id);
            }

            if (isset($fbdata['education'])) {
                foreach ($fbdata['education'] as $education) {
                    if ($education['school']['id'] == $college->facebook_network_id) {
                        $this->college_id = $college->id;
                    }
                }
            }

            if ($college->enabled == '1')
                $enabled_colleges[] = $college;
            else
                $disabled_colleges[] = $college;
        }
        return array_merge($enabled_colleges, $disabled_colleges);
    }

    function _get_affiliations()
    {
        $result = fb()->api(array(
                                 'method' => 'fql.query',
                                 'query' => "SELECT affiliations FROM user WHERE uid = $this->facebook_id",
                            ));
        return $result[0]['affiliations'];
    }

    private function _get_grad_year($college, $fbdata)
    {
        if (isset($fbdata['education'])) {
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

    function reason()
    {
        return $this->reason;
    }

    function __construct()
    {
        $this->data = array(
            'id' => 0,
            'first_name' => 'Anonymous',
        );
    }

    function is_anonymous()
    {
        return TRUE;
    }

    function can_view_dashboard()
    {
        $this->reason = REASON_NOT_LOGGED_IN;
        return FALSE;
    }

    function can_use_website()
    {
        $this->reason = REASON_NOT_LOGGED_IN;
        return FALSE;
    }

}
