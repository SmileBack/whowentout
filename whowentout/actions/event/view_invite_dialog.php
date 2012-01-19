<?php

class ViewInviteDialogAction extends Action
{

    /* @var $db Database */
    private $db;

    function __construct()
    {
        $this->db = db();
    }

    function execute($event_id)
    {
        $event = $this->db->table('events')->row($event_id);

        PageFlow::start(new InvitePageFlow($event->id));

        print r::event_invite(array(
            'event' => $event,
        ));
    }
}
