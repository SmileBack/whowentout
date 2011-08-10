<?php

function chat_send_message($from, $to, $message) {
  $sender = user($from);
  $receiver = user($to);
  $m = array(
    'sender_id' => $sender->id,
    'receiver_id' => $receiver->id,
    'sent_at' => current_time()->getTimestamp(),
    'message' => $message,
  );
  ci()->db->insert('chat_messages', $m);
}

function chat_new_messages($from, $to) {
  $sender = user($from);
  $receiver = user($to);
  
  $messages = ci()->db->from('chat_messages')
                  ->where(array('sender_id' => $sender->id, 'receiver_id' => $receiver->id))
                  ->or_where(array('sender_id' => $receiver->id, 'receiver_id' => $sender->id))
                  ->order_by('id', 'asc')
                  ->get()->result();
  return $messages;
}
