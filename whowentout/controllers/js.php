<?php

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

        if (is_array(post('user_ids'))) {
            $user_ids = post('user_ids');
    
            foreach ($user_ids as $user_id) {
                $user = user($user_id);
                $response['users'][$user_id] = $user->to_array();
                $response['users'][$user_id]['is_online'] = $user->is_online_to(current_user());
            }
        }
        
        $response['users'][ current_user()->id ] = current_user()->to_array(TRUE);
        $response['users'][ current_user()->id ]['is_online'] = current_user()->is_online_to( current_user() );

        $response['channels'] = array(
            'current_user' => array(
                'id' => 'user_159',
                'url' => serverchannel()->url('user_159'),
            ),
        );
        if ( is_array(post('party_ids')) ) {
            $party_ids = post('party_ids');
            foreach ($party_ids as $party_id) {
                $party = party($party_id);
                if ($party) {
                    $channel_id = 'party_' . $party->id;
                    $response['channels'][$channel_id] = array(
                        'id' => $channel_id,
                        'url' => serverchannel()->url($channel_id),
                        'frequency' => 10,
                    );
                }
            }
        }

        $response['request'] = post();
        $response['success'] = TRUE;
        $this->json($response);
    }

    function user($user_id)
    {
        //@TODO: enforce permissions for whether the user can view the info of the user
        $user = user($user_id);

        if (!$user) {
            $this->json(array(
                             'success' => FALSE,
                             'error' => "User doesn't exist.",
                        ));
        }
        else {
            $user_data = $user->to_array($user->is_current_user());
            $user_data['is_online'] = $user->is_online_to(current_user());
            $this->json(array(
                             'success' => TRUE,
                             'user' => $user_data,
                        ));
        }
    }

}
