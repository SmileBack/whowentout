<?php

function init_presence_events()
{
    $ci =& get_instance();
    $ci->presence->when('user_came_online', function($e) use($ci)
        {
            $ci->event->raise('user_came_online', array(
                                                       'user' => user($e->user_id),
                                                  ));
        });

    $ci->presence->when('user_went_offline', function($e) use($ci)
        {
            $ci->event->raise('user_went_offline', array(
                                                        'user' => user($e->user_id),
                                                   ));
        });
}
