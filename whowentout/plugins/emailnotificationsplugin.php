<?php

class EmailNotificationsPlugin
{

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
        $ci =& get_instance();

        $subject = "A {$e->sender->gender_word} from {$e->party->place->name} has smiled at you.";
        $body = $ci->load->view('emails/smile_received_email', array(
                                                                    'sender' => $e->sender,
                                                                    'receiver' => $e->receiver,
                                                                    'party' => $e->party,
                                                                    'date' => current_time(TRUE),
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
        $ci =& get_instance();

        $first_user = $e->match->first_user;
        $second_user = $e->match->second_user;
        
        // Send email to the sender
        $subject = "You and {$second_user->full_name} have smiled at each other.";
        $body = $ci->load->view('emails/match_notification_view', array(
                                                                       'sender' => $second_user,
                                                                       'receiver' => $first_user,
                                                                       'party' => $e->match->second_smile->party,
                                                                  ), TRUE);
        job_call_async('send_email', $first_user->id, $subject, $body);

        // Send email to the receiver
        $subject = "You and $first_user->full_name have smiled at each other.";
        $body = $ci->load->view('emails/match_notification_view', array(
                                                                       'sender' => $first_user,
                                                                       'receiver' => $second_user,
                                                                       'party' => $e->match->first_smile->party,
                                                                  ), TRUE);
        job_call_async('send_email', $second_user->id, $subject, $body);
    }

}
