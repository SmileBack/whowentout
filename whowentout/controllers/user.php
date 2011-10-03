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

        $this->json(array(
                         'success' => TRUE,
                         'raw_pic' => $user->raw_pic,
                         'crop_box' => $user->pic_crop_box,
                    ), TRUE);
    }

    function use_facebook_pic()
    {
        if (!logged_in())
            show_404();

        $user = current_user();
        $user->use_facebook_pic();

        $this->json(array(
                         'success' => TRUE,
                         'raw_pic' => $user->raw_pic,
                         'crop_box' => $user->pic_crop_box,
                    ), TRUE);
    }

    function edit_save()
    {
        if (!logged_in())
            show_404();

        $user = current_user();

        if (post('op') == 'Save') {
            if (post('width') && post('height')) {
                $user->crop_pic(post('x'), post('y'), post('width'), post('height'));
                $user->hometown_city = post('hometown_city');
                $user->hometown_state = post('hometown_state');
                $user->grad_year = post('grad_year');
            }

            if ($user->changed()) {
                $user->last_edit = date_format(current_time(), 'Y-m-d H:i:s');
                $user->save();
            }

            // The first time no-changes edit still counts as a save.
            if ($user->never_edited_profile()) {
                $user->last_edit = date_format(current_time(), 'Y-m-d H:i:s');
                $user->save();
            }

            if (login_action() != NULL && $user->can_use_website()) {
                $action = login_action();
                if ($action['name'] == 'checkin')
                    redirect('checkin');
            }

            redirect('dashboard');
        }

        show_404();
    }

    function edit()
    {
        require_login();

        if (!current_user()->can_use_website())
            current_user()->update_facebook_data();

        $message = array();

        if (current_user()->college != college()) {
            $message[] = load_view('missing_network_view');
        }

        if (current_user()->is_missing_info()) {
            $message[] = '<p>You are missing information</p>';
        }

        if (current_user()->gender == '') {
            $message[] = '<p>To use WhoWentOut, please <a href="http://www.facebook.com/editprofile.php" target="_blank">enter your gender</a> in your Facebook profile.</p>';
        }

        if (!empty($message))
            set_message(implode('', $message));

        $this->load_view('user_edit_view', array(
                                                'user' => current_user(),
                                                'is_missing_info' => current_user()->is_missing_info(),
                                                'missing_info' => current_user()->get_missing_info(),
                                           ));
    }

    function login()
    {
        $user_id = post('user_id');
        if (fb()->getUser() == NULL) {
            redirect(facebook_login_url());
        }
        else {
            login();

            enforce_restrictions();

            if (login_action() != NULL) {
                $action = login_action();
                if ($action['name'] == 'checkin')
                    redirect('checkin');
            }

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
        $party_id = post('party_id');
        $user = current_user();

        $response = array();

        require_login(array(
                           'name' => 'checkin', //action name
                           'party_id' => $party_id,
                      ));

        if (login_action_exists()) {
            $action = login_action();
            if ($action['name'] == 'checkin') {
                clear_login_action();
                $party_id = $action['party_id'];
            }
        }

        $party = party($party_id);

        if ($party == NULL) {
            if ($this->is_ajax())
                $this->json_failure("Party with id = $party_id doesn't exist.");
            else
                show_error("Party with id = $party_id doesn't exist.");
        }

        $party_date = new DateTime($party->date, college()->timezone);
        if ($user->can_checkin($party)) {
            $user->checkin($party);

            if ($this->is_ajax()) {
                $response['party_summary_view'] = load_view('party_summary_view', array(
                                                                                       'user' => $user,
                                                                                       'party' => $party,
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
                    'url' => serverchannel()->url($channel_id),
                    'frequency' => 10,
                );
            }

        }
        elseif ($user->reason() == REASON_ALREADY_ATTENDED_PARTY) {
            $other_party = $user->get_attended_party($party_date);

            if ($other_party->id == $party->id)
                $message = "You have already checked into {$party->place->name}.";
            else
                $message = "You have already checked into {$other_party->place->name}, so you can't checkin to {$party->place->name}.";
        }
        else {
            $message = get_reason_message($user->reason());
        }

        if ($this->is_ajax()) {
            $response['message'] = isset($message) ? $message : '';
            $this->json($response);
        }
        else {
            set_message($message);
            redirect('/');
        }

    }

    function smile()
    {
        $user = current_user();

        $party_id = post('party_id');
        $receiver_id = post('receiver_id');

        $receiver = user($receiver_id);

        if (!$user->can_smile_at($receiver->id, $party_id)) {
            show_error("Smile denied.");
        }

        $user->smile_at($receiver_id, $party_id);
        set_message("Smiled at $receiver->full_name");
        $this->jsaction->HighlightSmilesLeft(3000);

        redirect("party/$party_id");
    }

    function mutual_friends($target_id)
    {
        $user = current_user();
        $target = user($target_id);

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


    function invite()
    {
        if (!logged_in())
            show_error('Not logged in.');

        $friend_facebook_id = post('friend_facebook_id');
        if (empty($friend_facebook_id)) {
            set_message('You must select a friend to invite.');
            redirect('dashboard');
        }

        $user_id = current_user()->id;
        $rows = $this->db->from('friends')
                ->where('user_id', $user_id)
                ->where('friend_facebook_id', $friend_facebook_id)
                ->get()->result();

        if (empty($rows)) {
            set_message("No such friend.");
            redirect('dashboard');
        }

        $friend = $rows[0];
        set_message("Here we would send an invite to $friend->friend_full_name (facebook id = $friend->friend_facebook_id)");
        redirect('dashboard');
    }

    function ping()
    {
        $is_active = intval(post('isActive'));
        current_user()->ping_online($is_active);
        $this->json(array(
                        'success' => TRUE,
                        'is_active' => $is_active,
                        'post' => post(),
                    ));
    }

    function ping_leaving()
    {
        current_user()->ping_offline();
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

}
