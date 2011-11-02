<?php

class PushEventsPlugin extends Plugin
{

    function on_chat_sent($e)
    {
        $channel = $this->user_channel($e->sender->id);
        $this->broadcast($channel, 'chat_sent', array(
                                                     'sender' => $e->sender->to_array(),
                                                     'receiver' => $e->receiver->to_array(),
                                                     'message' => $e->message,
                                                ));
    }

    function on_chat_received($e)
    {
        $channel = $this->user_channel($e->receiver->id);
        $this->broadcast($channel, 'chat_received', array(
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
        $this->broadcast($channel, 'checkin', array(
                                                   'user_id' => $e->user->id,
                                                   'party_id' => $e->party->id,
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

        $party_notices_view = r('party_notices', array(
                                                      'user' => $e->smile->receiver,
                                                      'party' => $e->smile->party,
                                                      'smile_engine' => new SmileEngine(),
                                                 ));

        $this->broadcast($channel, 'smile_received', array(
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

        $party_notices_view = r('party_notices', array(
                                                      'user' => $e->match->second_smile->receiver,
                                                      'party' => $e->match->second_smile->party,
                                                      'smile_engine' => new SmileEngine(),
                                                 ));
        $this->broadcast($channel, 'smile_match', array(
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
        $this->broadcast($channel, 'notification', array(
                                                        'notification' => $e->notification,
                                                   ));
    }

    // $e->user
    // $e->visibility
    function on_user_changed_visibility($e)
    {
        $channel = $this->user_channel($e->user->id);
        $this->broadcast($channel, 'user_changed_visibility', array(
                                                                   'visibility' => $e->visibility,
                                                              ));
    }

    function broadcast($channel, $event_name, $event_data = array())
    {
        $ci =& get_instance();

        $event_data['channel'] = $channel;
        $ci->db->insert('events', array(
                                             'type' => $event_name,
                                             'channel' => $channel,
                                             'data' => serialize($event_data),
                                        ));
        serverchannel()->trigger($channel, $event_name, $event_data);
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
