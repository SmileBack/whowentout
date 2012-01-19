<?php

class UpdatePlaceAction extends Action
{

    function execute($place_id)
    {
        auth()->require_admin();

        $place_attributes = $_POST['place'];
        $place = app()->database()->table('places')->row($place_id);
        redirect('admin_places');
    }

}
