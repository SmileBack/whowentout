<?php

class InviteLeaderboard
{

    /* @var $database Database */
    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
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

    /**
     * @param DateTime $date
     * @return InviteLeaderBoardItem|null
     */
    function get_leader(DateTime $date)
    {
        $items = $this->get_items($date);

        if (empty($items))
            return null;
        else
            return $items[0];
    }

    /**
     * @param DateTime $date
     * @return InviteLeaderBoardItem[]
     */
    function get_items(DateTime $date)
    {
        $user_invites = array();

        $invites = $this->database->table('invites')
                                  ->where('event.date', $date)
                                  ->where('status', 'accepted');

        foreach ($invites as $invite) {
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

