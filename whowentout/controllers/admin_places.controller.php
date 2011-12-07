<?php

class Admin_Places_Controller extends Controller
{
    
    function index()
    {
        print r::page(array(
                           'content' => r::places(array(
                                                       'places' => app()->database()->table('places'),
                                                  )),
                      ));
    }

    function create()
    {
        $place_attributes = $_POST['place'];
        app()->database()->table('places')->create_row($place_attributes);
        redirect('admin_places');
    }

    function update($place_id)
    {
        $place_attributes = $_POST['place'];
        $place = app()->database()->table('places')->row($place_id);
        redirect('admin_places');
    }

    function destroy($place_id)
    {
        app()->database()->table('places')->destroy_row($place_id);
        redirect('admin_places');
    }

}
