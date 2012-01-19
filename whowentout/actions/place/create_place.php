<?php

class CreatePlaceAction extends Action
{
    function execute()
    {
        auth()->require_admin();

        $place_attributes = $_POST['place'];
        app()->database()->table('places')->create_row($place_attributes);
        redirect('admin_places');
    }
}
