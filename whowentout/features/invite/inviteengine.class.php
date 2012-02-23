<?php

class InviteEngine
{

    /* @var $database Database */
    private $database;

    /* @var $invites DatabaseTable */
    private $invites;

    /* @var $clock Clock */
    private $clock;

    /* @var $event_dispatcher EventDispathcer */
    private $event_dispatcher;

    function __construct(Database $database, Clock $clock, EventDispatcher $event_dispatcher)
    {
        $this->database = $database;
        $this->clock = $clock;
        $this->event_dispatcher = $event_dispatcher;

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
        if ($this->invite_is_sent($event, $sender, $receiver))
            return;

        $invite = array(
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'event_id' => $event->id,
            'created_at' => $this->clock->get_time(),
            'status' => 'pending',
        );
        $invite = $this->invites->create_row($invite);
        $this->clear_event_cache($event);

        $this->event_dispatcher->trigger('event_invites_sent', array(
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

    /**
     * @param $event
     * @param $sender
     * @param $receiver
     * @return DatabaseRow|null
     */
    function fetch_invite($event, $sender, $receiver)
    {
        return $this->invites->where('event_id', $event->id)
                             ->where('sender_id', $sender->id)
                             ->where('receiver_id', $receiver->id)
                             ->first();
    }

    function destroy_invite($event, $sender, $receiver)
    {
        $this->fetch_invite($event, $sender, $receiver);
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
        $senders = $this->get_invite_senders($event, $user);
        return count($senders) > 0;
    }

    /**
     * @param $event
     * @param $receiver
     * @return DatabaseRow[]
     */
    function get_invite_senders($event, $receiver)
    {
        $this->load_event_cache_if_missing($event);

        $senders = array();
        foreach ($this->event_invites[$event->id] as $invite) {
            if ($invite->receiver_id == $receiver->id)
                $senders[] = $invite->sender;
        }

        return $senders;
    }

}
