<?php

class Entourage_Invite_Display extends Display
{

    function process()
    {
        $this->entourage_engine = build('entourage_engine');

        $this->current_user = auth()->current_user();
        $this->friends = $this->current_user->friends->where('networks.id', $this->get_allowed_networks())
                                                     ->order_by('first_name');
    }

    private function get_allowed_networks()
    {
        $config = (array)build('allowed_networks');
    }

}
