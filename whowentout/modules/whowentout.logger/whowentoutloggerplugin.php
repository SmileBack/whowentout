<?php

class WhoWentOutLoggerPlugin extends Plugin
{

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
    }

    function on_checkin()
    {
    }

    function on_page_view($e)
    {
    }

    function on_picture_view($e)
    {
    }

    private function save_to_log(XUser $user, DateTime $time, $action, $data = array())
    {

    }

}
