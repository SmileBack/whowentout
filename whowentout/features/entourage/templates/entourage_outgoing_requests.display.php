<?php

class Entourage_Outgoing_Requests extends Display
{
    function process()
    {
        $this->user = auth()->current_user();
        /* @var $entourage_engine EntourageEngine */
        $this->entourage_engine = build('entourage_engine');
        $this->requests = $this->entourage_engine->get_pending_outgoing_requests($this->user);
    }
}
