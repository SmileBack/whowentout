<?php

class Venue_Controller extends Controller
{

    function create()
    {
        $attributes = app()->input->post('venue');
        app()->venues()->create($attributes);
    }

    function update($venue_id)
    {
        $attributes = app()->input()->post('venue');
        $venue = app()->venues()->find($venue_id);
        $venue->set($attributes);
        $venue->save();
    }

    function destroy($venue_id)
    {
        app()->venues()->destroy($venue_id);
    }

}
