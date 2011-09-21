<?php

class NotificationsPlugin
{

    private $ci;

    function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * Occurs when a $e->sender smiles at $e->receiver.
     *
     * @param XUser $e->sender
     * @param XUser $e->receiver
     * @param XSmile $e->smile
     * @param XParty $e->party
     */
    function on_smile_sent($e)
    {
        $message = "A {$e->sender->gender_word} from {$e->party->place->name} has smiled at you.";
        $this->ci->notification->send($e->smile->receiver, $message);
    }

    /**
     * Occurs when $sender smiles *back* at $e->receiver.
     *
     * @param XSmileMatch $e->match
     */
    function on_smile_match($e)
    {
        $ci =& get_instance();

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