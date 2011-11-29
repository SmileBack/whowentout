<?php

class Guestlist_Controller extends Controller
{

    function entries_create($guestlist_id)
    {
        $attributes = app()->input->post('entry');
        $guestlist = app()->guestlists()->find($guestlist_id);
        $guestlist->entries->create($attributes);
    }

    function entries_destroy($guestlist_id, $entry_id)
    {
        $guestlist = app()->guestlists()->find($guestlist_id);
        $guestlist->entries->destroy($entry_id);
    }


}
