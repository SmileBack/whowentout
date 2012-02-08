<?php

class AdminEditEventAction extends Action
{
    function execute($event_id)
    {
        $event = db()->table('events')->row($event_id);
        print r::admin_event_edit(array(
            'event' => $event,
        ));
    }
}
