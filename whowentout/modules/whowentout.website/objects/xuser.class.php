<?php

class XUser extends XObject
{

    protected static $table = 'users';

    //The reason the user can't do something
    private $reason = NULL;

    static function current()
    {
        if (logged_in()) {
            $current_user = self::get(get_user_id());
            return $current_user;
        }
        else {
            return new XAnonymousUser();
        }
    }

    static function destroy_session()
    {
//        krumo::dump('destroy_session');
//        krumo::backtrace();
//        exit;
        //delete user id
        set_user_id(0);

        //destroy facebook session data
        $app_id = fb()->getAppId();
        $session_vars = array('code', 'access_token', 'user_id', 'state');
        foreach ($session_vars as $var) {
            ci()->session->unset_userdata("fb_{$app_id}_{$var}");
        }
    }

    function __construct($id = NULL)
    {
        parent::__construct($id);
    }
    
    function change_visibility($visibility)
    {
        $allowed_visibilities = array('online', 'offline');
        if (!in_array($visibility, $allowed_visibilities))
            return FALSE;

        $this->visible_to = $visibility;
        $this->save();

        f()->trigger('user_changed_visibility', array(
                                                     'user' => $this,
                                                     'visibility' => $this->visible_to,
                                                ));

        return TRUE;
    }

    function logout()
    {
        self::destroy_session();
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

    function get_abbreviated_name()
    {
        return $this->first_name . ' ' . ucfirst(substr($this->last_name, 0, 1)) . '.';
    }

    function needs_to_edit_profile()
    {
        return $this->never_edited_profile()
               || $this->is_missing_info()
               || $this->gender == '';
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

    function mutual_friends($person)
    {
        $person = XUser::get($person);
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

        usort($mutual_friends, array($this, 'mutual_friends_sort'));

        return $mutual_friends;
    }

    private function mutual_friends_sort($a, $b)
    {
        return strcmp($a->full_name, $b->full_name);
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

        return XObject::load_objects('XUser', $rows);
    }

    function fetch_facebook_permissions()
    {
        $ci =& get_instance();
        $avaliable_permissions = $ci->config->item('facebook_permissions');
        $result = fb()->api(array(
                                 'method' => 'fql.query',
                                 'query' => "select " . implode(',', $avaliable_permissions) . " from permissions where uid = {$this->facebook_id}",
                            ));
        
        if (empty($result))
            return array();

        $permissions = array();
        foreach ($result[0] as $k => $v) {
            $permissions[$k] = ($v == '1');
        }
        return $permissions;
    }

    function has_facebook_permission($permission_name)
    {
        $perms = $this->fetch_facebook_permissions();
        return isset($perms[$permission_name]) && $perms[$permission_name] == TRUE;
    }

    function update_friends_from_facebook($force_update = FALSE)
    {
        $prev_access_token = fb()->getAccessToken();

        if (!$this->friends_need_update($force_update))
            return FALSE;

        try {
            fb()->setAccessToken($this->facebook_access_token);
            $results = fb()->api(array(
                                      'method' => 'fql.query',
                                      'query' => "SELECT uid, name, is_app_user FROM user
                                WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = $this->facebook_id)" // AND is_app_user = 1
                                 ));
        }
        catch (Exception $e) {
//            print '<h1>' . $e->getMessage() . '</h1>'; TODO: save to some log
            fb()->setAccessToken($prev_access_token);
            return FALSE;
        }

        fb()->setAccessToken($prev_access_token);

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
        
        if ( count($rows) > 0 )
            $this->db()->insert_batch('friends', $rows);

        //update friends
        $this->db()->query("UPDATE friends
                        SET friend_id = (SELECT users.id FROM users WHERE users.facebook_id = friend_facebook_id)
                        WHERE user_id = ?", array($this->id));

        //update reverse relationship
        $this->db()->query("UPDATE friends
                            SET friend_id = ?
                            WHERE friend_facebook_id = ?", array($this->id, $this->facebook_id));

        $this->db()->trans_complete();
        
        $this->last_updated_friends = $this->college->get_time()->getMySqlTimestamp();
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

        //out of date means about 3.5 days
        return $this->college->get_time()->getTimestamp() - $last_updated->getTimestamp() > 300000;
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
              ON users.id = party_attendees.user_id AND party_id IN ($party_ids) AND users.id IN ($friend_ids)
              ORDER BY first_name, last_name ASC";
        $rows = $this->db()->query($query)->result();
        foreach ($rows as $row) {
            $breakdown[$row->party_id][] = $row->user_id;
        }

        return $breakdown;
    }

    private function _party_ids(XDateTime $date)
    {
        $ids = array();
        foreach ($this->college->parties_on($date) as $party) {
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

    function anchor_facebook_message()
    {
        return anchor("http://www.facebook.com/messages/$this->facebook_id", 'send message', array('target' => '_blank', 'class' => 'send_fb_message'));
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

    function is_friend_of($user)
    {
        $user = XUser::get($user);
        return $this->db()->from('friends')
                       ->where('user_id', $this->id)
                       ->where('friend_id', $user->id)
                       ->count_all_results() > 0;
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
            $array['chatbar_state'] = $this->chatbar_state;
        }

        return $array;
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

        if (isset($fbdata['gender']))
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
