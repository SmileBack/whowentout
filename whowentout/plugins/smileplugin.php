<?php

class SmilePlugin extends CI_Plugin
{

    function __construct()
    {
        $this->ci =& get_instance();
        $this->db = $this->ci->db;
    }

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

            if ($this->smiles_in_previous_match(array($first_smile->id, $second_smile->id)))
            {
                return;
            }


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

    /**
     * @param array $smile_ids
     *   An array of ids.
     * @return bool
     *   Whether a match already occured with any of the provided smiles.
     */
    private function smiles_in_previous_match(array $smile_ids)
    {
        $query = $this->db->from('smile_matches');
        foreach ($smile_ids as $id) {
            $query->or_where('first_smile_id', $id)
                    ->or_where('second_smile_id', $id);
        }
        return $query->count_all_results() > 0;
    }

}
