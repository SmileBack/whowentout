<?php

class SmilePlugin
{

    /**
     * Occurs when a $e->sender smiles at $e->receiver.
     * @param XUser $e->sender
     * @param XUser $e->receiver
     * @param XSmile $e->smile
     * @param XParty $e->party
     */
    function on_smile_sent($e)
    {
        // check if $e->receiver has smiled at $e->sender before
        $first_smile = $e->receiver->most_recent_smile_to($e->sender);
        if ($first_smile) {
            // a match occured
            $second_smile = $e->smile;

            $match = XSmileMatch::create(array(
                                              'first_smile_id' => $first_smile->id,
                                              'second_smile_id' => $second_smile->id,
                                              'first_user_id' => $first_smile->sender->id,
                                              'second_user_id' => $second_smile->sender->id,
                                              'created_at' => current_time()->format('Y-m-d H:i:s'),
                                         ));

            raise_event('smile_match', array(
                                            'match' => $match,
                                       ));
        }
    }
    
}
