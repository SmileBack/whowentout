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
        //TODO: broadcast to all users to refresh their browser
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
    
}
