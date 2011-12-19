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
        $invite = array(
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'event_id' => $event->id,
            'created_at' => $this->clock->get_time(),
        );
        $this->invites->create_row($invite);
    }

    /**
     * Returns whether $user was invited to $event.
     *
     * @param $event
     * @param $user
     * @return bool
     */
    function was_invited($event, $user)
    {
        return $this->invites
                ->where('event_id', $event->id)
                ->where('receiver_id', $user->id)
                ->count() > 0;
    }

    function is_going_to_event($event, $user)
    {
        return false;
    }

}