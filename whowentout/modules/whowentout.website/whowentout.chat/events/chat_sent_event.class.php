<?php

class Chat_Sent_Event extends Event
{
    /* @var $sender XUser */
    public $sender;

    /* @var $sender XUser */
    public $receiver;

    /* @var $message object */
    public $message;

    /* @var $version int */
    public $version;
}
