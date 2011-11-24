<?php

class Venue_Controller extends Controller
{

    function create()
    {
        $attributes = app()->input->post('venue');
        if (app()->can('create_venue', $attributes)) {
            app()->venues()->create($attributes);
        }
    }

    function update($venue_id)
    {
        $attributes = app()->input()->post('venue');
        if (app()->can('update_venue', $venue_id, $attributes)) {
            $venue = app()->venues()->fetch($venue_id);
            $venue->set($attributes);
            $venue->save();
        }
    }

    function destroy($venue_id)
    {
        if (app()->can('destroy_venue', $venue_id)) {
            app()->venues()->destroy($venue_id);
        }
    }

}
