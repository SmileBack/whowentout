<?php

class Smile_Sent_Event extends Event
{
    /* @var $sender XUser */
    public $sender;
    /* @var $receiver XUser */
    public $receiver;
    /* @var $smile XSmile */
    public $smile;
    /* @var $party XParty */
    public $party;
}
