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
        $this->ci->event->store('chat_sent', array(
                                                  'channel' => $channel,
                                                  'sender' => $e->sender->to_array(),
                                                  'receiver' => $e->receiver->to_array(),
                                                  'message' => $e->message,
                                             ));
        serverchannel()->push($channel, $this->ci->event->version());
    }

    function on_chat_received($e)
    {
        $channel = 'user_' . $e->receiver->id;
        $this->ci->event->store('chat_received', array(
                                                      'channel' => $channel,
                                                      'sender' => $e->sender->to_array(),
                                                      'receiver' => $e->receiver->to_array(),
                                                      'message' => $e->message,
                                                 ));
        serverchannel()->push($channel, $this->ci->event->version());
    }

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
        $channel = 'party_' . $e->party->id;
        $this->ci->event->store('checkin', array(
                                                'channel' => $channel,
                                                'user' => $e->user->to_array(),
                                                'insert_positions' => $e->party->attendee_insert_positions($e->user),
                                                'party_attendee_view' => load_view('party_attendee_view', array(
                                                                                                               'party' => $e->party,
                                                                                                               'attendee' => $e->user,
                                                                                                          )),
                                           ));
        serverchannel()->push($channel, $this->ci->event->version());
    }

    function on_user_came_online($e)
    {
        //notify all other users within the college...
        $college = $e->user->college;
        $user = $e->user->to_array();
        foreach ($college->get_online_users_ids() as $user_id) {
            //don't alert yourself...
            if ($user_id == $e->user->id)
                continue;

            $channel = 'user_' . $user_id;
            $this->ci->event->store('user_came_online', array(
                                                             'channel' => $channel,
                                                             'user' => $user,
                                                        ));
            serverchannel()->push($channel, $this->ci->event->version());
        }
    }

    function on_user_went_offline($e)
    {
        //notify all other users within the college...
        $college = $e->user->college;
        $user = $e->user->to_array();
        foreach ($college->get_online_users_ids() as $user_id) {
            //don't alert yourself...
            if ($user_id == $e->user->id)
                continue;

            $channel = 'user_' . $user_id;
            $this->ci->event->store('user_went_offline', array(
                                                              'channel' => $channel,
                                                              'user' => $user,
                                                         ));
            serverchannel()->push($channel, $this->ci->event->version());
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
        $this->ci->event->store('smile_received', array(
                                                       'channel' => $channel,
                                                       'party' => $e->smile->party->to_array(),
                                                       'party_notices_view' => $party_notices_view,
                                                  ));
        serverchannel()->push($channel, $this->ci->event->version());
    }

    /**
     * Occurs when $sender smiles *back* at $e->receiver.
     *
     * @param XSmileMatch $e->match
     */
    function on_smile_match($e)
    {
        var_dump($e);
        $channel = 'user_' . $e->match->second_smile->receiver->id;
        $party_notices_view = load_view('party_notices_view', array(
                                                                   'user' => $e->match->second_smile->receiver,
                                                                   'party' => $e->match->second_smile->party,
                                                              ));
        $this->ci->event->store('smile_match', array(
                                                    'channel' => $channel,
                                                    'party' => $e->match->second_smile->party->to_array(),
                                                    'party_notices_view' => $party_notices_view,
                                               ));
        serverchannel()->push($channel, $this->ci->event->version());
    }

}
