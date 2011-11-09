<?php

class EmailNotificationsPlugin extends Plugin
{

    function __construct($name = NULL)
    {
        $this->name = $name;
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
        $subject = "A {$e->sender->gender_word} from {$e->party->place->name} has smiled at you";
        $body = r('smile_received_email', array(
                                                                    'sender' => $e->sender,
                                                                    'receiver' => $e->receiver,
                                                                    'party' => $e->party,
                                                                    'date' => $e->party->college->get_time(),
                                                               ), TRUE);
        job_call_async('send_email', $e->receiver->id, $subject, $body);
    }

    /**
     * Occurs when $sender smiles *back* at $e->receiver.
     *
     * @param XSmileMatch $e->match
     */
    function on_smile_match($e)
    {
        $first_user = $e->match->first_user;
        $second_user = $e->match->second_user;

        // Send email to the sender
        $subject = "You and {$second_user->full_name} have smiled at each other";
        $body = r('match_notification_email', array(
                                                                       'sender' => $second_user,
                                                                       'receiver' => $first_user,
                                                                       'party' => $e->match->second_smile->party,
                                                                  ), TRUE);
        job_call_async('send_email', $first_user->id, $subject, $body);

        // Send email to the receiver
        $subject = "You and $first_user->full_name have smiled at each other";
        $body = r('match_notification_email', array(
                                                                       'sender' => $first_user,
                                                                       'receiver' => $second_user,
                                                                       'party' => $e->match->first_smile->party,
                                                                  ), TRUE);
        job_call_async('send_email', $second_user->id, $subject, $body);
    }

    function on_party_invite_sent($e)
    {
        $sender = $e->sender;
        $receiver = $e->receiver;
        $party = $e->party;

        $user = array('email' => $receiver->email, 'full_name' => $receiver->full_name);
        $subject = "Someone who was with you at {$party->place->name} wants you to check in";

        $vars = array('full_name' => $receiver->full_name, 'party' => $party);

        $body = r('party_invite_email', $vars, TRUE);
        
        $user['email'] = 'vendiddy@gmail.com';
        job_call_async('send_email', $user, $subject, $body);
    }

}
