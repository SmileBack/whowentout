<?php

class Checkin_Event extends Event
{
    /* @var $user XUser */
    public $user;

    /* @var $party XParty */
    public $party;

    /* @var $previos_party XParty */
    public $previous_party;
}
