<?php

class AdminDestroyEventAction extends Action
{
    function execute($event_id)
    {
        auth()->require_admin();
        app()->database()->table('events')->destroy_row($event_id);
        redirect('admin/events');
        flash::message("Destroyed event with id = $event_id");
    }
}
