<?php

class EntourageEngine
{

    /* @var $database Databse */
    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
    }

    function send_request($sender, $receiver)
    {
        $request = $this->get_request_between($receiver, $sender);
        if ($request && $request->status == 'accepted') // already sent a request that was accepted
            return;

        if ($request && $request->status == 'pending') {
            $this->accept_request($request);
            return;
        }

        $this->database->table('entourage_requests')->create_or_update_row(array(
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ));
    }

    function request_was_sent($sender, $receiver)
    {
        return $this->get_request_between($sender, $receiver) != null;
    }

    function accept_request($request)
    {
        /* @var $request DatabaseRow */
        $request->status = 'accepted';
        $request->save();

        $this->database->table('entourage')->create_row(array(
            'user_id' => $request->sender_id,
            'friend_id' => $request->receiver_id,
        ));

        $this->database->table('entourage')->create_row(array(
            'user_id' => $request->receiver_id,
            'friend_id' => $request->sender_id,
        ));
    }

    function ignore_request($request)
    {
        $request->status = 'ignored';
        $request->save();
    }

    function get_pending_requests($user)
    {
        return $this->database->table('entourage_requests')
                              ->where('receiver_id', $user->id)
                              ->where('status', 'pending')->to_array();
    }

    function get_pending_outgoing_requests($user)
    {
        return $this->database->table('entourage_requests')
                              ->where('sender_id', $user->id)
                              ->where('status', 'pending')->to_array();
    }

    function get_entourage_users($user)
    {
        return $this->database->table('entourage')
                              ->where('user_id', $user->id)
                              ->friend->to_array();
    }

    function get_entourage_count($user)
    {
        return $this->database->table('entourage')
                              ->where('user_id', $user->id)->count();
    }

    function get_pending_request_count($user)
    {
        return $this->database->table('entourage_requests')
                              ->where('receiver_id', $user->id)
                              ->where('status', 'pending')->count();
    }

    function in_entourage($user, $friend)
    {
        return $this->database->table('entourage')
                              ->where('user_id', $user->id)
                              ->where('friend_id', $friend->id)
                              ->count() > 0;
    }

    function get_request($id)
    {
        return $this->database->table('entourage_requests')->row($id);
    }

    function get_request_between($sender, $receiver)
    {
        return $this->database->table('entourage_requests')
                              ->where('sender_id', $sender->id)
                              ->where('receiver_id', $receiver->id)
                              ->first();
    }

}
