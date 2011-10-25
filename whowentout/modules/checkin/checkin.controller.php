<?php

class Checkin extends MY_Controller
{
    
    function create()
    {
        $this->require_login();

        $party = $party = XParty::get(post('party_id'));
        $user = current_user();

        if (!$party)
            $this->json_failure("Party doesn't exist.");

        if (!$user)
            $this->json_failure("User doesn't exist.");

        $checkin_engine = new CheckinEngine();
        $smile_engine = new SmileEngine();

        $checkin_permission = new CheckinPermission();
        $can_checkin = $checkin_permission->check($user, $party);

        $response = array();

        if ($can_checkin) {
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

            $this->jsaction->SetText('.num_checkins', ' (' . $checkin_engine->get_num_checkins_for_user( $user ) . ')');
            
            $this->json($response);
        }
        else {
            $this->json_failure("You can't checkin.");
        }
    }

}
