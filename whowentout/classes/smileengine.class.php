<?php

class SmileEngine
{

    private $db;
    private $smiles_allowed_per_party = 3;

    private $smile_cache = array();

    function __construct()
    {
        $this->init_db();
    }

    function get_who_user_smiled_at($user, $party)
    {
        if ( ! $this->who_user_smiled_at_cache_isset($user, $party))
            $this->update_who_user_smiled_at_cache($user, $party);

        return $this->smile_cache[$user->id][$party->id];
    }

    private function who_user_smiled_at_cache_isset($user, $party)
    {
        return isset($this->smile_cache[$user->id])
            && isset($this->smile_cache[$user->id][$party->id]);
    }

    private function update_who_user_smiled_at_cache($user, $party)
    {
        $query = $this->db->select('receiver_id AS id')
                ->from('smiles')
                ->where('sender_id', $user->id)
                ->where('party_id', $party->id);

        $who_user_smiled_at = XObject::load_objects('XUser', $query);
        $this->smile_cache[$user->id][$party->id] = $who_user_smiled_at;
    }

    function smile_was_sent($sender, $receiver, $party)
    {
        $who_sender_smiled_at = $this->get_who_user_smiled_at($sender, $party);
        return in_array($receiver, $who_sender_smiled_at);
    }

    function get_smile_matches_for_user($user, $party)
    {
        $query = $this->db->select('smile_matches.id AS id')
                ->from('smile_matches')
                ->join('smiles', 'second_smile_id = smiles.id')
                ->where('smiles.party_id', $party->id)
                ->where('first_user_id', $user->id)
                ->or_where('smiles.party_id', $party->id)
                ->where('second_user_id', $user->id);

        $smile_match_objects = XObject::load_objects('XSmileMatch', $query);
        $matches = array();

        foreach ($smile_match_objects as $match_object) {
            if ($match_object->first_user == $user)
                $matches[] = $match_object->second_user;
            else
                $matches[] = $match_object->first_user;
        }

        return $matches;
    }

    function get_num_smiles_sent($user, $party)
    {
        return count($this->get_who_user_smiled_at($user, $party));
    }

    function get_num_smiles_received($user, $party)
    {
        return $this->db->from('smiles')
                ->where('receiver_id', $user->id)
                ->where('party_id', $party->id)
                ->count_all_results();
    }

    function get_num_smiles_left_to_give($user, $party)
    {
        return $this->smiles_allowed_per_party - $this->get_num_smiles_sent($user, $party);
    }

    function send_smile($sender, $receiver, $party)
    {
        $smile = XSmile::create(array(
                                     'sender_id' => $sender->id,
                                     'receiver_id' => $receiver->id,
                                     'party_id' => $party->id,
                                     'smile_time' => current_time()->format('Y-m-d H:i:s'),
                                ));

        $this->update_who_user_smiled_at_cache($sender, $party);

        $first_smile = $this->get_most_recent_smile_sent($receiver, $sender);

        if ($first_smile) {
            if ($this->smiles_are_used_in_previous_match(array($smile->id, $first_smile->id))) {
                //smiles have already been used in a previous match, so no match should be created
            }
            else {
                $match = XSmileMatch::create(array(
                                                  'first_smile_id' => $first_smile->id,
                                                  'second_smile_id' => $smile->id,
                                                  'first_user_id' => $first_smile->sender->id,
                                                  'second_user_id' => $smile->sender->id,
                                                  'created_at' => current_time()->format('Y-m-d H:i:s'),
                                             ));
            }
        }

        return $smile;
    }

    function get_most_recent_smile_sent($from_user, $to_user)
    {
        $row = $this->db->from('smiles')
                ->where('sender_id', $from_user->id)
                ->where('receiver_id', $to_user->id)
                ->order_by('id', 'desc')
                ->limit(1)
                ->get()->row();
        return empty($row) ? NULL : XSmile::get($row->id);
    }

    /**
     * @param array $smile_ids
     *   An array of ids.
     * @return bool
     *   Whether a match already occured with any of the provided smiles.
     */
    private function smiles_are_used_in_previous_match(array $smile_ids)
    {
        $query = $this->db->from('smile_matches');
        foreach ($smile_ids as $id) {
            $query->or_where('first_smile_id', $id)
                    ->or_where('second_smile_id', $id);
        }
        return $query->count_all_results() > 0;
    }

    private function init_db()
    {
        $ci =& get_instance();
        $this->db = $ci->db;
    }

}
