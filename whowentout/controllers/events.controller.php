<?php

class Events_Controller extends Controller
{

    function create()
    {
        $attributes = app()->input->post('event');
        app()->events()->create($attributes);
    }

    function update($venue_id)
    {
        $attributes = app()->input()->post('event');
        $event = app()->events()->find($venue_id);
        $event->set($attributes);
        $event->save();
    }
    
    function destroy($venue_id)
    {
        app()->events()->destroy($venue_id);
    }

}
