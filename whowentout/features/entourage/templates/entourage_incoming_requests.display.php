<?php

/**
 * @property $user
 * @property $requests
 */
class Entourage_Incoming_Requests extends Display
{

    /* @var $entourage_engine EntourageEngine */
    private $entourage_engine;

    function process()
    {
        $this->user = auth()->current_user();
        $this->entourage_engine = build('entourage_engine');
        $this->requests = $this->entourage_engine->get_pending_requests($this->user);
    }
}
