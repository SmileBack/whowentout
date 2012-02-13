<?php

class UpdatePlaceAction extends Action
{

    function execute($place_id)
    {
        auth()->require_admin();

        $place_attributes = $_POST['place'];
        $place = to::place($place_id);

        redirect('admin/places');
    }

}
