<?php

class OnlineOfflinePlugin
{

    function on_page_load($e) {
        if (logged_in()) {
            current_user()->ping_server();
        }
    }

}