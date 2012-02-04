<?php

class Received_Entourage_Requests_Section_Display extends Display
{
    function process()
    {
        /* @var $entourage_engine EntourageEngine */
        $entourage_engine = build('entourage_engine');
        $this->received_entourage_requests = $entourage_engine->get_pending_requests($this->user);
    }
}
