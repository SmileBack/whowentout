<?php

class Entourage_Display extends Display
{

    /* @var $entourage_engine EntourageEngine */
    private $entourage_engine;

    function process()
    {
        $this->user = auth()->current_user();
        $this->entourage_engine = build('entourage_engine');
        $this->sent_entourage_requests = $this->entourage_engine->get_pending_outgoing_requests($this->user);
    }

}