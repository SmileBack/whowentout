<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Notification
{

    private $ci;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    function send($user, $message, $type = 'normal')
    {
        if (empty($message))
            return;

        $user = user($user);
        $this->db->insert('notifications', array(
                                                'type' => $type,
                                                'user_id' => $user->id,
                                                'message' => $message,
                                                'sent_at' => college()->get_clock()->get_time()->getTimestamp(),
                                           ));

        $notification = $this->get($this->db->insert_id());

        raise_event('notification_sent', array(
                                      'user' => $user,
                                      'notification' => $notification,
                                 ));
    }

    function get($id)
    {
        return $this->db->from('notifications')
                ->where('id', $id)
                ->get()->row();
    }

    function unread_notifications($user)
    {
        $user = user($user);
        $query = $this->db->from('notifications')
                          ->where('user_id', $user->id)
                          ->where('is_read', 0);
        return $query->get()->result();
    }

    function mark_as_read($notification_id)
    {
        $this->db->update('notifications', array('is_read' => 1), array('id' => $notification_id));
    }
    
}
