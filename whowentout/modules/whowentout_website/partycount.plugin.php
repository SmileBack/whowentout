<?php

class PartyCountPlugin extends Plugin
{
    
    function on_checkin($e)
    {
        $this->update_num_checkins($e->user);
    }

    function on_before_controller_request($e)
    {
        $segments = explode('/', $e->uri);
        if ($segments[0] == 'party' || $segments[0] == 'dashboard' || $segments[0] == 'friends')
            $this->update_num_checkins(current_user());
    }

    private function update_num_checkins($user)
    {
        $ci =& get_instance();
        $checkin_engine = new CheckinEngine();
        $ci->jsaction->SetText('.num_checkins', ' (' . $checkin_engine->get_num_checkins_for_user( $user ) . ')');
    }

}
