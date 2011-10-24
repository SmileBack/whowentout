<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CI_Chat
{

    private $ci;
    private $version;
    private $last_query;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

    function send($sender_id, $receiver_id, $message_body, $type = 'normal')
    {
        if (empty($message_body))
            return;

        $sender = user($sender_id);
        $receiver = user($receiver_id);
        $this->db->insert('chat_messages', array(
                                                'type' => $type,
                                                'sender_id' => $sender->id,
                                                'receiver_id' => $receiver->id,
                                                'message' => $message_body,
                                                'sent_at' => college()->get_clock()->get_time()->getTimestamp(),
                                           ));

        $message = $this->message($this->db->insert_id());

        $this->ci->event->raise('chat_sent', array(
                                                  'source' => $sender,
                                                  'sender' => $sender,
                                                  'receiver' => $receiver,
                                                  'message' => $message,
                                                  'version' => $this->version,
                                             ));
        $this->ci->event->raise('chat_received', array(
                                                      'source' => $receiver,
                                                      'sender' => $sender,
                                                      'receiver' => $receiver,
                                                      'message' => $message,
                                                      'version' => $this->version,
                                                 ));
    }

    function message($id)
    {
        return $this->db->from('chat_messages')
                ->where('id', $id)
                ->get()->row();
    }

    function messages($user_id)
    {
        $one_week_ago = college()->get_clock()->get_time()->modify('-1 week')->getTimestamp();
        $user = user($user_id);

        $query = "SELECT * FROM chat_messages WHERE (sender_id = ? OR receiver_id = ?)
                    AND sent_at > ?
                ORDER BY id ASC";

        $messages = $this->db->query($query, array($user->id, $user->id, $one_week_ago))->result();

        return $messages;
    }

    function chatted_with_user_ids($from)
    {
        $ids = array();
        $from = user($from);

        $rows = $this->db->select('sender_id')
                ->distinct()
                ->from('chat_messages')
                ->where('receiver_id', $from->id)
                ->get()->result();

        foreach ($rows as $row) {
            $ids[] = $row->sender_id;
        }

        $rows = $this->db->select('receiver_id')
                ->distinct()
                ->from('chat_messages')
                ->where('sender_id', $from->id)
                ->get()->result();

        foreach ($rows as $row) {
            $ids[] = $row->receiver_id;
        }

        return array_unique($ids);
    }

    function mark_as_read($by, $from)
    {
        $from = user($from);
        $by = user($by);

        $this->db->where('receiver_id', $by->id)
                ->where('sender_id', $from->id)
                ->update('chat_messages', array('is_read' => 1));

        $this->last_query = $this->db->last_query();
    }

}
