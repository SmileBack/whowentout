<?php

define('PRESENCE_STATUS_OFFLINE', 0);
define('PRESENCE_STATUS_ONLINE', 1);
define('PRESENCE_STATUS_IDLE', 1);

class Presence
{

    private $ci;
    private $status_changed = FALSE;
    private $event_handlers = array();

    function when($event_type, $event_handler)
    {
        $this->event_handlers[$event_type][] = $event_handler;
    }

    function raise_event($event_type, $event_data)
    {
        $event = $this->cast_event($event_data);
        if (isset($this->event_handlers[$event_type])) {
            foreach ($this->event_handlers[$event_type] as $event_handler) {
                $event_handler($event);
            }
        }
    }

    private function cast_event($event_data)
    {
        return (object)$event_data;
    }

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    function is_online($user_id)
    {
        $status = $this->get_user_status($user_id);
        return $status->value == PRESENCE_STATUS_ONLINE;
    }

    function mark_online($user_id)
    {
        $this->set_user_status($user_id, PRESENCE_STATUS_ONLINE);

        if ($this->status_changed) {
            $this->raise_event('user_came_online', array(
                                                        'user_id' => $user_id,
                                                   ));
        }
    }

    function mark_offline($user_id)
    {
        $this->set_user_status($user_id, PRESENCE_STATUS_OFFLINE);

        if ($this->status_changed) {
            $this->raise_event('user_went_offline', array(
                                                         'user_id' => $user_id,
                                                    ));
        }
    }

    function get_online_user_ids()
    {
        $rows = $this->db->select('user_id')
                         ->from('user_statuses')
                         ->where('value', PRESENCE_STATUS_ONLINE)
                         ->get()->result();

        $online_user_ids = array();
        foreach ($rows as $row) {
            $online_user_ids[] = $row->user_id;
        }
        return $online_user_ids;
    }

    private function get_user_status($user_id)
    {
        $rows = $this->db->from('user_statuses')
                ->where('user_id', $user_id)
                ->get()->result();

        if (empty($rows)) {
            $this->db->insert('user_statuses', array(
                                                    'user_id' => $user_id,
                                                    'value' => PRESENCE_STATUS_OFFLINE,
                                               ));

            $rows = $this->db->from('user_statuses')
                    ->where('user_id', $user_id)
                    ->get()->result();
        }

        return $rows[0];
    }

    private function set_user_status($user_id, $user_status)
    {
        $initial_status_value = $this->get_user_status($user_id)->value;

        $this->db->where('user_id', $user_id);
        $this->db->update('user_statuses', array('value' => $user_status));

        $final_status_value = $this->get_user_status($user_id)->value;
        $this->status_changed = ($final_status_value != $initial_status_value);
    }

}
