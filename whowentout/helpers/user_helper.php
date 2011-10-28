<?php

require_once APPPATH . 'libraries/fb/facebook.php';

function get_facebook_id($user_name)
{
    static $ids = array();
    if (!isset($ids[$user_name]))
    {
        $data = fb()->api("/$user_name");
        $ids[$user_name] = $data['id'];
    }
    
    return $ids[$user_name];
}

/**
 * @return XUser
 */
function user($user_id)
{
    return XUser::get($user_id);
}

/**
 * @param int $facebook_id
 * @param array $data
 * @return XUser
 */
function create_user($facebook_id, $data = array())
{
    //we were given a username
    if ($facebook_id && !preg_match('/^\d+$/', $facebook_id)) {
        $facebook_id = get_facebook_id($facebook_id);
    }

    $data['facebook_id'] = $facebook_id;
    $data['registration_time'] = college()->get_time()->formatMySqlTimestamp();
    $user = XUser::create($data);
    $user->update_facebook_data();
    $user->refresh_image('facebook');

    return $user;
}

/**
 *
 * DELETES A USER AND ALL OF HIS INFORMATION!
 * THIS INCLUDES ALL SMILES AND ATTENDED PARTIES.
 *
 * @param XUser $user
 *   The ID of the user.
 */
function destroy_user($user)
{
    $ci =& get_instance();
    $user = XUser::get($user);

    if ($user == NULL)
        return;

    if (current_user() == $user)
        logout();

    $ci->db->delete('party_attendees', array('user_id' => $user->id));
    
    $ci->db->delete('smile_matches', array('first_user_id' => $user->id));
    $ci->db->delete('smile_matches', array('second_user_id' => $user->id));

    $ci->db->delete('notifications', array('user_id' => $user->id));

    $ci->db->delete('smiles', array('sender_id' => $user->id));
    $ci->db->delete('smiles', array('receiver_id' => $user->id));

    $ci->db->delete('chat_messages', array('sender_id' => $user->id));
    $ci->db->delete('chat_messages', array('receiver_id' => $user->id));
    
    $ci->db->delete('party_invitations', array('sender_id' => $user->id));

    $user->delete();
}

function user_exists($user_id)
{
    return XUser::get($user_id) != NULL;
}

function enforce_restrictions()
{
    if (logged_in() && current_user() == NULL) {
        XUser::destroy_session();
        exit;
    }

    $use_website_permission = new UseWebsitePermission();
    $can_use_website = $use_website_permission->check(current_user());
    if (!$can_use_website)
        redirect('user/edit');
}

/**
 * @return XCollege
 */
function create_college($name, $facebook_network_id, $facebook_school_id = NULL, $enabled = FALSE)
{
    $data = array(
        'name' => $name,
        'facebook_network_id' => $facebook_network_id,
        'enabled' => $enabled ? 1 : 0,
    );

    if ($facebook_school_id)
        $data['facebook_school_id'] = $facebook_school_id;

    $college = XCollege::get(array('facebook_network_id' => $facebook_network_id));

    if ($college == NULL) {
        $college = XCollege::create($data);
    }
    else {
        foreach ($data as $k => $v) {
            $college->$k = $v;
        }
        $college->save();
    }

    return $college;
}

/**
 * @return XUser
 */
function current_user()
{
    return XUser::current();
}

/**
 * @return int
 *   The id of the current user.
 */
function get_user_id()
{
    return ci()->session->userdata('user_id');
}

function set_user_id($user_id)
{
    ci()->session->set_userdata('user_id', $user_id);
}

/**
 * @return XUser
 */
function login()
{
    $facebook_id = fb()->getUser();
    $new_user = FALSE;
    if ($facebook_id) {
        $current_user = XUser::get(array(
                                        'facebook_id' => $facebook_id
                                   ));

        if ($current_user == NULL) {
            $current_user = create_user($facebook_id);
            $new_user = TRUE;
        }

        set_user_id($current_user->id);

        current_user()->facebook_access_token = fb()->getAccessToken();
        current_user()->save();
        
        return current_user();
    }
    else {
        return FALSE;
    }
}

function fake_login($user_id)
{
    $current_user = user($user_id);
    set_user_id($current_user->id);
}

function logout()
{
    if (logged_in()) {
        return XUser::logout();
    }
}

function logged_in()
{
    return get_user_id() != NULL;
}

function connected_to_facebook()
{
    return fb()->getUser() != 0;
}

function facebook_login_url()
{
    $ci =& get_instance();
    $permissions = $ci->config->item('facebook_permissions');
    return fb()->getLoginUrl(array(
                                  'scope' => implode(',', $permissions),
                             ));
}

function anchor_facebook_login($title = 'Facebook Login', $attributes = array())
{
    return anchor(facebook_login_url(), $title, $attributes);
}

function deny_anonymous()
{
    if (!logged_in())
        show_404();
}

function fb()
{
    static $facebook = NULL;
    $ci =& get_instance();
    if ($facebook == NULL) {
        if (FALSE) { //normally used for PHPFOG
            $facebook = new Facebook(array(
                                          'appId' => $ci->config->item('facebook_app_id'),
                                          'secret' => $ci->config->item('facebook_secret_key'),
                                     ));
        }
        else {
            $facebook = new TestFacebook(array(
                                              'appId' => $ci->config->item('facebook_app_id'),
                                              'secret' => $ci->config->item('facebook_secret_key'),
                                         ));
        }
    }
    return $facebook;
}
