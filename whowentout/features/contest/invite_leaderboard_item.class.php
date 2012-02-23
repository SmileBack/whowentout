<?php

class InviteLeaderboardItem
{
    public $user;
    public $invites;
    public $score = 0;

    function __construct($user, $invites = array())
    {
        $this->user = $user;
        $this->invites = $invites;
    }

}