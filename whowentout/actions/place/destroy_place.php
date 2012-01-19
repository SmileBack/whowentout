<?php

class DestroyPlaceAction extends Action
{
    function execute($place_id)
    {
        auth()->require_admin();

        app()->database()->table('places')->destroy_row($place_id);
        redirect('admin_places');
    }
}