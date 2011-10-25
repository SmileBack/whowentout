<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Js extends MY_Controller
{

    function app()
    {
        $response = array();

        if (!logged_in())
            $this->json(array('success' => FALSE, 'error' => 'Not logged in.'));

        $response['application']['currentUserID'] = current_user()->id;
        if (current_user()->college) {
            $user = current_user();
            $response['college'] = $user->college->to_array();
        }

        $response['request'] = post();

        $this->load_users($response);
        $this->load_channels($response);
        $this->load_presence_channels($response);

        $response['success'] = TRUE;
        $this->json($response);
    }

    function user($user_id)
    {
        //@TODO: enforce permissions for whether the user can view the info of the user
        $user = XUser::get($user_id);

        if (!$user) {
            $this->json(array(
                             'success' => FALSE,
                             'error' => "User doesn't exist.",
                        ));
        }
        else {
            $user_data = $user->to_array($user->is_current_user());
            $this->json(array(
                             'success' => TRUE,
                             'user' => $user_data,
                        ));
        }
    }

    function users()
    {
        $response = array();
        $this->load_users($response);

        $response['success'] = TRUE;
        $this->json($response);
    }

    private function load_users(&$response)
    {
        if (is_array(post('user_ids'))) {
            $user_ids = post('user_ids');

            foreach ($user_ids as $user_id) {
                $user = XUser::get($user_id);
                $response['users'][$user_id] = $user->to_array();
            }
        }

        $response['users'][current_user()->id] = current_user()->to_array(TRUE);
    }

    private function load_channels(&$response)
    {
        $response['channels'] = array();

        if (logged_in()) {
            $current_user_channel = 'private-user_' . current_user()->id;
            $response['channels']['current_user'] = array(
                'type' => serverchannel()->type(),
                'id' => $current_user_channel,
            );
        }

        if (is_array(post('party_ids'))) {
            $party_ids = post('party_ids');
            foreach ($party_ids as $party_id) {
                $party = XParty::get($party_id);
                if ($party) {
                    $channel_id = 'private-party_' . $party->id;
                    $response['channels'][$channel_id] = array(
                        'type' => serverchannel()->type(),
                        'id' => $channel_id,
                    );
                }
            }
        }
    }

    private function load_presence_channels(&$response)
    {
        if (!logged_in())
            return;

        $response['presence_channels'] = array();
        $checkin_engine = new CheckinEngine();
        $recently_attended_parties = $checkin_engine->get_recently_attended_parties_for_user( current_user() );
        foreach ($recently_attended_parties as $party) {
            $response['presence_channels'][] = 'presence-party_' . $party->id;
        }
    }

}
