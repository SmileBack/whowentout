<?php

class AdminViewEventsAction extends Action
{
    function execute()
    {
        auth()->require_admin();

        print r::admin_page(array(
            'content' => r::admin_events(array(
                'events' => app()->database()->table('events')->order_by('date', 'desc'),
                'places' => app()->database()->table('places')->order_by('name'),
            )),
        ));
    }
}
