<?php

class InviteContest
{

    /* @var $database Database */
    private $database;

    /* @var $clock Clock */
    private $clock;

    /* @var DateTime $date */
    private $date;

    function __construct(Database $database, Clock $clock, DateTime $date)
    {
        $this->database = $database;
        $this->clock = $clock;

        $this->date = $date;
    }

    public static function is_contest_date(DateTime $date)
    {
        $day_of_week = $date->format('l');
        return in_array($day_of_week, array(
            'Thursday',
            'Friday',
            'Saturday',
        ));
    }

    function has_ended()
    {
        return $this->clock->get_time() > $this->get_end_time();
    }

    /**
     * @static
     * @param $event
     * @return DateTime
     */
    function get_end_time()
    {
        $date = clone $this->date;
        $date->setTime(12 + 9, 0, 0);
        return $date;
    }

    /**
     * @param DateTime $date
     * @return InviteLeaderBoardItem|null
     */
    function get_leader()
    {
        $items = $this->get_items($this->date);

        if (empty($items))
            return null;
        else
            return $items[0];
    }

    function get_eligible_events()
    {
        return $this->database->table('events')->where('date', $this->date)
                                               ->where('place.type', array('bar', 'club'));
    }

    /**
     * @param DateTime $date
     * @return InviteLeaderBoardItem[]
     */
    function get_items()
    {
        $date = $this->date;

        $user_invites = array();

        $cutoff = $this->get_end_time();

        $invites = $this->database->table('invites')
                                  ->where('event.date', $date)
                                  ->where('status', 'accepted');

        foreach ($invites as $invite) {
            if ($invite->accepted_at > $cutoff) // expired invite
                continue;

            if (!isset($user_invites[$invite->sender->id]))
                $user_invites[$invite->sender->id] = new InviteLeaderBoardItem($invite->sender);

            $user_invites[$invite->sender->id]->invites[] = $invite;
            $user_invites[$invite->sender->id]->score++;
        }

        usort($user_invites, function($a, $b) {
            return $b->score - $a->score;
        });

        return $user_invites;
    }

}

