<?php

class UpdateProfilePlugin extends Plugin
{
    function on_after_request($e)
    {
        if (string_starts_with('jobs/', $e->url)) // don't want infinite recursion !
            return;

        if (!auth()->logged_in())
            return;
    }
}
