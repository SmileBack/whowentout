<?php

class Admin_Places_Controller extends Controller
{
    
    function index()
    {
        auth()->require_admin();

        print r::page(array(
                           'content' => r::admin_places(array(
                                                       'places' => app()->database()->table('places'),
                                                  )),
                      ));
    }

    function create()
    {
        auth()->require_admin();

        $place_attributes = $_POST['place'];
        app()->database()->table('places')->create_row($place_attributes);
        redirect('admin_places');
    }

    function update($place_id)
    {
        auth()->require_admin();

        $place_attributes = $_POST['place'];
        $place = app()->database()->table('places')->row($place_id);
        redirect('admin_places');
    }

    function destroy($place_id)
    {
        auth()->require_admin();

        app()->database()->table('places')->destroy_row($place_id);
        redirect('admin_places');
    }

}
