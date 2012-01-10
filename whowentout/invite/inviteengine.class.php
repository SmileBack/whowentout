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
        $this->invites->create_row($invite);
    }

    function invite_is_sent($event, $sender, $receiver)
    {
        return $this->invites->where('event_id', $event->id)
                             ->where('sender_id', $sender->id)
                             ->where('receiver_id', $receiver->id)
                             ->count() > 0;
    }

    function has_sent_invites($event, $sender)
    {
        return $this->invites->where('event_id', $event->id)
                             ->where('sender_id', $sender->id)
                             ->count() > 0;
    }

    function destroy_invite($event, $sender, $receiver)
    {
        $this->invites->where('event_id', $event->id)
                ->where('sender_id', $sender->id)
                ->where('receiver_id', $receiver->id)
                ->destroy();
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
        return $this->invites->where('event_id', $event->id)
                ->where('receiver_id', $user->id)
                ->count() > 0;
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
