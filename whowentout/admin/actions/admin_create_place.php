<?php

class AdminCreatePlace extends Action
{
    function execute()
    {
        auth()->require_admin();

        $place_attributes = $_POST['place'];
        $place = app()->database()->table('places')->create_row($place_attributes);

        flash::message("Created place $place->name.");

        redirect('admin/places');
    }
}
