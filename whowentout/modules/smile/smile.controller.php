<?php

class Smile extends MY_Controller
{
    
    function send()
    {
        $this->require_login();
        
        $sender = current_user();

        $party_id = post('party_id');
        $receiver_id = post('receiver_id');

        $party = XParty::get($party_id);
        $receiver = XUser::get($receiver_id);

        if (!$party)
            show_error("Party doesn't exist.");
        if (!$receiver)
            show_error("Recipient doesn't exist.");

        $smile_permission = new SmilePermission();
        if ( ! $smile_permission->check($sender, $receiver, $party) )
            show_error("Smile denied.");

        $smile_engine = new SmileEngine();
        $smile_engine->send_smile($sender, $receiver, $party);

        set_message("Smiled at $receiver->full_name");
        $this->jsaction->HighlightSmilesLeft(3000);

        redirect("party/$party->id");
    }
    
}
