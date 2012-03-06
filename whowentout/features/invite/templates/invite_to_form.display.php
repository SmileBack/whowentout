<?php

class Invite_To_Form extends Display
{

    /**
     * @var InviteEngine
     */
    public $invite_engine;

    function process()
    {
        $this->invite_engine = build('invite_engine');

        $this->is_invited = $this->invite_engine->is_invited($this->event, $this->user);
    }
}
