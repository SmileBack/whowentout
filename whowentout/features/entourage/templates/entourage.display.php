<?php

class Entourage_Display extends Display
{

    /* @var $entourage_engine EntourageEngine */
    private $entourage_engine;

    function process()
    {
        $this->user = auth()->current_user();
        $this->entourage_engine = build('entourage_engine');

        $this->entourage = $this->entourage_engine->get_entourage_users($this->user);

        $this->received_entourage_requests = $this->entourage_engine->get_pending_requests($this->user);
        $this->sent_entourage_requests = $this->entourage_engine->get_pending_outgoing_requests($this->user);
    }
}