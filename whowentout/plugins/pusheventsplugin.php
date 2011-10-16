<?php

class PushEventsPlugin extends CI_Plugin
{

    private $ci;

    function __construct()
    {
        $this->ci =& get_instance();
    }

    function on_chat_sent($e)
    {
        $channel = $this->user_channel($e->sender->id);
        $this->ci->event->broadcast($channel, 'chat_sent', array(
                                                                'sender' => $e->sender->to_array(),
                                                                'receiver' => $e->receiver->to_array(),
                                                                'message' => $e->message,
                                                           ));
    }

    function on_chat_received($e)
    {
        $channel = $this->user_channel($e->receiver->id);
        $this->ci->event->broadcast($channel, 'chat_received', array(
                                                                    'sender' => $e->sender->to_array(),
                                                                    'receiver' => $e->receiver->to_array(),
                                                                    'message' => $e->message,
                                                               ));
    }

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
        $channel = $this->party_channel($e->party->id);
        $this->ci->event->broadcast($channel, 'checkin', array(
                                                              'user' => $e->user->to_array(),
                                                              'insert_positions' => $e->party->attendee_insert_positions($e->user),
                                                              'party_attendee_view' => load_view('party_attendee_view', array(
                                                                                                                             'party' => $e->party,
                                                                                                                             'attendee' => $e->user,
                                                                                                                        )),
                                                         ));
    }

    //$e->user just came online
    function on_user_came_online($e)
    {
        //notify all other users within the college...
        $college = $e->user->college;
        $user = $e->user->to_array();
        foreach ($this->user_ids_online_to($e->user) as $user_id) {
            $channel = $this->user_channel($user_id);
            $this->ci->event->broadcast($channel, 'user_came_online', array(
                                                                           'user' => $user,
                                                                      ));
        }
    }

    function on_user_went_offline($e)
    {
        //notify all other users within the college...
        $college = $e->user->college;
        $user = $e->user->to_array();
        foreach ($this->ci->presence->get_online_users_ids() as $user_id) {
            $channel = $this->user_channel($user_id);
            $this->ci->event->broadcast($channel, 'user_went_offline', array(
                                                                            'user' => $user,
                                                                       ));
        }
    }

    //$e->user just came online
    function on_user_became_idle($e)
    {
        //notify all other users within the college...
        $college = $e->user->college;
        $user = $e->user->to_array();
        foreach ($this->user_ids_online_to($e->user) as $user_id) {
            $channel = $this->user_channel($user_id);
            $this->ci->event->broadcast($channel, 'user_became_idle', array(
                                                                           'user' => $user,
                                                                      ));
        }
    }

    function on_user_became_active($e)
    {
        //notify all other users within the college...
        $college = $e->user->college;
        $user = $e->user->to_array();
        foreach ($this->user_ids_online_to($e->user) as $user_id) {
            $channel = $this->user_channel($user_id);
            $this->ci->event->broadcast($channel, 'user_became_active', array(
                                                                             'user' => $user,
                                                                        ));
        }
    }

    /**
     * Occurs when a $e->sender smiles at $e->receiver.
     *
     * @param XUser $e->sender
     * @param XUser $e->receiver
     * @param XSmile $e->smile
     * @param XParty $e->party
     */
    function on_smile_sent($e)
    {
        $channel = $this->user_channel($e->smile->receiver->id);

        $party_notices_view = load_view('party_notices_view', array(
                                                                   'user' => $e->smile->receiver,
                                                                   'party' => $e->smile->party,
                                                              ));

        $this->ci->event->broadcast($channel, 'smile_received', array(
                                                                     'party' => $e->smile->party->to_array(),
                                                                     'party_notices_view' => $party_notices_view,
                                                                ));
    }

    /**
     * Occurs when $sender smiles *back* at $e->receiver.
     *
     * @param XSmileMatch $e->match
     */
    function on_smile_match($e)
    {
        $channel = $this->user_channel($e->match->second_smile->receiver->id);

        $party_notices_view = load_view('party_notices_view', array(
                                                                   'user' => $e->match->second_smile->receiver,
                                                                   'party' => $e->match->second_smile->party,
                                                              ));
        $this->ci->event->broadcast($channel, 'smile_match', array(
                                                                  'party' => $e->match->second_smile->party->to_array(),
                                                                  'party_notices_view' => $party_notices_view,
                                                             ));
    }

    function on_time_faked($e)
    {
        foreach ($this->ci->presence->get_online_user_ids() as $user_id) {
            $channel = $this->user_channel($user_id);
            $this->ci->event->broadcast($channel, 'time_faked', array(
                                                                     'fake_time' => $e->fake_time,
                                                                     'real_time' => $e->real_time,
                                                                ));
        }
    }

    function on_notification_sent($e)
    {
        $channel = $this->user_channel($e->user->id);
        $this->ci->event->broadcast($channel, 'notification', array(
                                                                   'notification' => $e->notification,
                                                              ));
    }

    // $e->user
    // $e->visibility
    function on_user_changed_visibility($e)
    {
        $channel = $this->user_channel($e->user->id);
        $this->ci->event->broadcast($channel, 'user_changed_visibility', array(
                                                                              'visibility' => $e->visibility,
                                                                         ));
    }

    private function user_channel($user_id)
    {
        return 'private-user_' . $user_id;
    }

    private function party_channel($party_id)
    {
        return 'private-party_' . $party_id;
    }

    private function user_ids_online_to($user)
    {
        $ids = array();
        foreach ($this->ci->presence->get_online_user_ids() as $id) {
            if ($user->is_online_to($id))
                $ids[] = $id;
        }
        return $ids;
    }

}
