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
        $source = 'user_' . $e->sender->id;
        $this->ci->event->store('chat_sent', array(
                                                  'source' => $source,
                                                  'sender' => $e->sender->to_array(),
                                                  'receiver' => $e->receiver->to_array(),
                                                  'message' => $e->message,
                                             ));
        serverchannel()->push($source, $this->ci->event->version());
    }

    function on_chat_received($e)
    {
        $source = 'user_' . $e->receiver->id;
        $this->ci->event->store('chat_received', array(
                                                      'source' => $source,
                                                      'sender' => $e->sender->to_array(),
                                                      'receiver' => $e->receiver->to_array(),
                                                      'message' => $e->message,
                                                 ));
        serverchannel()->push($source, $this->ci->event->version());
    }

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
        $source = 'party_' . $e->party->id;
        $this->ci->event->store('checkin', array(
                                                'source' => $source,
                                                'user' => $e->user->to_array(),
                                                'insert_positions' => $e->party->attendee_insert_positions($e->user),
                                                'party_attendee_view' => load_view('party_attendee_view', array(
                                                                                                               'party' => $e->party,
                                                                                                               'attendee' => $e->user,
                                                                                                          )),
                                           ));
        serverchannel()->push($source, $this->ci->event->version());
    }

    function on_user_came_online($e)
    {
    }

    function on_user_went_offline($e)
    {
    }

}
