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

    function export()
    {
        $ci =& get_instance();

        $all_columns = array('id' => TRUE, 'user_id' => TRUE, 'action' => TRUE, 'time' => TRUE);
        $all_data_points = array();
        
        $rows = $ci->db->from('user_log')->get()->result();
        foreach ($rows as $row) {
            $data_point = array();
            $row->data = @unserialize($row->data);
            
            foreach ($row as $col => $val) {
                $all_columns[$col] = TRUE;
                $data_point[$col] = $val;
            }

            foreach ($row->data as $k => $v) {
                $all_columns[$k] = TRUE;
                $data_point[$k] = $v;
            }

            unset($data_point['data']);

            $all_data_points[] = $data_point;
        }

        unset($all_columns['data']);
        
        return array(
            'columns' => $all_columns,
            'rows' => $all_data_points,
        );
    }

}
