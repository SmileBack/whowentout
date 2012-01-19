<?php

class DestroyEventAction extends Action
{
    function execute($event_id)
    {
        auth()->require_admin();

        app()->database()->table('events')->destroy_row($event_id);
        redirect('admin_events');
    }
}
