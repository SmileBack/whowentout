<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends MY_Controller
{

    function messages()
    {
        $this->load->library('chat');

        $messages = $this->chat->messages(current_user());

        $users = array();
        foreach ($messages as $message) {
            $sender = user($message->sender_id);
            $users[$sender->id] = $sender->to_array( $sender->is_current_user() );
            $receiver = user($message->receiver_id);
            $users[$receiver->id] = $receiver->to_array( $receiver->is_current_user() );
        }

        $response = array(
            'success' => TRUE,
            'messages' => $messages,
            'users' => $users,
        );

        $this->json($response);
    }

    function send()
    {
        $this->load->library('chat');

        $from = current_user();
        $to = user(post('to'));
        $message = post('message');

        $this->chat->send($from, $to, $message);
        $response = array(
            'success' => TRUE,
        );

        $this->json($response);
    }

    function mark_read()
    {
        $this->load->library('chat');

        $from = user(post('from'));

        $this->chat->mark_as_read(current_user(), $from);

        $this->json_success();
    }

    function save_chatbar_state()
    {
        $user = current_user();
        $state = post('chatbar_state');
        $user->chatbar_state = json_encode($state);
        $user->save();

        $this->json_success();
    }

}
