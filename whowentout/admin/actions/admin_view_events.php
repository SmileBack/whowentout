<?php

class AdminViewEventsAction extends Action
{
    function execute()
    {
        auth()->require_admin();

        print r::page(array(
                           'content' => r::admin_events(array(
                                                       'events' => app()->database()->table('events'),
                                                       'places' => app()->database()->table('places'),
                                                  )),
                      ));
    }
}
