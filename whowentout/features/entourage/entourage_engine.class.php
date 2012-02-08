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

        $request = $this->database->table('entourage_requests')->create_or_update_row(array(
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ));

        $this->clear_cache($sender);

        app()->trigger('entourage_request_sent', array(
            'request' => $request,
        ));

        return $request;
    }

    private $outgoing_request_cache = array();
    private $entourage_cache = array();

    private function load_cache($sender)
    {
        $this->outgoing_request_cache[$sender->id] = $this->database->table('entourage_requests')
                ->where('sender_id', $sender->id)->to_array();

        $this->entourage_cache[$sender->id] = $this->get_entourage($sender);
    }

    private function load_cache_if_missing($sender)
    {
        if ($this->cache_is_missing($sender)) {
            $this->load_cache($sender);
        }
    }

    private function cache_is_missing($sender)
    {
        return !isset($this->outgoing_request_cache[$sender->id])
                || !isset($this->entourage_cache[$sender->id]);
    }

    private function clear_cache($sender)
    {
        unset($this->outgoing_request_cache[$sender->id]);
        unset($this->entourage_cache[$sender->id]);
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

        $this->clear_cache($request->sender);
        $this->clear_cache($request->receiver);
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

    function get_entourage($user)
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

    function in_entourage($user, $friend)
    {
        $this->load_cache_if_missing($user);
        foreach ($this->entourage_cache[$user->id] as $cur)
            if ($cur == $friend)
                return true;

        return false;
    }

    function get_request($id)
    {
        return $this->database->table('entourage_requests')->row($id);
    }

    function get_request_between($sender, $receiver)
    {
        $this->load_cache_if_missing($sender);
        foreach ($this->outgoing_request_cache[$sender->id] as $request) {
            if ($request->receiver_id == $receiver->id)
                return $request;
        }
        return null;
    }

}
