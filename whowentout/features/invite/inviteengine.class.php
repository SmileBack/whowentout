<?php

class InviteEngine
{

    /* @var $database Database */
    private $database;

    /* @var $invites DatabaseTable */
    private $invites;

    /* @var $clock Clock */
    private $clock;

    function __construct(Database $database, Clock $clock)
    {
        $this->database = $database;
        $this->clock = $clock;

        $this->invites = $this->database->table('invites');
    }

    private $event_invites = array();
    private function load_event_cache_if_missing($event)
    {
        if (!isset($this->event_invites[$event->id]))
            $this->load_event_cache($event);
    }

    private function load_event_cache($event)
    {
        $this->event_invites[$event->id] = $this->invites->where('event_id', $event->id)->to_array();
    }

    private function clear_event_cache($event)
    {
        unset($this->event_invites[$event->id]);
    }

    function send_invite($event, $sender, $receiver)
    {
        // already been invited so don't do it
        if ($this->is_invited($event, $receiver))
            return;

        $invite = array(
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'event_id' => $event->id,
            'created_at' => $this->clock->get_time(),
        );
        $invite = $this->invites->create_row($invite);
        $this->clear_event_cache($event);

        app()->trigger('event_invite_sent', array(
            'invite' => $invite,
        ));
    }

    function invite_is_sent($event, $sender, $receiver)
    {
        $this->load_event_cache_if_missing($event);

        foreach ($this->event_invites[$event->id] as $invite)
            if ($invite->sender_id == $sender->id && $invite->receiver_id == $receiver->id)
                return true;

        return false;
    }

    function has_sent_invites($event, $sender)
    {
        $this->load_event_cache_if_missing($event);

        foreach ($this->event_invites[$event->id] as $invite)
            if ($invite->sender_id == $sender->id)
                return true;

        return false;
    }

    function destroy_invite($event, $sender, $receiver)
    {
        $this->invites->where('event_id', $event->id)
                ->where('sender_id', $sender->id)
                ->where('receiver_id', $receiver->id)
                ->destroy();

        $this->clear_event_cache($event);
    }

    /**
     * Returns whether $user was invited to $event.
     *
     * @param $event
     * @param $user
     * @return bool
     */
    function is_invited($event, $user)
    {
        $this->load_event_cache_if_missing($event);

        foreach ($this->event_invites[$event->id] as $invite)
            if ($invite->receiver_id == $user->id)
                return true;

        return false;
    }

    /**
     * @param $event
     * @param $receiver
     * @return DatabaseRow|null
     */
    function get_invite_sender($event, $receiver)
    {
        $invite = $this->invites->where('event_id', $event->id)
                                ->where('receiver_id', $receiver->id)
                                ->first();

        return $invite ? $invite->sender : null;
    }

}
