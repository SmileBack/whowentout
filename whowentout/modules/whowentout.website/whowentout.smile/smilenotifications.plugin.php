<?php

class SmileNotificationsPlugin extends Plugin
{
    
    function on_smile_sent(Smile_Sent_Event $e)
    {
        $this->ci =& get_instance();
        
        //send notification to sender
        $sender_message = "You smiled at {$e->receiver->full_name} at {$e->party->place->name}";
        $this->ci->notification->send($e->smile->sender, $sender_message);

        //send notification to receiver
        $receiver_message = "A {$e->sender->gender_word} from {$e->party->place->name} has smiled at you.";
        $this->ci->notification->send($e->smile->receiver, $receiver_message);
    }

    function on_smile_match(Smile_Match_Event $e)
    {
        $this->ci =& get_instance();
        
        $first_user = $e->match->first_user;
        $second_user = $e->match->second_user;

        // Send email to the sender
        $first_message = "You and {$second_user->full_name} have smiled at each other.";
        $this->ci->notification->send($first_user, $first_message);

        // Send email to the receiver
        $second_message = "You and $first_user->full_name have smiled at each other.";
        $this->ci->notification->send($second_user, $second_message);
    }
    
}
