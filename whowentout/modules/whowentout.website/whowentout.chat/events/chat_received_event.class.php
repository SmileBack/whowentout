<?php

class Chat_Received_Event extends Event
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
