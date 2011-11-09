<?php

class UserEventLogger
{

    function __construct()
    {
    }

    function log(XUser $user, XDateTime $time, $action, $data = array())
    {
        $time->setTimezone(new DateTimeZone('UTC'));

        $ci =& get_instance();
        $ci->db->insert('user_log', array(
                                         'user_id' => $user->id,
                                         'time' => $time->getMySqlTimestamp(),
                                         'action' => $action,
                                         'data' => serialize($data),
                                    ));
    }

}
