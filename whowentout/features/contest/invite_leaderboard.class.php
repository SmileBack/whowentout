<?php

class InviteLeaderboard
{

    /* @var $database Database */
    private $database;

    function __construct(Database $database)
    {
        $this->database = $database;
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

