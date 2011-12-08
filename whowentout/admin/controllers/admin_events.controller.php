<?php

class Admin_Events_Controller extends Controller
{

    function index()
    {
        print r::page(array(
                           'content' => r::admin_events(array(
                                                       'events' => app()->database()->table('events'),
                                                       'places' => app()->database()->table('places'),
                                                  )),
                      ));
    }

    function create()
    {
        $place_attributes = $_POST['event'];
        $place_attributes['date'] = new DateTime($place_attributes['date']);
        app()->database()->table('events')->create_row($place_attributes);
        redirect('admin_events');
    }

    function destroy($event_id)
    {
        app()->database()->table('events')->destroy_row($event_id);
        redirect('admin_events');
    }

    private function places()
    {
        $places = array();
        foreach (app()->database()->table('places') as $place_id => $place) {
            $places[$place_id] = $place->name;
        }
        return $places;
    }
    
}
