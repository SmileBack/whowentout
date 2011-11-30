<?php

class Events_Controller extends Controller
{
    
    function index()
    {
        $current_user = app()->current_user();

        $city = $current_user->city;
        $events = $city->events->order('created', 'desc')->limit(10);

        app()->show_page('events', array(
                                        'events' => $events,
                                   ));
    }

    function view($event_id)
    {
        $event = app()->events->find($event_id);

        app()->show_page('event', array(
                                       'event' => $event,
                                  ));
    }

    function edit($event_id)
    {
        $event = app()->events->find($event_id);

        app()->show_page('event_edit', array(
                                            'event' => $event,
                                       ));
    }

    function create()
    {
        $attributes = app()->input->post('event');
        $event = app()->events->create($attributes);

        app()->show_page('event_calendar', array(
                                                'date' => $event->date,
                                           ));
    }

    function update($event_id)
    {
        $attributes = app()->input()->post('event');
        $event = app()->events->find($event_id);
        $event->set($attributes);
        $event->save();

        app()->show_page('event_calendar', array(
                                                'date' => $event->date,
                                           ));
    }

    function destroy($event_id)
    {
        $event_date = app()->events->find($event_id)->date;
        app()->events->destroy($event_id);

        app()->show_page('event_calendar', array(
                                                'date' => $event_date,
                                           ));
    }

    function admins_add($event_id, $user_id)
    {
        $event = app()->events->find($event_id);
        $user = app()->users->find($user_id);

        $event->admins->add($user);
    }

    function admins_remove($event_id, $user_id)
    {
        $event = app()->events->find($event_id);
        $user = app()->users->find($user_id);

        $event->admins->remove($user);
    }

    function promoters_add($event_id, $user_id)
    {
        $event = app()->events->find($event_id);
        $user = app()->users->find($user_id);

        $event->promoters->add($user);
    }

    function promoters_remove($event_id, $user_id)
    {
        $event = app()->promoters->find($event_id);
        $user = app()->users->find($user_id);

        $event->promoters->remove($user);
    }

}
