<?php

class Checkin extends MY_Controller
{

    function create()
    {
        $this->require_login();

        $party = XParty::get(post('party_id'));
        $user = current_user();

        if (!$party)
            $this->json_failure("Party doesn't exist.");

        if (!$user)
            $this->json_failure("User doesn't exist.");

        $checkin_engine = new CheckinEngine();
        $checkin_permission = new CheckinPermission();
        $can_checkin = $checkin_permission->check($user, $party);

        $response = array();

        if ($can_checkin) {
            $checkin_engine->checkin_user_to_party($user, $party);
            $this->update_party_group($user, $party);

            $response['party'] = $party->to_array();

            $this->json($response);
        }
        else {
            $this->update_party_group($user, $party);
            $this->json_failure("You can't checkin.");
        }
    }

    function update_party_group(XUser $user, XParty $party)
    {
        $party_group = new PartyGroup(college()->get_clock(), $party->date);
        
        $party_group_view = r('party_group', array(
                                               'user' => $user,
                                               'party_group' => $party_group,
                                             ));

        $this->jsaction->ReplaceHtml('.party_group_' . $party_group->get_date()->format('Ymd'), $party_group_view);
    }
    
}
