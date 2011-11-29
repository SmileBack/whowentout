<?php

class Venue_Controller extends Controller
{

    function index()
    {
        $this->show_page('venues', array(
                                        'venues' => app()->venues(),
                                   ));
    }

    function create()
    {
        $attributes = app()->input->post('venue');
        $venue = app()->venues()->create($attributes);

        app()->notify_user("Created the venue $venue->name.");

        $this->show_page('venues', array(
                                        'venues' => app()->venues(),
                                   ));
    }

    function update($venue_id)
    {
        $attributes = app()->input()->post('venue');
        $venue = app()->venues()->find($venue_id);
        $venue->set($attributes);
        $venue->save();

        $this->show_page('venues', array(
                                        'venues' => app()->venues(),
                                   ));
    }

    function destroy($venue_id)
    {
        app()->venues()->destroy($venue_id);

        $this->show_page('venues', array(
                                        'venues' => app()->venues(),
                                   ));
    }

}
