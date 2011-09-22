<?php

class PushEventsPlugin
{

    private $ci;

    function __construct()
    {
        $this->ci =& get_instance();
    }

    function on_chat_sent($e)
    {
        $channel = 'user_' . $e->sender->id;
        $this->broadcast_event($channel, 'chat_sent', array(
                                                           'sender' => $e->sender->to_array(),
                                                           'receiver' => $e->receiver->to_array(),
                                                           'message' => $e->message,
                                                      ));
    }

    function on_chat_received($e)
    {
        $channel = 'user_' . $e->receiver->id;
        $this->broadcast_event($channel, 'chat_received', array(
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
        $channel = 'party_' . $e->party->id;
        $this->broadcast_event($channel, 'checkin', array(
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
            $channel = 'user_' . $user_id;
            $this->broadcast_event($channel, 'user_came_online', array(
                                                                      'user' => $user,
                                                                 ));
        }
    }

    function on_user_went_offline($e)
    {
        //notify all other users within the college...
        $college = $e->user->college;
        $user = $e->user->to_array();
        foreach ($college->get_online_users_ids() as $user_id) {
            $channel = 'user_' . $user_id;
            $this->broadcast_event($channel, 'user_went_offline', array(
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
            $channel = 'user_' . $user_id;
            $this->broadcast_event($channel, 'user_became_idle', array(
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
            $channel = 'user_' . $user_id;
            $this->broadcast_event($channel, 'user_became_active', array(
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
        $channel = 'user_' . $e->smile->receiver->id;

        $party_notices_view = load_view('party_notices_view', array(
                                                                   'user' => $e->smile->receiver,
                                                                   'party' => $e->smile->party,
                                                              ));

        $this->broadcast_event($channel, 'smile_received', array(
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
        $channel = 'user_' . $e->match->second_smile->receiver->id;
        $party_notices_view = load_view('party_notices_view', array(
                                                                   'user' => $e->match->second_smile->receiver,
                                                                   'party' => $e->match->second_smile->party,
                                                              ));
        $this->broadcast_event($channel, 'smile_match', array(
                                                             'party' => $e->match->second_smile->party->to_array(),
                                                             'party_notices_view' => $party_notices_view,
                                                        ));
    }

    function on_user_changed_visibility($e)
    {
        $channel = 'user_' . $e->user->id;
        $this->broadcast_event($channel, 'user_changed_visibility', array(
                                                                         'user' => $e->user->to_array(),
                                                                         'visibility' => $e->user->visible_to,
                                                                    ));
    }

    function on_time_faked($e)
    {
        foreach (college()->get_online_users_ids() as $user_id) {
            $channel = 'user_' . $user_id;
            $this->broadcast_event($channel, 'time_faked', array(
                                                                'fake_time' => $e->fake_time,
                                                                'real_time' => $e->real_time,
                                                           ));
        }
    }

    function on_notification_sent($e)
    {
        $channel = 'user_' . $e->user->id;
        $this->broadcast_event($channel, 'notification', array(
                                                              'notification' => $e->notification,
                                                         ));
    }

    private function user_ids_online_to($user)
    {
        $ids = array();
        foreach (college()->get_online_users_ids() as $id) {
            if ($user->is_online_to($id))
                $ids[] = $id;
        }
        return $ids;
    }

    private function broadcast_event($channel, $event_name, $event_data = array())
    {
        $event_data['channel'] = $channel;
        $this->ci->event->store($event_name, $event_data);
        $this->alert_channel($channel);
    }

    private function alert_channel($channel)
    {
        serverchannel()->push($channel, $this->ci->event->version($channel));
    }

}
