<?php

class AdminDestroyPlace extends Action
{
    function execute($place_id)
    {
        auth()->require_admin();

        app()->database()->table('places')->destroy_row($place_id);
        flash::message('Destroyed place.');
        redirect('admin/places');
    }
}
