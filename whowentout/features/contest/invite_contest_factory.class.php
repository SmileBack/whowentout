<?php

class InviteContestFactory
{

    /* @var $database Database */
    private $database;

    /* @var $clock Clock */
    private $clock;

    function __construct(Database $database, Clock $clock)
    {
        $this->database = $database;
        $this->clock = $clock;
    }

    function build(DateTime $date)
    {
        return new InviteContest($this->database, $this->clock, $date);
    }

}
