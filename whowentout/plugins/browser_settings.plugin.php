<?php

class BrowserSettingsPlugin extends Plugin
{

    function on_before_request($e)
    {
        browser::$settings['currentUser'] = to::json(auth()->current_user());
    }

}
