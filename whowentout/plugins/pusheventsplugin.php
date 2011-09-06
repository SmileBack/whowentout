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
        serverinbox()->push($source, $this->ci->event->version());
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
        serverinbox()->push($source, $this->ci->event->version());
    }

    /**
     * Occurs when a $e->user checks into a $e->party.
     * @param XUser $e->user
     * @param XParty $e->party
     */
    function on_checkin($e)
    {
    }

    function on_user_came_online($e)
    {
    }

    function on_user_went_offline($e)
    {
    }

}
