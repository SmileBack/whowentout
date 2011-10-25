<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

define('ANONYMOUS_CHECKIN_STATE', 1);
define('LOGIN_LINK_STATE', 2);

class User extends MY_Controller
{

    function upload_pic()
    {
        if (!logged_in())
            show_404();

        $user = current_user();
        $user->upload_pic();

        $this->json_for_ajax_file_upload(array(
                                              'success' => TRUE,
                                              'raw_pic' => $user->raw_pic,
                                              'crop_box' => $user->pic_crop_box,
                                         ));
    }

    function use_facebook_pic()
    {
        if (!logged_in())
            show_404();

        $user = current_user();
        $user->use_facebook_pic();

        $this->json_for_ajax_file_upload(array(
                                              'success' => TRUE,
                                              'raw_pic' => $user->raw_pic,
                                              'crop_box' => $user->pic_crop_box,
                                         ));
    }

    function edit_save()
    {
        if (!logged_in())
            show_404();

        $user = current_user();

        if (post('op') == 'Save') {
            if (post('width') && post('height')) {
                $user->crop_pic(post('x'), post('y'), post('width'), post('height'));
            }

            $user->hometown_city = post('hometown_city');
            $user->hometown_state = post('hometown_state');
            $user->grad_year = post('grad_year');

            if ($user->changed()) {
                $user->last_edit = $user->college->get_time()->formatMySqlTimestamp();
                $user->save();
            }

            // The first time no-changes edit still counts as a save.
            if ($user->never_edited_profile()) {
                $user->last_edit = $user->college->get_time()->formatMySqlTimestamp();
                $user->save();
            }

            redirect('dashboard');
        }

        show_404();
    }

    function edit()
    {
        $this->require_login();

        $use_website_permission = new UseWebsitePermission();
        $can_use_website = $use_website_permission->check(current_user());

        if (!$can_use_website)
            current_user()->update_facebook_data();

        $can_use_website = $use_website_permission->check(current_user()); //check again

        $message = array();

        if ($use_website_permission->cant_because(UseWebsitePermission::NETWORK_INFO_MISSING)) {
            $message[] = load_view('missing_network_view');
        }

        if ($use_website_permission->cant_because(UseWebsitePermission::GENDER_MISSING)) {
            $message[] = '<p>To use WhoWentOut, please <a href="http://www.facebook.com/editprofile.php" target="_blank">enter your gender</a> in your Facebook profile.</p>';
        }

        if ($use_website_permission->cant_because(UseWebsitePermission::GRAD_YEAR_MISSING)) {
            $message[] = '<p>You are missing your graduation year.</p>';
        }

        if ($use_website_permission->cant_because(UseWebsitePermission::HOMETOWN_MISSING)) {
            $message[] = '<p>You are missing your hometown.</p>';
        }

        if (!empty($message))
            set_message(implode('', $message));

        $this->load_view('user_edit_view', array(
                                                'user' => current_user(),
                                                'missing_info' => current_user()->get_missing_info(),
                                           ));
    }

    function login()
    {
        if (fb()->getUser() == NULL) {
            redirect(facebook_login_url());
        }
        else {
            login();
            enforce_restrictions();
            redirect('dashboard');
        }
    }

    function logout()
    {
        logout();
        redirect('/');
    }

    function checkin()
    {
        if (!logged_in())
            show_404();

        $party = $party = XParty::get(post('party_id'));
        $user = current_user();

        if (!$party)
            $this->json_failure("Party doesn't exist.");

        if (!$user)
            $this->json_failure("User doesn't exist.");

        $checkin_engine = new CheckinEngine();
        $smile_engine = new SmileEngine();

        $response = array();

        if ($user->can_checkin($party)) {
            $checkin_engine->checkin_user_to_party($user, $party);
            $response['party_summary_view'] = load_view('party_summary_view', array(
                                                                                   'user' => $user,
                                                                                   'party' => $party,
                                                                                   'smile_engine' => $smile_engine,
                                                                              ));
            $response['user_command_notice'] = load_view('user_command_notice', array(
                                                                                     'user' => $user,
                                                                                ));
            $response['checkin_form'] = load_view('forms/checkin_form');
            $response['party'] = $party->to_array();

            $channel_id = 'party_' . $party->id;
            $response['channels'][$channel_id] = array(
                'type' => serverchannel()->type(),
                'id' => $channel_id,
            );
            $this->json($response);
        }
        else {
            $this->json_failure("You are not allowed to checkin.");
        }
    }

    function mutual_friends($target_id)
    {
        $user = current_user();
        $target = XUser::get($target_id);

        if (!$user || !$target) {
            print "Invalid request.";
            exit;
        }

        $mutual_friends = $user->mutual_friends($target);

        $this->load->view('mutual_friends_view', array(
                                                      'target' => $target,
                                                      'mutual_friends' => $mutual_friends,
                                                 ));
    }

    function change_visibility($visibility)
    {
        $success = current_user()->change_visibility($visibility);
        $this->json(array('success' => $success, 'visibility' => current_user()->visible_to));
    }

    function friends()
    {
        if (!logged_in())
            show_error('Not logged in.');

        $user_id = current_user()->id;
        $q = $this->input->get('q');
        $results = $this->db->select('friend_facebook_id, friend_full_name')
                ->from('friends')
                ->where('user_id', $user_id)
                ->like('friend_full_name', $q, 'both')
                ->limit(10)
                ->get()->result();
        $matches = array();
        foreach ($results as $result) {
            $matches[] = array('id' => $result->friend_facebook_id, 'title' => $result->friend_full_name);
        }
        print json_encode($matches);
        exit;
    }

    function party_attendee_view()
    {
        if (!logged_in())
            $this->json_failure("You must be logged in.");

        $party = XParty::get(post('party_id'));
        $attendee = XUser::get(post('user_id'));

        if (!$party)
            $this->json_failure("party doesnt exist");

        if (!$attendee)
            $this->json_failure("user doesnt exist");

        $response = array();
        $response['insert_positions'] = $party->attendee_insert_positions($attendee);
        $response['party_attendee_view'] = load_view('party_attendee_view', array(
                                                                                 'logged_in_user' => current_user(),
                                                                                 'party' => $party,
                                                                                 'attendee' => $attendee,
                                                                                 'smile_engine' => new SmileEngine(),
                                                                            ));
        $this->json($response);
    }

    function pusherauth()
    {
        if (!logged_in())
            $this->json_failure('You must be logged in.');


        require_once APPPATH . 'third_party/pusher.php';
        $this->load->config('pusher');

        $channel_name = post('channel_name');
        $socket_id = post('socket_id');
        $user_id = current_user()->id;

        if (!$this->user_can_access_channel(current_user(), $channel_name)) {
            $this->json_failure("You don't have permission to access this channel.");
        }

        $custom_data = array(
            'user_id' => $user_id,
        );

        $pusher = new Pusher($this->config->item('pusher_app_key'),
            $this->config->item('pusher_app_secret'),
            $this->config->item('pusher_app_id'));

        print $pusher->socket_auth($channel_name, $socket_id, json_encode($custom_data));
    }

    private function user_can_access_channel(XUser $user, $channel)
    {
        $id = intval(preg_replace('/\D+/', '', $channel));
        if ($this->is_user_channel($channel)) {
            return $user->id == $id;
        }
        elseif ($this->is_party_channel($channel)) {
            return $user->has_attended_party($id);
        }
        else {
            return TRUE;
        }
    }

    private function is_user_channel($channel)
    {
        return string_starts_with('private-user_', $channel);
    }

    private function is_party_channel($channel)
    {
        return string_starts_with('private-party_', $channel);
    }

}
