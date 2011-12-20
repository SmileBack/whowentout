<?php

class InviteEngine
{

    /* @var $database Database */
    private $database;

    /* @var $invites DatabaseTable */
    private $invites;

    /* @var $clock Clock */
    private $clock;

    /* @var $checkin_engine CheckinEngine */
    private $checkin_engine;

    function __construct(Database $database, Clock $clock, CheckinEngine $checkin_engine)
    {
        $this->database = $database;
        $this->clock = $clock;
        $this->checkin_engine = $checkin_engine;

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
        return $this->invites
                ->where('event_id', $event->id)
                ->where('receiver_id', $user->id)
                ->count() > 0;
    }

    /**
     * @param $event
     * @param $user
     * @return bool
     */
    function is_going_to_event($event, $user)
    {
        return $this->checkin_engine->user_has_checked_into_event($user, $event);
    }

}
